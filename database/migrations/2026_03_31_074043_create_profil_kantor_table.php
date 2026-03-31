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
        Schema::create('profil_kantor', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi')->nullable();
            $table->string('kepala_dinas')->nullable();
            $table->string('nip_kepala_dinas', 30)->nullable();
            $table->string('sekdis')->nullable();
            $table->string('nip_sekdis', 30)->nullable();
            $table->text('lokasi_kantor')->nullable();
            $table->string('no_telepon', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_kantor');
    }
};
