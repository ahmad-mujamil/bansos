@extends('layouts.layout')
@section('title', 'Detail Pengguna')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Detail Pengguna</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('verifikasi-pengguna.index') }}">Verifikasi Pengguna</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Detail</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-2">
                    <a href="{{ route('verifikasi-pengguna.index') }}" class="btn btn-outline-secondary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    @if(!$user->is_active && $user->userDetail)
                        <a href="{{ route('verifikasi-pengguna.aktifkan', $user->id) }}" data-confirm-aktifkan="true" class="btn btn-success btn-icon btn-icon-start w-100 w-md-auto">
                            <i data-acorn-icon="check"></i>
                            <span>Aktifkan Pengguna</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="small-title mb-3">Data Akun</h2>
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label class="form-label text-muted text-small text-uppercase">Nama</label>
                        <p class="mb-0">{{ $user->nama }}</p>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label class="form-label text-muted text-small text-uppercase">Email</label>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label class="form-label text-muted text-small text-uppercase">Username</label>
                        <p class="mb-0">{{ $user->username }}</p>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label class="form-label text-muted text-small text-uppercase">Role</label>
                        <p class="mb-0">{{ $user->role->getDescription() }}</p>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label class="form-label text-muted text-small text-uppercase">Status Akun</label>
                        <p class="mb-0">
                            @if($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Belum aktif</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($user->userDetail)
            @php $d = $user->userDetail; @endphp
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="small-title mb-3">Data Detail (Profil)</h2>
                    <div class="row">
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label text-muted text-small text-uppercase">Jenis User</label>
                            <p class="mb-0">{{ $d->type->getDescription() }}</p>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label text-muted text-small text-uppercase">Nama User / Kontak</label>
                            <p class="mb-0">{{ $d->nama_user ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label text-muted text-small text-uppercase">No. Telepon</label>
                            <p class="mb-0">{{ $d->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label text-muted text-small text-uppercase">Desa</label>
                            <p class="mb-0">{{ $d->desa?->nama ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label text-muted text-small text-uppercase">Kecamatan</label>
                            <p class="mb-0">{{ $d->desa?->kecamatan?->nama ?? '-' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted text-small text-uppercase">Alamat</label>
                            <p class="mb-0">{{ $d->alamat ?? '-' }}</p>
                        </div>
                        @if($d->type->value === \App\Enums\JenisUser::INDIVIDUAL->value)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="form-label text-muted text-small text-uppercase">Nama Lengkap (Personal)</label>
                                <p class="mb-0">{{ $d->nama_personal ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="form-label text-muted text-small text-uppercase">NIK</label>
                                <p class="mb-0">{{ $d->nik ?? '-' }}</p>
                            </div>
                            @if($d->file_ktp)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label class="form-label text-muted text-small text-uppercase">KTP</label>
                                    <p class="mb-0"><a href="{{ asset('storage/' . $d->file_ktp) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat berkas</a></p>
                                </div>
                            @endif
                        @else
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="form-label text-muted text-small text-uppercase">Nama Lembaga</label>
                                <p class="mb-0">{{ $d->nama_lembaga ?? '-' }}</p>
                            </div>
                            @if($d->file_surat_kuasa)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <label class="form-label text-muted text-small text-uppercase">Surat Kuasa</label>
                                    <p class="mb-0"><a href="{{ asset('storage/' . $d->file_surat_kuasa) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat berkas</a></p>
                                </div>
                            @endif
                        @endif
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label text-muted text-small text-uppercase">Status Verifikasi Detail</label>
                            <p class="mb-0">
                                @if($d->verification_status->value === 'approved')
                                    <span class="badge bg-success">{{ $d->verification_status->getDescription() }}</span>
                                @elseif($d->verification_status->value === 'rejected')
                                    <span class="badge bg-danger">{{ $d->verification_status->getDescription() }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $d->verification_status->getDescription() }}</span>
                                @endif
                            </p>
                        </div>
                        @if($d->verification_note)
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted text-small text-uppercase">Catatan Verifikasi</label>
                                <p class="mb-0">{{ $d->verification_note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="card mb-4">
                <div class="card-body">
                    <p class="text-muted mb-0">Pengguna ini belum mengisi data detail.</p>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('js_vendor')
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@endpush
@push('js_page')
    <script>
        document.addEventListener('click', function(e) {
            var el = e.target.closest('[data-confirm-aktifkan]');
            if (!el) return;
            e.preventDefault();
            var href = el.getAttribute('href');
            Swal.fire({
                title: 'Aktifkan Pengguna',
                text: 'Apakah Anda yakin ingin mengaktifkan pengguna ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, aktifkan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) window.location.href = href;
            });
        });
    </script>
@endpush
