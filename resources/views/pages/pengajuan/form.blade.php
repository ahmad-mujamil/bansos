@extends('layouts.layout')
@section('title', $pengajuan ? 'Edit Pengajuan' : 'Tambah Pengajuan')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">{{ $pengajuan ? 'Edit Pengajuan' : 'Tambah Pengajuan' }}</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pengajuan.index') }}">Pengajuan</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ $pengajuan ? 'Edit' : 'Tambah' }}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $detail = $pengajuan?->details->first();
            $isEdit = (bool) $pengajuan;
            $route = $isEdit ? route('pengajuan.update', $pengajuan) : route('pengajuan.store');
            $method = $isEdit ? 'PUT' : 'POST';
            $jenis = old('jenis', $pengajuan?->jenis?->value ?? request('jenis', ''));
            $isBansos = $jenis === \App\Enums\JenisPengajuan::BANSOS->value;
        @endphp

        <form action="{{ $route }}" method="POST" class="needs-validation" id="formPengajuan">
            @csrf
            @method($method)

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-12">
                            <label class="form-label text-small text-uppercase">Jenis <span class="text-danger">*</span></label>
                            <select name="jenis" id="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                <option value="">Pilih Jenis</option>
                                @foreach($jenisOptions as $opt)
                                    <option value="{{ $opt->value }}" {{ $jenis == $opt->value ? 'selected' : '' }}>
                                        {{ $opt->getDescription() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-12" id="wrap-kelompok" style="{{ $isBansos ? 'display:none;' : '' }}">
                            <label class="form-label text-small text-uppercase">Kelompok <span class="text-danger">*</span></label>
                            <select name="kelompok_id" id="kelompok_id" class="form-select @error('kelompok_id') is-invalid @enderror">
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompokList as $k)
                                    <option value="{{ $k->id }}" {{ old('kelompok_id', $detail?->kelompok_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                @endforeach
                            </select>
                            @error('kelompok_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-12" id="wrap-penduduk" style="{{ !$isBansos ? 'display:none;' : '' }}">
                            <label class="form-label text-small text-uppercase">Penduduk <span class="text-danger">*</span></label>
                            <select name="penduduk_id" id="penduduk_id" class="form-select @error('penduduk_id') is-invalid @enderror">
                                <option value="">Pilih Penduduk</option>
                                @foreach($pendudukList as $p)
                                    <option value="{{ $p->id }}" {{ old('penduduk_id', $detail?->penduduk_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->nik }})</option>
                                @endforeach
                            </select>
                            @error('penduduk_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label text-small text-uppercase">Judul Usulan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul_usulan') is-invalid @enderror" name="judul_usulan" required
                                   value="{{ old('judul_usulan', $detail?->judul_usulan ?? '') }}" placeholder="Judul usulan bantuan" />
                            @error('judul_usulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-small text-uppercase">Latar Belakang</label>
                            <textarea class="form-control @error('latar_belakang') is-invalid @enderror" name="latar_belakang" rows="3">{{ old('latar_belakang', $detail?->latar_belakang ?? '') }}</textarea>
                            @error('latar_belakang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-small text-uppercase">Tujuan</label>
                            <textarea class="form-control @error('tujuan') is-invalid @enderror" name="tujuan" rows="3">{{ old('tujuan', $detail?->tujuan ?? '') }}</textarea>
                            @error('tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <label class="form-label text-small text-uppercase">Lokasi Kegiatan</label>
                            <input type="text" class="form-control @error('lokasi_kegiatan') is-invalid @enderror" name="lokasi_kegiatan"
                                   value="{{ old('lokasi_kegiatan', $detail?->lokasi_kegiatan ?? '') }}" />
                            @error('lokasi_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="form-label text-small text-uppercase">Nilai Usulan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('nilai_usulan') is-invalid @enderror" name="nilai_usulan" min="0" step="0.01" required
                                   value="{{ old('nilai_usulan', $detail?->nilai_usulan ?? '0') }}" />
                            @error('nilai_usulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="form-label text-small text-uppercase">Tanggal Pengajuan</label>
                            <input type="date" class="form-control @error('tanggal_pengajuan') is-invalid @enderror" name="tanggal_pengajuan"
                                   value="{{ old('tanggal_pengajuan', $detail?->tanggal_pengajuan?->format('Y-m-d') ?? date('Y-m-d')) }}" />
                            @error('tanggal_pengajuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Page Content End -->
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
@endpush

@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@endpush

@push('js_page')
    <script>
        $(function () {
            var BANSOS = '{{ \App\Enums\JenisPengajuan::BANSOS->value }}';

            $('#jenis').select2({ theme: 'bootstrap4', placeholder: 'Pilih Jenis', allowClear: false });
            $('#kelompok_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Kelompok', allowClear: true });
            $('#penduduk_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Penduduk', allowClear: true });

            function toggleJenis() {
                var jenis = $('#jenis').val();
                var isBansos = (jenis === BANSOS);
                $('#wrap-kelompok').toggle(!isBansos);
                $('#wrap-penduduk').toggle(isBansos);
                $('#kelompok_id').prop('required', !isBansos);
                $('#penduduk_id').prop('required', isBansos);
            }

            $('#jenis').on('change', toggleJenis);
            toggleJenis();

            $('#formPengajuan').on('submit', function (e) {
                e.preventDefault();
                var form = this;
                Swal.fire({
                    title: '{{ $pengajuan ? "Perbarui Pengajuan" : "Simpan Pengajuan" }}',
                    text: 'Apakah Anda yakin ingin {{ $pengajuan ? "memperbarui" : "menyimpan" }} pengajuan ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, {{ $pengajuan ? "perbarui" : "simpan" }}',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endpush
@include('components.form_validation')
