<?php

namespace App\Console\Commands;

use App\Enums\MomenSnapshot;
use App\Enums\PengajuanStatus;
use App\Models\Pengajuan;
use App\Services\PengajuanSnapshotService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillPengajuanSnapshot extends Command
{
    protected $signature = 'pengajuan:backfill-snapshot
                            {--force : Timpa snapshot yang sudah ada}';

    protected $description = 'Isi snapshot kelompok/penerima untuk pengajuan lama (perkiraan dari data saat ini).';

    public function handle(PengajuanSnapshotService $service): int
    {
        $this->warn('CATATAN: backfill memakai data kelompok/anggota SAAT INI sebagai perkiraan, '
            .'karena kondisi asli saat pengajuan lama tidak terekam.');

        $force = (bool) $this->option('force');

        $query = Pengajuan::query()->whereIn('status', [
            PengajuanStatus::DIAJUKAN->value,
            PengajuanStatus::DISETUJUI->value,
            PengajuanStatus::DITOLAK->value,
        ]);

        $total = (clone $query)->count();
        if ($total === 0) {
            $this->info('Tidak ada pengajuan yang perlu di-backfill.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $dibuat = 0;

        foreach ($query->cursor() as $pengajuan) {
            if ($pengajuan->status === PengajuanStatus::DISETUJUI) {
                // Cukup satu snapshot aktif: buat "disetujui" dan buang "diajukan" yang redundan.
                $dibuat += $this->ensure($service, $pengajuan, MomenSnapshot::DISETUJUI, $force);
                DB::transaction(fn () => $service->forget($pengajuan, MomenSnapshot::DIAJUKAN));
            } else {
                // Diajukan / ditolak → simpan snapshot momen "diajukan".
                $dibuat += $this->ensure($service, $pengajuan, MomenSnapshot::DIAJUKAN, $force);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Selesai. {$dibuat} snapshot dibuat/diperbarui dari {$total} pengajuan.");

        return self::SUCCESS;
    }

    private function ensure(PengajuanSnapshotService $service, Pengajuan $pengajuan, MomenSnapshot $momen, bool $force): int
    {
        $sudahAda = $pengajuan->kelompokSnapshots()->where('momen', $momen->value)->exists()
            || $pengajuan->penerimaSnapshots()->where('momen', $momen->value)->exists();

        if ($sudahAda && ! $force) {
            return 0;
        }

        DB::transaction(fn () => $service->capture($pengajuan, $momen));

        return 1;
    }
}
