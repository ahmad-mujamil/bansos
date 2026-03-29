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
                        <li class="breadcrumb-item"><a href="{{ route('verifikasi-pengajuan.index') }}">Pengajuan
                                Bantuan</a></li>
                        <li class="breadcrumb-item"><a href="javascript:;">{{ $pengajuan->kode_pengajuan }}</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-2">
                <a href="{{ route('verifikasi-pengajuan.index') }}"
                    class="btn btn-outline-secondary btn-icon btn-icon-start w-100 w-md-auto">
                    <i data-acorn-icon="arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    @php
    $status = $pengajuan->status;
    $badge = match ($status) {
    \App\Enums\PengajuanStatus::DRAFT => 'secondary',
    \App\Enums\PengajuanStatus::DIAJUKAN => 'info',
    \App\Enums\PengajuanStatus::DISETUJUI => 'success',
    \App\Enums\PengajuanStatus::DITOLAK => 'danger',
    default => 'secondary',
    };
    $catatan = $pengajuan->catatan_verifikator ?? $pengajuan->catatan;
    $canVerify = in_array($pengajuan->status, [\App\Enums\PengajuanStatus::DIAJUKAN], true);
    @endphp

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="small-title mb-4">Informasi Pengajuan {{ $pengajuan->kode_pengajuan }}</h2>
            <div class="row">

                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Judul</span>
                    <div class="fw-semibold">
                        {{ $pengajuan->judul ?? '-' }}
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Nilai Usulan</span>
                    <div class="fw-semibold">{{ number_format($pengajuan->nilai, 0, ',', '.') }}</div>
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
                    <div>{{ $pengajuan->user?->nama ?? ($pengajuan->user ?? '-') }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Kelompok</span>
                    <div class="fw-semibold">{{ $pengajuan->organisasi?->nama ?? '—' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Lokasi Kegiatan</span>
                    <div class="fw-semibold">{{ $pengajuan->lokasi ?? '—' }}, {{ $pengajuan->desa?->nama ?? '—' }}, {{ $pengajuan->desa?->kecamatan?->nama ?? '—' }}</div>
                </div>
                @if ($pengajuan->verified_at)
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Diverifikasi Pada</span>
                    <div>{{ $pengajuan->verified_at->translatedFormat('d F Y H:i') }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Diverifikasi Oleh</span>
                    <div>{{ $pengajuan->verifiedBy?->nama ?? ($pengajuan->verifiedBy?->email ?? '-') }}</div>
                </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Lampiran</span>
                    <div>
                        @php
                        $lampiran = $pengajuan->getFirstMedia('pengajuan');
                        @endphp
                        @if ($lampiran)
                        <a class="btn btn-sm btn-outline-primary" href="{{ $lampiran->getUrl() }}" target="_blank"
                            rel="noopener noreferrer">
                            Lihat Dokumen
                        </a>
                        <div class="text-muted small mt-1">{{ $lampiran->file_name }}</div>
                        @else
                        <div class="text-muted">-</div>
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

    @if ($pengajuan->pemeriksa->isNotEmpty())
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="small-title mb-4">Pemeriksa</h2>
            <div class="table-responsive">
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
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="small-title mb-4">Verifikasi</h2>

            <livewire:verifikasi-pengajuan-form :pengajuan="$pengajuan" />
        </div>
    </div>

    @if ($pengajuan->logs->isNotEmpty())
    <!-- <div class="card mb-4">
        <div class="card-body">
            <h2 class="small-title mb-4">Riwayat Aksi</h2>
            <div class="timeline">
                @foreach ($pengajuan->logs as $log)
                <div class="timeline-item">
                    <div class="timeline-content">
                        @php
                        $badgeLog = match ($log->status_to) {
                        \App\Enums\PengajuanStatus::DRAFT->value => 'secondary',
                        \App\Enums\PengajuanStatus::DIAJUKAN->value => 'info',
                        \App\Enums\PengajuanStatus::DISETUJUI->value => 'success',
                        \App\Enums\PengajuanStatus::DITOLAK->value => 'danger',
                        default => 'secondary',
                        };
                        @endphp

                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <span class="badge bg-light border text-dark">
                                        <i data-acorn-icon="check-circle" class="me-1"></i>
                                        {{ $log->action }}
                                    </span>
                                    @if ($log->status_to)
                                    <span class="badge bg-{{ $badgeLog }}">
                                        {{ strtoupper($log->status_to) }}
                                    </span>
                                    @endif
                                </div>
                                <div class="text-muted small mt-1">
                                    {{ $log->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                                    @if($log->user)
                                    <span class="mx-1">•</span>
                                    {{ $log->user?->nama ?? $log->user?->email }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($log->catatan)
                        <div class="mt-2">
                            <div class="small-title text-muted mb-1">Catatan</div>
                            <div class="alert alert-light border mb-0">
                                {{ $log->catatan }}
                            </div>
                        </div>
                        @endif

                        @php($verifikasiId = $log->metadata['verifikasi_pengajuan_id'] ?? null)
                        @if ($verifikasiId)
                        @php($bantuanUang = $bantuanUangByVerifikasi[$verifikasiId] ?? collect())
                        @php($bantuanBarangJasa = $bantuanBarangJasaByVerifikasi[$verifikasiId] ?? collect())

                        @if ($bantuanUang->isNotEmpty())
                        <div class="mt-3">
                            @php($totalBantuanUang = $bantuanUang->sum(fn($row) => (float) $row->nilai))
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small-title text-muted mb-0">Bantuan Uang</div>
                                <div class="text-muted small">Total: <span class="fw-semibold text-dark">{{ number_format((float) $totalBantuanUang, 2, ',', '.') }}</span></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Penduduk</th>
                                            <th class="text-end" style="width: 180px;">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bantuanUang as $row)
                                        <tr>
                                            <td>{{ $row->penduduk?->nama ?? $row->penduduk_id }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format((float) $row->nilai, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-end">Total</th>
                                            <th class="text-end fw-semibold">
                                                {{ number_format((float) $totalBantuanUang, 2, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @endif

                        @if ($bantuanBarangJasa->isNotEmpty())
                        <div class="mt-3">
                            @php($totalBarangJasa = $bantuanBarangJasa->sum(fn($row) => (float) $row->harga_satuan * (int) $row->qty))
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small-title text-muted mb-0">Bantuan Barang / Jasa</div>
                                <div class="text-muted small">Total: <span class="fw-semibold text-dark">{{ number_format((float) $totalBarangJasa, 2, ',', '.') }}</span></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th style="width: 120px;">Satuan</th>
                                            <th class="text-end" style="width: 90px;">Qty</th>
                                            <th class="text-end" style="width: 160px;">Harga Satuan</th>
                                            <th class="text-end" style="width: 180px;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bantuanBarangJasa as $row)
                                        @php($subtotal = (float) $row->harga_satuan * (int) $row->qty)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $row->nama_barang }}
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $row->spesifikasi }}
                                                </div>
                                            </td>
                                            <td>{{ $row->satuan }}</td>
                                            <td class="text-end">{{ $row->qty }}</td>
                                            <td class="text-end">
                                                {{ number_format((float) $row->harga_satuan, 2, ',', '.') }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format((float) $subtotal, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Total</th>
                                            <th class="text-end fw-semibold">
                                                {{ number_format((float) $totalBarangJasa, 2, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div> -->
    @endif
</div>
@endsection

@push('js_vendor')
<script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@endpush

@include('components.number-format')