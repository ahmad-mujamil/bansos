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
    $isSubsidiBunga = $jenis === \App\Enums\JenisPengajuan::SUBSIDI_BUNGA->value;
    // Subsidi bunga memakai alur seperti bansos: pilih jenis penerima (kelompok/individu).
    $isBansosLike = $isBansos || $isSubsidiBunga;
    $pendudukIsValidMap = $pendudukIsValidMap ?? [];
    $simpanDiblokir = $simpanDiblokir ?? false;
    $kelompokSimpanDiblokir = $kelompokSimpanDiblokir ?? false;
    $anggotaBelumTerverifikasi = $anggotaBelumTerverifikasi ?? collect();
    $detailPendudukForIndividu = $detailPendudukForIndividu ?? null;
    $pendudukIndividuInitial = $detailPendudukForIndividu
        ? ['id' => $detailPendudukForIndividu->id, 'is_valid' => (bool) $detailPendudukForIndividu->is_valid]
        : null;
    $jpbVal = old('jenis_penerima_bantuan', $pengajuan?->jenis_penerima_bantuan?->value ?? \App\Enums\JenisPenerimaBantuan::NON_INDIVIDU->value);
    $isNonIndividuJpb = $jpbVal === \App\Enums\JenisPenerimaBantuan::NON_INDIVIDU->value;
    $isIndividuKeluargaJpb = in_array($jpbVal, [\App\Enums\JenisPenerimaBantuan::INDIVIDU->value, \App\Enums\JenisPenerimaBantuan::KELUARGA->value], true);
    $hideKelompokTarget = ! $isNonIndividuJpb;
    $hidePendudukIndividuTarget = $isBansos || ! $isIndividuKeluargaJpb;
    $hidePendudukBansosTarget = ! ($isBansos && $isIndividuKeluargaJpb);
    @endphp

    <form action="{{ $route }}" method="POST" class="needs-validation" id="formPengajuan" enctype="multipart/form-data">
        @csrf
        @method($method)

        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <input type="hidden" name="jenis" value="{{ $jenis }}">

                    <div class="col-12">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6 col-sm-12 {{ $isBansosLike ? '' : 'd-none' }}">
                                <label class="form-label text-small text-uppercase fw-semibold" for="jenis_penerima_bantuan">Jenis penerima bantuan <span class="text-danger">*</span></label>
                                @php
                                    // Subsidi bunga: hanya Kelompok & Individu (tanpa Keluarga).
                                    $jpbCases = $isSubsidiBunga
                                        ? [\App\Enums\JenisPenerimaBantuan::NON_INDIVIDU, \App\Enums\JenisPenerimaBantuan::INDIVIDU]
                                        : \App\Enums\JenisPenerimaBantuan::cases();
                                    $jpbLabel = fn ($jpb) => $isSubsidiBunga
                                        ? ($jpb === \App\Enums\JenisPenerimaBantuan::NON_INDIVIDU ? 'Kelompok' : 'Individu')
                                        : $jpb->getDescription();
                                @endphp
                                <select name="jenis_penerima_bantuan" id="jenis_penerima_bantuan" class="form-select @error('jenis_penerima_bantuan') is-invalid @enderror" required>
                                    @foreach($jpbCases as $jpb)
                                        <option value="{{ $jpb->value }}" @selected(old('jenis_penerima_bantuan', $pengajuan?->jenis_penerima_bantuan?->value ?? \App\Enums\JenisPenerimaBantuan::NON_INDIVIDU->value) === $jpb->value)>
                                            {{ $jpbLabel($jpb) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_penerima_bantuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="{{ $isBansosLike ? 'col-md-6 col-sm-12' : 'col-12' }}" id="wrap-kolom-penerima-target">
                                @if($isBansos)
                                    <div id="wrap-penduduk" class="{{ $hidePendudukBansosTarget ? 'd-none' : '' }}">
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
                                @endif

                                <div id="wrap-kelompok" class="{{ $hideKelompokTarget ? 'd-none' : '' }}">
                                    <label class="form-label text-small text-uppercase" for="organisasi_id">Kelompok <span class="text-danger">*</span></label>
                                    <select name="organisasi_id" id="organisasi_id" class="form-select @error('organisasi_id') is-invalid @enderror">
                                        <option value="">Pilih Kelompok</option>
                                        @foreach($kelompokList as $k)
                                        <option value="{{ $k->id }}" {{ old('organisasi_id', $pengajuan?->organisasi_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('organisasi_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="wrap-penduduk-individu" class="{{ $hidePendudukIndividuTarget ? 'd-none' : '' }}">
                                    <label class="form-label text-small text-uppercase fw-semibold" for="penduduk_id_individu">Penduduk (cari NIK / nama) <span class="text-danger">*</span></label>
                                    <select name="penduduk_id" id="penduduk_id_individu" class="form-select @error('penduduk_id') is-invalid @enderror" data-placeholder="Ketik minimal 2 karakter NIK atau nama">
                                        @if($detailPendudukForIndividu)
                                            <option value="{{ $detailPendudukForIndividu->id }}" selected>{{ $detailPendudukForIndividu->nama }} — {{ $detailPendudukForIndividu->nik }}</option>
                                        @endif
                                    </select>
                                    @error('penduduk_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Pencarian berdasarkan NIK atau nama penduduk.</div>
                                </div>
                            </div>
                        </div>
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
                        <label class="form-label text-small text-uppercase">{{ $isSubsidiBunga ? 'Nama Usaha' : 'Judul Usulan' }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul" required
                            value="{{ old('judul', $detail?->judul ?? '') }}" placeholder="{{ $isSubsidiBunga ? 'Nama usaha' : 'Judul usulan bantuan' }}" />
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
                            {{ $isSubsidiBunga ? 'Nilai Usulan Kredit (Rp)' : 'Nilai Usulan (Rp)' }} <span class="text-danger">*</span>
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
                        <label class="form-label text-small text-uppercase">{{ $isSubsidiBunga ? 'NIB dan Dokumentasi Usaha (PDF)' : 'File Pengajuan (PDF)' }}</label>
                        <input type="file" class="form-control @error('file_pengajuan') is-invalid @enderror" name="file_pengajuan" accept="application/pdf">
                        <small class="text-muted">{{ $isSubsidiBunga ? 'Gabungkan NIB dan dokumentasi usaha dalam satu file PDF, maksimal 5 MB.' : 'Format PDF, maksimal 5 MB.' }}</small>
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
                    @elseif(old('jenis_penerima_bantuan', $pengajuan?->jenis_penerima_bantuan?->value ?? \App\Enums\JenisPenerimaBantuan::NON_INDIVIDU->value) !== \App\Enums\JenisPenerimaBantuan::NON_INDIVIDU->value)
                        Penduduk yang dipilih belum terverifikasi (is_valid). Selesaikan verifikasi data penduduk terlebih dahulu.
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
        var NON_INDIVIDU_JPB = '{{ \App\Enums\JenisPenerimaBantuan::NON_INDIVIDU->value }}';
        var INDIVIDU_JPB = '{{ \App\Enums\JenisPenerimaBantuan::INDIVIDU->value }}';
        var KELUARGA_JPB = '{{ \App\Enums\JenisPenerimaBantuan::KELUARGA->value }}';
        var pendudukIndividuInitial = @json($pendudukIndividuInitial);
        window._pendudukIndividuIsValid = null;

        function updateSimpanBlokir() {
            var blokir = false;
            var hint = '';
            var jp = $('#jenis_penerima_bantuan').val();

            if (jenisPengajuan === BANSOS) {
                var pid = $('#penduduk_id').val();
                if (pid && pendudukIsValidMap[pid] !== true) {
                    blokir = true;
                    hint = 'Penduduk yang dipilih belum terverifikasi (kolom is_valid). Selesaikan verifikasi data penduduk terlebih dahulu.';
                }
            } else if (jp === NON_INDIVIDU_JPB && kelompokSimpanDiblokir) {
                blokir = true;
                hint = 'Masih ada anggota kelompok yang data penduduknya belum diverifikasi. Selesaikan verifikasi seluruh anggota terlebih dahulu.';
            } else if (jp === INDIVIDU_JPB || jp === KELUARGA_JPB) {
                var pidI = $('#penduduk_id_individu').val();
                if (pidI) {
                    var ok = null;
                    if (pendudukIndividuInitial && String(pendudukIndividuInitial.id) === String(pidI)) {
                        ok = pendudukIndividuInitial.is_valid;
                    } else if (typeof window._pendudukIndividuIsValid === 'boolean') {
                        ok = window._pendudukIndividuIsValid;
                    }
                    if (ok === false) {
                        blokir = true;
                        hint = 'Penduduk yang dipilih belum terverifikasi (is_valid). Selesaikan verifikasi data penduduk terlebih dahulu.';
                    }
                }
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

        $('#jenis_penerima_bantuan').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih jenis penerima',
            allowClear: false,
            width: '100%'
        });
        if ($('#jenis_bantuan_id').length) {
            $('#jenis_bantuan_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Jenis Bantuan',
                allowClear: true
            });
        }

        function togglePenerimaBantuan() {
            var jp = $('#jenis_penerima_bantuan').val();
            var isNonIndividu = (jp === NON_INDIVIDU_JPB);
            var isIndividuAtauKeluarga = (jp === INDIVIDU_JPB || jp === KELUARGA_JPB);
            var isBansosFlow = (jenisPengajuan === BANSOS);

            if (isBansosFlow && $('#wrap-penduduk').length) {
                if (isNonIndividu) {
                    $('#wrap-penduduk').addClass('d-none');
                    $('#wrap-kelompok').removeClass('d-none');
                    $('#wrap-penduduk-individu').addClass('d-none');
                    $('#penduduk_id').prop('disabled', true).prop('required', false);
                    $('#organisasi_id').prop('disabled', false).prop('required', true);
                    $('#penduduk_id_individu').prop('disabled', true).prop('required', false);
                } else if (isIndividuAtauKeluarga) {
                    $('#wrap-penduduk').removeClass('d-none');
                    $('#wrap-kelompok').addClass('d-none');
                    $('#wrap-penduduk-individu').addClass('d-none');
                    $('#penduduk_id').prop('disabled', false).prop('required', true);
                    $('#organisasi_id').prop('disabled', true).prop('required', false);
                    $('#penduduk_id_individu').prop('disabled', true).prop('required', false);
                }
                if ($('#wrap-jenis-bantuan').length) {
                    $('#wrap-jenis-bantuan').toggle(jenisPengajuan === BANTUAN_KELOMPOK);
                }
                if ($('#jenis_bantuan_id').length) {
                    $('#jenis_bantuan_id').prop('required', jenisPengajuan === BANTUAN_KELOMPOK);
                }
                return;
            }

            if ($('#wrap-penduduk').length) {
                $('#wrap-penduduk').addClass('d-none');
                $('#penduduk_id').prop('disabled', true);
            }

            if (isNonIndividu) {
                $('#wrap-kelompok').removeClass('d-none');
                $('#wrap-penduduk-individu').addClass('d-none');
                $('#organisasi_id').prop('disabled', false).prop('required', true);
                $('#penduduk_id_individu').prop('disabled', true).prop('required', false);
            } else if (isIndividuAtauKeluarga) {
                $('#wrap-kelompok').addClass('d-none');
                $('#wrap-penduduk-individu').removeClass('d-none');
                $('#organisasi_id').prop('disabled', true).prop('required', false);
                $('#penduduk_id_individu').prop('disabled', false).prop('required', true);
            }

            if ($('#wrap-jenis-bantuan').length) {
                $('#wrap-jenis-bantuan').toggle(jenisPengajuan === BANTUAN_KELOMPOK);
            }
            if ($('#jenis_bantuan_id').length) {
                $('#jenis_bantuan_id').prop('required', jenisPengajuan === BANTUAN_KELOMPOK);
            }
        }

        togglePenerimaBantuan();

        $('#organisasi_id').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kelompok',
            allowClear: true
        });
        if ($('#penduduk_id').length) {
            $('#penduduk_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Penduduk',
                allowClear: true
            });
            $('#penduduk_id').on('change', function() {
                updateSimpanBlokir();
            });
        }

        $('#penduduk_id_individu').select2({
            theme: 'bootstrap4',
            placeholder: 'Ketik minimal 2 karakter NIK atau nama',
            allowClear: true,
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: "{{ route('pengajuan-opd.penduduk-search') }}",
                delay: 250,
                data: function (params) {
                    return { q: params.term || '' };
                },
                processResults: function (data) {
                    return data;
                }
            }
        });
        $('#penduduk_id_individu').on('select2:select', function (e) {
            window._pendudukIndividuIsValid = e.params.data.is_valid === true;
            updateSimpanBlokir();
        });
        $('#penduduk_id_individu').on('select2:clear', function () {
            window._pendudukIndividuIsValid = null;
            updateSimpanBlokir();
        });
        if (pendudukIndividuInitial) {
            window._pendudukIndividuIsValid = pendudukIndividuInitial.is_valid === true;
        }

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

        $('#jenis_penerima_bantuan').on('change', function () {
            togglePenerimaBantuan();
            updateSimpanBlokir();
        });
        updateSimpanBlokir();

        $('#formPengajuan').on('submit', function(e) {
            $('#organisasi_id').prop('disabled', false);
            $('#penduduk_id_individu').prop('disabled', false);
            var jp = $('#jenis_penerima_bantuan').val();
            if (jp === NON_INDIVIDU_JPB) {
                $('#penduduk_id_individu').prop('disabled', true);
            } else {
                $('#organisasi_id').prop('disabled', true);
            }

            if ($('#btnSimpanPengajuan').prop('disabled')) {
                e.preventDefault();
                $('#organisasi_id').prop('disabled', $('#jenis_penerima_bantuan').val() !== NON_INDIVIDU_JPB);
                $('#penduduk_id_individu').prop('disabled', $('#jenis_penerima_bantuan').val() === NON_INDIVIDU_JPB);
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
                if (result.isConfirmed) {
                    $('#organisasi_id').prop('disabled', false);
                    $('#penduduk_id_individu').prop('disabled', false);
                    var jpx = $('#jenis_penerima_bantuan').val();
                    if (jpx === NON_INDIVIDU_JPB) {
                        $('#penduduk_id_individu').prop('disabled', true);
                    } else {
                        $('#organisasi_id').prop('disabled', true);
                    }
                    form.submit();
                } else {
                    $('#organisasi_id').prop('disabled', $('#jenis_penerima_bantuan').val() !== NON_INDIVIDU_JPB);
                    $('#penduduk_id_individu').prop('disabled', $('#jenis_penerima_bantuan').val() === NON_INDIVIDU_JPB);
                }
            });
        });
    });
</script>
@endpush
@include('components.form_validation')