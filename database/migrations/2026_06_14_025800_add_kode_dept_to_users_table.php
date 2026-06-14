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
        if (!Schema::hasColumn('users', 'kode_dept')) {
            Schema::table('users', function (Blueprint $table) {
                $table->char('kode_dept', 10)->nullable()->after('kode_cabang');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'kode_dept')) {
                $table->dropColumn('kode_dept');
            }
        });
    }
};
