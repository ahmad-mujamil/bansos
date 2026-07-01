@extends('layouts.layout')
@section('title', 'Tahun Anggaran')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Tahun Anggaran</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('tahun-anggaran.index') }}">Tahun Anggaran</a></li>
                            <li class="breadcrumb-item">
                                <a href="javascript:;">{{ request()->routeIs('tahun-anggaran.create') ? 'Tambah Data' : 'Edit Data' }}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('tahun-anggaran.index') }}"
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
                $route = request()->routeIs('tahun-anggaran.create')
                    ? route('tahun-anggaran.store')
                    : route('tahun-anggaran.update', $tahunAnggaran->id ?? '');
                $method = request()->routeIs('tahun-anggaran.create') ? 'POST' : 'PUT';
            @endphp
            <form novalidate action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Tahun</label>
                            <input
                                type="number"
                                class="form-control @error('tahun') is-invalid @enderror"
                                id="tahun"
                                name="tahun"
                                required
                                min="2000" max="2100"
                                value="{{ old('tahun', $tahunAnggaran->tahun ?? date('Y')) }}"
                            />
                        </div>
                        <div class="col-lg-4 col-md-8 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Label</label>
                            <input
                                type="text"
                                class="form-control @error('label') is-invalid @enderror"
                                id="label"
                                name="label"
                                placeholder="mis. TA {{ date('Y') }}"
                                value="{{ old('label', $tahunAnggaran->label ?? '') }}"
                            />
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Keterangan</label>
                            <input
                                type="text"
                                class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan"
                                name="keterangan"
                                value="{{ old('keterangan', $tahunAnggaran->keterangan ?? '') }}"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-8 col-sm-12 mb-2">
                            <div class="form-check">
                                <input type="hidden" name="is_terkunci" value="0">
                                <input class="form-check-input" type="checkbox" id="is_terkunci" name="is_terkunci" value="1"
                                    {{ old('is_terkunci', $tahunAnggaran->is_terkunci ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_terkunci">
                                    Kunci (read-only, data tidak bisa diubah)
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@include('components.form_validation')
