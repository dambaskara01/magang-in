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
        Schema::create('lamarans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pendaftar_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('lowongan_id')
                ->constrained()
                ->onDelete('cascade');

            $table->date('tanggal_lamaran');

            $table->enum('status', [
                'pending',
                'diproses',
                'diterima',
                'ditolak'
            ])->default('pending');

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lamarans');
    }
};
