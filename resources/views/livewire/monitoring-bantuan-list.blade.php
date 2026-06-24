<div>
    <style>
        .monitoring-row { cursor: pointer; }
        .monitoring-row .chevron { transition: transform .2s ease; }
        .monitoring-row[aria-expanded="true"] .chevron { transform: rotate(90deg); }
        .monitoring-row:hover { background-color: rgba(0, 0, 0, .02); }
    </style>

    {{-- Filter selalu tampil di atas (berlaku untuk kedua tab) --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-lg-3">
                    <label for="tahap-monitoring" class="form-label text-small text-uppercase text-muted">Tahap</label>
                    <div wire:ignore>
                        <select id="tahap-monitoring" class="form-select" data-placeholder="Pilih tahap">
                            <option value="semua">Semua</option>
                            <option value="belum_bast">Belum BAST</option>
                            <option value="sudah_bast">Sudah BAST</option>
                        </select>
                    </div>
                </div>
                @if ($showOpdFilter)
                    <div class="col-12 col-lg-3">
                        <label for="opd-monitoring" class="form-label text-small text-uppercase text-muted">OPD</label>
                        <div wire:ignore>
                            <select id="opd-monitoring" class="form-select" data-placeholder="Semua OPD">
                                <option value="all" @selected($opdId === '')>Semua OPD</option>
                                @foreach ($opdOptions as $opd)
                                    <option value="{{ $opd->id }}" @selected($opdId === $opd->id)>{{ $opd->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col-12 col-lg-2">
                    <label for="tahun-monitoring" class="form-label text-small text-uppercase text-muted">Tahun</label>
                    <div class="input-group">
                        <input
                            id="tahun-monitoring"
                            type="text"
                            class="form-control"
                            wire:model.live.debounce.500ms="tahun"
                            inputmode="numeric"
                            maxlength="4"
                            placeholder="Semua"
                        >
                        <button
                            class="btn btn-outline-secondary"
                            type="button"
                            wire:click="setSemuaTahun"
                            @disabled($tahun === '')
                        >
                            Semua
                        </button>
                    </div>
                    <div class="text-small text-muted mt-1">Isi 4 digit (contoh: 2026)</div>
                </div>
                <div class="col-12 {{ $showOpdFilter ? 'col-lg-4' : 'col-lg-7' }}">
                    <label for="search-monitoring" class="form-label text-small text-uppercase text-muted">Cari pengajuan</label>
                    <input
                        id="search-monitoring"
                        type="text"
                        class="form-control"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Kode, judul, pemohon, atau jenis bantuan"
                    >
                </div>
            </div>

            <div class="mt-2 text-muted text-small" wire:loading wire:target="tahap,search,opdId,tahun,setSemuaTahun">
                Memuat data...
            </div>
        </div>
    </div>

    {{-- Tab navigasi --}}
    <ul class="nav nav-tabs mb-3" id="monitoring-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data-pane"
                type="button" role="tab" aria-controls="data-pane" aria-selected="true">
                Data Bantuan
                <span class="badge bg-secondary ms-1">{{ number_format($pengajuanList->total()) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ringkasan-tab" data-bs-toggle="tab" data-bs-target="#ringkasan-pane"
                type="button" role="tab" aria-controls="ringkasan-pane" aria-selected="false">
                Ringkasan &amp; Grafik
            </button>
        </li>
    </ul>

    {{-- Pembawa data chart, ikut ter-update tiap morph Livewire --}}
    <div id="monitoring-chart-data"
        data-summary='@json($chartSummary)'
        data-trend='@json($chartTrend)'
        hidden></div>

    <div class="tab-content">
        {{-- ============ TAB DATA BANTUAN ============ --}}
        <div class="tab-pane fade show active" id="data-pane" role="tabpanel" aria-labelledby="data-tab">
            @if ($pengajuanList->isEmpty())
                <div class="alert alert-light border" role="alert">
                    Tidak ada data monitoring bantuan untuk filter yang dipilih.
                </div>
            @else
                <div class="list-group shadow-sm">
                    @foreach ($pengajuanList as $pengajuan)
                        @php
                            $nilaiPengajuan = $pengajuan->nilai !== null ? 'Rp '.number_format((float) $pengajuan->nilai, 0, ',', '.') : '-';
                            $nilaiRekomendasiRaw = $pengajuan->verifikasiPengajuan?->nilai_rekomendasi;
                            $nilaiRekomendasi = $nilaiRekomendasiRaw !== null ? 'Rp '.number_format((float) $nilaiRekomendasiRaw, 0, ',', '.') : '-';
                            $dokumenPengajuanUrl = $pengajuan->getFirstMediaUrl('pengajuan');
                            $dokumenBaVerifikasiUrl = $pengajuan->verifikasiPengajuan?->getFirstMediaUrl('ba-verifikasi');
                            $dokumenBastUrl = $pengajuan->bast?->getFirstMediaUrl('dokumen');
                            $isAdmin = (bool) (auth()->user()?->is_admin() || auth()->user()?->is_super());
                            $sudahDiverifikasi = in_array($pengajuan->status, [
                                \App\Enums\PengajuanStatus::DISETUJUI,
                                \App\Enums\PengajuanStatus::DITOLAK,
                            ], true);
                            $bisaBatalVerifikasi = $sudahDiverifikasi && ($isAdmin || ! $dokumenBaVerifikasiUrl);
                            $detailId = 'monitoring-detail-'.$pengajuan->id;
                        @endphp
                        <div class="list-group-item p-0" wire:key="monitoring-{{ $pengajuan->id }}">
                            {{-- Baris ringkasan --}}
                            <div class="d-flex align-items-center gap-3 p-3">
                                {{-- Area klik untuk expand --}}
                                <div class="monitoring-row d-flex align-items-center gap-3 flex-grow-1" style="min-width: 0;"
                                    role="button" data-bs-toggle="collapse" data-bs-target="#{{ $detailId }}"
                                    aria-expanded="false" aria-controls="{{ $detailId }}">
                                    <span class="chevron d-inline-flex flex-shrink-0 text-muted">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M6 4l4 4-4 4V4z"/></svg>
                                    </span>
                                    <div style="min-width: 0;">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="text-small text-muted text-uppercase">{{ $pengajuan->kode_pengajuan }}</span>
                                            <span class="badge bg-{{ $pengajuan->status?->badgeColor() ?? 'secondary' }}">
                                                {{ $pengajuan->status?->getDescription() ?? '-' }}
                                            </span>
                                            @if ($pengajuan->bast)
                                                <span class="badge bg-success">Sudah BAST</span>
                                            @else
                                                <span class="badge bg-info text-white">Siap BAST</span>
                                            @endif
                                        </div>
                                        <div class="fw-semibold text-truncate" title="{{ $pengajuan->judul }}">{{ $pengajuan->judul ?? '-' }}</div>
                                        <div class="text-small text-muted text-truncate">
                                            {{ $pengajuan->jenisBantuan?->nama ?? '-' }} ·
                                            {{ $pengajuan->user?->nama ?? $pengajuan->user?->email ?? '-' }} ·
                                            @if ($pengajuan->opd)
                                                <span title="{{ $pengajuan->opd->nama }}">{{ $pengajuan->opd->singkatan }}</span> ·
                                            @endif
                                            {{ $pengajuan->created_at?->translatedFormat('d M Y') ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol aksi di kanan --}}
                                <div class="monitoring-actions d-flex flex-wrap gap-1 justify-content-end flex-shrink-0">
                                    @if (auth()->user()?->is_opd())
                                        <a href="{{ route('verifikasi-pengajuan.show', $pengajuan) }}" class="btn btn-sm btn-outline-primary">Detail Verifikasi</a>
                                    @endif
                                    @if ($pengajuan->bast)
                                        <a href="{{ route('bast.show', $pengajuan->bast) }}" class="btn btn-sm btn-outline-success">Lihat BAST</a>
                                    @endif
                                    @if ($bisaBatalVerifikasi)
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-batal-verifikasi" data-url="{{ route('verifikasi-pengajuan.batal-verifikasi', $pengajuan) }}">
                                            Batal Verifikasi
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Detail (expand) --}}
                            <div class="collapse" id="{{ $detailId }}">
                                <div class="border-top bg-light p-3">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Pemohon</div>
                                            <div class="fw-semibold">{{ $pengajuan->user?->nama ?? $pengajuan->user?->email ?? '-' }}</div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Dinas / OPD</div>
                                            <div class="fw-semibold">{{ $pengajuan->opd?->nama ?? '-' }}</div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Nilai Pengajuan</div>
                                            <div class="fw-semibold">{{ $nilaiPengajuan }}</div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Nilai Rekomendasi</div>
                                            <div class="fw-semibold">{{ $nilaiRekomendasi }}</div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Status BAST</div>
                                            @if ($pengajuan->bast)
                                                <div class="fw-semibold">No. {{ $pengajuan->bast->nomor }}</div>
                                                <div class="text-small text-muted">{{ $pengajuan->bast->tanggal?->translatedFormat('d M Y') ?? '-' }}</div>
                                            @else
                                                <span class="badge bg-info text-white">Belum ada BAST</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="text-small text-uppercase text-muted mb-2">Dokumen Terkait</div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if ($dokumenPengajuanUrl)
                                                <a href="{{ $dokumenPengajuanUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">Dokumen Pengajuan</a>
                                            @endif
                                            @if ($dokumenBaVerifikasiUrl)
                                                <a href="{{ $dokumenBaVerifikasiUrl }}" target="_blank" class="btn btn-sm btn-outline-info">BA Verifikasi</a>
                                            @elseif (auth()->user()?->is_opd())
                                                <div class="btn-group">
                                                    <a href="{{ route('verifikasi-pengajuan.download-ba-verifikasi', $pengajuan) }}" target="_blank" class="btn btn-sm btn-outline-info">Download BA Verifikasi (PDF)</a>
                                                    <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span class="visually-hidden">Pilih format</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('verifikasi-pengajuan.download-ba-verifikasi', $pengajuan) }}" target="_blank">Download sebagai PDF</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('verifikasi-pengajuan.download-ba-verifikasi', ['pengajuan' => $pengajuan, 'format' => 'word']) }}" target="_blank">Download sebagai Word</a></li>
                                                    </ul>
                                                </div>
                                            @endif
                                            @if ($dokumenBastUrl)
                                                <a href="{{ $dokumenBastUrl }}" target="_blank" class="btn btn-sm btn-outline-success">Dokumen BAST</a>
                                            @endif
                                            @if (! $dokumenPengajuanUrl && ! $dokumenBaVerifikasiUrl && ! $dokumenBastUrl && ! auth()->user()?->is_opd())
                                                <span class="text-small text-muted">Belum ada dokumen.</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
                    <div class="text-small text-muted">
                        Menampilkan {{ $pengajuanList->firstItem() }}-{{ $pengajuanList->lastItem() }}
                        dari {{ $pengajuanList->total() }} data.
                    </div>
                    <div>
                        {{ $pengajuanList->links() }}
                    </div>
                </div>
            @endif
        </div>

        {{-- ============ TAB RINGKASAN & GRAFIK ============ --}}
        <div class="tab-pane fade" id="ringkasan-pane" role="tabpanel" aria-labelledby="ringkasan-tab">
            <div class="card mb-3">
                <div class="card-body">
                    <p class="text-muted mb-3">
                        <strong>Semua (default):</strong> gabungan pengajuan siap input BAST dan yang sudah punya BAST.
                        <br>
                        <strong>Belum BAST:</strong> disetujui, BA verifikasi bertanda tangan sudah diunggah, belum ada data BAST.
                        <br>
                        <strong>Sudah BAST:</strong> pengajuan yang sudah memiliki Berita Acara Serah Terima.
                    </p>

                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="text-small text-uppercase text-muted mb-1">Siap input BAST</div>
                                <h3 class="mb-0">{{ number_format($stats['belum_bast']) }}</h3>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="text-small text-uppercase text-muted mb-1">Sudah BAST</div>
                                <h3 class="mb-0">{{ number_format($stats['sudah_bast']) }}</h3>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="text-small text-uppercase text-muted mb-1">Total ditampilkan</div>
                                <h3 class="mb-0">{{ number_format($pengajuanList->total()) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-12 col-xl-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-small text-uppercase text-muted mb-2">Komposisi Tahap BAST</div>
                            <div class="position-relative" style="height: 260px;">
                                <canvas id="monitoring-summary-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-8">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-small text-uppercase text-muted mb-2">Tren Monitoring</div>
                            <div class="position-relative" style="height: 260px;">
                                <canvas id="monitoring-trend-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    let monitoringSummaryChart = null;
    let monitoringTrendChart = null;
    let activeMonitoringTab = '#data-pane';

    const readMonitoringChartData = () => {
        const el = document.getElementById('monitoring-chart-data');
        if (! el) {
            return null;
        }

        try {
            return {
                summary: JSON.parse(el.dataset.summary),
                trend: JSON.parse(el.dataset.trend),
            };
        } catch (e) {
            return null;
        }
    };

    const initMonitoringCharts = () => {
        if (typeof Chart === 'undefined') {
            setTimeout(initMonitoringCharts, 50);

            return;
        }

        const data = readMonitoringChartData();
        if (! data) {
            return;
        }

        const summaryData = data.summary;
        const trendData = data.trend;

        const summaryCanvas = document.getElementById('monitoring-summary-chart');
        if (summaryCanvas) {
            if (monitoringSummaryChart) {
                monitoringSummaryChart.destroy();
            }

            monitoringSummaryChart = new Chart(summaryCanvas, {
                type: 'doughnut',
                data: {
                    labels: summaryData.labels,
                    datasets: [{
                        data: summaryData.values,
                        backgroundColor: ['#3abff8', '#2fb344'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                    },
                },
            });
        }

        const trendCanvas = document.getElementById('monitoring-trend-chart');
        if (trendCanvas) {
            if (monitoringTrendChart) {
                monitoringTrendChart.destroy();
            }

            monitoringTrendChart = new Chart(trendCanvas, {
                type: 'line',
                data: {
                    labels: trendData.labels,
                    datasets: [
                        {
                            label: 'Belum BAST',
                            data: trendData.series.belum_bast,
                            borderColor: '#3abff8',
                            backgroundColor: 'rgba(58, 191, 248, 0.12)',
                            tension: 0.35,
                            fill: true,
                        },
                        {
                            label: 'Sudah BAST',
                            data: trendData.series.sudah_bast,
                            borderColor: '#2fb344',
                            backgroundColor: 'rgba(47, 179, 68, 0.12)',
                            tension: 0.35,
                            fill: true,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                            },
                        },
                    },
                },
            });
        }
    };

    const initMonitoringTabs = () => {
        document.querySelectorAll('#monitoring-tab button[data-bs-toggle="tab"]').forEach((btn) => {
            if (btn.dataset.tabBound) {
                return;
            }
            btn.dataset.tabBound = '1';

            btn.addEventListener('shown.bs.tab', (event) => {
                activeMonitoringTab = event.target.getAttribute('data-bs-target');
                if (activeMonitoringTab === '#ringkasan-pane') {
                    initMonitoringCharts();
                }
            });
        });
    };

    const restoreMonitoringTab = () => {
        if (activeMonitoringTab === '#data-pane' || typeof bootstrap === 'undefined') {
            return;
        }

        const trigger = document.querySelector(`#monitoring-tab button[data-bs-target="${activeMonitoringTab}"]`);
        if (trigger) {
            bootstrap.Tab.getOrCreateInstance(trigger).show();
        }
    };

    const initMonitoringFilterSelect2 = () => {
        if (typeof $ === 'undefined' || !$.fn.select2) {
            setTimeout(initMonitoringFilterSelect2, 50);

            return;
        }

        const tahapValue = $wire.get('tahap');
        const $tahapSelect = $('#tahap-monitoring');
        if ($tahapSelect.length) {
            if (! $tahapSelect.hasClass('select2-hidden-accessible')) {
                $tahapSelect.select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    minimumResultsForSearch: Infinity,
                });
            }

            $tahapSelect.val(tahapValue).trigger('change.select2');
            $tahapSelect.off('change.monitoringTahap').on('change.monitoringTahap', function () {
                const value = $(this).val() || 'semua';

                $wire.set('tahap', value);
            });
        }

        const opdValue = $wire.get('opdId') || 'all';
        const $opdSelect = $('#opd-monitoring');
        if ($opdSelect.length) {
            if (! $opdSelect.hasClass('select2-hidden-accessible')) {
                $opdSelect.select2({
                    theme: 'bootstrap4',
                    minimumResultsForSearch: Infinity,
                    width: '100%',
                });
            }

            $opdSelect.val(opdValue).trigger('change.select2');
            $opdSelect.off('change.monitoringOpd').on('change.monitoringOpd', function () {
                const selectedValue = $(this).val();
                const value = selectedValue === 'all' ? '' : (selectedValue || '');

                $wire.set('opdId', value);
            });
        }
    };

    const initBatalVerifikasi = () => {
        $($el).off('click.batalVerifikasi', '.btn-batal-verifikasi').on('click.batalVerifikasi', '.btn-batal-verifikasi', function () {
            const url = $(this).data('url');

            Swal.fire({
                title: 'Batal Verifikasi?',
                text: 'Pengajuan akan kembali ke status Diajukan dan data verifikasi akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#dc3545',
            }).then(function (result) {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    };

    initMonitoringTabs();
    initMonitoringFilterSelect2();
    initBatalVerifikasi();

    Livewire.hook('morph.updated', () => {
        setTimeout(() => {
            initMonitoringTabs();
            restoreMonitoringTab();
            initMonitoringFilterSelect2();
            initBatalVerifikasi();
            if (activeMonitoringTab === '#ringkasan-pane') {
                initMonitoringCharts();
            }
        }, 10);
    });
</script>
@endscript
