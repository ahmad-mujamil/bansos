<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->smallInteger('tahun_anggaran')->nullable()->index()->after('status');
        });

        Schema::table('organisasi_detail', function (Blueprint $table) {
            $table->smallInteger('tahun_anggaran')->nullable()->after('jabatan');
            $table->index(['organisasi_id', 'tahun_anggaran']);
        });

        // Backfill dasar agar data lama langsung punya tahun (perkiraan).
        // Ditulis portable (PHP) agar jalan di MySQL maupun SQLite (test).
        $tahunSekarang = (int) date('Y');

        DB::table('pengajuan')->whereNull('tahun_anggaran')->orderBy('id')
            ->chunkById(500, function ($rows) use ($tahunSekarang) {
                foreach ($rows as $row) {
                    $ts = $row->verified_at ?: $row->created_at;
                    $tahun = $ts ? (int) date('Y', strtotime((string) $ts)) : $tahunSekarang;
                    DB::table('pengajuan')->where('id', $row->id)->update(['tahun_anggaran' => $tahun]);
                }
            });

        DB::table('organisasi_detail')->whereNull('tahun_anggaran')->update([
            'tahun_anggaran' => $tahunSekarang,
        ]);
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropIndex(['tahun_anggaran']);
            $table->dropColumn('tahun_anggaran');
        });

        Schema::table('organisasi_detail', function (Blueprint $table) {
            $table->dropIndex(['organisasi_id', 'tahun_anggaran']);
            $table->dropColumn('tahun_anggaran');
        });
    }
};
