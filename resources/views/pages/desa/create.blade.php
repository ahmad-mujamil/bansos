@extends('layouts.layout')
@section('title', 'Desa')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Desa</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Wilayah Administrasi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('desa.index') }}">Desa</a></li>
                            <li class="breadcrumb-item"><a
                                    href="javascript:;">{{ request()->routeIs('desa.create') ? 'Tambah Data' : 'Edit Data' }}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Back Button Start -->
                    <a href="{{ route('desa.index') }}"
                       class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <!-- Back Button End -->
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-5">
            @php
                $route = request()->routeIs('desa.create') ? route('desa.store') : route('desa.update',$desa->id??'');
                $method = request()->routeIs('desa.create') ? 'POST' : 'PUT';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama Desa</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                   name="nama" required
                                   value="{{ old('nama',$desa->nama??'') }}"/>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Kecamatan</label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-control @error('kecamatan_id') is-invalid @enderror">
                                <option value="">Pilih Kecamatan</option>
                                @foreach($kecamatans as $kecamatan)
                                    <option
                                        value="{{ $kecamatan->id }}" {{ (old('kecamatan_id',$desa->kecamatan_id??'') == $kecamatan->id) ? 'selected' : '' }}>
                                        {{ $kecamatan->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan Data</button>
                </div>
            </form>
        </div>
        <!-- Content Start -->
    </div>
    <!-- Page Content End -->
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
@endpush
@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script>
        $("document").ready(function () {
            $('#kecamatan_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Kecamatan',
                allowClear: true,
            });
        });
    </script>
@endpush
@include('components.form_validation')

