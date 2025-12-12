<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DokumenPerkara extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'dokumen_perkaras';

    protected $fillable = [
        'perkara_id',
        'nama_dokumen',
        'jenis_dokumen',
        'category',
        'file_path',
        'file_size',
        'mime_type',
        'version',
        'parent_id',
        'download_count',
        'last_downloaded_at',
        'uploaded_by',
        'description',
        'is_public',
        'thumbnail_path',
        'qr_code_path',
        'digital_signature',
        'signature_name',
        'signed_at',
        'signed_by',
        'metadata',
        'has_thumbnail',
        'is_signed',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'has_thumbnail' => 'boolean',
        'is_signed' => 'boolean',
        'last_downloaded_at' => 'datetime',
        'signed_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationship: Dokumen belongs to Perkara
    public function perkara()
    {
        return $this->belongsTo(Perkara::class, 'perkara_id');
    }

    // Relationship: Dokumen uploaded by User
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Relationship: Document signed by User
    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    // Relationship: Parent document (for versioning)
    public function parent()
    {
        return $this->belongsTo(DokumenPerkara::class, 'parent_id');
    }

    // Relationship: Child versions
    public function versions()
    {
        return $this->hasMany(DokumenPerkara::class, 'parent_id')->orderBy('version', 'desc');
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

    // Method: Track download
    public function trackDownload()
    {
        $this->increment('download_count');
        $this->update(['last_downloaded_at' => now()]);
    }

    // Accessor: Get file extension
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    // Accessor: Get icon based on file type
    public function getFileIconAttribute()
    {
        $extension = strtolower($this->file_extension);
        
        return match($extension) {
            'pdf' => 'fa-file-pdf text-red-600',
            'doc', 'docx' => 'fa-file-word text-blue-600',
            'xls', 'xlsx' => 'fa-file-excel text-green-600',
            'ppt', 'pptx' => 'fa-file-powerpoint text-orange-600',
            'jpg', 'jpeg', 'png', 'gif', 'svg' => 'fa-file-image text-purple-600',
            'zip', 'rar', '7z' => 'fa-file-archive text-yellow-600',
            'txt' => 'fa-file-alt text-gray-600',
            default => 'fa-file text-gray-600',
        };
    }

    // Accessor: Check if file is previewable
    public function getIsPreviewableAttribute()
    {
        $previewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'txt'];
        return in_array(strtolower($this->file_extension), $previewableTypes);
    }

    // Accessor: Get category badge
    public function getCategoryBadgeAttribute()
    {
        return match($this->category) {
            'evidence' => '<span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Bukti</span>',
            'legal' => '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Hukum</span>',
            'administrative' => '<span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Administrasi</span>',
            'correspondence' => '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">Surat</span>',
            default => '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Lainnya</span>',
        };
    }
}

