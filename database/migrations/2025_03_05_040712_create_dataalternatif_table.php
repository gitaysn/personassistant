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
        Schema::create('dataalternatif', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alternatif');
            $table->foreignId('subkriteria_id')->nullable()->constrained('subkriteria')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataalternatif');
    }
};
