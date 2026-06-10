@extends('layouts.layout')
@section('title', 'Monitoring Bantuan')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Monitoring Bantuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Monitoring &amp; Realisasi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('monitoring-bantuan.index') }}">Monitoring Bantuan</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <livewire:monitoring-bantuan-list />
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}" />
@endpush

@push('js_vendor')
    <script src="{{ asset('js/vendor/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@endpush
