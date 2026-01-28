@extends('layouts.layout')
@section('title', 'Kelompok Masyarakat')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Kelompok Masyarakat</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Organisasi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelompok-masyarakat.index') }}">Kelompok Masyarakat</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ !$organisasi ? 'Tambah Data' : 'Edit Data' }}</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('kelompok-masyarakat.index') }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
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
                $route = (!$organisasi) ? route('kelompok-masyarakat.store') : route('kelompok-masyarakat.update', $organisasi->id);
                $method = (!$organisasi) ? 'POST' : 'PUT';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                   name="nama" required value="{{ old('nama', optional($organisasi)->nama ?? '') }}"/>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nomor SK / Akta / Kemenkumham <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nomor') is-invalid @enderror" id="nomor"
                                   name="nomor" required value="{{ old('nomor', optional($organisasi)->nomor ?? '') }}"/>
                            @error('nomor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Jenis <span class="text-danger">*</span></label>
                            <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                                <option value="">Pilih Jenis</option>
                                @foreach(\App\Enums\JenisOrganisasi::cases() as $jenisOption)
                                    <option value="{{ $jenisOption->value }}" {{ old('jenis', optional($organisasi)->jenis ?? \App\Enums\JenisOrganisasi::KELOMPOK->value) == $jenisOption->value ? 'selected' : '' }}>
                                        {{ $jenisOption->getDescription() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Tanggal Pembentukan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tgl_pembentukan') is-invalid @enderror" id="tgl_pembentukan"
                                   name="tgl_pembentukan" required value="{{ old('tgl_pembentukan', isset($organisasi) ? $organisasi->tgl_pembentukan?->format('Y-m-d') : '') }}"/>
                            @error('tgl_pembentukan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Kecamatan <span class="text-danger">*</span></label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-control @error('kecamatan_id') is-invalid @enderror" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', optional($organisasi)->kecamatan_id ?? '') == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kecamatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Desa <span class="text-danger">*</span></label>
                            <select name="desa_id" id="desa_id" class="form-control @error('desa_id') is-invalid @enderror" required>
                                <option value="">Pilih Desa</option>
                                @if(isset($organisasi) && $organisasi->desa_id)
                                    @foreach(($organisasi->kecamatan->desa ?? collect()) as $desa)
                                        <option value="{{ $desa->id }}" {{ old('desa_id', $organisasi->desa_id) == $desa->id ? 'selected' : '' }}>
                                            {{ $desa->nama }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('desa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', optional($organisasi)->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
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
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
@endpush
@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
@endpush
@push('js_page')
    <script>
        $(document).ready(function () {
            @php
                $kecamatansData = $kecamatans->map(function($kecamatan) {
                    return [
                        'id' => $kecamatan->id,
                        'nama' => $kecamatan->nama,
                        'desa' => $kecamatan->desa->map(function($desa) {
                            return ['id' => $desa->id, 'nama' => $desa->nama];
                        })->values()->all()
                    ];
                })->values()->all();
            @endphp
            var kecamatansData = @json($kecamatansData);

            function loadDesaByKecamatan(kecamatanId, selectedDesaId = null) {
                var desaSelect = $('#desa_id');
                if (desaSelect.data('select2')) {
                    desaSelect.select2('destroy');
                }
                desaSelect.empty();
                desaSelect.append('<option value="">Pilih Desa</option>');
                if (kecamatanId) {
                    var selectedKecamatan = kecamatansData.find(function(k) { return k.id === kecamatanId; });
                    if (selectedKecamatan && selectedKecamatan.desa) {
                        selectedKecamatan.desa.forEach(function(desa) {
                            var sel = selectedDesaId && selectedDesaId === desa.id ? 'selected' : '';
                            desaSelect.append('<option value="' + desa.id + '" ' + sel + '>' + desa.nama + '</option>');
                        });
                    }
                }
                desaSelect.select2({ theme: 'bootstrap4', placeholder: 'Pilih Desa', allowClear: true });
            }

            $('#jenis').select2({ theme: 'bootstrap4', placeholder: 'Pilih Jenis', allowClear: false });
            $('#kecamatan_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Kecamatan', allowClear: true });
            $('#desa_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Desa', allowClear: true });

            var initialKecamatanId = $('#kecamatan_id').val();
            var initialDesaId = $('#desa_id').val();
            if (initialKecamatanId) {
                loadDesaByKecamatan(initialKecamatanId, initialDesaId);
            }

            $('#kecamatan_id').on('change', function() {
                var kecamatanId = $(this).val();
                if (kecamatanId) {
                    loadDesaByKecamatan(kecamatanId);
                } else {
                    var desaSelect = $('#desa_id');
                    if (desaSelect.data('select2')) desaSelect.select2('destroy');
                    desaSelect.empty();
                    desaSelect.append('<option value="">Pilih Desa</option>');
                    desaSelect.select2({ theme: 'bootstrap4', placeholder: 'Pilih Desa', allowClear: true });
                }
            });
        });
    </script>
@endpush
@include('components.form_validation')
