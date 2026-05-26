
# 🎓 Sistem Pendaftaran Magang (Laravel + Filament)

Project ini adalah sistem pendaftaran magang berbasis Laravel + Filament Admin Panel.

README ini berisi:
- Setup dari nol
- Install Laravel
- Install Filament
- Migration
- Model
- Resource
- Role system
- Apply lowongan
- Error umum dan solusinya
- Persiapan clone project saat tes

---

# 📦 PERSIAPAN AWAL

## Software yang wajib ada

- Laragon
- PHP 8.3
- Composer
- VSCode
- Git

---

# 🛠️ CEK PHP & COMPOSER

## Cek PHP

```bash
php -v
```

Minimal:
```text
PHP 8.3.x
```

---

## Cek Composer

```bash
composer -V
```

---

# 🚀 MEMBUAT PROJECT DARI NOL

## 1. Masuk folder laragon

```bash
cd C:\laragon\www
```

---

## 2. Buat project Laravel

```bash
composer create-project laravel/laravel magang-in
```

---

## 3. Masuk project

```bash
cd magang-in
```

---

# 🗄️ SETUP DATABASE

## 1. Buka phpMyAdmin

```text
http://localhost/phpmyadmin
```

---

## 2. Buat database

Nama database:

```text
magang-in
```

---

## 3. Edit file `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=magang-in
DB_USERNAME=root
DB_PASSWORD=
```

---

# ⚡ INSTALL FILAMENT

## Install package Filament

```bash
composer require filament/filament:"^4.0"

```

---

## Install panel Filament

```bash
php artisan filament:install --panels
```

Isi panel:

```text
admin
```

---

# 👤 MEMBUAT USER ADMIN

```bash
php artisan make:filament-user
```

Isi:
- nama
- email
- password

---

# ▶️ MENJALANKAN PROJECT

```bash
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000/admin
```

---

# 🔀 ROUTE REDIRECT

Agar `/` langsung ke login Filament.

## routes/web.php

```php
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/login');
```

---

# 🧱 MEMBUAT MIGRATION

# LOWONGAN

## Buat migration

```bash
php artisan make:migration create_lowongans_table
```

---

## Isi migration

```php
Schema::create('lowongans', function (Blueprint $table) {
    $table->id();
    $table->string('nama_posisi');
    $table->string('nama_perusahaan');
    $table->string('divisi');
    $table->text('deskripsi');
    $table->integer('kuota');
    $table->string('lokasi');
    $table->string('status');
    $table->timestamps();
});
```

---

# LAMARAN

## Buat migration

```bash
php artisan make:migration create_lamarans_table
```

---

## Isi migration

```php
Schema::create('lamarans', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('lowongan_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->enum('status', [
        'pending',
        'diproses',
        'diterima',
        'ditolak'
    ])->default('pending');

    $table->timestamps();
});
```

---

# ROLE USER

## Tambahkan role ke users

```bash
php artisan make:migration add_role_to_users_table
```

---

## Isi migration

```php
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', [
        'admin',
        'mahasiswa'
    ])->default('mahasiswa');
});
```

---

# 🚀 JALANKAN MIGRATE

```bash
php artisan migrate
```

---

# 🧠 MEMBUAT MODEL

## Lowongan

```bash
php artisan make:model Lowongan
```

---

## Lamaran

```bash
php artisan make:model Lamaran
```

---

# 🧩 FILLABLE MODEL

# app/Models/Lowongan.php

```php
protected $fillable = [
    'nama_posisi',
    'nama_perusahaan',
    'divisi',
    'deskripsi',
    'kuota',
    'lokasi',
    'status',
];
```

---

# app/Models/Lamaran.php

```php
protected $fillable = [
    'user_id',
    'lowongan_id',
    'status',
];
```

---

# app/Models/User.php

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
];
```

---

# 🔗 RELASI MODEL

# User.php

```php
public function lamarans()
{
    return $this->hasMany(Lamaran::class);
}
```

---

# Lowongan.php

```php
public function lamarans()
{
    return $this->hasMany(Lamaran::class);
}
```

---

# Lamaran.php

```php
public function user()
{
    return $this->belongsTo(User::class);
}

public function lowongan()
{
    return $this->belongsTo(Lowongan::class);
}
```

---

# 📦 MEMBUAT RESOURCE FILAMENT

## Resource User

```bash
php artisan make:filament-resource User
```

Isi:
- generate form/table → yes
- generate from database → yes

---

## Resource Lowongan

