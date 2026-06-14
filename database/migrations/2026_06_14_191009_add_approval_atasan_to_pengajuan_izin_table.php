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
        Schema::table('pengajuan_izin', function (Blueprint $table) {
            $table->char('status_approved_atasan', 1)->default('0')->comment('0: Pending, 1: Disetujui, 2: Ditolak')->after('status_approved');
            $table->text('catatan_atasan')->nullable()->after('catatan_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_izin', function (Blueprint $table) {
            $table->dropColumn('status_approved_atasan');
            $table->dropColumn('catatan_atasan');
        });
    }
};
