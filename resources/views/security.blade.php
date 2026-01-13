@extends('layouts.layout')
@section('title', 'Security')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Security</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Security</a></li>
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
        <h2 class="small-title">Update Password</h2>
        <div class="card mb-5">
            <div class="card-body">
                <form novalidate enctype="multipart/form-data" action="{{ route('password.update') }}" method="POST" class="needs-validation">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Password Lama</label>
                        <div class="col-sm-8 col-md-9 col-lg-10">
                            <input type="password" class="form-control" value="" name="password_old" id="password_old" required />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Password Baru</label>
                        <div class="col-sm-8 col-md-9 col-lg-10">
                            <input type="password" class="form-control" value="" name="password" id="password" required />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-form-label">Konfirmasi Password</label>
                        <div class="col-sm-8 col-md-9 col-lg-10">
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"  required/>
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
