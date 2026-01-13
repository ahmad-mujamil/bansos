@extends('layouts.layout')
@section('title', 'Jadwal Berakhir')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h4>Oppps !!, <b> Terjadi Kesalahan</b></h4>
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <!-- Stats Start -->
        <div class="mb-5">
            <div class="row">
                <div class="col-sm-4 col-lg-4 col-md-4">
                    <img src="{{ asset('img/calendar.svg') }}" class="card-img card-img-horizontal-sm" alt="card image" />
                </div>
                <div class="col-12 col-md-8 col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column h-100">
                            <h5 class="card-title">Jadwal <b>{{$type}}</b> Sudah Berakhir !!</h5>

                            <a href="{{ route('home') }}" class="btn btn-primary mt-auto w-30">Kembali ke Beranda</a>
                            <p class="card-text mt-3">
                                <small class="text-muted
                                    d-flex align-items-center">
                                    <i class="bx bx-time
                                        me-1"></i>
                                    Server Time : {{ now()->translatedFormat("l, d F Y,  H:i") }}
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats End -->
    </div>
    <!-- Page Content End -->
@endsection
