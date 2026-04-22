@extends('layouts.layout')
@section('title', 'Kelompok Masyarakat')
@section('content')
    <!-- Page Content Start -->
    @php
        $isEdit = (bool) $organisasi;
        $route = $isEdit ? route('kelompok-masyarakat.update', $organisasi->id) : route('kelompok-masyarakat.store');
        $method = $isEdit ? 'PUT' : 'POST';
    @endphp
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
                            <li class="breadcrumb-item"><a href="javascript:;">{{ $isEdit ? 'Edit Data' : 'Tambah Data' }}</a></li>
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

        <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation" id="formKelompokWizard">
            @csrf
            @if ($isEdit)
                @method($method)
            @endif
            <input type="hidden" name="jenis_pengajuan" value="{{ old('jenis_pengajuan', $jenisPengajuanValue ?? '') }}">
            <div class="card mb-5">
                <div class="card-body">
                    <ul class="nav nav-pills nav-justified flex-column flex-sm-row gap-2 mb-4" id="wizardSteps" role="list">
                        <li class="nav-item" role="listitem">
                            <div class="nav-link w-100 active d-flex align-items-center justify-content-center gap-2 py-3 wizard-step-label" data-wizard-step="1" aria-current="step">
                                <span class="badge rounded-circle wizard-step-badge bg-primary">1</span>
                                <span>Detail Kelompok</span>
                            </div>
                        </li>
                        <li class="nav-item" role="listitem">
                            <div class="nav-link w-100 d-flex align-items-center justify-content-center gap-2 py-3 text-muted wizard-step-label" data-wizard-step="2">
                                <span class="badge rounded-circle wizard-step-badge bg-secondary">2</span>
                                <span>Anggota</span>
                            </div>
                        </li>
                        <li class="nav-item" role="listitem">
                            <div class="nav-link w-100 d-flex align-items-center justify-content-center gap-2 py-3 text-muted wizard-step-label" data-wizard-step="3">
                                <span class="badge rounded-circle wizard-step-badge bg-secondary">3</span>
                                <span>Dokumen</span>
                            </div>
                        </li>
                    </ul>

                    <div class="wizard-pane" data-pane="1">
                        <h2 class="small-title mb-3">Data kelompok</h2>
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
                                @php
                                    $jenisStored = optional($organisasi)->jenis;
                                    $fallbackJenis = ($requireJenisSelection ?? false)
                                        ? ''
                                        : \App\Enums\JenisOrganisasi::KELOMPOK->value;
                                    $selectedJenis = old(
                                        'jenis',
                                        \App\Enums\JenisOrganisasi::tryFrom((string) ($jenisStored ?? ''))?->value
                                            ?? ($defaultJenis ?? null)
                                            ?? $fallbackJenis
                                    );
                                @endphp
                                @if(($isBantuanKelompok ?? false) && $selectedJenis === \App\Enums\JenisOrganisasi::KELOMPOK->value)
                                    <input type="hidden" name="jenis" value="{{ \App\Enums\JenisOrganisasi::KELOMPOK->value }}">
                                    <label class="form-label text-small text-uppercase">Jenis</label>
                                    <input type="text" class="form-control" value="Kelompok Masyarakat" readonly>
                                @else
                                    <label class="form-label text-small text-uppercase">Jenis <span class="text-danger">*</span></label>
                                    <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis</option>
                                        @foreach($jenisOrganisasiOptions as $jenisOption)
                                            <option value="{{ $jenisOption->value }}" {{ $selectedJenis === $jenisOption->value ? 'selected' : '' }}>
                                                {{ $jenisOption->getDescription() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>
                            @php
                                $selectedJenisKelompokMasyarakat = old(
                                    'jenis_kelompok_masyarakat_id',
                                    optional($organisasi)->jenis_kelompok_masyarakat_id ?? ''
                                );
                            @endphp
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-3 {{ ($isBantuanKelompok ?? false) ? '' : 'd-none' }}" id="jenisKelompokMasyarakatField">
                                <label class="form-label text-small text-uppercase">Jenis Kelompok Masyarakat <span class="text-danger">*</span></label>
                                <select
                                    name="jenis_kelompok_masyarakat_id"
                                    id="jenis_kelompok_masyarakat_id"
                                    class="form-control @error('jenis_kelompok_masyarakat_id') is-invalid @enderror"
                                    {{ ($isBantuanKelompok ?? false) ? 'required' : '' }}
                                >
                                    <option value="">Pilih Jenis Kelompok Masyarakat</option>
                                    @foreach(($jenisKelompokMasyarakatOptions ?? collect()) as $jenisKelompokMasyarakat)
                                        <option value="{{ $jenisKelompokMasyarakat->id }}" {{ $selectedJenisKelompokMasyarakat == $jenisKelompokMasyarakat->id ? 'selected' : '' }}>
                                            {{ $jenisKelompokMasyarakat->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_kelompok_masyarakat_id')
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
                                    @if($isEdit && optional($organisasi)->desa_id)
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
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-primary" id="wizardNext1">Selanjutnya</button>
                        </div>
                    </div>

                    <div class="wizard-pane d-none" data-pane="2">
                        <h2 class="small-title mb-3">Anggota kelompok</h2>
                        <p class="text-small text-muted mb-3">Kelola data anggota. Isi NIK 16 digit; gunakan <strong>Cek</strong> atau lanjutkan mengisi — jika NIK sudah ada di data penduduk, nama, jenis kelamin, kecamatan, dan desa terisi otomatis. Pilih kecamatan dulu, lalu desa, kemudian jabatan. Satu NIK hanya boleh satu baris. Menyimpan formulir akan mengganti daftar anggota sesuai isian di bawah.</p>
                        <div id="anggota-rows" class="mb-3"></div>
                        <button type="button" class="btn btn-outline-primary btn-sm mb-4" id="btn-add-anggota">
                            <i data-acorn-icon="plus"></i> Tambah anggota
                        </button>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-wizard-back="2">Kembali</button>
                            <button type="button" class="btn btn-primary" id="wizardNext2">Selanjutnya</button>
                        </div>
                    </div>

                    <div class="wizard-pane d-none" data-pane="3">
                        <h2 class="small-title mb-3">Dokumen pendukung</h2>
                        <p class="text-small text-muted mb-3">Kelola data dokumen. Format PDF, JPG, PNG, WebP — maks. 10 MB per file. Untuk dokumen yang sudah ada, unggah berkas baru hanya jika ingin mengganti.</p>
                        @if ($errors->any())
                            <p class="text-small text-warning mb-3">Jika penyimpanan gagal sebelumnya, pilih ulang file dokumen untuk entri baru.</p>
                        @endif
                        <div id="dokumen-rows" class="mb-3"></div>
                        <button type="button" class="btn btn-outline-primary btn-sm mb-4" id="btn-add-dokumen">
                            <i data-acorn-icon="plus"></i> Tambah dokumen
                        </button>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-wizard-back="3">Kembali</button>
                            <button type="submit" class="btn btn-primary">Simpan semua data</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <template id="tpl-anggota-row">
            <div class="card mb-3 anggota-row-item border-0 shadow-sm rounded-3 overflow-hidden" data-locked="0" data-existing-anggota="0">
                <div class="border-start border-primary border-4">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 px-3 py-2 bg-light border-bottom">
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="anggota-verifikasi-wrap d-none text-end">
                                <span class="d-block text-muted text-uppercase mb-0" style="font-size: 0.65rem; letter-spacing: 0.06em;">Status Penduduk</span>
                                <span class="badge anggota-verifikasi-badge mt-1">—</span>
                                <div class="anggota-catatan-validasi-wrap d-none mt-1">
                                    <span class="badge bg-warning text-dark fw-normal text-start anggota-catatan-validasi-text d-inline-block" title="Catatan validasi" style="font-size: 0.65rem; max-width: 16rem; white-space: normal; line-height: 1.35;"></span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-anggota rounded-pill px-2" title="Hapus baris" aria-label="Hapus baris anggota">&times;</button>
                        </div>
                    </div>
                    <div class="card-body pt-3 pb-3 px-3">
                    <input type="hidden" class="anggota-organisasi-detail-id-field" name="anggota[__IDX__][organisasi_detail_id]" value="">
                    <div class="row align-items-end">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase">NIK <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control anggota-nik" name="anggota[__IDX__][nik]"
                                       maxlength="16" minlength="16" inputmode="numeric" autocomplete="off"
                                       placeholder="16 digit angka" pattern="[0-9]{16}" required>
                                <button type="button" class="btn btn-outline-secondary btn-cek-nik" title="Cek NIK di data penduduk">Cek</button>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control anggota-nama" name="anggota[__IDX__][nama]" required maxlength="255">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase">Jenis kelamin <span class="text-danger">*</span></label>
                            <select class="form-control anggota-jk" name="anggota[__IDX__][jk]" required>
                                <option value="">Pilih</option>
                                @foreach(\App\Enums\JenisKelamin::cases() as $jkOpt)
                                    <option value="{{ $jkOpt->value }}">{{ $jkOpt->getDescription() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 col-md-12 mb-3">
                            <small class="text-muted d-none anggota-existing-hint">Anggota sudah terdaftar — hanya <strong>jabatan</strong> yang dapat diubah. Hapus baris ini jika ingin mengeluarkan dari daftar.</small>
                            <small class="text-success d-none anggota-nik-found-msg">NIK ditemukan di data penduduk; identitas diisi otomatis.</small>
                            <small class="text-warning d-none anggota-nik-not-found-msg">NIK tidak ditemukan. Lengkapi nama, jenis kelamin, kecamatan, dan desa secara manual.</small>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase">Kecamatan <span class="text-danger">*</span></label>
                            <select class="form-control anggota-kecamatan" name="anggota[__IDX__][kecamatan_id]" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase">Desa <span class="text-danger">*</span></label>
                            <select class="form-control anggota-desa" name="anggota[__IDX__][desa_id]" required>
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase">Jabatan <span class="text-danger">*</span></label>
                            <select class="form-control anggota-jabatan" name="anggota[__IDX__][jabatan]" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach(\App\Enums\JabatanOrganisasi::cases() as $jabatanOption)
                                    <option value="{{ $jabatanOption->value }}">{{ $jabatanOption->getDescription() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </template>

        <template id="tpl-dokumen-row">
            <div class="card mb-3 dokumen-row-item border">
                <div class="card-body py-3">
                    <input type="hidden" class="dokumen-id-field" name="dokumen[__IDX__][id]" value="">
                    <div class="row">
                        <div class="col-12 mb-2 dokumen-existing-wrap d-none">
                            <small class="text-muted">Berkas saat ini: <a class="dokumen-file-link fw-bold" href="#" target="_blank" rel="noopener noreferrer"></a>. Unggah berkas baru untuk mengganti.</small>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase">Jenis dokumen <span class="text-danger">*</span></label>
                            <select class="form-control dokumen-jenis" name="dokumen[__IDX__][jenis_dokumen]" required>
                                <option value="">Pilih jenis</option>
                                @foreach(\App\Enums\JenisDokumen::cases() as $jenis)
                                    <option value="{{ $jenis->value }}">{{ $jenis->getDescription() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="dokumen[__IDX__][keterangan]" required maxlength="255" placeholder="Contoh: NPWP organisasi">
                        </div>
                        <div class="col-lg-3 col-md-10 mb-3">
                            <label class="form-label text-small text-uppercase dokumen-file-label">File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control dokumen-file-input" name="dokumen[__IDX__][file]" accept=".pdf,.jpg,.jpeg,.png,.webp">
                        </div>
                        <div class="col-lg-1 col-md-2 mb-3 d-flex align-items-end justify-content-lg-end">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-dokumen" title="Hapus baris">&times;</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
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
            var BANTUAN_KELOMPOK = @json(\App\Enums\JenisPengajuan::BANTUAN_KELOMPOK->value);

            function syncJenisKelompokMasyarakatField() {
                var jenisPengajuan = $('input[name="jenis_pengajuan"]').val() || '';
                var isBantuanKelompok = jenisPengajuan === BANTUAN_KELOMPOK;
                var $fieldWrap = $('#jenisKelompokMasyarakatField');
                var $select = $('#jenis_kelompok_masyarakat_id');

                $fieldWrap.toggleClass('d-none', !isBantuanKelompok);
                $select.prop('required', isBantuanKelompok);

                if (!isBantuanKelompok) {
                    $select.val('').trigger('change');
                }
            }

            function loadDesaByKecamatan(kecamatanId, selectedDesaId) {
                var desaSelect = $('#desa_id');
                if (desaSelect.data('select2')) {
                    desaSelect.select2('destroy');
                }
                desaSelect.empty();
                desaSelect.append('<option value="">Pilih Desa</option>');
                if (kecamatanId) {
                    var selectedKecamatan = kecamatansData.find(function(k) { return String(k.id) === String(kecamatanId); });
                    if (selectedKecamatan && selectedKecamatan.desa) {
                        selectedKecamatan.desa.forEach(function(desa) {
                            var sel = selectedDesaId && String(selectedDesaId) === String(desa.id) ? 'selected' : '';
                            desaSelect.append('<option value="' + desa.id + '" ' + sel + '>' + desa.nama + '</option>');
                        });
                    }
                }
                desaSelect.select2({ theme: 'bootstrap4', placeholder: 'Pilih Desa', allowClear: true });
            }

            if ($('#jenis').length) {
                $('#jenis').select2({ theme: 'bootstrap4', placeholder: 'Pilih Jenis', allowClear: false });
            }
            $('#jenis_kelompok_masyarakat_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Jenis Kelompok Masyarakat', allowClear: true });
            $('#kecamatan_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Kecamatan', allowClear: true });
            $('#desa_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih Desa', allowClear: true });
            syncJenisKelompokMasyarakatField();

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

            (function () {
                var anggotaIndex = {{ count($anggotaInitial) }};
                var dokumenIndex = {{ count($dokumenInitial) }};
                var lookupNikUrl = @json(route('kelompok-masyarakat.penduduk-by-nik'));

                function setStep(step) {
                    $('.wizard-pane').addClass('d-none');
                    $('.wizard-pane[data-pane="' + step + '"]').removeClass('d-none');
                    $('#wizardSteps .wizard-step-label').each(function () {
                        var s = parseInt($(this).data('wizard-step'), 10);
                        var isActive = s === step;
                        $(this).toggleClass('active', isActive);
                        $(this).toggleClass('text-muted', !isActive);
                        $(this).attr('aria-current', isActive ? 'step' : null);
                        var badge = $(this).find('.wizard-step-badge');
                        badge.toggleClass('bg-primary', isActive);
                        badge.toggleClass('bg-secondary', !isActive);
                    });
                }

                function loadDesaForAnggotaRow($row, kecamatanId, selectedDesaId) {
                    var $desa = $row.find('.anggota-desa');
                    if ($desa.data('select2')) {
                        $desa.select2('destroy');
                    }
                    $desa.empty().append('<option value="">Pilih Desa</option>');
                    if (kecamatanId) {
                        var selectedKecamatan = kecamatansData.find(function (k) { return String(k.id) === String(kecamatanId); });
                        if (selectedKecamatan && selectedKecamatan.desa) {
                            selectedKecamatan.desa.forEach(function (desa) {
                                var sel = selectedDesaId && String(selectedDesaId) === String(desa.id) ? ' selected' : '';
                                $desa.append('<option value="' + desa.id + '"' + sel + '>' + desa.nama + '</option>');
                            });
                        }
                    }
                    $desa.select2({ theme: 'bootstrap4', placeholder: 'Pilih Desa', allowClear: true, width: '100%' });
                    if (selectedDesaId) {
                        $desa.val(String(selectedDesaId)).trigger('change');
                    }
                }

                function bindSelect2Anggota($row) {
                    $row.find('.anggota-kecamatan').select2({ theme: 'bootstrap4', placeholder: 'Pilih Kecamatan', allowClear: true, width: '100%' });
                    $row.find('.anggota-jk').select2({ theme: 'bootstrap4', placeholder: 'Pilih', allowClear: true, width: '100%' });
                    $row.find('.anggota-jabatan').select2({ theme: 'bootstrap4', placeholder: 'Pilih Jabatan', allowClear: false, width: '100%' });
                    loadDesaForAnggotaRow($row, $row.find('.anggota-kecamatan').val() || null, null);
                }

                /** Penduduk sudah diverifikasi sebagai tidak valid (is_valid = 0, validated_at terisi): identitas boleh diubah sepenuhnya. */
                function isPendudukInvalidVerified(p) {
                    return !!(p && !p.is_valid && p.validated_at);
                }

                /** Baris anggota dari database (mode edit): NIK–desa terkunci; hanya jabatan yang dapat diedit; tombol Cek disembunyikan. */
                function applyExistingAnggotaReadOnly($row) {
                    $row.attr('data-existing-anggota', '1');
                    $row.find('.anggota-existing-hint').removeClass('d-none');
                    $row.find('.anggota-nik').prop('disabled', true);
                    $row.find('.btn-cek-nik').addClass('d-none');
                    $row.find('.anggota-nama').prop('disabled', true);
                    $row.find('.anggota-jk').prop('disabled', true);
                    $row.find('.anggota-kecamatan').prop('disabled', true);
                    $row.find('.anggota-desa').prop('disabled', true);
                }

                function unlockAnggotaIdentity($row) {
                    $row.attr('data-locked', '0');
                    $row.removeData('skipDesaReset');
                    $row.find('.anggota-nama').prop('readonly', false);
                    $row.find('.anggota-jk').prop('disabled', false);
                    $row.find('.anggota-kecamatan').prop('disabled', false);
                    $row.find('.anggota-desa').prop('disabled', false);
                }

                /** Kosongkan field selain NIK sebelum cek ulang (tombol Cek). */
                function resetAnggotaRowIdentityFields($row) {
                    unlockAnggotaIdentity($row);
                    $row.find('.anggota-nama').val('');
                    $row.find('.anggota-jk').val('').trigger('change');
                    $row.data('skipDesaReset', true);
                    $row.find('.anggota-kecamatan').val('').trigger('change');
                    loadDesaForAnggotaRow($row, null, null);
                    $row.removeData('skipDesaReset');
                    $row.find('.anggota-jabatan').val('').trigger('change');
                    $row.find('.anggota-nik-found-msg').addClass('d-none');
                    $row.find('.anggota-nik-not-found-msg').addClass('d-none');
                    setAnggotaVerifikasiBadge($row, null);
                }

                /** Sama logika badge seperti KelompokMasyarakatAnggotaController::data status_label. */
                function setAnggotaVerifikasiBadge($row, p) {
                    var $wrap = $row.find('.anggota-verifikasi-wrap');
                    var $badge = $row.find('.anggota-verifikasi-badge');
                    var $catWrap = $row.find('.anggota-catatan-validasi-wrap');
                    var $catText = $row.find('.anggota-catatan-validasi-text');
                    if (!p) {
                        $wrap.addClass('d-none');
                        $catWrap.addClass('d-none');
                        $catText.text('');
                        return;
                    }
                    $wrap.removeClass('d-none');
                    $badge.removeClass('bg-success bg-danger bg-warning text-dark');
                    if (p.is_valid) {
                        $badge.addClass('bg-success').text('Terverifikasi');
                        $catWrap.addClass('d-none');
                        $catText.text('');
                    } else if (p.validated_at && !p.is_valid) {
                        $badge.addClass('bg-danger').text('Tidak Valid');
                        $catWrap.removeClass('d-none');
                        $catText.text(String(p.catatan_validasi || '').trim() || '—');
                    } else {
                        $badge.addClass('bg-warning text-dark').text('Belum Diverifikasi');
                        var catPending = String(p.catatan_validasi || '').trim();
                        if (catPending) {
                            $catWrap.removeClass('d-none');
                            $catText.text(catPending);
                        } else {
                            $catWrap.addClass('d-none');
                            $catText.text('');
                        }
                    }
                }

                function applyPendudukLookup($row, p) {
                    $row.find('.anggota-nik-not-found-msg').addClass('d-none');
                    $row.find('.anggota-nama').val(p.nama);
                    $row.data('skipDesaReset', true);
                    $row.find('.anggota-kecamatan').val(p.kecamatan_id).trigger('change');
                    loadDesaForAnggotaRow($row, p.kecamatan_id, p.desa_id);
                    $row.removeData('skipDesaReset');
                    $row.find('.anggota-jk').val(p.jk).trigger('change');

                    if (isPendudukInvalidVerified(p)) {
                        $row.attr('data-locked', '0');
                        $row.find('.anggota-nama').prop('readonly', false);
                        $row.find('.anggota-jk').prop('disabled', false);
                        $row.find('.anggota-kecamatan').prop('disabled', false);
                        $row.find('.anggota-desa').prop('disabled', false);
                    } else {
                        $row.attr('data-locked', '1');
                        $row.find('.anggota-nama').prop('readonly', true);
                        $row.find('.anggota-jk').prop('disabled', true);
                        $row.find('.anggota-kecamatan').prop('disabled', true);
                        $row.find('.anggota-desa').prop('disabled', true);
                    }

                    $row.find('.anggota-nik-found-msg').removeClass('d-none');
                    setAnggotaVerifikasiBadge($row, {
                        is_valid: !!p.is_valid,
                        validated_at: p.validated_at || null,
                        catatan_validasi: p.catatan_validasi || null
                    });
                }

                function fetchNikLookup($row) {
                    var $nik = $row.find('.anggota-nik');
                    var raw = ($nik.val() || '').replace(/\D/g, '');
                    $nik.val(raw);
                    if (raw.length !== 16) {
                        $row.find('.anggota-nik-found-msg').addClass('d-none');
                        $row.find('.anggota-nik-not-found-msg').addClass('d-none');
                        setAnggotaVerifikasiBadge($row, null);
                        return;
                    }
                    $.getJSON(lookupNikUrl, { nik: raw })
                        .done(function (res) {
                            if (res.found && res.penduduk) {
                                applyPendudukLookup($row, res.penduduk);
                            } else {
                                unlockAnggotaIdentity($row);
                                $row.find('.anggota-nik-found-msg').addClass('d-none');
                                $row.find('.anggota-nik-not-found-msg').removeClass('d-none');
                                setAnggotaVerifikasiBadge($row, null);
                            }
                        });
                }

                function wireAnggotaRow($el) {
                    bindSelect2Anggota($el);
                    $el.find('.anggota-kecamatan').on('change', function () {
                        if ($el.attr('data-existing-anggota') === '1') {
                            return;
                        }
                        if ($el.data('skipDesaReset')) {
                            return;
                        }
                        loadDesaForAnggotaRow($el, $(this).val(), null);
                    });
                    $el.find('.anggota-nik').on('input', function () {
                        if ($el.attr('data-existing-anggota') === '1') {
                            return;
                        }
                        var v = $(this).val().replace(/\D/g, '').slice(0, 16);
                        $(this).val(v);
                        $el.find('.anggota-nik-found-msg').addClass('d-none');
                        $el.find('.anggota-nik-not-found-msg').addClass('d-none');
                        if ($el.attr('data-locked') === '1') {
                            unlockAnggotaIdentity($el);
                            setAnggotaVerifikasiBadge($el, null);
                        }
                    });
                    $el.find('.btn-cek-nik').on('click', function () {
                        if ($el.attr('data-existing-anggota') === '1') {
                            return;
                        }
                        resetAnggotaRowIdentityFields($el);
                        fetchNikLookup($el);
                    });
                    $el.find('.anggota-nik').on('blur', function () {
                        if ($el.attr('data-existing-anggota') === '1') {
                            return;
                        }
                        var v = ($(this).val() || '').replace(/\D/g, '');
                        if (v.length === 16 && $el.attr('data-locked') !== '1') {
                            fetchNikLookup($el);
                        }
                    });
                }

                function bindSelect2Dokumen($root) {
                    $root.find('.dokumen-jenis').select2({ theme: 'bootstrap4', placeholder: 'Pilih jenis', allowClear: false, width: '100%' });
                }

                function addAnggotaRow(values) {
                    values = values || {};
                    var tpl = document.getElementById('tpl-anggota-row');
                    var html = tpl.innerHTML.replace(/__IDX__/g, String(anggotaIndex));
                    var $el = $(html);
                    $('#anggota-rows').append($el);
                    wireAnggotaRow($el);
                    if (values.organisasi_detail_id) {
                        $el.find('.anggota-organisasi-detail-id-field').val(values.organisasi_detail_id);
                    }
                    if (values.nik) {
                        $el.find('.anggota-nik').val(values.nik);
                    }
                    if (values.nama) {
                        $el.find('.anggota-nama').val(values.nama);
                    }
                    if (values.kecamatan_id) {
                        $el.data('skipDesaReset', true);
                        $el.find('.anggota-kecamatan').val(values.kecamatan_id).trigger('change');
                        loadDesaForAnggotaRow($el, values.kecamatan_id, values.desa_id || null);
                        $el.removeData('skipDesaReset');
                    }
                    if (values.jk) {
                        $el.find('.anggota-jk').val(values.jk).trigger('change');
                    }
                    if (values.jabatan) {
                        $el.find('.anggota-jabatan').val(values.jabatan).trigger('change');
                    }
                    if (values.organisasi_detail_id && !isPendudukInvalidVerified({
                        is_valid: !!values.is_valid,
                        validated_at: values.validated_at || null
                    })) {
                        applyExistingAnggotaReadOnly($el);
                    }
                    if (Object.prototype.hasOwnProperty.call(values, 'is_valid') || Object.prototype.hasOwnProperty.call(values, 'validated_at') || Object.prototype.hasOwnProperty.call(values, 'catatan_validasi')) {
                        setAnggotaVerifikasiBadge($el, {
                            is_valid: !!values.is_valid,
                            validated_at: values.validated_at || null,
                            catatan_validasi: values.catatan_validasi != null ? values.catatan_validasi : null
                        });
                    }
                    anggotaIndex++;
                }

                function addDokumenRow(values) {
                    values = values || {};
                    var tpl = document.getElementById('tpl-dokumen-row');
                    var html = tpl.innerHTML.replace(/__IDX__/g, String(dokumenIndex));
                    var $el = $(html);
                    $('#dokumen-rows').append($el);
                    bindSelect2Dokumen($el);
                    var $file = $el.find('.dokumen-file-input');
                    var $labelStar = $el.find('.dokumen-file-label .text-danger');
                    if (values.id) {
                        $el.find('.dokumen-id-field').val(values.id);
                        $file.prop('required', false);
                        $labelStar.addClass('d-none');
                        $el.find('.dokumen-existing-wrap').removeClass('d-none');
                        if (values.file_url && values.file_name) {
                            $el.find('.dokumen-file-link').attr('href', values.file_url).text(values.file_name);
                        } else {
                            $el.find('.dokumen-existing-wrap').addClass('d-none');
                        }
                    } else {
                        $el.find('.dokumen-id-field').remove();
                        $file.prop('required', true);
                    }
                    if (values.jenis_dokumen) {
                        $el.find('.dokumen-jenis').val(values.jenis_dokumen).trigger('change');
                    }
                    if (values.keterangan) {
                        $el.find('input[name*="[keterangan]"]').val(values.keterangan);
                    }
                    dokumenIndex++;
                }

                $('#btn-add-anggota').on('click', function () { addAnggotaRow(); });
                $('#btn-add-dokumen').on('click', function () { addDokumenRow(); });

                $(document).on('click', '.btn-remove-anggota', function () {
                    var $card = $(this).closest('.anggota-row-item');
                    $card.find('.anggota-kecamatan, .anggota-desa, .anggota-jk, .anggota-jabatan').each(function () {
                        if ($(this).data('select2')) {
                            $(this).select2('destroy');
                        }
                    });
                    $card.remove();
                });

                $('#formKelompokWizard').on('submit', function () {
                    $('.anggota-row-item[data-locked="1"]').each(function () {
                        $(this).find('.anggota-jk, .anggota-kecamatan, .anggota-desa').prop('disabled', false);
                    });
                    $('.anggota-row-item[data-existing-anggota="1"]').each(function () {
                        $(this).find('.anggota-nik, .anggota-nama, .anggota-jk, .anggota-kecamatan, .anggota-desa').prop('disabled', false);
                    });
                });

                $(document).on('click', '.btn-remove-dokumen', function () {
                    var $card = $(this).closest('.dokumen-row-item');
                    $card.find('.dokumen-jenis').each(function () {
                        if ($(this).data('select2')) $(this).select2('destroy');
                    });
                    $card.remove();
                });

                $('#wizardNext1').on('click', function () {
                    var pane = document.querySelector('.wizard-pane[data-pane="1"]');
                    var inputs = pane.querySelectorAll('input, select, textarea');
                    for (var i = 0; i < inputs.length; i++) {
                        if (!inputs[i].checkValidity()) {
                            inputs[i].reportValidity();
                            return;
                        }
                    }
                    setStep(2);
                });

                $('#wizardNext2').on('click', function () { setStep(3); });

                $('[data-wizard-back]').on('click', function () {
                    var from = parseInt($(this).data('wizard-back'), 10);
                    setStep(from - 1);
                });

                @foreach($anggotaInitial as $i => $a)
                addAnggotaRow({
                    organisasi_detail_id: @json($a['organisasi_detail_id'] ?? ''),
                    nik: @json($a['nik'] ?? ''),
                    nama: @json($a['nama'] ?? ''),
                    jk: @json($a['jk'] ?? ''),
                    kecamatan_id: @json($a['kecamatan_id'] ?? ''),
                    desa_id: @json($a['desa_id'] ?? ''),
                    jabatan: @json($a['jabatan'] ?? null),
                    @if(array_key_exists('is_valid', $a) || array_key_exists('validated_at', $a) || array_key_exists('catatan_validasi', $a))
                    is_valid: @json($a['is_valid'] ?? false),
                    validated_at: @json($a['validated_at'] ?? null),
                    catatan_validasi: @json($a['catatan_validasi'] ?? null),
                    @endif
                });
                @endforeach

                @foreach($dokumenInitial as $i => $d)
                addDokumenRow({
                    id: @json($d['id'] ?? null),
                    jenis_dokumen: @json($d['jenis_dokumen'] ?? null),
                    keterangan: @json($d['keterangan'] ?? null),
                    file_name: @json($d['file_name'] ?? null),
                    file_url: @json($d['file_url'] ?? null)
                });
                @endforeach

                @if ($errors->any())
                    @if ($errors->has('nama') || $errors->has('nomor') || $errors->has('jenis') || $errors->has('jenis_kelompok_masyarakat_id') || $errors->has('tgl_pembentukan') || $errors->has('kecamatan_id') || $errors->has('desa_id'))
                        setStep(1);
                    @elseif (collect($errors->keys())->contains(fn ($k) => str_starts_with($k, 'anggota.')))
                        setStep(2);
                    @else
                        setStep(3);
                    @endif
                @endif
            })();
        });
    </script>
@endpush
@include('components.form_validation')
