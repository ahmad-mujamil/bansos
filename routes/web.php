<?php

use App\Http\Controllers\BeritaPublikController;
use App\Http\Controllers\BlacklistInfoController;
use App\Http\Controllers\CariPendudukByNikController;
use App\Http\Controllers\Kelola\AlurBantuanController as KelolaAlurBantuanController;
use App\Http\Controllers\Kelola\BeritaController as KelolaBeritaController;
use App\Http\Controllers\Kelola\HeroSlideController as KelolaHeroSlideController;
use App\Http\Controllers\Kelola\ProfilKantorController as KelolaProfilKantorController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MonitoringBantuanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');

Route::get('/berita', [BeritaPublikController::class, 'index'])->name('berita.publik.index');
Route::get('/berita/{berita:slug}', [BeritaPublikController::class, 'show'])->name('berita.publik.show');

Auth::routes([
    'register' => false,
    'confirm' => false,
    'reset' => false,
]);

// Registration Routes
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::group(['middleware' => ['auth:web', 'check.perorangan.detail', 'ensure.user.active']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // User detail (lengkapi data diri untuk user yang belum verifikasi)
    Route::get('/user-detail', [App\Http\Controllers\UserDetailController::class, 'create'])->name('user-detail.create');
    Route::post('/user-detail', [App\Http\Controllers\UserDetailController::class, 'store'])->name('user-detail.store');
    Route::put('/user-detail', [App\Http\Controllers\UserDetailController::class, 'update'])->name('user-detail.update');

    // Kelompok baru dari user-detail (is_active = false), lengkapi anggota & dokumen
    Route::post('/user-detail/kelompok', [App\Http\Controllers\UserDetailController::class, 'storeKelompok'])->name('user-detail.kelompok.store');
    Route::get('/user-detail/kelompok/{organisasi}/lengkapi', [App\Http\Controllers\UserDetailKelompokController::class, 'lengkapi'])->name('user-detail.kelompok.lengkapi');
    Route::post('/user-detail/kelompok/{organisasi}/anggota', [App\Http\Controllers\UserDetailKelompokController::class, 'storeAnggota'])->name('user-detail.kelompok.anggota.store');
    Route::match(['DELETE'], '/user-detail/kelompok/{organisasi}/anggota/{anggota}', [App\Http\Controllers\UserDetailKelompokController::class, 'destroyAnggota'])->name('user-detail.kelompok.anggota.destroy');
    Route::post('/user-detail/kelompok/{organisasi}/dokumen', [App\Http\Controllers\UserDetailKelompokController::class, 'storeDokumen'])->name('user-detail.kelompok.dokumen.store');
    Route::match(['DELETE'], '/user-detail/kelompok/{organisasi}/dokumen/{dokumen}', [App\Http\Controllers\UserDetailKelompokController::class, 'destroyDokumen'])->name('user-detail.kelompok.dokumen.destroy');

    // Info Blacklist (user)
    Route::get('/blacklist-info', BlacklistInfoController::class)->name('blacklist.info');

    // Pengajuan (user)
    Route::middleware(['ensure.organisasi.not_blacklisted'])->group(function () {
        Route::get('/pengajuan', [App\Http\Controllers\PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [App\Http\Controllers\PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [App\Http\Controllers\PengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{pengajuan}', [App\Http\Controllers\PengajuanController::class, 'show'])->name('pengajuan.show');
        Route::get('/pengajuan/{pengajuan}/edit', [App\Http\Controllers\PengajuanController::class, 'edit'])->name('pengajuan.edit');
        Route::put('/pengajuan/{pengajuan}', [App\Http\Controllers\PengajuanController::class, 'update'])->name('pengajuan.update');
        Route::post('/pengajuan/{pengajuan}/submit', [App\Http\Controllers\PengajuanController::class, 'submit'])->name('pengajuan.submit');
    });
    // PROFILE (user)
    Route::get('/my-profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/my-profile', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');

    // Profil kantor / instansi (admin & super)
    Route::middleware(['role:super,admin'])->group(function () {
        Route::get('/profile', [KelolaProfilKantorController::class, 'edit'])->name('profil-kantor.edit');
        Route::put('/profile', [KelolaProfilKantorController::class, 'update'])->name('profil-kantor.update');
    });

    // SECURITY
    Route::get('/my-profile/security', [App\Http\Controllers\ProfileController::class, 'security'])->name('security.index');
    Route::put('/my-profile/security/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');

    // USERS
    Route::middleware(['role:super,admin'])->group(function () {
        Route::resource('pengguna', App\Http\Controllers\PenggunaController::class);
    });

    // USERS KELOMPOK
    Route::middleware(['role:super,opd'])->group(function () {
        Route::resource('user-kelompok', App\Http\Controllers\UserKelompokController::class)
            ->parameters(['user-kelompok' => 'userKelompok']);
    });

    // Kelola konten landing (berita)
    Route::middleware(['role:super,admin'])->prefix('kelola')->name('kelola.')->group(function () {
        Route::get('berita', [KelolaBeritaController::class, 'index'])->name('berita.index');
        Route::get('berita/create', [KelolaBeritaController::class, 'create'])->name('berita.create');
        Route::post('berita', [KelolaBeritaController::class, 'store'])->name('berita.store');
        Route::get('berita/{berita}/edit', [KelolaBeritaController::class, 'edit'])->name('berita.edit');
        Route::put('berita/{berita}', [KelolaBeritaController::class, 'update'])->name('berita.update');
        Route::delete('berita/{berita}', [KelolaBeritaController::class, 'destroy'])->name('berita.destroy');

        Route::get('slider', [KelolaHeroSlideController::class, 'index'])->name('slider.index');
        Route::get('slider/create', [KelolaHeroSlideController::class, 'create'])->name('slider.create');
        Route::post('slider', [KelolaHeroSlideController::class, 'store'])->name('slider.store');
        Route::get('slider/{heroSlide}/edit', [KelolaHeroSlideController::class, 'edit'])->name('slider.edit');
        Route::put('slider/{heroSlide}', [KelolaHeroSlideController::class, 'update'])->name('slider.update');
        Route::delete('slider/{heroSlide}', [KelolaHeroSlideController::class, 'destroy'])->name('slider.destroy');

        Route::get('alur-bantuan', [KelolaAlurBantuanController::class, 'edit'])->name('alur-bantuan.edit');

        Route::get('gallery', [\App\Http\Controllers\Kelola\GalleryController::class, 'index'])->name('gallery.index');
        Route::get('gallery/create', [\App\Http\Controllers\Kelola\GalleryController::class, 'create'])->name('gallery.create');
        Route::post('gallery', [\App\Http\Controllers\Kelola\GalleryController::class, 'store'])->name('gallery.store');
        Route::get('gallery/{gallery}/edit', [\App\Http\Controllers\Kelola\GalleryController::class, 'edit'])->name('gallery.edit');
        Route::put('gallery/{gallery}', [\App\Http\Controllers\Kelola\GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('gallery/{gallery}', [\App\Http\Controllers\Kelola\GalleryController::class, 'destroy'])->name('gallery.destroy');
    });

    // Verifikasi Pengguna (user belum aktif)
    Route::middleware(['role:super,admin,opd'])->group(function () {
        Route::get('verifikasi-pengguna', [App\Http\Controllers\VerifikasiPenggunaController::class, 'index'])->name('verifikasi-pengguna.index');
        Route::get('verifikasi-pengguna/{user}/aktifkan', [App\Http\Controllers\VerifikasiPenggunaController::class, 'aktifkan'])->name('verifikasi-pengguna.aktifkan');
        Route::get('verifikasi-pengguna/{user}', [App\Http\Controllers\VerifikasiPenggunaController::class, 'show'])->name('verifikasi-pengguna.show');
    });

    // Verifikasi Penduduk
    Route::middleware(['role:dukcapil,admin, opd'])->group(function () {
        Route::get('verifikasi-penduduk', [App\Http\Controllers\VerifikasiPendudukController::class, 'index'])->name('verifikasi-penduduk.index');
        Route::get('verifikasi-penduduk/{penduduk}', [App\Http\Controllers\VerifikasiPendudukController::class, 'show'])->name('verifikasi-penduduk.show');
        Route::post('verifikasi-penduduk/{penduduk}/verifikasi', [App\Http\Controllers\VerifikasiPendudukController::class, 'verifikasi'])->name('verifikasi-penduduk.verifikasi');
    });
    // Pengajuan (OPD)
    Route::middleware(['role:opd'])->group(function () {
        Route::get('/pengajuan-opd', [App\Http\Controllers\PengajuanOpdController::class, 'index'])->name('pengajuan-opd.index');
        Route::get('/pengajuan-opd/create', [App\Http\Controllers\PengajuanOpdController::class, 'create'])->name('pengajuan-opd.create');
        Route::get('/pengajuan-opd/penduduk-search', [App\Http\Controllers\PengajuanOpdController::class, 'searchPenduduk'])->name('pengajuan-opd.penduduk-search');
        Route::post('/pengajuan-opd', [App\Http\Controllers\PengajuanOpdController::class, 'store'])->name('pengajuan-opd.store');
        Route::get('/pengajuan-opd/{pengajuan}', [App\Http\Controllers\PengajuanOpdController::class, 'show'])->name('pengajuan-opd.show');
        Route::get('/pengajuan-opd/{pengajuan}/edit', [App\Http\Controllers\PengajuanOpdController::class, 'edit'])->name('pengajuan-opd.edit');
        Route::put('/pengajuan-opd/{pengajuan}', [App\Http\Controllers\PengajuanOpdController::class, 'update'])->name('pengajuan-opd.update');
        Route::post('/pengajuan-opd/{pengajuan}/submit', [App\Http\Controllers\PengajuanOpdController::class, 'submit'])->name('pengajuan-opd.submit');
    });

    // Verifikasi Pengajuan
    Route::middleware(['role:opd'])->group(function () {

        Route::get('verifikasi-pengajuan', [App\Http\Controllers\VerifikasiPengajuanController::class, 'index'])->name('verifikasi-pengajuan.index');
        Route::get('verifikasi-pengajuan/{pengajuan}', [App\Http\Controllers\VerifikasiPengajuanController::class, 'show'])->name('verifikasi-pengajuan.show');
        Route::post('verifikasi-pengajuan/{pengajuan}/verifikasi', [App\Http\Controllers\VerifikasiPengajuanController::class, 'verifikasi'])->name('verifikasi-pengajuan.verifikasi');
        Route::post('verifikasi-pengajuan/{pengajuan}/upload-ba', [App\Http\Controllers\VerifikasiPengajuanController::class, 'uploadBa'])->name('verifikasi-pengajuan.upload-ba');
        Route::get('verifikasi-pengajuan/{pengajuan}/download-ba', [App\Http\Controllers\VerifikasiPengajuanController::class, 'downloadBaVerifikasi'])->name('verifikasi-pengajuan.download-ba-verifikasi');
    });

    // Blacklist Kelompok/Organisasi (OPD)
    Route::middleware(['role:opd'])->group(function () {
        Route::get('blacklist', [App\Http\Controllers\BlacklistOrganisasiOpdController::class, 'index'])->name('blacklist.index');
        Route::post('blacklist/{organisasi}/toggle', [App\Http\Controllers\BlacklistOrganisasiOpdController::class, 'toggle'])->name('blacklist.toggle');
    });

    // Laporan (rekap pengajuan kelompok + hasil verifikasi)
    Route::middleware(['role:super,admin,opd'])->group(function () {
        Route::get('laporan-pengajuan', [App\Http\Controllers\LaporanPengajuanController::class, 'index'])->name('laporan-pengajuan.index');
        Route::get('laporan-pengajuan/{pengajuan}', [App\Http\Controllers\LaporanPengajuanController::class, 'show'])->name('laporan-pengajuan.show');
        Route::get('laporan-anggota-kelompok', [App\Http\Controllers\LaporanAnggotaKelompokController::class, 'index'])->name('laporan-anggota-kelompok.index');
        Route::get('laporan-realisasi', [App\Http\Controllers\LaporanRealisasiController::class, 'index'])->name('laporan-realisasi.index');
        Route::get('laporan-kelompok', [App\Http\Controllers\LaporanKelompokController::class, 'index'])->name('laporan-kelompok.index');
        Route::get('laporan-kelompok/preview', [App\Http\Controllers\LaporanKelompokController::class, 'preview'])->name('laporan-kelompok.preview');
        Route::get('laporan-kelompok/export', [App\Http\Controllers\LaporanKelompokController::class, 'export'])->name('laporan-kelompok.export');
        Route::get('laporan-kelompok/{organisasi}/anggota', [App\Http\Controllers\LaporanKelompokController::class, 'anggota'])->name('laporan-kelompok.anggota');
    });

    // Realisasi (dokumentasi laporan kegiatan; pengajuan yang sudah punya BAST)
    Route::permanentRedirect('realisasi-bantuan', '/realisasi');
    Route::get('realisasi', [App\Http\Controllers\RealisasiController::class, 'index'])->name('realisasi.index');
    Route::get('realisasi/{pengajuan}/create', [App\Http\Controllers\RealisasiController::class, 'create'])->name('realisasi.create');
    Route::post('realisasi/{pengajuan}', [App\Http\Controllers\RealisasiController::class, 'store'])->name('realisasi.store');
    Route::put('realisasi/{pengajuan}', [App\Http\Controllers\RealisasiController::class, 'update'])->name('realisasi.update');

    // BAST & monitoring (tahap menuju / setelah BAST)
    Route::middleware(['role:super,admin,opd'])->group(function () {
        Route::get('monitoring-bantuan', [MonitoringBantuanController::class, 'index'])->name('monitoring-bantuan.index');
        Route::resource('bast', App\Http\Controllers\BastController::class);
    });

    // MASTER DATA
    Route::middleware(['role:super,admin,opd'])->group(function () {
        Route::resource('kecamatan', App\Http\Controllers\KecamatanController::class);
        Route::resource('desa', App\Http\Controllers\DesaController::class);
        Route::resource('penduduk', App\Http\Controllers\PendudukController::class);
        Route::get('cari-penduduk-by-nik', CariPendudukByNikController::class)->name('penduduk.cari-by-nik');
        Route::resource('opd', App\Http\Controllers\OpdController::class);
        Route::resource('jenis-bantuan', App\Http\Controllers\JenisBantuanController::class);
        Route::resource('jenis-kelompok-masyarakat', App\Http\Controllers\JenisKelompokMasyarakatController::class);
        Route::get('kelompok-masyarakat/penduduk-by-nik', [App\Http\Controllers\KelompokMasyarakatController::class, 'lookupPendudukByNik'])->name('kelompok-masyarakat.penduduk-by-nik');
        Route::resource('kelompok-masyarakat', App\Http\Controllers\KelompokMasyarakatController::class);
        Route::resource('kelompok-masyarakat.anggota', App\Http\Controllers\KelompokMasyarakatAnggotaController::class)->except(['show']);
        Route::resource('kelompok-masyarakat.dokumen', App\Http\Controllers\KelompokMasyarakatDokumenController::class)->except(['show']);
    });
});
