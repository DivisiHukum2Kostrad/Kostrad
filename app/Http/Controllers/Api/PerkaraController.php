<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PerkaraResource;
use App\Models\Perkara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerkaraController extends Controller
{
    /**
     * Display a listing of cases with filtering and pagination
     * 
     * @group Cases
     * @authenticated
     * 
     * @queryParam search string Search in case number, type, or description. Example: PKR-001
     * @queryParam status string Filter by status (Proses|Selesai). Example: Proses
     * @queryParam priority string Filter by priority (Low|Medium|High|Urgent). Example: High
     * @queryParam kategori int Filter by category ID. Example: 1
     * @queryParam deadline_status string Filter by deadline status (overdue|upcoming|no_deadline). Example: overdue
     * @queryParam assigned_to string Filter by assigned personnel. Example: John Doe
     * @queryParam per_page int Items per page (default: 15). Example: 20
     * @queryParam sort_by string Sort by field (created_at|deadline|priority|progress). Example: deadline
     * @queryParam sort_dir string Sort direction (asc|desc). Example: asc
     */
    public function index(Request $request)
    {
        $query = Perkara::with(['kategori']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_perkara', 'like', "%{$search}%")
                    ->orWhere('jenis_perkara', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter by deadline status
        if ($request->filled('deadline_status')) {
            switch ($request->deadline_status) {
                case 'overdue':
                    $query->overdue();
                    break;
                case 'upcoming':
                    $query->upcomingDeadline(7);
                    break;
                case 'no_deadline':
                    $query->whereNull('deadline');
                    break;
            }
        }

        // Filter by assigned person
        if ($request->filled('assigned_to') && $request->assigned_to !== 'all') {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        
        $allowedSorts = ['created_at', 'deadline', 'priority', 'progress', 'tanggal_perkara'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->latest();
        }

        $perPage = $request->get('per_page', 15);
        $perkaras = $query->paginate($perPage);

        return PerkaraResource::collection($perkaras);
    }

    /**
     * Store a newly created case
     * 
     * @group Cases
     * @authenticated
     */
    public function store(Request $request)
    {
        if (!$request->user()->hasPermission('manage_cases')) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to create cases.'
            ], 403);
        }

        $validated = $request->validate([
            'nomor_perkara' => 'required|unique:perkaras,nomor_perkara',
            'jenis_perkara' => 'required|string|max:255',
            'nama' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_perkara' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_masuk',
            'deadline' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status' => 'required|in:Proses,Selesai',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'progress' => 'nullable|integer|min:0|max:100',
            'estimated_days' => 'nullable|integer|min:1',
            'assigned_to' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        // Convert comma-separated tags to array
        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $validated['is_public'] = $request->boolean('is_public');

        $perkara = Perkara::create($validated);

        return response()->json([
            'message' => 'Case created successfully',
            'data' => new PerkaraResource($perkara->load('kategori')),
        ], 201);
    }

    /**
     * Display the specified case
     * 
     * @group Cases
     * @authenticated
     */
    public function show(Perkara $perkara)
    {
        $perkara->load(['kategori', 'dokumens.uploadedBy']);
        
        return new PerkaraResource($perkara);
    }

    /**
     * Update the specified case
     * 
     * @group Cases
     * @authenticated
     */
    public function update(Request $request, Perkara $perkara)
    {
        if (!$request->user()->hasPermission('manage_cases')) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to update cases.'
            ], 403);
        }

        $validated = $request->validate([
            'nomor_perkara' => 'required|unique:perkaras,nomor_perkara,' . $perkara->id,
            'jenis_perkara' => 'required|string|max:255',
            'nama' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_perkara' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_masuk',
            'deadline' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status' => 'required|in:Proses,Selesai',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'progress' => 'nullable|integer|min:0|max:100',
            'estimated_days' => 'nullable|integer|min:1',
            'assigned_to' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        // Convert comma-separated tags to array
        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $validated['is_public'] = $request->boolean('is_public');

        $perkara->update($validated);

        return response()->json([
            'message' => 'Case updated successfully',
            'data' => new PerkaraResource($perkara->fresh()->load('kategori')),
        ]);
    }

    /**
     * Remove the specified case
     * 
     * @group Cases
     * @authenticated
     */
    public function destroy(Request $request, Perkara $perkara)
    {
        if (!$request->user()->hasPermission('manage_cases')) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to delete cases.'
            ], 403);
        }

        // Delete associated file if exists
        if ($perkara->file_dokumentasi) {
            Storage::disk('public')->delete($perkara->file_dokumentasi);
        }

        $perkara->delete();

        return response()->json([
            'message' => 'Case deleted successfully',
        ]);
    }

    /**
     * Get case statistics
     * 
     * @group Cases
     * @authenticated
     */
    public function statistics()
    {
        return response()->json([
            'total' => Perkara::count(),
            'by_status' => [
                'proses' => Perkara::where('status', 'Proses')->count(),
                'selesai' => Perkara::where('status', 'Selesai')->count(),
            ],
            'by_priority' => [
                'urgent' => Perkara::where('priority', 'Urgent')->count(),
                'high' => Perkara::where('priority', 'High')->count(),
                'medium' => Perkara::where('priority', 'Medium')->count(),
                'low' => Perkara::where('priority', 'Low')->count(),
            ],
            'overdue' => Perkara::overdue()->count(),
            'upcoming_deadline' => Perkara::upcomingDeadline(7)->count(),
        ]);
    }
}