```bash
php artisan make:filament-resource Lowongan
```

Isi:
- title attribute → nama_posisi
- generate from database → yes

---

## Resource Lamaran

```bash
php artisan make:filament-resource Lamaran
```

---

# 🔐 ROLE SYSTEM

# Hanya admin bisa create/edit lowongan

## LowonganResource.php

```php
public static function canCreate(): bool
{
    return Auth::check()
        && Auth::user()->role === 'admin';
}

public static function canEdit($record): bool
{
    return Auth::check()
        && Auth::user()->role === 'admin';
}
```

---

# 👨‍🎓 MAHASISWA BISA LIHAT LOWONGAN

```php
public static function canViewAny(): bool
{
    return Auth::check();
}
```

---

# 🚫 SEMBUNYIKAN TOMBOL EDIT

## LowongansTable.php

```php
EditAction::make()
    ->visible(fn () =>
        Auth::user()->role === 'admin'
    ),
```

---

# 📝 FITUR APPLY LOWONGAN

## Tambahkan action apply

```php
Action::make('apply')
    ->label('Apply')
    ->visible(fn () =>
        Auth::user()->role === 'mahasiswa'
    )
```

---

# 🧪 VALIDASI PROJECT

## Clear cache

```bash
php artisan optimize:clear
```

---

## Dump autoload

```bash
composer dump-autoload
```

---

## Cek syntax PHP

```bash
php -l app/Models/Lowongan.php
```

---

# ⚠️ ERROR YANG SERING TERJADI

# 1. php tidak dikenali

```text
'php' is not recognized
```

## Solusi

Laragon:
```text
Tools → Add to PATH
```

Restart VSCode.

---

# 2. Database tidak ditemukan

```text
Unknown database
```

## Solusi

- Buat database di phpMyAdmin
- Cek `.env`

---

# 3. MassAssignmentException

## Penyebab

Lupa `$fillable`

---

# 4. 403 Forbidden

## Penyebab

Role bukan admin.

---

# 5. Tombol tidak hilang

## Solusi

```bash
php artisan optimize:clear
```

---

# 6. Composer require PHP 8.3

## Solusi

Gunakan PHP 8.3.

Cek:

```bash
php -v
```

---

# 🔄 CARA PUSH KE GITHUB

## Init git

```bash
git init
```

---

## Add file

```bash
git add .
```

---

## Commit

```bash
git commit -m "first commit"
```

---

## Connect repository

```bash
git remote add origin https://github.com/USERNAME/REPO.git
```

---

## Push

```bash
git push -u origin main
```

---

# 💻 CARA CLONE SAAT TES

## Clone project

```bash
git clone URL_REPOSITORY
```

---

## Masuk project

```bash
cd NAMA_PROJECT
```

---

## Install dependency

```bash
composer install
```

---

## Setup env

```bash
cp .env.example .env
php artisan key:generate
```

---

## Setup database

Edit `.env`

---

## Migrate

```bash
php artisan migrate
```

---

## Buat admin

```bash
php artisan make:filament-user
```

---

## Jalankan

```bash
php artisan serve
```

---

# 🔥 FITUR YANG SUDAH ADA

✅ Login admin  
✅ Login mahasiswa  
✅ CRUD User  
✅ CRUD Lowongan  
✅ Apply Lowongan  
✅ Status Lamaran  
✅ Role system  
✅ Filament Admin Panel  

---

# COMMAND PENTING LARAVEL + FILAMENT

## Menjalankan Project

```bash
php artisan serve
```

Akses:

```text
http://127.0.0.1:8000
```

---

# INSTALL DEPENDENCY

## Install vendor Laravel

```bash
composer install
```

## Install node modules

```bash
npm install
```

---

# ENVIRONMENT

## Copy file env

```bash
cp .env.example .env
```

## Generate app key

```bash
php artisan key:generate
```

---

# DATABASE

## Migrasi database

```bash
php artisan migrate
```

## Reset database + migrate ulang

```bash
php artisan migrate:fresh
```

## Reset database + seed otomatis

```bash
php artisan migrate:fresh --seed
```

## Jalankan seeder saja

```bash
php artisan db:seed
```

---

# CACHE & ERROR FIX

## Clear cache Laravel

```bash
php artisan optimize:clear
```

## Clear config cache

```bash
php artisan config:clear
```

## Clear route cache

```bash
php artisan route:clear
```

## Clear view cache

```bash
php artisan view:clear
```

## Composer autoload refresh

