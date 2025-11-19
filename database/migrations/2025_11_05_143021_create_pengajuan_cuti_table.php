<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_cuti', function (Blueprint $table) {
            $table->char('kode_cuti', 25)->primary()->comment('Kode unik cuti');
            $table->string('nama_cuti')->comment('Nama jenis cuti (Cuti Tahunan, Cuti Sakit, dll)');
            $table->integer('jml_hari')->default(0)->comment('Jumlah hari cuti yang diberikan');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->comment('Status cuti');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cuti');
    }
};
