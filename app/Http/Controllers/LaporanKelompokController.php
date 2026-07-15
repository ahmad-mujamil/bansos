<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKelompokExport;
use App\Models\Opd;
use App\Models\Organisasi;
use App\Models\TahunAnggaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LaporanKelompokController extends Controller
{
    private function baseQuery()
    {
        $q = Organisasi::query()
            ->leftJoin('opd', 'opd.id', '=', 'organisasi.opd_id')
            ->select('organisasi.*')
            ->with(['opd', 'kecamatan', 'desa', 'jenisKelompokMasyarakat'])
            ->withCount('organisasiDetail');

        $user = Auth::user();

        if ($user->is_opd()) {
            $q->where('organisasi.opd_id', $user->opd_id);
        } else {
            $opdId = request('opd_id');
            if (is_string($opdId) && $opdId !== '' && Opd::query()->whereKey($opdId)->exists()) {
                $q->where('organisasi.opd_id', $opdId);
            }
        }

        $status = request('status');
        if ($status === 'aktif') {
            $q->where('organisasi.is_active', true);
        } elseif ($status === 'nonaktif') {
            $q->where('organisasi.is_active', false);
        }

        if (request('belum_verifikasi') === '1') {
            $q->whereHas('organisasiDetail', fn ($d) =>
                $d->whereHas('penduduk', fn ($p) => $p->where('is_valid', false))
            );
        }

        $tahun = request('tahun_anggaran');
        if (is_numeric($tahun)) {
            $q->where('organisasi.tahun_anggaran', (int) $tahun);
        }

        // Grup utama OPD; di dalamnya dipisah per tahun anggaran (terbaru dulu).
        $q->orderBy('opd.nama');
        if (! is_numeric($tahun)) {
            $q->orderByDesc('organisasi.tahun_anggaran');
        }

        return $q->orderBy('organisasi.nama');
    }

    private function data()
    {
        return DataTables::of($this->baseQuery())
            ->addIndexColumn()
            ->addColumn('id', fn ($row) => $row->id)
            ->addColumn('opd', fn ($row) => $row->opd?->nama ?? '-')
            ->addColumn('jenis_kelompok', fn ($row) => $row->jenisKelompokMasyarakat?->nama ?? '-')
            ->addColumn('kecamatan', fn ($row) => $row->kecamatan?->nama ?? '-')
            ->addColumn('desa', fn ($row) => $row->desa?->nama ?? '-')
            ->addColumn('tgl_pembentukan', fn ($row) => $row->tgl_pembentukan?->format('d-m-Y') ?? '-')
            ->addColumn('jumlah_anggota', fn ($row) => $row->organisasi_detail_count)
            ->addColumn('tahun_anggaran', fn ($row) => $row->tahun_anggaran ?? '-')
            ->addColumn('status', fn ($row) => $row->is_active
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-danger">Nonaktif</span>')
            ->rawColumns(['status'])
            ->toJson();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->data();
        }

        $opds = Opd::query()->orderBy('nama')->get(['id', 'nama']);
        $tahunList = TahunAnggaran::query()->orderByDesc('tahun')->get(['tahun', 'label']);

        return view('pages.laporan-kelompok.index', compact('opds', 'tahunList'));
    }

    public function preview()
    {
        $kelompoks = $this->baseQuery()
            ->with(['organisasiDetail.penduduk'])
            ->get();

        $selectedOpd = null;
        $opdId = request('opd_id');
        if ($opdId) {
            $selectedOpd = Opd::find($opdId);
        } elseif (Auth::user()->is_opd()) {
            $selectedOpd = Auth::user()->opd;
        }

        $statusLabel = match (request('status')) {
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
            default => 'Semua',
        };

        return view('pages.laporan-kelompok.preview', compact('kelompoks', 'selectedOpd', 'statusLabel'));
    }

    public function anggota(Organisasi $organisasi): JsonResponse
    {
        $anggota = $organisasi->organisasiDetail()
            ->with('penduduk')
            ->orderByRaw("FIELD(jabatan, 'Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Admin', 'Anggota')")
            ->get()
            ->map(fn ($d) => [
                'nik'               => $d->penduduk?->nik ?? '-',
                'nama'              => $d->penduduk?->nama ?? '-',
                'alamat'            => $d->penduduk?->alamat ?? '-',
                'jabatan'           => $d->jabatan?->value ?? '-',
                'status_verifikasi' => $d->penduduk?->labelStatusVerifikasi() ?? '-',
                'is_valid'          => $d->penduduk?->is_valid,
                'keterangan'        => $d->penduduk?->catatan_validasi ?? '-',
            ]);

        return response()->json($anggota);
    }

    public function export()
    {
        $kelompoks = $this->baseQuery()->get();
        $filename = 'laporan-kelompok-'.date('YmdHis').'.xlsx';

        return Excel::download(new LaporanKelompokExport($kelompoks), $filename);
    }
}
