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
            $detail = $pengajuan?->first();
            $mediaPengajuan = $pengajuan?->getFirstMedia('pengajuan');
            $isEdit = (bool) $pengajuan;
            $route = $isEdit ? route('pengajuan.update', $pengajuan) : route('pengajuan.store');
            $method = $isEdit ? 'PUT' : 'POST';
            // $jenis = old('jenis', $pengajuan?->jenis?->value ?? request('jenis', ''));
            $isBansos = $jenis === \App\Enums\JenisPengajuan::BANSOS->value;
            $isBantuanKelompok = $jenis === \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK->value;
        @endphp

        <form action="{{ $route }}" method="POST" class="needs-validation" id="formPengajuan" enctype="multipart/form-data">
            @csrf
            @method($method)

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-12">
                            <label class="form-label text-small text-uppercase">Jenis <span class="text-danger">*</span></label>
                            <input type="hidden" name="jenis" value="{{ $jenis }}">
                            <select id="jenis" class="form-select @error('jenis') is-invalid @enderror" disabled>
                                <option value="">Pilih Jenis</option>
                                @foreach($jenisOptions as $opt)
                                    <option value="{{ $opt->value }}" {{ 'bantuan_kelompok' == $opt->value ? 'selected' : '' }}>
                                        {{ $opt->getDescription() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis')
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

                        <div class="col-md-6 col-sm-12" id="wrap-kelompok" style="{{ $isBansos ? 'display:none;' : '' }}">
                            

                            <label class="form-label text-small text-uppercase">Kelompok <span class="text-danger">*</span></label>
                            <select name="kelompok_id" id="kelompok_id" class="form-select @error('kelompok_id') is-invalid @enderror" disabled>
                                <option value="">Pilih Kelompok</option>
                                @foreach($kelompokList as $k)
                                    <option value="{{ $k->id }}" {{ old('kelompok_id', auth()->user()->userDetail?->organisasi_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                @endforeach
                            </select>
                            @error('kelompok_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <label class="form-label text-small text-uppercase">Judul Usulan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul" required
                                   value="{{ old('judul', $detail?->judul ?? '') }}" placeholder="Judul usulan bantuan" />
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <input type="hidden" name="opd_id" value="{{ auth()->user()->userDetail?->organisasi?->opd_id ?? '' }}">
                        <input type="hidden" name="organisasi_id" value="{{ auth()->user()->userDetail?->organisasi_id ?? '' }}">

                        <div class="col-md-12 col-sm-12">
                            <label class="form-label text-small text-uppercase">Lokasi Kegiatan</label>
                            <textarea class="form-control @error('lokasi') is-invalid @enderror" name="lokasi" rows="2">{{ old('lokasi', $detail?->lokasi ?? '') }}</textarea>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <label class="form-label text-small text-uppercase">
                                Nilai Usulan (Rp) <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('nilai') is-invalid @enderror" 
                                name="nilai" 
                                id="nilai"
                                min="0" 
                                step="0.01" 
                                required
                                value="{{ old('nilai', isset($detail) ? number_format($detail->nilai, 0, ',', '.') : '0') }}"
                                inputmode="numeric"
                                autocomplete="off"
                                />
                            @error('nilai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <label class="form-label text-small text-uppercase">File Pengajuan (PDF)</label>
                            <input type="file" class="form-control @error('file_pengajuan') is-invalid @enderror" name="file_pengajuan" accept="application/pdf">
                            <small class="text-muted">Format PDF, maksimal 5 MB.</small>
                            @if($mediaPengajuan)
                                <div class="mt-2">
                                    <a href="{{ $mediaPengajuan->getUrl() }}" target="_blank" class="text-primary fw-semibold">
                                        Lihat file saat ini: {{ $mediaPengajuan->file_name }}
                                    </a>
                                </div>
                            @endif
                            @error('file_pengajuan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const nilaiInput = document.getElementById('nilai');
                                nilaiInput.addEventListener('input', function(e) {
                                    let value = this.value.replace(/[^,\d]/g, '').toString();
                                    if (!value) {
                                        this.value = '';
                                        return;
                                    }
                                    let split = value.split(',');
                                    let sisa = split[0].length % 3;
                                    let rupiah = split[0].substr(0, sisa);
                                    let ribuan = split[0].substr(sisa).match(/\d{3}/g);
                                    if (ribuan) {
                                        rupiah += (sisa ? '.' : '') + ribuan.join('.');
                                    }
                                    this.value = rupiah + (split[1] !== undefined ? ',' + split[1] : '');
                                });

                                // On form submit, remove formatting so the value is numeric
                                nilaiInput.form && nilaiInput.form.addEventListener('submit', function() {
                                    let val = nilaiInput.value.replace(/\./g, '').replace(',', '.');
                                    nilaiInput.value = val;
                                });
                            });
                        </script>
                        
                    </div>

                    <div class="d-flex gap-2 mt-5">
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
            var BANTUAN_KELOMPOK = '{{ \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK->value }}';

            $('#jenis').select2({ theme: 'bootstrap4', placeholder: 'Pilih Jenis', allowClear: false });
            $('#jenis_bantuan_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Jenis Bantuan', allowClear: true });
            $('#kelompok_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Kelompok', allowClear: true });
            $('#penduduk_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Penduduk', allowClear: true });

            function toggleJenis() {
                var jenis = $('#jenis').val();
                var isBansos = (jenis === BANSOS);
                var isBantuanKelompok = (jenis === BANTUAN_KELOMPOK);

                $('#wrap-kelompok').toggle(!isBansos);
                $('#wrap-penduduk').toggle(isBansos);
                $('#wrap-jenis-bantuan').toggle(isBantuanKelompok);
                $('#jenis_bantuan_id').prop('required', isBantuanKelompok);
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
