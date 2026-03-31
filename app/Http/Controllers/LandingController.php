<?php

namespace App\Http\Controllers;

use App\Enums\JenisOrganisasi;
use App\Enums\JenisUser;
use App\Enums\KategoriBantuan;
use App\Enums\PengajuanStatus;
use App\Enums\RoleUser;
use App\Models\AlurBantuan;
use App\Models\Berita;
use App\Models\HeroSlide;
use App\Models\Organisasi;
use App\Models\Pengajuan;
use App\Models\ProfilKantor;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        $heroSlides = HeroSlide::query()
            ->active()
            ->ordered()
            ->get();

        $beritaTerbaru = Berita::query()
            ->published()
            ->with('kategoriBerita')
            ->latest('published_at')
            ->take(3)
            ->get();

        $pengajuanTerbaru = Pengajuan::query()
            ->where('status', '!=', PengajuanStatus::DRAFT->value)
            ->latest()
            ->take(8)
            ->get([
                'id',
                'kode_pengajuan',
                'judul',
                'lokasi',
                'nilai',
                'status',
                'created_at',
            ]);

        $totalPengajuanPublik = Pengajuan::query()
            ->where('status', '!=', PengajuanStatus::DRAFT->value)
            ->count();
        $totalDiajukan = Pengajuan::query()->where('status', PengajuanStatus::DIAJUKAN->value)->count();
        $totalDisetujui = Pengajuan::query()->where('status', PengajuanStatus::DISETUJUI->value)->count();
        $totalDitolak = Pengajuan::query()->where('status', PengajuanStatus::DITOLAK->value)->count();

        $alurBantuanByKategori = collect();
        if (Schema::hasTable('alur_bantuan')) {
            $alurBantuanByKategori = AlurBantuan::query()
                ->with('steps')
                ->get()
                ->keyBy(fn(AlurBantuan $alur) => $alur->kategori?->value ?? '');
        }

        $alurBantuanPublik = collect(KategoriBantuan::cases())
            ->map(function (KategoriBantuan $kategori) use ($alurBantuanByKategori): array {
                /** @var AlurBantuan|null $alur */
                $alur = $alurBantuanByKategori->get($kategori->value);

                return [
                    'key' => $kategori->value,
                    'label' => $kategori->getDescription(),
                    'steps' => $this->resolveSteps($alur),
                ];
            });

        $profilKantor = null;
        if (Schema::hasTable('profil_kantor')) {
            $profilKantor = ProfilKantor::query()->first();
        }

        $totalPenggunaIndividuAktif = 0;
        if (Schema::hasTable('users')) {
            $totalPenggunaIndividuAktif =  UserDetail::query()->where('type', JenisUser::INDIVIDUAL)->count();
        }

        $totalKelompokTerdaftar = 0;
        $totalOrganisasiTerdaftar = 0;
        if (Schema::hasTable('organisasi')) {
            $totalKelompokTerdaftar = Organisasi::query()
                ->where('jenis', JenisOrganisasi::KELOMPOK->value)
                ->where('is_active', true)
                ->count();

            $totalOrganisasiTerdaftar = Organisasi::query()
                ->whereIn('jenis', [
                    JenisOrganisasi::ORGANISASI->value,
                    JenisOrganisasi::YAYASAN->value,
                    JenisOrganisasi::TEMPAT_IBADAH->value,
                    JenisOrganisasi::INSTANSI->value,
                ])
                ->where('is_active', true)
                ->count();
        }

        return view('pages.landing', compact(
            'beritaTerbaru',
            'heroSlides',
            'pengajuanTerbaru',
            'totalPengajuanPublik',
            'totalDiajukan',
            'totalDisetujui',
            'totalDitolak',
            'alurBantuanPublik',
            'profilKantor',
            'totalPenggunaIndividuAktif',
            'totalKelompokTerdaftar',
            'totalOrganisasiTerdaftar',
        ));
    }

    /**
     * @return Collection<int, array{title: string, description: string, icon: string}>
     */
    private function resolveSteps(?AlurBantuan $alur): Collection
    {
        if ($alur === null) {
            return collect([
                ['title' => '1. Pendataan', 'description' => 'Petugas melakukan verifikasi data lapangan di lingkungan RT/RW setempat.', 'icon' => 'person_search'],
                ['title' => '2. Verifikasi', 'description' => 'Validasi data dilakukan oleh perangkat daerah terkait.', 'icon' => 'fact_check'],
                ['title' => '3. Penetapan', 'description' => 'Hasil verifikasi ditetapkan sebagai dasar pemberian bantuan.', 'icon' => 'gavel'],
                ['title' => '4. Penyaluran', 'description' => 'Bantuan disalurkan kepada penerima sesuai ketentuan yang berlaku.', 'icon' => 'payments'],
            ]);
        }

        if ($alur->steps->isNotEmpty()) {
            return $alur->steps
                ->map(fn($step): array => [
                    'title' => $step->judul,
                    'description' => $step->deskripsi,
                    'icon' => $this->resolveStepIcon($step->judul, $step->deskripsi, $step->icon),
                ])
                ->values();
        }

        return collect();
    }

    private function resolveStepIcon(string $title, string $description, ?string $icon): string
    {
        if (($icon ?? '') !== '') {
            return (string) $icon;
        }

        $text = mb_strtolower($title . ' ' . $description);

        return match (true) {
            str_contains($text, 'data'), str_contains($text, 'daftar'), str_contains($text, 'registrasi'), str_contains($text, 'pendataan') => 'person_search',
            str_contains($text, 'verifikasi'), str_contains($text, 'validasi'), str_contains($text, 'cek') => 'fact_check',
            str_contains($text, 'tetap'), str_contains($text, 'putusan'), str_contains($text, 'penetapan') => 'gavel',
            str_contains($text, 'salur'), str_contains($text, 'cair'), str_contains($text, 'transfer'), str_contains($text, 'bayar') => 'payments',
            default => 'task_alt',
        };
    }
}
