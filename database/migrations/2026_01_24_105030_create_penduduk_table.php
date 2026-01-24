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
            $table->string('no_kk',25)->unique();
            $table->string('nama');
            $table->string('alamat');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('jk',1)->comment('L/P');
            $table->string('agama');
            $table->string('status_perkawinan');
            $table->string('pekerjaan');
            $table->string('pendidikan');
            $table->string('rt_rw',7)->comment('RT/RW');
            $table->foreignUuid('desa_id')->nullable()->constrained('desa')->nullOnDelete();
            $table->foreignUuid('kecamatan_id')->nullable()->constrained('kecamatan')->nullOnDelete();
            $table->tinyInteger('level_desil');
            $table->timestamps();
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
