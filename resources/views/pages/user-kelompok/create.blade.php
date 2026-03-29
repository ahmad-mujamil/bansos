@extends('layouts.layout')
@section('title', 'Pengguna')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Pengguna</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}">Pengguna</a></li>
                            <li class="breadcrumb-item"><a
                                    href="javascript:;">{{ request()->routeIs('pengguna.create') ? 'Tambah Data' : 'Edit Data' }}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Back Button Start -->
                    <a href="{{ route('pengguna.index') }}"
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
                $route = request()->routeIs('pengguna.create') ? route('pengguna.store') : route('pengguna.update',$pengguna->id??'');
                $method = request()->routeIs('pengguna.create') ? 'POST' : 'PUT';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama Pengguna</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                   name="nama" required
                                   value="{{ old('nama',$pengguna->nama??'') }}"/>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                   name="email" required
                                   value="{{ old('email',$pengguna->email??'') }}"/>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Role</label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                                <option value="">Pilih Role</option>
                                @foreach(\App\Enums\RoleUser::cases() as $item)
                                    <option
                                        value="{{ $item->value }}" {{ (old('role',$pengguna->role?->value??'') == $item->value) ? 'selected' : '' }}>
                                        {{ $item->getDescription() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                   id="username" name="username" required
                                   value="{{ old('username',$pengguna->username??'') }}"/>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" {{ request()->routeIs('pengguna.create') ? 'required' : '' }}
                                   />
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Status</label>
                            <select name="is_active" id="is_active"
                                    class="form-control @error('is_active') is-invalid @enderror">
                                <option value="">Pilih Status</option>
                                <option
                                    value="1" {{ (old('is_active',$pengguna->is_active??'') == 1) ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option
                                    value="0" {{ (old('is_active',$pengguna->is_active??'') == 0) ? 'selected' : '' }}>
                                    Non Active
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">OPD</label>
                            <select name="opd_id" id="opd_id" class="form-control @error('opd_id') is-invalid @enderror">
                                <option value="">Pilih OPD</option>
                                @foreach($opds ?? [] as $opd)
                                    <option
                                        value="{{ $opd->id }}" {{ (old('opd_id',$pengguna->opd_id??'') == $opd->id) ? 'selected' : '' }}>
                                        {{ $opd->nama }}
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
            $('#role').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Role',
                allowClear: true,
            });
            $('#is_active').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Status',
                allowClear: true,
            });
            $('#opd_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih OPD',
                allowClear: true,
            });
        });
    </script>
@endpush
@include('components.form_validation')

