<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lowongan extends Model
{
    protected $fillable = [
        'nama_posisi',
        'nama_perusahaan',
        'divisi',
        'deskripsi',
        'kuota',
        'lokasi',
        'status',
    ];

    public function lamarans(): HasMany
    {
        return $this->hasMany(Lamaran::class);
    }
}
