@extends('layouts.layout')
@section('title', $judul)
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">{{ $judul }}</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Organisasi Teregistrasi</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-icon btn-icon-start">
                        <i data-acorn-icon="chevron-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <livewire:reports.organisasi-teregistrasi-list :jenis="$jenis" />
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}" />
    <style>
        /* Select2 selaras dengan form-select-sm */
        .laporan-pengajuan-list .select2-container--bootstrap4 .select2-selection--single {
            height: 31px;
            font-size: 0.875rem;
        }
        .laporan-pengajuan-list .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 29px;
            padding-top: 0;
            padding-bottom: 0;
        }
    </style>
@endpush

@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
@endpush
