<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DokumenPerkara extends Model
{
    use HasFactory;

    protected $table = 'dokumen_perkaras';

    protected $fillable = [
        'perkara_id',
        'nama_dokumen',
        'jenis_dokumen',
        'file_path',
        'file_size',
        'mime_type',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Relationship: Dokumen belongs to Perkara
    public function perkara()
    {
        return $this->belongsTo(Perkara::class, 'perkara_id');
    }

    // Accessor: Get file URL
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    // Accessor: Get formatted file size
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    // Method: Delete file from storage
    public function deleteFile()
    {
        if (Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
    }
}

