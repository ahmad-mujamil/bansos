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
        Schema::create('pengajuan_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->foreignUuid('kelompok_id')->nullable()->constrained('organisasi')->nullOnDelete()->comment('jika bantuan tipe kelompok/hibah');
            $table->foreignUuid('penduduk_id')->nullable()->constrained('penduduk')->nullOnDelete()->comment('jika bansos');
            $table->string('judul_usulan');
            $table->text('latar_belakang')->nullable();
            $table->text('tujuan')->nullable();
            $table->string('lokasi_kegiatan')->nullable();
            $table->decimal('nilai_usulan', 18, 2)->default(0);
            $table->date('tanggal_pengajuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_detail');
    }
};
