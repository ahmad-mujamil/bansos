<?php

namespace App\Http\Controllers;

use App\Http\Requests\PendudukRequest;
use App\Models\HistoryVerifikasiPenduduk;
use App\Models\Penduduk;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Enums\JenisKelamin;
use App\Enums\StatusPerkawinan;
use App\Enums\LevelDesil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PendudukController extends Controller
{
    private function data(): \Illuminate\Http\JsonResponse
    {
        $statusRequest = request('status', 'all');

        $data = Penduduk::query()
            ->with([
                'kecamatan',
                'desa',
                'organisasiDetails.organisasi.opd',
            ])
            ->latest();

        if ($statusRequest === '1') {
            $data->where('is_valid', true);
        } elseif ($statusRequest === '0') {
            $data->where('is_valid', false)->whereNull('validated_at');
        } elseif ($statusRequest === '2') {
            $data->where('is_valid', false)->whereNotNull('validated_at');
        }

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = "</ul></nav>";

                $delete = "<li class='breadcrumb-item'><a href='" . route('penduduk.destroy', $data->id) . "' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='" . route('penduduk.edit', $data->id) . "'  title='Edit Data'
                        class='fw-bold text-success' >Edit</a></li>";

                return $navActionStart . $edit . $delete . $navActionEnd;
            })
            ->editColumn('level_desil', function ($data) {
                return $data->level_desil?->getDescription();
            })
            ->addColumn('nik_nama', function ($data) {
                return '<div class="fw-bold">' . e($data->nama) . '</div>'
                    . '<small class="text-muted">' . e($data->nik) . '</small>';
            })
            ->filterColumn('nik_nama', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('nik', 'like', "%{$keyword}%");
                });
            })
            ->orderColumn('nik_nama', 'nama $1')
            ->addColumn('desa_kecamatan', function ($data) {
                $desa = $data->desa?->nama;
                $kecamatan = $data->kecamatan?->nama;

                if (! $desa && ! $kecamatan) {
                    return '-';
                }

                return '<div class="fw-bold">' . e($desa ?? '-') . '</div>'
                    . '<small class="text-muted">' . e($kecamatan ?? '-') . '</small>';
            })
            ->addColumn('status_verifikasi', function ($data) {
                if ($data->is_valid) {
                    return '<span class="badge bg-success">Terverifikasi</span>';
                }
                if ($data->validated_at && !$data->is_valid) {
                    $catatan = trim((string) $data->catatan_validasi) !== ''
                        ? $data->catatan_validasi
                        : 'Tidak ada catatan';

                    return '<span class="badge bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" style="cursor: help;" title="' . e($catatan) . '">Tidak Valid</span>';
                }
                return '<span class="badge bg-warning text-dark">Belum Diverifikasi</span>';
            })
            ->addColumn('kelompok_opd', function ($data) {
                $items = $data->organisasiDetails
                    ->filter(fn ($d) => $d->organisasi !== null)
                    ->map(fn ($d) => [
                        'kelompok' => $d->organisasi->nama,
                        'opd' => $d->organisasi->opd?->nama,
                    ])
                    ->unique('kelompok')
                    ->values();

                if ($items->isEmpty()) {
                    return '<span class="text-muted">-</span>';
                }

                return $items->map(function ($item) {
                    $opd = $item['opd']
                        ? '<small class="text-muted d-block">' . e($item['opd']) . '</small>'
                        : '';
                    return '<div class="mb-1">'
                        . '<span class="badge bg-outline-primary">' . e($item['kelompok']) . '</span>'
                        . $opd
                        . '</div>';
                })->implode('');
            })
            ->rawColumns(['action', 'status_verifikasi', 'kelompok_opd', 'desa_kecamatan', 'nik_nama'])
            ->toJson();
    }

    public function index()
    {
        confirmDelete("Delete Data", "Are you sure you want to delete?");
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.penduduk.index');
    }

    public function create()
    {
        $jenis_kelamin = JenisKelamin::cases();
        $status_perkawinan = StatusPerkawinan::cases();
        $level_desil = LevelDesil::cases();

        return view('pages.penduduk.create', compact( 'jenis_kelamin', 'status_perkawinan', 'level_desil'));
    }

    public function store(PendudukRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $penduduk = Penduduk::query()->create($request->validated());

            HistoryVerifikasiPenduduk::create([
                'penduduk_id' => $penduduk->id,
                'user_id'     => Auth::id(),
                'action'      => 'input',
                'catatan'     => 'Data penduduk diinput',
            ]);

            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('penduduk.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function edit(Penduduk $penduduk)
    {
        $jenis_kelamin = JenisKelamin::cases();
        $status_perkawinan = StatusPerkawinan::cases();
        $level_desil = LevelDesil::cases();

        return view('pages.penduduk.create', compact( 'jenis_kelamin', 'status_perkawinan', 'level_desil','penduduk'));
    }

     public function show(Penduduk $penduduk)
    {
        $jenis_kelamin = JenisKelamin::cases();
        $status_perkawinan = StatusPerkawinan::cases();
        $level_desil = LevelDesil::cases();

        return view('pages.penduduk.create', compact( 'jenis_kelamin', 'status_perkawinan', 'level_desil','penduduk'));
    }

    public function update(PendudukRequest $request, Penduduk $penduduk): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $wasRejected = $penduduk->validated_at && ! $penduduk->is_valid;

            $data = $request->validated();
            if ($wasRejected) {
                $data['is_valid']         = false;
                $data['validated_at']     = null;
                $data['validated_by']     = null;
                $data['catatan_validasi'] = null;
            }

            $penduduk->update($data);

            HistoryVerifikasiPenduduk::create([
                'penduduk_id' => $penduduk->id,
                'user_id'     => Auth::id(),
                'action'      => 'perbaikan',
                'catatan'     => $wasRejected
                    ? 'Data diperbaiki setelah ditolak, menunggu verifikasi ulang'
                    : 'Data penduduk diperbarui',
            ]);

            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('penduduk.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(Penduduk $penduduk): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $penduduk->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil dihapus');
            return redirect()->route('penduduk.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return redirect()->route('penduduk.index');
        }
    }
}
