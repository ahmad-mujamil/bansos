@extends('layouts.layout')
@section('title', 'OPD')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">OPD</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('opd.index') }}">OPD</a></li>
                            <li class="breadcrumb-item"><a
                                    href="javascript:;">{{ request()->routeIs('opd.create') ? 'Tambah Data' : 'Edit Data' }}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Back Button Start -->
                    <a href="{{ route('opd.index') }}"
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
                $route = request()->routeIs('opd.create') ? route('opd.store') : route('opd.update',$opd->id??'');
                $method = request()->routeIs('opd.create') ? 'POST' : 'PUT';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama OPD <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                   name="nama" required
                                   value="{{ old('nama', $opd->nama ?? '') }}"/>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Kepala OPD <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kepala_opd') is-invalid @enderror" id="kepala_opd"
                                   name="kepala_opd" required
                                   value="{{ old('kepala_opd', $opd->kepala_opd ?? '') }}"/>
                            @error('kepala_opd')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Alamat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                      name="alamat" required value="{{ old('alamat', $opd->alamat ?? '') }}"/>
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">No. Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                                   name="no_telp" required
                                   value="{{ old('no_telp', $opd->no_telp ?? '') }}"/>
                            @error('no_telp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Fax</label>
                            <input type="text" class="form-control @error('fax') is-invalid @enderror" id="fax"
                                   name="fax"
                                   value="{{ old('fax', $opd->fax ?? '') }}"/>
                            @error('fax')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                   name="email" required
                                   value="{{ old('email', $opd->email ?? '') }}"/>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Website</label>
                            <input type="text" class="form-control @error('website') is-invalid @enderror" id="website"
                                   name="website"
                                   placeholder="https://..."
                                   value="{{ old('website', $opd->website ?? '') }}"/>
                            @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

