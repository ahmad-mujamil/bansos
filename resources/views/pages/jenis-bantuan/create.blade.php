@extends('layouts.layout')
@section('title', 'Jenis Bantuan')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Jenis Bantuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('jenis-bantuan.index') }}">Jenis Bantuan</a></li>
                            <li class="breadcrumb-item"><a
                                    href="javascript:;">{{ request()->routeIs('jenis-bantuan.create') ? 'Tambah Data' : 'Edit Data' }}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Back Button Start -->
                    <a href="{{ route('jenis-bantuan.index') }}"
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
                $route = request()->routeIs('jenis-bantuan.create') ? route('jenis-bantuan.store') : route('jenis-bantuan.update',$jenisBantuan->id??'');
                $method = request()->routeIs('jenis-bantuan.create') ? 'POST' : 'PUT';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Kategori</label>
                            <select name="kategori" id="kategori" class="form-control form-select @error('keterangan') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach(\App\Enums\KategoriBantuan::cases() as $item)
                                    <option value="{{ $item->value }}" {{ old('kategori',$jenisBantuan->kategori??'') === $item->value ? 'selected' : '' }}>{{ $item->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama Jenis</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                   name="nama" required
                                   value="{{ old('nama',$jenisBantuan->nama??'') }}"/>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Keterangan</label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                   name="keterangan" required
                                   value="{{ old('keterangan',$jenisBantuan->keterangan??'') }}"/>
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
@include('components.form_validation')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}" />
@endpush
@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#kategori").select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Kategori',
                allowClear: true
            });
        });
    </script>
@endpush

