<?php

namespace App\Exports;

use App\Enums\JenisOrganisasi;
use App\Enums\JenisPengajuan;
use App\Enums\PengajuanStatus;
use App\Enums\RoleUser;
use App\Models\Organisasi;
use App\Models\Pengajuan;
use App\Models\Penduduk;
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
        private ?string $opd = null,
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
            'Kelompok Pemohon',
            'Anggota Kelompok',
            'NIK/Wilayah',
            'Desil Penduduk',
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
                'organisasi.organisasiDetail.penduduk',
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

        $rows = collect();
        $no = 0;

        foreach ($query->get() as $row) {
            $org = $row->organisasi;
            $isKelompok = $org && $org->jenis === JenisOrganisasi::KELOMPOK->value;

            // Kelompok masyarakat: satu baris per anggota kelompok.
            if ($isKelompok && $org->organisasiDetail->isNotEmpty()) {
                $wilayah = $this->wilayahLabel($org);

                foreach ($org->organisasiDetail as $detail) {
                    $anggota = $detail->penduduk;

                    $rows->push($this->buildRow(
                        $row,
                        ++$no,
                        $org->nama,
                        $anggota?->nama ?? '-',
                        $anggota?->nik ?? ($wilayah !== '' ? $wilayah : '-'),
                        $this->desilLabel($anggota),
                    ));
                }

                continue;
            }

            $penduduk = $row->details->first()?->penduduk;

            if ($org) {
                $pemohon = $org->nama;
                $nikWilayah = $this->wilayahLabel($org);
            } elseif ($penduduk) {
                $pemohon = $penduduk->nama;
                $nikWilayah = $penduduk->nik ?? '-';
            } else {
                $pemohon = '-';
                $nikWilayah = '-';
            }

            $rows->push($this->buildRow(
                $row,
                ++$no,
                $pemohon,
                '-',
                $nikWilayah !== '' ? $nikWilayah : '-',
                $this->desilLabel($penduduk),
            ));
        }

        return $rows;
    }

    private function buildRow(Pengajuan $row, int $no, string $pemohon, string $anggota, string $nikWilayah, string $desilLabel): array
    {
        $yaTidak = fn(?bool $v) => $v === null ? '-' : ($v ? 'Ya' : 'Tidak');
        $org = $row->organisasi;

        $keputusan = match ($row->status) {
            PengajuanStatus::DISETUJUI => 'Disetujui',
            PengajuanStatus::DITOLAK => 'Ditolak',
            default => '-',
        };

        $verifikasi = $row->verifikasiPengajuan;
        $tglVerifikasi = $row->verified_at ?? $verifikasi?->created_at;

        return [
            $no,
            $row->kode_pengajuan,
            $row->judul ?? '-',
            $row->kategori_pengajuan?->getDescription()
                ?? JenisPengajuan::fromJenisOrganisasi($org?->jenis)?->getDescription()
                ?? '-',
            $row->jenisBantuan?->nama ?? '-',
            $pemohon,
            $anggota !== '' ? $anggota : '-',
            $nikWilayah !== '' ? $nikWilayah : '-',
            $desilLabel,
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
    }

    private function wilayahLabel(Organisasi $org): string
    {
        return trim(
            ($org->desa?->nama ?? '')
            . ($org->desa?->kecamatan?->nama ? ', ' . $org->desa->kecamatan->nama : '')
        );
    }

    private function desilLabel(?Penduduk $penduduk): string
    {
        $desil = $penduduk?->level_desil;

        return $desil
            ? $desil->value . ' - ' . $desil->getDescription()
            : '-';
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
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $cell = $sheet->getCell("H{$row}");
                    $cell->setValueExplicit((string) $cell->getValue(), DataType::TYPE_STRING);
                }
            },
        ];
    }
}
