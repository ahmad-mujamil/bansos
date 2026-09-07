@extends('layouts.layout_full')

@section('content_left')
    <div class="auth-hero">
        <img src="{{ asset('img/background/background-2.jpeg') }}"
             alt="Petani, nelayan, dan peternak Lombok Barat menerima sertifikat dan paket bantuan"
             class="auth-hero-photo"/>
        <div class="auth-hero-veil" aria-hidden="true"></div>

        <div class="auth-hero-mark">
            <img src="{{ asset('img/login/lombok-barat-logo.png') }}"
                 alt="Logo Kabupaten Lombok Barat"
                 onerror="this.style.display='none'"/>
            <span>Pemerintah Kabupaten<br/>Lombok Barat</span>
        </div>

        <div class="auth-hero-copy">
            <h1>Daftar sekali,<br/>ajukan kapan saja.</h1>
            <p class="auth-hero-expand">Si-BATUR &mdash; Pemerintah Kabupaten Lombok Barat</p>
            <p class="auth-hero-lede">
                Akun Anda dipakai untuk mengajukan bantuan sosial, hibah, dan bantuan
                kelompok masyarakat, sekaligus memantau prosesnya.
            </p>
        </div>
    </div>
@endsection

@section('content_right')
    <!-- Right Side Start -->
    <div class="sw-lg-70 min-h-100 d-flex justify-content-center align-items-center py-5 login-right-panel">
        <div class="login-form-wrapper">

            <h2 class="login-form-title">Buat akun baru</h2>
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
    @include('partials.auth-theme')
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
