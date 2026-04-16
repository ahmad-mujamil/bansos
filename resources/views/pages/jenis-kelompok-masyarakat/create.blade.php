@extends('layouts.layout')
@section('title', 'Jenis Kelompok Masyarakat')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Jenis Kelompok Masyarakat</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('jenis-kelompok-masyarakat.index') }}">Jenis Kelompok Masyarakat</a></li>
                            <li class="breadcrumb-item">
                                <a href="javascript:;">{{ request()->routeIs('jenis-kelompok-masyarakat.create') ? 'Tambah Data' : 'Edit Data' }}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('jenis-kelompok-masyarakat.index') }}"
                       class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
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
                $route = request()->routeIs('jenis-kelompok-masyarakat.create')
                    ? route('jenis-kelompok-masyarakat.store')
                    : route('jenis-kelompok-masyarakat.update', $jenisKelompokMasyarakat->id ?? '');
                $method = request()->routeIs('jenis-kelompok-masyarakat.create') ? 'POST' : 'PUT';
            @endphp
            <form novalidate action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama Jenis</label>
                            <input
                                type="text"
                                class="form-control @error('nama') is-invalid @enderror"
                                id="nama"
                                name="nama"
                                required
                                value="{{ old('nama', $jenisKelompokMasyarakat->nama ?? '') }}"
                            />
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Keterangan</label>
                            <input
                                type="text"
                                class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan"
                                name="keterangan"
                                value="{{ old('keterangan', $jenisKelompokMasyarakat->keterangan ?? '') }}"
                            />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@include('components.form_validation')
