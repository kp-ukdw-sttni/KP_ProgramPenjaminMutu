<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluasi_diri', function (Blueprint $table) {
            // Auditor-assigned score: 1 (Sangat Kurang) to 4 (Sangat Baik)
            $table->tinyInteger('nilai')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('evaluasi_diri', function (Blueprint $table) {
            $table->dropColumn('nilai');
        });
    }
};
