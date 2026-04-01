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
        Schema::create('log_blacklist_organisasi', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('organisasi_id')->constrained('organisasi')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('jadi_blacklist')->comment('true=blacklist, false=unblacklist');
            $table->string('alasan', 255)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['organisasi_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_blacklist_organisasi');
    }
};
