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
        Schema::create('verifikasi_pengajuan', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid('pengajuan_id')->constrained('pengajuan');
            $table->foreignUuid('user_id')->comment('user yang melakukan verifikasi')->constrained('users');
            $table->text('catatan')->nullable();
            $table->decimal('nilai_rekomendasi', 18, 2)->nullable()->comment('nilai yang rekomendasi setelah verifikasi, bisa saja berbeda dengan nilai pengajuan awal');
            $table->boolean('lulus_kriteria')->default(false)->comment('true jika lulus kriteria, false jika tidak');
            $table->boolean('lulus_administrasi')->default(false)->comment('true jika lulus administrasi, false jika tidak');
            $table->boolean('lulus_kesesuaian')->default(false)->comment('true jika lulus kesesuaian kegiatan, false jika tidak');
            $table->boolean('sesuai_program_pemda')->default(false)->comment('true jika sesuai dengan program pemda, false jika tidak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi');
    }
};
