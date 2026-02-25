@extends('layouts.layout')
@section('title', 'Dashboard')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h4>Selamat Datang, <b>{{ auth()->user()->nama??'-' }}</b></h4>
                    <div class="text-muted font-heading text-small">Halaman Beranda</div>
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        @if(auth()->user()->is_user() && !auth()->user()->userDetail)
        <!-- Alert Lengkapi Data Diri untuk Verifikasi Start -->
        <div class="alert-lengkapi-detail mb-4">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%); border-radius: 16px;">
                <div class="card-body p-4 p-lg-5 position-relative">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-md-auto">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-3" style="width: 72px; height: 72px; background: rgba(255,255,255,0.2);">
                                <i data-acorn-icon="user" data-acorn-size="40" style="color: #fff;"></i>
                            </div>
                        </div>
                        <div class="col-12 col-md">
                            <h5 class="mb-2 fw-bold text-white">
                                <i data-acorn-icon="information-circle" data-acorn-size="22" class="me-2 align-middle"></i>
                                Lengkapi Data Diri untuk Verifikasi
                            </h5>
                            <p class="mb-0 mb-md-2 text-white" style="font-size: 0.95rem; line-height: 1.5; opacity: 0.95;">
                                Untuk dapat diverifikasi oleh administrator, silakan lengkapi data diri Anda terlebih dahulu. Klik tombol di samping untuk mengisi formulir.
                            </p>
                        </div>
                        <div class="col-12 col-md-auto text-md-end">
                            <a href="{{ route('user-detail.create') }}" class="btn btn-light btn-lg px-4">
                                <i data-acorn-icon="edit" data-acorn-size="18" class="me-2"></i>
                                Lengkapi Data Diri
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alert Lengkapi Data Diri untuk Verifikasi End -->
        @endif

        @if(!auth()->user()->is_active)
        <!-- Alert Akun Belum Diverifikasi Start -->
        <div class="alert-verification mb-4">
            <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #f5d020 0%, #ebb71a 50%, #d4a010 100%); border-radius: 16px;">
                <div class="card-body p-4 p-lg-5 position-relative">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-md-auto">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-3" style="width: 72px; height: 72px; background: rgba(0,0,0,0.15);">
                                <i data-acorn-icon="shield" data-acorn-size="40" style="color: #1a1a1a;"></i>
                            </div>
                        </div>
                        <div class="col-12 col-md">
                            <h5 class="mb-2 fw-bold" style="color: #1a1a1a;">
                                <i data-acorn-icon="information-circle" data-acorn-size="22" class="me-2 align-middle" style="color: #1a1a1a;"></i>
                                Akun Belum Diverifikasi
                            </h5>
                            <p class="mb-0 mb-md-2" style="font-size: 0.95rem; line-height: 1.5; color: rgba(0,0,0,0.8);">
                                Akun Anda sedang dalam proses verifikasi oleh administrator. Setelah diverifikasi, Anda dapat mengakses semua fitur secara penuh. Mohon menunggu atau hubungi admin jika membutuhkan bantuan.
                            </p>
                        </div>
                        <div class="col-12 col-md-auto text-md-end">
                            <span class="badge px-3 py-2 fs-6 fw-semibold" style="background: rgba(0,0,0,0.2); color: #1a1a1a;">
                                <i data-acorn-icon="clock" data-acorn-size="18" class="me-1 align-middle" style="color: #1a1a1a;"></i>
                                Menunggu Verifikasi
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alert Akun Belum Diverifikasi End -->
        @endif

        @php
            $showDetailData = auth()->user()->is_user()
                && auth()->user()->userDetail
                && (!auth()->user()->is_active || auth()->user()->userDetail->verification_status->value !== 'approved');
        @endphp
        @if($showDetailData)
        <!-- Card Detail Data Diri (belum verifikasi / belum active) Start -->
        @php $d = auth()->user()->userDetail; @endphp
        <div class="mb-4">
            <h2 class="small-title">Detail Data Diri</h2>
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Jenis</label>
                            <div class="fw-semibold">{{ $d->type?->getDescription() ?? '-' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Status Verifikasi</label>
                            <div>
                                @if($d->verification_status->value === 'approved')
                                    <span class="badge bg-success">{{ $d->verification_status->getDescription() }}</span>
                                @elseif($d->verification_status->value === 'rejected')
                                    <span class="badge bg-danger">{{ $d->verification_status->getDescription() }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $d->verification_status->getDescription() }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Nama User</label>
                            <div class="fw-semibold">{{ $d->nama_user ?? '-' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Telepon</label>
                            <div class="fw-semibold">{{ $d->phone ?? '-' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted text-small text-uppercase">Alamat</label>
                            <div class="fw-semibold">{{ $d->alamat ?? '-' }}</div>
                        </div>
                        @if($d->desa_id)
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Desa</label>
                            <div class="fw-semibold">{{ $d->desa?->nama ?? '-' }}</div>
                        </div>
                        @endif
                        @if(in_array($d->type?->value ?? '', ['IND', 'KLP']))
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Nama Personal</label>
                            <div class="fw-semibold">{{ $d->nama_personal ?? '-' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">NIK</label>
                            <div class="fw-semibold">{{ $d->nik ?? '-' }}</div>
                        </div>
                        @else
                        <div class="col-12 col-md-6">
                            <label class="text-muted text-small text-uppercase">Nama Lembaga</label>
                            <div class="fw-semibold">{{ $d->nama_lembaga ?? '-' }}</div>
                        </div>
                        @endif
                        @if($d->verification_status->value === 'rejected' && $d->verification_note)
                        <div class="col-12">
                            <label class="text-muted text-small text-uppercase">Catatan Verifikasi</label>
                            <div class="text-danger">{{ $d->verification_note }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <a href="{{ route('user-detail.create') }}" class="btn btn-outline-primary btn-sm">
                            <i data-acorn-icon="edit" data-acorn-size="16" class="me-1"></i>
                            Ubah Data Diri
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Detail Data Diri End -->
        @endif

        @if(auth()->user()->is_active)
        <!-- Dashboard Stats Start -->
        <div class="mb-5">
            <h2 class="small-title mb-3">Dashboard</h2>
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted text-small text-uppercase">Jumlah Pengajuan</span>
                                <i data-acorn-icon="file-text" class="text-primary"></i>
                            </div>
                            <div class="cta-1 text-primary">{{ number_format(24) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted text-small text-uppercase">Verifikasi</span>
                                <i data-acorn-icon="check-circle" class="text-success"></i>
                            </div>
                            <div class="cta-1 text-success">{{ number_format(18) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted text-small text-uppercase">Belum Verifikasi</span>
                                <i data-acorn-icon="clock" class="text-warning"></i>
                            </div>
                            <div class="cta-1 text-warning">{{ number_format(6) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted text-small text-uppercase">Realisasi</span>
                                <i data-acorn-icon="notebook-1" class="text-info"></i>
                            </div>
                            <div class="cta-1 text-info">{{ number_format(12) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dashboard Stats End -->

        <!-- Menu Bantuan Start -->
        <div class="mb-5">
            <h2 class="small-title mb-3">Pilih Jenis Bantuan</h2>
            <p class="text-muted mb-4">Silakan pilih jenis bantuan yang ingin Anda ajukan. Klik kartu untuk melanjutkan.</p>
            <div class="row g-4">
                {{-- Card Hibah --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('pengajuan.create', ['jenis' => 'hibah']) }}" class="text-decoration-none d-block h-100">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                            <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #6366f1 0%, #4f46e5 50%, #4338ca 100%); min-height: 200px;">
                                <div class="p-4 position-relative z-1">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                        <i data-acorn-icon="gift" data-acorn-size="28" class="text-white"></i>
                                    </div>
                                    <h5 class="text-white fw-bold mb-2">Hibah</h5>
                                    <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan hibah untuk mendukung kegiatan atau program yang Anda jalankan.</p>
                                    <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                        Ajukan sekarang
                                        <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                    </span>
                                </div>
                                <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                    <i data-acorn-icon="gift" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- Card Bantuan ke Masyarakat --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('pengajuan.create', ['jenis' => 'bantuan_kelompok']) }}" class="text-decoration-none d-block h-100">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                            <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #0d9488 0%, #0f766e 50%, #115e59 100%); min-height: 200px;">
                                <div class="p-4 position-relative z-1">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                        <i data-acorn-icon="grid-1" data-acorn-size="28" class="text-white"></i>
                                    </div>
                                    <h5 class="text-white fw-bold mb-2">Bantuan ke Masyarakat</h5>
                                    <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan untuk program pemberdayaan dan peningkatan kesejahteraan kelompok masyarakat.</p>
                                    <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                        Ajukan sekarang
                                        <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                    </span>
                                </div>
                                <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                    <i data-acorn-icon="grid-1" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- Card Bantuan Sosial --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('pengajuan.create', ['jenis' => 'bansos']) }}" class="text-decoration-none d-block h-100">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                            <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #ea580c 0%, #c2410c 50%, #9a3412 100%); min-height: 200px;">
                                <div class="p-4 position-relative z-1">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                        <i data-acorn-icon="heart" data-acorn-size="28" class="text-white"></i>
                                    </div>
                                    <h5 class="text-white fw-bold mb-2">Bantuan Sosial</h5>
                                    <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan sosial untuk meringankan beban dan mendukung kebutuhan dasar penerima manfaat.</p>
                                    <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                        Ajukan sekarang
                                        <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                    </span>
                                </div>
                                <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                    <i data-acorn-icon="heart" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <style>
            .menu-bantuan-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
            }
        </style>
        <!-- Menu Bantuan End -->

        

        <!-- Charts End -->
        @endif
    </div>
    <!-- Page Content End -->
@endsection
@push('js_page')
    

@endpush
