@extends('layouts.layout_full')

@section('content_left')
    {{-- Statistik organisasi/kelompok disembunyikan — data tidak ditampilkan di halaman login.
    @php
        $totalKelompok      = (int) collect($organisasiAktif)->sum();
        $klpKelompok        = (int) ($organisasiAktif['KLP'] ?? 0);
        $yysKelompok        = (int) ($organisasiAktif['YYS'] ?? 0);
        $dllKelompok        = max(0, $totalKelompok - $klpKelompok - $yysKelompok);
    @endphp
    --}}
    <div class="login-hero position-relative overflow-hidden"
         style="min-height: 100vh !important; height: 100vh !important; width: 100% !important; display: block !important; background-color: #2563eb !important; background-image: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 30%, #3b82f6 65%, #93c5fd 100%) !important;">
        {{-- Background gradient teal/hijau (layer cadangan) --}}
        <div class="login-hero-bg"></div>

        {{-- Pola batik/tenun dekoratif kiri-atas --}}
        <div class="login-hero-batik" aria-hidden="true"></div>

        {{-- Garis sirkuit / network dekoratif --}}
        <svg class="login-hero-circuit" viewBox="0 0 600 400" preserveAspectRatio="none" aria-hidden="true">
            <g stroke="#ffffff" stroke-width="1" fill="none" opacity="0.45">
                <path d="M40 80 L160 80 L180 100 L320 100"/>
                <path d="M60 140 L140 140 L160 160 L260 160"/>
                <path d="M30 220 L180 220 L210 250"/>
                <path d="M380 60 L470 60 L490 80 L560 80"/>
                <circle cx="40"  cy="80"  r="3" fill="#ffffff"/>
                <circle cx="60"  cy="140" r="3" fill="#ffffff"/>
                <circle cx="30"  cy="220" r="3" fill="#ffffff"/>
                <circle cx="320" cy="100" r="3" fill="#ffffff"/>
                <circle cx="260" cy="160" r="3" fill="#ffffff"/>
                <circle cx="210" cy="250" r="3" fill="#ffffff"/>
                <circle cx="380" cy="60"  r="3" fill="#ffffff"/>
                <circle cx="560" cy="80"  r="3" fill="#ffffff"/>
            </g>
        </svg>

        {{-- Foto pejabat (Bupati / Wakil Bupati) — disembunyikan sementara --}}
        {{-- <div class="login-hero-officials"
             role="img"
             aria-label="Bupati dan Wakil Bupati Lombok Barat"></div> --}}

        {{-- Logo Daerah (top right) --}}
        <div class="login-hero-logo-daerah">
            <img src="{{ asset('img/login/lombok-barat-logo.png') }}"
                 alt="Logo Kabupaten Lombok Barat"
                 onerror="this.style.display='none'"/>
        </div>

        {{-- Gelombang dekoratif di bawah --}}
        <svg class="login-hero-wave" viewBox="0 0 1200 200" preserveAspectRatio="none" aria-hidden="true">
            <path d="M0 120 C 200 60, 400 180, 600 120 S 1000 60, 1200 120 L 1200 200 L 0 200 Z"
                  fill="#1d4ed8" opacity="0.35"/>
            <path d="M0 150 C 200 100, 400 200, 600 150 S 1000 100, 1200 150 L 1200 200 L 0 200 Z"
                  fill="#1e3a8a" opacity="0.4"/>
        </svg>

        {{-- Content area --}}
        <div class="login-hero-content">
            <div class="login-hero-brand mb-4">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill login-hero-pill">
                    <i data-acorn-icon="heart" data-acorn-size="14"></i>
                    <span class="fw-bold" style="font-size: 0.78rem;">BKAD - LOMBOK BARAT</span>
                </div>
                <h2 class="login-hero-title mt-3 mb-2">
                    BANTU-IN<br/><small>Bantuan Tuntas Terintegrasi</small>
                </h2>
                <p class="login-hero-subtitle mb-0">
                    Layanan terpadu pengajuan bantuan sosial, hibah, dan bantuan kelompok masyarakat di Kabupaten Lombok Barat.
                </p>
            </div>

