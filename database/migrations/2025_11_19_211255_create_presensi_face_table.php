<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_face', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20);
            $table->date('tanggal');

            // Multi-shift columns (NO ->after() in CREATE!)
            $table->integer('shift_ke')
                ->nullable()
                ->comment('Urutan shift (1, 2, 3, dst) untuk multi-shift');

            $table->string('nama_shift', 50)
                ->nullable()
                ->comment('Nama shift (contoh: Subuh, Zuhur, Ashar)');

            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->text('lokasi')->nullable()->comment('Format: latitude,longitude');
            $table->enum('status', ['verified', 'failed'])->default('verified');
            $table->timestamps();

            // Foreign key
            $table->foreign('nik')
                ->references('nik')
                ->on('karyawan')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Indexes
            $table->index(['nik', 'tanggal', 'shift_ke'], 'idx_presensi_face_nik_tanggal_shift');
            $table->index('tanggal', 'idx_presensi_face_tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_face');
    }
};
