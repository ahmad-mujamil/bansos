@extends('layouts.layout')
@section('title', $pengajuan ? 'Edit Pengajuan' : 'Tambah Pengajuan')
@section('content')
<!-- Page Content Start -->
<div class="col">
    <div class="page-title-container mb-3">
        <div class="row">
            <div class="col mb-2">
                <h1 class="mb-2 pb-0 display-4">
                    {{ $pengajuan ? 'Edit Pengajuan' : 'Tambah Pengajuan ' }}
                    @unless ($pengajuan)
                    <strong>{{ \Illuminate\Support\Str::of($jenis)->replace('_', ' ')->title() }}</strong>
                    @endunless
                </h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengajuan-opd.index') }}">Pengajuan OPD</a></li>
                        <li class="breadcrumb-item"><a href="javascript:;">{{ $pengajuan ? 'Edit' : 'Tambah' }}</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                <a href="{{ route('pengajuan-opd.index') }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
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
    $detail = $pengajuan;
    $mediaPengajuan = $pengajuan?->getFirstMedia('pengajuan');
    $isEdit = (bool) $pengajuan;
    $route = $isEdit ? route('pengajuan-opd.update', $pengajuan) : route('pengajuan-opd.store');
    $method = $isEdit ? 'PUT' : 'POST';
    // $jenis = old('jenis', $pengajuan?->jenis?->value ?? request('jenis', ''));
    $isBansos = $jenis === \App\Enums\JenisPengajuan::BANSOS->value;
    $isBantuanKelompok = $jenis === \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK->value;
    $pendudukIsValidMap = $pendudukIsValidMap ?? [];
    $simpanDiblokir = $simpanDiblokir ?? false;
    $kelompokSimpanDiblokir = $kelompokSimpanDiblokir ?? false;
    $anggotaBelumTerverifikasi = $anggotaBelumTerverifikasi ?? collect();
    @endphp

    <form action="{{ $route }}" method="POST" class="needs-validation" id="formPengajuan" enctype="multipart/form-data">
        @csrf
        @method($method)

        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <input type="hidden" name="jenis" value="{{ $jenis }}">

                    <div class="col-md-6 col-sm-12" id="wrap-penduduk" style="{{ !$isBansos ? 'display:none;' : '' }}">
                        <label class="form-label text-small text-uppercase">Penduduk <span class="text-danger">*</span></label>
                        <select name="penduduk_id" id="penduduk_id" class="form-select @error('penduduk_id') is-invalid @enderror">
                            <option value="">Pilih Penduduk</option>
                            @foreach($pendudukList as $p)
                            <option value="{{ $p->id }}" {{ old('penduduk_id', $selectedPendudukId ?? null) == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->nik }})</option>
                            @endforeach
                        </select>
                        @error('penduduk_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 col-sm-12" id="wrap-kelompok" style="{{ $isBansos ? 'display:none;' : '' }}">


                        <label class="form-label text-small text-uppercase">Kelompok <span class="text-danger">*</span></label>
                        <select name="organisasi_id" id="organisasi_id" class="form-select @error('organisasi_id') is-invalid @enderror" >
                            <option value="">Pilih Kelompok</option>
                            @foreach($kelompokList as $k)
                            <option value="{{ $k->id }}" {{ old('organisasi_id', $pengajuan?->organisasi_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('organisasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($isBantuanKelompok && $anggotaBelumTerverifikasi->isNotEmpty())
                    <div class="col-12">
                        <div class="alert alert-warning border-0 shadow-sm mb-0">
                            <div class="fw-semibold mb-2">
                                <i data-acorn-icon="user" data-acorn-size="18" class="me-1 align-middle"></i>
                                Anggota kelompok yang data penduduknya belum terverifikasi (is_valid)
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered bg-white mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama</th>
                                            <th>NIK</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($anggotaBelumTerverifikasi as $row)
                                        <tr>
                                            <td>{{ $row['nama'] }}</td>
                                            <td>{{ $row['nik'] }}</td>
                                            <td>
                                                @if($row['status'] === 'Tidak Valid')
                                                    <span class="badge bg-danger">Tidak Valid</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Belum Diverifikasi</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-muted text-small mb-0 mt-2">Selesaikan verifikasi data penduduk anggota di halaman administrasi penduduk/kelompok sebelum menyimpan pengajuan.</p>
                        </div>
                    </div>
                    @endif

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="form-label text-small text-uppercase">Judul Usulan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul" required
                            value="{{ old('judul', $detail?->judul ?? '') }}" placeholder="Judul usulan bantuan" />
                        @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label text-small text-uppercase">Kecamatan</label>
                        <select name="kecamatan_id" id="kecamatan_id" class="form-select @error('kecamatan_id') is-invalid @enderror">
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $pengajuan?->desa?->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
                                {{ $kecamatan->nama }}
                            </option>
                            @endforeach
                        </select>
                        @error('kecamatan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label class="form-label text-small text-uppercase">Desa</label>
                        <select name="desa_id" id="desa_id" class="form-select @error('desa_id') is-invalid @enderror">
                            <option value="">Pilih Desa</option>
                            @if($pengajuan?->desa_id)
                            @foreach(($pengajuan->desa->kecamatan->desa ?? collect()) as $desa)
                            <option value="{{ $desa->id }}" {{ old('desa_id', $pengajuan->desa_id) == $desa->id ? 'selected' : '' }}>
                                {{ $desa->nama }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                        @error('desa_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <label class="form-label text-small text-uppercase">Lokasi Detail Usulan (opsional)</label>
                        <textarea class="form-control @error('lokasi') is-invalid @enderror" name="lokasi" rows="2">{{ old('lokasi', $detail->lokasi ?? '') }}</textarea>
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
                            autocomplete="off" />
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
                            <a href="{{ $mediaPengajuan->getUrl() }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary">
                                <i data-acorn-icon="eye"></i> Lihat file saat ini
                            </a>
                            <div class="text-muted text-small mt-1 mb-0">{{ $mediaPengajuan->file_name }}</div>
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

                <div class="d-flex flex-wrap align-items-center gap-2 mt-5">
                    <button type="submit" id="btnSimpanPengajuan" class="btn btn-primary" @if($simpanDiblokir) disabled aria-disabled="true" @endif>Simpan</button>
                    <a href="{{ route('pengajuan-opd.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
                @if($simpanDiblokir)
                <p id="hintSimpanDiblokir" class="text-muted text-small mt-2 mb-0">
                    @if($isBansos)
                        Penduduk yang dipilih belum terverifikasi (kolom is_valid). Selesaikan verifikasi data penduduk terlebih dahulu.
                    @else
                        Masih ada anggota kelompok yang data penduduknya belum diverifikasi. Selesaikan verifikasi seluruh anggota terlebih dahulu.
                    @endif
                </p>
                @else
                <p id="hintSimpanDiblokir" class="text-muted text-small mt-2 mb-0 d-none"></p>
                @endif
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
    $(function() {
        var BANSOS = '{{ \App\Enums\JenisPengajuan::BANSOS->value }}';
        var BANTUAN_KELOMPOK = '{{ \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK->value }}';
        var jenisPengajuan = '{{ $jenis }}';
        var pendudukIsValidMap = @json($pendudukIsValidMap);
        var kelompokSimpanDiblokir = {{ $kelompokSimpanDiblokir ? 'true' : 'false' }};

        function updateSimpanBlokir() {
            var blokir = false;
            var hint = '';
            if (jenisPengajuan === BANSOS) {
                var pid = $('#penduduk_id').val();
                if (pid && pendudukIsValidMap[pid] !== true) {
                    blokir = true;
                    hint = 'Penduduk yang dipilih belum terverifikasi (kolom is_valid). Selesaikan verifikasi data penduduk terlebih dahulu.';
                }
            } else if (kelompokSimpanDiblokir) {
                blokir = true;
                hint = 'Masih ada anggota kelompok yang data penduduknya belum diverifikasi. Selesaikan verifikasi seluruh anggota terlebih dahulu.';
            }
            $('#btnSimpanPengajuan').prop('disabled', blokir).attr('aria-disabled', blokir ? 'true' : 'false');
            var $hint = $('#hintSimpanDiblokir');
            if (blokir && hint) {
                $hint.removeClass('d-none').text(hint);
            } else {
                $hint.addClass('d-none').text('');
            }
        }

        @php
        $kecamatansData = $kecamatans->map(function($kecamatan) {
            return [
                'id' => $kecamatan->id,
                'nama' => $kecamatan->nama,
                'desa' => $kecamatan->desa->map(function($desa) {
                    return [
                        'id' => $desa->id,
                        'nama' => $desa->nama
                    ];
                })->values()->all(),
            ];
        })->values()->all();
        @endphp
        var kecamatansData = @json($kecamatansData);

        function loadDesaByKecamatan(kecamatanId, selectedDesaId) {
            var desaSelect = $('#desa_id');
            if (desaSelect.data('select2')) {
                desaSelect.select2('destroy');
            }
            desaSelect.empty();
            desaSelect.append('<option value="">Pilih Desa</option>');
            if (kecamatanId) {
                var selectedKecamatan = kecamatansData.find(function(k) {
                    return String(k.id) === String(kecamatanId);
                });
                if (selectedKecamatan && selectedKecamatan.desa) {
                    selectedKecamatan.desa.forEach(function(desa) {
                        var sel = selectedDesaId && String(selectedDesaId) === String(desa.id) ? 'selected' : '';
                        desaSelect.append('<option value="' + desa.id + '" ' + sel + '>' + desa.nama + '</option>');
                    });
                }
            }
            desaSelect.select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Desa',
                allowClear: true
            });
        }

        $('#jenis').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Jenis',
            allowClear: false
        });
        if ($('#jenis_bantuan_id').length) {
            $('#jenis_bantuan_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Jenis Bantuan',
                allowClear: true
            });
        }
        $('#organisasi_id').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kelompok',
            allowClear: true
        });
        $('#penduduk_id').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Penduduk',
            allowClear: true
        });
        $('#penduduk_id').on('change', function() {
            updateSimpanBlokir();
        });
        $('#kecamatan_id').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kecamatan',
            allowClear: true
        });
        $('#desa_id').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Desa',
            allowClear: true
        });

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
                if (desaSelect.data('select2')) {
                    desaSelect.select2('destroy');
                }
                desaSelect.empty();
                desaSelect.append('<option value="">Pilih Desa</option>');
                desaSelect.select2({
                    theme: 'bootstrap4',
                    placeholder: 'Pilih Desa',
                    allowClear: true
                });
            }
        });

        function toggleJenis() {
            var jenis = $('#jenis').val();
            var isBansos = (jenis === BANSOS);
            var isBantuanKelompok = (jenis === BANTUAN_KELOMPOK);

            $('#wrap-kelompok').toggle(!isBansos);
            $('#wrap-penduduk').toggle(isBansos);
            $('#wrap-jenis-bantuan').toggle(isBantuanKelompok);
            $('#jenis_bantuan_id').prop('required', isBantuanKelompok);
            $('#organisasi_id').prop('required', !isBansos);
            $('#penduduk_id').prop('required', isBansos);
        }

        $('#jenis').on('change', toggleJenis);
        toggleJenis();
        updateSimpanBlokir();

        $('#formPengajuan').on('submit', function(e) {
            if ($('#btnSimpanPengajuan').prop('disabled')) {
                e.preventDefault();
                return;
            }
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: '{{ $pengajuan ? "Perbarui Pengajuan" : "Simpan Pengajuan" }}',
                text: 'Apakah Anda yakin ingin {{ $pengajuan ? "memperbarui" : "menyimpan" }} pengajuan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, {{ $pengajuan ? "perbarui" : "simpan" }}',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endpush
@include('components.form_validation')