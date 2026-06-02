@extends('layouts.layout')
@section('title', 'Dashboard')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h4>Selamat Datang, <b>{{ auth()->user()->nama??'-' }}</b></h4>
                    <div class="text-muted font-heading text-small">Halaman Beranda</div>
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <!-- Stats Start -->
        <div class="mb-5">
            <h2 class="small-title">Summary</h2>
            <div class="row g-3">
                <div class="col-12 col-lg-3 col-xxl-3">
                    <div class="card stat-card stat-card-blue border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="stat-card-label">Total Perorangan</div>
                                <div class="stat-card-icon"><i data-acorn-icon="user" data-acorn-size="22"></i></div>
                            </div>
                            <div class="stat-card-value">{{ number_format($totalPerorangan) }}</div>
                            <div class="stat-card-deco"><i data-acorn-icon="user"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 col-xxl-3">
                    <div class="card stat-card stat-card-emerald border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="stat-card-label">Total Organisasi</div>
                                <div class="stat-card-icon"><i data-acorn-icon="building" data-acorn-size="22"></i></div>
                            </div>
                            <div class="stat-card-value">{{ number_format($totalOrganisasi) }}</div>
                            <div class="stat-card-deco"><i data-acorn-icon="building"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 col-xxl-3">
                    <div class="card stat-card stat-card-violet border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="stat-card-label">Jumlah Pengajuan</div>
                                <div class="stat-card-icon"><i data-acorn-icon="file-text" data-acorn-size="22"></i></div>
                            </div>
                            <div class="stat-card-value">{{ number_format($totalPengajuan) }}</div>
                            <div class="stat-card-deco"><i data-acorn-icon="file-text"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 col-xxl-3">
                    <div class="card stat-card stat-card-amber border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="stat-card-label">Total Bantuan</div>
                                <div class="stat-card-icon"><i data-acorn-icon="dollar" data-acorn-size="22"></i></div>
                            </div>
                            <div class="stat-card-value">Rp {{ number_format($totalBantuan) }}</div>
                            <div class="stat-card-deco"><i data-acorn-icon="dollar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats End -->

        <!-- Charts Start -->
        <h2 class="small-title">Analitik</h2>
        <div class="row g-3 mb-5">
            <div class="col-12 col-xl-6">
                <div class="card chart-card chart-card-violet border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="chart-card-header">
                            <div class="chart-card-icon"><i data-acorn-icon="file-text" data-acorn-size="20"></i></div>
                            <div>
                                <div class="chart-card-title">Chart Pengajuan</div>
                                <div class="chart-card-sub">Jumlah pengajuan per periode</div>
                            </div>
                        </div>
                        <div class="chart-card-body">
                            <div class="sh-45">
                                <canvas id="chartPengajuan"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="card chart-card chart-card-amber border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="chart-card-header">
                            <div class="chart-card-icon"><i data-acorn-icon="dollar" data-acorn-size="20"></i></div>
                            <div>
                                <div class="chart-card-title">Chart Realisasi</div>
                                <div class="chart-card-sub">Total nilai bantuan tersalurkan</div>
                            </div>
                        </div>
                        <div class="chart-card-body">
                            <div class="sh-45">
                                <canvas id="chartBantuan"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Charts End -->
    </div>
    <!-- Page Content End -->
@endsection
@push('js_page')
    <script src="{{ asset('js/cs/charts.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/Chart.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            const makeGradient = (ctx, top, bottom) => {
                const gradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.clientHeight || 320);
                gradient.addColorStop(0, top);
                gradient.addColorStop(1, bottom);
                return gradient;
            };

            const formatRupiah = (value) => 'Rp ' + Number(value).toLocaleString('id-ID');
            const formatNumber = (value) => Number(value).toLocaleString('id-ID');

            const baseChartOptions = (valueFormatter) => ({
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 700, easing: 'easeOutQuart' },
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    datalabels: { display: false },
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.92)',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(255,255,255,0.08)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        titleFont: { weight: '700', size: 12 },
                        bodyFont: { size: 13, weight: '600' },
                        callbacks: {
                            label: (ctx) => '  ' + valueFormatter(ctx.parsed.y),
                        },
                    },
                },
                layout: { padding: { top: 8, bottom: 8, left: 4, right: 8 } },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#64748b', font: { size: 11, weight: '600' } },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.18)', drawBorder: false },
                        border: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            padding: 6,
                            callback: (v) => valueFormatter(v),
                        },
                    },
                },
            });

            if (document.getElementById('chartPengajuan')) {
                const canvas = document.getElementById('chartPengajuan');
                const ctx = canvas.getContext('2d');
                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($dataChartPengajuan["labels"], JSON_THROW_ON_ERROR) !!},
                        datasets: [{
                            label: "{{ array_keys($dataChartPengajuan["data"])[0] ?? 'Jumlah' }}",
                            data: {!! json_encode(collect($dataChartPengajuan["data"]["Jumlah"]), JSON_THROW_ON_ERROR) !!},
                            backgroundColor: makeGradient(ctx, 'rgba(139, 92, 246, 0.95)', 'rgba(109, 40, 217, 0.65)'),
                            hoverBackgroundColor: makeGradient(ctx, 'rgba(167, 139, 250, 1)', 'rgba(124, 58, 237, 0.85)'),
                            borderRadius: 10,
                            borderSkipped: false,
                            barThickness: 'flex',
                            maxBarThickness: 38,
                        }],
                    },
                    options: baseChartOptions(formatNumber),
                });
            }

            if (document.getElementById('chartBantuan')) {
                const canvas = document.getElementById('chartBantuan');
                const ctx = canvas.getContext('2d');
                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($dataChartBantuan["labels"], JSON_THROW_ON_ERROR) !!},
                        datasets: [{
                            label: "{{ array_keys($dataChartBantuan["data"])[0] ?? 'Rupiah' }}",
                            data: {!! json_encode(collect($dataChartBantuan["data"]["Rupiah"]), JSON_THROW_ON_ERROR) !!},
                            backgroundColor: makeGradient(ctx, 'rgba(251, 191, 36, 0.95)', 'rgba(217, 119, 6, 0.65)'),
                            hoverBackgroundColor: makeGradient(ctx, 'rgba(253, 224, 71, 1)', 'rgba(245, 158, 11, 0.9)'),
                            borderRadius: 10,
                            borderSkipped: false,
                            barThickness: 'flex',
                            maxBarThickness: 38,
                        }],
                    },
                    options: baseChartOptions(formatRupiah),
                });
            }
        });
    </script>
@endpush