```bash
composer dump-autoload
```

---

# FILAMENT COMMAND

## Install Filament

```bash
php artisan filament:install --panels
```

---

## Membuat resource Filament

```bash
php artisan make:filament-resource User
```

```bash
php artisan make:filament-resource Lowongan
```

```bash
php artisan make:filament-resource Lamaran
```

---

## Membuat widget chart

```bash
php artisan make:filament-widget StatistikLamaran --chart
```

---

# MODEL & MIGRATION

## Membuat model + migration sekaligus

```bash
php artisan make:model Lowongan -m
```

```bash
php artisan make:model Lamaran -m
```

```bash
php artisan make:model Pendaftar -m
```

---

# CONTROLLER & ROUTE

## Membuat controller

```bash
php artisan make:controller NamaController
```

---

# DEBUGGING

## Cek syntax PHP

```bash
php -l nama_file.php
```

Contoh:

```bash
php -l app/Models/User.php
```

---

# GIT & GITHUB

## Init git

```bash
git init
```

## Add semua file

```bash
git add .
```

## Commit

```bash
git commit -m "first commit"
```

## Connect repository GitHub

```bash
git remote add origin URL_REPOSITORY
```

## Push ke GitHub

```bash
git push -u origin main
```

---

# CLONE PROJECT DI LAPTOP KAMPUS

## Clone repository

```bash
git clone URL_REPOSITORY
```

## Masuk folder

```bash
cd nama-project
```

## Install dependency

```bash
composer install
```

```bash
npm install
```

## Copy env

```bash
cp .env.example .env
```

## Generate key

```bash
php artisan key:generate
```

## Setup database

Buat database baru di phpMyAdmin:

```text
magang_in
```

---

## Jalankan migrate + seed

```bash
php artisan migrate:fresh --seed
```

---

## Jalankan server

```bash
php artisan serve
```

---

# LOGIN TEST

## Admin

```text
email: admin@mail.com
password: password
```

---

## Mahasiswa

```text
email: mhs@mail.com
password: password
```

---

# STRUKTUR DATABASE

## users

Menyimpan akun login:
- admin
- mahasiswa

Field penting:
- id
- name
- email
- password
- role

---

## pendaftars

Menyimpan data mahasiswa/pelamar.

Field:
- id
- user_id
- nama
- dll

Relasi:
- pendaftars.user_id → users.id

---

## lowongans

Menyimpan data lowongan magang.

Field:
- id
- nama_posisi
- nama_perusahaan
- divisi
- deskripsi
- kuota
- lokasi
- status

---

## lamarans

Menyimpan data pengajuan lamaran mahasiswa.

Field:
- id
- pendaftar_id
- lowongan_id
- tanggal_lamaran
- status
- catatan

Status:
- pending
- diproses
- diterima
- ditolak

---

# RELASI MODEL

## User

```php
hasOne(Pendaftar::class)
```

---

## Pendaftar

```php
belongsTo(User::class)
hasMany(Lamaran::class)
```

---

## Lowongan

```php
hasMany(Lamaran::class)
```

---

## Lamaran

```php
belongsTo(Pendaftar::class)
belongsTo(Lowongan::class)
```

---

# ROLE SYSTEM

## Admin
Bisa:
- tambah lowongan
- edit lowongan
- hapus lowongan
- melihat semua lamaran
- mengubah status lamaran

---

## Mahasiswa
Bisa:
- melihat lowongan
- apply lowongan
- melihat status lamaran sendiri

Tidak bisa:
- tambah lowongan
- edit lowongan
- akses data admin

---

# ERROR YANG PERNAH TERJADI

## 403 Forbidden

Penyebab:
akses dibatasi role admin.

Solusi:
atur permission menggunakan:

```php
canEdit()
canCreate()
```

---

## Unknown column 'user_id'

Penyebab:
tabel menggunakan `id`, bukan `user_id`.

Solusi:
sesuaikan query dengan struktur tabel.

---

## Cannot redeclare static heading

Penyebab:
property `$heading` dibuat static.

Salah:

```php
protected static ?string $heading
```

Benar:

```php
protected ?string $heading
```

---

# FITUR YANG SUDAH DIBUAT

- Login admin & mahasiswa
- CRUD lowongan
- Apply lamaran
- Status lamaran
- Role access
- Sidebar dinamis
- Statistik chart dashboard admin
- Seeder dummy data
- Filament admin panel

# 👨‍💻 AUTHOR

Adham Baskara
