<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPerkara extends Model
{
    use HasFactory;

    protected $table = 'riwayat_perkaras';

    protected $fillable = [
        'perkara_id',
        'user_id',
        'aksi',
        'deskripsi',
        'tanggal_aksi',
    ];

    protected $casts = [
        'tanggal_aksi' => 'datetime',
    ];

    // Relationship: Riwayat belongs to Perkara
    public function perkara()
    {
        return $this->belongsTo(Perkara::class, 'perkara_id');
    }

    // Relationship: Riwayat belongs to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Method: Log aktivitas
    public static function log($perkaraId, $aksi, $deskripsi = null)
    {
        return self::create([
            'perkara_id' => $perkaraId,
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'tanggal_aksi' => now(),
        ]);
    }
}
