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
{{--                <div class="mb-4">--}}
{{--                    <h2 class="cta-1 mb-0 text-primary"><strong>U G C</strong></h2>--}}
{{--                    <h6>Eldery.id</h6>--}}
{{--                </div>--}}
                <br>
                <div class="mb-5">
                    <p class="h6">Gunakan Username dan Password untuk masuk kedalam aplikasi</p>
                </div>
                <div>
                    <form id="loginForm" class="tooltip-end-bottom" novalidate action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="user"></i>
                            <input class="form-control" placeholder="Username" name="username" id="username" />
                        </div>
                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="lock-off"></i>
                            <input class="form-control pe-7" name="password" type="password" placeholder="Password" />
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
    </div>
    <!-- Right Side End -->
@endsection
@section('content_left')
    <!-- Left Side Start -->
    <!--div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">
        <div class="min-h-100 d-flex align-items-center">
            <div class="w-100 w-lg-75 w-xxl-50">
                <div>
                    <div class="mb-5">
                        <h1 class="display-3 text-white">S P M B</h1>
                        <h1 class="display-6 text-white">Sistem Penerimaan Murid Baru 2025</h1>
                    </div>
                    <p class="h6 text-white lh-1-5 mb-5">
                        Sistem Penerimaan Murid Baru (SPMB) 2025 telah dibuka. Silahkan daftarkan diri anda sekarang juga.
                        jadwal pendaftaran, syarat dan ketentuan, serta informasi lainnya dapat dilihat pada link berikut.
                    </p>
{{--                    <div class="mb-5">--}}
{{--                        <a class="btn btn-lg btn-outline-white" href="{{ route('front-page') }}">Informasi Lengkap</a>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div-->
    <!-- Left Side End -->
@endsection
