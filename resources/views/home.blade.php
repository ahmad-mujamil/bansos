@extends('layouts.layout')
@section('title', 'Dashboard')
@section('content')
    <style>
        .dash-card { border: 0; border-radius: 14px; color: #fff; box-shadow: 0 6px 18px rgba(15, 23, 42, .12); overflow: hidden; }
        .dash-card .card-body { padding: 1.2rem 1.35rem; }
        .dash-blue { background: linear-gradient(135deg, #4f8df8 0%, #2563eb 100%); }
        .dash-cyan { background: linear-gradient(135deg, #22b8d4 0%, #0e7490 100%); }
        .dash-amber { background: linear-gradient(135deg, #f9b23c 0%, #ea7d1d 100%); }
        .dash-indigo { background: linear-gradient(135deg, #7c83f7 0%, #4338ca 100%); }
        .dash-green { background: linear-gradient(135deg, #21c5a6 0%, #36b67c 100%); }
        .dash-purple { background: linear-gradient(135deg, #8b5cf6 0%, #6c28d9 100%); }
        .dash-title { font-size: .82rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; opacity: .95; margin-bottom: .35rem; }
        .dash-value { font-size: 2.5rem; line-height: 1.05; font-weight: 800; margin-bottom: .85rem; }
        .dash-rows { border-top: 1px solid rgba(255, 255, 255, .25); padding-top: .55rem; display: flex; flex-direction: column; gap: .2rem; }
        .dash-row { display: flex; justify-content: space-between; align-items: center; gap: .5rem; }
        .dash-row__label { font-size: .76rem; font-weight: 600; text-transform: uppercase; letter-spacing: .02em; opacity: .92; }
        .dash-row__value { font-size: .9rem; font-weight: 700; }
        .dash-card--center { text-align: center; }
        .dash-card--center .dash-rows { text-align: left; }
        .dash-chart-card { border: 0; border-radius: 14px; box-shadow: 0 6px 18px rgba(15, 23, 42, .08); }
        .dash-chart-title { font-weight: 700; font-size: 1rem; color: #1f2937; }
    </style>

    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h4>Selamat Datang, <b>{{ auth()->user()->nama ?? '-' }}</b></h4>
                    <div class="text-muted font-heading text-small">Halaman Beranda</div>
                </div>
            </div>
            @if (!empty($isDemo))
                <div class="alert alert-warning d-flex align-items-center justify-content-between py-2 mb-0" role="alert">
                    <span><b>Mode Demo</b> — data di bawah hanya contoh untuk pratinjau tampilan.</span>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-dark">Tampilkan data asli</a>
                </div>
            @endif
        </div>

        {{-- ===== Baris 1: Total per jenis pengajuan ===== --}}
        @php $warnaKartu = ['dash-blue', 'dash-cyan', 'dash-amber']; @endphp
        <div class="row g-3 mb-3">
            @foreach ($kartuKategori as $kartu)
                <div class="col-12 col-md-4">
                    <div class="card dash-card {{ $warnaKartu[$loop->index % count($warnaKartu)] }} dash-card--center h-100">
                        <div class="card-body">
                            <div class="dash-title">{{ $kartu['title'] }}</div>
                            <div class="dash-value">{{ number_format($kartu['total']) }}</div>
                            <div class="dash-rows">
                                <div class="dash-row">
                                    <span class="dash-row__label">Usulan</span>
                                    <span class="dash-row__value">{{ number_format($kartu['usulan']) }}</span>
                                </div>
                                <div class="dash-row">
                                    <span class="dash-row__label">Verifikasi BA</span>
                                    <span class="dash-row__value">{{ number_format($kartu['verifikasi']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ===== Baris 2: Perorangan / Organisasi / Pengajuan ===== --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card dash-card dash-indigo h-100">
                    <div class="card-body">
                        <div class="dash-title">Perorangan</div>
                        <div class="dash-value">{{ number_format($totalPerorangan) }}</div>
                        <div class="dash-rows">
                            <div class="dash-row">
                                <span class="dash-row__label">Bantuan Sosial</span>
                                <span class="dash-row__value">{{ number_format($totalPerorangan) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card dash-card dash-green h-100">
                    <div class="card-body">
                        <div class="dash-title">Organisasi</div>
                        <div class="dash-value">{{ number_format($totalOrganisasi) }}</div>
                        <div class="dash-rows">
                            <div class="dash-row">
                                <span class="dash-row__label">Hibah</span>
                                <span class="dash-row__value">{{ number_format($orgHibah) }}</span>
                            </div>
                            <div class="dash-row">
                                <span class="dash-row__label">Bantuan ke Masyarakat</span>
                                <span class="dash-row__value">{{ number_format($orgKelompok) }}</span>
                            </div>
                            @if ($orgBansos > 0)
                                <div class="dash-row">
                                    <span class="dash-row__label">Bantuan Sosial</span>
                                    <span class="dash-row__value">{{ number_format($orgBansos) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card dash-card dash-purple h-100">
                    <div class="card-body">
                        <div class="dash-title">Pengajuan</div>
                        <div class="dash-value">{{ number_format($totalPengajuan) }}</div>
                        <div class="dash-rows">
                            <div class="dash-row">
                                <span class="dash-row__label">Bantuan Sosial</span>
                                <span class="dash-row__value">{{ number_format($pengBansos) }}</span>
                            </div>
                            <div class="dash-row">
                                <span class="dash-row__label">Hibah</span>
                                <span class="dash-row__value">{{ number_format($pengHibah) }}</span>
                            </div>
                            <div class="dash-row">
                                <span class="dash-row__label">Bantuan ke Masyarakat</span>
                                <span class="dash-row__value">{{ number_format($pengKelompok) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Baris 3: Chart ===== --}}
        @php
            $adaDataUsulan = array_sum($chartUsulan['values']) > 0;
            $adaDataPengajuan = collect($chartPengajuan['series'])->flatMap(fn ($s) => $s['data'])->sum() > 0;
        @endphp
        <div class="row g-3 mb-5">
            <div class="col-12 col-xl-6">
                <div class="card dash-chart-card h-100">
                    <div class="card-body">
                        <div class="dash-chart-title mb-3">Chart Usulan Calon Penerima Bantuan</div>
                        @if ($adaDataUsulan)
                            <div style="height: 320px;">
                                <canvas id="chartUsulan"></canvas>
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="height: 320px;">
                                <i data-acorn-icon="chart-4" data-acorn-size="40" class="mb-2 opacity-50"></i>
                                <div class="fw-semibold">Belum ada data</div>
                                <div class="text-small">Data usulan calon penerima bantuan belum tersedia.</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="card dash-chart-card h-100">
                    <div class="card-body">
                        <div class="dash-chart-title mb-3">Chart Pengajuan</div>
                        @if ($adaDataPengajuan)
                            <div style="height: 320px;">
                                <canvas id="chartPengajuan"></canvas>
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="height: 320px;">
                                <i data-acorn-icon="chart-4" data-acorn-size="40" class="mb-2 opacity-50"></i>
                                <div class="fw-semibold">Belum ada data</div>
                                <div class="text-small">Data pengajuan belum tersedia.</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js_page')
    <script src="{{ asset('js/vendor/Chart.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Chart.js v2.8 belum punya borderRadius -> override gambar bar agar sudut atas rounded.
            Chart.elements.Rectangle.prototype.draw = function () {
                const ctx = this._chart.ctx;
                const vm = this._view;
                const opts = this._chart.config.options || {};

                const left = vm.x - vm.width / 2;
                const right = vm.x + vm.width / 2;
                const top = vm.y;
                const bottom = vm.base;
                const width = right - left;
                const height = bottom - top; // positif jika nilai positif

                let radius = opts.cornerRadius || 0;
                if (opts.roundTopDatasetOnly != null && this._datasetIndex !== opts.roundTopDatasetOnly) {
                    radius = 0;
                }
                radius = Math.min(radius, Math.abs(height), width / 2);

                ctx.beginPath();
                ctx.fillStyle = vm.backgroundColor;
                ctx.strokeStyle = vm.borderColor;
                ctx.lineWidth = vm.borderWidth;

                const dir = height >= 0 ? 1 : -1; // arah dari base ke ujung nilai
                ctx.moveTo(left, bottom);
                ctx.lineTo(left, top + radius * dir);
                ctx.quadraticCurveTo(left, top, left + radius, top);
                ctx.lineTo(right - radius, top);
                ctx.quadraticCurveTo(right, top, right, top + radius * dir);
                ctx.lineTo(right, bottom);
                ctx.closePath();

                ctx.fill();
                if (vm.borderWidth) {
                    ctx.stroke();
                }
            };

            // Chart.js v2.8: tick integer (beginAtZero di dalam ticks, precision 0 -> step tanpa desimal)
            const integerTicks = { beginAtZero: true, precision: 0, suggestedMax: 1 };

            const usulanEl = document.getElementById('chartUsulan');
            if (usulanEl) {
                new Chart(usulanEl, {
                    type: 'bar',
                    data: {
                        labels: @json($chartUsulan['labels']),
                        datasets: [{
                            label: 'Jumlah Usulan',
                            data: @json($chartUsulan['values']),
                            backgroundColor: '#4d8af0',
                            hoverBackgroundColor: '#3b78e0',
                            maxBarThickness: 80,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cornerRadius: 8,
                        legend: { display: false },
                        plugins: { datalabels: { display: false } },
                        scales: {
                            xAxes: [{ gridLines: { display: false } }],
                            yAxes: [{ ticks: integerTicks }],
                        },
                    },
                });
            }

            const pengajuanEl = document.getElementById('chartPengajuan');
            if (pengajuanEl) {
                const series = @json($chartPengajuan['series']);
                const colors = ['#e8734f', '#4ec3e0', '#2f43b5'];
                const datasets = series.map((s, i) => ({
                    label: s.label,
                    data: s.data,
                    backgroundColor: colors[i % colors.length],
                    maxBarThickness: 90,
                }));

                new Chart(pengajuanEl, {
                    type: 'bar',
                    data: {
                        labels: @json($chartPengajuan['labels']),
                        datasets: datasets,
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cornerRadius: 8,
                        roundTopDatasetOnly: datasets.length - 1,
                        legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } },
                        plugins: { datalabels: { display: false } },
                        scales: {
                            xAxes: [{ stacked: true, gridLines: { display: false } }],
                            yAxes: [{ stacked: true, ticks: integerTicks }],
                        },
                    },
                });
            }
        });
    </script>
@endpush
