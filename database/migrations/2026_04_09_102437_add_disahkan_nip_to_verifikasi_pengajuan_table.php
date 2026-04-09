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
        Schema::table('verifikasi_pengajuan', function (Blueprint $table) {
            $table->string('disahkan_nip',20)->nullable()->after('disahkan_oleh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifikasi_pengajuan', function (Blueprint $table) {
            $table->dropColumn('disahkan_nip');
        });
    }
};
