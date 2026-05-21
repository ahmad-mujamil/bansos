<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('history_verifikasi_penduduk', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('penduduk_id')->constrained('penduduk')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('status')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['penduduk_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history_verifikasi_penduduk');
    }
};
