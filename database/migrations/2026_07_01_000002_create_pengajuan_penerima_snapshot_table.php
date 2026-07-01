<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Snapshot penerima individu/keluarga (dibekukan dari pengajuan_detail).
     */
    public function up(): void
    {
        Schema::create('pengajuan_penerima_snapshot', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->string('momen')->comment('diajukan | disetujui');
            $table->uuid('penduduk_id')->nullable()->comment('soft link ke penduduk, jangan dipakai untuk display');
            $table->string('nik')->nullable();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->decimal('nilai', 18, 2)->nullable();
            $table->boolean('is_valid')->default(false)->comment('status validasi penduduk saat momen');
            $table->unsignedTinyInteger('level_desil')->nullable();
            $table->timestamps();

            $table->index(['pengajuan_id', 'momen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_penerima_snapshot');
    }
};
