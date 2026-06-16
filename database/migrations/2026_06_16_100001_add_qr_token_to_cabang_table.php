<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            $table->string('qr_token', 64)->nullable()->after('radius_cabang');
        });

        // Generate token for existing cabang
        $cabangs = DB::table('cabang')->get();
        foreach ($cabangs as $cabang) {
            DB::table('cabang')
                ->where('kode_cabang', $cabang->kode_cabang)
                ->update(['qr_token' => Str::random(32)]);
        }
    }

    public function down(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
};
