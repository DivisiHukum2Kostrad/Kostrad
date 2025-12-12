<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerkaraResource extends JsonResource
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
            'nomor_perkara' => $this->nomor_perkara,
            'nama' => $this->nama,
            'jenis_perkara' => $this->jenis_perkara,
            'deskripsi' => $this->deskripsi,
            'kategori' => [
                'id' => $this->kategori->id,
                'nama' => $this->kategori->nama,
            ],
            'priority' => $this->priority,
            'priority_badge' => $this->priority_badge,
            'status' => $this->status,
            'status_badge' => $this->status_badge,
            'progress' => $this->progress,
            'deadline' => $this->deadline?->format('Y-m-d'),
            'deadline_status' => $this->deadline ? $this->deadline_status : null,
            'days_until_deadline' => $this->deadline ? $this->days_until_deadline : null,
            'assigned_to' => $this->assigned_to,
            'tanggal_perkara' => $this->tanggal_perkara?->format('Y-m-d'),
            'tanggal_masuk' => $this->tanggal_masuk->format('Y-m-d'),
            'tanggal_selesai' => $this->tanggal_selesai?->format('Y-m-d'),
            'estimated_days' => $this->estimated_days,
            'keterangan' => $this->keterangan,
            'internal_notes' => $this->when(
                $request->user()?->hasPermission('manage_cases'),
                $this->internal_notes
            ),
            'tags' => $this->tags,
            'is_public' => $this->is_public,
            'file_dokumentasi' => $this->file_dokumentasi,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            
            // Computed fields
            'is_overdue' => $this->isOverdue(),
            'is_deadline_approaching' => $this->isDeadlineApproaching(),
            
            // Relationships (when loaded)
            'dokumens_count' => $this->whenLoaded('dokumens', fn() => $this->dokumens->count()),
            'dokumens' => DokumenPerkaraResource::collection($this->whenLoaded('dokumens')),
        ];
    }
}
