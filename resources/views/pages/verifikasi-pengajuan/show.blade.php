@extends('layouts.layout')
@section('title', 'Detail Verifikasi Pengajuan')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Detail Verifikasi Pengajuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Verifikasi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('verifikasi-pengajuan.index') }}">Pengajuan Bantuan</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ $pengajuan->kode_pengajuan }}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-2">
                    <a href="{{ route('verifikasi-pengajuan.index') }}" class="btn btn-outline-secondary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        @php
            $status = $pengajuan->status;
            $badge = match($status) {
                \App\Enums\PengajuanStatus::DRAFT => 'secondary',
                \App\Enums\PengajuanStatus::DIAJUKAN => 'info',
                \App\Enums\PengajuanStatus::DISETUJUI => 'success',
                \App\Enums\PengajuanStatus::DITOLAK => 'danger',
                default => 'secondary',
            };
            $catatan = $pengajuan->catatan_verifikator ?? $pengajuan->catatan;
            $canVerify = in_array($pengajuan->status, [
                \App\Enums\PengajuanStatus::DIAJUKAN,
            ], true);
        @endphp

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="small-title mb-4">Informasi Pengajuan</h2>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Kode</span>
                        <div class="fw-semibold">{{ $pengajuan->kode_pengajuan }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Jenis</span>
                        <div class="fw-semibold">
                            {{ $pengajuan->jenisBantuan?->nama ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Status</span>
                        <div>
                            <span class="badge bg-{{ $badge }}">{{ $pengajuan->status->getDescription() }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Tanggal Dibuat</span>
                        <div>{{ $pengajuan->created_at?->translatedFormat('d F Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Pengaju</span>
                        <div>{{ $pengajuan->user?->nama ?? $pengajuan->user?->email ?? '-' }}</div>
                    </div>
                    @if($pengajuan->verified_at)
                        <div class="col-md-4 mb-3">
                            <span class="text-small text-uppercase text-muted">Diverifikasi Pada</span>
                            <div>{{ $pengajuan->verified_at->translatedFormat('d F Y H:i') }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="text-small text-uppercase text-muted">Oleh</span>
                            <div>{{ $pengajuan->verifiedBy?->nama ?? $pengajuan->verifiedBy?->email ?? '-' }}</div>
                        </div>
                    @endif
                </div>
                @if($catatan)
                    <div class="mt-3">
                        <span class="text-small text-uppercase text-muted">Catatan Verifikasi</span>
                        <div class="alert alert-light border mt-1 mb-0">{{ $catatan }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="small-title mb-4">Verifikasi</h2>

                @if(! $canVerify)
                    <div class="alert alert-info mb-0">
                        Pengajuan ini sudah diproses. Tidak ada aksi verifikasi yang tersedia.
                    </div>
                @else
                    <form
                        id="form-verifikasi-pengajuan"
                        action="{{ route('verifikasi-pengajuan.verifikasi', $pengajuan) }}"
                        method="POST"
                    >
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted text-small text-uppercase">Keputusan</label>
                                <select id="keputusan" class="form-select" name="keputusan" required>
                                    <option value="{{ \App\Enums\PengajuanStatus::DISETUJUI->value }}" @selected(old('keputusan') === \App\Enums\PengajuanStatus::DISETUJUI->value)>Disetujui</option>
                                    <option value="{{ \App\Enums\PengajuanStatus::DITOLAK->value }}" @selected(old('keputusan') === \App\Enums\PengajuanStatus::DITOLAK->value)>Ditolak</option>
                                </select>
                                @error('keputusan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label text-muted text-small text-uppercase">Catatan</label>
                                <textarea
                                    class="form-control"
                                    name="catatan"
                                    rows="4"
                                    placeholder="Tuliskan catatan/verifikasi untuk pengaju"
                                >{{ old('catatan') }}</textarea>
                                @error('catatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    Verifikasi
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        @if($pengajuan->logs->isNotEmpty())
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="small-title mb-4">Riwayat Aksi</h2>
                    <div class="timeline">
                        @foreach($pengajuan->logs as $log)
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge bg-light border">{{ $log->action }}</span>
                                        <small class="text-muted">{{ $log->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</small>
                                    </div>
                                    @if($log->status_to)
                                        <div class="mt-1 text-muted">
                                            Status: <span class="fw-semibold text-dark">{{ $log->status_to }}</span>
                                        </div>
                                    @endif
                                    @if($log->catatan)
                                        <div class="mt-2">
                                            <div class="small-title text-muted mb-1">Catatan</div>
                                            <div class="alert alert-light border mt-1 mb-0">{{ $log->catatan }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
@endpush

@push('js_vendor')
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
@endpush

@push('js_page')
    <script>
        $(document).ready(function () {
            const $keputusan = $('#keputusan');
            if ($keputusan.length) {
                $keputusan.select2({
                    theme: 'bootstrap4',
                    width: '100%',
                });
            }
        });

        const form = document.getElementById('form-verifikasi-pengajuan');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Verifikasi',
                    text: 'Apakah Anda yakin akan memproses verifikasi pengajuan ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, proses',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) form.submit();
                });
            });
        }
    </script>
@endpush

