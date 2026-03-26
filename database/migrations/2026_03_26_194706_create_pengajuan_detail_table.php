<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Diisi jika pengajuan nya berupa uang baik bansos atau hibah
     */
    public function up(): void
    {
        Schema::create('pengajuan_detail', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid('pengajuan_id')->constrained('pengajuan');
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
        Schema::dropIfExists('pengajuan_detail');
    }
};
