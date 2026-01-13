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
            <div class="row g-2">
                <div class="col-12 col-lg-3 col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="heading mb-0 d-flex justify-content-between lh-1-25 mb-3">
                                <span>Total <b>Perorangan</b></span>
                                <i data-acorn-icon="user" class="text-primary"></i>
                            </div>
                            {{--                            <div class="text-small text-success mb-1">--}}
                            {{--                                <i data-acorn-icon="arrow-top" class="me-1" data-acorn-size="13"></i>--}}
                            {{--                                <span class="text-medium">+18.4%</span>--}}
                            {{--                            </div>--}}
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
                            {{--                            <div class="text-small text-success mb-1">--}}
                            {{--                                <i data-acorn-icon="arrow-top" class="me-1" data-acorn-size="13"></i>--}}
                            {{--                                <span class="text-medium">+55.2%</span>--}}
                            {{--                            </div>--}}
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
                            {{--                            <div class="text-small text-success mb-1">--}}
                            {{--                                <i data-acorn-icon="arrow-top" class="me-1" data-acorn-size="13"></i>--}}
                            {{--                                <span class="text-medium">+55.2%</span>--}}
                            {{--                            </div>--}}
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
                            {{--                            <div class="text-small text-success mb-1">--}}
                            {{--                                <i data-acorn-icon="arrow-top" class="me-1" data-acorn-size="13"></i>--}}
                            {{--                                <span class="text-medium">+55.2%</span>--}}
                            {{--                            </div>--}}
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
