<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perkara;
use App\Models\Kategori;
use App\Models\Personel;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PerkaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Perkara::with('kategori');

        // Advanced Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_perkara', 'like', "%{$search}%")
                  ->orWhere('jenis_perkara', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_masuk', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_masuk', '<=', $request->tanggal_sampai);
        }

        // Filter by public visibility
        if ($request->filled('is_public')) {
            $query->where('is_public', $request->is_public === '1');
        }

        // Filter by priority
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
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

        $perkaras = $query->paginate(15)->withQueryString();
        $kategoris = Kategori::all();

        // Get unique assigned names for filter
        $assignedUsers = Perkara::whereNotNull('assigned_to')
            ->distinct()
            ->pluck('assigned_to')
            ->sort();

        return view('admin.perkaras.index', compact('perkaras', 'kategoris', 'assignedUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $personels = Personel::all();
        $nomor_perkara = Perkara::generateNomorPerkara();

        return view('admin.perkaras.create', compact('kategoris', 'personels', 'nomor_perkara'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
            'file_dokumentasi' => 'nullable|file|mimes:pdf|max:5120',
        ], [
            'nomor_perkara.required' => 'Nomor perkara wajib diisi',
            'nomor_perkara.unique' => 'Nomor perkara sudah ada',
            'jenis_perkara.required' => 'Jenis perkara wajib diisi',
            'kategori_id.required' => 'Kategori wajib dipilih',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'priority.required' => 'Prioritas wajib dipilih',
            'file_dokumentasi.mimes' => 'File harus berformat PDF',
            'file_dokumentasi.max' => 'File maksimal 5MB',
        ]);

        // Handle file upload
        if ($request->hasFile('file_dokumentasi')) {
            $file = $request->file('file_dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('perkaras', $filename, 'public');
            $validated['file_dokumentasi'] = $path;
        }

        // Convert comma-separated tags to array
        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $validated['is_public'] = $request->boolean('is_public');

        $perkara = Perkara::create($validated);

        // Attach personels if provided
        if ($request->filled('personels')) {
            $notificationService = app(NotificationService::class);
            
            foreach ($request->personels as $personelId => $peran) {
                if ($peran) {
                    $perkara->personels()->attach($personelId, ['peran' => $peran]);
                    
                    // Send notification to assigned personel (if personel has user account)
                    $personel = Personel::find($personelId);
                    if ($personel && $personel->user_id) {
                        $user = \App\Models\User::find($personel->user_id);
                        if ($user) {
                            $notificationService->sendCaseAssigned($user, $perkara, Auth::user());
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.perkaras.index')
            ->with('success', 'Perkara berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Perkara $perkara)
    {
        $perkara->load(['kategori', 'personels', 'dokumens', 'riwayats.user', 'activityLogs.user']);

        return view('admin.perkaras.show', compact('perkara'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perkara $perkara)
    {
        $kategoris = Kategori::all();
        $personels = Personel::all();
        $perkara->load('personels');

        return view('admin.perkaras.edit', compact('perkara', 'kategoris', 'personels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perkara $perkara)
    {
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
            'file_dokumentasi' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('file_dokumentasi')) {
            // Delete old file
            if ($perkara->file_dokumentasi) {
                Storage::disk('public')->delete($perkara->file_dokumentasi);
            }

            $file = $request->file('file_dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('perkaras', $filename, 'public');
            $validated['file_dokumentasi'] = $path;
        }

        // Convert comma-separated tags to array
        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $validated['is_public'] = $request->boolean('is_public');

        // Check if status changed
        $oldStatus = $perkara->status;
        $statusChanged = $oldStatus !== $validated['status'];

        $perkara->update($validated);

        // Send status change notification
        if ($statusChanged) {
            $notificationService = app(NotificationService::class);
            
            // Notify all personels assigned to this case
            foreach ($perkara->personels as $personel) {
                if ($personel->user_id) {
                    $user = \App\Models\User::find($personel->user_id);
                    if ($user) {
                        $notificationService->sendStatusChanged($user, $perkara, $oldStatus, $validated['status'], Auth::user());
                    }
                }
            }
        }

        // Sync personels
        if ($request->filled('personels')) {
            $sync = [];
            foreach ($request->personels as $personelId => $peran) {
                if ($peran) {
                    $sync[$personelId] = ['peran' => $peran];
                }
            }
            $perkara->personels()->sync($sync);
        } else {
            $perkara->personels()->detach();
        }

        return redirect()->route('admin.perkaras.index')
            ->with('success', 'Perkara berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perkara $perkara)
    {
        // Delete file if exists
        if ($perkara->file_dokumentasi) {
            Storage::disk('public')->delete($perkara->file_dokumentasi);
        }

        $perkara->delete();

        return redirect()->route('admin.perkaras.index')
            ->with('success', 'Perkara berhasil dihapus!');
    }

    /**
     * Export perkaras to Excel (CSV format)
     */
    public function exportExcel(Request $request)
    {
        $query = Perkara::with('kategori');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_perkara', 'like', "%{$search}%")
                  ->orWhere('jenis_perkara', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_masuk', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_masuk', '<=', $request->tanggal_sampai);
        }

        $perkaras = $query->latest()->get();

        $filename = 'perkara_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($perkaras) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, [
                'Nomor Perkara',
                'Jenis Perkara',
                'Kategori',
                'Tanggal Masuk',
                'Tanggal Selesai',
                'Status',
                'Keterangan',
                'Publik'
            ]);

            // Data rows
            foreach ($perkaras as $perkara) {
                fputcsv($file, [
                    $perkara->nomor_perkara,
                    $perkara->jenis_perkara,
                    $perkara->kategori->nama ?? '-',
                    $perkara->tanggal_masuk->format('d/m/Y'),
                    $perkara->tanggal_selesai ? $perkara->tanggal_selesai->format('d/m/Y') : '-',
                    $perkara->status,
                    $perkara->keterangan ?? '-',
                    $perkara->is_public ? 'Ya' : 'Tidak'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export perkaras to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Perkara::with('kategori');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_perkara', 'like', "%{$search}%")
                  ->orWhere('jenis_perkara', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_masuk', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_masuk', '<=', $request->tanggal_sampai);
        }

        $perkaras = $query->latest()->get();

        $pdf = \PDF::loadView('admin.perkaras.pdf', compact('perkaras'));

        return $pdf->download('perkara_' . date('Y-m-d_His') . '.pdf');
    }
}
