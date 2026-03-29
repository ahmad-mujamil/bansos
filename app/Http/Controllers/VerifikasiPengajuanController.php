<?php

namespace App\Http\Controllers;

use App\Enums\PengajuanStatus;
use App\Enums\RupaBantuan;
use App\Http\Requests\VerifikasiPengajuanRequest;
use App\Models\BantuanBarangJasa;
use App\Models\BantuanUang;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use App\Models\PengajuanPemeriksa;
use App\Models\VerifikasiPengajuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiPengajuanController extends Controller
{
    public function badgeColor(?PengajuanStatus $status): string
    {
        return match ($status) {
            PengajuanStatus::DRAFT => 'secondary',
            PengajuanStatus::DIAJUKAN => 'info',
            PengajuanStatus::DISETUJUI => 'success',
            PengajuanStatus::DITOLAK => 'danger',
            default => 'secondary',
        };
    }

    private function getCatatanAttribute(): string
    {
        // Beberapa skema versi sebelumnya menggunakan `catatan_verifikator`.
        // Kita fallback ke kolom `catatan` yang tersedia.
        return Schema::hasColumn('pengajuan', 'catatan_verifikator') ? 'catatan_verifikator' : 'catatan';
    }

    private function data()
    {
        $statusRequest = (string) request('status', 'all');
        $allowedStatuses = [
            PengajuanStatus::DIAJUKAN->value,
            PengajuanStatus::DISETUJUI->value,
            PengajuanStatus::DITOLAK->value,
        ];

        $query = Pengajuan::query()
            ->with(['user', 'verifiedBy', 'logs'])
            ->where('opd_id', Auth::user()->opd_id)
            ->latest();

        if ($statusRequest !== 'all' && in_array($statusRequest, $allowedStatuses, true)) {
            $query->where('status', $statusRequest);
        } elseif ($statusRequest !== 'all') {
            // Default to `diajukan` if filter value invalid.
            $query->where('status', PengajuanStatus::DIAJUKAN->value);
        } elseif ($statusRequest == 'all') {
            $query->whereIn('status', [PengajuanStatus::DIAJUKAN->value, PengajuanStatus::DISETUJUI->value, PengajuanStatus::DITOLAK->value]);
        }

        return DataTables::of($query)
            ->addColumn('kode_pengajuan', fn ($row) => $row->kode_pengajuan)
            ->addColumn('jenis', fn ($row) => $row->jenisBantuan?->nama ?? '-')
            ->addColumn('judul', fn ($row) => $row->judul ?? '-')
            ->addColumn('status', function ($row) {
                $status = $row->status;
                $badge = $this->badgeColor($status);

                return '<span class="badge bg-'.$badge.'">'.e($status?->getDescription() ?? '-').'</span>';
            })
            ->addColumn('tanggal', fn ($row) => $row->created_at?->translatedFormat('d M Y') ?? '-')
            ->addColumn('user', fn ($row) => $row->user?->nama ?? $row->user?->email ?? '-')
            ->addColumn('action', function ($row) {
                $lihat = route('verifikasi-pengajuan.show', $row->id);

                return "<a href='{$lihat}' class='btn btn-sm btn-outline-primary' title='Lihat detail'>Lihat</a>";
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.verifikasi-pengajuan.index');
    }

    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load(['user', 'verifiedBy', 'logs.user', 'details.penduduk', 'pemeriksa']);

        $verifikasiIds = $pengajuan->logs
            ->map(fn (PengajuanLog $log) => $log->metadata['verifikasi_pengajuan_id'] ?? null)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $bantuanUangByVerifikasi = BantuanUang::query()
            ->with('penduduk')
            ->whereIn('verifikasi_pengajuan_id', $verifikasiIds)
            ->get()
            ->groupBy('verifikasi_pengajuan_id');

        $bantuanBarangJasaByVerifikasi = BantuanBarangJasa::query()
            ->whereIn('verifikasi_pengajuan_id', $verifikasiIds)
            ->get()
            ->groupBy('verifikasi_pengajuan_id');

        return view('pages.verifikasi-pengajuan.show', [
            'pengajuan' => $pengajuan,
            'bantuanUangByVerifikasi' => $bantuanUangByVerifikasi,
            'bantuanBarangJasaByVerifikasi' => $bantuanBarangJasaByVerifikasi,
        ]);
    }

    public function verifikasi(VerifikasiPengajuanRequest $request, Pengajuan $pengajuan): RedirectResponse
    {
        $oldStatus = $pengajuan->status;
        $catatan = $request->validated('catatan');

        $allPassed = (bool) $request->validated('lulus_kriteria')
            && (bool) $request->validated('lulus_administrasi')
            && (bool) $request->validated('lulus_kesesuaian')
            && (bool) $request->validated('sesuai_program_pemda');

        $newStatus = $allPassed ? PengajuanStatus::DISETUJUI : PengajuanStatus::DITOLAK;

        DB::beginTransaction();

        try {
            $verifikasi = VerifikasiPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::id(),
                'catatan' => $catatan,
                'nilai_rekomendasi' => $request->validated('nilai_rekomendasi'),
                'rupa_bantuan' => $request->validated('rupa_bantuan'),
                'lulus_kriteria' => (bool) $request->validated('lulus_kriteria'),
                'lulus_administrasi' => (bool) $request->validated('lulus_administrasi'),
                'lulus_kesesuaian' => (bool) $request->validated('lulus_kesesuaian'),
                'sesuai_program_pemda' => (bool) $request->validated('sesuai_program_pemda'),
            ]);

            foreach ($request->validated('pemeriksa') as $pemeriksaRow) {
                PengajuanPemeriksa::create([
                    'pengajuan_id' => $pengajuan->id,
                    'nama' => $pemeriksaRow['nama'],
                    'nip' => $pemeriksaRow['nip'],
                    'jabatan' => $pemeriksaRow['jabatan'],
                ]);
            }

            $pengajuan->verified_at = now();
            $pengajuan->verified_by = Auth::id();
            $pengajuan->status = $newStatus;
            $pengajuan->save();

            PengajuanLog::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::id(),
                'action' => 'verifikasi',
                'status_from' => $oldStatus?->value,
                'status_to' => $newStatus->value,
                'catatan' => $catatan,
                'metadata' => [
                    'verifikasi_pengajuan_id' => $verifikasi->id,
                ],
            ]);

            if ($newStatus === PengajuanStatus::DISETUJUI) {
                $rupa = $request->validated('rupa_bantuan');

                if (! $rupa) {
                    throw new \RuntimeException('Rupa bantuan wajib dipilih untuk pengajuan yang disetujui.');
                }

                if ($rupa === RupaBantuan::UANG->value) {
                    $details = $request->validated('detail') ?? [];

                    if (count($details) < 1) {
                        throw new \RuntimeException('Detail bantuan uang wajib diisi.');
                    }

                    foreach ($details as $row) {
                        BantuanUang::create([
                            'verifikasi_pengajuan_id' => $verifikasi->id,
                            'penduduk_id' => $row['penduduk_id'],
                            'nilai' => $row['nilai'],
                        ]);
                    }
                }

                if (in_array($rupa, [RupaBantuan::BARANG->value, RupaBantuan::JASA->value], true)) {
                    $items = $request->validated('items');

                    if (is_array($items) && count($items) > 0) {
                        foreach ($items as $item) {
                            BantuanBarangJasa::create([
                                'verifikasi_pengajuan_id' => $verifikasi->id,
                                'nama_barang' => $item['nama_barang'],
                                'satuan' => $item['satuan'],
                                'spesifikasi' => $item['spesifikasi'],
                                'harga_satuan' => $item['harga_satuan'],
                                'qty' => $item['qty'],
                            ]);
                        }
                    } else {
                        // Backward-compatible: support single-item payload.
                        $payload = $request->safe()->only(['nama_barang', 'satuan', 'spesifikasi', 'harga_satuan', 'qty']);

                        foreach (['nama_barang', 'satuan', 'spesifikasi', 'harga_satuan', 'qty'] as $field) {
                            if (! isset($payload[$field]) || $payload[$field] === '' || $payload[$field] === null) {
                                throw new \RuntimeException('Detail barang/jasa wajib diisi.');
                            }
                        }

                        BantuanBarangJasa::create([
                            'verifikasi_pengajuan_id' => $verifikasi->id,
                            'nama_barang' => $payload['nama_barang'],
                            'satuan' => $payload['satuan'],
                            'spesifikasi' => $payload['spesifikasi'],
                            'harga_satuan' => $payload['harga_satuan'],
                            'qty' => $payload['qty'],
                        ]);
                    }
                }
            }

            DB::commit();

            toast()->success('Berhasil', 'Pengajuan berhasil diverifikasi.');

            return redirect()->route('verifikasi-pengajuan.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());

            return redirect()->back()->withInput();
        }
    }
}
