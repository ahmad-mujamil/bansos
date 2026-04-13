<?php

namespace App\Livewire;

use App\Enums\PengajuanStatus;
use App\Models\Opd;
use App\Models\Pengajuan;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class MonitoringBantuanList extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $tahap = 'semua';

    public string $search = '';

    public string $opdId = '';

    public string $tahun = '';

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

    private function baseQuery(): Builder
    {
        $query = Pengajuan::query()
            ->with(['user', 'jenisBantuan', 'media', 'verifikasiPengajuan.media', 'bast.media'])
            ->select('pengajuan.*');

        $user = Auth::user();

        if ($user?->is_opd()) {
            if ($user->opd_id === null) {
                $query->whereRaw('1 = 0');

                return $query;
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

        return $query->latest('pengajuan.created_at');
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
            if (!Auth::user()->is_super()) {
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
        $base = $this->baseQuery();

        $belumBast = (clone $base);
        $this->applyBelumBastSiapInputCriteria($belumBast);

        return [
            'belum_bast' => $belumBast->count(),
            'sudah_bast' => (clone $base)->whereHas('bast')->count(),
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
        $labels = [];
        $belumBastSeries = [];
        $sudahBastSeries = [];

        for ($index = 0; $index < $monthsToShow; $index++) {
            $monthStart = (clone $startMonth)->addMonths($index)->startOfMonth();
            $monthEnd = (clone $monthStart)->endOfMonth();

            $labels[] = $selectedYear !== null
                ? $monthStart->translatedFormat('M')
                : $monthStart->translatedFormat('M Y');

            $belumBastQuery = $this->baseQuery()
                ->whereBetween('pengajuan.created_at', [$monthStart, $monthEnd]);
            $this->applyBelumBastSiapInputCriteria($belumBastQuery);
            $belumBastSeries[] = $belumBastQuery->count();

            $sudahBastSeries[] = $this->baseQuery()
                ->whereBetween('pengajuan.created_at', [$monthStart, $monthEnd])
                ->whereHas('bast')
                ->count();
        }

        return [
            'labels' => $labels,
            'series' => [
                'belum_bast' => $belumBastSeries,
                'sudah_bast' => $sudahBastSeries,
            ],
        ];
    }

    public function render(): View
    {
        $query = $this->baseQuery();
        $this->applyTahapFilter($query);
        $this->applySearchFilter($query);
        $stats = $this->stats();

        return view('livewire.monitoring-bantuan-list', [
            'pengajuanList' => $query->paginate(4),
            'stats' => $stats,
            'showOpdFilter' => $this->shouldShowOpdFilter(),
            'opdOptions' => $this->opdOptions(),
            'chartSummary' => $this->chartSummary($stats),
            'chartTrend' => $this->chartTrend(),
        ]);
    }
}
