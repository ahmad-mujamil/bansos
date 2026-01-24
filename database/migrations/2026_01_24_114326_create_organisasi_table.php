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
        Schema::create('organisasi', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('nama');
            $table->string('nomor',100)->unique()->comment('nomor sk/akta/kemenkumham');
            $table->date('tgl_pembentukan');
            $table->string('jenis')->comment('dari enum jenis organisasi');
            $table->foreignUuid('desa_id')->constrained("desa");
            $table->foreignUuid("kecamatan_id")->constrained("kecamatan");
            $table->foreignUuid("opd_id")->constrained("opd");
            $table->foreignUuid("user_id")->constrained('users');
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisasi');
    }
};
