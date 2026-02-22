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
        Schema::table('jenis_bantuan', function (Blueprint $table) {
            $table->string('kategori',20)->comment('dari enum kategori bantuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_bantuan', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
