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
            $table->string("disahkan_oleh")->nullable();
            $table->date("tgl_disahkan")->nullable();
            $table->string('lokasi_pengesahan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifikasi_pengajuan', function (Blueprint $table) {
            $table->dropColumn("disahkan_oleh");
            $table->dropColumn("tgl_disahkan");
            $table->dropColumn('lokasi_pengesahan');
        });
    }
};
