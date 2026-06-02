@extends('layouts.layout_full')

@section('content_left')
    @php
        $totalKelompok      = (int) collect($organisasiAktif)->sum();
        $klpKelompok        = (int) ($organisasiAktif['KLP'] ?? 0);
        $yysKelompok        = (int) ($organisasiAktif['YYS'] ?? 0);
        $dllKelompok        = max(0, $totalKelompok - $klpKelompok - $yysKelompok);
    @endphp
    <div class="login-hero position-relative overflow-hidden"
         style="min-height: 100vh !important; height: 100vh !important; width: 100% !important; display: block !important; background-color: #2563eb !important; background-image: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 30%, #3b82f6 65%, #93c5fd 100%) !important;">
        {{-- Background gradient (layer cadangan) --}}
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

        {{-- Foto pejabat (Bupati / Wakil Bupati) --}}
        <div class="login-hero-officials"
             role="img"
             aria-label="Bupati dan Wakil Bupati Lombok Barat"></div>

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
                    <i data-acorn-icon="user-plus" data-acorn-size="14"></i>
                    <span class="fw-bold" style="font-size: 0.78rem;">BKAD - LOMBOK BARAT</span>
                </div>
                <h1 class="login-hero-title mt-3 mb-2">
                    Bergabung Sebagai<br/>Pengguna Baru
                </h1>
                <p class="login-hero-subtitle mb-0">
                    Daftarkan diri Anda untuk mengakses layanan bantuan terpadu Kabupaten Lombok Barat — bantuan sosial, hibah, dan bantuan kelompok masyarakat.
                </p>
            </div>

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
        </div>
    </div>
@endsection

