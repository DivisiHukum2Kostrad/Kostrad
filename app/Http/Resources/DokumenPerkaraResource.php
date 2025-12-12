<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DokumenPerkaraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_dokumen' => $this->nama_dokumen,
            'jenis_dokumen' => $this->jenis_dokumen,
            'kategori_dokumen' => $this->kategori_dokumen,
            'category_badge' => $this->category_badge,
            'file_path' => $this->file_path,
            'file_name' => $this->file_name,
            'file_size' => $this->file_size,
            'formatted_file_size' => $this->formatted_file_size,
            'mime_type' => $this->mime_type,
            'file_icon' => $this->file_icon,
            'download_count' => $this->download_count,
            'is_previewable' => $this->is_previewable,
            'metadata' => $this->metadata,
            'keterangan' => $this->keterangan,
            'perkara_id' => $this->perkara_id,
            'uploaded_by' => $this->uploadedBy ? [
                'id' => $this->uploadedBy->id,
                'name' => $this->uploadedBy->name,
                'email' => $this->uploadedBy->email,
            ] : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
