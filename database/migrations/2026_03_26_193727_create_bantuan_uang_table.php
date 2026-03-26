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
        Schema::create('bantuan_uang', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("verifikasi_pengajuan_id")->constrained("verifikasi_pengajuan");
            $table->foreignUuid("penduduk_id")->constrained("penduduk");
            $table->decimal('nilai', 18, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuan_uang');
    }
};
