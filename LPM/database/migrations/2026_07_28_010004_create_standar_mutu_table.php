<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standar_mutu', function (Blueprint $table) {
            $table->id();
            $table->string('kode_standar', 30)->unique();
            $table->string('nama_standar');
            $table->text('deskripsi')->nullable();
            $table->text('indikator_kinerja')->nullable();
            $table->string('target_capaian', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standar_mutu');
    }
};
