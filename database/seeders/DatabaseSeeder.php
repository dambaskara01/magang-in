<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lowongan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin3@mail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Mahasiswa 1',
            'email' => 'mhs1@mail.com',
            'password' => bcrypt('12345678'),
            'role' => 'mahasiswa',
        ]);

        Lowongan::create([
            'nama_posisi' => 'UI/UX Designer',
            'nama_perusahaan' => 'PT Mencari Cinta Sejati',
            'divisi' => 'Product Development',
            'deskripsi' => 'Membuat desain aplikasi',
            'kuota' => 5,
            'lokasi' => 'Surabaya',
            'status' => 'dibuka',
        ]);
    }
}
