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
        Schema::create('pengajuan_anggota_snapshot', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('snapshot_id')->constrained('pengajuan_kelompok_snapshot')->cascadeOnDelete();
            $table->uuid('penduduk_id')->nullable()->comment('soft link ke penduduk, jangan dipakai untuk display');
            $table->string('nik')->nullable();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('jabatan')->nullable();
            $table->boolean('is_valid')->default(false)->comment('status validasi penduduk saat momen');
            $table->unsignedTinyInteger('level_desil')->nullable();
            $table->timestamps();

            $table->index('snapshot_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_anggota_snapshot');
    }
};
