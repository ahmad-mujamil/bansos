<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKelompokExport;
use App\Models\Opd;
use App\Models\Organisasi;
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

        return $q->orderBy('opd.nama')->orderBy('organisasi.nama');
    }

    private function data()
    {
        return DataTables::of($this->baseQuery())
            ->addIndexColumn()
            ->addColumn('opd', fn ($row) => $row->opd?->nama ?? '-')
            ->addColumn('jenis_kelompok', fn ($row) => $row->jenisKelompokMasyarakat?->nama ?? '-')
            ->addColumn('kecamatan', fn ($row) => $row->kecamatan?->nama ?? '-')
            ->addColumn('desa', fn ($row) => $row->desa?->nama ?? '-')
            ->addColumn('tgl_pembentukan', fn ($row) => $row->tgl_pembentukan?->format('d-m-Y') ?? '-')
            ->addColumn('jumlah_anggota', fn ($row) => $row->organisasi_detail_count)
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

        return view('pages.laporan-kelompok.index', compact('opds'));
    }

    public function preview()
    {
        $kelompoks = $this->baseQuery()->get();

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

    public function export()
    {
        $kelompoks = $this->baseQuery()->get();
        $filename  = 'laporan-kelompok-' . date('YmdHis') . '.xlsx';

        return Excel::download(new LaporanKelompokExport($kelompoks), $filename);
    }
}