{{-- Kartu statistik disembunyikan — data tidak ditampilkan di halaman login.
            <div>
                <div class="login-hero-section-label mb-3">
                    Organisasi / Kelompok Aktif
                </div>
                <div class="row g-3 login-hero-stats">
                    <div class="col-6">
                        <div class="login-stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="login-stat-label">Total Kelompok</span>
                                <span class="login-stat-icon"><i data-acorn-icon="file-text" data-acorn-size="16"></i></span>
                            </div>
                            <div class="login-stat-value">{{ number_format($totalKelompok) }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="login-stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="login-stat-label">Kelompok Masyarakat</span>
                                <span class="login-stat-icon"><i data-acorn-icon="send" data-acorn-size="16"></i></span>
                            </div>
                            <div class="login-stat-value">{{ number_format($klpKelompok) }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="login-stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="login-stat-label">Yayasan</span>
                                <span class="login-stat-icon"><i data-acorn-icon="check-circle" data-acorn-size="16"></i></span>
                            </div>
                            <div class="login-stat-value">{{ number_format($yysKelompok) }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="login-stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="login-stat-label">DLL</span>
                                <span class="login-stat-icon"><i data-acorn-icon="building" data-acorn-size="16"></i></span>
                            </div>
                            <div class="login-stat-value">{{ number_format($dllKelompok) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            --}}
        </div>
    </div>
@endsection

@section('content_right')
    <!-- Right Side Start -->
    <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center py-5 login-right-panel">
        <div class="login-form-wrapper">

{{--            <div class="login-form-logo mb-4">--}}
{{--                <a href="">--}}
{{--                    <img src="{{ asset('img/logo/logo-wide.png') }}" alt="logo" class="login-logo"/>--}}
{{--                </a>--}}
{{--            </div>--}}

            <h2 class="login-form-title">Masuk ke akun Anda</h2>
            <p class="login-form-subtitle">Gunakan Username dan Password terdaftar untuk melanjutkan.</p>

            <form id="loginForm" class="tooltip-end-bottom mt-4" novalidate action="{{ route('login') }}" method="post">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2 border-0 shadow-sm mb-3" role="alert" style="border-radius: 14px;">
                        <i data-acorn-icon="error-hexagon" data-acorn-size="20" class="flex-shrink-0 mt-1"></i>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4 login-field">
                    <label for="username" class="login-field-label">Username</label>
                    <div class="position-relative">
                        <span class="login-field-icon"><i data-acorn-icon="user" data-acorn-size="18"></i></span>
                        <input class="form-control login-input @error('username') is-invalid @enderror"
                               placeholder="Masukkan username Anda"
                               name="username"
                               id="username"
                               value="{{ old('username') }}"
                               autocomplete="username" />
                    </div>
                </div>

                <div class="mb-3 login-field">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <label for="password" class="login-field-label mb-0">Password</label>
{{--                        <a href="{{ route('password.request') }}" class="login-forgot-link">Lupa sandi?</a>--}}
                    </div>
                    <div class="position-relative mt-1">
                        <span class="login-field-icon"><i data-acorn-icon="lock-off" data-acorn-size="18"></i></span>
                        <input class="form-control login-input password-input @error('password') is-invalid @enderror"
                               name="password"
                               id="password"
                               type="password"
                               placeholder="Masukkan kata sandi"
                               autocomplete="current-password" />
                        <button class="btn position-absolute end-0 top-0 h-100 px-3 password-addon" type="button">
                            <i data-acorn-icon="eye-off" class="icon-eye-off text-primary"></i>
                            <i data-acorn-icon="eye" class="icon-eye d-none text-primary"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check login-remember mb-4">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                    <label class="form-check-label" for="remember">Biarkan saya tetap masuk</label>
                </div>

                <button type="submit" class="btn w-100 login-submit">
                    Masuk
                </button>
            </form>

            <div class="login-divider"><span>atau</span></div>

            <p class="text-center mb-0 login-register-prompt">
                Belum punya akun?
                <a href="{{ route('register') }}" class="fw-bold">Daftar sekarang</a>
            </p>

            <div class="text-center mt-4 pt-3 login-footer-meta">
                &copy; 2026 Pemerintah Kabupaten Lombok Barat
            </div>
        </div>
    </div>
    <!-- Right Side End -->
@endsection

@push('css')
<style>
    /* === Login Hero (Left Side) — Blue Theme === */
    .login-hero {
        color: #0f172a;
        min-height: 100vh;
        background-color: #2563eb;
        background-image:
            radial-gradient(circle at 85% 30%, rgba(255,255,255,0.5) 0, transparent 55%),
            linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 30%, #3b82f6 65%, #93c5fd 100%);
    }

    /* Layer cadangan kalau ada CSS lain yang override `.login-hero`. */
    .login-hero-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 85% 30%, rgba(255,255,255,0.5) 0, transparent 55%),
            linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 30%, #3b82f6 65%, #93c5fd 100%);
        z-index: 0;
    }

    /* Pola batik / tenun di kiri-atas */
    .login-hero-batik {
        position: absolute;
        top: -40px;
        left: -40px;
        width: 360px;
        height: 480px;
        z-index: 1;
        pointer-events: none;
        opacity: 0.55;
        background-image:
            repeating-linear-gradient(45deg,
                rgba(255,255,255,0.55) 0 4px,
                transparent 4px 14px),
            repeating-linear-gradient(-45deg,
                rgba(56, 189, 248, 0.45) 0 3px,
                transparent 3px 14px),
            repeating-linear-gradient(90deg,
                rgba(14, 165, 233, 0.20) 0 2px,
                transparent 2px 22px);
        -webkit-mask-image: linear-gradient(135deg, #000 0%, #000 40%, transparent 90%);
                mask-image: linear-gradient(135deg, #000 0%, #000 40%, transparent 90%);
        transform: rotate(-6deg);
    }

    /* Garis sirkuit dekoratif */
    .login-hero-circuit {
        position: absolute;
        top: 12%;
        left: 0;
        width: 70%;
        height: 60%;
        z-index: 2;
        pointer-events: none;
    }

    /* Foto pejabat — Bupati & Wakil Bupati Lombok Barat.
       Letakkan foto di: public/img/login/officials.png
       (PNG transparan, background sudah dihapus). */
    .login-hero-officials {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 55%;
        height: 80%;
        z-index: 3;
        background-image: url('{{ asset('img/login/officials.png') }}');
        background-size: contain;
        background-position: left bottom;
        background-repeat: no-repeat;
        pointer-events: none;
    }

    /* Logo daerah pojok kanan-atas */
    .login-hero-logo-daerah {
        position: absolute;
        top: 1.5rem;
        right: 1.75rem;
        z-index: 4;
        width: 72px;
        height: 72px;
    }
    .login-hero-logo-daerah img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 4px 10px rgba(15, 23, 42, 0.18));
    }

    /* Gelombang bawah */
    .login-hero-wave {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 22%;
        z-index: 2;
        pointer-events: none;
    }

    /* Konten (text + cards) di sisi kanan hero */
    .login-hero-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 58%;
        z-index: 5;
        padding: 3.5rem 2.5rem 3.5rem 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    @media (max-width: 1399.98px) {
        .login-hero-officials { width: 48%; height: 72%; }
        .login-hero-content { width: 60%; padding: 2.5rem 2rem 2.5rem 1rem; }
    }
    @media (max-width: 1199.98px) {
        .login-hero-officials { opacity: 0.6; }
        .login-hero-content { width: 70%; padding: 2rem 1.5rem; }
    }

    .login-hero-pill {
        background: rgba(255, 255, 255, 0.6);
        color: #1d4ed8;
        border: 1px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    .login-hero-pill i { color: #1d4ed8; }

    .login-hero-title {
        color: #ffffff;
        font-weight: 800;
        font-size: 2.4rem;
        line-height: 1.1;
        letter-spacing: -0.01em;
        text-shadow: 0 2px 12px rgba(15, 23, 42, 0.25);
    }
    .login-hero-subtitle {
        color: #ffffff;
        font-size: 0.95rem;
        max-width: 480px;
        opacity: 0.92;
        line-height: 1.55;
    }
    .login-hero-section-label {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.72rem;
        font-weight: 700;
        color: #ffffff;
        opacity: 0.9;
    }

    .login-stat-card {
        background: rgba(29, 78, 216, 0.42);
        border: 1px solid rgba(255, 255, 255, 0.28);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-radius: 16px;
        padding: 0.95rem 1.1rem;
        height: 100%;
        transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
    }
    .login-stat-card:hover {
        transform: translateY(-3px);
        background: rgba(37, 99, 235, 0.55);
        border-color: rgba(255, 255, 255, 0.45);
    }
    .login-stat-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: rgba(255, 255, 255, 0.92);
        font-weight: 700;
        line-height: 1.25;
    }
    .login-stat-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px; height: 32px;
        border-radius: 9px;
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
        flex-shrink: 0;
    }
    .login-stat-icon i { color: #ffffff; }
    .login-stat-value {
        color: #ffffff;
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.01em;
        margin-top: 0.25rem;
    }

    /* === Login Right Panel (minimalist) === */
    .login-right-panel {
        background: #f1f5f9;
        position: relative;
    }
    .login-form-wrapper {
        width: 100%;
        max-width: 460px;
        padding: 0 2rem;
        position: relative;
        z-index: 1;
    }
    .login-form-logo { display: flex; }
    .login-logo { max-height: 34px; width: auto; }

    .login-form-title {
        font-size: 1.85rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.01em;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }
    .login-form-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    .login-field-label {
        display: block;
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
        letter-spacing: 0;
        text-transform: none;
    }
    .login-forgot-link {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1d4ed8;
        text-decoration: none;
    }
    .login-forgot-link:hover { color: #1e40af; text-decoration: underline; }

    .login-field-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        pointer-events: none;
        z-index: 2;
    }
    .login-input {
        width: 100%;
        padding: 0.85rem 1rem 0.85rem 2.8rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        font-size: 0.95rem;
        color: #0f172a;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .login-input::placeholder { color: #94a3b8; }
    .login-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14), 0 1px 2px rgba(15, 23, 42, 0.04);
    }
    .login-input.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }
    .login-input.password-input { padding-right: 3.2rem; }
    .password-addon { z-index: 3; border-radius: 0 12px 12px 0 !important; }
    .password-addon i { color: #94a3b8 !important; }

    .login-remember .form-check-input {
        width: 1.15rem;
        height: 1.15rem;
        border-radius: 6px;
        border: 1.5px solid #cbd5e1;
        margin-top: 0.15rem;
        cursor: pointer;
    }
    .login-remember .form-check-input:checked {
        background-color: #2563eb;
        border-color: #2563eb;
    }
    .login-remember .form-check-label {
        font-size: 0.92rem;
        color: #1e293b;
        margin-left: 0.4rem;
        cursor: pointer;
    }

    .login-submit {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 55%, #1d4ed8 100%);
        border: none;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: 0.01em;
        padding: 0.85rem 1rem;
        border-radius: 12px;
        font-size: 1rem;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.32);
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

    .login-divider {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin: 1.75rem 0 1rem;
        color: #94a3b8;
        font-size: 0.85rem;
    }
    .login-divider::before,
    .login-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }
    .login-register-prompt {
        font-size: 0.95rem;
        color: #475569;
    }
    .login-register-prompt a { color: #1d4ed8; text-decoration: none; }
    .login-register-prompt a:hover { text-decoration: underline; }
    .login-footer-meta {
        font-size: 0.75rem;
        color: #94a3b8;
        border-top: 1px dashed #e2e8f0;
    }
</style>
@endpush

