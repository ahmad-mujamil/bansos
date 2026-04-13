@extends('layouts.layout')
@section('title', 'User Kelompok')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">User Kelompok</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user-kelompok.index') }}">User Kelompok</a></li>
                            <li class="breadcrumb-item"><a
                                    href="javascript:;">{{ request()->routeIs('user-kelompok.create') ? 'Tambah Data' : 'Edit Data' }}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Back Button Start -->
                    <a href="{{ route('user-kelompok.index') }}"
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
                $route = request()->routeIs('user-kelompok.create') ? route('user-kelompok.store') : route('user-kelompok.update',$userKelompok->id??'');
                $method = request()->routeIs('user-kelompok.create') ? 'POST' : 'PUT';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">NIK</label>
                            <input type="text" class="form-control number-only @error('nik') is-invalid @enderror" name="nik" maxlength="30"
                                   value="{{ old('nik', $userKelompok->userDetail?->nik ?? '') }}"/>

                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama Pengguna</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                   name="nama" required
                                   value="{{ old('nama',$userKelompok->nama??'') }}"/>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">No. Telepon</label>
                            <input type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" maxlength="15"
                                   value="{{ old('phone', $userKelompok->userDetail?->phone ?? '') }}"/>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                   name="alamat" required
                                   value="{{ old('alamat',$userKelompok->userDetail?->alamat??'') }}"/>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Organisasi/Kelompok</label>
                            <select name="organisasi_id" id="organisasi_id"
                                    class="form-control @error('organisasi_id') is-invalid @enderror">
                                <option value="">Pilih Organisasi</option>
                                @foreach($organisasi as $org)
                                    <option value="{{ $org->id }}" {{ (old('organisasi_id', $userKelompok->userDetail->organisasi_id ?? '') === $org->id) ? 'selected' : '' }}>
                                        {{ $org->nama }}
                                    </option>

                                @endforeach
                            </select>
                        </div>
                    </div>
                    @livewire('wilayah-select', ['kecamatan' => old('kecamatan_id', $userKelompok->userDetail?->kecamatan_id ?? ''), 'desa' => old('desa_id', $userKelompok->userDetail?->desa_id ?? '')])
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                   id="username" name="username" required
                                   value="{{ old('username',$userKelompok->username??'') }}"/>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control password-input @error('password') is-invalid @enderror"
                                       id="password" name="password" {{ request()->routeIs('user-kelompok.create') ? 'required' : '' }}
                                       />
                                <button class="btn position-absolute end-0 top-0 h-100 px-3 password-addon" type="button">
                                    <i data-acorn-icon="eye-off" class="icon-eye-off text-primary"></i>
                                    <i data-acorn-icon="eye" class="icon-eye d-none text-primary"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                   name="email" required
                                   value="{{ old('email',$userKelompok->email??'') }}"/>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Status</label>
                            <select name="is_active" id="is_active"
                                    class="form-control @error('is_active') is-invalid @enderror">
                                <option value="">Pilih Status</option>
                                <option value="1" {{ (old('is_active',$userKelompok->is_active??'') === 1) ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0" {{ (old('is_active',$userKelompok->is_active??'') === 0) ? 'selected' : '' }}>
                                    Non Active
                                </option>
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
@push('js_vendor')
    <script>
        $("document").ready(function () {
            $('#is_active').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Status',
                allowClear: true,
            });
            $('#organisasi_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Status',
                allowClear: true,
            });
        });
    </script>
@endpush
@include('components.form_validation')
@include('components.number-format')

