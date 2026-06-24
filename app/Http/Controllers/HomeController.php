<?php

namespace App\Http\Controllers;

use App\Enums\JenisPenerimaBantuan;
use App\Enums\JenisPengajuan;
use App\Models\Organisasi;
use App\Models\Penduduk;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    public function index()
    {
        if (URL::previous() === route('login')) {
            toast()->success('Success !!', 'Berhasil masuk ke sistem.');
        }

        $user = auth()->user();

        if ($user->is_user()) {
            $user->load('userDetail.desa');

            $totalPengajuan = Pengajuan::query()->where('user_id', $user->id)->count();
            $totalVerifikasi = Pengajuan::query()->where('user_id', $user->id)->whereNotNull('verified_at')->count();
            $totalBelumVerifikasi = Pengajuan::query()->where('user_id', $user->id)->whereNull('verified_at')->count();
            $totalRealisasi = Pengajuan::query()
                ->where('user_id', $user->id)
                ->whereHas('realisasi')
                ->count();

            return view('home-user', compact(
                'totalPengajuan',
                'totalVerifikasi',
                'totalBelumVerifikasi',
                'totalRealisasi',
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
                    ->sum(fn (Pengajuan $pengajuan) => (float) ($pengajuan->verifikasiPengajuan?->nilai_rekomendasi ?? 0))
                : 0.0);
            $totalBlacklist = 0;

            $pendudukTidakValid = $opdId
                ? Penduduk::query()
                    ->where('is_valid', false)
                    ->whereNotNull('validated_at')
                    ->whereHas('organisasiDetails.organisasi', fn ($q) => $q->where('opd_id', $opdId))
                    ->with([
                        'organisasiDetails' => fn ($q) => $q
                            ->whereHas('organisasi', fn ($qq) => $qq->where('opd_id', $opdId))
                            ->with(['organisasi' => fn ($qq) => $qq->where('opd_id', $opdId)]),
                        'validatedBy:id,nama',
                    ])
                    ->orderByDesc('validated_at')
                    ->get()
                : collect();

            return view('home-opd', compact(
                'totalBlacklist',
                'totalOrganisasi',
                'totalBansos',
                'totalPengajuan',
                'pendudukTidakValid',
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

        // Dashboard admin / super
        return view('home', $this->dashboardAdminData());
    }

    /**
     * Data ringkasan dashboard untuk role admin/super.
     *
     * @return array<string, mixed>
     */
    private function dashboardAdminData(): array
    {
        // Mode demo: tampilkan data dummy untuk melihat model chart (akses ?demo=1)
        if (request()->boolean('demo')) {
            return $this->dummyDashboardData();
        }

        // Jumlah pengajuan per kategori (1 query)
        $pengajuanPerKategori = Pengajuan::query()
            ->selectRaw('kategori_pengajuan, COUNT(*) as total')
            ->groupBy('kategori_pengajuan')
            ->pluck('total', 'kategori_pengajuan');

        // Pengajuan yang sudah memiliki dokumen BA verifikasi, per kategori (1 query)
        $verifikasiPerKategori = Pengajuan::query()
            ->whereHas('verifikasiPengajuan.media', fn ($q) => $q->where('collection_name', 'ba-verifikasi'))
            ->selectRaw('kategori_pengajuan, COUNT(*) as total')
            ->groupBy('kategori_pengajuan')
            ->pluck('total', 'kategori_pengajuan');

        $pengCount = fn (JenisPengajuan $k): int => (int) $pengajuanPerKategori->get($k->value, 0);
        $verifCount = fn (JenisPengajuan $k): int => (int) $verifikasiPerKategori->get($k->value, 0);

        // Tiga kartu total per jenis pengajuan (Total = Usulan + Verifikasi BA)
        $definisiKartu = [
            ['title' => 'Total Bansos', 'label' => 'Bantuan Sosial', 'jenis' => JenisPengajuan::BANSOS],
            ['title' => 'Total Hibah', 'label' => 'Hibah', 'jenis' => JenisPengajuan::HIBAH],
            ['title' => 'Total BDSKM', 'label' => 'Bantuan ke Masyarakat', 'jenis' => JenisPengajuan::BANTUAN_KELOMPOK],
        ];

        $kartuKategori = array_map(function (array $def) use ($pengCount, $verifCount): array {
            $total = $pengCount($def['jenis']);
            $verifikasi = $verifCount($def['jenis']);

            return [
                'title' => $def['title'],
                'label' => $def['label'],
                'total' => $total,
                'usulan' => max(0, $total - $verifikasi),
                'verifikasi' => $verifikasi,
            ];
        }, $definisiKartu);

        // Penerima perorangan = jenis_penerima_bantuan individu/keluarga
        $jenisPerorangan = [JenisPenerimaBantuan::INDIVIDU->value, JenisPenerimaBantuan::KELUARGA->value];

        // Organisasi per jenis pengajuan = pengajuan dengan penerima selain individu/keluarga (1 query)
        $organisasiPerKategori = Pengajuan::query()
            ->whereNotIn('jenis_penerima_bantuan', $jenisPerorangan)
            ->selectRaw('kategori_pengajuan, COUNT(*) as total')
            ->groupBy('kategori_pengajuan')
            ->pluck('total', 'kategori_pengajuan');

        $orgCount = fn (JenisPengajuan $k): int => (int) $organisasiPerKategori->get($k->value, 0);
        $orgHibah = $orgCount(JenisPengajuan::HIBAH);
        $orgKelompok = $orgCount(JenisPengajuan::BANTUAN_KELOMPOK);
        $orgBansos = $orgCount(JenisPengajuan::BANSOS);

        // Perorangan = pengajuan dengan penerima individu/keluarga, selain itu organisasi
        $totalPerorangan = Pengajuan::query()
            ->whereIn('jenis_penerima_bantuan', $jenisPerorangan)
            ->count();
        $totalOrganisasi = (int) $organisasiPerKategori->sum();
        $totalPengajuan = (int) $pengajuanPerKategori->sum();

        $pengBansos = $pengCount(JenisPengajuan::BANSOS);
        $pengHibah = $pengCount(JenisPengajuan::HIBAH);
        $pengKelompok = $pengCount(JenisPengajuan::BANTUAN_KELOMPOK);

        // Chart usulan calon penerima bantuan per jenis
        $chartUsulan = [
            'labels' => ['Bantuan Sosial', 'Hibah', 'BDSKM'],
            'values' => [$pengBansos, $pengHibah, $pengKelompok],
        ];

        // Chart pengajuan: stacked (Usulan vs Sudah Verifikasi) per jenis
        $chartPengajuan = [
            'labels' => ['Pengajuan Usulan', 'Sudah Verifikasi'],
            'series' => array_map(function (array $kartu): array {
                return [
                    'label' => $kartu['label'],
                    'data' => [$kartu['usulan'], $kartu['verifikasi']],
                ];
            }, $kartuKategori),
        ];

        return [
            'kartuKategori' => $kartuKategori,
            'totalPerorangan' => $totalPerorangan,
            'totalOrganisasi' => $totalOrganisasi,
            'orgHibah' => $orgHibah,
            'orgKelompok' => $orgKelompok,
            'orgBansos' => $orgBansos,
            'totalPengajuan' => $totalPengajuan,
            'pengBansos' => $pengBansos,
            'pengHibah' => $pengHibah,
            'pengKelompok' => $pengKelompok,
            'chartUsulan' => $chartUsulan,
            'chartPengajuan' => $chartPengajuan,
            'isDemo' => false,
        ];
    }

    /**
     * Data dummy untuk preview tampilan dashboard (mode ?demo=1).
     *
     * @return array<string, mixed>
     */
    private function dummyDashboardData(): array
    {
        $kartuKategori = [
            ['title' => 'Total Bansos', 'label' => 'Bantuan Sosial', 'total' => 26, 'usulan' => 20, 'verifikasi' => 6],
            ['title' => 'Total Hibah', 'label' => 'Hibah', 'total' => 26, 'usulan' => 20, 'verifikasi' => 6],
            ['title' => 'Total BDSKM', 'label' => 'Bantuan ke Masyarakat', 'total' => 26, 'usulan' => 20, 'verifikasi' => 6],
        ];

        return [
            'kartuKategori' => $kartuKategori,
            'totalPerorangan' => 26,
            'totalOrganisasi' => 214,
            'orgHibah' => 74,
            'orgKelompok' => 140,
            'orgBansos' => 0,
            'totalPengajuan' => 214,
            'pengBansos' => 89,
            'pengHibah' => 7,
            'pengKelompok' => 45,
            'chartUsulan' => [
                'labels' => ['Bantuan Sosial', 'Hibah', 'BDSKM'],
                'values' => [78, 60, 45],
            ],
            'chartPengajuan' => [
                'labels' => ['Pengajuan Usulan', 'Sudah Verifikasi'],
                'series' => [
                    ['label' => 'Bantuan Sosial', 'data' => [8, 38]],
                    ['label' => 'Hibah', 'data' => [5, 19]],
                    ['label' => 'Bantuan ke Masyarakat', 'data' => [33, 26]],
                ],
            ],
            'isDemo' => true,
        ];
    }
}
