@extends('layouts.layout')
@section('title', $heroSlide ? 'Edit Slide' : 'Tambah Slide')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">{{ $heroSlide ? 'Edit Slide' : 'Tambah Slide' }}</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Landing Page</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelola.slider.index') }}">Slider</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ $heroSlide ? 'Edit' : 'Tambah' }}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('kelola.slider.index') }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-5">
            @php
                $isEdit = (bool) $heroSlide;
                $route = $isEdit ? route('kelola.slider.update', $heroSlide) : route('kelola.slider.store');
                $method = $isEdit ? 'PUT' : 'POST';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase">Kategori (badge)</label>
                            <input type="text" class="form-control @error('kategori') is-invalid @enderror" name="kategori"
                                   value="{{ old('kategori', $heroSlide->kategori ?? '') }}" required maxlength="120"
                                   placeholder="Contoh: Program Pemerintah Resmi"/>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-small text-uppercase">Urutan</label>
                            <input type="number" min="0" max="65535" class="form-control @error('urutan') is-invalid @enderror" name="urutan"
                                   value="{{ old('urutan', $heroSlide->urutan ?? 0) }}"/>
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                    {{ old('is_active', $heroSlide === null || $heroSlide->is_active ? '1' : '') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Tampil di beranda</label>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">Judul utama</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul"
                                   value="{{ old('judul', $heroSlide->judul ?? '') }}" required maxlength="255"
                                   placeholder="Contoh: Gotong Royong Membangun"/>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">Judul sorot (warna aksen, opsional)</label>
                            <input type="text" class="form-control @error('judul_sorot') is-invalid @enderror" name="judul_sorot"
                                   value="{{ old('judul_sorot', $heroSlide->judul_sorot ?? '') }}" maxlength="255"
                                   placeholder="Contoh: Kesejahteraan."/>
                            <small class="text-muted">Kosongkan jika seluruh judul memakai warna sama.</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">Subtitle</label>
                            <textarea class="form-control @error('subtitle') is-invalid @enderror" name="subtitle" rows="3" required maxlength="1000">{{ old('subtitle', $heroSlide->subtitle ?? '') }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">Gambar hero</label>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar" accept="image/jpeg,image/png,image/webp,image/gif" {{ $isEdit ? '' : 'required' }}/>
                            @if($isEdit && $heroSlide->getFirstMediaUrl('hero'))
                                <div class="mt-2">
                                    <span class="text-small text-muted d-block mb-1">Gambar saat ini:</span>
                                    <img src="{{ $heroSlide->getFirstMediaUrl('hero') }}" alt="" class="rounded border" style="max-height:120px"/>
                                </div>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@include('components.form_validation')