@section('content_right')
    <!-- Right Side Start -->
    <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center py-5 login-right-panel">
        <div class="login-form-wrapper">

            <h2 class="login-form-title">Buat Akun Baru</h2>
            <p class="login-form-subtitle">Lengkapi data berikut untuk mendaftar sebagai pengguna baru.</p>

            <div class="mt-4">
                    {{-- Wizard Stepper --}}
                    <div class="wizard-stepper" data-wizard-stepper>
                        <div class="wizard-step-item active" data-step-indicator="1">
                            <div class="wizard-step-circle">
                                <span class="wizard-step-num">1</span>
                                <i data-acorn-icon="check" data-acorn-size="16" class="wizard-step-check"></i>
                            </div>
                            <div class="wizard-step-label">Identitas</div>
                        </div>
                        <div class="wizard-step-line" data-step-line="1-2"></div>
                        <div class="wizard-step-item" data-step-indicator="2">
                            <div class="wizard-step-circle">
                                <span class="wizard-step-num">2</span>
                                <i data-acorn-icon="check" data-acorn-size="16" class="wizard-step-check"></i>
                            </div>
                            <div class="wizard-step-label">Kredensial</div>
                        </div>
                        <div class="wizard-step-line" data-step-line="2-3"></div>
                        <div class="wizard-step-item" data-step-indicator="3">
                            <div class="wizard-step-circle">
                                <span class="wizard-step-num">3</span>
                                <i data-acorn-icon="check" data-acorn-size="16" class="wizard-step-check"></i>
                            </div>
                            <div class="wizard-step-label">Konfirmasi</div>
                        </div>
                    </div>

                    <form id="registerForm" class="tooltip-end-bottom" novalidate action="{{ route('register') }}" method="post">
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

                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-start gap-2 border-0 shadow-sm mb-3" role="alert" style="border-radius: 12px;">
                                <i data-acorn-icon="check-circle" data-acorn-size="20" class="flex-shrink-0 mt-1"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        {{-- Step 1: Identitas --}}
                        <div class="wizard-step-panel active" data-step-panel="1">
                            <div class="wizard-step-title">
                                <i data-acorn-icon="user" data-acorn-size="18"></i>
                                <span>Identitas Diri</span>
                            </div>

                            <div class="mb-3 login-field">
                                <label for="nama" class="login-field-label">Nama Lengkap</label>
                                <div class="position-relative">
                                    <span class="login-field-icon"><i data-acorn-icon="user" data-acorn-size="18"></i></span>
                                    <input class="form-control login-input @error('nama') is-invalid @enderror"
                                           placeholder="Masukkan nama lengkap"
                                           name="nama" id="nama"
                                           data-wizard-field="1"
                                           value="{{ old('nama') }}" required />
                                </div>
                            </div>

                            <div class="mb-3 login-field">
                                <label for="email" class="login-field-label">Email</label>
                                <div class="position-relative">
                                    <span class="login-field-icon"><i data-acorn-icon="email" data-acorn-size="18"></i></span>
                                    <input type="email"
                                           class="form-control login-input @error('email') is-invalid @enderror"
                                           placeholder="nama@email.com"
                                           name="email" id="email"
                                           data-wizard-field="1"
                                           value="{{ old('email') }}" required />
                                </div>
                            </div>

                            <div class="mb-0 login-field">
                                <label for="jenis_user" class="login-field-label">Jenis User</label>
                                <div class="position-relative">
                                    <span class="login-field-icon"><i data-acorn-icon="category" data-acorn-size="18"></i></span>
                                    <select class="form-select login-input @error('jenis_user') is-invalid @enderror"
                                            name="jenis_user" id="jenis_user"
                                            data-wizard-field="1" required>
                                        <option value="" disabled {{ old('jenis_user') ? '' : 'selected' }}>Pilih jenis user</option>
                                        @foreach (\App\Enums\JenisUser::cases() as $jenis)
                                            <option value="{{ $jenis->value }}" {{ old('jenis_user') === $jenis->value ? 'selected' : '' }}>
                                                {{ $jenis->getDescription() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Kredensial --}}
                        <div class="wizard-step-panel" data-step-panel="2">
                            <div class="wizard-step-title">
                                <i data-acorn-icon="lock-on" data-acorn-size="18"></i>
                                <span>Kredensial Akun</span>
                            </div>

                            <div class="mb-3 login-field">
                                <label for="username" class="login-field-label">Username</label>
                                <div class="position-relative">
                                    <span class="login-field-icon"><i data-acorn-icon="user" data-acorn-size="18"></i></span>
                                    <input class="form-control login-input @error('username') is-invalid @enderror"
                                           placeholder="Pilih username unik"
                                           name="username" id="username"
                                           data-wizard-field="2"
                                           value="{{ old('username') }}" required />
                                </div>
                            </div>

                            <div class="mb-3 login-field">
                                <label for="password" class="login-field-label">Password</label>
                                <div class="position-relative">
                                    <span class="login-field-icon"><i data-acorn-icon="lock-off" data-acorn-size="18"></i></span>
                                    <input type="password"
                                           class="form-control login-input password-input @error('password') is-invalid @enderror"
                                           name="password" id="password"
                                           data-wizard-field="2"
                                           placeholder="Minimal 8 karakter" required />
                                    <button class="btn position-absolute end-0 top-0 h-100 px-3 password-addon" type="button">
                                        <i data-acorn-icon="eye-off" class="icon-eye-off text-primary"></i>
                                        <i data-acorn-icon="eye" class="icon-eye d-none text-primary"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-0 login-field">
                                <label for="password_confirmation" class="login-field-label">Konfirmasi Password</label>
                                <div class="position-relative">
                                    <span class="login-field-icon"><i data-acorn-icon="lock-on" data-acorn-size="18"></i></span>
                                    <input type="password"
                                           class="form-control login-input password-input @error('password_confirmation') is-invalid @enderror"
                                           name="password_confirmation" id="password_confirmation"
                                           data-wizard-field="2"
                                           placeholder="Ulangi password" required />
                                    <button class="btn position-absolute end-0 top-0 h-100 px-3 password-addon" type="button">
                                        <i data-acorn-icon="eye-off" class="icon-eye-off text-primary"></i>
                                        <i data-acorn-icon="eye" class="icon-eye d-none text-primary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Konfirmasi --}}
                        <div class="wizard-step-panel" data-step-panel="3">
                            <div class="wizard-step-title">
                                <i data-acorn-icon="check-circle" data-acorn-size="18"></i>
                                <span>Tinjau Data Anda</span>
                            </div>
                            <p class="text-muted mb-3" style="font-size: 0.88rem;">
                                Pastikan data berikut sudah benar sebelum mendaftar.
                            </p>

                            <div class="wizard-review">
                                <div class="wizard-review-row">
                                    <div class="wizard-review-label">Nama Lengkap</div>
                                    <div class="wizard-review-value" data-review="nama">—</div>
                                </div>
                                <div class="wizard-review-row">
                                    <div class="wizard-review-label">Email</div>
                                    <div class="wizard-review-value" data-review="email">—</div>
                                </div>
                                <div class="wizard-review-row">
                                    <div class="wizard-review-label">Jenis User</div>
                                    <div class="wizard-review-value" data-review="jenis_user">—</div>
                                </div>
                                <div class="wizard-review-row">
                                    <div class="wizard-review-label">Username</div>
                                    <div class="wizard-review-value" data-review="username">—</div>
                                </div>
                                <div class="wizard-review-row">
                                    <div class="wizard-review-label">Password</div>
                                    <div class="wizard-review-value" data-review="password">••••••••</div>
                                </div>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="agree" data-wizard-agree required />
                                <label class="form-check-label text-small" for="agree">
                                    Saya menyetujui bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan.
                                </label>
                            </div>
                        </div>

                        {{-- Wizard Navigation --}}
                        <div class="wizard-nav mt-3 d-flex justify-content-between gap-2">
                            <button type="button" class="btn wizard-btn-prev" data-wizard-prev style="visibility: hidden;">
                                <i data-acorn-icon="arrow-left" data-acorn-size="16" class="me-1 align-middle"></i>
                                Sebelumnya
                            </button>
                            <button type="button" class="btn wizard-btn-next" data-wizard-next>
                                Selanjutnya
                                <i data-acorn-icon="arrow-right" data-acorn-size="16" class="ms-1 align-middle"></i>
                            </button>
                            <button type="submit" class="btn login-submit wizard-btn-submit" data-wizard-submit style="display: none;">
                                <i data-acorn-icon="user-plus" data-acorn-size="18" class="me-2 align-middle"></i>
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>
            </div>

            <div class="login-divider"><span>atau</span></div>

            <p class="text-center mb-0 login-register-prompt">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="fw-bold">Masuk di sini</a>
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
    .login-hero-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 85% 30%, rgba(255,255,255,0.5) 0, transparent 55%),
            linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 30%, #3b82f6 65%, #93c5fd 100%);
        z-index: 0;
    }
    .login-hero-batik {
        position: absolute;
        top: -40px;
        left: -40px;
        width: 360px;
        height: 480px;
        z-index: 1;
        pointer-events: none;
        opacity: 0.5;
        background-image:
            repeating-linear-gradient(45deg,
                rgba(255,255,255,0.55) 0 4px,
                transparent 4px 14px),
            repeating-linear-gradient(-45deg,
                rgba(147, 197, 253, 0.45) 0 3px,
                transparent 3px 14px),
            repeating-linear-gradient(90deg,
                rgba(96, 165, 250, 0.20) 0 2px,
                transparent 2px 22px);
        -webkit-mask-image: linear-gradient(135deg, #000 0%, #000 40%, transparent 90%);
                mask-image: linear-gradient(135deg, #000 0%, #000 40%, transparent 90%);
        transform: rotate(-6deg);
    }
    .login-hero-circuit {
        position: absolute;
        top: 12%;
        left: 0;
        width: 70%;
        height: 60%;
        z-index: 2;
        pointer-events: none;
    }
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

    /* === Right Panel (minimalist, no card) === */
    .login-right-panel {
        background: #f1f5f9;
        position: relative;
    }
    .login-form-wrapper {
        width: 100%;
        max-width: 480px;
        padding: 0 2rem;
        position: relative;
        z-index: 1;
    }
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
    .login-divider {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin: 1.5rem 0 1rem;
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

    /* === Wizard Stepper === */
    .wizard-stepper {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        margin-bottom: 1rem;
        padding: 0.25rem 0.2rem 0.6rem;
    }
    .wizard-step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
        min-width: 0;
    }
    .wizard-step-circle {
        position: relative;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-weight: 700;
        font-size: 0.85rem;
        line-height: 1;
        transition: all 0.3s ease;
    }
    .wizard-step-num,
    .wizard-step-check {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 16px;
        height: 16px;
        margin: 0;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        transform: translate(-50%, -50%) scale(0.5);
        opacity: 0;
        transition: opacity 0.25s ease, transform 0.25s ease;
    }
    .wizard-step-num {
        width: auto;
        height: auto;
    }
    .wizard-step-item .wizard-step-num,
    .wizard-step-item.active .wizard-step-num {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
    .wizard-step-check {
        color: #ffffff;
    }
    .wizard-step-check svg {
        width: 16px;
        height: 16px;
        display: block;
    }
    .wizard-step-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #94a3b8;
        text-align: center;
        transition: color 0.25s ease;
    }
    .wizard-step-line {
        flex: 1 1 auto;
        height: 2px;
        background: #e2e8f0;
        border-radius: 999px;
        margin: -20px 0.1rem 0;
        position: relative;
        overflow: hidden;
    }
    .wizard-step-line::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s ease;
    }
    .wizard-step-line.completed::after { transform: scaleX(1); }

    .wizard-step-item.active .wizard-step-circle {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 55%, #1d4ed8 100%);
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        transform: scale(1.08);
    }
    .wizard-step-item.active .wizard-step-label {
        color: #1d4ed8;
    }
    .wizard-step-item.completed .wizard-step-circle {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 6px 14px rgba(16, 185, 129, 0.3);
    }
    .wizard-step-item.completed .wizard-step-num {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.5);
    }
    .wizard-step-item.completed .wizard-step-check {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
    .wizard-step-item.completed .wizard-step-label { color: #059669; }

    /* === Wizard Panels === */
    .wizard-step-panel {
        display: none;
        animation: wizard-fade-in 0.35s ease;
    }
    .wizard-step-panel.active { display: block; }
    @keyframes wizard-fade-in {
        from { opacity: 0; transform: translateX(12px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .wizard-step-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        font-size: 0.88rem;
        color: #0f172a;
        margin-bottom: 0.85rem;
        padding-bottom: 0.55rem;
        border-bottom: 1px dashed #e2e8f0;
    }
    .wizard-step-title i { color: #2563eb; }

    /* === Wizard Review === */
    .wizard-review {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 10px;
        overflow: hidden;
    }
    .wizard-review-row {
        display: flex;
        padding: 0.55rem 0.85rem;
        border-bottom: 1px dashed #e2e8f0;
        gap: 0.75rem;
    }
    .wizard-review-row:last-child { border-bottom: none; }
    .wizard-review-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        font-weight: 700;
        flex: 0 0 110px;
    }
    .wizard-review-value {
        font-size: 0.84rem;
        color: #0f172a;
        font-weight: 600;
        flex: 1;
        word-break: break-word;
    }

    /* === Wizard Buttons === */
    .wizard-btn-prev,
    .wizard-btn-next {
        border-radius: 12px;
        padding: 0.7rem 1.1rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    .wizard-btn-prev {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 55%, #d97706 100%);
        color: #ffffff;
        border: none;
        box-shadow: 0 8px 18px rgba(217, 119, 6, 0.28);
    }
    .wizard-btn-prev:hover,
    .wizard-btn-prev:focus {
        color: #ffffff;
        transform: translateX(-2px);
        box-shadow: 0 12px 22px rgba(217, 119, 6, 0.35);
        filter: brightness(1.04);
    }
    .wizard-btn-next {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 55%, #1d4ed8 100%);
        color: #ffffff;
        border: none;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.28);
    }
    .wizard-btn-next:hover,
    .wizard-btn-next:focus {
        color: #ffffff;
        transform: translateX(2px);
        box-shadow: 0 14px 26px rgba(37, 99, 235, 0.35);
    }
    .wizard-btn-submit {
        flex: 1;
    }
    .wizard-nav { gap: 0.75rem; }
    .wizard-nav .wizard-btn-next,
    .wizard-nav .wizard-btn-submit { margin-left: auto; }

    @media (max-width: 575.98px) {
        .wizard-step-label { font-size: 0.65rem; }
        .wizard-step-circle { width: 34px; height: 34px; font-size: 0.85rem; }
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
    .login-field-icon {
        position: absolute;
        left: 14px;
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
    select.login-input { padding-right: 2.5rem; }
    .password-addon { z-index: 3; border-radius: 0 12px 12px 0 !important; }
    .password-addon i { color: #94a3b8 !important; }

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
</style>
@endpush

@push('js_page')
<script src="{{ asset('/js/particles-futuristic.js') }}"></script>
<script>
(function () {
    var form = document.getElementById('registerForm');
    if (!form) return;

    var totalSteps = 3;
    var currentStep = 1;

    var indicators = form.parentElement.querySelectorAll('[data-step-indicator]');
    var lines      = form.parentElement.querySelectorAll('[data-step-line]');
    var panels     = form.querySelectorAll('[data-step-panel]');
    var btnPrev    = form.querySelector('[data-wizard-prev]');
    var btnNext    = form.querySelector('[data-wizard-next]');
    var btnSubmit  = form.querySelector('[data-wizard-submit]');
    var agreeBox   = form.querySelector('[data-wizard-agree]');

    var firstInvalid = form.querySelector('.is-invalid');
    if (firstInvalid) {
        var stepWithError = firstInvalid.closest('[data-step-panel]');
        if (stepWithError) currentStep = parseInt(stepWithError.getAttribute('data-step-panel'), 10);
    }

    function setStep(step) {
        currentStep = step;

        indicators.forEach(function (el) {
            var n = parseInt(el.getAttribute('data-step-indicator'), 10);
            el.classList.remove('active', 'completed');
            if (n < currentStep) el.classList.add('completed');
            else if (n === currentStep) el.classList.add('active');
        });

        lines.forEach(function (el) {
            var pair = el.getAttribute('data-step-line').split('-');
            var leftStep = parseInt(pair[0], 10);
            if (leftStep < currentStep) el.classList.add('completed');
            else el.classList.remove('completed');
        });

        panels.forEach(function (el) {
            var n = parseInt(el.getAttribute('data-step-panel'), 10);
            el.classList.toggle('active', n === currentStep);
        });

        btnPrev.style.visibility = currentStep === 1 ? 'hidden' : 'visible';
        if (currentStep === totalSteps) {
            btnNext.style.display = 'none';
            btnSubmit.style.display = 'inline-flex';
            populateReview();
        } else {
            btnNext.style.display = 'inline-flex';
            btnSubmit.style.display = 'none';
        }
    }

    function validateStep(step) {
        var fields = form.querySelectorAll('[data-wizard-field="' + step + '"]');
        var valid = true;
        fields.forEach(function (field) {
            field.classList.remove('is-invalid');
            var val = (field.value || '').trim();
            if (field.hasAttribute('required') && !val) {
                field.classList.add('is-invalid');
                valid = false;
            }
            if (field.type === 'email' && val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                field.classList.add('is-invalid');
                valid = false;
            }
            if (field.id === 'password' && val && val.length < 8) {
                field.classList.add('is-invalid');
                valid = false;
            }
            if (field.id === 'password_confirmation') {
                var pwd = form.querySelector('#password');
                if (pwd && val !== pwd.value) {
                    field.classList.add('is-invalid');
                    valid = false;
                }
            }
        });
        if (!valid) {
            var firstBad = form.querySelector('[data-wizard-field="' + step + '"].is-invalid');
            if (firstBad) firstBad.focus();
        }
        return valid;
    }

    function populateReview() {
        var get = function (sel) { var el = form.querySelector(sel); return el ? el.value : ''; };
        var reviews = form.querySelectorAll('[data-review]');
        reviews.forEach(function (el) {
            var key = el.getAttribute('data-review');
            if (key === 'jenis_user') {
                var sel = form.querySelector('#jenis_user');
                el.textContent = sel && sel.selectedIndex >= 0 ? sel.options[sel.selectedIndex].text : '—';
                return;
            }
            if (key === 'password') {
                var pw = get('#password');
                el.textContent = pw ? '•'.repeat(Math.min(pw.length, 12)) : '—';
                return;
            }
            var val = get('#' + key);
            el.textContent = val || '—';
        });
    }

    btnNext.addEventListener('click', function () {
        if (!validateStep(currentStep)) return;
        if (currentStep < totalSteps) setStep(currentStep + 1);
    });

    btnPrev.addEventListener('click', function () {
        if (currentStep > 1) setStep(currentStep - 1);
    });

    form.addEventListener('submit', function (e) {
        for (var s = 1; s <= totalSteps - 1; s++) {
            if (!validateStep(s)) {
                e.preventDefault();
                setStep(s);
                return;
            }
        }
        if (agreeBox && !agreeBox.checked) {
            e.preventDefault();
            agreeBox.focus();
            agreeBox.classList.add('is-invalid');
        }
    });

    form.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && currentStep < totalSteps) {
            if (e.target.tagName === 'TEXTAREA') return;
            e.preventDefault();
            btnNext.click();
        }
    });

    form.querySelectorAll('[data-wizard-field]').forEach(function (el) {
        var evt = el.tagName === 'SELECT' ? 'change' : 'input';
        el.addEventListener(evt, function () {
            el.classList.remove('is-invalid');
        });
    });

    if (agreeBox) {
        agreeBox.addEventListener('change', function () {
            agreeBox.classList.remove('is-invalid');
        });
    }

    setStep(currentStep);
})();
</script>
@endpush
