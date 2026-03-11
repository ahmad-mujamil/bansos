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
        Schema::create('pengajuan_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('user yang melakukan aksi');
            $table->string('action')->comment('created, status_changed, updated, dll');
            $table->string('status_from')->nullable()->comment('status sebelum perubahan');
            $table->string('status_to')->nullable()->comment('status setelah perubahan');
            $table->text('catatan')->nullable();
            $table->json('metadata')->nullable()->comment('data tambahan misal old/new values');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_log');
    }
};
