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
            <h1>Buat kata sandi baru.</h1>
            <p class="auth-hero-expand">Si-BATUR &mdash; Pemerintah Kabupaten Lombok Barat</p>
            <p class="auth-hero-lede">
                Pilih kata sandi minimal 8 karakter, gabungkan huruf, angka, dan simbol.
                Hindari nama, tanggal lahir, atau NIK, dan jangan bagikan ke siapa pun.
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

            <h2 class="auth-title">Kata sandi baru</h2>
            <p class="auth-subtitle">Setelah disimpan, gunakan kata sandi ini untuk masuk.</p>

            <form method="POST" action="{{ route('password.update') }}" class="auth-body" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

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
                    <label for="email">Email</label>
                    <div class="auth-control position-relative">
                        <span class="auth-control-icon"><i data-acorn-icon="email" data-acorn-size="17"></i></span>
                        <input id="email"
                               type="email"
                               class="form-control auth-input @error('email') is-invalid @enderror"
                               name="email"
                               placeholder="nama@email.com"
                               value="{{ $email ?? old('email') }}"
                               required autocomplete="email" autofocus />
                    </div>
                </div>

                <div class="auth-field">
                    <label for="password">Kata sandi baru</label>
                    <div class="auth-control position-relative">
                        <span class="auth-control-icon"><i data-acorn-icon="lock-off" data-acorn-size="17"></i></span>
                        <input id="password"
                               type="password"
                               class="form-control auth-input password-input @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="Minimal 8 karakter"
                               required autocomplete="new-password" />
                        <button class="btn auth-reveal password-addon" type="button" aria-label="Tampilkan kata sandi">
                            <i data-acorn-icon="eye-off" class="icon-eye-off"></i>
                            <i data-acorn-icon="eye" class="icon-eye d-none"></i>
                        </button>
                    </div>
                </div>

                <div class="auth-field">
                    <label for="password-confirm">Ulangi kata sandi baru</label>
                    <div class="auth-control position-relative">
                        <span class="auth-control-icon"><i data-acorn-icon="lock-on" data-acorn-size="17"></i></span>
                        <input id="password-confirm"
                               type="password"
                               class="form-control auth-input password-input"
                               name="password_confirmation"
                               placeholder="Ketik ulang kata sandi baru"
                               required autocomplete="new-password" />
                        <button class="btn auth-reveal password-addon" type="button" aria-label="Tampilkan kata sandi">
                            <i data-acorn-icon="eye-off" class="icon-eye-off"></i>
                            <i data-acorn-icon="eye" class="icon-eye d-none"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn w-100 auth-submit mt-2">Simpan kata sandi</button>
            </form>

            <p class="auth-switch">
                Kembali ke <a href="{{ route('login') }}">halaman masuk</a>
            </p>

            <p class="auth-meta">&copy; {{ date('Y') }} Pemerintah Kabupaten Lombok Barat</p>
        </div>
    </div>
@endsection

@push('css')
    @include('partials.auth-theme')
@endpush
