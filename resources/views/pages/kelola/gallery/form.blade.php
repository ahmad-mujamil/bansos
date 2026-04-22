@extends('layouts.layout')
@section('title', $gallery ? 'Edit Foto Galeri' : 'Tambah Foto Galeri')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">{{ $gallery ? 'Edit Foto Galeri' : 'Tambah Foto Galeri' }}</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Landing Page</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelola.gallery.index') }}">Galeri</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ $gallery ? 'Edit' : 'Tambah' }}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('kelola.gallery.index') }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
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
                $isEdit = (bool) $gallery;
                $route  = $isEdit ? route('kelola.gallery.update', $gallery) : route('kelola.gallery.store');
                $method = $isEdit ? 'PUT' : 'POST';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label text-small text-uppercase">Judul Foto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                   name="judul" value="{{ old('judul', $gallery->judul ?? '') }}"
                                   required maxlength="255" placeholder="Contoh: Distribusi Bantuan Pangan 2025"/>
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label text-small text-uppercase">Urutan</label>
                            <input type="number" min="0" max="65535"
                                   class="form-control @error('urutan') is-invalid @enderror"
                                   name="urutan" value="{{ old('urutan', $gallery->urutan ?? 0) }}"/>
                        </div>
                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                    {{ old('is_active', $gallery === null || $gallery->is_active ? '1' : '') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Tampilkan</label>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">Keterangan (opsional)</label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror"
                                   name="keterangan" value="{{ old('keterangan', $gallery->keterangan ?? '') }}"
                                   maxlength="500" placeholder="Contoh: Kegiatan penyaluran bantuan pangan di Lombok Barat"/>
                            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">
                                Foto <span class="text-danger">{{ $isEdit ? '' : '*' }}</span>
                            </label>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                   name="gambar" accept="image/jpeg,image/png,image/webp,image/gif"
                                   {{ $isEdit ? '' : 'required' }}/>
                            <small class="text-muted">Format: JPG, PNG, WEBP, GIF. Maks 5 MB.</small>
                            @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if ($isEdit && $gallery->getFirstMediaUrl('galeri'))
                                <div class="mt-2">
                                    <span class="text-small text-muted d-block mb-1">Foto saat ini:</span>
                                    <img src="{{ $gallery->getFirstMediaUrl('galeri', 'thumb') ?: $gallery->getFirstMediaUrl('galeri') }}"
                                         alt="" class="rounded border" style="max-height:150px"/>
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
