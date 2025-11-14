<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personel extends Model
{
    use HasFactory;

    protected $table = 'personels';

    protected $fillable = [
        'nrp',
        'nama',
        'pangkat',
        'jabatan',
        'kesatuan',
    ];

    // Relationship: Personel has many Perkara (Many-to-Many)
    public function perkaras()
    {
        return $this->belongsToMany(Perkara::class, 'perkara_personel')
                    ->withPivot('peran')
                    ->withTimestamps();
    }

    // Accessor: Get full name with pangkat
    public function getFullNameAttribute()
    {
        return $this->pangkat
            ? "{$this->pangkat} {$this->nama}"
            : $this->nama;
    }
}
