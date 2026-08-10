<?php

namespace App\Livewire;

use App\Enums\PengajuanStatus;
use App\Models\Opd;
use App\Models\Pengajuan;
use App\Models\Sp2d;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MonitoringBantuanList extends Component
{
    use WithFileUploads, WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $tahap = 'semua';

    public string $search = '';

    public string $opdId = '';

    public string $tahun = '';

    // Form input SP2D (khusus bendahara) untuk pengajuan yang sudah BAST.
    public ?string $sp2dPengajuanId = null;

    public string $sp2dNomor = '';

    public string $sp2dTanggal = '';

    public ?string $sp2dNilai = null;

    public ?string $sp2dKeterangan = null;

    public $sp2dDokumen = null;

    public bool $showSp2dModal = false;

    // Modal detail SP2D (read-only).
    public bool $showSp2dDetail = false;

    /** @var array<string, mixed> */
    public array $sp2dDetail = [];

    public function mount(): void
    {
        $this->tahap = $this->sanitizeTahap((string) request('tahap', 'semua'));
        $this->tahun = $this->sanitizeTahun((string) request('tahun', ''));
    }

    public function updatedTahap(): void
    {
        $this->tahap = $this->sanitizeTahap($this->tahap);
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedOpdId(): void
    {
        $this->opdId = $this->sanitizeOpdId($this->opdId);
        $this->resetPage();
    }

    public function updatedTahun(): void
    {
        $this->tahun = $this->sanitizeTahun($this->tahun);
        $this->resetPage();
    }

    public function setSemuaTahun(): void
    {
        $this->tahun = '';
        $this->resetPage();
    }

    private function sanitizeTahap(string $tahap): string
    {
        return in_array($tahap, ['semua', 'belum_bast', 'sudah_bast'], true) ? $tahap : 'semua';
    }

    private function sanitizeOpdId(string $opdId): string
    {
        if (! $this->shouldShowOpdFilter() || $opdId === '') {
            return '';
        }

        return Opd::query()->whereKey($opdId)->exists() ? $opdId : '';
    }

    private function sanitizeTahun(string $tahun): string
    {
        $yearDigitsOnly = preg_replace('/\D/', '', $tahun) ?? '';

        return substr($yearDigitsOnly, 0, 4);
    }

    private function selectedYear(): ?int
    {
        if (preg_match('/^\d{4}$/', $this->tahun) !== 1) {
            return null;
        }

        return (int) $this->tahun;
    }

    private function shouldShowOpdFilter(): bool
    {
        $user = Auth::user();

        return ($user?->is_admin() ?? false) || ($user?->is_super() ?? false);
    }

    private function opdOptions(): Collection
    {
        if (! $this->shouldShowOpdFilter()) {
            return collect();
        }

        return Opd::query()
            ->select(['id', 'nama'])
            ->orderBy('nama')
            ->get();
    }

    private function scopedQuery(): Builder
    {
        $query = Pengajuan::query();

        $user = Auth::user();

        if ($user?->is_opd()) {
            if ($user->opd_id === null) {
                return $query->whereRaw('1 = 0');
            }

            $query->where('pengajuan.opd_id', $user->opd_id);
        }

        if ($this->shouldShowOpdFilter() && $this->opdId !== '') {
            $query->where('pengajuan.opd_id', $this->opdId);
        }

        $selectedYear = $this->selectedYear();
        if ($selectedYear !== null) {
            $query->whereYear('pengajuan.created_at', $selectedYear);
        }

        return $query;
    }

    private function baseQuery(): Builder
    {
        return $this->scopedQuery()
            ->with([
                'user', 'opd', 'jenisBantuan', 'media', 'verifikasiPengajuan.media', 'bast.media',
                // Info penerima: pakai snapshot beku bila ada, fallback ke data live.
                'organisasi', 'details.penduduk', 'kelompokSnapshots', 'penerimaSnapshots',
                // SP2D (tanda diperiksa bendahara).
                'sp2d.user', 'sp2d.media',
            ])
            ->select('pengajuan.*')
            ->latest('pengajuan.created_at');
    }

    private function applyBelumBastSiapInputCriteria(Builder $query): void
    {
        $query
            ->where('pengajuan.status', PengajuanStatus::DISETUJUI)
            ->whereDoesntHave('bast')
            ->whereHas('verifikasiPengajuan.media', function (Builder $builder): void {
                $builder->where('collection_name', 'ba-verifikasi');
            });
    }

    private function applyTahapFilter(Builder $query): void
    {
        if ($this->tahap === 'sudah_bast') {
            $query->whereHas('bast');

            return;
        }

        if ($this->tahap === 'semua') {
            if (! (Auth::user()?->is_super() ?? false)) {
                $query->where(function (Builder $builder): void {
                    $builder->where(function (Builder $subQuery): void {
                        $this->applyBelumBastSiapInputCriteria($subQuery);
                    })->orWhereHas('bast');
                });
            }

            return;
        }

        $this->applyBelumBastSiapInputCriteria($query);
    }

    private function applySearchFilter(Builder $query): void
    {
        $keyword = trim($this->search);
        if ($keyword === '') {
            return;
        }

        $query->where(function (Builder $builder) use ($keyword): void {
            $builder
                ->where('pengajuan.kode_pengajuan', 'like', "%{$keyword}%")
                ->orWhere('pengajuan.judul', 'like', "%{$keyword}%")
                ->orWhereHas('user', function (Builder $userQuery) use ($keyword): void {
                    $userQuery
                        ->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                })
                ->orWhereHas('jenisBantuan', function (Builder $jenisBantuanQuery) use ($keyword): void {
                    $jenisBantuanQuery->where('nama', 'like', "%{$keyword}%");
                });
        });
    }

    private function stats(): array
    {
        $belumBast = $this->scopedQuery();
        $this->applyBelumBastSiapInputCriteria($belumBast);

        return [
            'belum_bast' => $belumBast->count(),
            'sudah_bast' => $this->scopedQuery()->whereHas('bast')->count(),
        ];
    }

    private function chartSummary(array $stats): array
    {
        return [
            'labels' => ['Belum BAST', 'Sudah BAST'],
            'values' => [
                (int) $stats['belum_bast'],
                (int) $stats['sudah_bast'],
            ],
        ];
    }

    private function chartTrend(): array
    {
        $selectedYear = $this->selectedYear();
        $startMonth = $selectedYear !== null
            ? now()->setYear($selectedYear)->startOfYear()
            : now()->startOfMonth()->subMonths(5);
        $monthsToShow = $selectedYear !== null ? 12 : 6;

        $rangeStart = (clone $startMonth)->startOfMonth();
        $rangeEnd = (clone $startMonth)->addMonths($monthsToShow - 1)->endOfMonth();

        $labels = [];
        $belumBastSeries = [];
        $sudahBastSeries = [];

        for ($index = 0; $index < $monthsToShow; $index++) {
            $monthStart = (clone $startMonth)->addMonths($index)->startOfMonth();
            $key = $monthStart->format('Y-m');

            $labels[] = $selectedYear !== null
                ? $monthStart->translatedFormat('M')
                : $monthStart->translatedFormat('M Y');
            $belumBastSeries[$key] = 0;
            $sudahBastSeries[$key] = 0;
        }

        // Dua query agregat saja, lalu di-bucket per bulan di PHP (portabel SQLite/MySQL).
        $belumBastQuery = $this->scopedQuery()
            ->whereBetween('pengajuan.created_at', [$rangeStart, $rangeEnd]);
        $this->applyBelumBastSiapInputCriteria($belumBastQuery);
        foreach ($belumBastQuery->get(['pengajuan.id', 'pengajuan.created_at']) as $row) {
            $key = $row->created_at->format('Y-m');
            if (array_key_exists($key, $belumBastSeries)) {
                $belumBastSeries[$key]++;
            }
        }

        $sudahBastQuery = $this->scopedQuery()
            ->whereBetween('pengajuan.created_at', [$rangeStart, $rangeEnd])
            ->whereHas('bast');
        foreach ($sudahBastQuery->get(['pengajuan.id', 'pengajuan.created_at']) as $row) {
            $key = $row->created_at->format('Y-m');
            if (array_key_exists($key, $sudahBastSeries)) {
                $sudahBastSeries[$key]++;
            }
        }

        return [
            'labels' => $labels,
            'series' => [
                'belum_bast' => array_values($belumBastSeries),
                'sudah_bast' => array_values($sudahBastSeries),
            ],
        ];
    }

    public function isBendahara(): bool
    {
        return (bool) (Auth::user()?->is_bendahara() || Auth::user()?->is_super());
    }

    public function openSp2d(string $pengajuanId): void
    {
        $this->resetSp2dForm();
        $this->sp2dPengajuanId = $pengajuanId;

        $pengajuan = Pengajuan::query()->with('verifikasiPengajuan')->find($pengajuanId);
        $rek = $pengajuan?->verifikasiPengajuan?->nilai_rekomendasi;
        if ($rek !== null) {
            $this->sp2dNilai = (string) (int) round((float) $rek);
        }

        $this->showSp2dModal = true;
    }

    public function closeSp2d(): void
    {
        $this->resetSp2dForm();
        $this->showSp2dModal = false;
    }

    public function viewSp2d(string $pengajuanId): void
    {
        $pengajuan = Pengajuan::query()->with(['sp2d.user', 'sp2d.media'])->find($pengajuanId);
        $sp2d = $pengajuan?->sp2d;

        if (! $sp2d) {
            return;
        }

        $this->sp2dDetail = [
            'kode_pengajuan' => $pengajuan->kode_pengajuan,
            'nomor' => $sp2d->nomor,
            'tanggal' => $sp2d->tanggal?->translatedFormat('d F Y') ?? '-',
            'nilai' => $sp2d->nilai !== null ? 'Rp '.number_format((float) $sp2d->nilai, 0, ',', '.') : '-',
            'keterangan' => $sp2d->keterangan ?: '-',
            'oleh' => $sp2d->user?->nama ?? 'bendahara',
            'dibuat' => $sp2d->created_at?->translatedFormat('d F Y H:i') ?? '-',
            'dokumen_url' => $sp2d->getFirstMediaUrl('dokumen') ?: null,
        ];

        $this->showSp2dDetail = true;
    }

    public function closeSp2dDetail(): void
    {
        $this->sp2dDetail = [];
        $this->showSp2dDetail = false;
    }

    public function saveSp2d(): void
    {
        if (! $this->isBendahara()) {
            abort(403);
        }

        // Normalisasi nilai: buang pemisah ribuan / karakter non-digit.
        if ($this->sp2dNilai !== null && $this->sp2dNilai !== '') {
            $bersih = preg_replace('/[^\d]/', '', (string) $this->sp2dNilai);
            $this->sp2dNilai = $bersih === '' ? null : $bersih;
        }

        $this->validate([
            'sp2dPengajuanId' => ['required', 'uuid', 'exists:pengajuan,id'],
            'sp2dNomor' => ['required', 'string', 'max:255'],
            'sp2dTanggal' => ['required', 'date'],
            'sp2dNilai' => ['nullable', 'numeric', 'min:0'],
            'sp2dKeterangan' => ['nullable', 'string', 'max:1000'],
            'sp2dDokumen' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ], [], [
            'sp2dNomor' => 'nomor SP2D',
            'sp2dTanggal' => 'tanggal SP2D',
            'sp2dNilai' => 'nilai',
            'sp2dDokumen' => 'dokumen',
        ]);

        $pengajuan = Pengajuan::query()->with(['bast', 'sp2d'])->find($this->sp2dPengajuanId);

        if (! $pengajuan?->bast) {
            $this->addError('sp2dPengajuanId', 'Pengajuan belum memiliki BAST.');

            return;
        }

        if ($pengajuan->sp2d) {
            $this->addError('sp2dPengajuanId', 'SP2D sudah ada untuk pengajuan ini.');

            return;
        }

        $sp2d = Sp2d::query()->create([
            'pengajuan_id' => $pengajuan->id,
            'nomor' => $this->sp2dNomor,
            'tanggal' => $this->sp2dTanggal,
            'nilai' => ($this->sp2dNilai !== null && $this->sp2dNilai !== '') ? (float) $this->sp2dNilai : null,
            'keterangan' => $this->sp2dKeterangan,
            'user_id' => Auth::id(),
        ]);

        if ($this->sp2dDokumen) {
            $sp2d->addMedia($this->sp2dDokumen->getRealPath())
                ->usingFileName($this->sp2dDokumen->getClientOriginalName())
                ->toMediaCollection('dokumen');
        }

        $this->resetSp2dForm();
        $this->showSp2dModal = false;
        toast()->success('Berhasil', 'SP2D berhasil disimpan.');
    }

    private function resetSp2dForm(): void
    {
        $this->reset(['sp2dPengajuanId', 'sp2dNomor', 'sp2dTanggal', 'sp2dNilai', 'sp2dKeterangan', 'sp2dDokumen']);
        $this->resetValidation();
    }

    public function render(): View
    {
        $query = $this->baseQuery();
        $this->applyTahapFilter($query);
        $this->applySearchFilter($query);
        $stats = $this->stats();

        return view('livewire.monitoring-bantuan-list', [
            'pengajuanList' => $query->paginate(10),
            'stats' => $stats,
            'isBendahara' => $this->isBendahara(),
            'showOpdFilter' => $this->shouldShowOpdFilter(),
            'opdOptions' => $this->opdOptions(),
            'chartSummary' => $this->chartSummary($stats),
            'chartTrend' => $this->chartTrend(),
        ]);
    }
}
