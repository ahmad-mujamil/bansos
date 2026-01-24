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
        Schema::create('organisasi_dokumen', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid('organisasi_id')->constrained("organisasi");
            $table->string('keterangan')->comment('keterangan dokumen');
            $table->string("jenis_dokumen")->comment('dari enum jenis dokumen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisasi_dokumen');
    }
};
