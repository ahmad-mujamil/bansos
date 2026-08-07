<?php

use App\Enums\JenisPengajuan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisasi', function (Blueprint $table) {
            $table->string('jenis_pengajuan')->nullable()->after('jenis')
                ->comment('asal jenis pengajuan saat kelompok dibuat: bantuan_kelompok | subsidi_bunga | hibah | bansos');
        });

        // Backfill: turunkan dari jenis organisasi (KLP lama = bantuan_kelompok).
        DB::table('organisasi')->whereNull('jenis_pengajuan')->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    $jp = JenisPengajuan::fromJenisOrganisasi($row->jenis);
                    DB::table('organisasi')->where('id', $row->id)->update([
                        'jenis_pengajuan' => $jp?->value,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('organisasi', function (Blueprint $table) {
            $table->dropColumn('jenis_pengajuan');
        });
    }
};
