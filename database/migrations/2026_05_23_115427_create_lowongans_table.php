<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lowongans', function (Blueprint $table) {
            $table->id();

            $table->string('nama_posisi');
            $table->string('divisi');
            $table->text('deskripsi');
            $table->integer('kuota');
            $table->string('lokasi');

            $table->enum('status', [
                'dibuka',
                'ditutup'
            ])->default('dibuka');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};