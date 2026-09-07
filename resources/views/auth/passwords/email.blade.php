@extends('layouts.layout_full')

@section('content_left')
    <div class="auth-hero">
        <img src="{{ asset('img/background/background-1.jpeg') }}"
             alt="Warga Lombok Barat membawa paket bantuan pangan, perikanan, dan peternakan"
             class="auth-hero-photo"/>
        <div class="auth-hero-veil" aria-hidden="true"></div>

        <div class="auth-hero-mark">
            <img src="{{ asset('img/login/lombok-barat-logo.png') }}"
                 alt="Logo Kabupaten Lombok Barat"
                 onerror="this.style.display='none'"/>
            <span>Pemerintah Kabupaten<br/>Lombok Barat</span>
        </div>

        <div class="auth-hero-copy">
            <h1>Lupa kata sandi?<br/>Kami kirim tautannya.</h1>
            <p class="auth-hero-expand">Si-BATUR &mdash; Pemerintah Kabupaten Lombok Barat</p>
            <p class="auth-hero-lede">
                Masukkan email akun Anda. Kami kirim tautan untuk mengatur
                kata sandi baru, dan Anda bisa langsung masuk kembali.
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

            <h2 class="auth-title">Atur ulang kata sandi</h2>
            <p class="auth-subtitle">Kami kirim tautan pengaturan ulang ke email akun Anda.</p>

            <form method="POST" action="{{ route('password.email') }}" class="auth-body" novalidate>
                @csrf

                @if (session('status'))
                    <div class="auth-alert auth-alert-ok" role="alert">
                        <i data-acorn-icon="check-circle" data-acorn-size="18"></i>
                        <div><p>{{ session('status') }}</p></div>
                    </div>
                @endif

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
                               value="{{ old('email') }}"
                               required autocomplete="email" autofocus />
                    </div>
                </div>

                <button type="submit" class="btn w-100 auth-submit">Kirim tautan</button>
            </form>

            <p class="auth-switch">
                Ingat kata sandi Anda?
                <a href="{{ route('login') }}">Kembali ke halaman masuk</a>
            </p>

            <p class="auth-meta">&copy; {{ date('Y') }} Pemerintah Kabupaten Lombok Barat</p>
        </div>
    </div>
@endsection

@push('css')
    @include('partials.auth-theme')
@endpush
