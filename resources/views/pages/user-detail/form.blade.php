@extends('layouts.layout')
@section('title', 'Lengkapi Data Diri')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Lengkapi Data Diri</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Detail User</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
            $isEdit = (bool) $userDetail;
            $route = $isEdit ? route('user-detail.update') : route('user-detail.store');
            $method = $isEdit ? 'PUT' : 'POST';
            $type = old('type', $userDetail?->type?->value ?? \App\Enums\JenisUser::INDIVIDUAL->value);
            $isIndividual = $type === \App\Enums\JenisUser::INDIVIDUAL->value;
            $isLocked = $isLocked ?? false;
        @endphp

        @if ($isLocked && $userDetail)
            <div class="card mb-4 border-primary">
                <div class="card-body">
                    <h2 class="small-title mb-3">Informasi Verifikasi</h2>
                    <p class="text-muted small mb-3">Data detail tidak dapat diubah karena sudah diverifikasi atau akun aktif.</p>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <span class="text-small text-uppercase text-muted">Status</span>
                            <div>
                                @php $status = $userDetail->verification_status; @endphp
                                @if($status === \App\Enums\VerificationStatus::APPROVED)
                                    <span class="badge bg-success">{{ $status->getDescription() }}</span>
                                @elseif($status === \App\Enums\VerificationStatus::REJECTED)
                                    <span class="badge bg-danger">{{ $status->getDescription() }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $status->getDescription() }}</span>
                                @endif
                            </div>
                        </div>
                        @if($userDetail->verified_at)
                            <div class="col-md-4 mb-2">
                                <span class="text-small text-uppercase text-muted">Tanggal Verifikasi</span>
                                <div>{{ $userDetail->verified_at->translatedFormat('d F Y H:i') }}</div>
                            </div>
                        @endif
                        @if($userDetail->verifiedBy)
                            <div class="col-md-4 mb-2">
                                <span class="text-small text-uppercase text-muted">Diverifikasi Oleh</span>
                                <div>{{ $userDetail->verifiedBy->nama ?? $userDetail->verifiedBy->email ?? '-' }}</div>
                            </div>
                        @endif
                        @if($userDetail->verification_note)
                            <div class="col-12 mt-2">
                                <span class="text-small text-uppercase text-muted">Catatan</span>
                                <div>{{ $userDetail->verification_note }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation" id="formUserDetail">
            @csrf
            @method($method)

            <div class="card mb-5">
                <div class="card-body">
                    <h2 class="small-title mb-4">Jenis & Data Umum</h2>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Jenis User <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required @disabled($isLocked)>
                                @foreach($jenisUserOptions as $opt)
                                    <option value="{{ $opt->value }}" {{ old('type', $userDetail?->type?->value ?? '') == $opt->value ? 'selected' : '' }}>
                                        {{ $opt->getDescription() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama User / Kontak <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_user') is-invalid @enderror" name="nama_user" required
                                   value="{{ old('nama_user', $userDetail?->nama_user ?? auth()->user()->nama ?? '') }}" @readonly($isLocked)/>
                            @error('nama_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">No. Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" maxlength="20"
                                   value="{{ old('phone', $userDetail?->phone ?? auth()->user()->no_telp ?? '') }}" @readonly($isLocked)/>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Kecamatan</label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-select @error('kecamatan_id') is-invalid @enderror" @disabled($isLocked)>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $userDetail?->desa?->kecamatan_id ?? '') == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kecamatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Desa</label>
                            <select name="desa_id" id="desa_id" class="form-select @error('desa_id') is-invalid @enderror" @disabled($isLocked)>
                                <option value="">Pilih Desa</option>
                                @if($userDetail && $userDetail->desa && $userDetail->desa->relationLoaded('kecamatan'))
                                    @foreach($userDetail->desa->kecamatan->desa ?? [] as $d)
                                        <option value="{{ $d->id }}" {{ old('desa_id', $userDetail->desa_id) == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('desa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="2" @readonly($isLocked)>{{ old('alamat', $userDetail?->alamat ?? '') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Perorangan --}}
            <div class="card mb-5" id="card-perorangan" style="{{ $isIndividual ? '' : 'display:none;' }}">
                <div class="card-body">
                    <h2 class="small-title mb-4">Data Perorangan</h2>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_personal') is-invalid @enderror" name="nama_personal" id="nama_personal"
                                   value="{{ old('nama_personal', $userDetail?->nama_personal ?? '') }}" @readonly($isLocked)/>
                            @error('nama_personal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">NIK (16 digit) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik" maxlength="16"
                                   value="{{ old('nik', $userDetail?->nik ?? '') }}" @readonly($isLocked)/>
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">{{ $isLocked ? 'Dokumen KTP' : 'Upload KTP' }}@if(!$isLocked)<span class="text-danger">*</span>@endif</label>
                            @if(!$isLocked)
                                <input type="file" class="form-control @error('file_ktp') is-invalid @enderror" name="file_ktp" accept="image/jpeg,image/png,image/jpg,application/pdf"/>
                            @endif
                            @error('file_ktp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($userDetail && $userDetail->file_ktp)
                                <div class="{{ !$isLocked ? 'mt-2' : '' }}">
                                    <a href="{{ asset('storage/' . $userDetail->file_ktp) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat KTP</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bukan perorangan --}}
            <div class="card mb-5" id="card-lembaga" style="{{ $isIndividual ? 'display:none;' : '' }}">
                <div class="card-body">
                    <h2 class="small-title mb-4">Data Lembaga</h2>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nama Lembaga <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_lembaga') is-invalid @enderror" name="nama_lembaga" id="nama_lembaga"
                                   value="{{ old('nama_lembaga', $userDetail?->nama_lembaga ?? '') }}" @readonly($isLocked)/>
                            @error('nama_lembaga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">{{ $isLocked ? 'Dokumen Surat Kuasa' : 'Upload Surat Kuasa' }}@if(!$isLocked)<span class="text-danger">*</span>@endif</label>
                            @if(!$isLocked)
                                <input type="file" class="form-control @error('file_surat_kuasa') is-invalid @enderror" name="file_surat_kuasa" accept="image/jpeg,image/png,image/jpg,application/pdf"/>
                            @endif
                            @error('file_surat_kuasa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($userDetail && $userDetail->file_surat_kuasa)
                                <div class="{{ !$isLocked ? 'mt-2' : '' }}">
                                    <a href="{{ asset('storage/' . $userDetail->file_surat_kuasa) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Surat</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                @if(!$isLocked)
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                @else
                    <p class="text-muted small mb-2">Data tidak dapat diubah karena sudah diverifikasi atau akun aktif.</p>
                @endif
                @if($userDetail)
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
        $(function () {
            var IND = '{{ \App\Enums\JenisUser::INDIVIDUAL->value }}';
            var kecamatansData = @json($kecamatansData);

            function toggleType() {
                var type = $('#type').val();
                var isInd = (type === IND);
                $('#card-perorangan').toggle(isInd);
                $('#card-lembaga').toggle(!isInd);
                $('#nama_personal').prop('required', isInd);
                $('#nama_lembaga').prop('required', !isInd);
                $('input[name="nik"]').prop('required', isInd);
                $('input[name="file_ktp"]').prop('required', isInd);
                $('input[name="file_surat_kuasa"]').prop('required', !isInd);
            }

            $('#type').on('change', toggleType);
            toggleType();

            function loadDesa(kecamatanId, selectedDesaId) {
                var $desa = $('#desa_id');
                $desa.empty().append('<option value="">Pilih Desa</option>');
                if (!kecamatanId) return;
                var k = kecamatansData.find(function (x) { return x.id === kecamatanId; });
                if (k && k.desa) {
                    k.desa.forEach(function (d) {
                        var sel = (selectedDesaId && d.id === selectedDesaId) ? ' selected' : '';
                        $desa.append('<option value="' + d.id + '"' + sel + '>' + d.nama + '</option>');
                    });
                }
            }

            var initKec = $('#kecamatan_id').val();
            var initDesa = $('#desa_id').val();
            if (initKec) loadDesa(initKec, initDesa);

            $('#kecamatan_id').on('change', function () {
                loadDesa($(this).val(), null);
            });

            $('#kecamatan_id, #desa_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih', allowClear: true });
        });
    </script>
@endpush
@include('components.form_validation')
