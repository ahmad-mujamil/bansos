<?php

namespace App\Http\Controllers;

use App\Enums\JenisUser;
use App\Enums\PengajuanStatus;
use App\Models\Organisasi;
use Illuminate\Support\Collection;
use App\Models\Penduduk;
use App\Models\Pengajuan;
use App\Models\PengajuanRealisasi;
use App\Models\UserDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    public function index()
    {
        if (URL::previous() === route('login')) {
            toast()->success('Success !!', 'Berhasil masuk ke sistem.');
        }

        $user = auth()->user();
        $year = (int) now()->year;

        if ($user->is_user()) {
            $user->load('userDetail.desa');

            $totalPengajuan = Pengajuan::query()->where('user_id', $user->id)->count();
            $totalVerifikasi = Pengajuan::query()->where('user_id', $user->id)->whereNotNull('verified_at')->count();
            $totalBelumVerifikasi = Pengajuan::query()->where('user_id', $user->id)->whereNull('verified_at')->count();
            $totalRealisasi = Pengajuan::query()
                ->where('user_id', $user->id)
                ->whereHas('realisasi')
                ->count();

            /** @var Collection<int, array{nama: string, nik: string, status: string}> $anggotaKelompokBelumTerverifikasi */
            $anggotaKelompokBelumTerverifikasi = collect();
            if ($user->jenis_user === JenisUser::KELOMPOK && ($oid = $user->userDetail?->organisasi_id)) {
                $anggotaKelompokBelumTerverifikasi = Organisasi::query()->find($oid)?->anggotaBelumTerverifikasiData() ?? collect();
            }

            return view('home-user', compact(
                'totalPengajuan',
                'totalVerifikasi',
                'totalBelumVerifikasi',
                'totalRealisasi',
                'anggotaKelompokBelumTerverifikasi',
            ));
        }

        if ($user->is_opd()) {
            $opdId = $user->opd_id;

            $totalOrganisasi = $opdId
                ? Organisasi::query()->where('opd_id', $opdId)->count()
                : 0;
            $totalPengajuan = $opdId
                ? Pengajuan::query()->where('opd_id', $opdId)->count()
                : 0;
            $totalBansos = (float) ($opdId
                ? Pengajuan::query()
                ->where('opd_id', $opdId)
                ->whereHas('realisasi')
                ->with('verifikasiPengajuan:id,pengajuan_id,nilai_rekomendasi')
                ->get()
                ->sum(fn(Pengajuan $pengajuan) => (float) ($pengajuan->verifikasiPengajuan?->nilai_rekomendasi ?? 0))
                : 0.0);
            $totalBlacklist = 0;

            return view('home-opd', compact(
                'totalBlacklist',
                'totalOrganisasi',
                'totalBansos',
                'totalPengajuan',
            ));
        }

        if ($user->is_dukcapil()) {
            $totalPenduduk = Penduduk::query()->count();
            $totalPendudukTerverifikasi = Penduduk::query()
                ->whereNotNull('validated_at')
                ->count();
            return view('home-dukcapil', compact(
                'totalPenduduk',
                'totalPendudukTerverifikasi'
            ));
        }

        $charts = $this->dashboardChartsForYear($year, null);

        $totalPerorangan = UserDetail::query()->where('type', JenisUser::INDIVIDUAL)->count();
        $totalOrganisasi = Organisasi::query()->count();
        $totalPengajuan = Pengajuan::query()->count();


        $totalBansos = (float) Pengajuan::query()
            ->whereHas('realisasi')
            ->with('verifikasiPengajuan:id,pengajuan_id,nilai_rekomendasi')
            ->get()
            ->sum(fn(Pengajuan $pengajuan) => (float) ($pengajuan->verifikasiPengajuan?->nilai_rekomendasi ?? 0));

        return view('home', array_merge(compact(
            'totalPerorangan',
            'totalOrganisasi',
            'totalBansos',
            'totalPengajuan',
        ), $charts));
    }

    /**
     * @return array{dataLabel: list<string>, dataChartPengajuan: array{labels: list<string>, data: array<string, list<int>>}, dataChartBansos: array{labels: list<string>, data: array<string, list<float>>}}
     */
    private function dashboardChartsForYear(int $year, ?string $opdId): array
    {
        $labels = $this->monthLabels($year);

        return [
            'dataLabel' => $labels,
            'dataChartPengajuan' => [
                'labels' => $labels,
                'data' => [
                    'Jumlah' => $this->pengajuanCountsPerMonth($year, $opdId),
                ],
            ],
            'dataChartBansos' => [
                'labels' => $labels,
                'data' => [
                    'Rupiah' => $this->realisasiNilaiPerMonth($year, $opdId),
                ],
            ],
        ];
    }

    /**
     * @return list<string>
     */
    private function monthLabels(int $year): array
    {
        return collect(range(1, 12))
            ->map(fn(int $month) => Carbon::create($year, $month, 1)->translatedFormat('F'))
            ->all();
    }

    /**
     * @return list<int>
     */
    private function pengajuanCountsPerMonth(int $year, ?string $opdId): array
    {
        $query = Pengajuan::query()
            ->whereYear('created_at', $year)
            ->when($opdId !== null, fn($q) => $q->where('opd_id', $opdId));

        $byMonth = [];
        foreach ($query->get(['created_at']) as $pengajuan) {
            $month = (int) $pengajuan->created_at->format('n');
            $byMonth[$month] = ($byMonth[$month] ?? 0) + 1;
        }

        return $this->twelveMonthSeriesInt($byMonth);
    }

    /**
     * @return list<float>
     */
    private function realisasiNilaiPerMonth(int $year, ?string $opdId): array
    {
        $query = PengajuanRealisasi::query()
            ->whereYear('tanggal_laporan', $year)
            ->with([
                'pengajuan' => fn($pengajuanQuery) => $pengajuanQuery
                    ->select(['id', 'opd_id'])
                    ->with([
                        'verifikasiPengajuan:id,pengajuan_id,nilai_rekomendasi',
                    ]),
            ]);

        $byMonth = [];
        foreach ($query->get() as $realisasi) {
            $pengajuan = $realisasi->pengajuan;
            if ($pengajuan === null) {
                continue;
            }
            if ($opdId !== null && (string) $pengajuan->opd_id !== (string) $opdId) {
                continue;
            }

            $nilaiRekomendasi = $pengajuan->verifikasiPengajuan?->nilai_rekomendasi;
            if ($nilaiRekomendasi === null) {
                continue;
            }

            $month = (int) $realisasi->tanggal_laporan->format('n');
            $byMonth[$month] = ($byMonth[$month] ?? 0.0) + (float) $nilaiRekomendasi;
        }

        return $this->twelveMonthSeriesFloat($byMonth);
    }

    /**
     * @param  array<int, int>  $byMonth
     * @return list<int>
     */
    private function twelveMonthSeriesInt(array $byMonth): array
    {
        $out = [];
        for ($m = 1; $m <= 12; $m++) {
            $out[] = (int) ($byMonth[$m] ?? 0);
        }

        return $out;
    }

    /**
     * @param  array<int, float>  $byMonth
     * @return list<float>
     */
    private function twelveMonthSeriesFloat(array $byMonth): array
    {
        $out = [];
        for ($m = 1; $m <= 12; $m++) {
            $out[] = (float) ($byMonth[$m] ?? 0.0);
        }

        return $out;
    }
}
