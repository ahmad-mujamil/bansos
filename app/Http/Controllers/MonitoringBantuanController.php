<?php

namespace App\Http\Controllers;

use App\Enums\PengajuanStatus;
use App\Models\Pengajuan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MonitoringBantuanController extends Controller
{
    private function tahapRequest(): string
    {
        $tahap = (string) request('tahap', 'semua');

        return in_array($tahap, ['semua', 'belum_bast', 'sudah_bast'], true) ? $tahap : 'semua';
    }

    private function baseQuery()
    {
        $query = Pengajuan::query()
            ->with(['user', 'jenisBantuan', 'verifikasiPengajuan.media', 'bast'])
            ->select('pengajuan.*');

        $opdId = Auth::user()?->opd_id;
        if ($opdId !== null) {
            $query->where('pengajuan.opd_id', $opdId);
        }

        return $query->latest('pengajuan.created_at');
    }

    private function applyBelumBastSiapInputCriteria(Builder $query): void
    {
        $query
            ->where('pengajuan.status', PengajuanStatus::DISETUJUI)
            ->whereDoesntHave('bast')
            ->whereHas('verifikasiPengajuan.media', function ($q): void {
                $q->where('collection_name', 'ba-verifikasi');
            });
    }

    private function applyTahapFilter(Builder $query, string $tahap): void
    {
        if ($tahap === 'sudah_bast') {
            $query->whereHas('bast');

            return;
        }

        if ($tahap === 'semua') {
            $query->where(function (Builder $q): void {
                $q->where(function (Builder $sub): void {
                    $this->applyBelumBastSiapInputCriteria($sub);
                })->orWhereHas('bast');
            });

            return;
        }

        $this->applyBelumBastSiapInputCriteria($query);
    }

    private function data(): JsonResponse
    {
        $tahap = $this->tahapRequest();
        $query = $this->baseQuery();
        $this->applyTahapFilter($query, $tahap);

        return DataTables::of($query)
            ->addColumn('kode_pengajuan', fn ($row) => e($row->kode_pengajuan))
            ->addColumn('jenis', fn ($row) => e($row->jenisBantuan?->nama ?? '-'))
            ->addColumn('judul', fn ($row) => e($row->judul ?? '-'))
            ->addColumn('status', function ($row) {
                $status = $row->status;
                $badge = $status?->badgeColor() ?? 'secondary';

                return '<span class="badge bg-'.$badge.'">'.e($status?->getDescription() ?? '-').'</span>';
            })
            ->addColumn('tanggal', fn ($row) => $row->created_at?->translatedFormat('d M Y') ?? '-')
            ->addColumn('user', fn ($row) => e($row->user?->nama ?? $row->user?->email ?? '-'))
            ->addColumn('bast_info', function ($row) use ($tahap) {
                if ($row->bast) {
                    $tgl = $row->bast->tanggal?->translatedFormat('d M Y') ?? '-';

                    return '<div class="text-small"><strong>'.e($row->bast->nomor).'</strong><br><span class="text-muted">'.$tgl.'</span></div>';
                }

                if ($tahap === 'belum_bast' || $tahap === 'semua') {
                    return '<span class="badge bg-info text-white">Siap BAST</span>';
                }

                return '-';
            })
            // ->addColumn('action', function ($row) use ($tahap) {
            //     $ver = route('verifikasi-pengajuan.show', $row->id);
            //     $html = "<a href='{$ver}' class='btn btn-sm btn-outline-primary'>Detail verifikasi</a>";

            //     if ($row->bast) {
            //         $bastUrl = route('bast.show', $row->bast->id);
            //         $html .= " <a href='{$bastUrl}' class='btn btn-sm btn-outline-success ms-1'>Lihat BAST</a>";
            //     } elseif ($tahap === 'belum_bast' || $tahap === 'semua') {
            //         $createUrl = route('bast.create');
            //         $html .= " <a href='{$createUrl}' class='btn btn-sm btn-outline-secondary ms-1'>Input BAST</a>";
            //     }

            //     return $html;
            // })
            ->rawColumns(['status', 'bast_info'])
            ->toJson();
    }

    public function index(): JsonResponse|View
    {
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.monitoring-bantuan.index');
    }
}
