@extends('layouts.layout')
@section('title', 'BA Serah Terima')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">BA Serah Terima</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">BA Serah Terima</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('bast.index') }}">BAST</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ request()->routeIs('bast.create') ? 'Tambah Data' : 'Edit Data' }}</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('bast.index') }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
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
                $route  = request()->routeIs('bast.create') ? route('bast.store') : route('bast.update', $bast->id ?? '');
                $method = request()->routeIs('bast.create') ? 'POST' : 'PUT';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nomor BAST</label>
                            <input type="text" class="form-control @error('nomor') is-invalid @enderror" name="nomor"
                                   value="{{ old('nomor', $bast->nomor ?? '') }}" required />
                            @error('nomor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Tanggal</label>
                            <input type="text" id="tanggal" autocomplete="off"
                                   class="form-control @error('tanggal') is-invalid @enderror" name="tanggal"
                                   value="{{ old('tanggal', isset($bast) ? $bast->tanggal?->format('d-m-Y') : '') }}"
                                   placeholder="dd-mm-yyyy" required />
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Penerima</label>
                            <input type="text" class="form-control @error('penerima') is-invalid @enderror" name="penerima"
                                   value="{{ old('penerima', $bast->penerima ?? '') }}" required />
                            @error('penerima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Pengajuan</label>
                            <select name="pengajuan_id" id="pengajuan_id"
                                    class="form-control @error('pengajuan_id') is-invalid @enderror" required>
                                <option value="">Pilih Pengajuan</option>
                                @foreach($pengajuan as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('pengajuan_id', $bast->pengajuan_id ?? '') === $p->id ? 'selected' : '' }}>
                                        {{ $p->kode_pengajuan." - ".$p->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pengajuan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">
                                Dokumen PDF
                                @if(!request()->routeIs('bast.create'))
                                    <span class="text-muted">(kosongkan jika tidak ingin mengubah)</span>
                                @endif
                            </label>
                            <input type="file" class="form-control @error('dokumen') is-invalid @enderror" name="dokumen"
                                   accept=".pdf" {{ request()->routeIs('bast.create') ? 'required' : '' }} />
                            @error('dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(isset($bast) && $bast->getFirstMedia('dokumen'))
                                <div class="mt-2">
                                    <a href="{{ $bast->getFirstMediaUrl('dokumen') }}" target="_blank" class="text-primary text-small">
                                        <i data-acorn-icon="file-text"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">
                                Foto
                                @if(!request()->routeIs('bast.create'))
                                    <span class="text-muted">(upload baru akan menggantikan foto lama)</span>
                                @endif
                            </label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto[]"
                                   accept="image/*" multiple {{ request()->routeIs('bast.create') ? 'required' : '' }} />
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('foto.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @if(isset($bast) && $bast->getMedia('foto')->count())
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    @foreach($bast->getMedia('foto') as $foto)
                                        <img src="{{ $foto->getUrl() }}" alt="Foto" class="rounded" style="height: 80px; width: 80px; object-fit: cover;" />
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Page Content End -->
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-datepicker3.standalone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
@endpush
@push('js_vendor')
    <script src="{{ asset('js/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script>
        $("document").ready(function () {
            $('#tanggal').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                orientation: 'bottom',
            });
            $('#pengajuan_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Pengajuan',
                allowClear: true,
            });
        });
    </script>
@endpush
@include('components.form_validation')
