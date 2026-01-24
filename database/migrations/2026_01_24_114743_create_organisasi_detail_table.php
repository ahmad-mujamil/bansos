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
        Schema::create('organisasi_detail', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid('organisasi_id')->constrained("organisasi");
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
        Schema::dropIfExists('organisasi_detail');
    }
};
