@extends('layouts.layout')
@section('title', 'Penduduk')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Penduduk</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('penduduk.index') }}">Penduduk</a></li>
                            <li class="breadcrumb-item"><a
                                    href="javascript:;">{{ request()->routeIs('penduduk.create') ? 'Tambah Data' : 'Edit Data' }}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Back Button Start -->
                    <a href="{{ route('penduduk.index') }}"
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
                $route = request()->routeIs('penduduk.create') ? route('penduduk.store') : route('penduduk.update',$penduduk->id??'');
                $method = request()->routeIs('penduduk.create') ? 'POST' : 'PUT';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">NIK</label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik"
                                   name="nik" required
                                   value="{{ old('nik',$penduduk->nik??'') }}"/>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">No. KK</label>
                            <input type="text" class="form-control @error('no_kk') is-invalid @enderror" id="no_kk"
                                   name="no_kk" required
                                   value="{{ old('no_kk',$penduduk->no_kk??'') }}"/>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                   name="nama" required
                                   value="{{ old('nama',$penduduk->nama??'') }}"/>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                      name="alamat" required>{{ old('alamat',$penduduk->alamat??'') }}</textarea>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Tempat Lahir</label>
                            <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir"
                                   name="tempat_lahir" required
                                   value="{{ old('tempat_lahir',$penduduk->tempat_lahir??'') }}"/>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Tanggal Lahir</label>
                            <input type="text" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir"
                                   name="tgl_lahir" required readonly
                                   value="{{ old('tgl_lahir',isset($penduduk->tgl_lahir) ? $penduduk->tgl_lahir->format('Y-m-d') : '') }}"/>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Jenis Kelamin</label>
                            <select class="form-control select2-penduduk @error('jk') is-invalid @enderror" id="jk" name="jk" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                @foreach($jenis_kelamin as $jk)
                                    <option value="{{ $jk->value }}" {{ old('jk', $penduduk->jk->value ?? '') === $jk->value ? 'selected' : '' }}>{{ $jk->getDescription() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Agama</label>
                            <select class="form-control select2-penduduk @error('agama') is-invalid @enderror" id="agama" name="agama" required>
                                <option value="">Pilih Agama</option>
                                @php
                                    $agama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Khonghucu'];
                                @endphp
                                @foreach($agama as $item)
                                    <option value="{{ $item }}" {{ old('agama', $penduduk->agama ?? '') === $item ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Status Perkawinan</label>
                            <select class="form-control select2-penduduk @error('status_perkawinan') is-invalid @enderror" id="status_perkawinan" name="status_perkawinan" required>
                                <option value="">Pilih Status</option>
                                @foreach($status_perkawinan as $status)
                                    <option value="{{ $status->value }}" {{ old('status_perkawinan', $penduduk->status_perkawinan->value ?? '') === $status->value ? 'selected' : '' }}>{{ $status->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Pekerjaan</label>
                            <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror" id="pekerjaan"
                                   name="pekerjaan" required
                                   value="{{ old('pekerjaan',$penduduk->pekerjaan??'') }}"/>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Pendidikan</label>
                            <input type="text" class="form-control @error('pendidikan') is-invalid @enderror" id="pendidikan"
                                   name="pendidikan" required
                                   value="{{ old('pendidikan',$penduduk->pendidikan??'') }}"/>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">RT/RW</label>
                            <input type="text" class="form-control @error('rt_rw') is-invalid @enderror" id="rt_rw"
                                   name="rt_rw" required
                                   value="{{ old('rt_rw',$penduduk->rt_rw??'') }}"/>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                            @livewire('wilayah-select', [
                                'kecamatan' => old('kecamatan_id', $penduduk->kecamatan_id ?? null),
                                'desa' => old('desa_id', $penduduk->desa_id ?? null)
                            ])
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Level Desil</label>
                            <select class="form-control select2-penduduk @error('level_desil') is-invalid @enderror" id="level_desil" name="level_desil" required>
                                <option value="">Pilih Level Desil</option>
                                @foreach($level_desil as $desil)
                                    <option value="{{ $desil->value }}" {{ old('level_desil', $penduduk->level_desil->value ?? '') === $desil->value ? 'selected' : '' }}>{{ $desil->value }} - {{ $desil->getDescription() }}</option>
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
@include('components.form_validation')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-datepicker3.standalone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
@endpush
@push('js_vendor')
    <script src="{{ asset('js/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
@endpush

@push('js_page')
    <script>
        $(document).ready(function () {
            $('#tgl_lahir').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                orientation: 'bottom',
            });

            $('.select2-penduduk').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@endpush
