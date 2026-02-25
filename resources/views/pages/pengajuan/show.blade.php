@extends('layouts.layout')
@section('title', 'Detail Pengajuan')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Detail Pengajuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pengajuan.index') }}">Pengajuan</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ $pengajuan->kode_pengajuan }}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-1">
                    @if($pengajuan->canEdit())
                        <a href="{{ route('pengajuan.edit', $pengajuan) }}" class="btn btn-outline-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="edit"></i>
                            <span>Edit</span>
                        </a>
                    @endif
                    @if($pengajuan->canSubmit())
                        <form action="{{ route('pengajuan.submit', $pengajuan) }}" method="POST" class="d-inline form-ajukan-pengajuan">
                            @csrf
                            <button type="submit" class="btn btn-success btn-icon btn-icon-start">
                                <i data-acorn-icon="send"></i>
                                <span>Ajukan</span>
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-secondary btn-icon btn-icon-start">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        @php
            $detail = $pengajuan->details->first();
            $status = $pengajuan->status;
            $badge = match($status) {
                \App\Enums\PengajuanStatus::DRAFT => 'secondary',
                \App\Enums\PengajuanStatus::DIAJUKAN => 'info',
                \App\Enums\PengajuanStatus::VERIFIKASI_ADMINISTRASI, \App\Enums\PengajuanStatus::VERIFIKASI_TEKNIS => 'warning',
                \App\Enums\PengajuanStatus::DISETUJUI, \App\Enums\PengajuanStatus::SELESAI => 'success',
                \App\Enums\PengajuanStatus::DITOLAK => 'danger',
                \App\Enums\PengajuanStatus::REVISI => 'primary',
                default => 'secondary',
            };
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
                        <div class="fw-semibold">{{ $pengajuan->jenis->getDescription() }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Status</span>
                        <div><span class="badge bg-{{ $badge }}">{{ $pengajuan->status->getDescription() }}</span></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Tanggal Dibuat</span>
                        <div>{{ $pengajuan->created_at->translatedFormat('d F Y H:i') }}</div>
                    </div>
                    @if($pengajuan->catatan_verifikator)
                        <div class="col-12 mb-3">
                            <span class="text-small text-uppercase text-muted">Catatan Verifikator</span>
                            <div class="alert alert-light border mt-1 mb-0">{{ $pengajuan->catatan_verifikator }}</div>
                        </div>
                    @endif
                    @if($pengajuan->verified_at)
                        <div class="col-md-4 mb-3">
                            <span class="text-small text-uppercase text-muted">Diverifikasi Pada</span>
                            <div>{{ $pengajuan->verified_at->translatedFormat('d F Y H:i') }}</div>
                        </div>
                        @if($pengajuan->verifiedBy)
                            <div class="col-md-4 mb-3">
                                <span class="text-small text-uppercase text-muted">Oleh</span>
                                <div>{{ $pengajuan->verifiedBy->nama ?? $pengajuan->verifiedBy->email ?? '-' }}</div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @if($detail)
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="small-title mb-4">Detail Usulan</h2>
                    <div class="row">
                        @if($detail->kelompok_id)
                            <div class="col-md-6 mb-3">
                                <span class="text-small text-uppercase text-muted">Kelompok</span>
                                <div>{{ $detail->kelompok?->nama ?? '-' }}</div>
                            </div>
                        @endif
                        @if($detail->penduduk_id)
                            <div class="col-md-6 mb-3">
                                <span class="text-small text-uppercase text-muted">Penerima (Penduduk)</span>
                                <div>{{ $detail->penduduk?->nama ?? '-' }} @if($detail->penduduk)(NIK: {{ $detail->penduduk->nik }})@endif</div>
                            </div>
                        @endif
                        <div class="col-12 mb-3">
                            <span class="text-small text-uppercase text-muted">Judul Usulan</span>
                            <div class="fw-semibold">{{ $detail->judul_usulan }}</div>
                        </div>
                        @if($detail->latar_belakang)
                            <div class="col-12 mb-3">
                                <span class="text-small text-uppercase text-muted">Latar Belakang</span>
                                <div>{{ $detail->latar_belakang }}</div>
                            </div>
                        @endif
                        @if($detail->tujuan)
                            <div class="col-12 mb-3">
                                <span class="text-small text-uppercase text-muted">Tujuan</span>
                                <div>{{ $detail->tujuan }}</div>
                            </div>
                        @endif
                        @if($detail->lokasi_kegiatan)
                            <div class="col-md-6 mb-3">
                                <span class="text-small text-uppercase text-muted">Lokasi Kegiatan</span>
                                <div>{{ $detail->lokasi_kegiatan }}</div>
                            </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <span class="text-small text-uppercase text-muted">Nilai Usulan</span>
                            <div class="fw-semibold">Rp {{ number_format($detail->nilai_usulan, 0, ',', '.') }}</div>
                        </div>
                        @if($detail->tanggal_pengajuan)
                            <div class="col-md-6 mb-3">
                                <span class="text-small text-uppercase text-muted">Tanggal Pengajuan</span>
                                <div>{{ $detail->tanggal_pengajuan->translatedFormat('d F Y') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- Page Content End -->
@endsection

@push('js_vendor')
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@endpush

@push('js_page')
    <script>
        document.querySelectorAll('form.form-ajukan-pengajuan').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var f = this;
                Swal.fire({
                    title: 'Ajukan Pengajuan',
                    text: 'Apakah Anda yakin ingin mengajukan pengajuan ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, ajukan',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) f.submit();
                });
            });
        });
    </script>
@endpush
