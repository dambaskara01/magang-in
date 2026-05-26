<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
