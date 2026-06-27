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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->char('nik', 10)->primary(); // PASTIKAN CHAR(10)
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('jabatan', 20)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('password');
            $table->string('remember_token', 255)->nullable();
            $table->string('foto', 30)->nullable();
            $table->foreignId('organ_id')->nullable()->constrained('organs')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};