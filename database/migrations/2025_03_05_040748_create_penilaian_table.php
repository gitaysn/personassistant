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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternatif_id')->constrained('dataalternatif')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('datakriteria')->onDelete('cascade');
            $table->foreignId('subkriteria_id')->nullable()->constrained('subkriteria')->onDelete('cascade'); // ✅ Tambahkan ini
            $table->float('nilai_c1')->nullable(); // Menyimpan nilai C1
            $table->float('nilai_c2')->nullable(); // Menyimpan nilai C2
            $table->float('nilai_c3')->nullable(); // Menyimpan nilai C3
            $table->float('nilai_c4')->nullable(); // Menyimpan nilai C4
            $table->float('nilai_c5')->nullable(); // Menyimpan nilai C5
            $table->float('nilai_c6')->nullable(); // Menyimpan nilai C6
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
