@extends('layouts.layout')
@section('title', 'Profile')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">User Profile</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">User Profile</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Public Info Start -->
        <h2 class="small-title">Bio Data</h2>
        <div class="card mb-5">
            <div class="card-body">
                <form novalidate enctype="multipart/form-data" action="{{ route('profile.update') }}" method="POST" class="needs-validation">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-8 col-md-9 col-lg-10">
                            <input type="text" class="form-control" value="{{ auth()->user()->pengguna->nama??'' }}" name="nama" id="nama" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">User Name</label>
                        <div class="col-sm-8 col-md-9 col-lg-10">
                            <input type="text" class="form-control" value="{{ auth()->user()->username??'' }}" disabled />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Sekolah</label>
                        <div class="col-sm-8 col-md-9 col-lg-10">
                            <input type="text" class="form-control" value="{{ auth()->user()->pengguna->sekolah->nama??'-' }}" name="sekolah" id="sekolah" disabled />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Email</label>
                        <div class="col-sm-8 col-md-9 col-lg-10">
                            <input type="email" class="form-control" name="email" id="email"
                                   value="{{ auth()->user()->pengguna->email??'' }}" required />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">No Telp</label>
                        <div class="col-sm-8 col-md-9 col-lg-10">
                            <input type="number" class="form-control" id="no_hp" name="no_hp" value="{{ auth()->user()->pengguna->no_hp??'' }}"  required/>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Alamat</label>
                        <div class="col-sm-8 col-md-9 col-lg-10">
                            <textarea class="form-control" rows="3" name="alamat" id="alamat" required>{{ auth()->user()->pengguna->alamat??"-" }}</textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Foto (max:500kb)</label>
                        <div class="col-sm-8 col-md-9 col-lg-10">
                            <input type="file" class="form-control" value="" accept=".png, .jpg," name="foto" id="foto" />
                        </div>
                    </div>
                    <div class="mb-3 row mt-5">
                        <div class="col-sm-8 col-md-9 col-lg-10 ms-auto">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Public Info End -->
    </div>
    <!-- Page Content End -->
@endsection
@include('components.form_validation')
