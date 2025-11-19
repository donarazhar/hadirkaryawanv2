<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('face_data', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->unique();
            $table->text('face_descriptor'); // JSON array dari face-api.js
            $table->string('face_image')->nullable(); // Path foto referensi
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('enrollment_count')->default(1);
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();

            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_data');
    }
};