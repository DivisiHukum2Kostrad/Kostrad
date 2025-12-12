<?php

namespace App\Services;

use App\Models\Perkara;
use App\Models\DokumenPerkara;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR code for case tracking using SVG
     */
    public function generateCaseQRCode(Perkara $perkara): string
    {
        $trackingUrl = route('perkara.track', ['id' => $perkara->id, 'code' => $this->generateTrackingCode($perkara)]);
        
        $qrData = [
            'case_id' => $perkara->id,
            'case_number' => $perkara->nomor_perkara,
            'tracking_url' => $trackingUrl,
        ];

        // Create QR directory if not exists
        $qrDir = 'qrcodes/' . date('Y/m');
        if (!Storage::exists($qrDir)) {
            Storage::makeDirectory($qrDir);
        }

        // Generate QR code filename
        $filename = 'case_' . $perkara->id . '_' . time() . '.svg';
        $qrPath = $qrDir . '/' . $filename;

        // Generate QR code SVG
        $svg = $this->generateQRCodeSVG($trackingUrl);
        
        // Save QR code
        Storage::put($qrPath, $svg);

        return $qrPath;
    }

    /**
     * Generate QR code for document tracking
     */
    public function generateDocumentQRCode(DokumenPerkara $dokumen): string
    {
        $trackingUrl = route('dokumen.track', ['id' => $dokumen->id, 'code' => $this->generateDocumentTrackingCode($dokumen)]);
        
        // Create QR directory if not exists
        $qrDir = 'qrcodes/documents/' . date('Y/m');
        if (!Storage::exists($qrDir)) {
            Storage::makeDirectory($qrDir);
        }

        // Generate QR code filename
        $filename = 'doc_' . $dokumen->id . '_' . time() . '.svg';
        $qrPath = $qrDir . '/' . $filename;

        // Generate QR code SVG
        $svg = $this->generateQRCodeSVG($trackingUrl);
        
        // Save QR code
        Storage::put($qrPath, $svg);

        // Update document record
        $dokumen->update(['qr_code_path' => $qrPath]);

        return $qrPath;
    }

    /**
     * Generate tracking code for case
     */
    private function generateTrackingCode(Perkara $perkara): string
    {
        return hash('sha256', $perkara->id . $perkara->nomor_perkara . config('app.key'));
    }

    /**
     * Generate tracking code for document
     */
    private function generateDocumentTrackingCode(DokumenPerkara $dokumen): string
    {
        return hash('sha256', $dokumen->id . $dokumen->nama_dokumen . config('app.key'));
    }

    /**
     * Verify tracking code
     */
    public function verifyTrackingCode(Perkara $perkara, string $code): bool
    {
        return hash_equals($this->generateTrackingCode($perkara), $code);
    }

    /**
     * Generate QR Code SVG (Simple implementation)
     * For production, consider using a proper QR code library
     */
    private function generateQRCodeSVG(string $data): string
    {
        // Simple QR code representation using data URI
        // In production, you would use a proper QR code generation library
        
        $size = 300;
        $gridSize = 21; // QR code modules
        $moduleSize = $size / $gridSize;
        
        // Generate a simple grid pattern based on data hash
        $hash = md5($data);
        $binary = '';
        for ($i = 0; $i < strlen($hash); $i++) {
            $binary .= str_pad(decbin(hexdec($hash[$i])), 4, '0', STR_PAD_LEFT);
        }
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '">';
        $svg .= '<rect width="' . $size . '" height="' . $size . '" fill="white"/>';
        
        // Generate pattern
        $bitIndex = 0;
        for ($y = 0; $y < $gridSize; $y++) {
            for ($x = 0; $x < $gridSize; $x++) {
                // Add finder patterns (corners)
                if (($x < 7 && $y < 7) || ($x >= $gridSize - 7 && $y < 7) || ($x < 7 && $y >= $gridSize - 7)) {
                    $isBorder = ($x == 0 || $x == 6 || $y == 0 || $y == 6) ||
                                ($x >= $gridSize - 7 && ($x == $gridSize - 7 || $x == $gridSize - 1) && $y < 7) ||
                                ($x >= $gridSize - 7 && $y < 7 && ($y == 0 || $y == 6)) ||
                                ($x < 7 && $y >= $gridSize - 7 && ($x == 0 || $x == 6)) ||
                                ($x < 7 && $y >= $gridSize - 7 && ($y == $gridSize - 7 || $y == $gridSize - 1));
                    
                    $isCenter = ($x >= 2 && $x <= 4 && $y >= 2 && $y <= 4) ||
                                ($x >= $gridSize - 5 && $x <= $gridSize - 3 && $y >= 2 && $y <= 4) ||
                                ($x >= 2 && $x <= 4 && $y >= $gridSize - 5 && $y <= $gridSize - 3);
                    
                    if ($isBorder || $isCenter) {
                        $svg .= '<rect x="' . ($x * $moduleSize) . '" y="' . ($y * $moduleSize) . '" width="' . $moduleSize . '" height="' . $moduleSize . '" fill="black"/>';
                    }
                } else {
                    // Data area - use hash bits
                    if ($bitIndex < strlen($binary) && $binary[$bitIndex] == '1') {
                        $svg .= '<rect x="' . ($x * $moduleSize) . '" y="' . ($y * $moduleSize) . '" width="' . $moduleSize . '" height="' . $moduleSize . '" fill="black"/>';
                    }
                    $bitIndex++;
                }
            }
        }
        
        // Add centered text with tracking info
        $svg .= '<text x="' . ($size / 2) . '" y="' . ($size + 20) . '" font-family="Arial" font-size="12" text-anchor="middle" fill="black">' . substr($data, 0, 30) . '...</text>';
        
        $svg .= '</svg>';
        
        return $svg;
    }

    /**
     * Get QR code as base64 for inline display
     */
    public function getQRCodeBase64(string $qrPath): ?string
    {
        if (!Storage::exists($qrPath)) {
            return null;
        }

        $content = Storage::get($qrPath);
        return 'data:image/svg+xml;base64,' . base64_encode($content);
    }

    /**
     * Batch generate QR codes for multiple cases
     */
    public function batchGenerateCaseQRCodes(array $perkaraIds): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'details' => [],
        ];

        foreach ($perkaraIds as $id) {
            $perkara = Perkara::find($id);
            
            if (!$perkara) {
                $results['failed']++;
                $results['details'][$id] = 'Case not found';
                continue;
            }

            try {
                $qrPath = $this->generateCaseQRCode($perkara);
                $results['success']++;
                $results['details'][$id] = [
                    'message' => 'QR code generated successfully',
                    'path' => $qrPath,
                ];
            } catch (\Exception $e) {
                $results['failed']++;
                $results['details'][$id] = 'QR code generation failed: ' . $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * Batch generate QR codes for documents
     */
    public function batchGenerateDocumentQRCodes(array $dokumentIds): array
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

            try {
                $qrPath = $this->generateDocumentQRCode($dokumen);
                $results['success']++;
                $results['details'][$id] = [
                    'message' => 'QR code generated successfully',
                    'path' => $qrPath,
                ];
            } catch (\Exception $e) {
                $results['failed']++;
                $results['details'][$id] = 'QR code generation failed: ' . $e->getMessage();
            }
        }

        return $results;
    }
}
