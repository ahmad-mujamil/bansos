<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_anggaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->smallInteger('tahun')->unique();
            $table->string('label')->nullable();
            $table->boolean('is_aktif')->default(false)->comment('tahun default untuk data & konteks baru');
            $table->boolean('is_terkunci')->default(false)->comment('true = read-only, tidak bisa ditulis');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_anggaran');
    }
};
