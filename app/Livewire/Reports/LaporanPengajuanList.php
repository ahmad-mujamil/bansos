<?php

namespace App\Livewire\Reports;

use App\Enums\JenisPengajuan;
use App\Enums\PengajuanStatus;
use App\Enums\RoleUser;
use App\Livewire\DataList;
use App\Models\Pengajuan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LaporanPengajuanList extends DataList
{
    public string $kategori = '';
    public string $status = 'all';

    public function mount(): void
    {
        $this->configure([
            'model' => Pengajuan::class,
            'columns' => $this->defaultColumns(),
            'detailFields' => $this->defaultDetailFields(),
            'with' => [
                'organisasi.desa.kecamatan',
                'jenisBantuan',
                'opd',
                'verifikasiPengajuan.user',
                'verifiedBy',
            ],
            'defaultSortColumn' => 'created_at',
            'defaultSortDirection' => 'desc',
            'perPage' => 10,
            'title' => 'Detail Pengajuan',
            'searchPlaceholder' => 'Cari kode, judul, pemohon…',
            'enableDetail' => true,
            'showSearch' => true,
            'detailRoute' => 'laporan-pengajuan.show',
        ]);

        $this->kategori = JenisPengajuan::BANSOS->value;
    }

    public function updatedKategori(): void
    {
        if (JenisPengajuan::tryFrom($this->kategori) === null && $this->kategori !== 'all') {
            $this->kategori = JenisPengajuan::BANSOS->value;
        }
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $allowed = array_map(fn ($c) => $c->value, PengajuanStatus::cases());
        if ($this->status !== 'all' && ! in_array($this->status, $allowed, true)) {
            $this->status = 'all';
        }
        $this->resetPage();
    }

    public function setKategori(string $kategori): void
    {
        $this->kategori = $kategori;
        $this->updatedKategori();
    }

    protected function applyExtraQuery(Builder $query): void
    {
        $user = Auth::user();
        if ($user?->role === RoleUser::OPD) {
            $query->where('pengajuan.opd_id', $user->opd_id);
        }

        if ($this->kategori !== 'all' && JenisPengajuan::tryFrom($this->kategori) !== null) {
            $kategori = $this->kategori;
            $query->where(function (Builder $q) use ($kategori) {
                $q->where('kategori_pengajuan', $kategori)
                    ->orWhere(function (Builder $q2) use ($kategori) {
                        $q2->whereNull('kategori_pengajuan')
                            ->whereHas('jenisBantuan', fn (Builder $jb) => $jb->where('kategori', $kategori));
                    });
            });
        }

        if ($this->status !== 'all' && PengajuanStatus::tryFrom($this->status) !== null) {
            $query->where('status', $this->status);
        }
    }

    private function defaultColumns(): array
    {
        return [
            [
                'key' => 'organisasi.nama',
                'label' => 'Pemohon',
                'searchable' => true,
            ],
            [
                'key' => 'kode_pengajuan',
                'label' => 'Kode / Tanggal',
                'sortable' => true,
                'searchable' => true,
                'sub_key' => 'created_at',
                'sub_format' => 'date',
            ],
            [
                'key' => 'jenisBantuan.nama',
                'label' => 'Jenis Bantuan',
                'searchable' => true,
            ],
            [
                'key' => 'nilai',
                'label' => 'Nilai Usulan',
                'format' => 'currency',
                'sortable' => true,
            ],
            [
                'key' => 'opd.nama',
                'label' => 'OPD',
            ],
            [
                'key' => 'status',
                'label' => 'Status',
                'format' => 'badge_enum',
            ],
        ];
    }

    private function defaultDetailFields(): array
    {
        return [
            ['section' => 'Identitas Pengajuan', 'key' => 'kode_pengajuan',     'label' => 'Kode Pengajuan'],
            ['section' => 'Identitas Pengajuan', 'key' => 'judul',              'label' => 'Judul',              'width' => 'full'],
            ['section' => 'Identitas Pengajuan', 'key' => 'kategori_pengajuan', 'label' => 'Kategori Pengajuan', 'format' => 'enum'],
            ['section' => 'Identitas Pengajuan', 'key' => 'created_at',         'label' => 'Tgl Pengajuan',      'format' => 'date'],

            ['section' => 'Pemohon', 'key' => 'organisasi.nama',                'label' => 'Nama Pemohon',       'width' => 'full'],
            ['section' => 'Pemohon', 'key' => 'organisasi.desa.nama',           'label' => 'Desa'],
            ['section' => 'Pemohon', 'key' => 'organisasi.desa.kecamatan.nama', 'label' => 'Kecamatan'],

            ['section' => 'Bantuan',  'key' => 'jenisBantuan.nama', 'label' => 'Jenis Bantuan'],
            ['section' => 'Bantuan',  'key' => 'opd.nama',          'label' => 'OPD'],
            ['section' => 'Bantuan',  'key' => 'nilai',             'label' => 'Nilai Usulan', 'format' => 'currency'],
            ['section' => 'Bantuan',  'key' => 'status',            'label' => 'Status',       'format' => 'badge_enum'],

            ['section' => 'Verifikasi', 'key' => 'verifikasiPengajuan.nilai_rekomendasi',    'label' => 'Nilai Rekomendasi',   'format' => 'currency'],
            ['section' => 'Verifikasi', 'key' => 'verified_at',                              'label' => 'Tgl Verifikasi',      'format' => 'datetime'],
            ['section' => 'Verifikasi', 'key' => 'verifikasiPengajuan.user.nama',            'label' => 'Verifikator',         'width' => 'full'],
            ['section' => 'Verifikasi', 'key' => 'verifikasiPengajuan.lulus_kriteria',       'label' => 'Lulus Kriteria',      'format' => 'yesno'],
            ['section' => 'Verifikasi', 'key' => 'verifikasiPengajuan.lulus_administrasi',   'label' => 'Lulus Administrasi',  'format' => 'yesno'],
            ['section' => 'Verifikasi', 'key' => 'verifikasiPengajuan.lulus_kesesuaian',     'label' => 'Lulus Kesesuaian',    'format' => 'yesno'],
            ['section' => 'Verifikasi', 'key' => 'verifikasiPengajuan.sesuai_program_pemda', 'label' => 'Sesuai Program Pemda','format' => 'yesno'],
            ['section' => 'Verifikasi', 'key' => 'verifikasiPengajuan.catatan',              'label' => 'Catatan Verifikasi',  'width' => 'full'],
        ];
    }

    public function render(): View
    {
        $items = $this->buildQuery()->paginate($this->perPage);

        return view('livewire.reports.laporan-pengajuan-list', [
            'items' => $items,
            'kategoriOptions' => [
                JenisPengajuan::BANSOS,
                JenisPengajuan::HIBAH,
                JenisPengajuan::BANTUAN_KELOMPOK,
            ],
            'statusOptions' => PengajuanStatus::cases(),
        ]);
    }
}
