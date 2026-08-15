<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengurus_lpm', function (Blueprint $table) {
            $table->id();
            $table->string('jabatan');
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->boolean('is_permanent')->default(false);
            $table->integer('urutan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengurus_lpm');
    }
};
