<?php

namespace App\Exports;

use App\Enums\JenisPengajuan;
use App\Enums\PengajuanStatus;
use App\Enums\RoleUser;
use App\Models\Pengajuan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPengajuanRekapExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    public function __construct(
        private ?string $kategori = null,
        private ?string $status = null,
        private ?string $opd = null,
        private ?string $bulan = null,
    ) {}

    public function title(): string
    {
        return 'Rekap Pengajuan';
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Pengajuan',
            'OPD',
            'Judul',
            'Jenis Pengajuan',
            'Pemohon (Kelompok/Individu)',
            'Status',
            'Nilai Usulan',
            'Nilai Rekomendasi',
        ];
    }

    public function collection(): Collection
    {
        $query = Pengajuan::query()
            ->with(['organisasi', 'details.penduduk', 'verifikasiPengajuan', 'jenisBantuan', 'opd'])
            ->latest();

        $user = Auth::user();
        if ($user?->role === RoleUser::OPD) {
            $query->where('opd_id', $user->opd_id);
        } elseif ($this->opd && $this->opd !== 'all') {
            $query->where('opd_id', $this->opd);
        }

        $kategori = $this->kategori ?? JenisPengajuan::BANSOS->value;
        if ($kategori !== 'all' && JenisPengajuan::tryFrom($kategori) !== null) {
            $query->where(function ($q) use ($kategori) {
                $q->where('kategori_pengajuan', $kategori)
                    ->orWhere(function ($q2) use ($kategori) {
                        $q2->whereNull('kategori_pengajuan')
                            ->whereHas('jenisBantuan', fn ($jb) => $jb->where('kategori', $kategori));
                    });
            });
        }

        if ($this->status && $this->status !== 'all' && PengajuanStatus::tryFrom($this->status) !== null) {
            $query->where('status', $this->status);
        }

        // Periode bulan; tahun mengikuti Tahun Anggaran terpilih (via global scope).
        if ($this->bulan && $this->bulan !== 'all' && in_array((int) $this->bulan, range(1, 12), true)) {
            $query->whereMonth('created_at', (int) $this->bulan);
        }

        $rows = collect();
        $no = 0;

        foreach ($query->get() as $row) {
            $org = $row->organisasi;
            if ($org) {
                $pemohon = $org->nama.' (Kelompok)';
            } else {
                $namaIndividu = $row->details->first()?->penduduk?->nama;
                $pemohon = $namaIndividu ? $namaIndividu.' (Individu)' : '-';
            }

            $jenisPengajuan = $row->kategori_pengajuan?->getDescription()
                ?? JenisPengajuan::fromJenisOrganisasi($org?->jenis)?->getDescription()
                ?? '-';

            $rows->push([
                ++$no,
                $row->kode_pengajuan,
                $row->opd?->nama ?? '-',
                $row->judul ?? '-',
                $jenisPengajuan,
                $pemohon,
                $row->status?->getDescription() ?? '-',
                (float) $row->nilai,
                $row->verifikasiPengajuan?->nilai_rekomendasi !== null
                    ? (float) $row->verifikasiPengajuan->nilai_rekomendasi
                    : '-',
            ]);
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
