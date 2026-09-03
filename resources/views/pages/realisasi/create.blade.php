@extends('layouts.layout')
@section('title', 'Dokumentasi Realisasi')
@section('content')
    @php
        $status = $pengajuan->status;
        $badge  = $status?->badgeColor() ?? 'secondary';
        $catatan = $pengajuan->catatan_verifikator ?? $pengajuan->catatan;
        $realisasi = $pengajuan->realisasi;
        $routeForm = $realisasi ? route('realisasi.update', $pengajuan) : route('realisasi.store', $pengajuan);
        $methodForm = $realisasi ? 'PUT' : 'POST';
    @endphp
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Dokumentasi Realisasi</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('realisasi.index') }}">Realisasi</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ $pengajuan->kode_pengajuan }}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('realisasi.index') }}" class="btn btn-outline-secondary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Informasi pengajuan (selaras verifikasi-pengajuan/show) --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h2 class="small-title mb-4">Informasi Pengajuan {{ $pengajuan->kode_pengajuan }}</h2>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Judul</span>
                        <div class="fw-semibold">{{ $pengajuan->judul ?? '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Jenis bantuan</span>
                        <div class="fw-semibold">{{ $pengajuan->jenisBantuan?->nama ?? '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Nilai usulan</span>
                        <div class="fw-semibold">Rp {{ number_format((float) $pengajuan->nilai, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Status</span>
                        <div>
                            <span class="badge bg-{{ $badge }}">{{ $pengajuan->status->getDescription() }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Tanggal dibuat</span>
                        <div>{{ $pengajuan->created_at?->translatedFormat('d F Y H:i') ?? '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Pengaju</span>
                        <div>{{ $pengajuan->user?->nama ?? ($pengajuan->user?->email ?? '—') }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Kelompok</span>
                        <div class="fw-semibold">{{ $pengajuan->organisasi?->nama ?? '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Lokasi kegiatan</span>
                        <div class="fw-semibold">
                            {{ $pengajuan->lokasi ?? '—' }},
                            {{ $pengajuan->desa?->nama ?? '—' }},
                            {{ $pengajuan->desa?->kecamatan?->nama ?? '—' }}
                        </div>
                    </div>
                    @if ($pengajuan->verifikasiPengajuan?->nilai_rekomendasi !== null)
                        <div class="col-md-4 mb-3">
                            <span class="text-small text-uppercase text-muted">Nilai rekomendasi</span>
                            <div class="fw-semibold text-primary">
                                Rp {{ number_format((float) $pengajuan->verifikasiPengajuan->nilai_rekomendasi, 0, ',', '.') }}
                            </div>
                        </div>
                    @endif
                    @if ($pengajuan->verified_at)
                        <div class="col-md-4 mb-3">
                            <span class="text-small text-uppercase text-muted">Diverifikasi pada</span>
                            <div>{{ $pengajuan->verified_at->translatedFormat('d F Y H:i') }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="text-small text-uppercase text-muted">Diverifikasi oleh</span>
                            <div>{{ $pengajuan->verifiedBy?->nama ?? ($pengajuan->verifiedBy?->email ?? '—') }}</div>
                        </div>
                    @endif
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Lampiran pengajuan</span>
                        <div>
                            @php $lampiran = $pengajuan->getFirstMedia('pengajuan'); @endphp
                            @if ($lampiran)
                                <a class="btn btn-sm btn-outline-primary" href="{{ $lampiran->getUrl() }}" target="_blank" rel="noopener noreferrer">
                                    Lihat dokumen
                                </a>
                                <div class="text-muted small mt-1">{{ $lampiran->file_name }}</div>
                            @else
                                <div class="text-muted">—</div>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($catatan)
                    <div class="mt-3">
                        <span class="text-small text-uppercase text-muted">Catatan</span>
                        <div class="alert alert-light border mt-1 mb-0">{{ $catatan }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- BAST & Pemeriksa --}}
        @if ($pengajuan->bast || $pengajuan->pemeriksa->isNotEmpty())
            <div class="row g-3 mb-4">
                @if ($pengajuan->bast)
                    <div class="{{ $pengajuan->pemeriksa->isNotEmpty() ? 'col-lg-8' : 'col-12' }}">
                        <div class="card h-100 border-start border-4 border-primary shadow-sm">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                                    <div>
                                        <h2 class="small-title mb-1">Berita Acara Serah Terima (BAST)</h2>
                                        <p class="text-muted small mb-0">Data serah terima bantuan untuk pengajuan ini.</p>
                                    </div>
                                    <span class="badge bg-primary align-self-center">Terverifikasi &amp; Diserahterimakan</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <span class="text-small text-uppercase text-muted">Nomor BAST</span>
                                        <div class="fw-semibold fs-5">{{ $pengajuan->bast->nomor }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-small text-uppercase text-muted">Tanggal BAST</span>
                                        <div class="fw-semibold">{{ $pengajuan->bast->tanggal?->translatedFormat('d F Y') ?? '—' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-small text-uppercase text-muted">Penerima</span>
                                        <div class="fw-semibold">{{ $pengajuan->bast->penerima }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="text-small text-uppercase text-muted">Dicatat oleh</span>
                                        <div>{{ $pengajuan->bast->user?->nama ?? ($pengajuan->bast->user?->email ?? '—') }}</div>
                                    </div>
                                </div>
                                @php
                                    $bastDokumen = $pengajuan->bast->getFirstMedia('dokumen');
                                    $bastFotos = $pengajuan->bast->getMedia('foto');
                                @endphp
                                <div class="border-top pt-3 mt-2">
                                    <span class="text-small text-uppercase text-muted d-block mb-3">Dokumentasi BAST</span>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <span class="text-small text-uppercase text-muted d-block mb-2">Dokumen PDF</span>
                                            @if ($bastDokumen)
                                                <a href="{{ $bastDokumen->getUrl() }}" target="_blank" rel="noopener noreferrer"
                                                   class="btn btn-sm btn-outline-danger btn-icon btn-icon-start">
                                                    <i data-acorn-icon="file-text" data-acorn-size="16"></i>
                                                    <span class="text-truncate" style="max-width: 12rem;">{{ $bastDokumen->file_name }}</span>
                                                </a>
                                            @else
                                                <div class="text-muted small mb-0">Tidak ada dokumen.</div>
                                            @endif
                                        </div>
                                        <div class="col-md-8">
                                            <span class="text-small text-uppercase text-muted d-block mb-2">
                                                Foto <span class="fw-normal">({{ $bastFotos->count() }})</span>
                                            </span>
                                            @if ($bastFotos->isNotEmpty())
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($bastFotos as $foto)
                                                        <a href="{{ $foto->getUrl() }}" target="_blank" rel="noopener noreferrer" class="d-block">
                                                            <img src="{{ $foto->getUrl() }}" alt="{{ $foto->file_name }}"
                                                                 class="rounded border"
                                                                 style="height: 72px; width: 72px; object-fit: cover;" />
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-muted small mb-0">Tidak ada foto.</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($pengajuan->pemeriksa->isNotEmpty())
                    <div class="{{ $pengajuan->bast ? 'col-lg-4' : 'col-12' }}">
                        <div class="card h-100 border-start border-4 border-primary shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <h2 class="small-title mb-1">Pemeriksa</h2>
                                    <p class="text-muted small mb-0">Tim pemeriksa pengajuan ini.</p>
                                </div>
                                <div class="table-responsive flex-grow-1">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Jabatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuan->pemeriksa as $pemeriksa)
                                                <tr>
                                                    <td class="fw-semibold">{{ $pemeriksa->nama }}</td>
                                                    <td>{{ $pemeriksa->nip }}</td>
                                                    <td>{{ $pemeriksa->jabatan }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- SP2D (dicatat bendahara pada halaman monitoring bantuan) --}}
        @if ($pengajuan->sp2d)
            @php
                $sp2dDokumen = $pengajuan->sp2d->getFirstMedia('dokumen');
            @endphp
            <div class="card mb-4 border-start border-4 border-success shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                        <div>
                            <h2 class="small-title mb-1">Surat Perintah Pencairan Dana (SP2D)</h2>
                            <p class="text-muted small mb-0">Data pencairan dana untuk pengajuan ini.</p>
                        </div>
                        <span class="badge bg-success align-self-center">Sudah SP2D</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <span class="text-small text-uppercase text-muted">Nomor SP2D</span>
                            <div class="fw-semibold fs-5">{{ $pengajuan->sp2d->nomor }}</div>
                        </div>
                        <div class="col-md-4">
                            <span class="text-small text-uppercase text-muted">Tanggal SP2D</span>
                            <div class="fw-semibold">{{ $pengajuan->sp2d->tanggal?->translatedFormat('d F Y') ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <span class="text-small text-uppercase text-muted">Nilai</span>
                            <div class="fw-semibold">
                                {{ $pengajuan->sp2d->nilai !== null ? 'Rp '.number_format((float) $pengajuan->sp2d->nilai, 0, ',', '.') : '—' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <span class="text-small text-uppercase text-muted">Dicatat oleh</span>
                            <div>{{ $pengajuan->sp2d->user?->nama ?? ($pengajuan->sp2d->user?->email ?? '—') }}</div>
                        </div>
                        <div class="col-md-8">
                            <span class="text-small text-uppercase text-muted">Keterangan</span>
                            <div>{{ $pengajuan->sp2d->keterangan ?: '—' }}</div>
                        </div>
                    </div>
                    <div class="border-top pt-3 mt-3">
                        <span class="text-small text-uppercase text-muted d-block mb-2">Dokumen SP2D</span>
                        @if ($sp2dDokumen)
                            <a href="{{ $sp2dDokumen->getUrl() }}" target="_blank" rel="noopener noreferrer"
                               class="btn btn-sm btn-outline-danger btn-icon btn-icon-start">
                                <i data-acorn-icon="file-text" data-acorn-size="16"></i>
                                <span class="text-truncate" style="max-width: 12rem;">{{ $sp2dDokumen->file_name }}</span>
                            </a>
                        @else
                            <div class="text-muted small mb-0">Tidak ada dokumen.</div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-light border d-flex align-items-center gap-2 mb-4" role="alert">
                <i data-acorn-icon="dollar" data-acorn-size="18" class="flex-shrink-0"></i>
                <span class="small mb-0">Belum ada SP2D untuk pengajuan ini. Nomor SP2D akan muncul di sini setelah bendahara mencatatnya.</span>
            </div>
        @endif

        {{-- Form dokumentasi realisasi --}}
        <div class="card mb-5 shadow-sm overflow-hidden">
            <div class="card-header bg-primary text-white py-3 border-0">
                <div class="d-flex align-items-center gap-2">
                    <i data-acorn-icon="notebook-1" class="text-white" data-acorn-size="20"></i>
                    <div>
                        <h2 class="small-title text-white mb-0">Unggah dokumentasi realisasi</h2>
                        <p class="mb-0 text-white opacity-75 small">Laporan kegiatan dan bukti pelaksanaan bantuan (PDF).</p>
                    </div>
                </div>
            </div>
            <form novalidate enctype="multipart/form-data" action="{{ $routeForm }}" method="POST" class="needs-validation">
                @csrf
                @method($methodForm)
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-small text-uppercase fw-semibold" for="keterangan">
                                Keterangan <span class="text-danger">*</span>
                            </label>
                            <textarea id="keterangan" name="keterangan" rows="3" required
                                      class="form-control @error('keterangan') is-invalid @enderror"
                                      placeholder="Ringkasan kegiatan realisasi, lokasi pelaksanaan, atau catatan penting.">{{ old('keterangan', $realisasi?->keterangan ?? '') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-5 col-lg-4">
                            <label class="form-label text-small text-uppercase fw-semibold" for="tanggal_laporan">
                                Tanggal laporan <span class="text-muted fw-normal">(opsional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i data-acorn-icon="calendar" data-acorn-size="16"></i></span>
                                <input type="text" id="tanggal_laporan" autocomplete="off"
                                       class="form-control border-start-0 @error('tanggal_laporan') is-invalid @enderror" name="tanggal_laporan"
                                       value="{{ old('tanggal_laporan', $realisasi?->tanggal_laporan?->format('d-m-Y') ?? '') }}"
                                       placeholder="dd-mm-yyyy" />
                                @error('tanggal_laporan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-7 col-lg-8">
                            <label class="form-label text-small text-uppercase fw-semibold" for="dokumen">
                                Dokumen laporan (PDF)
                                @unless($realisasi)
                                    <span class="text-danger">*</span>
                                @else
                                    <span class="text-muted fw-normal">(opsional)</span>
                                @endunless
                            </label>
                            <input type="file" id="dokumen" name="dokumen"
                                   class="form-control @error('dokumen') is-invalid @enderror"
                                   accept=".pdf,application/pdf" {{ $realisasi ? '' : 'required' }} />
                            <div class="form-text">Satu file PDF, maks. 5 MB.</div>
                            @error('dokumen')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($realisasi && $realisasi->getMedia('laporan_kegiatan')->count())
                            <div class="col-12">
                                <span class="form-label text-small text-uppercase fw-semibold d-block mb-2">Berkas yang sudah diunggah</span>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($realisasi->getMedia('laporan_kegiatan') as $media)
                                        <a href="{{ $media->getUrl() }}" target="_blank" rel="noopener noreferrer"
                                           class="btn btn-outline-primary btn-sm rounded-pill">
                                            <i data-acorn-icon="file-text" data-acorn-size="14" class="me-1"></i>
                                            {{ $media->name }}
                                        </a>
                                    @endforeach
                                </div>
                                <p class="text-muted small mt-2 mb-0">Mengunggah file baru akan mengganti semua dokumen sebelumnya.</p>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="check"></i>
                            <span>{{ $realisasi ? 'Simpan perubahan' : 'Simpan dokumentasi' }}</span>
                        </button>
                        <a href="{{ route('realisasi.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-datepicker3.standalone.min.css') }}">
@endpush
@push('js_vendor')
    <script src="{{ asset('js/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $("document").ready(function () {
            $('#tanggal_laporan').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                orientation: 'bottom',
            });
        });
    </script>
@endpush
@include('components.form_validation')
