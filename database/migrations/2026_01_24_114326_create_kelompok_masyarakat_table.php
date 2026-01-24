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
        Schema::create('kelompok_masyarakat', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('nama');
            $table->string('nomor_sk',100)->unique()->comment('nomor sk kelompok masyarakat');
            $table->date('tgl_pembentukan');
            $table->foreignUuid('desa_id')->constrained("desa");
            $table->foreignUuid("kecamatan_id")->constrained("kecamatan");
            $table->foreignUuid("opd_id")->constrained("opd");
            $table->foreignUuid("user_id")->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_masyarakat');
    }
};
