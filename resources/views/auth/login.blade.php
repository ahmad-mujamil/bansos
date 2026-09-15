@extends('layouts.layout_full')

@section('content_left')
    <div class="auth-hero">
        <img src="{{ asset('img/background/background-1.jpeg') }}"
             alt="Warga Lombok Barat menerima bantuan pangan, perikanan, dan peternakan"
             class="auth-hero-photo"/>
        <div class="auth-hero-veil" aria-hidden="true"></div>

        <div class="auth-hero-mark">
            <img src="{{ asset('img/login/lombok-barat-logo.png') }}"
                 alt="Logo Kabupaten Lombok Barat"
                 onerror="this.style.display='none'"/>
            <span>Pemerintah Kabupaten<br/>Lombok Barat</span>
        </div>

        <div class="auth-hero-copy">
            <h1 class="is-wordmark">Si-BATUR</h1>
            <p class="auth-hero-expand">Sistem Informasi Bantuan Terpadu dan Terukur</p>
            <p class="auth-hero-lede">
                Satu pintu untuk mengajukan dan memantau bantuan sosial, hibah,
                dan bantuan kelompok masyarakat di Lombok Barat.
            </p>
        </div>
    </div>
@endsection

@section('content_right')
    <div class="sw-lg-70 min-h-100 d-flex justify-content-center align-items-center py-5 auth-panel">
        <div class="auth-form">

            <div class="auth-form-mark d-lg-none">
                <img src="{{ asset('img/login/lombok-barat-logo.png') }}"
                     alt="Logo Kabupaten Lombok Barat"
                     onerror="this.style.display='none'"/>
                <span>Si-BATUR<br/><small>Pemkab Lombok Barat</small></span>
            </div>

            <h2 class="auth-title">Masuk ke akun Anda</h2>
            <p class="auth-subtitle">Gunakan username dan kata sandi yang sudah terdaftar.</p>

            <form id="loginForm" class="tooltip-end-bottom auth-body" novalidate action="{{ route('login') }}" method="post">
                @csrf

                @if ($errors->any())
                    <div class="auth-alert" role="alert">
                        <i data-acorn-icon="error-hexagon" data-acorn-size="18"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="auth-field">
                    <label for="username">Username</label>
                    <div class="auth-control position-relative">
                        <span class="auth-control-icon"><i data-acorn-icon="user" data-acorn-size="17"></i></span>
                        <input class="form-control auth-input @error('username') is-invalid @enderror"
                               placeholder="Masukkan username Anda"
                               name="username"
                               id="username"
                               value="{{ old('username') }}"
                               autocomplete="username" />
                    </div>
                </div>

                <div class="auth-field">
                    <div class="auth-field-head">
                        <label for="password">Kata sandi</label>
                        <a href="{{ route('password.request') }}" class="auth-field-aside">Lupa kata sandi?</a>
                    </div>
                    <div class="auth-control position-relative">
                        <span class="auth-control-icon"><i data-acorn-icon="lock-off" data-acorn-size="17"></i></span>
                        <input class="form-control auth-input password-input @error('password') is-invalid @enderror"
                               name="password"
                               id="password"
                               type="password"
                               placeholder="Masukkan kata sandi"
                               autocomplete="current-password" />
                        <button class="btn auth-reveal password-addon" type="button" aria-label="Tampilkan kata sandi">
                            <i data-acorn-icon="eye-off" class="icon-eye-off"></i>
                            <i data-acorn-icon="eye" class="icon-eye d-none"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check auth-remember">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                    <label class="form-check-label" for="remember">Biarkan saya tetap masuk</label>
                </div>

                <button type="submit" class="btn w-100 auth-submit">Masuk</button>
            </form>

            <p class="auth-switch">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar sekarang</a>
            </p>

            <p class="auth-meta">&copy; {{ date('Y') }} Pemerintah Kabupaten Lombok Barat</p>
        </div>
    </div>
@endsection

@push('css')
    @include('partials.auth-theme')
@endpush
