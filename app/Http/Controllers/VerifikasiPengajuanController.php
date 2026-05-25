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
use Dompdf\Dompdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiPengajuanController extends Controller
{
    private function data()
    {
        $statusRequest = (string) request('status', 'all');
        $allowedStatuses = [
            PengajuanStatus::DIAJUKAN->value,
            PengajuanStatus::DISETUJUI->value,
            PengajuanStatus::DITOLAK->value,
        ];

        $query = Pengajuan::query()
            ->with(['user', 'verifiedBy', 'logs', 'verifikasiPengajuan.media'])
            ->where('opd_id', Auth::user()->opd_id)
            ->latest();

        if ($statusRequest !== 'all' && in_array($statusRequest, $allowedStatuses, true)) {
            $query->where('status', $statusRequest);
        } elseif ($statusRequest !== 'all') {
            // Default to `diajukan` if filter value invalid.
            $query->where('status', PengajuanStatus::DIAJUKAN->value);
        } elseif ($statusRequest === 'all') {
            $query->whereIn('status', [PengajuanStatus::DIAJUKAN->value, PengajuanStatus::DISETUJUI->value, PengajuanStatus::DITOLAK->value]);
        }

        return DataTables::of($query)
            ->addColumn('kode_pengajuan', fn($row) => $row->kode_pengajuan)
            ->addColumn('jenis', fn($row) => $row->jenisBantuan?->nama ?? '-')
            ->addColumn('judul', fn($row) => $row->judul ?? '-')
            ->addColumn('status', function ($row) {
                $status = $row->status;
                $badge = $status?->badgeColor() ?? 'secondary';

                return '<span class="badge bg-' . $badge . '">' . e($status?->getDescription() ?? '-') . '</span>';
            })
            ->addColumn('tanggal', fn($row) => $row->created_at?->translatedFormat('d M Y') ?? '-')
            ->addColumn('user', fn($row) => $row->user?->nama ?? $row->user?->email ?? '-')
            ->addColumn('action', function ($row) {
                $lihat = route('verifikasi-pengajuan.show', $row->id);
                $label = $row->status === PengajuanStatus::DIAJUKAN ? 'Verifikasi' : 'Lihat';
                $action = "<a href='{$lihat}' class='btn btn-sm btn-outline-primary' title='{$label}'>{$label}</a>";

                $processed = in_array($row->status, [
                    PengajuanStatus::DISETUJUI,
                    PengajuanStatus::DITOLAK,
                ], true);

                if ($processed) {
                    $baMedia = $row->verifikasiPengajuan?->getFirstMedia('ba-verifikasi');
                    $downloadBaUrl = route('verifikasi-pengajuan.download-ba-verifikasi', $row->id);
                    $actionUpload = " <button type='button' class='btn btn-sm btn-outline-warning ms-1 btn-upload-ba' data-id='{$row->id}' title='Upload BA Verifikasi (hasil tanda tangan)'>Upload BA</button>";

                    if ($baMedia) {
                        $baUrl = $baMedia->getUrl();
                        $action .= " <a href='{$baUrl}' target='_blank' class='btn btn-sm btn-outline-success ms-1' title='Lihat File BA'>Lihat BA</a>";
                    } else {
                        $action .= " <a href='{$downloadBaUrl}' target='_blank' class='btn btn-sm btn-outline-success ms-1' title='Download BA Verifikasi (template)'>Download BA</a>";
                        $action .= $actionUpload;
                        $batalUrl = route('verifikasi-pengajuan.batal-verifikasi', $row->id);
                        $action .= " <button type='button' class='btn btn-sm btn-outline-danger ms-1 btn-batal-verifikasi' data-id='{$row->id}' data-url='{$batalUrl}' title='Batal Verifikasi'>Batal</button>";
                    }
                }

                return $action;
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
        $pengajuan->load(['user', 'verifiedBy', 'logs.user', 'details.penduduk', 'pemeriksa', 'organisasi', 'desa.kecamatan', 'verifikasiPengajuan.media']);

        $verifikasiIds = $pengajuan->logs
            ->map(fn(PengajuanLog $log) => $log->metadata['verifikasi_pengajuan_id'] ?? null)
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

        try {
            DB::beginTransaction();
            $verifikasi = VerifikasiPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::id(),
                'catatan' => $catatan,
                'disahkan_oleh' => $request->validated('disahkan_oleh'),
                'disahkan_nip' => $request->validated('disahkan_nip'),
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

                if ($rupa === RupaBantuan::UANG->value && ! $pengajuan->organisasi_id) {
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
            toast()->error('Gagal', $e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    public function batalVerifikasi(Pengajuan $pengajuan): RedirectResponse
    {
        $processed = in_array($pengajuan->status, [
            PengajuanStatus::DISETUJUI,
            PengajuanStatus::DITOLAK,
        ], true);

        if (! $processed) {
            toast()->error('Gagal', 'Pengajuan belum diverifikasi.');
            return redirect()->route('verifikasi-pengajuan.index');
        }

        $verifikasi = $pengajuan->verifikasiPengajuan;

        if ($verifikasi && $verifikasi->getFirstMedia('ba-verifikasi')) {
            toast()->error('Gagal', 'BA Verifikasi sudah diunggah. Pembatalan tidak dapat dilakukan.');
            return redirect()->route('verifikasi-pengajuan.index');
        }

        $oldStatus = $pengajuan->status;

        try {
            DB::beginTransaction();

            if ($verifikasi) {
                BantuanUang::where('verifikasi_pengajuan_id', $verifikasi->id)->delete();
                BantuanBarangJasa::where('verifikasi_pengajuan_id', $verifikasi->id)->delete();
                $verifikasi->delete();
            }

            PengajuanPemeriksa::where('pengajuan_id', $pengajuan->id)->delete();

            $pengajuan->status = PengajuanStatus::DIAJUKAN;
            $pengajuan->verified_at = null;
            $pengajuan->verified_by = null;
            $pengajuan->save();

            PengajuanLog::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::id(),
                'action' => 'batal_verifikasi',
                'status_from' => $oldStatus?->value,
                'status_to' => PengajuanStatus::DIAJUKAN->value,
                'catatan' => 'Verifikasi dibatalkan untuk perbaikan data.',
            ]);

            DB::commit();

            toast()->success('Berhasil', 'Verifikasi berhasil dibatalkan. Pengajuan kembali ke status Diajukan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
        }

        return redirect()->route('verifikasi-pengajuan.index');
    }

    public function uploadBa(Request $request, Pengajuan $pengajuan): RedirectResponse
    {
        $request->validate([
            'tgl_pengesahan' => ['required', 'date'],
            'dokumen' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $verifikasi = $pengajuan->verifikasiPengajuan;

        if (! $verifikasi) {
            toast()->error('Gagal', 'Data verifikasi tidak ditemukan untuk pengajuan ini.');

            return redirect()->route('verifikasi-pengajuan.index');
        }


        try {
            DB::beginTransaction();
            $verifikasi->update(['tgl_disahkan' => $request->tgl_pengesahan]);

            $verifikasi->addMediaFromRequest('dokumen')
                ->toMediaCollection('ba-verifikasi');

            DB::commit();

            toast()->success('Berhasil', 'Dokumen BA berhasil diunggah.');
        } catch (\Throwable $e) {
            toast()->error('Gagal', $e->getMessage());
        }

        return redirect()->route('verifikasi-pengajuan.index');
    }

    public function downloadBaVerifikasi(Pengajuan $pengajuan): \Symfony\Component\HttpFoundation\Response
    {
        $pengajuan->loadMissing([
            'user.userDetail',
            'verifiedBy',
            'pemeriksa',
            'organisasi',
            'organisasi.opd',
            'desa.kecamatan',
            'details.penduduk',
            'jenisBantuan',
            'verifikasiPengajuan',
        ]);

        $verifikasi = $pengajuan->verifikasiPengajuan;

        abort_unless($verifikasi instanceof VerifikasiPengajuan, 404);

        $pengajuanStatus = $pengajuan->status;
        abort_unless(in_array($pengajuanStatus, [
            PengajuanStatus::DISETUJUI,
            PengajuanStatus::DITOLAK,
        ], true), 404);

        $baMedia = $verifikasi->getFirstMedia('ba-verifikasi');
        if ($baMedia) {
            return response()->download(
                $baMedia->getPath(),
                $baMedia->file_name ?: 'BA-Verifikasi.pdf',
                ['Content-Type' => 'application/pdf'],
            );
        }

        $bantuanUang = BantuanUang::query()
            ->with('penduduk')
            ->where('verifikasi_pengajuan_id', $verifikasi->id)
            ->get();

        $bantuanBarangJasa = BantuanBarangJasa::query()
            ->where('verifikasi_pengajuan_id', $verifikasi->id)
            ->get();

        $rupaBantuan = $verifikasi->rupa_bantuan;

        $tahunAnggaran = $verifikasi->tgl_disahkan?->format('Y') ?: now()->format('Y');

        $alamat = trim(implode(', ', array_filter([
            $pengajuan->user?->userDetail?->alamat,
            $pengajuan->lokasi,
            $pengajuan->desa?->nama,
            $pengajuan->desa?->kecamatan?->nama,
        ])));

        $pemohon = $pengajuan->user?->userDetail?->nama_user
            ?? $pengajuan->user?->nama
            ?? $pengajuan->user?->email
            ?? '-';

        $namaKelompok = $pengajuan->organisasi?->nama
            ?? $pengajuan->details()->first()?->penduduk?->nama
            ?? '-';
        $kepalaSkpd = $verifikasi->disahkan_oleh ?? '-';
        $nipKepalaSkpd = $verifikasi->disahkan_nip ?? '-';
        $jumlahUsulan = 0;
        $jumlahDisetujui = 0;
        $satuan = '-';
        $spesifikasiTeknis = '-';

        if ($rupaBantuan === RupaBantuan::UANG) {
            $jumlahUsulan = (float) $pengajuan->nilai;
            $jumlahDisetujui = $pengajuanStatus === PengajuanStatus::DISETUJUI ? (float) $verifikasi->nilai_rekomendasi : 0;
            $jenisBarang = 'Uang';
        } elseif ($rupaBantuan) {
            $jumlahUsulan = $pengajuan->nilai;
            $jumlahDisetujui = $pengajuanStatus === PengajuanStatus::DISETUJUI ? $verifikasi->nilai_rekomendasi : 0;

            $satuan = $bantuanBarangJasa
                ->pluck('satuan')
                ->filter()
                ->unique()->implode(', ') ?: '-';

            $spesifikasiTeknis = $bantuanBarangJasa
                ->pluck('spesifikasi')
                ->filter()
                ->unique()->implode(', ') ?: '-';

            $jenisBarang = $bantuanBarangJasa->pluck('nama_barang')->filter()->unique()->implode(', ');
        }

        $pemeriksa = $pengajuan->pemeriksa;

        $nilaiBesarAngka = $verifikasi->nilai_rekomendasi !== null
            ? (int) round((float) $verifikasi->nilai_rekomendasi)
            : (int) round((float) $pengajuan->nilai);

        $nilaiBesar = number_format($nilaiBesarAngka, 0, ',', '.');
        $nilaiBesarTerbilang = trim(terbilang($nilaiBesarAngka) . ' rupiah');

        $html = view('pages.verifikasi-pengajuan.ba-verifikasi', [
            'pengajuan' => $pengajuan,
            'verifikasi' => $verifikasi,
            'pemeriksa' => $pemeriksa,
            'tahunAnggaran' => $tahunAnggaran,
            'alamat' => $alamat,
            'pemohon' => $pemohon,
            'namaKelompok' => $namaKelompok,
            'kepalaSkpd' => $kepalaSkpd,
            'nipKepalaSkpd' => $nipKepalaSkpd,
            'jenisBarang' => $jenisBarang,
            'spesifikasiTeknis' => $spesifikasiTeknis,
            'satuan' => $satuan,
            'jumlahUsulan' => $jumlahUsulan,
            'jumlahDisetujui' => $jumlahDisetujui,
            'nilaiBesar' => $nilaiBesar,
            'nilaiBesarTerbilang' => $nilaiBesarTerbilang,
        ])->render();

        $dompdf = new Dompdf([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();

        $fileName = 'BA-Verifikasi-' . ($pengajuan->kode_pengajuan ?: $pengajuan->id) . '.pdf';

        // $verifikasi->addMediaFromString($pdfContent)
        //     ->usingFileName($fileName)
        //     ->toMediaCollection('ba-verifikasi');

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
