@extends('layouts.layout')
@section('title', 'Detail Perorangan')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Detail Perorangan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Detail Perorangan</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
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

        @php
            $route = $peroranganDetail ? route('perorangan-detail.update') : route('perorangan-detail.store');
            $method = $peroranganDetail ? 'PUT' : 'POST';
        @endphp
        <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation" id="formPeroranganDetail">
            @csrf
            @method($method)

            <!-- Card Detail Start -->
            <div class="card mb-5">
                <div class="card-body">
                    <h2 class="small-title mb-4">Detail</h2>
                    <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">NIK <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik"
                                           name="nik" required maxlength="16"
                                           value="{{ old('nik', $peroranganDetail->nik ?? '') }}"/>
                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                           name="nama" required
                                           value="{{ old('nama', $peroranganDetail->nama ?? '') }}"/>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir"
                                           name="tanggal_lahir" required
                                           value="{{ old('tanggal_lahir', $peroranganDetail->tanggal_lahir ?? '') }}"/>
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin', $peroranganDetail->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $peroranganDetail->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Kecamatan <span class="text-danger">*</span></label>
                                    <select name="kecamatan_id" id="kecamatan_id" class="form-control @error('kecamatan_id') is-invalid @enderror" required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach($kecamatans as $kecamatan)
                                            <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $peroranganDetail->desa->kecamatan_id ?? '') == $kecamatan->id ? 'selected' : '' }}>
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
                                        @if($peroranganDetail && $peroranganDetail->desa)
                                            @php
                                                $selectedKecamatanId = $peroranganDetail->desa->kecamatan_id;
                                            @endphp
                                            @foreach($kecamatans->where('id', $selectedKecamatanId)->first()->desa ?? [] as $desa)
                                                <option value="{{ $desa->id }}" {{ old('desa_id', $peroranganDetail->desa_id) == $desa->id ? 'selected' : '' }}>
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
                                    <label class="form-label text-small text-uppercase">Alamat <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $peroranganDetail->alamat ?? '') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Pekerjaan</label>
                                    <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror" id="pekerjaan"
                                           name="pekerjaan"
                                           value="{{ old('pekerjaan', $peroranganDetail->pekerjaan ?? '') }}"/>
                                    @error('pekerjaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Jenis Usaha</label>
                                    <select name="jenis_usaha" id="jenis_usaha" class="form-control @error('jenis_usaha') is-invalid @enderror">
                                        <option value="">Pilih Jenis Usaha</option>
                                        @foreach($jenisUsaha as $usaha)
                                            <option value="{{ $usaha->value }}" {{ old('jenis_usaha', $peroranganDetail->jenis_usaha ?? '') == $usaha->value ? 'selected' : '' }}>
                                                {{ $usaha->getDescription() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_usaha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Penghasilan</label>
                                    <input type="number" class="form-control @error('penghasilan') is-invalid @enderror" id="penghasilan"
                                           name="penghasilan" step="0.01" min="0"
                                           value="{{ old('penghasilan', $peroranganDetail->penghasilan ?? '') }}"/>
                                    @error('penghasilan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                    </div>
                </div>
            </div>
            <!-- Card Detail End -->

            <!-- Card Dokumen Start -->
            <div class="card mb-5">
                <div class="card-body">
                    <h2 class="small-title mb-4">Dokumen</h2>
                    <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Upload KTP</label>
                                    <input type="file" class="form-control @error('ktp') is-invalid @enderror" id="ktp"
                                           name="ktp" accept="image/jpeg,image/png,application/pdf"/>
                                    @error('ktp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($peroranganDetail && $peroranganDetail->getFirstMedia(\App\Models\PeroranganDetail::COLLECTION_KTP))
                                        <div class="mt-2">
                                            <a href="{{ $peroranganDetail->getFirstMedia(\App\Models\PeroranganDetail::COLLECTION_KTP)->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i data-acorn-icon="eye"></i> Lihat KTP
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Upload KK</label>
                                    <input type="file" class="form-control @error('kk') is-invalid @enderror" id="kk"
                                           name="kk" accept="image/jpeg,image/png,application/pdf"/>
                                    @error('kk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($peroranganDetail && $peroranganDetail->getFirstMedia(\App\Models\PeroranganDetail::COLLECTION_KK))
                                        <div class="mt-2">
                                            <a href="{{ $peroranganDetail->getFirstMedia(\App\Models\PeroranganDetail::COLLECTION_KK)->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i data-acorn-icon="eye"></i> Lihat KK
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Upload Foto Rumah/Usaha</label>
                                    <input type="file" class="form-control @error('foto_rumah_usaha') is-invalid @enderror" id="foto_rumah_usaha"
                                           name="foto_rumah_usaha" accept="image/jpeg,image/png"/>
                                    @error('foto_rumah_usaha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($peroranganDetail && $peroranganDetail->getFirstMedia(\App\Models\PeroranganDetail::COLLECTION_FOTO_RUMAH_USAHA))
                                        <div class="mt-2">
                                            <a href="{{ $peroranganDetail->getFirstMedia(\App\Models\PeroranganDetail::COLLECTION_FOTO_RUMAH_USAHA)->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i data-acorn-icon="eye"></i> Lihat Foto
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Upload Surat Keterangan Tidak Mampu</label>
                                    <input type="file" class="form-control @error('surat_keterangan_tidak_mampu') is-invalid @enderror" id="surat_keterangan_tidak_mampu"
                                           name="surat_keterangan_tidak_mampu" accept="image/jpeg,image/png,application/pdf"/>
                                    @error('surat_keterangan_tidak_mampu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($peroranganDetail && $peroranganDetail->getFirstMedia(\App\Models\PeroranganDetail::COLLECTION_SURAT_KETERANGAN_TIDAK_MAMPU))
                                        <div class="mt-2">
                                            <a href="{{ $peroranganDetail->getFirstMedia(\App\Models\PeroranganDetail::COLLECTION_SURAT_KETERANGAN_TIDAK_MAMPU)->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i data-acorn-icon="eye"></i> Lihat Surat
                                            </a>
                                        </div>
                                    @endif
                                </div>
                    </div>
                </div>
            </div>
            <!-- Card Dokumen End -->

            <!-- Card Validasi Sosial Start -->
            <div class="card mb-5">
                <div class="card-body">
                    <h2 class="small-title mb-4">Validasi Sosial</h2>
                    <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Terdaftar pada DTKS</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status_dtks" name="status_dtks" 
                                               {{ old('status_dtks', $peroranganDetail->status_dtks ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_dtks">
                                            Ya, terdaftar pada DTKS
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Penerima Bantuan Lain</label>
                                    <input type="text" class="form-control @error('penerima_bantuan_lain') is-invalid @enderror" id="penerima_bantuan_lain"
                                           name="penerima_bantuan_lain" placeholder="Misal: PKH, BPNT, dll"
                                           value="{{ old('penerima_bantuan_lain', $peroranganDetail->penerima_bantuan_lain ?? '') }}"/>
                                    @error('penerima_bantuan_lain')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label text-small text-uppercase">Jumlah Tanggungan</label>
                                    <input type="number" class="form-control @error('jumlah_tanggungan') is-invalid @enderror" id="jumlah_tanggungan"
                                           name="jumlah_tanggungan" min="0"
                                           value="{{ old('jumlah_tanggungan', $peroranganDetail->jumlah_tanggungan ?? 0) }}"/>
                                    @error('jumlah_tanggungan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                    </div>
                </div>
            </div>
            <!-- Card Validasi Sosial End -->

            <div class="mb-5">
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                @if($peroranganDetail)
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Kembali</a>
                @endif
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
@endpush

@push('js_page')
    <script>
        $(document).ready(function () {
            // Data kecamatan dan desa dari server
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
                        })->values()->all()
                    ];
                })->values()->all();
            @endphp
            var kecamatansData = @json($kecamatansData);

            // Function untuk load desa berdasarkan kecamatan
            function loadDesaByKecamatan(kecamatanId, selectedDesaId = null) {
                var desaSelect = $('#desa_id');
                
                // Destroy Select2 untuk update opsi
                if (desaSelect.data('select2')) {
                    desaSelect.select2('destroy');
                }
                
                desaSelect.empty();
                desaSelect.append('<option value="">Pilih Desa</option>');
                
                if (kecamatanId) {
                    var selectedKecamatan = kecamatansData.find(function(k) {
                        return k.id === kecamatanId;
                    });
                    
                    if (selectedKecamatan && selectedKecamatan.desa) {
                        selectedKecamatan.desa.forEach(function(desa) {
                            var selected = selectedDesaId && selectedDesaId === desa.id ? 'selected' : '';
                            desaSelect.append('<option value="' + desa.id + '" ' + selected + '>' + desa.nama + '</option>');
                        });
                    }
                }
                
                // Re-initialize Select2
                desaSelect.select2({
                    theme: 'bootstrap4',
                    placeholder: 'Pilih Desa',
                    allowClear: true,
                });
            }

            // Initialize Select2 untuk kecamatan
            $('#kecamatan_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Kecamatan',
                allowClear: true,
            });

            // Initialize Select2 untuk desa
            $('#desa_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Desa',
                allowClear: true,
            });

            // Initialize Select2 untuk jenis kelamin
            $('#jenis_kelamin').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Jenis Kelamin',
                allowClear: true,
            });

            // Initialize Select2 untuk jenis usaha
            $('#jenis_usaha').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Jenis Usaha',
                allowClear: true,
            });

            // Load desa saat pertama kali jika ada kecamatan yang sudah dipilih (edit mode)
            var initialKecamatanId = $('#kecamatan_id').val();
            var initialDesaId = $('#desa_id').val();
            if (initialKecamatanId) {
                loadDesaByKecamatan(initialKecamatanId, initialDesaId);
            }

            // Load desa berdasarkan kecamatan saat perubahan
            $('#kecamatan_id').on('change', function() {
                var kecamatanId = $(this).val();
                if (kecamatanId) {
                    loadDesaByKecamatan(kecamatanId);
                } else {
                    // Reset desa jika kecamatan dihapus
                    var desaSelect = $('#desa_id');
                    if (desaSelect.data('select2')) {
                        desaSelect.select2('destroy');
                    }
                    desaSelect.empty();
                    desaSelect.append('<option value="">Pilih Desa</option>');
                    desaSelect.select2({
                        theme: 'bootstrap4',
                        placeholder: 'Pilih Desa',
                        allowClear: true,
                    });
                }
            });
        });
    </script>
@endpush

@include('components.form_validation')

