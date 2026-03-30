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
        Schema::create('alur_bantuan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kategori')->unique();
            $table->timestamps();
        });

        Schema::create('alur_bantuan_step', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('alur_bantuan_id')->constrained('alur_bantuan')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan');
            $table->string('icon', 80)->nullable();
            $table->string('judul');
            $table->text('deskripsi');
            $table->timestamps();

            $table->unique(['alur_bantuan_id', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alur_bantuan_step');
        Schema::dropIfExists('alur_bantuan');
    }
};
