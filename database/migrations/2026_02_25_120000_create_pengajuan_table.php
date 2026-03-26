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
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_pengajuan')->unique();
            $table->foreignUuid('jenis_bantuan_id')->nullable()->constrained("jenis_bantuan");
            $table->string('judul');
            $table->string('lokasi')->nullable();
            $table->decimal('nilai', 18, 2)->default(0);
            $table->string('status')->default('draft')->comment('dari enum status pengajuan');
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('opd_id')->constrained('opd')->cascadeOnDelete();
            $table->foreignUuid('organisasi_id')->constrained('organisasi')->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->foreignUuid('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
