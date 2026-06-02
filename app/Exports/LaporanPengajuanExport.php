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
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPengajuanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle, WithColumnFormatting, WithEvents
{
    public function __construct(
        private ?string $kategori = null,
        private ?string $status = null,
    ) {}

    public function title(): string
    {
        return 'Laporan Pengajuan';
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Pengajuan',
            'Judul',
            'Jenis Pengajuan',
            'Jenis Bantuan',
            'Pemohon',
            'NIK/Wilayah',
            'OPD',
            'Nilai Usulan',
            'Status',
            'Tanggal Pengajuan',
            'Keputusan',
            'Nilai Rekomendasi',
            'Lulus Kriteria',
            'Lulus Administrasi',
            'Lulus Kesesuaian',
            'Sesuai Program Pemda',
            'Catatan Verifikasi',
            'Verifikator',
            'Tanggal Verifikasi',
        ];
    }

    public function collection(): Collection
    {
        $query = Pengajuan::query()
            ->with([
                'organisasi.desa.kecamatan',
                'jenisBantuan',
                'details.penduduk',
                'verifikasiPengajuan.user',
                'verifiedBy',
                'opd',
            ])
            ->latest();

        $user = Auth::user();
        if ($user?->role === RoleUser::OPD) {
            $query->where('opd_id', $user->opd_id);
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

        $yaTidak = fn(?bool $v) => $v === null ? '-' : ($v ? 'Ya' : 'Tidak');

        return $query->get()->values()->map(function (Pengajuan $row, int $i) use ($yaTidak) {
            $org = $row->organisasi;
            $penduduk = $row->details->first()?->penduduk;

            if ($org) {
                $pemohon = $org->nama;
                $nikWilayah = trim(
                    ($org->desa?->nama ?? '')
                    . ($org->desa?->kecamatan?->nama ? ', ' . $org->desa->kecamatan->nama : '')
                );
            } elseif ($penduduk) {
                $pemohon = $penduduk->nama;
                $nikWilayah = $penduduk->nik ?? '-';
            } else {
                $pemohon = '-';
                $nikWilayah = '-';
            }

            $keputusan = match ($row->status) {
                PengajuanStatus::DISETUJUI => 'Disetujui',
                PengajuanStatus::DITOLAK => 'Ditolak',
                default => '-',
            };

            $verifikasi = $row->verifikasiPengajuan;
            $tglVerifikasi = $row->verified_at ?? $verifikasi?->created_at;

            return [
                $i + 1,
                $row->kode_pengajuan,
                $row->judul ?? '-',
                $row->kategori_pengajuan?->getDescription()
                    ?? JenisPengajuan::fromJenisOrganisasi($org?->jenis)?->getDescription()
                    ?? '-',
                $row->jenisBantuan?->nama ?? '-',
                $pemohon,
                $nikWilayah !== '' ? $nikWilayah : '-',
                $row->opd?->nama ?? '-',
                (float) $row->nilai,
                $row->status?->getDescription() ?? '-',
                $row->created_at?->translatedFormat('d M Y') ?? '-',
                $keputusan,
                $verifikasi?->nilai_rekomendasi !== null ? (float) $verifikasi->nilai_rekomendasi : '-',
                $yaTidak($verifikasi?->lulus_kriteria),
                $yaTidak($verifikasi?->lulus_administrasi),
                $yaTidak($verifikasi?->lulus_kesesuaian),
                $yaTidak($verifikasi?->sesuai_program_pemda),
                $verifikasi?->catatan ?? '-',
                $verifikasi?->user?->nama ?? $row->verifiedBy?->nama ?? '-',
                $tglVerifikasi?->translatedFormat('d M Y H:i') ?? '-',
            ];
        });
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $cell = $sheet->getCell("G{$row}");
                    $cell->setValueExplicit((string) $cell->getValue(), DataType::TYPE_STRING);
                }
            },
        ];
    }
}
