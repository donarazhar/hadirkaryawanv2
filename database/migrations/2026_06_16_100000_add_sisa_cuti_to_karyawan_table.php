<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            // Sisa cuti tahunan (default 12 hari per tahun)
            $table->tinyInteger('sisa_cuti')->unsigned()->default(12)->after('kode_cabang');
        });
    }

    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn('sisa_cuti');
        });
    }
};
