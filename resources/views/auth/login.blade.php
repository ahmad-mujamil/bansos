@extends('layouts.layout_full')

@section('content_left')
    <div class="d-flex flex-column justify-content-center h-100 px-5 py-5">

        {{-- Page Title --}}
        <div class="page-title-container mb-4">
            <h4 class="mb-1">Pengajuan Bantuan Masyarakat</h4>
            <div class="text-muted font-heading text-small">Ringkasan data pengajuan publik</div>
        </div>

        {{-- Stats Row --}}
        <div class="mb-4">
            <h2 class="small-title mb-3">Organisasi / Kelompok</h2>
            <div class="row g-3">
                <div class="col-12 col-sm-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted text-small text-uppercase">Total Kelompok Aktif</span>
                                <i data-acorn-icon="file-text" class="text-primary"></i>
                            </div>
                            <div class="cta-1 text-primary">{{ number_format(collect($organisasiAktif)->sum()) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted text-small text-uppercase">Kelompok Masyarakat</span>
                                <i data-acorn-icon="send" class="text-warning"></i>
                            </div>
                            <div class="cta-1 text-warning">{{ number_format($organisasiAktif['KLP'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted text-small text-uppercase">Yayasan</span>
                                <i data-acorn-icon="check-circle" class="text-success"></i>
                            </div>
                            <div class="cta-1 text-success">{{ number_format($organisasiAktif['YYS'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted text-small text-uppercase">Tempat Ibadah</span>
                                <i data-acorn-icon="school" class="text-danger"></i>
                            </div>
                            <div class="cta-1 text-danger">{{ number_format($organisasiAktif['TIB'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted text-small text-uppercase">Organisasi</span>
                                <i data-acorn-icon="building" class="text-info"></i>
                            </div>
                            <div class="cta-1 text-info">{{ number_format($organisasiAktif['ORG'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted text-small text-uppercase">Instansi</span>
                                <i data-acorn-icon="building-large" class="text-secondary"></i>
                            </div>
                            <div class="cta-1 text-secondary">{{ number_format($organisasiAktif['INS'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('content_right')
    <!-- Right Side Start -->
    <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
            <div class="sw-lg-50 px-6">
                <div class="sh-11 mb-6">
                    <a href="">
                        <img src="{{ asset('img/logo/logo-wide.png') }}" alt="logo" class="img-fluid"/>

                    </a>
                </div>
                <br>
                <div class="mb-5">
                    <p class="h6">Gunakan Username dan Password untuk masuk kedalam aplikasi</p>
                </div>
                <div>
                    <form id="loginForm" class="tooltip-end-bottom" novalidate action="{{ route('login') }}" method="post">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="user"></i>
                            <input class="form-control @error('username') is-invalid @enderror" placeholder="Username" name="username" id="username" value="{{ old('username') }}" autocomplete="username" />
                        </div>
                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="lock-off"></i>
                            <input class="form-control pe-7 @error('password') is-invalid @enderror" name="password" type="password" placeholder="Password" autocomplete="current-password" />
                            <!-- <a class="text-small position-absolute t-3 e-3" href="#">Lupa password?</a>
                            <a href=""><span class="badge rounded-pill bg-foreground mt-2">* Pertanyaan yang paling sering diajukan (FAQ's)</span></a>
           -->
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary w-100">Masuk</button>
                    </form>
                    <br>
                    <div class="text-center">
                        <p class="text-small">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
                    </div>
                    <br><br>
                    <span class="badge rounded-pill bg-foreground mt-2">Copyright &copy;2026. Pemerintah Kabupaten Lombok Barat</span>

                </div>
            </div>
        </div>
    <!-- Right Side End -->
@endsection
