<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('datakriteria', function (Blueprint $table) {
            $table->id(); // ID auto-increment
            $table->string('kode_kriteria', 10)->unique(); // Kode kriteria (VARCHAR 10)
            $table->string('nama_kriteria', 255); // Nama kriteria (VARCHAR 255)
            $table->decimal('bobot', 5, 2); // Bobot (DECIMAL 5,2)
            $table->enum('jenis', ['Benefit', 'Cost']); // Jenis (Benefit atau Cost)
            $table->timestamps(); // Kolom created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('datakriteria');
    }
};
