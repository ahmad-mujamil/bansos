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
        Schema::create('pengajuan_kelompok_snapshot', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->uuid('organisasi_id')->nullable()->comment('soft link ke organisasi, jangan dipakai untuk display');
            $table->string('momen')->comment('diajukan | disetujui');
            $table->string('nama_kelompok')->comment('nama kelompok yang dibekukan saat momen');
            $table->string('nomor')->nullable();
            $table->string('jenis')->nullable();
            $table->string('jenis_kelompok')->nullable();
            $table->date('tgl_pembentukan')->nullable();
            $table->string('wilayah_label')->nullable();
            $table->unsignedInteger('jumlah_anggota')->default(0);
            $table->foreignUuid('dibekukan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['pengajuan_id', 'momen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kelompok_snapshot');
    }
};
