<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration: Membuat tabel 'matches' untuk menyimpan data pertandingan
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('tim_tamu');          // Nama tim tamu
            $table->string('tim_kandang');        // Nama tim kandang
            $table->date('tanggal');              // Tanggal pertandingan
            $table->string('jam');                // Jam pertandingan (misal: 19:00)
            $table->string('venue');              // Lokasi venue
            $table->decimal('harga', 10, 2)->default(50000); // Harga tiket
            $table->integer('kapasitas')->default(100);       // Kapasitas kursi
            $table->boolean('aktif')->default(true);          // Status aktif/tidak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
