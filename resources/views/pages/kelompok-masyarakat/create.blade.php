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
                                <label class="form-label text-small text-uppercase">Jenis <span class="text-danger">*</span></label>
                                <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                                    <option value="">Pilih Jenis</option>
                                    @foreach(\App\Enums\JenisOrganisasi::cases() as $jenisOption)
                                        <option value="{{ $jenisOption->value }}" {{ old('jenis', optional($organisasi)->jenis?->value ?? \App\Enums\JenisOrganisasi::KELOMPOK->value) == $jenisOption->value ? 'selected' : '' }}>
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
                        <p class="text-small text-muted mb-3">Kelola anggota (opsional). Satu penduduk hanya dapat dipilih sekali. Menyimpan formulir ini akan mengganti daftar anggota sesuai isian di bawah.</p>
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
                        <p class="text-small text-muted mb-3">Kelola dokumen (opsional). Format PDF, JPG, PNG, WebP — maks. 10 MB per file. Untuk dokumen yang sudah ada, unggah berkas baru hanya jika ingin mengganti.</p>
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
            <div class="card mb-3 anggota-row-item border">
                <div class="card-body py-3">
                    <div class="row align-items-end">
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <label class="form-label text-small text-uppercase">Penduduk <span class="text-danger">*</span></label>
                            <select class="form-control anggota-penduduk" name="anggota[__IDX__][penduduk_id]" required>
                                <option value="">Pilih Penduduk</option>
                                @foreach($penduduks as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }} — {{ $p->nik }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5 mb-3 mb-lg-0">
                            <label class="form-label text-small text-uppercase">Jabatan <span class="text-danger">*</span></label>
                            <select class="form-control anggota-jabatan" name="anggota[__IDX__][jabatan]" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach(\App\Enums\JabatanOrganisasi::cases() as $jabatanOption)
                                    <option value="{{ $jabatanOption->value }}">{{ $jabatanOption->getDescription() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-1 text-lg-end">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-anggota" title="Hapus baris">&times;</button>
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

            (function () {
                var anggotaIndex = {{ count($anggotaInitial) }};
                var dokumenIndex = {{ count($dokumenInitial) }};

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

                function bindSelect2Anggota($root) {
                    $root.find('.anggota-penduduk').select2({ theme: 'bootstrap4', placeholder: 'Pilih Penduduk', allowClear: true, width: '100%' });
                    $root.find('.anggota-jabatan').select2({ theme: 'bootstrap4', placeholder: 'Pilih Jabatan', allowClear: false, width: '100%' });
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
                    bindSelect2Anggota($el);
                    if (values.penduduk_id) {
                        $el.find('.anggota-penduduk').val(values.penduduk_id).trigger('change');
                    }
                    if (values.jabatan) {
                        $el.find('.anggota-jabatan').val(values.jabatan).trigger('change');
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
                    $card.find('.anggota-penduduk, .anggota-jabatan').each(function () {
                        if ($(this).data('select2')) $(this).select2('destroy');
                    });
                    $card.remove();
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
                addAnggotaRow({ penduduk_id: @json($a['penduduk_id'] ?? null), jabatan: @json($a['jabatan'] ?? null) });
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
                    @if ($errors->has('nama') || $errors->has('nomor') || $errors->has('jenis') || $errors->has('tgl_pembentukan') || $errors->has('kecamatan_id') || $errors->has('desa_id'))
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
