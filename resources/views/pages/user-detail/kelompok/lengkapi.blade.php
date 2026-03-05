@extends('layouts.layout')
@section('title', 'Lengkapi Data Kelompok - ' . $organisasi->nama)
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Lengkapi Data Kelompok</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user-detail.create') }}">Lengkapi Data Diri</a></li>
                            <li class="breadcrumb-item active">Lengkapi Kelompok</li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('user-detail.create', ['organisasi_id' => $organisasi->id]) }}" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="check"></i>
                        <span>Selesai, gunakan kelompok ini</span>
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

        {{-- Info Kelompok --}}
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="small-title mb-3">Data Kelompok</h2>
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-small text-uppercase text-muted">Nama</dt>
                    <dd class="col-sm-9">{{ $organisasi->nama }}</dd>
                    <dt class="col-sm-3 text-small text-uppercase text-muted">Nomor SK / Akta</dt>
                    <dd class="col-sm-9">{{ $organisasi->nomor }}</dd>
                    <dt class="col-sm-3 text-small text-uppercase text-muted">Tanggal Pembentukan</dt>
                    <dd class="col-sm-9">{{ $organisasi->tgl_pembentukan?->translatedFormat('d F Y') }}</dd>
                    <dt class="col-sm-3 text-small text-uppercase text-muted">Wilayah</dt>
                    <dd class="col-sm-9">{{ $organisasi->kecamatan?->nama }} / {{ $organisasi->desa?->nama }}</dd>
                </dl>
            </div>
        </div>

        <div class="row">
            {{-- Card Anggota --}}
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="small-title mb-0">Anggota</h2>
                            <button type="button" class="btn btn-sm btn-primary" id="btnTambahAnggota" title="Tambah Anggota">
                                <i data-acorn-icon="plus"></i> Tambah
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-small text-uppercase">Nama</th>
                                        <th class="text-small text-uppercase">NIK</th>
                                        <th class="text-small text-uppercase">Jabatan</th>
                                        <th class="text-small text-uppercase w-5">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anggotas as $a)
                                        <tr>
                                            <td>{{ $a->penduduk?->nama ?? '-' }}</td>
                                            <td>{{ $a->penduduk?->nik ?? '-' }}</td>
                                            <td>{{ $a->jabatan?->getDescription() ?? $a->jabatan }}</td>
                                            <td>
                                                <form action="{{ route('user-detail.kelompok.anggota.destroy', [$organisasi->id, $a->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus anggota ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i data-acorn-icon="bin"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-muted text-center py-3">Belum ada anggota. Klik <strong>Tambah</strong>.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Dokumen --}}
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="small-title mb-3">Dokumen</h2>
                        <p class="text-muted small mb-3">Upload dokumen sesuai jenis di bawah:</p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach(\App\Enums\JenisDokumen::cases() as $jenis)
                                <button type="button" class="btn btn-outline-primary btn-upload-jenis" data-jenis="{{ $jenis->value }}" data-label="{{ $jenis->getDescription() }}">
                                    <i data-acorn-icon="upload"></i> {{ $jenis->getDescription() }}
                                </button>
                            @endforeach
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-small text-uppercase">Jenis</th>
                                        <th class="text-small text-uppercase">Keterangan</th>
                                        <th class="text-small text-uppercase">File</th>
                                        <th class="text-small text-uppercase w-5">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dokumens as $d)
                                        @php $media = $d->getFirstMedia('dokumen'); @endphp
                                        <tr>
                                            <td>{{ $d->jenis_dokumen?->getDescription() ?? $d->jenis_dokumen }}</td>
                                            <td>{{ $d->keterangan }}</td>
                                            <td>
                                                @if($media)
                                                    <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-link p-0">{{ $media->file_name }}</a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('user-detail.kelompok.dokumen.destroy', [$organisasi->id, $d->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i data-acorn-icon="bin"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-muted text-center py-3">Belum ada dokumen. Klik tombol upload di atas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 mb-5">
            <a href="{{ route('user-detail.create', ['organisasi_id' => $organisasi->id]) }}" class="btn btn-primary">
                <i data-acorn-icon="check"></i> Selesai, gunakan kelompok ini
            </a>
            <a href="{{ route('user-detail.create') }}" class="btn btn-outline-secondary">Kembali ke Data Diri</a>
        </div>
    </div>

    {{-- Modal Tambah Anggota --}}
    <div class="modal fade" id="modalTambahAnggota" tabindex="-1" aria-labelledby="modalTambahAnggotaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAnggotaLabel">Tambah Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user-detail.kelompok.anggota.store', $organisasi->id) }}" method="POST" id="formTambahAnggota">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="modal_penduduk_id">Penduduk / Anggota <span class="text-danger">*</span></label>
                            <select name="penduduk_id" id="modal_penduduk_id" class="form-select @error('penduduk_id') is-invalid @enderror" required>
                                <option value="">Pilih Penduduk</option>
                                @foreach($penduduks as $p)
                                    <option value="{{ $p->id }}" {{ old('penduduk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} — {{ $p->nik }}</option>
                                @endforeach
                            </select>
                            @error('penduduk_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="modal_jabatan">Jabatan <span class="text-danger">*</span></label>
                            <select name="jabatan" id="modal_jabatan" class="form-select @error('jabatan') is-invalid @enderror" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach(\App\Enums\JabatanOrganisasi::cases() as $j)
                                    <option value="{{ $j->value }}" {{ old('jabatan') == $j->value ? 'selected' : '' }}>{{ $j->getDescription() }}</option>
                                @endforeach
                            </select>
                            @error('jabatan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Upload Dokumen (satu modal, jenis di-set dari tombol) --}}
    <div class="modal fade" id="modalUploadDokumen" tabindex="-1" aria-labelledby="modalUploadDokumenLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadDokumenLabel">Upload Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user-detail.kelompok.dokumen.store', $organisasi->id) }}" method="POST" enctype="multipart/form-data" id="formUploadDokumen">
                    @csrf
                    <input type="hidden" name="jenis_dokumen" id="upload_jenis_dokumen" value="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Jenis Dokumen</label>
                            <input type="text" class="form-control bg-light" id="upload_jenis_label" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" name="keterangan" class="form-control" required maxlength="255" placeholder="Contoh: NPWP atas nama organisasi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                            <small class="text-muted">PDF, JPG, PNG, WebP. Maks. 10 MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
        $(function () {
            var modalAnggotaEl = document.getElementById('modalTambahAnggota');
            var modalAnggota = modalAnggotaEl ? new bootstrap.Modal(modalAnggotaEl) : null;

            // Buka modal tambah anggota jika ada error validasi
            @if ($errors->has('penduduk_id') || $errors->has('jabatan'))
                if (modalAnggota) modalAnggota.show();
            @endif

            // Tombol Tambah Anggota → buka modal
            $('#btnTambahAnggota').on('click', function () {
                $('#formTambahAnggota')[0].reset();
                $('#modal_penduduk_id, #modal_jabatan').val('').trigger('change');
                if (modalAnggota) modalAnggota.show();
            });

            // Select2 di modal anggota (opsional: jika tidak ada, form tetap jalan)
            if (typeof $.fn.select2 !== 'undefined') {
                $('#modal_penduduk_id').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Pilih Penduduk',
                    allowClear: true,
                    dropdownParent: $('#modalTambahAnggota')
                });
                $('#modal_jabatan').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Pilih Jabatan',
                    allowClear: false,
                    dropdownParent: $('#modalTambahAnggota')
                });
            }

            // Tombol upload per jenis dokumen → set jenis lalu buka modal
            $('.btn-upload-jenis').on('click', function () {
                var jenis = $(this).data('jenis');
                var label = $(this).data('label');
                $('#upload_jenis_dokumen').val(jenis);
                $('#upload_jenis_label').val(label);
                $('#modalUploadDokumenLabel').text('Upload ' + label);
                $('#formUploadDokumen')[0].reset();
                $('#upload_jenis_dokumen').val(jenis);
                $('#upload_jenis_label').val(label);
                new bootstrap.Modal(document.getElementById('modalUploadDokumen')).show();
            });
        });
    </script>
@endpush
