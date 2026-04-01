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
        Schema::table('opd', function (Blueprint $table) {
            $table->string("alamat");
            $table->string("no_telp");
            $table->string("email");
            $table->string("website");
            $table->string("fax");
            $table->string("kepala_opd");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd', function (Blueprint $table) {
            $table->dropColumn("alamat");
            $table->dropColumn("no_telp");
            $table->dropColumn("email");
            $table->dropColumn("website");
            $table->dropColumn("fax");
            $table->dropColumn("kepala_opd");
        });
    }
};
