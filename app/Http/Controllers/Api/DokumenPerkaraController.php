<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DokumenPerkaraResource;
use App\Models\DokumenPerkara;
use App\Models\Perkara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenPerkaraController extends Controller
{
    /**
     * Display documents for a case
     * 
     * @group Documents
     * @authenticated
     */
    public function index(Perkara $perkara)
    {
        $dokumens = $perkara->dokumens()->with('uploadedBy')->get();
        
        return DokumenPerkaraResource::collection($dokumens);
    }

    /**
     * Upload a new document
     * 
     * @group Documents
     * @authenticated
     */
    public function store(Request $request, Perkara $perkara)
    {
        if (!$request->user()->hasPermission('manage_documents')) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to upload documents.'
            ], 403);
        }

        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'jenis_dokumen' => 'required|string|max:100',
            'kategori_dokumen' => 'required|in:Surat Keputusan,Berita Acara,Laporan,Bukti,Lainnya',
            'file' => 'required|file|max:10240', // 10MB max
            'keterangan' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/' . $perkara->id, $fileName, 'public');

        $dokumen = DokumenPerkara::create([
            'perkara_id' => $perkara->id,
            'nama_dokumen' => $validated['nama_dokumen'],
            'jenis_dokumen' => $validated['jenis_dokumen'],
            'kategori_dokumen' => $validated['kategori_dokumen'],
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $request->user()->id,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'data' => new DokumenPerkaraResource($dokumen->load('uploadedBy')),
        ], 201);
    }

    /**
     * Display the specified document
     * 
     * @group Documents
     * @authenticated
     */
    public function show(DokumenPerkara $dokumen)
    {
        $dokumen->load('uploadedBy');
        
        return new DokumenPerkaraResource($dokumen);
    }

    /**
     * Update the specified document metadata
     * 
     * @group Documents
     * @authenticated
     */
    public function update(Request $request, DokumenPerkara $dokumen)
    {
        if (!$request->user()->hasPermission('manage_documents')) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to update documents.'
            ], 403);
        }

        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'jenis_dokumen' => 'required|string|max:100',
            'kategori_dokumen' => 'required|in:Surat Keputusan,Berita Acara,Laporan,Bukti,Lainnya',
            'keterangan' => 'nullable|string',
        ]);

        $dokumen->update($validated);

        return response()->json([
            'message' => 'Document updated successfully',
            'data' => new DokumenPerkaraResource($dokumen->fresh()->load('uploadedBy')),
        ]);
    }

    /**
     * Remove the specified document
     * 
     * @group Documents
     * @authenticated
     */
    public function destroy(Request $request, DokumenPerkara $dokumen)
    {
        if (!$request->user()->hasPermission('manage_documents')) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to delete documents.'
            ], 403);
        }

        // Delete file from storage
        if ($dokumen->file_path) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return response()->json([
            'message' => 'Document deleted successfully',
        ]);
    }

    /**
     * Download the specified document
     * 
     * @group Documents
     * @authenticated
     */
    public function download(DokumenPerkara $dokumen)
    {
        // Increment download count
        $dokumen->increment('download_count');

        return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
    }
}
