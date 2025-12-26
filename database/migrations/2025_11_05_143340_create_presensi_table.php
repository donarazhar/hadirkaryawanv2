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
        Schema::create('presensi', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nik', 10);
            $table->date('tgl_presensi');
            $table->time('jam_in')->nullable();
            $table->time('jam_out')->nullable();
            $table->string('foto_in')->nullable();
            $table->string('foto_out')->nullable();
            $table->text('lokasi_in')->nullable();
            $table->text('lokasi_out')->nullable();
            $table->char('kode_jam_kerja', 10)->nullable();

            // Multi-shift columns (NO ->after() in CREATE!)
            $table->integer('shift_ke')
                ->nullable()
                ->comment('Shift ke berapa (untuk multi-shift). Null = regular');

            $table->string('nama_shift', 50)
                ->nullable()
                ->comment('Nama shift (Subuh, Zuhur, dll)');

            $table->char('status', 1)->nullable();
            $table->char('kode_izin', 25)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
