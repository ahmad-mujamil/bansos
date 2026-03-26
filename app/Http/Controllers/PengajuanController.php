<?php

namespace App\Http\Controllers;

use App\Enums\JenisOrganisasi;
use App\Enums\JenisPengajuan;
use App\Enums\JenisUser;
use App\Enums\PengajuanStatus;
use App\Models\Organisasi;
use App\Models\Penduduk;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Models\PengajuanLog;
use App\Models\JenisBantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuan = auth()->user()
            ->pengajuan()
            ->latest()
            ->get();

        return view('pages.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        $jenisUser = auth()->user()->jenis_user?->value ?? null;
        $jenisOptions = match ($jenisUser) {
            'IND' => [JenisPengajuan::BANSOS],
            'KLP' => [JenisPengajuan::BANTUAN_KELOMPOK],
            default => [JenisPengajuan::HIBAH],
        };

        $kelompokList = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->where('is_active', true)
            ->orderBy('nama')
            ->get(['id', 'nama']);
        $pendudukList = Penduduk::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nik']);

          $jenis = auth()->user()->jenis_user?->value === JenisUser::INDIVIDUAL->value ? JenisPengajuan::BANSOS->value : JenisPengajuan::BANTUAN_KELOMPOK->value;

        $jenisBantuanKelompokList = JenisBantuan::query()
            ->where('kategori', 'bantuan_kelompok')
            ->orderBy('nama')
            ->get(['id', 'nama', 'keterangan']);

        return view('pages.pengajuan.form', [
            'pengajuan' => null,
            'kelompokList' => $kelompokList,
            'pendudukList' => $pendudukList,
            'jenisOptions' => $jenisOptions,
            'jenisBantuanKelompokList' => $jenisBantuanKelompokList,
            'jenis' => $jenis,
        ]);
    }

    public function store(Request $request)
    {

 
        $validated = $this->validatePengajuan($request);
        // return $request->all();
        try {
            DB::beginTransaction();
            $pengajuan = new Pengajuan();
            $pengajuan->user_id = auth()->id();
            $pengajuan->kode_pengajuan = $this->generateKodePengajuan();
            $pengajuan->jenis_bantuan_id = $validated['jenis_bantuan_id'] ?? null;
            $pengajuan->judul = $validated['judul'];
            $pengajuan->lokasi = $validated['lokasi'] ?? null;
            $pengajuan->nilai = $validated['nilai'];
            $pengajuan->opd_id = $validated['opd_id'];
            $pengajuan->organisasi_id = $validated['organisasi_id'];
            $pengajuan->status = PengajuanStatus::DRAFT;
            $pengajuan->save();

            if ($request->hasFile('file_pengajuan')) {
                $pengajuan->addMediaFromRequest('file_pengajuan')->toMediaCollection('pengajuan');
            }

            $this->logPengajuan($pengajuan, 'created', null, PengajuanStatus::DRAFT->value);
            DB::commit();
            toast()->success('Berhasil', 'Pengajuan berhasil disimpan.');
            return redirect()->route('pengajuan.index');
        } catch (\Throwable $e) {
            return $e;
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
            return back()->withInput();
        }
    }

    public function show(Pengajuan $pengajuan)
    {
        $this->authorizeUser($pengajuan);
        $pengajuan->load(['user', 'verifiedBy']);

        return view('pages.pengajuan.show', compact('pengajuan'));
    }

    public function edit(Pengajuan $pengajuan)
    {
        $this->authorizeUser($pengajuan);
        if (! $pengajuan->canEdit()) {
            toast()->warning('Tidak dapat diedit', 'Pengajuan ini tidak dapat diedit.');
            return redirect()->route('pengajuan.show', $pengajuan);
        }

        $jenisUser = auth()->user()->jenis_user?->value ?? null;
        $jenisOptions = match ($jenisUser) {
            'IND' => [JenisPengajuan::BANSOS],
            'KLP' => [JenisPengajuan::BANTUAN_KELOMPOK],
            default => [JenisPengajuan::HIBAH],
        };

        $kelompokList = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->where('is_active', true)
            ->orderBy('nama')
            ->get(['id', 'nama']);
        $pendudukList = Penduduk::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nik']);

        $jenisBantuanKelompokList = JenisBantuan::query()
            ->where('kategori', 'bantuan_kelompok')
            ->orderBy('nama')
            ->get(['id', 'nama', 'keterangan']);

        return view('pages.pengajuan.form', [
            'pengajuan' => $pengajuan,
            'kelompokList' => $kelompokList,
            'pendudukList' => $pendudukList,
            'jenisOptions' => $jenisOptions,
            'jenisBantuanKelompokList' => $jenisBantuanKelompokList,
            'jenis' => $pengajuan->jenis?->value,
        ]);
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeUser($pengajuan);
        if (! $pengajuan->canEdit()) {
            toast()->warning('Tidak dapat diedit', 'Pengajuan ini tidak dapat diedit.');
            return redirect()->route('pengajuan.index');
        }

        $validated = $this->validatePengajuan($request);

        try {
            DB::beginTransaction();
            $pengajuan->jenis_bantuan_id = $validated['jenis_bantuan_id'] ?? null;
            $pengajuan->save();

            if ($request->hasFile('file_pengajuan')) {
                $pengajuan->clearMediaCollection('pengajuan');
                $pengajuan->addMediaFromRequest('file_pengajuan')->toMediaCollection('pengajuan');
            }

            $this->logPengajuan($pengajuan, 'updated', $pengajuan->status->value, $pengajuan->status->value);
            DB::commit();
            toast()->success('Berhasil', 'Pengajuan berhasil diperbarui.');
            return redirect()->route('pengajuan.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
            return back()->withInput();
        }
    }

    public function submit(Pengajuan $pengajuan)
    {
        
        $this->authorizeUser($pengajuan);
        if (! $pengajuan->canSubmit()) {
            toast()->warning('Tidak dapat diajukan', 'Status pengajuan tidak memungkinkan untuk diajukan.');
            return redirect()->route('pengajuan.index');
        }

        $oldStatus = $pengajuan->status->value;
        $pengajuan->update(['status' => PengajuanStatus::DIAJUKAN]);
        $this->logPengajuan($pengajuan, 'status_changed', $oldStatus, PengajuanStatus::DIAJUKAN->value);
        toast()->success('Berhasil', 'Pengajuan berhasil diajukan.');
        return redirect()->route('pengajuan.index');
    }

    private function authorizeUser(Pengajuan $pengajuan): void
    {
        if ($pengajuan->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function generateKodePengajuan(): string
    {
        do {
            $kode = 'PEN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Pengajuan::where('kode_pengajuan', $kode)->exists());

        return $kode;
    }

    private function validatePengajuan(Request $request): array
    {
        $rules = [
            'jenis_bantuan_id' => 'nullable|exists:jenis_bantuan,id',
            'judul' => 'required|string|max:255',
            'opd_id' => 'required|exists:opd,id',
            'organisasi_id' => 'required|exists:organisasi,id',
            'lokasi' => 'nullable|string|max:255',
            'nilai' => 'required|numeric|min:0',
            'file_pengajuan' => 'nullable|file|mimes:pdf|max:5120',
            'jenis_bantuan_id' => 'nullable|exists:jenis_bantuan,id',
        ];

        return $request->validate($rules);
    }


    private function logPengajuan(Pengajuan $pengajuan, string $action, ?string $statusFrom, ?string $statusTo, ?string $catatan = null, ?array $metadata = null): void
    {
        PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'status_from' => $statusFrom,
            'status_to' => $statusTo,
            'catatan' => $catatan,
            'metadata' => $metadata,
        ]);
    }
}
