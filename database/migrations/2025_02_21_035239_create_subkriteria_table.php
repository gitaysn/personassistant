<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subkriteria', function (Blueprint $table) {
            $table->id();
            $table->string('nama_subkriteria');
            $table->integer('nilai');
            $table->foreignId('kriteria_id')->nullable()->constrained('datakriteria')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('subkriteria', function (Blueprint $table) {
            $table->dropForeign(['kriteria_id']);
        });

        Schema::dropIfExists('subkriteria');
    }
};