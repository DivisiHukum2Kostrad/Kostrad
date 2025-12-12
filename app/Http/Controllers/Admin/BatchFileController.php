<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenPerkara;
use App\Models\Perkara;
use App\Services\FileProcessingService;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BatchFileController extends Controller
{
    protected $fileProcessingService;
    protected $qrCodeService;

    public function __construct(FileProcessingService $fileProcessingService, QRCodeService $qrCodeService)
    {
        $this->fileProcessingService = $fileProcessingService;
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Show batch operations page
     */
    public function index()
    {
        return view('admin.batch-operations.index');
    }

    /**
     * Batch generate thumbnails
     */
    public function batchGenerateThumbnails(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:dokumen_perkaras,id',
        ]);

        $results = $this->fileProcessingService->batchGenerateThumbnails($request->document_ids);

        return response()->json([
            'success' => true,
            'message' => "{$results['success']} thumbnails generated, {$results['failed']} failed, {$results['skipped']} skipped",
            'data' => $results,
        ]);
    }

    /**
     * Batch sign documents
     */
    public function batchSignDocuments(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:dokumen_perkaras,id',
            'signature_name' => 'required|string|max:255',
        ]);

        $results = $this->fileProcessingService->batchSignDocuments(
            $request->document_ids,
            Auth::id(),
            $request->signature_name
        );

        return response()->json([
            'success' => true,
            'message' => "{$results['success']} documents signed, {$results['failed']} failed",
            'data' => $results,
        ]);
    }

    /**
     * Batch delete documents
     */
    public function batchDeleteDocuments(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:dokumen_perkaras,id',
        ]);

        $results = $this->fileProcessingService->batchDeleteDocuments($request->document_ids);

        return response()->json([
            'success' => true,
            'message' => "{$results['success']} documents deleted, {$results['failed']} failed",
            'data' => $results,
        ]);
    }

    /**
     * Batch download documents as ZIP
     */
    public function batchDownloadDocuments(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:dokumen_perkaras,id',
        ]);

        $documents = DokumenPerkara::whereIn('id', $request->document_ids)->get();

        if ($documents->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No documents found'], 404);
        }

        // Create ZIP file
        $zipFileName = 'documents_' . time() . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Create temp directory if not exists
        if (!Storage::exists('temp')) {
            Storage::makeDirectory('temp');
        }

        $zip = new ZipArchive();
        
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return response()->json(['success' => false, 'message' => 'Could not create ZIP file'], 500);
        }

        foreach ($documents as $dokumen) {
            $fullPath = storage_path('app/' . $dokumen->file_path);
            
            if (file_exists($fullPath)) {
                $zip->addFile($fullPath, $dokumen->nama_dokumen);
            }
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Batch generate QR codes for documents
     */
    public function batchGenerateQRCodes(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:dokumen_perkaras,id',
        ]);

        $results = $this->qrCodeService->batchGenerateDocumentQRCodes($request->document_ids);

        return response()->json([
            'success' => true,
            'message' => "{$results['success']} QR codes generated, {$results['failed']} failed",
            'data' => $results,
        ]);
    }

    /**
     * Batch generate QR codes for cases
     */
    public function batchGenerateCaseQRCodes(Request $request)
    {
        $request->validate([
            'case_ids' => 'required|array',
            'case_ids.*' => 'exists:perkaras,id',
        ]);

        $results = $this->qrCodeService->batchGenerateCaseQRCodes($request->case_ids);

        return response()->json([
            'success' => true,
            'message' => "{$results['success']} QR codes generated, {$results['failed']} failed",
            'data' => $results,
        ]);
    }

    /**
     * Batch move documents to different case
     */
    public function batchMoveDocuments(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:dokumen_perkaras,id',
            'target_case_id' => 'required|exists:perkaras,id',
        ]);

        $targetCase = Perkara::findOrFail($request->target_case_id);
        $documents = DokumenPerkara::whereIn('id', $request->document_ids)->get();

        $moved = 0;
        foreach ($documents as $dokumen) {
            $dokumen->update(['perkara_id' => $targetCase->id]);
            $moved++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$moved} documents moved to case: {$targetCase->nomor_perkara}",
        ]);
    }

    /**
     * Batch update document category
     */
    public function batchUpdateCategory(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:dokumen_perkaras,id',
            'category' => 'required|string|max:255',
        ]);

        $updated = DokumenPerkara::whereIn('id', $request->document_ids)
            ->update(['category' => $request->category]);

        return response()->json([
            'success' => true,
            'message' => "{$updated} documents updated",
        ]);
    }

    /**
     * Get document thumbnail
     */
    public function getThumbnail($id)
    {
        $dokumen = DokumenPerkara::findOrFail($id);

        if (!$dokumen->has_thumbnail || !$dokumen->thumbnail_path) {
            // Try to generate thumbnail
            $thumbnailPath = $this->fileProcessingService->generateThumbnail($dokumen);
            
            if (!$thumbnailPath) {
                return response()->json(['success' => false, 'message' => 'Thumbnail not available'], 404);
            }
        }

        if (!Storage::exists($dokumen->thumbnail_path)) {
            return response()->json(['success' => false, 'message' => 'Thumbnail file not found'], 404);
        }

        $file = Storage::get($dokumen->thumbnail_path);
        $mimeType = Storage::mimeType($dokumen->thumbnail_path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }

    /**
     * Get document QR code
     */
    public function getQRCode($id)
    {
        $dokumen = DokumenPerkara::findOrFail($id);

        if (!$dokumen->qr_code_path) {
            // Generate QR code
            $qrPath = $this->qrCodeService->generateDocumentQRCode($dokumen);
        }

        if (!Storage::exists($dokumen->qr_code_path)) {
            return response()->json(['success' => false, 'message' => 'QR code not found'], 404);
        }

        $file = Storage::get($dokumen->qr_code_path);

        return response($file, 200)->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Sign document
     */
    public function signDocument(Request $request, $id)
    {
        $request->validate([
            'signature_name' => 'required|string|max:255',
        ]);

        $dokumen = DokumenPerkara::findOrFail($id);

        $signed = $this->fileProcessingService->signDocument(
            $dokumen,
            Auth::id(),
            $request->signature_name
        );

        if ($signed) {
            return response()->json([
                'success' => true,
                'message' => 'Document signed successfully',
                'data' => [
                    'signature' => $dokumen->fresh()->digital_signature,
                    'signed_at' => $dokumen->signed_at,
                    'signed_by' => $dokumen->signedBy->name,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to sign document',
        ], 500);
    }

    /**
     * Verify document signature
     */
    public function verifySignature($id)
    {
        $dokumen = DokumenPerkara::findOrFail($id);

        $result = $this->fileProcessingService->verifySignature($dokumen);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Get document metadata
     */
    public function getMetadata($id)
    {
        $dokumen = DokumenPerkara::findOrFail($id);

        $metadata = $this->fileProcessingService->getDocumentMetadata($dokumen);

        return response()->json([
            'success' => true,
            'data' => $metadata,
        ]);
    }
}
