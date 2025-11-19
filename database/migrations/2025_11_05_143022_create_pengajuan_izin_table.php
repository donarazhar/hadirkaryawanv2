<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_izin', function (Blueprint $table) {
            $table->char('kode_izin', 25)->primary();
            $table->char('nik', 10);
            $table->char('kode_cuti', 25)->nullable();
            $table->date('tgl_izin_dari');
            $table->date('tgl_izin_sampai');
            $table->char('status', 1)->comment('i=izin, s=sakit, c=cuti');
            $table->text('keterangan');
            $table->char('status_approved', 1)->default('0');
            $table->string('doc_sid')->nullable();
            $table->timestamps();

            $table->index('nik');
            $table->index('status');
            $table->index('tgl_izin_dari');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin');
    }
};
