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
        Schema::table('perorangan_detail', static function (Blueprint $table) {
            $table->string('penerima_bantuan_lain')->nullable()->after('status_dtks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perorangan_detail', function (Blueprint $table) {
            $table->dropColumn('penerima_bantuan_lain');
        });
    }
};
