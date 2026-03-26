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
        Schema::create('bantuan_barang_jasa', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid('verifikasi_pengajuan_id')->constrained("verifikasi_pengajuan");
            $table->string('nama_barang');
            $table->string('satuan');
            $table->text('spesifikasi')->comment('format: ukuran, bahan, dll');
            $table->decimal('harga_satuan', 18, 2);
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuan_barang_jasa');
    }
};
