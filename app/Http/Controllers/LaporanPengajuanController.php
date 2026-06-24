<?php

namespace App\Http\Controllers;

use App\Enums\JenisPengajuan;
use App\Enums\PengajuanStatus;
use App\Enums\RoleUser;
use App\Exports\LaporanPengajuanExport;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LaporanPengajuanController extends Controller
{
    private function baseQuery()
    {
        $query = Pengajuan::query()
            ->with([
                'organisasi.desa.kecamatan',
                'jenisBantuan',
                'user',
                'desa.kecamatan',
                'verifikasiPengajuan.user',
                'verifiedBy',
                'opd',
            ]);

        $user = Auth::user();
        if ($user->role === RoleUser::OPD) {
            $query->where('opd_id', $user->opd_id);
        }

        return $query->latest('pengajuan.created_at');
    }

    private function data()
    {
        $statusRequest = (string) request('status', 'all');
        $allowed = [
            PengajuanStatus::DRAFT->value,
            PengajuanStatus::DIAJUKAN->value,
            PengajuanStatus::DISETUJUI->value,
            PengajuanStatus::DITOLAK->value,
        ];

        $kategoriRequest = (string) request('kategori', JenisPengajuan::BANSOS->value);

        $query = $this->baseQuery();

        if ($kategoriRequest !== 'all' && JenisPengajuan::tryFrom($kategoriRequest) !== null) {
            $query->where(function ($q) use ($kategoriRequest) {
                $q->where('kategori_pengajuan', $kategoriRequest)
                    ->orWhere(function ($q2) use ($kategoriRequest) {
                        $q2->whereNull('kategori_pengajuan')
                            ->whereHas('jenisBantuan', fn ($jb) => $jb->where('kategori', $kategoriRequest));
                    });
            });
        }

        if ($statusRequest !== 'all' && in_array($statusRequest, $allowed, true)) {
            $query->where('status', $statusRequest);
        }

        $yesNo = fn (?bool $v) => $v
            ? '<span class="text-success">Ya</span>'
            : '<span class="text-danger">Tidak</span>';

        return DataTables::of($query)
            ->addColumn('kelompok', function (Pengajuan $row) {
                $org = $row->organisasi;
                if (! $org) {
                    return '-';
                }
                $parts = [];
                if ($org->desa?->nama) {
                    $parts[] = $org->desa->nama;
                }
                if ($org->desa?->kecamatan?->nama) {
                    $parts[] = $org->desa->kecamatan->nama;
                }
                $wilayah = $parts !== [] ? ' <span class="text-muted">('.e(implode(', ', $parts)).')</span>' : '';

                return '<span class="fw-semibold">'.e($org->nama).'</span>'.$wilayah;
            })
            ->addColumn('kode_pengajuan', fn (Pengajuan $row) => e($row->kode_pengajuan))
            ->addColumn('judul', fn (Pengajuan $row) => e($row->judul ?? '-'))
            ->addColumn('kode_tanggal', function (Pengajuan $row) {
                $kode = '<span class="fw-semibold">'.e($row->kode_pengajuan).'</span>';
                $judul = $row->judul
                    ? '<div class="text-muted text-small">'.$row->created_at?->translatedFormat('d M Y').'</div>'
                    : '';

                return $kode.$judul;
            })
            ->addColumn('jenis_bantuan', fn (Pengajuan $row) => e($row->jenisBantuan?->nama ?? '-'))
            ->addColumn('nilai_usulan', fn (Pengajuan $row) => 'Rp '.number_format((float) $row->nilai, 0, ',', '.'))
            ->addColumn('opd', fn (Pengajuan $row) => e($row->opd?->nama ?? '-'))
            ->addColumn('status', function (Pengajuan $row) {
                $status = $row->status;
                $badge = $status?->badgeColor() ?? 'secondary';

                return '<span class="badge bg-'.$badge.'">'.e($status?->getDescription() ?? '-').'</span>';
            })
            ->addColumn('tanggal_pengajuan', fn (Pengajuan $row) => $row->created_at?->translatedFormat('d M Y') ?? '-')
            ->addColumn('keputusan', function (Pengajuan $row) {
                if (! in_array($row->status, [PengajuanStatus::DISETUJUI, PengajuanStatus::DITOLAK], true)) {
                    return '<span class="text-muted">—</span>';
                }

                return $row->status === PengajuanStatus::DISETUJUI
                    ? '<span class="badge bg-success">Disetujui</span>'
                    : '<span class="badge bg-danger">Ditolak</span>';
            })
            ->addColumn('nilai_rekomendasi', function (Pengajuan $row) {
                $v = $row->verifikasiPengajuan?->nilai_rekomendasi;
                if ($v === null) {
                    return '<span class="text-muted">—</span>';
                }

                return 'Rp '.number_format((float) $v, 0, ',', '.');
            })
            ->addColumn('vk', fn (Pengajuan $row) => $yesNo($row->verifikasiPengajuan?->lulus_kriteria))
            ->addColumn('va', fn (Pengajuan $row) => $yesNo($row->verifikasiPengajuan?->lulus_administrasi))
            ->addColumn('vks', fn (Pengajuan $row) => $yesNo($row->verifikasiPengajuan?->lulus_kesesuaian))
            ->addColumn('vpp', fn (Pengajuan $row) => $yesNo($row->verifikasiPengajuan?->sesuai_program_pemda))
            ->addColumn('catatan_verifikasi', function (Pengajuan $row) {
                $c = $row->verifikasiPengajuan?->catatan;

                return $c ? e(Str::limit($c, 80)) : '<span class="text-muted">—</span>';
            })
            ->addColumn('verifikator', fn (Pengajuan $row) => e($row->verifikasiPengajuan?->user?->nama
                ?? $row->verifiedBy?->nama
                ?? '-'))
            ->addColumn('tanggal_verifikasi', function (Pengajuan $row) {
                $at = $row->verified_at ?? $row->verifikasiPengajuan?->created_at;

                return $at ? $at->translatedFormat('d M Y H:i') : '<span class="text-muted">—</span>';
            })
            ->addColumn('action', function (Pengajuan $row) {
                $url = route('laporan-pengajuan.show', $row);

                return "<a href='{$url}' class='btn btn-sm btn-outline-primary'>Lihat</a>";
            })
            ->rawColumns([
                'kelompok',
                'kode_tanggal',
                'status',
                'keputusan',
                'nilai_rekomendasi',
                'vk',
                'va',
                'vks',
                'vpp',
                'catatan_verifikasi',
                'tanggal_verifikasi',
                'action',
            ])
            ->toJson();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.laporan-pengajuan.index');
    }

    public function exportExcel()
    {
        $kategori = (string) request('kategori', 'all');
        $status = (string) request('status', 'all');
        $filename = 'laporan-pengajuan-'.date('YmdHis').'.xlsx';

        return Excel::download(new LaporanPengajuanExport($kategori, $status), $filename);
    }

    public function show(Pengajuan $pengajuan)
    {
        $this->authorizeLaporan($pengajuan);

        $pengajuan->load([
            'organisasi.desa.kecamatan',
            'organisasi.organisasiDetail.penduduk',
            'jenisBantuan',
            'opd',
            'verifikasiPengajuan.user',
            'verifiedBy',
        ]);

        return view('pages.laporan-pengajuan.show', [
            'pengajuan' => $pengajuan,
        ]);
    }

    private function authorizeLaporan(Pengajuan $pengajuan): void
    {
        $user = Auth::user();
        if ($user->role === RoleUser::SUPER || $user->role === RoleUser::ADMIN) {
            return;
        }
        if ($user->role === RoleUser::OPD && $user->opd_id === $pengajuan->opd_id) {
            return;
        }

        abort(403);
    }
}
