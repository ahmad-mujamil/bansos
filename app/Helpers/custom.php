<?php

if (! function_exists('tahun_aktif')) {
    /**
     * Tahun anggaran yang sedang dipilih (konteks aplikasi).
     *
     * Nilai yang di-bind middleware (per-request) → tahun kalender berjalan.
     * Aman dipanggil sebelum migrasi/seed.
     */
    function tahun_aktif(): int
    {
        if (app()->bound('tahun_anggaran_terpilih')) {
            return (int) app('tahun_anggaran_terpilih');
        }

        return (int) date('Y');
    }
}

if (! function_exists('tahun_terkunci')) {
    /**
     * Apakah sebuah tahun anggaran terkunci (read-only). Default: tahun terpilih.
     */
    function tahun_terkunci(?int $tahun = null): bool
    {
        $tahun ??= tahun_aktif();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('tahun_anggaran')) {
                return (bool) \App\Models\TahunAnggaran::query()
                    ->where('tahun', $tahun)
                    ->value('is_terkunci');
            }
        } catch (\Throwable $e) {
            // anggap tidak terkunci bila tabel belum ada
        }

        return false;
    }
}

if (! function_exists('get_day_name')) {
    function get_day_name($day): string
    {
        $dayNames = get_days();

        return $dayNames[$day];
    }
}

if (! function_exists('get_days')) {
    function get_days(): array
    {
        return ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    }
}

if (! function_exists('terbilang')) {
    /**
     * Convert number to Indonesian words.
     * Example: 1000 => "seribu"
     */
    function terbilang(int $number): string
    {
        $negative = $number < 0;
        $number = abs($number);

        if ($number === 0) {
            return 'nol';
        }

        $digits = [
            0 => 'nol',
            1 => 'satu',
            2 => 'dua',
            3 => 'tiga',
            4 => 'empat',
            5 => 'lima',
            6 => 'enam',
            7 => 'tujuh',
            8 => 'delapan',
            9 => 'sembilan',
        ];

        $terbilangBelowThousand = function (int $n) use (&$digits, &$terbilangBelowThousand): string {
            if ($n === 0) {
                return '';
            }

            if ($n < 10) {
                return $digits[$n];
            }

            if ($n < 20) {
                if ($n === 10) {
                    return 'sepuluh';
                }
                if ($n === 11) {
                    return 'sebelas';
                }

                return $digits[$n - 10].' belas';
            }

            if ($n < 100) {
                $tens = intdiv($n, 10);
                $ones = $n % 10;

                $tensWordMap = [
                    2 => 'dua puluh',
                    3 => 'tiga puluh',
                    4 => 'empat puluh',
                    5 => 'lima puluh',
                    6 => 'enam puluh',
                    7 => 'tujuh puluh',
                    8 => 'delapan puluh',
                    9 => 'sembilan puluh',
                ];

                $tensWord = $tensWordMap[$tens] ?? ($digits[$tens].' puluh');

                if ($ones === 0) {
                    return $tensWord;
                }

                return $tensWord.' '.$terbilangBelowThousand($ones);
            }

            // 100..999
            $hundreds = intdiv($n, 100);
            $remainder = $n % 100;

            if ($hundreds === 1) {
                $hundredsWord = 'seratus';
            } else {
                $hundredsWord = $digits[$hundreds].' ratus';
            }

            if ($remainder === 0) {
                return $hundredsWord;
            }

            return $hundredsWord.' '.$terbilangBelowThousand($remainder);
        };

        $scales = [
            0 => '',
            1 => 'ribu',
            2 => 'juta',
            3 => 'miliar',
            4 => 'triliun',
            5 => 'kuadriliun',
            6 => 'kuintiliun',
        ];

        $parts = [];
        $scale = 0;
        while ($number > 0) {
            $group = $number % 1000;

            if ($group > 0) {
                if ($scale === 1) {
                    // 1000..1999: seribu, bukan satu ribu (untuk penulisan umum)
                    $parts[] = $group === 1 ? 'seribu' : $terbilangBelowThousand($group).' '.$scales[$scale];
                } else {
                    $scaleWord = $scales[$scale];
                    $parts[] = $scaleWord === '' ? $terbilangBelowThousand($group) : $terbilangBelowThousand($group).' '.$scaleWord;
                }
            }

            $number = intdiv($number, 1000);
            $scale++;
        }

        $result = implode(' ', array_reverse($parts));
        $result = preg_replace('/\s+/', ' ', trim($result));

        return $negative ? 'minus '.$result : $result;
    }
}
