@extends('layouts.layout_full')

@section('content_left')
    <div class="login-hero h-100 position-relative overflow-hidden">
        <div class="login-hero-bg"></div>
        <div class="login-hero-blob login-hero-blob-1"></div>
        <div class="login-hero-blob login-hero-blob-2"></div>
        <div class="login-hero-blob login-hero-blob-3"></div>

        {{-- Floating particles --}}
        <div class="login-particles" aria-hidden="true">
            @for($i = 1; $i <= 18; $i++)
                <span class="particle particle-{{ $i }}"></span>
            @endfor
        </div>

        {{-- Twinkling stars --}}
        <div class="login-stars" aria-hidden="true">
            @for($i = 1; $i <= 14; $i++)
                <span class="star star-{{ $i }}"></span>
            @endfor
        </div>

        <div class="position-relative h-100 d-flex flex-column justify-content-center px-5 py-5" style="z-index: 2;">
            {{-- Brand --}}
            <div class="login-hero-brand mb-5">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill login-hero-pill">
                    <i data-acorn-icon="heart" data-acorn-size="16"></i>
                    <span class="text-uppercase fw-bold" style="letter-spacing: 0.12em; font-size: 0.7rem;">BKAD · Lombok Barat</span>
                </div>
                <h1 class="text-white fw-bold mt-4 mb-2" style="font-size: 2.25rem; line-height: 1.15;">
                    Pengajuan Bantuan<br/>Masyarakat
                </h1>
                <p class="text-white mb-0" style="opacity: 0.85; max-width: 480px;">
                    Layanan terpadu pengajuan bantuan sosial, hibah, dan bantuan kelompok masyarakat di Kabupaten Lombok Barat.
                </p>
            </div>

            {{-- Stats Row --}}
            <div>
                <div class="text-uppercase fw-bold text-white mb-3" style="letter-spacing: 0.1em; font-size: 0.72rem; opacity: 0.85;">
                    Organisasi / Kelompok Aktif
                </div>
                <div class="row g-3">
                    <div class="col-12 col-sm-4">
                        <div class="login-stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="login-stat-label">Total Kelompok</span>
                                <span class="login-stat-icon"><i data-acorn-icon="file-text" data-acorn-size="18"></i></span>
                            </div>
                            <div class="login-stat-value">{{ number_format(collect($organisasiAktif)->sum()) }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="login-stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="login-stat-label">Kelompok Masyarakat</span>
                                <span class="login-stat-icon"><i data-acorn-icon="send" data-acorn-size="18"></i></span>
                            </div>
                            <div class="login-stat-value">{{ number_format($organisasiAktif['KLP'] ?? 0) }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="login-stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="login-stat-label">Yayasan</span>
                                <span class="login-stat-icon"><i data-acorn-icon="check-circle" data-acorn-size="18"></i></span>
                            </div>
                            <div class="login-stat-value">{{ number_format($organisasiAktif['YYS'] ?? 0) }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="login-stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="login-stat-label">Tempat Ibadah</span>
                                <span class="login-stat-icon"><i data-acorn-icon="shop" data-acorn-size="18"></i></span>
                            </div>
                            <div class="login-stat-value">{{ number_format($organisasiAktif['TIB'] ?? 0) }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="login-stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="login-stat-label">Organisasi</span>
                                <span class="login-stat-icon"><i data-acorn-icon="building" data-acorn-size="18"></i></span>
                            </div>
                            <div class="login-stat-value">{{ number_format($organisasiAktif['ORG'] ?? 0) }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="login-stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="login-stat-label">Instansi</span>
                                <span class="login-stat-icon"><i data-acorn-icon="building-large" data-acorn-size="18"></i></span>
                            </div>
                            <div class="login-stat-value">{{ number_format($organisasiAktif['INS'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content_right')
    <!-- Right Side Start -->
    <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center py-5 login-right-panel">
        <div class="register-card-wrapper">
            <div class="register-card">
                {{-- Accent top bar --}}
                <div class="register-card-accent"></div>

                {{-- Header with logo --}}
                <div class="register-card-header">
                    <div class="sh-11 register-logo-wrap">
                        <a href="">
                            <img src="{{ asset('img/logo/logo-wide.png') }}" alt="logo" class="img-fluid login-logo"/>
                        </a>
                    </div>
                </div>

                <div class="mb-2 text-center px-3">
                    <h5 class="fw-bold mb-1" style="color: #0f172a;">Selamat Datang Kembali</h5>
                    <p class="text-muted mb-0" style="font-size: 0.82rem;">
                        Masuk dengan Username dan Password Anda.
                    </p>
                </div>

                <div class="register-card-body">
                    <form id="loginForm" class="tooltip-end-bottom" novalidate action="{{ route('login') }}" method="post">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-start gap-2 border-0 shadow-sm mb-3" role="alert" style="border-radius: 12px;">
                                <i data-acorn-icon="error-hexagon" data-acorn-size="20" class="flex-shrink-0 mt-1"></i>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3 login-field">
                            <label for="username" class="login-field-label">Username</label>
                            <div class="position-relative">
                                <span class="login-field-icon"><i data-acorn-icon="user" data-acorn-size="18"></i></span>
                                <input class="form-control login-input @error('username') is-invalid @enderror"
                                       placeholder="Masukkan username"
                                       name="username"
                                       id="username"
                                       value="{{ old('username') }}"
                                       autocomplete="username" />
                            </div>
                        </div>

                        <div class="mb-3 login-field">
                            <label for="password" class="login-field-label">Password</label>
                            <div class="position-relative">
                                <span class="login-field-icon"><i data-acorn-icon="lock-off" data-acorn-size="18"></i></span>
                                <input class="form-control login-input password-input @error('password') is-invalid @enderror"
                                       name="password"
                                       id="password"
                                       type="password"
                                       placeholder="Masukkan password"
                                       autocomplete="current-password" />
                                <button class="btn position-absolute end-0 top-0 h-100 px-3 password-addon" type="button">
                                    <i data-acorn-icon="eye-off" class="icon-eye-off text-primary"></i>
                                    <i data-acorn-icon="eye" class="icon-eye d-none text-primary"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lg w-100 login-submit mt-2">
                            <i data-acorn-icon="login" data-acorn-size="18" class="me-2 align-middle"></i>
                            Masuk
                        </button>
                    </form>
                </div>

                <div class="text-center px-3 pb-3 pt-2">
                    <p class="text-small text-muted mb-2">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="fw-semibold">Daftar di sini</a>
                    </p>
                    <div class="pt-2" style="border-top: 1px dashed #e5e7eb;">
                        <span class="text-muted" style="font-size: 0.72rem;">
                            &copy; 2026 Pemerintah Kabupaten Lombok Barat
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Right Side End -->
@endsection

@push('css')
<style>
    /* === Login Hero (Left Side) === */
    .login-hero { color: #fff; }
    .login-hero-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 35%, #3b82f6 70%, #1d4ed8 100%);
        z-index: 0;
    }
    .login-hero-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 20% 10%, rgba(255,255,255,0.10) 0, transparent 35%),
            radial-gradient(circle at 85% 80%, rgba(255,255,255,0.08) 0, transparent 40%);
    }
    .login-hero-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0.55;
        pointer-events: none;
        z-index: 0;
    }
    .login-hero-blob-1 {
        width: 320px; height: 320px;
        background: radial-gradient(circle, #60a5fa 0%, rgba(96,165,250,0) 70%);
        top: -80px; right: -80px;
    }
    .login-hero-blob-2 {
        width: 380px; height: 380px;
        background: radial-gradient(circle, #a78bfa 0%, rgba(167,139,250,0) 70%);
        bottom: -120px; left: -120px;
    }
    .login-hero-blob-3 {
        width: 220px; height: 220px;
        background: radial-gradient(circle, #22d3ee 0%, rgba(34,211,238,0) 70%);
        top: 40%; right: 8%;
        opacity: 0.35;
    }
    .login-hero-pill {
        background: rgba(255,255,255,0.18);
        color: #ffffff;
        border: 1px solid rgba(255,255,255,0.25);
        backdrop-filter: blur(8px);
    }

    /* === Floating Particles === */
    .login-particles,
    .login-stars {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    }
    .particle {
        position: absolute;
        bottom: -40px;
        display: block;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.55);
        animation: particle-float linear infinite;
        will-change: transform, opacity;
        opacity: 0;
    }
    @keyframes particle-float {
        0%   { transform: translate3d(0, 0, 0) scale(0.6); opacity: 0; }
        10%  { opacity: 0.85; }
        50%  { transform: translate3d(20px, -52vh, 0) scale(1); opacity: 0.9; }
        90%  { opacity: 0.6; }
        100% { transform: translate3d(-10px, -110vh, 0) scale(0.4); opacity: 0; }
    }
    .particle-1  { left:  4%; width:  6px; height:  6px; animation-duration: 14s; animation-delay:  0s; }
    .particle-2  { left:  9%; width: 10px; height: 10px; animation-duration: 18s; animation-delay:  2s; background: rgba(167, 139, 250, 0.7); box-shadow: 0 0 14px rgba(167, 139, 250, 0.55); }
    .particle-3  { left: 14%; width:  4px; height:  4px; animation-duration: 11s; animation-delay:  4s; }
    .particle-4  { left: 19%; width:  8px; height:  8px; animation-duration: 16s; animation-delay:  1s; background: rgba(125, 211, 252, 0.75); box-shadow: 0 0 14px rgba(125, 211, 252, 0.55); }
    .particle-5  { left: 24%; width: 12px; height: 12px; animation-duration: 22s; animation-delay:  6s; }
    .particle-6  { left: 30%; width:  5px; height:  5px; animation-duration: 13s; animation-delay:  3s; }
    .particle-7  { left: 36%; width:  9px; height:  9px; animation-duration: 19s; animation-delay:  5s; background: rgba(34, 211, 238, 0.7); box-shadow: 0 0 14px rgba(34, 211, 238, 0.55); }
    .particle-8  { left: 42%; width:  6px; height:  6px; animation-duration: 15s; animation-delay:  7s; }
    .particle-9  { left: 48%; width: 11px; height: 11px; animation-duration: 24s; animation-delay:  0s; background: rgba(255, 255, 255, 0.55); }
    .particle-10 { left: 54%; width:  4px; height:  4px; animation-duration: 12s; animation-delay:  9s; }
    .particle-11 { left: 60%; width:  8px; height:  8px; animation-duration: 17s; animation-delay:  2s; background: rgba(196, 181, 253, 0.7); box-shadow: 0 0 14px rgba(196, 181, 253, 0.55); }
    .particle-12 { left: 66%; width:  6px; height:  6px; animation-duration: 14s; animation-delay:  6s; }
    .particle-13 { left: 72%; width: 10px; height: 10px; animation-duration: 21s; animation-delay:  4s; background: rgba(147, 197, 253, 0.75); box-shadow: 0 0 14px rgba(147, 197, 253, 0.55); }
    .particle-14 { left: 78%; width:  5px; height:  5px; animation-duration: 13s; animation-delay:  8s; }
    .particle-15 { left: 83%; width:  9px; height:  9px; animation-duration: 18s; animation-delay:  1s; }
    .particle-16 { left: 88%; width:  6px; height:  6px; animation-duration: 16s; animation-delay:  5s; background: rgba(255, 255, 255, 0.55); }
    .particle-17 { left: 92%; width:  4px; height:  4px; animation-duration: 12s; animation-delay: 10s; }
    .particle-18 { left: 96%; width: 11px; height: 11px; animation-duration: 23s; animation-delay:  3s; background: rgba(165, 180, 252, 0.7); box-shadow: 0 0 14px rgba(165, 180, 252, 0.55); }

    /* === Twinkling Stars === */
    .star {
        position: absolute;
        width: 3px;
        height: 3px;
        background: #ffffff;
        border-radius: 50%;
        box-shadow: 0 0 6px rgba(255, 255, 255, 0.95);
        animation: star-twinkle ease-in-out infinite;
        opacity: 0.7;
        will-change: opacity, transform;
    }
    @keyframes star-twinkle {
        0%, 100% { opacity: 0.15; transform: scale(0.7); }
        50%      { opacity: 1;    transform: scale(1.3); }
    }
    .star-1  { top:  8%; left: 12%; animation-duration: 3.2s; animation-delay: 0.0s; }
    .star-2  { top: 14%; left: 78%; animation-duration: 4.1s; animation-delay: 0.8s; width: 4px; height: 4px; }
    .star-3  { top: 22%; left: 35%; animation-duration: 2.8s; animation-delay: 1.4s; }
    .star-4  { top: 30%; left: 65%; animation-duration: 3.6s; animation-delay: 2.0s; width: 2px; height: 2px; }
    .star-5  { top: 38%; left: 20%; animation-duration: 4.4s; animation-delay: 0.4s; }
    .star-6  { top: 45%; left: 88%; animation-duration: 3.0s; animation-delay: 1.1s; width: 4px; height: 4px; }
    .star-7  { top: 52%; left:  6%; animation-duration: 3.8s; animation-delay: 2.6s; }
    .star-8  { top: 58%; left: 48%; animation-duration: 2.6s; animation-delay: 0.2s; width: 2px; height: 2px; }
    .star-9  { top: 64%; left: 72%; animation-duration: 4.0s; animation-delay: 1.8s; }
    .star-10 { top: 70%; left: 28%; animation-duration: 3.4s; animation-delay: 0.6s; width: 4px; height: 4px; }
    .star-11 { top: 76%; left: 92%; animation-duration: 4.6s; animation-delay: 2.2s; }
    .star-12 { top: 82%; left: 58%; animation-duration: 2.9s; animation-delay: 1.5s; }
    .star-13 { top: 88%; left: 16%; animation-duration: 3.5s; animation-delay: 0.9s; width: 2px; height: 2px; }
    .star-14 { top: 94%; left: 80%; animation-duration: 4.2s; animation-delay: 2.4s; }

    @media (prefers-reduced-motion: reduce) {
        .particle, .star { animation: none; opacity: 0.4; }
    }
    .login-stat-card {
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.20);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-radius: 14px;
        padding: 1rem 1.1rem;
        height: 100%;
        transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
    }
    .login-stat-card:hover {
        transform: translateY(-3px);
        background: rgba(255,255,255,0.18);
        border-color: rgba(255,255,255,0.35);
    }
    .login-stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: rgba(255,255,255,0.85);
        font-weight: 700;
        line-height: 1.25;
    }
    .login-stat-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px; height: 32px;
        border-radius: 10px;
        background: rgba(255,255,255,0.18);
        color: #ffffff;
        flex-shrink: 0;
    }
    .login-stat-icon i { color: #ffffff; }
    .login-stat-value {
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.01em;
    }

    /* === Login Right Panel === */
    .login-right-panel {
        background: linear-gradient(180deg, #ffffff 0%, #f1f5f9 100%);
        position: relative;
    }
    .login-right-panel::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 90% 10%, rgba(59,130,246,0.06) 0, transparent 35%),
            radial-gradient(circle at 10% 90%, rgba(139,92,246,0.05) 0, transparent 40%);
        pointer-events: none;
    }
    .register-card-wrapper {
        width: 100%;
        max-width: 460px;
        padding: 0 1rem;
        position: relative;
        z-index: 1;
    }
    .register-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 0;
        box-shadow:
            0 20px 50px rgba(15, 23, 42, 0.10),
            0 4px 12px rgba(15, 23, 42, 0.04);
        border: 1px solid #eef2f7;
        position: relative;
        overflow: hidden;
    }
    .register-card-accent {
        height: 4px;
        background: linear-gradient(90deg, #3b82f6 0%, #2563eb 35%, #8b5cf6 70%, #06b6d4 100%);
        background-size: 200% 100%;
        animation: register-accent-shift 6s ease-in-out infinite;
    }
    @keyframes register-accent-shift {
        0%, 100% { background-position: 0% 50%; }
        50%      { background-position: 100% 50%; }
    }
    .register-card-header {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.6rem;
        padding: 1.2rem 1.4rem 0.4rem;
    }
    .register-logo-wrap { display: inline-flex; }
    .login-logo { max-height: 34px; width: auto; }
    .register-card-body {
        padding: 0 1.4rem 1.4rem;
    }

    .login-field-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
        margin-bottom: 0.25rem;
    }
    .login-field-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        pointer-events: none;
        z-index: 2;
    }
    .login-input {
        padding: 0.55rem 0.85rem 0.55rem 2.4rem;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        font-size: 0.88rem;
        transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    }
    .login-input:focus {
        background: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }
    .login-input.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }
    .login-input.password-input { padding-right: 3rem; }
    .password-addon { z-index: 3; }
    .login-submit {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 55%, #1d4ed8 100%);
        border: none;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: 0.02em;
        padding: 0.65rem 1rem;
        border-radius: 10px;
        font-size: 0.92rem;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.28);
        transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
    }
    .login-submit:hover,
    .login-submit:focus {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(37, 99, 235, 0.4);
        filter: brightness(1.04);
    }
    .login-submit:active { transform: translateY(0); }
</style>
@endpush

@push('js_page')
    <script src="{{ asset('/js/particles-futuristic.js') }}"></script>
@endpush
