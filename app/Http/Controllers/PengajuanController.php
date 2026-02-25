<?php

namespace App\Http\Controllers;

use App\Enums\JenisOrganisasi;
use App\Enums\JenisPengajuan;
use App\Enums\PengajuanStatus;
use App\Models\Organisasi;
use App\Models\Penduduk;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Models\PengajuanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuan = auth()->user()
            ->pengajuan()
            ->with(['details.kelompok', 'details.penduduk'])
            ->latest()
            ->get();

        return view('pages.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        $kelompokList = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->where('is_active', true)
            ->orderBy('nama')
            ->get(['id', 'nama']);
        $pendudukList = Penduduk::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nik']);

        return view('pages.pengajuan.form', [
            'pengajuan' => null,
            'kelompokList' => $kelompokList,
            'pendudukList' => $pendudukList,
            'jenisOptions' => JenisPengajuan::cases(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePengajuan($request);

        try {
            DB::beginTransaction();
            $pengajuan = new Pengajuan();
            $pengajuan->user_id = auth()->id();
            $pengajuan->kode_pengajuan = $this->generateKodePengajuan();
            $pengajuan->jenis = $validated['jenis'];
            $pengajuan->status = PengajuanStatus::DRAFT;
            $pengajuan->save();

            $this->saveDetail($pengajuan, $validated);
            $this->logPengajuan($pengajuan, 'created', null, PengajuanStatus::DRAFT->value);
            DB::commit();
            toast()->success('Berhasil', 'Pengajuan berhasil disimpan.');
            return redirect()->route('pengajuan.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
            return back()->withInput();
        }
    }

    public function show(Pengajuan $pengajuan)
    {
        $this->authorizeUser($pengajuan);
        $pengajuan->load(['details.kelompok', 'details.penduduk', 'user', 'verifiedBy']);

        return view('pages.pengajuan.show', compact('pengajuan'));
    }

    public function edit(Pengajuan $pengajuan)
    {
        $this->authorizeUser($pengajuan);
        if (! $pengajuan->canEdit()) {
            toast()->warning('Tidak dapat diedit', 'Pengajuan ini tidak dapat diedit.');
            return redirect()->route('pengajuan.show', $pengajuan);
        }

        $pengajuan->load('details');
        $kelompokList = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->where('is_active', true)
            ->orderBy('nama')
            ->get(['id', 'nama']);
        $pendudukList = Penduduk::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nik']);

        return view('pages.pengajuan.form', [
            'pengajuan' => $pengajuan,
            'kelompokList' => $kelompokList,
            'pendudukList' => $pendudukList,
            'jenisOptions' => JenisPengajuan::cases(),
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
            $pengajuan->jenis = $validated['jenis'];
            $pengajuan->save();

            $pengajuan->details()->delete();
            $this->saveDetail($pengajuan, $validated);
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
        $jenis = $request->input('jenis');
        $rules = [
            'jenis' => 'required|in:' . implode(',', array_column(JenisPengajuan::cases(), 'value')),
            'judul_usulan' => 'required|string|max:255',
            'latar_belakang' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'lokasi_kegiatan' => 'nullable|string|max:255',
            'nilai_usulan' => 'required|numeric|min:0',
            'tanggal_pengajuan' => 'nullable|date',
        ];

        if ($jenis === JenisPengajuan::BANSOS->value) {
            $rules['penduduk_id'] = 'required|exists:penduduk,id';
            $rules['kelompok_id'] = 'nullable';
        } else {
            $rules['kelompok_id'] = 'required|exists:organisasi,id';
            $rules['penduduk_id'] = 'nullable';
        }

        return $request->validate($rules);
    }

    private function saveDetail(Pengajuan $pengajuan, array $validated): void
    {
        $detail = new PengajuanDetail();
        $detail->pengajuan_id = $pengajuan->id;
        $detail->kelompok_id = $validated['kelompok_id'] ?? null;
        $detail->penduduk_id = $validated['penduduk_id'] ?? null;
        $detail->judul_usulan = $validated['judul_usulan'];
        $detail->latar_belakang = $validated['latar_belakang'] ?? null;
        $detail->tujuan = $validated['tujuan'] ?? null;
        $detail->lokasi_kegiatan = $validated['lokasi_kegiatan'] ?? null;
        $detail->nilai_usulan = $validated['nilai_usulan'];
        $detail->tanggal_pengajuan = $validated['tanggal_pengajuan'] ?? null;
        $detail->save();
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
