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
        Schema::create('cabang', function (Blueprint $table) {
            $table->char('kode_cabang', 10)->primary();
            $table->string('nama_cabang', 50)->nullable();
            $table->string('lokasi_cabang', 255)->nullable();
            $table->smallInteger('radius_cabang')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang');
    }
};