<?php

namespace App\Http\Controllers;

use App\Models\LogBlacklistOrganisasi;
use App\Models\Organisasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BlacklistOrganisasiOpdController extends Controller
{
    private function data()
    {
        $opdId = Auth::user()->opd_id;

        $query = Organisasi::query()
            ->where('opd_id', $opdId)
            ->latest();

        return DataTables::of($query)
            ->addColumn('nama', fn (Organisasi $row) => e($row->nama ?? '-'))
            ->addColumn('jenis', fn (Organisasi $row) => e($row->jenis ?? '-'))
            ->addColumn('status_blacklist', function (Organisasi $row) {
                return $row->is_blacklist
                    ? '<span class="badge bg-danger">Blacklist</span>'
                    : '<span class="badge bg-success">Normal</span>';
            })
            ->addColumn('action', function (Organisasi $row) {
                $target = $row->is_blacklist ? 0 : 1;
                $label = $row->is_blacklist ? 'Unblacklist' : 'Blacklist';
                $btnClass = $row->is_blacklist ? 'btn-outline-success' : 'btn-outline-danger';

                return "<button type='button'
                    class='btn btn-sm {$btnClass} btn-toggle-blacklist'
                    data-id='{$row->id}'
                    data-target='{$target}'
                    data-nama='".e($row->nama ?? '-')."'
                >{$label}</button>";
            })
            ->rawColumns(['status_blacklist', 'action'])
            ->toJson();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.opd.blacklist-organisasi.index');
    }

    public function toggle(Request $request, Organisasi $organisasi): RedirectResponse
    {
        $user = Auth::user();

        if ($user->opd_id === null || $organisasi->opd_id !== $user->opd_id) {
            abort(403);
        }

        $validated = $request->validate([
            'jadi_blacklist' => ['required', 'boolean'],
            'alasan' => ['nullable', 'string', 'max:255'],
        ]);

        $jadiBlacklist = (bool) $validated['jadi_blacklist'];

        DB::beginTransaction();

        try {
            $organisasi->update([
                'is_blacklist' => $jadiBlacklist,
            ]);

            LogBlacklistOrganisasi::create([
                'organisasi_id' => $organisasi->id,
                'user_id' => $user->id,
                'jadi_blacklist' => $jadiBlacklist,
                'alasan' => $validated['alasan'] ?? null,
                'meta' => [
                    'ip' => $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                ],
            ]);

            DB::commit();

            toast()->success('Berhasil', $jadiBlacklist ? 'Kelompok/Organisasi berhasil diblacklist.' : 'Blacklist berhasil dibatalkan.');
            return back();
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
            return back()->withInput();
        }
    }
}

