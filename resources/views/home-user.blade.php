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

        @if(auth()->user()->is_user() && !auth()->user()->userDetail)
        <!-- Alert Lengkapi Data Diri untuk Verifikasi Start -->
        <div class="alert-lengkapi-detail mb-4">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%); border-radius: 16px;">
                <div class="card-body p-4 p-lg-5 position-relative">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-md-auto">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-3" style="width: 72px; height: 72px; background: rgba(255,255,255,0.2);">
                                <i data-acorn-icon="user" data-acorn-size="40" style="color: #fff;"></i>
                            </div>
                        </div>
                        <div class="col-12 col-md">
                            <h5 class="mb-2 fw-bold text-white">
                                <i data-acorn-icon="information-circle" data-acorn-size="22" class="me-2 align-middle"></i>
                                Lengkapi Data Diri untuk Verifikasi
                            </h5>
                            <p class="mb-0 mb-md-2 text-white" style="font-size: 0.95rem; line-height: 1.5; opacity: 0.95;">
                                Untuk dapat diverifikasi oleh administrator, silakan lengkapi data diri Anda terlebih dahulu. Klik tombol di samping untuk mengisi formulir.
                            </p>
                        </div>
                        <div class="col-12 col-md-auto text-md-end">
                            <a href="{{ route('user-detail.create') }}" class="btn btn-light btn-lg px-4">
                                <i data-acorn-icon="edit" data-acorn-size="18" class="me-2"></i>
                                Lengkapi Data Diri
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alert Lengkapi Data Diri untuk Verifikasi End -->
        @endif

        @if(!auth()->user()->is_active)
        <!-- Alert Akun Belum Diverifikasi Start -->
        <div class="alert-verification mb-4">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #f5d020 0%, #ebb71a 50%, #d4a010 100%); border-radius: 16px;">
                <div class="card-body p-4 p-lg-5 position-relative">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-md-auto">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-3" style="width: 72px; height: 72px; background: rgba(0,0,0,0.15);">
                                <i data-acorn-icon="shield" data-acorn-size="40" style="color: #1a1a1a;"></i>
                            </div>
                        </div>
                        <div class="col-12 col-md">
                            <h5 class="mb-2 fw-bold" style="color: #1a1a1a;">
                                <i data-acorn-icon="information-circle" data-acorn-size="22" class="me-2 align-middle" style="color: #1a1a1a;"></i>
                                Akun Belum Diverifikasi
                            </h5>
                            <p class="mb-0 mb-md-2" style="font-size: 0.95rem; line-height: 1.5; color: rgba(0,0,0,0.8);">
                                Akun Anda sedang dalam proses verifikasi oleh administrator. Setelah diverifikasi, Anda dapat mengakses semua fitur secara penuh. Mohon menunggu atau hubungi admin jika membutuhkan bantuan.
                            </p>
                        </div>
                        <div class="col-12 col-md-auto text-md-end">
                            <span class="badge px-3 py-2 fs-6 fw-semibold" style="background: rgba(0,0,0,0.2); color: #1a1a1a;">
                                <i data-acorn-icon="clock" data-acorn-size="18" class="me-1 align-middle" style="color: #1a1a1a;"></i>
                                Menunggu Verifikasi
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alert Akun Belum Diverifikasi End -->
        @endif

        @php
            $showDetailData = auth()->user()->is_user()
                && auth()->user()->userDetail
                && (!auth()->user()->is_active || auth()->user()->userDetail->verification_status->value !== 'approved');
        @endphp
        @if($showDetailData)
        <!-- Card Detail Data Diri (belum verifikasi / belum active) Start -->
        @php $d = auth()->user()->userDetail; @endphp
        <div class="mb-4">
            <h2 class="small-title">Detail Data Diri</h2>
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Jenis</label>
                            <div class="fw-semibold">{{ $d->type?->getDescription() ?? '-' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Status Verifikasi</label>
                            <div>
                                @if($d->verification_status->value === 'approved')
                                    <span class="badge bg-success">{{ $d->verification_status->getDescription() }}</span>
                                @elseif($d->verification_status->value === 'rejected')
                                    <span class="badge bg-danger">{{ $d->verification_status->getDescription() }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $d->verification_status->getDescription() }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Nama User</label>
                            <div class="fw-semibold">{{ $d->nama_user ?? '-' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Telepon</label>
                            <div class="fw-semibold">{{ $d->phone ?? '-' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted text-small text-uppercase">Alamat</label>
                            <div class="fw-semibold">{{ $d->alamat ?? '-' }}</div>
                        </div>
                        @if($d->desa_id)
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Desa</label>
                            <div class="fw-semibold">{{ $d->desa?->nama ?? '-' }}</div>
                        </div>
                        @endif
                        @if(in_array($d->type?->value ?? '', ['IND', 'KLP']))
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Nama Personal</label>
                            <div class="fw-semibold">{{ $d->nama_personal ?? '-' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">NIK</label>
                            <div class="fw-semibold">{{ $d->nik ?? '-' }}</div>
                        </div>
                        @else
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Nama Lembaga</label>
                            <div class="fw-semibold">{{ $d->nama_lembaga ?? '-' }}</div>
                        </div>
                        @endif
                        @if($d->verification_status->value === 'rejected' && $d->verification_note)
                        <div class="col-12">
                            <label class="text-muted text-small text-uppercase">Catatan Verifikasi</label>
                            <div class="text-danger">{{ $d->verification_note }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <a href="{{ route('user-detail.create') }}" class="btn btn-outline-primary btn-sm">
                            <i data-acorn-icon="edit" data-acorn-size="16" class="me-1"></i>
                            Ubah Data Diri
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Detail Data Diri End -->
        @endif

        @if(auth()->user()->is_active)
        <!-- Stats Start -->
        <div class="mb-5">
            <h2 class="small-title">Summary</h2>
            <div class="row g-2">
                <div class="col-12 col-lg-3 col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="heading mb-0 d-flex justify-content-between lh-1-25 mb-3">
                                <span>Total <b>Perorangan</b></span>
                                <i data-acorn-icon="user" class="text-primary"></i>
                            </div>
                          
                            <div class="cta-1 text-primary">{{ number_format($totalPerorangan) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="heading mb-0 d-flex justify-content-between lh-1-25 mb-3">
                                <span>Total <b>Organisasi</b></span>
                                <i data-acorn-icon="category" class="text-primary"></i>
                            </div>
                           
                            <div class="cta-1 text-primary">{{ number_format($totalOrganisasi) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="heading mb-0 d-flex justify-content-between lh-1-25 mb-3">
                                <span>Total <b>Pengajuan</b></span>
                                <i data-acorn-icon="category" class="text-primary"></i>
                            </div>
                            
                            <div class="cta-1 text-primary">{{ number_format($totalPengajuan) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="heading mb-0 d-flex justify-content-between lh-1-25 mb-3">
                                <span>Total <b>Bansos</b></span>
                                <i data-acorn-icon="category" class="text-primary"></i>
                            </div>
                           
                            <div class="cta-1 text-primary">Rp. {{ number_format($totalBansos) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats End -->

        <!-- Charts Start -->
        <div class="row">
            <div class="col-6 col-xxl-6 col-xl-6 col-lg-6">
                <h2 class="small-title">Chart Pengajuan</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="sh-45">
                            <canvas id="chartPengajuan"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xxl-6 col-xl-6 col-lg-6">
                <h2 class="small-title">Chart Realisasi</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="sh-45">
                            <canvas id="chartBansos"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts End -->
        @endif
    </div>
    <!-- Page Content End -->
@endsection
@push('js_page')
    <script src="{{ asset('js/cs/charts.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/Chart.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            if (document.getElementById('chartPengajuan')) {
                const chartPengajuan = document.getElementById('chartPengajuan');
                new Chart(chartPengajuan, {
                    type: 'bar',
                    data: {
                        labels: {!!  json_encode($dataChartPengajuan["labels"], JSON_THROW_ON_ERROR) !!},
                        datasets: [
                            {
                                label: "{{ array_keys($dataChartPengajuan["data"])[0]??"-" }}",
                                fill: true,
                                borderColor: [Globals.warning],
                                borderWidth: 2,
                                data: {!!  json_encode(collect($dataChartPengajuan["data"]["Jumlah"]), JSON_THROW_ON_ERROR) !!},
                            },
                        ],
                    },

                    options: {
                        plugins: {
                            datalabels: {display: false},
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        title: {
                            display: false,
                        },
                        layout: {
                            padding: {
                                bottom: 20,
                            },
                        },
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: ChartsExtend.LegendLabels(),
                        },
                        tooltips: ChartsExtend.ChartTooltip(),
                    },
                });
            }

            if (document.getElementById('chartBansos')) {
                const chartBansos = document.getElementById('chartBansos');
                new Chart(chartBansos, {
                    type: 'bar',
                    data: {
                        labels: {!!  json_encode($dataChartBansos["labels"], JSON_THROW_ON_ERROR) !!},
                        datasets: [
                            {
                                label: "{{ array_keys($dataChartBansos["data"])[0]??"-" }}",
                                fill: true,
                                borderColor: [Globals.primary],
                                borderWidth: 2,
                                data: {!!  json_encode(collect($dataChartBansos["data"]["Rupiah"]), JSON_THROW_ON_ERROR) !!},
                            },
                        ],
                    },

                    options: {
                        plugins: {
                            datalabels: {display: false},
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        title: {
                            display: false,
                        },
                        layout: {
                            padding: {
                                bottom: 20,
                            },
                        },
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: ChartsExtend.LegendLabels(),
                        },
                        tooltips: ChartsExtend.ChartTooltip(),
                    },
                });
            }
        });
    </script>

@endpush
