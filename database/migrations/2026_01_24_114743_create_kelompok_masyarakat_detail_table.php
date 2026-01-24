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
        Schema::create('kelompok_masyarakat_detail', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid('kelompok_masyarakat_id')->constrained("kelompok_masyarakat");
            $table->foreignUuid("penduduk_id")->constrained("penduduk");
            $table->string("jabatan")->comment("dari enum jabatan organisasi");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_masyarakat_detail');
    }
};
