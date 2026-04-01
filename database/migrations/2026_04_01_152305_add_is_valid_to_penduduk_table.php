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
        Schema::table('penduduk', function (Blueprint $table) {
            $table->boolean('is_valid')->default(false);
            $table->dateTime('validated_at')->nullable();
            $table->foreignUuid('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('catatan_validasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->dropColumn('is_valid');
            $table->dropColumn('catatan_validasi');
            $table->dropColumn('validated_at');
            $table->dropConstrainedForeignId('validated_by');
        });
    }
};
