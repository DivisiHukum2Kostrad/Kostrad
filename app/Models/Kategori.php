<?php

// app/Models/Kategori.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Perkara;


class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';

    protected $fillable = [
        'nama',
        'warna',
    ];

    // Relationship: Kategori has many Perkara
    public function perkaras()
    {
        return $this->hasMany(Perkara::class, 'kategori_id');
    }
}
