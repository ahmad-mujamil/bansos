<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->string('kategori_pengajuan', 30)->nullable()
                ->after('jenis_bantuan_id')
                ->comment('dari enum JenisPengajuan: hibah | bansos | bantuan_kelompok');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropColumn('kategori_pengajuan');
        });
    }
};
