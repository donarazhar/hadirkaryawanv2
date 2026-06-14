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
        Schema::table('users', function (Blueprint $table) {
            $table->char('kode_dept', 10)->nullable()->after('kode_cabang');

            // Set foreign key
            $table->foreign('kode_dept')
                  ->references('kode_dept')->on('departemen')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kode_dept']);
            $table->dropColumn('kode_dept');
        });
    }
};
