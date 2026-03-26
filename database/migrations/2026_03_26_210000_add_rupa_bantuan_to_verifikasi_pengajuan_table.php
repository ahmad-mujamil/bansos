<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verifikasi_pengajuan', function (Blueprint $table) {
            $table->string('rupa_bantuan')->nullable()->after('nilai_rekomendasi');
        });
    }

    public function down(): void
    {
        Schema::table('verifikasi_pengajuan', function (Blueprint $table) {
            $table->dropColumn('rupa_bantuan');
        });
    }
};
