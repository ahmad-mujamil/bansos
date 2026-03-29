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
        Schema::create('penduduk', static function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('nik',25)->unique();
            $table->string('no_kk',25)->nullable();
            $table->string('nama');
            $table->string('alamat');
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('jk',1)->comment('L/P');
            $table->string('agama')->nullable();
            $table->string('status_perkawinan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('rt_rw',7)->nullable()->comment('RT/RW');
            $table->foreignUuid('desa_id')->nullable()->constrained('desa')->nullOnDelete();
            $table->foreignUuid('kecamatan_id')->nullable()->constrained('kecamatan')->nullOnDelete();
            $table->tinyInteger('level_desil')->nullable();
            $table->timestamps();

            $table->unique(['nik']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};
