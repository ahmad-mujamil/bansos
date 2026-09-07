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
                <div class="col-12 col-lg-4 col-xxl-4">
                    <div class="card stat-card stat-card-primary border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="stat-card-label">Total Penduduk</div>
                                <div class="stat-card-icon"><i data-acorn-icon="user" data-acorn-size="22"></i></div>
                            </div>
                            <div class="stat-card-value">{{ number_format($totalPenduduk) }}</div>
                            <div class="stat-card-deco"><i data-acorn-icon="user"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 col-xxl-4">
                    <div class="card stat-card stat-card-emerald border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="stat-card-label">Total Terverifikasi</div>
                                <div class="stat-card-icon"><i data-acorn-icon="check-square" data-acorn-size="22"></i></div>
                            </div>
                            <div class="stat-card-value">{{ number_format($totalPendudukTerverifikasi) }}</div>
                            <div class="stat-card-deco"><i data-acorn-icon="check-square"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 col-xxl-4">
                    <div class="card stat-card stat-card-amber border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="stat-card-label">Belum Terverifikasi</div>
                                <div class="stat-card-icon"><i data-acorn-icon="close-circle" data-acorn-size="22"></i></div>
                            </div>
                            <div class="stat-card-value">{{ number_format($totalPenduduk-$totalPendudukTerverifikasi) }}</div>
                            <div class="stat-card-deco"><i data-acorn-icon="close-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats End -->

        <!-- Verifikasi Saya Start -->
        <div class="mb-5">
            <h2 class="small-title">Verifikasi Saya</h2>
            <div class="row g-3">
                <div class="col-12 col-lg-4 col-xxl-4">
                    <a href="{{ route('verifikasi-penduduk.index', ['verifikator' => 'me']) }}" class="text-decoration-none">
                        <div class="card stat-card stat-card-primary border-0 shadow-sm h-100 overflow-hidden">
                            <div class="card-body p-4 position-relative">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="stat-card-label">Total Diverifikasi</div>
                                    <div class="stat-card-icon"><i data-acorn-icon="check" data-acorn-size="22"></i></div>
                                </div>
                                <div class="stat-card-value">{{ number_format($totalDiverifikasiSaya) }}</div>
                                <div class="stat-card-deco"><i data-acorn-icon="check"></i></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-lg-4 col-xxl-4">
                    <a href="{{ route('verifikasi-penduduk.index', ['status' => '1', 'verifikator' => 'me']) }}" class="text-decoration-none">
                        <div class="card stat-card stat-card-emerald border-0 shadow-sm h-100 overflow-hidden">
                            <div class="card-body p-4 position-relative">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="stat-card-label">Verifikasi Valid</div>
                                    <div class="stat-card-icon"><i data-acorn-icon="check-circle" data-acorn-size="22"></i></div>
                                </div>
                                <div class="stat-card-value">{{ number_format($totalValid) }}</div>
                                <div class="stat-card-deco"><i data-acorn-icon="check-circle"></i></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-lg-4 col-xxl-4">
                    <a href="{{ route('verifikasi-penduduk.index', ['status' => '2', 'verifikator' => 'me']) }}" class="text-decoration-none">
                        <div class="card stat-card stat-card-rose border-0 shadow-sm h-100 overflow-hidden">
                            <div class="card-body p-4 position-relative">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="stat-card-label">Verifikasi Tidak Valid</div>
                                    <div class="stat-card-icon"><i data-acorn-icon="error-hexagon" data-acorn-size="22"></i></div>
                                </div>
                                <div class="stat-card-value">{{ number_format($totalTidakValid) }}</div>
                                <div class="stat-card-deco"><i data-acorn-icon="error-hexagon"></i></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Verifikasi Saya End -->

    </div>
    <!-- Page Content End -->
@endsection
