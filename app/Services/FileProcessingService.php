<?php

namespace App\Services;

use App\Models\DokumenPerkara;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

class FileProcessingService
{
    /**
     * Generate thumbnail for image files
     */
    public function generateThumbnail(DokumenPerkara $dokumen): ?string
    {
        // Check if file is an image
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        $extension = strtolower(pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
        
        if (!in_array($extension, $imageExtensions)) {
            return null;
        }

        try {
            $fullPath = storage_path('app/' . $dokumen->file_path);
            
            if (!file_exists($fullPath)) {
                return null;
            }

            // Create thumbnail directory if not exists
            $thumbnailDir = 'thumbnails/' . date('Y/m');
            if (!Storage::exists($thumbnailDir)) {
                Storage::makeDirectory($thumbnailDir);
            }

            // Generate thumbnail filename
            $filename = pathinfo($dokumen->file_path, PATHINFO_FILENAME);
            $thumbnailPath = $thumbnailDir . '/' . $filename . '_thumb.' . $extension;

            // Generate thumbnail using Intervention Image
            $image = Image::read($fullPath);
            
            // Resize to max 300x300 maintaining aspect ratio
            $image->scale(width: 300, height: 300);
            
            // Save thumbnail
            $thumbnailFullPath = storage_path('app/' . $thumbnailPath);
            $image->save($thumbnailFullPath, quality: 85);

            // Update document record
            $dokumen->update([
                'thumbnail_path' => $thumbnailPath,
                'has_thumbnail' => true,
            ]);

            return $thumbnailPath;
        } catch (\Exception $e) {
            \Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate thumbnails for multiple documents in batch
     */
    public function batchGenerateThumbnails(array $dokumentIds): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'skipped' => 0,
            'details' => [],
        ];

        foreach ($dokumentIds as $id) {
            $dokumen = DokumenPerkara::find($id);
            
            if (!$dokumen) {
                $results['skipped']++;
                $results['details'][$id] = 'Document not found';
                continue;
            }

            if ($dokumen->has_thumbnail) {
                $results['skipped']++;
                $results['details'][$id] = 'Thumbnail already exists';
                continue;
            }

            $thumbnail = $this->generateThumbnail($dokumen);
            
            if ($thumbnail) {
                $results['success']++;
                $results['details'][$id] = 'Thumbnail generated successfully';
            } else {
                $results['failed']++;
                $results['details'][$id] = 'Thumbnail generation failed';
            }
        }

        return $results;
    }

    /**
     * Apply digital signature to document
     */
    public function signDocument(DokumenPerkara $dokumen, $userId, string $signatureName): bool
    {
        try {
            // Generate signature hash
            $signatureData = [
                'document_id' => $dokumen->id,
                'document_name' => $dokumen->nama_dokumen,
                'file_path' => $dokumen->file_path,
                'file_size' => $dokumen->file_size,
                'signed_by' => $userId,
                'signed_at' => now()->toIso8601String(),
                'signature_name' => $signatureName,
            ];

            // Create digital signature using SHA-256 hash
            $signature = hash('sha256', json_encode($signatureData));

            // Update document record
            $dokumen->update([
                'digital_signature' => $signature,
                'signature_name' => $signatureName,
                'signed_at' => now(),
                'signed_by' => $userId,
                'is_signed' => true,
                'metadata' => array_merge($dokumen->metadata ?? [], [
                    'signature_algorithm' => 'SHA-256',
                    'signature_timestamp' => now()->toIso8601String(),
                ]),
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Document signing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify digital signature
     */
    public function verifySignature(DokumenPerkara $dokumen): array
    {
        if (!$dokumen->is_signed || !$dokumen->digital_signature) {
            return [
                'valid' => false,
                'message' => 'Document is not signed',
            ];
        }

        try {
            // Recreate signature data
            $signatureData = [
                'document_id' => $dokumen->id,
                'document_name' => $dokumen->nama_dokumen,
                'file_path' => $dokumen->file_path,
                'file_size' => $dokumen->file_size,
                'signed_by' => $dokumen->signed_by,
                'signed_at' => $dokumen->signed_at->toIso8601String(),
                'signature_name' => $dokumen->signature_name,
            ];

            // Recreate signature hash
            $expectedSignature = hash('sha256', json_encode($signatureData));

            // Compare signatures
            $valid = hash_equals($expectedSignature, $dokumen->digital_signature);

            return [
                'valid' => $valid,
                'message' => $valid ? 'Signature is valid' : 'Signature is invalid',
                'signed_by' => $dokumen->signedBy?->name,
                'signed_at' => $dokumen->signed_at?->format('Y-m-d H:i:s'),
                'signature_name' => $dokumen->signature_name,
            ];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'Signature verification failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Batch sign documents
     */
    public function batchSignDocuments(array $dokumentIds, $userId, string $signatureName): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => [],
        ];

        foreach ($dokumentIds as $id) {
            $dokumen = DokumenPerkara::find($id);
            
            if (!$dokumen) {
                $results['failed']++;
                $results['details'][$id] = 'Document not found';
                continue;
            }

            $signed = $this->signDocument($dokumen, $userId, $signatureName);
            
            if ($signed) {
                $results['success']++;
                $results['details'][$id] = 'Document signed successfully';
            } else {
                $results['failed']++;
                $results['details'][$id] = 'Document signing failed';
            }
        }

        return $results;
    }

    /**
     * Delete document and related files
     */
    public function deleteDocument(DokumenPerkara $dokumen): bool
    {
        try {
            // Delete main file
            if (Storage::exists($dokumen->file_path)) {
                Storage::delete($dokumen->file_path);
            }

            // Delete thumbnail
            if ($dokumen->thumbnail_path && Storage::exists($dokumen->thumbnail_path)) {
                Storage::delete($dokumen->thumbnail_path);
            }

            // Delete QR code
            if ($dokumen->qr_code_path && Storage::exists($dokumen->qr_code_path)) {
                Storage::delete($dokumen->qr_code_path);
            }

            // Delete database record
            $dokumen->delete();

            return true;
        } catch (\Exception $e) {
            \Log::error('Document deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Batch delete documents
     */
    public function batchDeleteDocuments(array $dokumentIds): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => [],
        ];

        foreach ($dokumentIds as $id) {
            $dokumen = DokumenPerkara::find($id);
            
            if (!$dokumen) {
                $results['failed']++;
                $results['details'][$id] = 'Document not found';
                continue;
            }

            $deleted = $this->deleteDocument($dokumen);
            
            if ($deleted) {
                $results['success']++;
                $results['details'][$id] = 'Document deleted successfully';
            } else {
                $results['failed']++;
                $results['details'][$id] = 'Document deletion failed';
            }
        }

        return $results;
    }

    /**
     * Get document metadata
     */
    public function getDocumentMetadata(DokumenPerkara $dokumen): array
    {
        $fullPath = storage_path('app/' . $dokumen->file_path);
        
        $metadata = [
            'id' => $dokumen->id,
            'name' => $dokumen->nama_dokumen,
            'type' => $dokumen->jenis_dokumen,
            'category' => $dokumen->category,
            'size' => $dokumen->file_size,
            'size_formatted' => $this->formatFileSize($dokumen->file_size),
            'mime_type' => $dokumen->mime_type,
            'extension' => pathinfo($dokumen->file_path, PATHINFO_EXTENSION),
            'has_thumbnail' => $dokumen->has_thumbnail,
            'is_signed' => $dokumen->is_signed,
            'download_count' => $dokumen->download_count,
            'uploaded_at' => $dokumen->created_at->format('Y-m-d H:i:s'),
            'uploaded_by' => $dokumen->uploader?->name,
        ];

        if (file_exists($fullPath)) {
            $metadata['created_at'] = date('Y-m-d H:i:s', filectime($fullPath));
            $metadata['modified_at'] = date('Y-m-d H:i:s', filemtime($fullPath));
        }

        if ($dokumen->is_signed) {
            $metadata['signature'] = [
                'name' => $dokumen->signature_name,
                'signed_by' => $dokumen->signedBy?->name,
                'signed_at' => $dokumen->signed_at?->format('Y-m-d H:i:s'),
            ];
        }

        return $metadata;
    }

    /**
     * Format file size to human readable format
     */
    private function formatFileSize($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
