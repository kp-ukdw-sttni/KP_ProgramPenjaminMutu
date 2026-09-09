<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_mutu', function (Blueprint $table) {
            // Root cause analysis by auditee
            $table->text('akar_masalah')->nullable()->after('rekomendasi');
            // Corrective action description (semantic replacement for rencana_tindak_lanjut)
            $table->text('tindak_lanjut')->nullable()->after('akar_masalah');
            // File path for uploaded evidence of corrective action
            $table->string('bukti_perbaikan')->nullable()->after('tindak_lanjut');
        });
    }

    public function down(): void
    {
        Schema::table('audit_mutu', function (Blueprint $table) {
            $table->dropColumn(['akar_masalah', 'tindak_lanjut', 'bukti_perbaikan']);
        });
    }
};
