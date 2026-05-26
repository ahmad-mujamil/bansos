<?php

namespace App\Imports;

use App\Enums\JenisKelamin;
use App\Enums\LevelDesil;
use App\Enums\StatusPerkawinan;
use App\Models\Desa;
use App\Models\HistoryVerifikasiPenduduk;
use App\Models\Penduduk;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PendudukImport implements ToCollection, WithHeadingRow, WithStartRow, WithMultipleSheets
{
    use Importable;

    /** Kolom data resmi pada sheet "Data Penduduk" — dipakai untuk mendeteksi baris kosong. */
    private const DATA_FIELDS = [
        'nik', 'no_kk', 'nama', 'alamat', 'tempat_lahir', 'tgl_lahir', 'jk', 'agama',
        'status_perkawinan', 'pekerjaan', 'pendidikan', 'rt_rw', 'desa', 'level_desil',
    ];

    public function sheets(): array
    {
        // Hanya proses sheet "Data Penduduk"; sheet "Referensi" diabaikan.
        return [
            'Data Penduduk' => $this,
        ];
    }

    public int $imported = 0;

    public int $skippedDuplicates = 0;

    public array $errors = [];

    /** @var array<string,int> NIK -> first row number, used to detect in-file duplicates */
    private array $seenNiks = [];

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows): void
    {
        // Pre-pass: collect all NIKs from the file & check duplicates within the file + DB
        $this->preCheckDuplicateNiks($rows);

        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                if ($this->isEmptyRow($row)) {
                    continue;
                }

                $nik = $this->normalizeNik($row['nik'] ?? null);
                if ($nik === '' || ! isset($this->seenNiks[$nik])) {
                    // NIK kosong → akan terdeteksi di validateRow.
                    // NIK tidak ada di seenNiks → sudah dilaporkan sebagai duplikat di preCheck.
                    if ($nik !== '' && ! isset($this->seenNiks[$nik])) {
                        continue;
                    }
                }

                $error = $this->validateRow($row, $rowNumber);
                if ($error !== null) {
                    $this->errors[] = $error;
                    continue;
                }

                $penduduk = Penduduk::query()->create($this->mapRow($row));

                HistoryVerifikasiPenduduk::create([
                    'penduduk_id' => $penduduk->id,
                    'user_id'     => Auth::id(),
                    'action'      => 'input',
                    'catatan'     => 'Data penduduk diinput melalui import Excel',
                ]);

                $this->imported++;
            }
        });
    }

    private function preCheckDuplicateNiks(Collection $rows): void
    {
        $niksInFile = [];

        foreach ($rows as $index => $row) {
            $nik = $this->normalizeNik($row['nik'] ?? null);
            if ($nik === '') {
                continue;
            }
            if (isset($niksInFile[$nik])) {
                // Duplikat dalam file — di-skip diam-diam
                $this->skippedDuplicates++;
                continue;
            }
            $niksInFile[$nik] = $index + 2;
        }

        if (empty($niksInFile)) {
            return;
        }

        $existing = Penduduk::query()->whereIn('nik', array_keys($niksInFile))->pluck('nik')->all();
        foreach ($existing as $nik) {
            // Sudah ada di DB — di-skip diam-diam
            $this->skippedDuplicates++;
            unset($niksInFile[$nik]);
        }

        $this->seenNiks = $niksInFile;
    }

    private function normalizeNik(mixed $value): string
    {
        if ($value === null) {
            return '';
        }
        // Excel may give NIK as float (e.g. 3.20101E+15); cast carefully
        if (is_numeric($value) && ! is_string($value)) {
            return number_format((float) $value, 0, '', '');
        }
        return trim((string) $value);
    }

    private function isEmptyRow(Collection $row): bool
    {
        foreach (self::DATA_FIELDS as $field) {
            $v = $row[$field] ?? null;
            if ($v !== null && $v !== '') {
                return false;
            }
        }
        return true;
    }

    private function validateRow(Collection $row, int $rowNumber): ?string
    {
        $required = ['nik', 'no_kk', 'nama', 'alamat', 'tempat_lahir', 'tgl_lahir', 'jk', 'agama', 'status_perkawinan', 'pekerjaan', 'pendidikan', 'rt_rw', 'level_desil'];
        foreach ($required as $field) {
            if (blank($row[$field] ?? null)) {
                return "Baris {$rowNumber}: kolom '{$field}' wajib diisi.";
            }
        }

        // Duplikat NIK (dalam file maupun di DB) sudah dilaporkan di preCheckDuplicateNiks
        // dan baris-baris bermasalah disaring di collection() sebelum sampai ke sini.

        if (! $this->parseJenisKelamin($row['jk'])) {
            return "Baris {$rowNumber}: kolom 'jk' harus L atau P.";
        }

        if (! $this->parseStatusPerkawinan($row['status_perkawinan'])) {
            return "Baris {$rowNumber}: kolom 'status_perkawinan' tidak valid (Belum Kawin / Kawin / Cerai Hidup / Cerai Mati).";
        }

        if (! $this->parseLevelDesil($row['level_desil'])) {
            return "Baris {$rowNumber}: kolom 'level_desil' harus angka 1-10.";
        }

        if (! $this->parseTanggal($row['tgl_lahir'])) {
            return "Baris {$rowNumber}: kolom 'tgl_lahir' bukan tanggal yang valid (format YYYY-MM-DD).";
        }

        if (filled($row['desa'] ?? null) && ! $this->findDesa($row['desa'])) {
            return "Baris {$rowNumber}: desa '{$row['desa']}' tidak ditemukan.";
        }

        return null;
    }

    private function mapRow(Collection $row): array
    {
        $desa = filled($row['desa'] ?? null) ? $this->findDesa($row['desa']) : null;

        return [
            'nik'               => $this->normalizeNik($row['nik']),
            'no_kk'             => $this->normalizeNik($row['no_kk']),
            'nama'              => (string) $row['nama'],
            'alamat'            => (string) $row['alamat'],
            'tempat_lahir'      => (string) $row['tempat_lahir'],
            'tgl_lahir'         => $this->parseTanggal($row['tgl_lahir']),
            'jk'                => $this->parseJenisKelamin($row['jk'])->value,
            'agama'             => (string) $row['agama'],
            'status_perkawinan' => $this->parseStatusPerkawinan($row['status_perkawinan'])->value,
            'pekerjaan'         => (string) $row['pekerjaan'],
            'pendidikan'        => (string) $row['pendidikan'],
            'rt_rw'             => (string) $row['rt_rw'],
            'desa_id'           => $desa?->id,
            'kecamatan_id'      => $desa?->kecamatan_id,
            'level_desil'       => $this->parseLevelDesil($row['level_desil'])->value,
        ];
    }

    private function parseJenisKelamin(mixed $value): ?JenisKelamin
    {
        $v = strtoupper(trim((string) $value));
        return match ($v) {
            'L', 'LAKI-LAKI', 'LAKI LAKI', 'PRIA' => JenisKelamin::LAKI_LAKI,
            'P', 'PEREMPUAN', 'WANITA'            => JenisKelamin::PEREMPUAN,
            default                                => null,
        };
    }

    private function parseStatusPerkawinan(mixed $value): ?StatusPerkawinan
    {
        $v = Str::title(trim((string) $value));
        foreach (StatusPerkawinan::cases() as $case) {
            if (strcasecmp($case->value, $v) === 0) {
                return $case;
            }
        }
        return null;
    }

    private function parseLevelDesil(mixed $value): ?LevelDesil
    {
        if (! is_numeric($value)) {
            return null;
        }
        return LevelDesil::tryFrom((int) $value);
    }

    private function parseTanggal(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
            }
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    /** @var array<string, ?Desa> in-memory cache keyed by lowercased desa name */
    private array $desaCache = [];

    private function findDesa(mixed $nama): ?Desa
    {
        $nama = strtolower(trim((string) $nama));
        if ($nama === '') {
            return null;
        }
        if (array_key_exists($nama, $this->desaCache)) {
            return $this->desaCache[$nama];
        }
        return $this->desaCache[$nama] = Desa::whereRaw('LOWER(nama) = ?', [$nama])->first();
    }
}
