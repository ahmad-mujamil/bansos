<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sp2d', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pengajuan_id')->unique()->constrained('pengajuan')->cascadeOnDelete();
            $table->string('nomor');
            $table->date('tanggal');
            $table->decimal('nilai', 18, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete()
                ->comment('bendahara yang menginput/memeriksa SP2D');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sp2d');
    }
};
