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
            // Pastikan nilai jenis_user dari auth selalu string
            $authJenis = auth()->user()?->jenis_user;
            $authJenisValue = $authJenis instanceof \App\Enums\JenisUser ? $authJenis->value : $authJenis;
            // Prioritas: old() -> jenis_user di tabel users -> default INDIVIDUAL
            $type = old('type', $authJenisValue ?? \App\Enums\JenisUser::INDIVIDUAL->value);
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
                    <h2 class="small-title mb-4">Jenis & Data Umum </h2>
                    <div class="row">
                        {{-- <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Jenis User <span class="text-danger">*</span></label>
                            <select id="type" class="form-select" disabled>
                                @foreach($jenisUserOptions as $opt)
                                    <option value="{{ $opt->value }}" {{ $type == $opt->value ? 'selected' : '' }}>
                                        {{ $opt->getDescription() }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="type" value="{{ $type }}">
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <input type="hidden" name="type" value="{{ $type }}">
                        <div class="col-lg-8 col-md-8 col-sm-12 mb-3">
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
                    <h2 class="small-title mb-1">Data Lembaga/Kelompok</h2>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">OPD <span class="text-danger">*</span></label>
                            <select id="opd_id" class="form-select @error('opd_id') is-invalid @enderror" name="opd_id" @disabled($isLocked)>
                                <option value="">Pilih OPD</option>
                                @php
                                    $selectedOpdId = old('opd_id', $userDetail?->organisasi?->opd_id ?? $preselectOpdId ?? '');
                                @endphp
                                @foreach($opds as $opd)
                                    <option value="{{ $opd->id }}" {{ $selectedOpdId == $opd->id ? 'selected' : '' }}>
                                        {{ $opd->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('opd_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase mb-1">Kelompok / Organisasi <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2 organisasi-select-wrapper">
                                <select id="organisasi_id" name="organisasi_id" class="form-select @error('organisasi_id') is-invalid @enderror flex-grow-1" @disabled($isLocked)>
                                    <option value="">Pilih Kelompok / Organisasi</option>
                                </select>
                                <button type="button"
                                        class="btn btn-outline-info rounded-circle btn-icon-only shadow-sm"
                                        id="btnInfoOrganisasi"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalInfoOrganisasi"
                                        disabled
                                        title="Lihat informasi kelompok">
                                    <i data-acorn-icon="info-circle"></i>
                                </button>
                            </div>
                            @php
                                // Secara otomatis pilih 'Tambah Kelompok Baru' saat edit data
                                $isEdit = (bool) $userDetail;
                                // Untuk langsung select organisasi jika sudah aktif
                                $selectedOrganisasiId = old('organisasi_id', $userDetail?->organisasi_id ?? '');
                                $organisasiIsActive = $userDetail?->organisasi && $userDetail->organisasi->is_active;
                            @endphp
                            @if($organisasiIsActive && !$isLocked)
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        setTimeout(function() {
                                            const $org = document.getElementById('organisasi_id');
                                            if ($org) {
                                                $org.value = "{{ $selectedOrganisasiId }}";
                                                if ("createEvent" in document) {
                                                    var evt = document.createEvent("HTMLEvents");
                                                    evt.initEvent("change", false, true);
                                                    $org.dispatchEvent(evt);
                                                } else {
                                                    $org.dispatchEvent(new Event('change'));
                                                }
                                            }
                                        }, 400);
                                    });
                                </script>
                            @elseif($isEdit && !$isLocked)
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        // Tunggu select sudah terisi dari JS utama, lalu set nilainya ke __new__ dan trigger event
                                        setTimeout(function() {
                                            const $org = document.getElementById('organisasi_id');
                                            if ($org) {
                                                $org.value = '__new__';
                                                if ("createEvent" in document) {
                                                    var evt = document.createEvent("HTMLEvents");
                                                    evt.initEvent("change", false, true);
                                                    $org.dispatchEvent(evt);
                                                } else {
                                                    $org.dispatchEvent(new Event('change'));
                                                }
                                            }
                                        }, 400);
                                    });
                                </script>
                            @endif
                            @error('organisasi_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <input type="hidden" name="nama_lembaga" id="nama_lembaga" value="{{ old('nama_lembaga', $userDetail?->nama_lembaga ?? '') }}"/>
                            @error('nama_lembaga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Blok Tambah Kelompok Baru (satu form dengan data diri): data kelompok + anggota + dokumen --}}
                        @if(!$isLocked)
                            @php
                                $org = ($selectedOrganisasiInactive ?? false) ? $userDetail?->organisasi : null;
                                $defaultNamaKelompok = old('nama_kelompok', $org?->nama);
                                $defaultNomorKelompok = old('nomor_kelompok', $org?->nomor);
                                $defaultTglKelompok = old('tgl_pembentukan_kelompok', $org?->tgl_pembentukan?->format('Y-m-d'));
                                $defaultKecamatanKelompok = old('kecamatan_id_kelompok', $org?->kecamatan_id);
                                $defaultDesaKelompok = old('desa_id_kelompok', $org?->desa_id);
                                $defaultAnggota = old('anggota');
                                if ($defaultAnggota === null && $org && $org->organisasiDetail && $org->organisasiDetail->isNotEmpty()) {
                                    $defaultAnggota = $org->organisasiDetail->map(fn($d) => [
                                        'penduduk_id' => $d->penduduk_id,
                                        'jabatan' => $d->jabatan instanceof \App\Enums\JabatanOrganisasi ? $d->jabatan->value : $d->jabatan,
                                    ])->values()->all();
                                }
                                $defaultAnggota = $defaultAnggota ?? [[]];
                                $defaultDokumen = old('dokumen');
                                if ($defaultDokumen === null && $org && $org->organisasiDokumen && $org->organisasiDokumen->isNotEmpty()) {
                                    $defaultDokumen = $org->organisasiDokumen->map(fn($d) => [
                                        'jenis_dokumen' => $d->jenis_dokumen instanceof \App\Enums\JenisDokumen ? $d->jenis_dokumen->value : $d->jenis_dokumen,
                                        'keterangan' => $d->keterangan ?? '',
                                    ])->values()->all();
                                }
                                $defaultDokumen = $defaultDokumen ?? [[]];
                            @endphp
                            <div class="col-12 mb-3" id="wrap-tambah-kelompok-baru" style="display:none;" data-initial-desa-id="{{ $defaultDesaKelompok ?? '' }}">
                                <div class="card border border-primary">
                                    <div class="card-body">
                                        <h3 class="small-title mb-3">Data Kelompok Baru</h3>
                                        <input type="hidden" id="input_tambah_kelompok_baru" name="tambah_kelompok_baru" value="1" disabled>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nama Kelompok <span class="text-danger">*</span></label>
                                                <input type="text" name="nama_kelompok" class="form-control @error('nama_kelompok') is-invalid @enderror" value="{{ $defaultNamaKelompok }}" maxlength="255"/>
                                                @error('nama_kelompok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nomor SK / Akta <span class="text-danger">*</span></label>
                                                <input type="text" name="nomor_kelompok" class="form-control @error('nomor_kelompok') is-invalid @enderror" value="{{ $defaultNomorKelompok }}" maxlength="100"/>
                                                @error('nomor_kelompok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Tanggal Pembentukan <span class="text-danger">*</span></label>
                                                <input type="date" name="tgl_pembentukan_kelompok" class="form-control @error('tgl_pembentukan_kelompok') is-invalid @enderror" value="{{ $defaultTglKelompok }}"/>
                                                @error('tgl_pembentukan_kelompok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                                <select name="kecamatan_id_kelompok" id="kel_kecamatan_id" class="form-select @error('kecamatan_id_kelompok') is-invalid @enderror">
                                                    <option value="">Pilih Kecamatan</option>
                                                    @foreach($kecamatans as $kec)
                                                        <option value="{{ $kec->id }}" {{ $defaultKecamatanKelompok == $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>
                                                    @endforeach
                                                </select>
                                                @error('kecamatan_id_kelompok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Desa <span class="text-danger">*</span></label>
                                                <select name="desa_id_kelompok" id="kel_desa_id" class="form-select @error('desa_id_kelompok') is-invalid @enderror">
                                                    <option value="">Pilih Desa</option>
                                                </select>
                                                @error('desa_id_kelompok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <hr class="my-4">
                                        <h4 class="small-title mb-2">Anggota Kelompok</h4>
                                        <div id="anggota-container">
                                            @php $oldAnggota = $defaultAnggota; @endphp
                                            @foreach($oldAnggota as $idx => $a)
                                            <div class="row align-items-end mb-2 row-anggota">
                                                <div class="col-md-5">
                                                    <label class="form-label small">Penduduk</label>
                                                    <select name="anggota[{{ $idx }}][penduduk_id]" class="form-select form-select-sm select-penduduk">
                                                        <option value="">Pilih</option>
                                                        @foreach($penduduks ?? [] as $p)
                                                            <option value="{{ $p->id }}" {{ (isset($a['penduduk_id']) && $a['penduduk_id'] == $p->id) ? 'selected' : '' }}>{{ $p->nama }} — {{ $p->nik }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">Jabatan</label>
                                                    <select name="anggota[{{ $idx }}][jabatan]" class="form-select form-select-sm">
                                                        <option value="">Pilih</option>
                                                        @foreach(\App\Enums\JabatanOrganisasi::cases() as $j)
                                                            <option value="{{ $j->value }}" {{ (isset($a['jabatan']) && $a['jabatan'] == $j->value) ? 'selected' : '' }}>{{ $j->getDescription() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-sm btn-danger btn-hapus-anggota">X</button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btnTambahAnggotaRow"><i data-acorn-icon="plus"></i> Tambah Anggota</button>
                                        <hr class="my-4">
                                        <h4 class="small-title mb-2">Dokumen Kelompok</h4>
                                        <div id="dokumen-container">
                                            @php $oldDokumen = $defaultDokumen; @endphp
                                            @foreach($oldDokumen as $idx => $d)
                                            <div class="row align-items-end mb-2 row-dokumen">
                                                <div class="col-md-3">
                                                    <label class="form-label small">Jenis</label>
                                                    <select name="dokumen[{{ $idx }}][jenis_dokumen]" class="form-select form-select-sm">
                                                        <option value="">Pilih</option>
                                                        @foreach(\App\Enums\JenisDokumen::cases() as $j)
                                                            <option value="{{ $j->value }}" {{ (isset($d['jenis_dokumen']) && $d['jenis_dokumen'] == $j->value) ? 'selected' : '' }}>{{ $j->getDescription() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">Keterangan</label>
                                                    <input type="text" name="dokumen[{{ $idx }}][keterangan]" class="form-control form-control-sm" value="{{ $d['keterangan'] ?? '' }}" maxlength="255" placeholder="Keterangan">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">File</label>
                                                    <input type="file" name="dokumen[{{ $idx }}][file]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-sm btn-danger btn-hapus-dokumen">X</button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btnTambahDokumenRow"><i data-acorn-icon="plus"></i> Tambah Dokumen</button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
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

{{-- Modal Info Kelompok --}}
<div class="modal fade" id="modalInfoOrganisasi" tabindex="-1" aria-labelledby="modalInfoOrganisasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInfoOrganisasiLabel">Informasi Kelompok / Organisasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-small text-uppercase text-muted">Nama Kelompok</dt>
                    <dd class="col-sm-8" id="infoOrgNama">-</dd>

                    <dt class="col-sm-4 text-small text-uppercase text-muted">Nomor SK / Akta / Kemenkumham</dt>
                    <dd class="col-sm-8" id="infoOrgNomor">-</dd>

                    <dt class="col-sm-4 text-small text-uppercase text-muted">Tanggal Pembentukan</dt>
                    <dd class="col-sm-8" id="infoOrgTgl">-</dd>

                    <dt class="col-sm-4 text-small text-uppercase text-muted">Wilayah</dt>
                    <dd class="col-sm-8" id="infoOrgWilayah">-</dd>

                    <dt class="col-sm-4 text-small text-uppercase text-muted">Status</dt>
                    <dd class="col-sm-8" id="infoOrgStatus">-</dd>

                    <dt class="col-sm-4 text-small text-uppercase text-muted mt-3">Anggota</dt>
                    <dd class="col-sm-8 mt-3" id="infoOrgAnggotaCount">-</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
    <style>
        /* Teks di kotak terpilih (closed) */
        .select2-container--bootstrap4 .select2-selection--single,
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            color: #212529 !important;
        }

        /* Teks option saat cursor/hover di list dropdown — warna hitam */
        .select2-container--bootstrap4 .select2-results__option--highlighted,
        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected],
        .select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
            color: #212529 !important;
        }

        .select2-results__option--highlighted {
            color: #212529 !important;
        }

        /* Tombol info bulat di samping select organisasi */
        .btn-icon-only {
            width: 2.35rem;
            height: 2.35rem;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .organisasi-select-wrapper .select2-container {
            flex: 1 1 auto;
        }
    </style>
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
                $('#opd_id').prop('required', !isInd);
                $('#organisasi_id').prop('required', !isInd);
                $('input[name="nik"]').prop('required', isInd);
                $('input[name="file_ktp"]').prop('required', isInd);
                $('input[name="file_surat_kuasa"]').prop('required', !isInd);
            }

            $('#type').on('change', toggleType);
            toggleType();

            @if(!$isLocked && (($selectedOrganisasiInactive ?? false) || $errors->has('nama_kelompok') || $errors->has('nomor_kelompok')))
                setTimeout(function () {
                    var el = document.getElementById('wrap-tambah-kelompok-baru');
                    if (el) { el.style.display = ''; el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
                }, 300);
            @endif

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

            var opdsData = @json($opdsData);

            var NEW_KELOMPOK_VAL = '__new__';
            function loadOrganisasi(opdId, selectedOrganisasiId) {
                var $org = $('#organisasi_id');
                $org.empty().append('<option value="">Pilih Kelompok / Organisasi</option>');
                if (!opdId) {
                    $('#nama_lembaga').val('');
                    $('#wrap-tambah-kelompok-baru').hide();
                    return;
                }
                $org.append('<option value="' + NEW_KELOMPOK_VAL + '">➕ Tambah Kelompok Baru</option>');
                var o = opdsData.find(function (x) { return x.id === opdId; });
                if (o && o.organisasi) {
                    o.organisasi.forEach(function (org) {
                        var sel = (selectedOrganisasiId && org.id === selectedOrganisasiId) ? ' selected' : '';
                        $org.append('<option value="' + org.id + '"' + sel + '>' + org.nama + '</option>');
                    });
                }
            }

            function toggleWrapTambahKelompok() {
                var orgId = $('#organisasi_id').val();
                if (orgId === NEW_KELOMPOK_VAL) {
                    $('#wrap-tambah-kelompok-baru').show();
                    $('#input_tambah_kelompok_baru').prop('disabled', false);
                    $('#organisasi_id').removeAttr('required');
                } else {
                    $('#wrap-tambah-kelompok-baru').hide();
                    $('#input_tambah_kelompok_baru').prop('disabled', true);
                    $('#organisasi_id').prop('required', true);
                }
            }

            function findSelectedOrganisasi(opdId, orgId) {
                if (!opdId || !orgId) return null;
                var opdFound = opdsData.find(function (x) { return x.id === opdId; });
                if (!opdFound || !opdFound.organisasi) return null;
                return opdFound.organisasi.find(function (o) { return o.id === orgId; }) || null;
            }

            function fillOrganisasiDetail(org) {
                if (!org) {
                    $('#infoOrgNama').text('-');
                    $('#infoOrgNomor').text('-');
                    $('#infoOrgTgl').text('-');
                    $('#infoOrgWilayah').text('-');
                    $('#infoOrgStatus').text('-');
                    $('#infoOrgAnggotaCount').text('-');
                    $('#btnInfoOrganisasi').prop('disabled', true);
                    return;
                }
                $('#infoOrgNama').text(org.nama || '-');
                $('#infoOrgNomor').text(org.nomor || '-');
                $('#infoOrgTgl').text(org.tgl_pembentukan || '-');
                var wilayah = '';
                if (org.kecamatan) {
                    wilayah += org.kecamatan;
                }
                if (org.desa) {
                    wilayah += (wilayah ? ' / ' : '') + org.desa;
                }
                $('#infoOrgWilayah').text(wilayah || '-');
                $('#infoOrgStatus').text(org.is_active ? 'Aktif' : 'Nonaktif');

                var count = typeof org.anggota_count !== 'undefined' ? org.anggota_count : null;
                if (count === null) {
                    $('#infoOrgAnggotaCount').text('-');
                } else {
                    $('#infoOrgAnggotaCount').text(count + ' orang');
                }

                $('#btnInfoOrganisasi').prop('disabled', false);
            }

            var initOpd = $('#opd_id').val();
            var initOrganisasi = '{{ (old("tambah_kelompok_baru") || ($selectedOrganisasiInactive ?? false)) ? "__new__" : old("organisasi_id", $userDetail?->organisasi_id ?? "") }}';
            if (initOpd) {
                loadOrganisasi(initOpd, initOrganisasi);
                toggleWrapTambahKelompok();
                var selectedOrg = findSelectedOrganisasi(initOpd, initOrganisasi);
                if (selectedOrg) {
                    $('#nama_lembaga').val(selectedOrg.nama);
                    fillOrganisasiDetail(selectedOrg);
                } else {
                    fillOrganisasiDetail(null);
                }
            } else {
                fillOrganisasiDetail(null);
            }

            $('#opd_id').on('change', function () {
                var opdId = $(this).val();
                loadOrganisasi(opdId, null);
                $('#nama_lembaga').val('');
                fillOrganisasiDetail(null);
            });

            $('#organisasi_id').on('change', function () {
                var opdId = $('#opd_id').val();
                var orgId = $(this).val();
                toggleWrapTambahKelompok();
                if (orgId === NEW_KELOMPOK_VAL) {
                    $('#nama_lembaga').val('');
                    fillOrganisasiDetail(null);
                    return;
                }
                var org = findSelectedOrganisasi(opdId, orgId);
                $('#nama_lembaga').val(org ? org.nama : '');
                fillOrganisasiDetail(org);
            });

            $('#btnInfoOrganisasi').on('click', function () {
                var opdId = $('#opd_id').val();
                var orgId = $('#organisasi_id').val();
                var org = findSelectedOrganisasi(opdId, orgId);
                fillOrganisasiDetail(org);
            });

            $('#opd_id, #organisasi_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih', allowClear: true });

            // Desa untuk blok "Tambah Kelompok Baru"
            var $wrapTambahKelompok = $('#wrap-tambah-kelompok-baru');
            if ($wrapTambahKelompok.length) {
                function loadKelDesa(kecamatanId, selectedDesaId) {
                    var $desa = $('#kel_desa_id');
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
                $('#kel_kecamatan_id').on('change', function () { loadKelDesa($(this).val(), null); });
                var kelKec = $('#kel_kecamatan_id').val();
                var kelDesa = $('#kel_desa_id').val() || ($wrapTambahKelompok.data('initial-desa-id') || '');
                if (kelKec) loadKelDesa(kelKec, kelDesa);
                if (typeof $.fn.select2 !== 'undefined') {
                    $('#kel_kecamatan_id, #kel_desa_id').select2({ theme: 'bootstrap4', placeholder: 'Pilih', allowClear: true });
                }
            }

            function initSelect2Penduduk($container) {
                if (typeof $.fn.select2 === 'undefined') return;
                var $sel = $container || $(document);
                $sel.find('.select-penduduk').each(function () {
                    var $t = $(this);
                    if ($t.hasClass('select2-hidden-accessible')) return;
                    $t.select2({ theme: 'bootstrap4', placeholder: 'Pilih Penduduk', allowClear: true, width: '100%' });
                });
            }
            initSelect2Penduduk($('#wrap-tambah-kelompok-baru'));

            // Dynamic row: Anggota Kelompok
            var penduduksOptions = @json(($penduduks ?? collect())->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama, 'nik' => $p->nik])->values()->all());
            var jabatanOptions = @json(collect(\App\Enums\JabatanOrganisasi::cases())->map(fn($j) => ['value' => $j->value, 'label' => $j->getDescription()])->values()->all());
            var jenisDokumenOptions = @json(collect(\App\Enums\JenisDokumen::cases())->map(fn($j) => ['value' => $j->value, 'label' => $j->getDescription()])->values()->all());

            $('#btnTambahAnggotaRow').on('click', function () {
                var idx = $('.row-anggota').length;
                var row = '<div class="row align-items-end mb-2 row-anggota">' +
                    '<div class="col-md-5"><label class="form-label small">Penduduk</label><select name="anggota[' + idx + '][penduduk_id]" class="form-select form-select-sm select-penduduk"><option value="">Pilih</option>' +
                    penduduksOptions.map(function(p) { return '<option value="' + p.id + '">' + p.nama + ' — ' + (p.nik || '') + '</option>'; }).join('') +
                    '</select></div>' +
                    '<div class="col-md-4"><label class="form-label small">Jabatan</label><select name="anggota[' + idx + '][jabatan]" class="form-select form-select-sm"><option value="">Pilih</option>' +
                    jabatanOptions.map(function(j) { return '<option value="' + j.value + '">' + j.label + '</option>'; }).join('') +
                    '</select></div>' +
                    '<div class="col-md-3"><button type="button" class="btn btn-sm btn-outline-danger btn-hapus-anggota"><i data-acorn-icon="bin"></i></button></div></div>';
                $('#anggota-container').append(row);
                initSelect2Penduduk($('#anggota-container .row-anggota:last'));
            });
            $(document).on('click', '.btn-hapus-anggota', function () {
                var $row = $(this).closest('.row-anggota');
                var $sel = $row.find('.select-penduduk');
                if ($sel.length && $sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');
                $row.remove();
                $('#anggota-container .row-anggota').each(function (i) {
                    $(this).find('select').attr('name', function (_, n) { return n.replace(/\[\d+\]/, '[' + i + ']'); });
                });
            });

            $('#btnTambahDokumenRow').on('click', function () {
                var idx = $('.row-dokumen').length;
                var row = '<div class="row align-items-end mb-2 row-dokumen">' +
                    '<div class="col-md-3"><label class="form-label small">Jenis</label><select name="dokumen[' + idx + '][jenis_dokumen]" class="form-select form-select-sm"><option value="">Pilih</option>' +
                    jenisDokumenOptions.map(function(j) { return '<option value="' + j.value + '">' + j.label + '</option>'; }).join('') +
                    '</select></div>' +
                    '<div class="col-md-3"><label class="form-label small">Keterangan</label><input type="text" name="dokumen[' + idx + '][keterangan]" class="form-control form-control-sm" maxlength="255" placeholder="Keterangan"></div>' +
                    '<div class="col-md-4"><label class="form-label small">File</label><input type="file" name="dokumen[' + idx + '][file]" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png,.webp"></div>' +
                    '<div class="col-md-2"><button type="button" class="btn btn-sm btn-outline-danger btn-hapus-dokumen"><i data-acorn-icon="bin"></i></button></div></div>';
                $('#dokumen-container').append(row);
            });
            $(document).on('click', '.btn-hapus-dokumen', function () {
                $(this).closest('.row-dokumen').remove();
                $('#dokumen-container .row-dokumen').each(function (i) {
                    $(this).find('select, input').attr('name', function (_, n) { return n.replace(/\[\d+\]/, '[' + i + ']'); });
                });
            });
        });
    </script>
@endpush
@include('components.form_validation')
