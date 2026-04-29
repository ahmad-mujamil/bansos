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
                                <span>Total <b>Organisasi/Kelompok</b></span>
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
                                <span>Total <b>Realisasi</b></span>
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
                <div class="col-12 col-lg-3 col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="heading mb-0 d-flex justify-content-between lh-1-25 mb-3">
                                <span>Total <b>Blacklist</b></span>
                                <i data-acorn-icon="user" class="text-primary"></i>
                            </div>
                            <div class="cta-1 text-primary">{{ number_format($totalBlacklist) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-5">
            <h2 class="small-title mb-3">Pilih Jenis Bantuan</h2>
            <p class="text-muted mb-4">Silakan pilih jenis bantuan yang ingin Anda ajukan. Klik kartu untuk melanjutkan.</p>
            <div class="row g-4">
                {{-- Jika Individu: hanya Bantuan Sosial --}}
                
                    <div class="col-4">
                        <a href="{{ route('pengajuan-opd.create', ['jenis' => 'bansos']) }}" class="text-decoration-none d-block h-100">
                            <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #ea580c 0%, #c2410c 50%, #9a3412 100%); min-height: 200px;">
                                    <div class="p-4 position-relative z-1">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                            <i data-acorn-icon="heart" data-acorn-size="28" class="text-white"></i>
                                        </div>
                                        <h5 class="text-white fw-bold mb-2">Bantuan Sosial</h5>
                                        <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan sosial untuk meringankan beban dan mendukung kebutuhan dasar penerima manfaat.</p>
                                        <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                            Ajukan sekarang
                                            <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                        </span>
                                    </div>
                                    <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                        <i data-acorn-icon="heart" class="text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                {{-- Jika Kelompok: hanya Bantuan ke Masyarakat --}}
                
                    <div class="col-4">
                        <a href="{{ route('pengajuan-opd.create', ['jenis' => 'bantuan_kelompok']) }}" class="text-decoration-none d-block h-100">
                            <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #0d9488 0%, #0f766e 50%, #115e59 100%); min-height: 200px;">
                                    <div class="p-4 position-relative z-1">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                            <i data-acorn-icon="grid-1" data-acorn-size="28" class="text-white"></i>
                                        </div>
                                        <h5 class="text-white fw-bold mb-2">Bantuan ke Masyarakat</h5>
                                        <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan untuk program pemberdayaan dan peningkatan kesejahteraan kelompok masyarakat.</p>
                                        <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                            Ajukan sekarang
                                            <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                        </span>
                                    </div>
                                    <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                        <i data-acorn-icon="grid-1" class="text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                {{-- Selain itu: hanya Hibah --}}
                
                    <div class="col-4">
                        <a href="{{ route('pengajuan-opd.create', ['jenis' => 'hibah']) }}" class="text-decoration-none d-block h-100">
                            <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #6366f1 0%, #4f46e5 50%, #4338ca 100%); min-height: 200px;">
                                    <div class="p-4 position-relative z-1">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                            <i data-acorn-icon="gift" data-acorn-size="28" class="text-white"></i>
                                        </div>
                                        <h5 class="text-white fw-bold mb-2">Hibah</h5>
                                        <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan hibah untuk mendukung kegiatan atau program yang Anda jalankan.</p>
                                        <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                            Ajukan sekarang
                                            <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                        </span>
                                    </div>
                                    <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                        <i data-acorn-icon="gift" class="text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

            </div>
        </div>

        @if($pendudukTidakValid->isNotEmpty())
            <div class="mb-5">
                <h2 class="small-title mb-3">Penduduk Tidak Valid</h2>
                <p class="text-muted mb-3">
                    Penduduk yang terdaftar pada kelompok di OPD Anda dengan status verifikasi
                    <span class="badge bg-danger">Tidak Valid</span>.
                </p>

                <div class="card mb-5">
                    <div class="card-body">
                        <table class="data-table data-table-pagination data-table-standard responsive nowrap stripe" id="datatable-tidak-valid">
                            <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase">Nama</th>
                                    <th class="text-muted text-small text-uppercase">NIK</th>
                                    <th class="text-muted text-small text-uppercase">Kelompok</th>
                                    <th class="text-muted text-small text-uppercase">Catatan</th>
                                    <th class="text-muted text-small text-uppercase">Diverifikasi</th>
                                </tr>
                            </thead>
                            <tbody class="text-alternate text-medium">
                            @foreach($pendudukTidakValid as $p)
                                @php
                                    $kelompokItems = $p->organisasiDetails
                                        ->filter(fn ($d) => $d->organisasi !== null)
                                        ->map(fn ($d) => [
                                            'nama' => $d->organisasi->nama,
                                            'jabatan' => $d->jabatan?->getDescription() ?? null,
                                        ])
                                        ->unique('nama')
                                        ->values();
                                    $catatan = trim((string) $p->catatan_validasi) !== ''
                                        ? $p->catatan_validasi
                                        : '-';
                                @endphp
                                <tr>
                                    <td>{{ $p->nama }}</td>
                                    <td>{{ $p->nik }}</td>
                                    <td>
                                        @forelse($kelompokItems as $k)
                                            <div class="mb-1">
                                                <span class="badge bg-primary">{{ $k['nama'] }}</span>
                                                @if($k['jabatan'])
                                                    <small class="text-muted ms-1">{{ $k['jabatan'] }}</small>
                                                @endif
                                            </div>
                                        @empty
                                            <span class="text-muted">-</span>
                                        @endforelse
                                    </td>
                                    <td>{{ $catatan }}</td>
                                    <td>
                                        {{ $p->validated_at?->translatedFormat('d M Y H:i') }}
                                        @if($p->validatedBy)
                                            <div class="text-muted text-small">oleh {{ $p->validatedBy->nama }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        <!-- Stats End -->

        <!-- Charts Start -->
{{--        <div class="row">--}}
{{--            <div class="col-6 col-xxl-6 col-xl-6 col-lg-6">--}}
{{--                <h2 class="small-title">Chart Pengajuan</h2>--}}
{{--                <div class="card mb-5">--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="sh-45">--}}
{{--                            <canvas id="chartPengajuan"></canvas>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-6 col-xxl-6 col-xl-6 col-lg-6">--}}
{{--                <h2 class="small-title">Chart Realisasi</h2>--}}
{{--                <div class="card mb-5">--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="sh-45">--}}
{{--                            <canvas id="chartBansos"></canvas>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


        <!-- Charts End -->
    </div>
    <!-- Page Content End -->
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css') }}" />
@endpush
@push('js_vendor')
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        $(function () {
            new DatatableExtend();
            if ($('#datatable-tidak-valid').length) {
                $('#datatable-tidak-valid').DataTable({
                    language: {
                        paginate: {
                            previous: '<i class="cs-chevron-left"></i>',
                            next: '<i class="cs-chevron-right"></i>',
                        },
                    },
                    responsive: true,
                    lengthChange: false,
                    pageLength: 10,
                    sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
                });
            }
        });
    </script>
@endpush
@push('js_page')
{{--    <script src="{{ asset('js/cs/charts.extend.js') }}"></script>--}}
{{--    <script src="{{ asset('js/vendor/Chart.bundle.min.js') }}"></script>--}}
{{--    <script>--}}
{{--        $(document).ready(function () {--}}

{{--            if (document.getElementById('chartPengajuan')) {--}}
{{--                const chartPengajuan = document.getElementById('chartPengajuan');--}}
{{--                new Chart(chartPengajuan, {--}}
{{--                    type: 'bar',--}}
{{--                    data: {--}}
{{--                        labels: {!!  json_encode($dataChartPengajuan["labels"], JSON_THROW_ON_ERROR) !!},--}}
{{--                        datasets: [--}}
{{--                            {--}}
{{--                                label: "{{ array_keys($dataChartPengajuan["data"])[0]??"-" }}",--}}
{{--                                fill: true,--}}
{{--                                borderColor: [Globals.warning],--}}
{{--                                borderWidth: 2,--}}
{{--                                data: {!!  json_encode(collect($dataChartPengajuan["data"]["Jumlah"]), JSON_THROW_ON_ERROR) !!},--}}
{{--                            },--}}
{{--                        ],--}}
{{--                    },--}}

{{--                    options: {--}}
{{--                        plugins: {--}}
{{--                            datalabels: {display: false},--}}
{{--                        },--}}
{{--                        responsive: true,--}}
{{--                        maintainAspectRatio: false,--}}
{{--                        title: {--}}
{{--                            display: false,--}}
{{--                        },--}}
{{--                        layout: {--}}
{{--                            padding: {--}}
{{--                                bottom: 20,--}}
{{--                            },--}}
{{--                        },--}}
{{--                        scales: {--}}
{{--                            x: {--}}
{{--                                stacked: true,--}}
{{--                            },--}}
{{--                            y: {--}}
{{--                                stacked: true--}}
{{--                            }--}}
{{--                        },--}}
{{--                        legend: {--}}
{{--                            position: 'bottom',--}}
{{--                            labels: ChartsExtend.LegendLabels(),--}}
{{--                        },--}}
{{--                        tooltips: ChartsExtend.ChartTooltip(),--}}
{{--                    },--}}
{{--                });--}}
{{--            }--}}

{{--            if (document.getElementById('chartBansos')) {--}}
{{--                const chartBansos = document.getElementById('chartBansos');--}}
{{--                new Chart(chartBansos, {--}}
{{--                    type: 'bar',--}}
{{--                    data: {--}}
{{--                        labels: {!!  json_encode($dataChartBansos["labels"], JSON_THROW_ON_ERROR) !!},--}}
{{--                        datasets: [--}}
{{--                            {--}}
{{--                                label: "{{ array_keys($dataChartBansos["data"])[0]??"-" }}",--}}
{{--                                fill: true,--}}
{{--                                borderColor: [Globals.primary],--}}
{{--                                borderWidth: 2,--}}
{{--                                data: {!!  json_encode(collect($dataChartBansos["data"]["Rupiah"]), JSON_THROW_ON_ERROR) !!},--}}
{{--                            },--}}
{{--                        ],--}}
{{--                    },--}}

{{--                    options: {--}}
{{--                        plugins: {--}}
{{--                            datalabels: {display: false},--}}
{{--                        },--}}
{{--                        responsive: true,--}}
{{--                        maintainAspectRatio: false,--}}
{{--                        title: {--}}
{{--                            display: false,--}}
{{--                        },--}}
{{--                        layout: {--}}
{{--                            padding: {--}}
{{--                                bottom: 20,--}}
{{--                            },--}}
{{--                        },--}}
{{--                        scales: {--}}
{{--                            x: {--}}
{{--                                stacked: true,--}}
{{--                            },--}}
{{--                            y: {--}}
{{--                                stacked: true--}}
{{--                            }--}}
{{--                        },--}}
{{--                        legend: {--}}
{{--                            position: 'bottom',--}}
{{--                            labels: ChartsExtend.LegendLabels(),--}}
{{--                        },--}}
{{--                        tooltips: ChartsExtend.ChartTooltip(),--}}
{{--                    },--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}

@endpush
