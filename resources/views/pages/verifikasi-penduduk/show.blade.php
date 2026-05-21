@extends('layouts.layout')
@section('title', 'Detail Verifikasi Penduduk')
@section('content')
<div class="col">
    <!-- Page Title Start -->
    <div class="page-title-container mb-3">
        <div class="row">
            <div class="col mb-2">
                <h1 class="mb-2 pb-0 display-4">Detail Verifikasi Penduduk</h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:;">Verifikasi</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('verifikasi-penduduk.index') }}">Penduduk</a></li>
                        <li class="breadcrumb-item"><a href="javascript:;">{{ $penduduk->nik }}</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-2">
                <a href="{{ route('verifikasi-penduduk.index') }}"
                    class="btn btn-outline-secondary btn-icon btn-icon-start w-100 w-md-auto">
                    <i data-acorn-icon="arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Page Title End -->

    <div class="row">
        <div class="col-lg-6">
        <!-- Info Penduduk Start -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="small-title mb-4">Informasi Penduduk</h2>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">NIK</span>
                        <div class="fw-semibold">{{ $penduduk->nik }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Nama Lengkap</span>
                        <div class="fw-semibold">{{ $penduduk->nama }}</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Agama</span>
                        <div class="fw-semibold">{{ $penduduk->agama }}</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <span class="text-small text-uppercase text-muted">Alamat</span>
                        <div>{{ $penduduk->alamat?? '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Jenis Kelamin</span>
                        <div>{{ $penduduk->jk?->getDescription() ?? '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Tanggal Lahir</span>
                        <div>{{ $penduduk->tgl_lahir?->translatedFormat('d F Y') ?? '-' }}</div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="text-small text-uppercase text-muted">Pekerjaan</span>
                        <div class="fw-semibold">{{ $penduduk->pekerjaan }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Desa</span>
                        <div>{{ $penduduk->desa?->nama ?? '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Kecamatan</span>
                        <div>{{ $penduduk->desa?->kecamatan?->nama ?? '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Status Validasi</span>
                        <div>
                            @if ($penduduk->is_valid)
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Diverifikasi</span>
                            @endif
                        </div>
                    </div>
                    @if ($penduduk->validated_at)
                        <div class="col-md-4 mb-3">
                            <span class="text-small text-uppercase text-muted">Tanggal Diverifikasi</span>
                            <div>{{ $penduduk->validated_at->translatedFormat('d F Y H:i') }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="text-small text-uppercase text-muted">Diverifikasi Oleh</span>
                            <div>{{ $penduduk->validatedBy?->nama ?? $penduduk->validatedBy?->email ?? '-' }}</div>
                        </div>
                    @endif
                    @if ($penduduk->catatan_validasi)
                        <div class="col-12 mb-3">
                            <span class="text-small text-uppercase text-muted">Catatan Validasi Sebelumnya</span>
                            <div class="p-3 bg-light rounded mt-1">{{ $penduduk->catatan_validasi }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Info Penduduk End -->
        </div>
        <div class="col-lg-6">
        <!-- Form Verifikasi Start -->
        <div class="card mb-5">
            <div class="card-body">
                <h2 class="small-title mb-4">Form Verifikasi</h2>
                <form action="{{ route('verifikasi-penduduk.verifikasi', $penduduk->id) }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label class="form-label text-small text-uppercase fw-semibold">Keputusan Verifikasi <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input
                                    class="form-check-input @error('is_valid') is-invalid @enderror"
                                    type="radio"
                                    name="is_valid"
                                    id="valid_yes"
                                    value="1"
                                    {{ old('is_valid', $penduduk->is_valid ? '1' : '') == '1' ? 'checked' : '' }}
                                    required
                                >
                                <label class="form-check-label text-success fw-semibold" for="valid_yes">
                                    <i data-acorn-icon="check-circle" class="me-1"></i> Valid / Terverifikasi
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                    class="form-check-input @error('is_valid') is-invalid @enderror"
                                    type="radio"
                                    name="is_valid"
                                    id="valid_no"
                                    value="0"
                                    {{ old('is_valid', !$penduduk->is_valid && $penduduk->validated_at ? '0' : '') == '0' ? 'checked' : '' }}
                                    required
                                >
                                <label class="form-check-label text-danger fw-semibold" for="valid_no">
                                    <i data-acorn-icon="close-circle" class="me-1"></i> Tidak Valid
                                </label>
                            </div>
                        </div>
                        @error('is_valid')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="catatan_validasi" class="form-label text-small text-uppercase fw-semibold">Catatan Validasi</label>
                        <textarea
                            class="form-control @error('catatan_validasi') is-invalid @enderror"
                            id="catatan_validasi"
                            name="catatan_validasi"
                            rows="4"
                            maxlength="500"
                            placeholder="Tambahkan catatan verifikasi (opsional)..."
                        >{{ old('catatan_validasi', $penduduk->catatan_validasi) }}</textarea>
                        @error('catatan_validasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maksimal 500 karakter.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="check"></i>
                            <span>Simpan Verifikasi</span>
                        </button>
                        <a href="{{ route('verifikasi-penduduk.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Form Verifikasi End -->
        </div>
    </div>

    <!-- History Verifikasi Start -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-5">
                <div class="card-body">
                    <h2 class="small-title mb-4">Riwayat Verifikasi</h2>
                    @if ($penduduk->historyVerifikasi->isEmpty())
                        <div class="text-muted">Belum ada riwayat verifikasi.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 180px;">Waktu</th>
                                        <th style="width: 160px;">Aksi</th>
                                        <th style="width: 140px;">Status</th>
                                        <th>Catatan</th>
                                        <th style="width: 200px;">Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penduduk->historyVerifikasi as $history)
                                        <tr>
                                            <td>{{ $history->created_at?->translatedFormat('d M Y H:i') }}</td>
                                            <td>
                                                <span class="badge {{ $history->badgeClass() }}">
                                                    {{ $history->actionLabel() }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($history->statusLabel())
                                                    <span class="fw-semibold">{{ $history->statusLabel() }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $history->catatan ?? '-' }}</td>
                                            <td>{{ $history->user?->nama ?? $history->user?->email ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- History Verifikasi End -->
</div>
@endsection
