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
        Schema::create('jam_kerja', function (Blueprint $table) {
            $table->string('kode_jam_kerja', 10)->primary();
            $table->string('nama_jam_kerja', 15);
            $table->time('awal_jam_masuk');
            $table->time('jam_masuk');
            $table->time('akhir_jam_masuk');
            $table->time('jam_pulang');
            $table->char('lintashari', 1)->default('0');

            // Multi-shift columns (NO ->after() in CREATE!)
            $table->enum('tipe_jam_kerja', ['regular', 'multi_shift'])
                ->default('regular')
                ->comment('regular = jam kerja biasa, multi_shift = untuk imam/muazin dll');

            $table->integer('total_shift')
                ->default(1)
                ->comment('Jumlah shift dalam 1 hari (default 1 untuk regular)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_kerja');
    }
};
