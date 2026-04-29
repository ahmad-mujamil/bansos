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
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropForeign(['organisasi_id']);
        });

        Schema::table('pengajuan', function (Blueprint $table) {
            $table->uuid('organisasi_id')->nullable()->change();
        });

        Schema::table('pengajuan', function (Blueprint $table) {
            $table->foreign('organisasi_id')
                ->references('id')
                ->on('organisasi')
                ->cascadeOnDelete();
        });

        Schema::table('pengajuan_detail', function (Blueprint $table) {
            $table->decimal('nilai', 18, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_detail', function (Blueprint $table) {
            $table->decimal('nilai', 18, 2)->nullable(false)->default(0)->change();
        });

        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropForeign(['organisasi_id']);
        });

        Schema::table('pengajuan', function (Blueprint $table) {
            $table->uuid('organisasi_id')->nullable(false)->change();
        });

        Schema::table('pengajuan', function (Blueprint $table) {
            $table->foreign('organisasi_id')
                ->references('id')
                ->on('organisasi')
                ->cascadeOnDelete();
        });
    }
};
