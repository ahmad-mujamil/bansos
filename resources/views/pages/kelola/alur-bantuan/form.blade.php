@extends('layouts.layout')
@section('title', 'Kelola Alur Bantuan')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Kelola Alur Bantuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Landing Page</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelola.alur-bantuan.edit') }}">Alur Bantuan</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <livewire:kelola.alur-bantuan-form />
        </div>
    </div>
@endsection
@include('components.form_validation')
