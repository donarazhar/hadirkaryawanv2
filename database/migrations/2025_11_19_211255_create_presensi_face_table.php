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
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->text('lokasi')->nullable(); // Format: latitude,longitude
            $table->enum('status', ['verified', 'failed'])->default('verified');
            $table->timestamps();

            // Foreign key
            $table->foreign('nik')
                ->references('nik')
                ->on('karyawan')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Indexes
            $table->index(['nik', 'tanggal'], 'idx_presensi_face_nik_tanggal');
            $table->index('tanggal', 'idx_presensi_face_tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_face');
    }
};
