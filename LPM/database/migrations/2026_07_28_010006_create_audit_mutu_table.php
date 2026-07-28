<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_mutu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluasi_diri_id')->constrained('evaluasi_diri')->cascadeOnDelete();
            $table->foreignId('auditor_id')->constrained('users')->cascadeOnDelete();
            // string, not enum – restriction enforced via PHP Enum + $casts
            $table->string('kategori_temuan', 30);
            $table->text('deskripsi_temuan');
            $table->text('rekomendasi')->nullable();
            $table->text('rencana_tindak_lanjut')->nullable();
            // string, not enum
            $table->string('status_audit', 20)->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_mutu');
    }
};
