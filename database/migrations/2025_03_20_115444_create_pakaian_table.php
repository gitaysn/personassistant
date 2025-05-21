<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pakaian', function (Blueprint $table) {
            $table->id(); // ID otomatis
            $table->string('nama'); // Nama pakaian
            $table->string('jenis_acara'); // Kasual, Formal, Pesta, Santai
            $table->string('jenis_pakaian'); // Kemeja, Blouse, Celana, Rok, Dress, Cardigan
            $table->integer('harga'); // Harga pakaian
            $table->string('warna'); // Warna pakaian
            $table->string('lokasi'); // Indoor / Outdoor
            $table->string('cuaca'); // Cerah / Berawan
            $table->decimal('rating', 3, 2)->default(0); // Contoh: 4.5
            $table->timestamps(); // Tanggal dibuat & diperbarui
        });
    }

    public function down()
    {
        Schema::dropIfExists('pakaian');
    }
};
