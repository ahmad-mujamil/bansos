<?php

namespace App\Http\Controllers;

use App\Models\HistoryVerifikasiPenduduk;
use App\Models\Penduduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiPendudukController extends Controller
{
    private function data()
    {
        $statusRequest = request('status', 'all');

        $query = Penduduk::query()
            ->with(['kecamatan', 'desa', 'validatedBy'])
            ->withCount(['historyVerifikasi as perbaikan_count' => fn ($q) => $q->where('action', 'perbaikan')]);

        if (request('verifikator') === 'me') {
            $query->where('validated_by', auth()->id());
        }

        if ($statusRequest === '1') {
            $query->where('is_valid', true);
        } elseif ($statusRequest === '0') {
            $query->where('is_valid', false)
                ->whereNull('validated_at')
                ->whereDoesntHave('historyVerifikasi', fn ($q) => $q->where('action', 'perbaikan'));
        } elseif ($statusRequest === '2') {
            $query->where('is_valid', false);
            $query->whereNotNull('validated_at');
        } elseif ($statusRequest === '3') {
            $query->where('is_valid', false)
                ->whereNull('validated_at')
                ->whereHas('historyVerifikasi', fn ($q) => $q->where('action', 'perbaikan'));
        }

        return DataTables::of($query)
            ->addColumn('is_valid_badge', function ($row) {
                if ($row->is_valid) {
                    return '<span class="badge bg-success">Terverifikasi</span>';
                }
                if ($row->validated_at && !$row->is_valid) {
                    return '<span class="badge bg-danger">Ditolak</span>';
                }
                if (($row->perbaikan_count ?? 0) > 0) {
                    return '<span class="badge bg-info text-dark">Sudah Perbaikan</span>';
                }
                return '<span class="badge bg-warning text-dark">Belum Diverifikasi</span>';
            })
            ->addColumn('validated_at_fmt', fn ($row) => $row->validated_at
                ? \Carbon\Carbon::parse($row->validated_at)->translatedFormat('d M Y H:i')
                : '-')
            ->addColumn('created_at_fmt', fn ($row) => $row->created_at
                ? \Carbon\Carbon::parse($row->created_at)->translatedFormat('d M Y H:i')
                : '-')
            ->addColumn('action', fn ($row) =>
                "<a href='" . route('verifikasi-penduduk.show', $row->id) . "' class='btn btn-sm btn-outline-primary'>Lihat</a>"
            )
            ->orderColumn('created_at_fmt', 'created_at $1')
            ->orderColumn('validated_at_fmt', 'validated_at $1')
            ->rawColumns(['is_valid_badge', 'action'])
            ->toJson();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.verifikasi-penduduk.index');
    }

    public function show(Penduduk $penduduk)
    {
        $penduduk->load(['desa.kecamatan', 'validatedBy', 'historyVerifikasi.user']);

        return view('pages.verifikasi-penduduk.show', compact('penduduk'));
    }

    public function verifikasi(Request $request, Penduduk $penduduk): RedirectResponse
    {
        $validated = $request->validate([
            'is_valid'         => ['required', 'boolean'],
            'catatan_validasi' => ['nullable', 'string', 'max:500'],
        ]);

        DB::beginTransaction();

        try {
            $penduduk->update([
                'is_valid'         => $validated['is_valid'],
                'validated_at'     => now(),
                'validated_by'     => Auth::id(),
                'catatan_validasi' => $validated['catatan_validasi'] ?? null,
            ]);

            HistoryVerifikasiPenduduk::create([
                'penduduk_id' => $penduduk->id,
                'user_id'     => Auth::id(),
                'action'      => 'verifikasi',
                'status'      => $validated['is_valid'] ? 'diverifikasi' : 'ditolak',
                'catatan'     => $validated['catatan_validasi'] ?? null,
            ]);

            DB::commit();

            toast()->success('Berhasil', 'Data penduduk berhasil diverifikasi.');

            return redirect()->route('verifikasi-penduduk.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());

            return back()->withInput();
        }
    }
}
