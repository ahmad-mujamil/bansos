<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('organisasi_blacklist_logs') && !Schema::hasTable('log_blacklist_organisasi')) {
            Schema::rename('organisasi_blacklist_logs', 'log_blacklist_organisasi');
        }

        if (Schema::hasTable('log_blacklist_organisasi')) {
            // Rename kolom ke Bahasa Indonesia tanpa bergantung pada doctrine/dbal
            // (gunakan SQL native yang tersedia di DB modern)
            try {
                DB::statement('ALTER TABLE log_blacklist_organisasi RENAME COLUMN to_blacklist TO jadi_blacklist');
            } catch (\Throwable $e) {
                // ignore jika sudah pernah direname / DB tidak mendukung syntax ini
            }

            try {
                DB::statement('ALTER TABLE log_blacklist_organisasi RENAME COLUMN reason TO alasan');
            } catch (\Throwable $e) {
                // ignore jika sudah pernah direname / DB tidak mendukung syntax ini
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('log_blacklist_organisasi')) {
            try {
                DB::statement('ALTER TABLE log_blacklist_organisasi RENAME COLUMN jadi_blacklist TO to_blacklist');
            } catch (\Throwable $e) {
                // ignore
            }

            try {
                DB::statement('ALTER TABLE log_blacklist_organisasi RENAME COLUMN alasan TO reason');
            } catch (\Throwable $e) {
                // ignore
            }
        }

        if (Schema::hasTable('log_blacklist_organisasi') && !Schema::hasTable('organisasi_blacklist_logs')) {
            Schema::rename('log_blacklist_organisasi', 'organisasi_blacklist_logs');
        }
    }
};
