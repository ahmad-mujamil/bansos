@extends('layouts.layout')
@section('title', $berita ? 'Edit Berita' : 'Tambah Berita')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">{{ $berita ? 'Edit Berita' : 'Tambah Berita' }}</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Landing Page</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelola.berita.index') }}">Berita</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ $berita ? 'Edit' : 'Tambah' }}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('kelola.berita.index') }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
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
                $isEdit = (bool) $berita;
                $route = $isEdit ? route('kelola.berita.update', $berita) : route('kelola.berita.store');
                $method = $isEdit ? 'PUT' : 'POST';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase" for="kategori_berita_id">Kategori</label>
                            <select id="kategori_berita_id" name="kategori_berita_id"
                                    class="form-control @error('kategori_berita_id') is-invalid @enderror" required>
                                <option value="">Pilih kategori</option>
                                @foreach($kategoriBeritas as $kat)
                                    <option value="{{ $kat->id }}"
                                        @selected((string) old('kategori_berita_id', $berita?->kategori_berita_id) === (string) $kat->id)>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_berita_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-8 col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase">Judul</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul"
                                   value="{{ old('judul', $berita->judul ?? '') }}" required maxlength="255"/>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">Ringkasan</label>
                            <textarea class="form-control @error('ringkasan') is-invalid @enderror" name="ringkasan" rows="3" required maxlength="500">{{ old('ringkasan', $berita->ringkasan ?? '') }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">Konten</label>
                            <textarea class="form-control @error('konten') is-invalid @enderror" name="konten" rows="12" required>{{ old('konten', $berita->konten ?? '') }}</textarea>
                            <small class="text-muted">Mendukung HTML sederhana (mis. &lt;p&gt;, &lt;strong&gt;).</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">Gambar utama</label>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar" accept="image/jpeg,image/png,image/webp,image/gif" {{ $isEdit ? '' : 'required' }}/>
                            @if($isEdit && $berita->getFirstMediaUrl('featured'))
                                <div class="mt-2">
                                    <span class="text-small text-muted d-block mb-1">Gambar saat ini:</span>
                                    <img src="{{ $berita->getFirstMediaUrl('featured') }}" alt="" class="rounded border" style="max-height:120px"/>
                                </div>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terbit" value="1" id="terbit"
                                    {{ old('terbit', $berita && $berita->isPublished() ? '1' : null) ? 'checked' : '' }}>
                                <label class="form-check-label" for="terbit">Terbitkan (tampil di portal publik)</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@include('components.form_validation')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}" />
@endpush
@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#kategori_berita_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih kategori berita',
                allowClear: false,
                width: '100%',
            });
        });
    </script>
@endpush
