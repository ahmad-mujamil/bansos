<?php

namespace App\Http\Controllers;

use App\Enums\JenisOrganisasi;
use App\Models\Organisasi;
use App\Models\OrganisasiDetail;
use App\Models\OrganisasiDokumen;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserDetailKelompokController extends Controller
{
    /**
     * Resolve organisasi yang boleh dikelola user (milik user, is_active = false).
     */
    private function resolveOrganisasi(string $organisasiId): Organisasi
    {
        $organisasi = Organisasi::query()
            ->where('id', $organisasiId)
            ->where('user_id', auth()->id())
            ->where('is_active', false)
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->with(['kecamatan', 'desa'])
            ->firstOrFail();

        return $organisasi;
    }

    /**
     * Halaman lengkapi data kelompok: anggota & dokumen.
     */
    public function lengkapi(string $organisasi)
    {
        $organisasi = $this->resolveOrganisasi($organisasi);
        $organisasi->loadCount(['organisasiDetail', 'organisasiDokumen']);
        $anggotas = $organisasi->organisasiDetail()->with('penduduk')->latest()->get();
        $dokumens = $organisasi->organisasiDokumen()->latest()->get();
        $penduduks = Penduduk::query()->orderBy('nama')->get();

        return view('pages.user-detail.kelompok.lengkapi', compact('organisasi', 'anggotas', 'dokumens', 'penduduks'));
    }

    /**
     * Simpan anggota baru (form di halaman lengkapi).
     */
    public function storeAnggota(Request $request, string $organisasi)
    {
        $organisasi = $this->resolveOrganisasi($organisasi);
        $validated = $request->validate([
            'penduduk_id' => [
                'required',
                'exists:penduduk,id',
                \Illuminate\Validation\Rule::unique('organisasi_detail', 'penduduk_id')
                    ->where('organisasi_id', $organisasi->id),
            ],
            'jabatan' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\JabatanOrganisasi::class)],
        ]);

        try {
            DB::beginTransaction();
            OrganisasiDetail::query()->create([
                ...$validated,
                'organisasi_id' => $organisasi->id,
            ]);
            DB::commit();
            toast()->success('Berhasil', 'Anggota berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
        }

        return redirect()->route('user-detail.create', ['organisasi_id' => $organisasi->id]);
    }

    /**
     * Hapus anggota.
     */
    public function destroyAnggota(string $organisasi, string $anggota)
    {
        $organisasi = $this->resolveOrganisasi($organisasi);
        $detail = OrganisasiDetail::query()
            ->where('organisasi_id', $organisasi->id)
            ->findOrFail($anggota);

        try {
            $detail->delete();
            toast()->success('Berhasil', 'Anggota berhasil dihapus.');
        } catch (\Throwable $e) {
            toast()->error('Gagal', $e->getMessage());
        }

        return redirect()->route('user-detail.create', ['organisasi_id' => $organisasi->id]);
    }

    /**
     * Simpan dokumen baru (form di halaman lengkapi).
     */
    public function storeDokumen(Request $request, string $organisasi)
    {
        $organisasi = $this->resolveOrganisasi($organisasi);
        $validated = $request->validate([
            'jenis_dokumen' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\JenisDokumen::class)],
            'keterangan' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:10240'],
        ]);

        try {
            DB::beginTransaction();
            $dokumen = OrganisasiDokumen::query()->create([
                'organisasi_id' => $organisasi->id,
                'keterangan' => $validated['keterangan'],
                'jenis_dokumen' => $validated['jenis_dokumen'],
            ]);
            if ($request->hasFile('file')) {
                $dokumen->addMediaFromRequest('file')->toMediaCollection('dokumen');
            }
            DB::commit();
            toast()->success('Berhasil', 'Dokumen berhasil diunggah.');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
        }

        return redirect()->route('user-detail.create', ['organisasi_id' => $organisasi->id]);
    }

    /**
     * Hapus dokumen.
     */
    public function destroyDokumen(string $organisasi, string $dokumen)
    {
        $organisasi = $this->resolveOrganisasi($organisasi);
        $row = OrganisasiDokumen::query()
            ->where('organisasi_id', $organisasi->id)
            ->findOrFail($dokumen);

        try {
            DB::beginTransaction();
            $row->clearMediaCollection('dokumen');
            $row->delete();
            DB::commit();
            toast()->success('Berhasil', 'Dokumen berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
        }

        return redirect()->route('user-detail.create', ['organisasi_id' => $organisasi->id]);
    }
}
