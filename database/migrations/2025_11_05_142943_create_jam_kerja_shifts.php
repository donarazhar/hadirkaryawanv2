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
        Schema::create('jam_kerja_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jam_kerja', 10); // MUST match jam_kerja.kode_jam_kerja
            $table->integer('shift_ke')->comment('Shift ke berapa (1-5)');
            $table->string('nama_shift', 50)->comment('Contoh: Subuh, Zuhur, dll');
            $table->time('awal_jam_masuk')->comment('Awal jam masuk shift ini');
            $table->time('jam_masuk')->comment('Jam masuk normal shift ini');
            $table->time('akhir_jam_masuk')->comment('Batas akhir masuk shift ini');
            $table->time('jam_pulang')->comment('Jam pulang shift ini');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Add indexes
            $table->index('kode_jam_kerja');
            $table->unique(['kode_jam_kerja', 'shift_ke']);

            // Foreign key - NOW IT WILL WORK because jam_kerja.kode_jam_kerja is PRIMARY KEY!
            $table->foreign('kode_jam_kerja')
                ->references('kode_jam_kerja')
                ->on('jam_kerja')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_kerja_shifts');
    }
};
