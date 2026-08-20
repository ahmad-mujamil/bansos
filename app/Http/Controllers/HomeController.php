<?php

namespace App\Http\Controllers;

use App\Enums\JenisPenerimaBantuan;
use App\Enums\JenisPengajuan;
use App\Enums\PengajuanStatus;
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

            $totalDiverifikasiSaya = Penduduk::query()
                ->where('validated_by', $user->id)
                ->whereNotNull('validated_at')
                ->count();
            $totalValid = Penduduk::query()
                ->where('validated_by', $user->id)
                ->where('is_valid', true)
                ->count();
            $totalTidakValid = Penduduk::query()
                ->where('validated_by', $user->id)
                ->where('is_valid', false)
                ->whereNotNull('validated_at')
                ->count();

            return view('home-dukcapil', compact(
                'totalPenduduk',
                'totalPendudukTerverifikasi',
                'totalDiverifikasiSaya',
                'totalValid',
                'totalTidakValid',
            ));
        }

        // dd($this->dashboardAdminData());
        // Dashboard admin / super
        return view('home', $this->dashboardAdminData());
    }

    /**
     * Daftar pengajuan detail di balik angka dashboard admin/super.
     *
     * Filter via query string:
     *  - jenis: bansos|hibah|bantuan_kelompok|subsidi_bunga
     *  - penerima: perorangan|organisasi
     *  - verif: usulan|verifikasi
     */
    public function detail()
    {
        $jenis = JenisPengajuan::tryFrom((string) request()->query('jenis'));
        $penerimaParam = (string) request()->query('penerima');
        $verifParam = (string) request()->query('verif');

        $statusEnum = PengajuanStatus::tryFrom((string) request()->query('status'));

        $kategori = $jenis?->value ?? 'all';
        $penerima = in_array($penerimaParam, ['perorangan', 'organisasi'], true) ? $penerimaParam : 'all';
        $verif = in_array($verifParam, ['usulan', 'verifikasi'], true) ? $verifParam : 'all';
        $status = $statusEnum?->value ?? 'all';

        $titleParts = [];

        if ($jenis) {
            $titleParts[] = match ($jenis) {
                JenisPengajuan::BANSOS => 'Bantuan Sosial',
                JenisPengajuan::HIBAH => 'Hibah',
                JenisPengajuan::BANTUAN_KELOMPOK => 'Bantuan ke Masyarakat',
                JenisPengajuan::SUBSIDI_BUNGA => 'Subsidi Bunga',
            };
        }
        if ($penerima === 'perorangan') {
            $titleParts[] = 'Perorangan';
        } elseif ($penerima === 'organisasi') {
            $titleParts[] = 'Organisasi';
        }
        if ($statusEnum) {
            $titleParts[] = $statusEnum->getDescription();
        }
        if ($verif === 'verifikasi') {
            $titleParts[] = 'Verifikasi BA';
        } elseif ($verif === 'usulan') {
            $titleParts[] = 'Proses Pengajuan';
        }

        $judul = 'Detail ' . (empty($titleParts) ? 'Semua Pengajuan' : implode(' · ', $titleParts));

        return view('pages.dashboard.detail', [
            'judul' => $judul,
            'kategori' => $kategori,
            'penerima' => $penerima,
            'verif' => $verif,
            'status' => $status,
        ]);
    }

    /**
     * Daftar organisasi teregistrasi sesuai jenis pengajuan (dipakai oleh metrik
     * "Teregistrasi" pada dashboard).
     */
    public function organisasi()
    {
        $jenis = JenisPengajuan::tryFrom((string) request()->query('jenis'));
        abort_if($jenis === null, 404);

        $pengajuanParam = (string) request()->query('pengajuan');
        $pengajuan = in_array($pengajuanParam, ['belum', 'sudah'], true) ? $pengajuanParam : 'all';

        $judulJenis = match ($jenis) {
            JenisPengajuan::BANSOS => 'Bantuan Sosial',
            JenisPengajuan::HIBAH => 'Hibah',
            JenisPengajuan::BANTUAN_KELOMPOK => 'Bantuan ke Masyarakat',
            JenisPengajuan::SUBSIDI_BUNGA => 'Subsidi Bunga',
        };

        $judulPengajuan = match ($pengajuan) {
            'belum' => ' · Belum Mengajukan',
            'sudah' => ' · Sudah Mengajukan',
            default => '',
        };

        return view('pages.dashboard.organisasi', [
            'jenis' => $jenis->value,
            'pengajuan' => $pengajuan,
            'judul' => 'Organisasi Teregistrasi · ' . $judulJenis . $judulPengajuan,
        ]);
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

        // Jumlah pengajuan per kategori per status (1 query)
        $pengajuanPerKategoriStatus = Pengajuan::query()
            ->selectRaw('kategori_pengajuan, status, COUNT(*) as total')
            ->groupBy('kategori_pengajuan', 'status')
            ->get()
            ->mapWithKeys(fn ($r) => [
                $r->getRawOriginal('kategori_pengajuan') . '|' . $r->getRawOriginal('status') => (int) $r->total,
            ]);

        $pengCount = fn (JenisPengajuan $k): int => (int) $pengajuanPerKategori->get($k->value, 0);
        $verifCount = fn (JenisPengajuan $k): int => (int) $verifikasiPerKategori->get($k->value, 0);
        $statusCount = fn (JenisPengajuan $k, PengajuanStatus $s): int => (int) ($pengajuanPerKategoriStatus[$k->value . '|' . $s->value] ?? 0);

        // Kartu total per jenis pengajuan (Total = Usulan + Verifikasi BA)
        $definisiKartu = [
            ['title' => 'Bansos', 'label' => 'Bantuan Sosial', 'chartLabel' => 'Bantuan Sosial', 'jenis' => JenisPengajuan::BANSOS],
            ['title' => 'Hibah', 'label' => 'Hibah', 'chartLabel' => 'Hibah', 'jenis' => JenisPengajuan::HIBAH],
            ['title' => 'BDSKM', 'label' => 'Bantuan ke Masyarakat', 'chartLabel' => 'BDSKM', 'jenis' => JenisPengajuan::BANTUAN_KELOMPOK],
            ['title' => 'Subsidi Bunga', 'label' => 'Subsidi Bunga', 'chartLabel' => 'Subsidi Bunga', 'jenis' => JenisPengajuan::SUBSIDI_BUNGA],
        ];

        // Teregistrasi = jumlah organisasi/kelompok yang jenisnya termasuk kategori pengajuan tsb.
        $organisasiTeregistrasiCount = function (JenisPengajuan $k): int {
            $jenisOrganisasi = array_map(fn ($j) => $j->value, $k->getJenisOrganisasi());

            return Organisasi::query()->whereIn('jenis', $jenisOrganisasi)->count();
        };

        // Jenis pengajuan yang teregistrasinya dihitung dari jumlah organisasi (bukan pengajuan).
        $teregistrasiDariOrganisasi = [JenisPengajuan::HIBAH, JenisPengajuan::BANTUAN_KELOMPOK, JenisPengajuan::BANSOS, JenisPengajuan::SUBSIDI_BUNGA];

        $kartuKategori = array_map(function (array $def) use ($pengCount, $verifCount, $statusCount, $organisasiTeregistrasiCount, $teregistrasiDariOrganisasi): array {
            $total = $pengCount($def['jenis']);
            $verifikasi = $verifCount($def['jenis']);

            // Hibah & BDSKM: teregistrasi dihitung dari jumlah organisasi/kelompok berjenis terkait.
            $dariOrganisasi = in_array($def['jenis'], $teregistrasiDariOrganisasi, true);
            $teregistrasi = $dariOrganisasi
                ? $organisasiTeregistrasiCount($def['jenis'])
                : $total;

            return [
                'title' => $def['title'],
                'label' => $def['label'],
                'chartLabel' => $def['chartLabel'],
                'jenis' => $def['jenis']->value,
                'total' => $total,
                'teregistrasi' => $teregistrasi,
                'teregistrasiOrganisasi' => $dariOrganisasi,
                'usulan' => max(0, $total - $verifikasi),
                'verifikasi' => $verifikasi,
                'diajukan' => $statusCount($def['jenis'], PengajuanStatus::DIAJUKAN),
                'disetujui' => $statusCount($def['jenis'], PengajuanStatus::DISETUJUI),
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
        $orgSubsidiBunga = $orgCount(JenisPengajuan::SUBSIDI_BUNGA);

        // Perorangan = pengajuan dengan penerima individu/keluarga, selain itu organisasi
        $totalPerorangan = Pengajuan::query()
            ->whereIn('jenis_penerima_bantuan', $jenisPerorangan)
            ->count();
        $totalOrganisasi = (int) $organisasiPerKategori->sum();
        $totalPengajuan = (int) $pengajuanPerKategori->sum();

        $pengBansos = $pengCount(JenisPengajuan::BANSOS);
        $pengHibah = $pengCount(JenisPengajuan::HIBAH);
        $pengKelompok = $pengCount(JenisPengajuan::BANTUAN_KELOMPOK);
        $pengSubsidiBunga = $pengCount(JenisPengajuan::SUBSIDI_BUNGA);

        // Chart usulan calon penerima bantuan per jenis — samakan dengan angka utama (Teregistrasi) tiap kartu
        $chartUsulan = [
            'labels' => array_map(fn (array $kartu): string => $kartu['chartLabel'], $kartuKategori),
            'values' => array_map(fn (array $kartu): int => $kartu['teregistrasi'], $kartuKategori),
        ];

        // Chart pengajuan: stacked per jenis — samakan dengan metrik kartu di atas
        $chartPengajuan = [
            'labels' => ['Proses Pengajuan SKPD', 'Verifikasi BA'],
            'series' => array_map(function (array $kartu): array {
                return [
                    'label' => $kartu['label'],
                    'data' => [$kartu['diajukan'], $kartu['disetujui']],
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
            'orgSubsidiBunga' => $orgSubsidiBunga,
            'totalPengajuan' => $totalPengajuan,
            'pengBansos' => $pengBansos,
            'pengHibah' => $pengHibah,
            'pengKelompok' => $pengKelompok,
            'pengSubsidiBunga' => $pengSubsidiBunga,
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
            ['title' => 'Bansos', 'label' => 'Bantuan Sosial', 'chartLabel' => 'Bantuan Sosial', 'jenis' => JenisPengajuan::BANSOS->value, 'total' => 26, 'teregistrasi' => 30, 'teregistrasiOrganisasi' => true, 'usulan' => 20, 'verifikasi' => 6, 'diajukan' => 12, 'disetujui' => 8],
            ['title' => 'Hibah', 'label' => 'Hibah', 'chartLabel' => 'Hibah', 'jenis' => JenisPengajuan::HIBAH->value, 'total' => 26, 'teregistrasi' => 40, 'teregistrasiOrganisasi' => true, 'usulan' => 20, 'verifikasi' => 6, 'diajukan' => 12, 'disetujui' => 8],
            ['title' => 'BDSKM', 'label' => 'Bantuan ke Masyarakat', 'chartLabel' => 'BDSKM', 'jenis' => JenisPengajuan::BANTUAN_KELOMPOK->value, 'total' => 26, 'teregistrasi' => 52, 'teregistrasiOrganisasi' => true, 'usulan' => 20, 'verifikasi' => 6, 'diajukan' => 12, 'disetujui' => 8],
            ['title' => 'Subsidi Bunga', 'label' => 'Subsidi Bunga', 'chartLabel' => 'Subsidi Bunga', 'jenis' => JenisPengajuan::SUBSIDI_BUNGA->value, 'total' => 18, 'teregistrasi' => 24, 'teregistrasiOrganisasi' => true, 'usulan' => 13, 'verifikasi' => 5, 'diajukan' => 9, 'disetujui' => 5],
        ];

        return [
            'kartuKategori' => $kartuKategori,
            'totalPerorangan' => 26,
            'totalOrganisasi' => 214,
            'orgHibah' => 74,
            'orgKelompok' => 140,
            'orgBansos' => 0,
            'orgSubsidiBunga' => 18,
            'totalPengajuan' => 214,
            'pengBansos' => 89,
            'pengHibah' => 7,
            'pengKelompok' => 45,
            'pengSubsidiBunga' => 18,
            'chartUsulan' => [
                'labels' => ['Bantuan Sosial', 'Hibah', 'BDSKM', 'Subsidi Bunga'],
                'values' => [30, 40, 52, 24],
            ],
            'chartPengajuan' => [
                'labels' => ['Proses Pengajuan SKPD', 'Verifikasi BA'],
                'series' => [
                    ['label' => 'Bantuan Sosial', 'data' => [12, 8]],
                    ['label' => 'Hibah', 'data' => [12, 8]],
                    ['label' => 'Bantuan ke Masyarakat', 'data' => [12, 8]],
                    ['label' => 'Subsidi Bunga', 'data' => [9, 5]],
                ],
            ],
            'isDemo' => true,
        ];
    }
}
