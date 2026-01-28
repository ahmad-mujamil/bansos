@extends('layouts.layout')
@section('title', ($anggota ? 'Edit' : 'Tambah') . ' Anggota - ' . $organisasi->nama)
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">{{ $anggota ? 'Edit' : 'Tambah' }} Anggota</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelompok-masyarakat.index') }}">Kelompok Masyarakat</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelompok-masyarakat.anggota.index', $organisasi->id) }}">Anggota - {{ $organisasi->nama }}</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ $anggota ? 'Edit' : 'Tambah' }}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('kelompok-masyarakat.anggota.index', $organisasi->id) }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>

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
                $route = $anggota
                    ? route('kelompok-masyarakat.anggota.update', [$organisasi->id, $anggota->id])
                    : route('kelompok-masyarakat.anggota.store', $organisasi->id);
                $method = $anggota ? 'PUT' : 'POST';
            @endphp
            <form novalidate action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Penduduk / Anggota <span class="text-danger">*</span></label>
                            <select name="penduduk_id" id="penduduk_id" class="form-control @error('penduduk_id') is-invalid @enderror" required>
                                <option value="">Pilih Penduduk</option>
                                @foreach($penduduks as $p)
                                    <option value="{{ $p->id }}" {{ old('penduduk_id', optional($anggota)->penduduk_id ?? '') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} — {{ $p->nik }}
                                    </option>
                                @endforeach
                            </select>
                            @error('penduduk_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Jabatan <span class="text-danger">*</span></label>
                            <select name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach(\App\Enums\JabatanOrganisasi::cases() as $jabatanOption)
                                    <option value="{{ $jabatanOption->value }}" {{ old('jabatan', optional($anggota)->jabatan?->value ?? '') == $jabatanOption->value ? 'selected' : '' }}>
                                        {{ $jabatanOption->getDescription() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
@endpush
@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
@endpush
@push('js_page')
    <script>
        $(document).ready(function () {
            $('#penduduk_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Penduduk', allowClear: true });
            $('#jabatan').select2({ theme: 'bootstrap4', placeholder: 'Pilih Jabatan', allowClear: false });
        });
    </script>
@endpush
@include('components.form_validation')
