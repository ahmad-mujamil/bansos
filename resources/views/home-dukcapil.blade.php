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
                <div class="col-12 col-lg-4 col-xxl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="heading mb-0 d-flex justify-content-between lh-1-25 mb-3">
                                <span>Total <b>Penduduk</b></span>
                                <i data-acorn-icon="user" class="text-primary"></i>
                            </div>
                            {{--                            <div class="text-small text-success mb-1">--}}
                            {{--                                <i data-acorn-icon="arrow-top" class="me-1" data-acorn-size="13"></i>--}}
                            {{--                                <span class="text-medium">+55.2%</span>--}}
                            {{--                            </div>--}}
                            <div class="cta-1 text-primary">{{ number_format($totalPenduduk) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 col-xxl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="heading mb-0 d-flex justify-content-between lh-1-25 mb-3">
                                <span>Total <b>Terverifikasi</b></span>
                                <i data-acorn-icon="check-square" class="text-primary"></i>
                            </div>
                            {{--                            <div class="text-small text-success mb-1">--}}
                            {{--                                <i data-acorn-icon="arrow-top" class="me-1" data-acorn-size="13"></i>--}}
                            {{--                                <span class="text-medium">+55.2%</span>--}}
                            {{--                            </div>--}}
                            <div class="cta-1 text-primary">{{ number_format($totalPendudukTerverifikasi) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 col-xxl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="heading mb-0 d-flex justify-content-between lh-1-25 mb-3">
                                <span>Belum <b>Terverifikasi</b></span>
                                <i data-acorn-icon="close-circle" class="text-primary"></i>
                            </div>
                            {{--                            <div class="text-small text-success mb-1">--}}
                            {{--                                <i data-acorn-icon="arrow-top" class="me-1" data-acorn-size="13"></i>--}}
                            {{--                                <span class="text-medium">+55.2%</span>--}}
                            {{--                            </div>--}}
                            <div class="cta-1 text-primary">{{ number_format($totalPenduduk-$totalPendudukTerverifikasi) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats End -->

    </div>
    <!-- Page Content End -->
@endsection
