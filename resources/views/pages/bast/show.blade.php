@extends('layouts.layout')
@section('title', 'Detail BAST')
@section('content')
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Detail BA Serah Terima</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">BA Serah Terima</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('bast.index') }}">BAST</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ $bast->nomor }}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-1">
                    <a href="{{ route('bast.edit', $bast) }}" class="btn btn-outline-primary btn-icon btn-icon-start">
                        <i data-acorn-icon="edit"></i>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('bast.index') }}" class="btn btn-outline-secondary btn-icon btn-icon-start">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        {{-- BAST Info --}}
        <div class="card mb-3">
            <div class="card-body py-3">
                <h2 class="small-title mb-3">Informasi BAST</h2>
                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Nomor BAST</p>
                        <p class="fw-semibold mb-0">{{ $bast->nomor }}</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Tanggal</p>
                        <p class="mb-0">{{ $bast->tanggal?->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Penerima</p>
                        <p class="mb-0">{{ $bast->penerima }}</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Dibuat Oleh</p>
                        <p class="mb-0">{{ $bast->user?->nama ?? $bast->user?->email ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pengajuan + Verifikasi side-by-side --}}
        @php
            $pengajuan    = $bast->pengajuan;
            $verifikasi   = $pengajuan?->verifikasiPengajuan;
            $statusBadge = $pengajuan?->status?->badgeColor() ?? 'secondary';
        @endphp

        <div class="row mb-3">
            <div class="col-lg-6 mb-3 mb-lg-0">
                <div class="card h-100">
                    <div class="card-body py-3">
                        <h2 class="small-title mb-3">Detail Pengajuan</h2>
                        @if($pengajuan)
                            <div class="row g-2">
                                <div class="col-6">
                                    <p class="text-small text-uppercase text-muted mb-1">Kode</p>
                                    <p class="fw-semibold mb-0">{{ $pengajuan->kode_pengajuan }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-small text-uppercase text-muted mb-1">Status</p>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $statusBadge }}">{{ $pengajuan->status->getDescription() }}</span>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p class="text-small text-uppercase text-muted mb-1">Judul</p>
                                    <p class="mb-0">{{ $pengajuan->judul }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-small text-uppercase text-muted mb-1">Jenis Bantuan</p>
                                    <p class="mb-0">{{ $pengajuan->jenisBantuan?->nama ?? '-' }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-small text-uppercase text-muted mb-1">Nilai Pengajuan</p>
                                    <p class="mb-0">Rp {{ number_format($pengajuan->nilai, 0, ',', '.') }}</p>
                                </div>
                                @if($pengajuan->lokasi)
                                    <div class="col-12">
                                        <p class="text-small text-uppercase text-muted mb-1">Lokasi</p>
                                        <p class="mb-0">{{ $pengajuan->lokasi }}</p>
                                    </div>
                                @endif
                                @if($pengajuan->catatan)
                                    <div class="col-12">
                                        <p class="text-small text-uppercase text-muted mb-1">Catatan</p>
                                        <p class="mb-0 text-muted fst-italic">{{ $pengajuan->catatan }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-muted mb-0">Data pengajuan tidak tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body py-3">
                        <h2 class="small-title mb-3">Detail Verifikasi</h2>
                        @if($verifikasi)
                            <div class="row g-2">
                                <div class="col-6">
                                    <p class="text-small text-uppercase text-muted mb-1">Nilai Rekomendasi</p>
                                    <p class="fw-semibold mb-0">
                                        @if($verifikasi->nilai_rekomendasi !== null)
                                            Rp {{ number_format($verifikasi->nilai_rekomendasi, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="text-small text-uppercase text-muted mb-1">Diverifikasi Oleh</p>
                                    <p class="mb-0">{{ $verifikasi->user?->nama ?? $verifikasi->user?->email ?? '-' }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-small text-uppercase text-muted mb-1">Lulus Kriteria</p>
                                    <p class="mb-0">
                                        @if($verifikasi->lulus_kriteria)
                                            <span class="badge bg-success">Ya</span>
                                        @else
                                            <span class="badge bg-danger">Tidak</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="text-small text-uppercase text-muted mb-1">Lulus Administrasi</p>
                                    <p class="mb-0">
                                        @if($verifikasi->lulus_administrasi)
                                            <span class="badge bg-success">Ya</span>
                                        @else
                                            <span class="badge bg-danger">Tidak</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="text-small text-uppercase text-muted mb-1">Lulus Kesesuaian</p>
                                    <p class="mb-0">
                                        @if($verifikasi->lulus_kesesuaian)
                                            <span class="badge bg-success">Ya</span>
                                        @else
                                            <span class="badge bg-danger">Tidak</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="text-small text-uppercase text-muted mb-1">Sesuai Program Pemda</p>
                                    <p class="mb-0">
                                        @if($verifikasi->sesuai_program_pemda)
                                            <span class="badge bg-success">Ya</span>
                                        @else
                                            <span class="badge bg-danger">Tidak</span>
                                        @endif
                                    </p>
                                </div>
                                @if($verifikasi->catatan)
                                    <div class="col-12">
                                        <p class="text-small text-uppercase text-muted mb-1">Catatan Verifikator</p>
                                        <p class="mb-0 text-muted fst-italic">{{ $verifikasi->catatan }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-muted mb-0">Belum ada data verifikasi.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Dokumen + Foto side-by-side --}}
        @php $dokumen = $bast->getFirstMedia('dokumen'); $fotos = $bast->getMedia('foto'); @endphp
        <div class="row mb-5">
            <div class="col-lg-4 mb-3 mb-lg-0">
                <div class="card h-100">
                    <div class="card-body py-3">
                        <h2 class="small-title mb-3">Dokumen PDF</h2>
                        @if($dokumen)
                            <a href="{{ $dokumen->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-danger btn-icon btn-icon-start">
                                <i data-acorn-icon="file-text"></i>
                                <span>{{ $dokumen->file_name }}</span>
                            </a>
                        @else
                            <p class="text-muted mb-0">Tidak ada dokumen.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body py-3">
                        <h2 class="small-title mb-3">Foto <span class="text-muted fw-normal">({{ $fotos->count() }})</span></h2>
                        @if($fotos->count())
                            <div class="row g-2">
                                @foreach($fotos as $foto)
                                    <div class="col-4 col-md-3 col-lg-2">
                                        <a href="{{ $foto->getUrl() }}" target="_blank">
                                            <img src="{{ $foto->getUrl() }}" alt="{{ $foto->file_name }}"
                                                 class="img-fluid rounded" style="height: 80px; width: 100%; object-fit: cover;" />
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">Tidak ada foto.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
