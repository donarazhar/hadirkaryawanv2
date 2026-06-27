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
        Schema::create('konfigurasi_jk_unit_detail', function (Blueprint $table) {
            $table->string('kode_jk_unit', 20);
            $table->char('kode_jam_kerja', 10);
            $table->string('hari', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_jk_unit_detail');
    }
};