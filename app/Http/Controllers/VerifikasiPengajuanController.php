<?php

namespace App\Http\Controllers;

use App\Enums\PengajuanStatus;
use App\Http\Requests\VerifikasiPengajuanRequest;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiPengajuanController extends Controller
{
    private function badgeColor(?PengajuanStatus $status): string
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
            PengajuanStatus::DRAFT->value,
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
        $pengajuan->load(['user', 'verifiedBy', 'logs.user']);

        return view('pages.verifikasi-pengajuan.show', compact('pengajuan'));
    }

    public function verifikasi(VerifikasiPengajuanRequest $request, Pengajuan $pengajuan): RedirectResponse
    {
        $decision = $request->validated('keputusan');
        $catatan = $request->validated('catatan');

        $oldStatus = $pengajuan->status;

        $newStatus = match ($decision) {
            PengajuanStatus::DISETUJUI->value => PengajuanStatus::DISETUJUI,
            PengajuanStatus::DITOLAK->value => PengajuanStatus::DITOLAK,
            default => null,
        };

        if (! $newStatus) {
            abort(422);
        }

        DB::beginTransaction();

        try {
            $pengajuan->{$this->getCatatanAttribute()} = $catatan;
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
                'metadata' => null,
            ]);

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
