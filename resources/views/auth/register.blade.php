@extends('layouts.layout_full')

@section('content_right')
    <!-- Right Side Start -->
    <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
        <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
            <div class="sw-lg-50 px-6">
                <div class="sh-11 mb-6">
                    <a href="">
                        <img src="{{ asset('img/logo/logo-wide.png') }}" alt="logo" class="img-fluid"/>

                    </a>
                </div>
                <br>
                <div class="mb-5">
                    <p class="h6">Silakan lengkapi form di bawah ini untuk mendaftar</p>
                </div>
                <div>
                    <form id="registerForm" class="tooltip-end-bottom" novalidate action="{{ route('register') }}" method="post">
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

                        @if (session('success'))
                            <div class="alert alert-success mb-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="user"></i>
                            <input class="form-control @error('nama') is-invalid @enderror" placeholder="Nama" name="nama" id="nama" value="{{ old('nama') }}" required />
                        </div>
                        
                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="email"></i>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" id="email" value="{{ old('email') }}" required />
                        </div>

                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="user"></i>
                            <input class="form-control @error('username') is-invalid @enderror" placeholder="Username" name="username" id="username" value="{{ old('username') }}" required />
                        </div>

                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="lock-off"></i>
                            <input type="password" class="form-control pe-7 @error('password') is-invalid @enderror" name="password" placeholder="Password" required />
                        </div>

                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="lock-off"></i>
                            <input type="password" class="form-control pe-7 @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Konfirmasi Password" required />
                        </div>

                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="user"></i>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="status" required>
                                <option value="">Pilih Status User</option>
                                <option value="{{ \App\Enums\StatusUser::PERORANGAN->value }}" {{ old('status') == \App\Enums\StatusUser::PERORANGAN->value ? 'selected' : '' }}>
                                    {{ \App\Enums\StatusUser::PERORANGAN->getDescription() }}
                                </option>
                                <option value="{{ \App\Enums\StatusUser::ORGANISASI->value }}" {{ old('status') == \App\Enums\StatusUser::ORGANISASI->value ? 'selected' : '' }}>
                                    {{ \App\Enums\StatusUser::ORGANISASI->getDescription() }}
                                </option>
                                <option value="{{ \App\Enums\StatusUser::KELOMPOK->value }}" {{ old('status') == \App\Enums\StatusUser::KELOMPOK->value ? 'selected' : '' }}>
                                    {{ \App\Enums\StatusUser::KELOMPOK->getDescription() }}
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-lg btn-primary w-100">Daftar</button>
                    </form>
                    <br>
                    <div class="text-center">
                        <p class="text-small">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
                    </div>
                    <br><br>
                    <span class="badge rounded-pill bg-foreground mt-2">Copyright &copy;2026. Pemerintah Kabupaten Lombok Barat</span>

                </div>
            </div>
        </div>
    </div>
    <!-- Right Side End -->
@endsection
@section('content_left')
   
@endsection
