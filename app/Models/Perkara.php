<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perkara extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perkaras';

    protected $fillable = [
        'nomor_perkara',
        'jenis_perkara',
        'kategori_id',
        'tanggal_masuk',
        'tanggal_selesai',
        'status',
        'keterangan',
        'file_dokumentasi',
        'is_public',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_selesai' => 'date',
        'is_public' => 'boolean',
    ];

    // Relationship: Perkara belongs to Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relationship: Perkara has many Personel (Many-to-Many)
    public function personels()
    {
        return $this->belongsToMany(Personel::class, 'perkara_personel')
                    ->withPivot('peran')
                    ->withTimestamps();
    }

    // Relationship: Perkara has many Dokumen
    public function dokumens()
    {
        return $this->hasMany(DokumenPerkara::class, 'perkara_id');
    }

    // Relationship: Perkara has many Riwayat
    public function riwayats()
    {
        return $this->hasMany(RiwayatPerkara::class, 'perkara_id');
    }

    // Scope: Hanya data publik
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // Scope: Hanya status Selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }

    // Scope: Hanya status Proses
    public function scopeProses($query)
    {
        return $query->where('status', 'Proses');
    }

    // Accessor: Get badge color berdasarkan status
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'Selesai'
            ? 'bg-green-100 text-green-800'
            : 'bg-yellow-100 text-yellow-800';
    }

    // Accessor: Get kategori badge color
    public function getKategoriBadgeAttribute()
    {
        $colors = [
            'Disiplin' => 'bg-purple-100 text-purple-800',
            'Administrasi' => 'bg-blue-100 text-blue-800',
            'Pidana' => 'bg-red-100 text-red-800',
        ];

        return $colors[$this->kategori->nama] ?? 'bg-gray-100 text-gray-800';
    }

    // Method: Auto generate nomor perkara
    public static function generateNomorPerkara()
    {
        $year = date('Y');
        $lastPerkara = self::whereYear('created_at', $year)
                          ->orderBy('id', 'desc')
                          ->first();

        $nextNumber = $lastPerkara
            ? (int) substr($lastPerkara->nomor_perkara, -3) + 1
            : 1;

        return sprintf('PERK/DIV2/%s/%03d', $year, $nextNumber);
    }
}
