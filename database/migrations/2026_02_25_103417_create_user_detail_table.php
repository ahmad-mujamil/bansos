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
        Schema::create('user_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('type'); // enum: JenisUser (IND, KLP, DS, INS, ORG, YYS, TIB)

            // DATA UMUM
            $table->string('nama_user')->nullable();
            $table->text('alamat')->nullable();
            $table->foreignUuid('desa_id')->nullable()->constrained('desa')->nullOnDelete();
            $table->string('phone', 20)->nullable();

            // KHUSUS PERORANGAN
            $table->string('nama_personal')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('file_ktp')->nullable();

            // KHUSUS YANG BUKAN INDIVIDU
            $table->string('nama_lembaga')->nullable();
            $table->string('file_surat_kuasa')->nullable();

            // VERIFIKASI
            $table->string('verification_status')->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->foreignUuid('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('verification_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_detail');
    }
};
