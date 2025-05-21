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
        Schema::table('penilaian', function (Blueprint $table) {
            $table->float('nilai_c1')->nullable();
            $table->float('nilai_c2')->nullable();
            $table->float('nilai_c3')->nullable();
            $table->float('nilai_c4')->nullable();
            $table->float('nilai_c5')->nullable();
            $table->float('nilai_c6')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_c1', 'nilai_c2', 'nilai_c3',
                'nilai_c4', 'nilai_c5', 'nilai_c6'
            ]);
        });
    }
};
