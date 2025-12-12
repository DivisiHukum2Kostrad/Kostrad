<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenPerkara;
use App\Models\Perkara;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DokumenPerkaraController extends Controller
{
    /**
     * Display a listing of documents for a specific case
     */
    public function index(Request $request, $perkaraId = null)
    {
        $query = DokumenPerkara::with(['perkara', 'uploader']);

        if ($perkaraId) {
            $query->where('perkara_id', $perkaraId);
            $perkara = Perkara::findOrFail($perkaraId);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by jenis dokumen
        if ($request->filled('jenis_dokumen')) {
            $query->where('jenis_dokumen', $request->jenis_dokumen);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('nama_dokumen', 'like', '%' . $request->search . '%');
        }

        $documents = $query->latest()->paginate(15);

        return view('admin.documents.index', compact('documents', 'perkara'));
    }

    /**
     * Show the form for uploading new documents
     */
    public function create($perkaraId)
    {
        $perkara = Perkara::findOrFail($perkaraId);
        return view('admin.documents.create', compact('perkara'));
    }

    /**
     * Store multiple uploaded documents
     */
    public function store(Request $request, $perkaraId)
    {
        $validated = $request->validate([
            'files.*' => ['required', 'file', 'max:10240'], // 10MB max per file
            'jenis_dokumen' => ['required', 'string'],
            'category' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'is_public' => ['boolean'],
        ]);

        $perkara = Perkara::with('personels')->findOrFail($perkaraId);
        $uploadedCount = 0;
        $notificationService = app(NotificationService::class);
        $lastDocument = null;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) 
                    . '_' . time() . '_' . uniqid() 
                    . '.' . $file->getClientOriginalExtension();

                $filePath = $file->storeAs('documents/' . $perkaraId, $fileName, 'public');

                $document = DokumenPerkara::create([
                    'perkara_id' => $perkaraId,
                    'nama_dokumen' => $file->getClientOriginalName(),
                    'jenis_dokumen' => $validated['jenis_dokumen'],
                    'category' => $validated['category'],
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => auth()->id(),
                    'description' => $validated['description'] ?? null,
                    'is_public' => $request->boolean('is_public'),
                    'version' => 1,
                ]);

                $lastDocument = $document;
                $uploadedCount++;
            }

            // Send notification to all personels assigned to this case
            if ($lastDocument && $perkara->personels->isNotEmpty()) {
                foreach ($perkara->personels as $personel) {
                    if ($personel->user_id && $personel->user_id !== Auth::id()) {
                        $user = \App\Models\User::find($personel->user_id);
                        if ($user) {
                            $notificationService->sendDocumentUploaded($user, $lastDocument, $perkara, Auth::user());
                        }
                    }
                }
            }
        }

        return redirect()
            ->route('admin.perkaras.show', $perkaraId)
            ->with('success', "$uploadedCount dokumen berhasil diupload!");
    }

    /**
     * Display document details and versions
     */
    public function show(DokumenPerkara $document)
    {
        $document->load(['perkara', 'uploader', 'versions.uploader', 'parent']);
        
        return view('admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing document metadata
     */
    public function edit(DokumenPerkara $document)
    {
        return view('admin.documents.edit', compact('document'));
    }

    /**
     * Update document metadata
     */
    public function update(Request $request, DokumenPerkara $document)
    {
        $validated = $request->validate([
            'nama_dokumen' => ['required', 'string', 'max:255'],
            'jenis_dokumen' => ['required', 'string'],
            'category' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'is_public' => ['boolean'],
        ]);

        $document->update($validated);

        return redirect()
            ->route('admin.documents.show', $document)
            ->with('success', 'Metadata dokumen berhasil diperbarui!');
    }

    /**
     * Upload new version of existing document
     */
    public function uploadVersion(Request $request, DokumenPerkara $document)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('file');
        $fileName = Str::slug(pathinfo($document->nama_dokumen, PATHINFO_FILENAME)) 
            . '_v' . ($document->version + 1) . '_' . time() 
            . '.' . $file->getClientOriginalExtension();

        $filePath = $file->storeAs('documents/' . $document->perkara_id, $fileName, 'public');

        // Create new version
        $newVersion = DokumenPerkara::create([
            'perkara_id' => $document->perkara_id,
            'nama_dokumen' => $document->nama_dokumen,
            'jenis_dokumen' => $document->jenis_dokumen,
            'category' => $document->category,
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => auth()->id(),
            'description' => $document->description,
            'is_public' => $document->is_public,
            'version' => $document->version + 1,
            'parent_id' => $document->parent_id ?? $document->id,
        ]);

        return redirect()
            ->route('admin.documents.show', $newVersion)
            ->with('success', 'Versi baru dokumen berhasil diupload!');
    }

    /**
     * Download document and track download
     */
    public function download(DokumenPerkara $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        // Track download
        $document->trackDownload();

        return Storage::disk('public')->download($document->file_path, $document->nama_dokumen);
    }

    /**
     * Preview document
     */
    public function preview(DokumenPerkara $document)
    {
        if (!$document->is_previewable) {
            return redirect()->back()->with('error', 'File ini tidak dapat di-preview');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = Storage::disk('public')->path($document->file_path);
        $mimeType = $document->mime_type;

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->nama_dokumen . '"'
        ]);
    }

    /**
     * Delete document
     */
    public function destroy(DokumenPerkara $document)
    {
        // Check permission
        if (!auth()->user()->hasPermission('delete_cases')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus dokumen');
        }

        $perkaraId = $document->perkara_id;

        // Delete file from storage
        $document->deleteFile();

        // Delete all versions if this is parent
        if ($document->versions()->count() > 0) {
            foreach ($document->versions as $version) {
                $version->deleteFile();
                $version->delete();
            }
        }

        $document->delete();

        return redirect()
            ->route('admin.perkaras.show', $perkaraId)
            ->with('success', 'Dokumen berhasil dihapus!');
    }
}
