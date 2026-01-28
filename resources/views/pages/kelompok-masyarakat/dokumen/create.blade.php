@extends('layouts.layout')
@section('title', 'Upload Dokumen - ' . $organisasi->nama)
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Upload Dokumen</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelompok-masyarakat.index') }}">Kelompok Masyarakat</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelompok-masyarakat.dokumen.index', $organisasi->id) }}">Dokumen - {{ $organisasi->nama }}</a></li>
                            <li class="breadcrumb-item">Upload</li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('kelompok-masyarakat.dokumen.index', $organisasi->id) }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-5">
            <form action="{{ route('kelompok-masyarakat.dokumen.store', $organisasi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                            <select name="jenis_dokumen" id="jenis_dokumen" class="form-select @error('jenis_dokumen') is-invalid @enderror" required>
                                <option value="">Pilih Jenis Dokumen</option>
                                @foreach(\App\Enums\JenisDokumen::cases() as $jenis)
                                    <option value="{{ $jenis->value }}" {{ old('jenis_dokumen') == $jenis->value ? 'selected' : '' }}>
                                        {{ $jenis->getDescription() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" value="{{ old('keterangan') }}" required maxlength="255" placeholder="Contoh: NPWP atas nama organisasi">
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                            <small class="text-muted">PDF, JPG, PNG, WebP. Maks. 10 MB</small>
                            @error('file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Upload Dokumen</button>
                </div>
            </form>
        </div>
    </div>
@endsection
