<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi_diri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standar_mutu_id')->constrained('standar_mutu')->cascadeOnDelete();
            $table->foreignId('program_studi_id')->constrained('program_studi')->cascadeOnDelete();
            $table->string('tahun_akademik', 20); // e.g. "2024/2025"
            // string, not enum – restriction enforced via PHP Enum + $casts
            $table->string('semester', 20)->default('Ganjil');
            $table->string('capaian_aktual', 255)->nullable();
            $table->text('deskripsi_ketercapaian')->nullable();
            $table->string('file_bukti_fisik')->nullable();
            // string, not enum
            $table->string('status', 20)->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_diri');
    }
};
