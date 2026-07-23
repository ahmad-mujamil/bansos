<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisasi', function (Blueprint $table) {
            $table->smallInteger('tahun_anggaran')->nullable()->after('jenis')
                ->comment('tahun anggaran saat kelompok dibuat');
        });

        // Backfill dari tahun created_at (portable MySQL/SQLite).
        $tahunSekarang = (int) date('Y');
        DB::table('organisasi')->whereNull('tahun_anggaran')->orderBy('id')
            ->chunkById(500, function ($rows) use ($tahunSekarang) {
                foreach ($rows as $row) {
                    $tahun = $row->created_at ? (int) date('Y', strtotime((string) $row->created_at)) : $tahunSekarang;
                    DB::table('organisasi')->where('id', $row->id)->update(['tahun_anggaran' => $tahun]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('organisasi', function (Blueprint $table) {
            $table->dropColumn('tahun_anggaran');
        });
    }
};
