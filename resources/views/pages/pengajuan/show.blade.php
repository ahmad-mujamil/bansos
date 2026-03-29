@extends('layouts.layout')
@section('title', 'Detail Pengajuan')
@push('css')
<style>
    .pengajuan-hero {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 45%, #1e3a8a 100%);
        border-radius: 1rem;
        color: #fff;
        overflow: hidden;
        position: relative;
    }
    .pengajuan-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 100% 0%, rgba(255,255,255,0.12) 0%, transparent 45%);
        pointer-events: none;
    }
    .pengajuan-hero .pengajuan-hero-inner {
        position: relative;
        z-index: 1;
    }
    .pengajuan-hero .text-hero-muted {
        color: rgba(255, 255, 255, 0.82);
    }
    .pengajuan-hero .display-hero-nilai {
        font-size: clamp(1.35rem, 3vw, 1.85rem);
        font-weight: 700;
        letter-spacing: -0.02em;
    }
    .pengajuan-field-tile {
        border-radius: 0.75rem;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .pengajuan-field-tile:hover {
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.06);
    }
    .pengajuan-timeline-wrap {
        position: relative;
        padding-left: 0.5rem;
    }
    .pengajuan-timeline-wrap::before {
        content: '';
        position: absolute;
        left: 0.65rem;
        top: 0.5rem;
        bottom: 0.5rem;
        width: 2px;
        background: linear-gradient(180deg, var(--bs-primary), var(--bs-border-color));
        opacity: 0.35;
        border-radius: 2px;
    }
    .pengajuan-timeline-item {
        position: relative;
        padding-left: 1.75rem;
        margin-bottom: 1rem;
    }
    .pengajuan-timeline-item:last-child {
        margin-bottom: 0;
    }
    .pengajuan-timeline-dot {
        position: absolute;
        left: 0.2rem;
        top: 0.65rem;
        width: 0.65rem;
        height: 0.65rem;
        border-radius: 50%;
        background: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.2);
    }
    .pengajuan-log-card {
        border-radius: 0.75rem;
    }
</style>
@endpush
@section('content')
<!-- Page Content Start -->
<div class="col">
    <div class="page-title-container mb-3">
        <div class="row align-items-start">
            <div class="col mb-2">
                <h1 class="mb-1 pb-0 display-4">Detail Pengajuan</h1>
                <p class="text-muted mb-2 small">Lihat ringkasan, lokasi, dan riwayat proses pengajuan Anda.</p>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0 mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengajuan.index') }}">Pengajuan</a></li>
                        <li class="breadcrumb-item"><a href="javascript:;">{{ $pengajuan->kode_pengajuan }}</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-12 col-lg-auto d-flex flex-wrap align-items-stretch justify-content-lg-end gap-2 mt-2 mt-lg-0">
                @if($pengajuan->canEdit())
                <a href="{{ route('pengajuan.edit', $pengajuan) }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                    <i data-acorn-icon="edit"></i>
                    <span>Edit</span>
                </a>
                @endif
                @if($pengajuan->canSubmit())
                <form action="{{ route('pengajuan.submit', $pengajuan) }}" method="POST" class="d-inline form-ajukan-pengajuan w-100 w-md-auto">
                    @csrf
                    <button type="submit" class="btn btn-success btn-icon btn-icon-start w-100">
                        <i data-acorn-icon="send"></i>
                        <span>Ajukan</span>
                    </button>
                </form>
                @endif
                <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-secondary btn-icon btn-icon-start w-100 w-md-auto">
                    <i data-acorn-icon="arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @php
    $catatan = $pengajuan->catatan_verifikator ?? $pengajuan->catatan;
    $lampiran = $pengajuan->getFirstMedia('pengajuan');
    @endphp

    <div class="card border-0 shadow-sm mb-4 pengajuan-hero">
        <div class="card-body p-4 p-md-5 pengajuan-hero-inner">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="text-hero-muted text-small text-uppercase mb-2 tracking-wide">Kode pengajuan</div>
                    <div class="font-monospace fs-5 fw-semibold mb-3">{{ $pengajuan->kode_pengajuan }}</div>
                    <h2 class="h3 text-white mb-3 lh-sm">{{ $pengajuan->judul }}</h2>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-semibold">
                            {{ $pengajuan->status->getDescription() }}
                        </span>
                        <span class="text-hero-muted small d-inline-flex align-items-center gap-1">
                            <i data-acorn-icon="calendar" class="icon" data-acorn-size="14"></i>
                            {{ $pengajuan->created_at->translatedFormat('d F Y · H:i') }}
                        </span>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="text-hero-muted text-small text-uppercase mb-1">Nilai usulan</div>
                    <div class="display-hero-nilai text-white">Rp {{ number_format($pengajuan->nilai, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4 align-items-stretch">
        <div class="col-12 {{ $pengajuan->logs->isNotEmpty() ? 'col-lg-8' : '' }}">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary text-white p-2">
                            <i data-acorn-icon="file-text" data-acorn-size="18"></i>
                        </span>
                        <div>
                            <h2 class="small-title mb-0">Data pengajuan</h2>
                            <p class="text-muted small mb-0">Informasi Data Pengajuan</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="pengajuan-field-tile border border-separator bg-foreground p-3 h-100">
                                <div class="text-muted text-small text-uppercase mb-1">Organisasi</div>
                                <div class="fw-semibold text-body">{{ $pengajuan->organisasi?->nama ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="pengajuan-field-tile border border-separator bg-foreground p-3 h-100">
                                <div class="text-muted text-small text-uppercase mb-2">Lokasi & wilayah</div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="text-muted small mb-0">Lokasi kegiatan</div>
                                        <div class="fw-semibold text-body">{{ $pengajuan->lokasi ?? '—' }}</div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="text-muted small mb-0">Desa</div>
                                        <div class="fw-semibold text-body">{{ $pengajuan->desa?->nama ?? '—' }}</div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="text-muted small mb-0">Kecamatan</div>
                                        <div class="fw-semibold text-body">{{ $pengajuan->desa?->kecamatan?->nama ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($pengajuan->jenisBantuan)
                        <div class="col-12">
                            <div class="pengajuan-field-tile border border-separator bg-foreground p-3 h-100">
                                <div class="text-muted text-small text-uppercase mb-1">Jenis bantuan</div>
                                <div class="fw-semibold text-body">{{ $pengajuan->jenisBantuan->nama }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="col-12">
                            <div class="pengajuan-field-tile border border-separator bg-foreground p-3 h-100">
                                <div class="text-muted text-small text-uppercase mb-1">Lampiran PDF</div>
                                @if ($lampiran)
                                    <a class="btn btn-sm btn-outline-primary mt-1" href="{{ $lampiran->getUrl() }}" target="_blank" rel="noopener noreferrer">
                                        <i data-acorn-icon="download" class="me-1" data-acorn-size="14"></i>
                                        Buka dokumen
                                    </a>
                                    <div class="text-muted small mt-2 text-truncate" title="{{ $lampiran->file_name }}">{{ $lampiran->file_name }}</div>
                                @else
                                    <div class="text-muted small mt-1">Belum diunggah</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($catatan)
                    <div class="alert alert-light border border-warning border-start border-4 mt-4 mb-0 shadow-sm" role="status">
                        <div class="d-flex gap-3">
                            <span class="text-warning flex-shrink-0"><i data-acorn-icon="info-circle" data-acorn-size="20"></i></span>
                            <div>
                                <div class="fw-semibold text-body mb-1">Catatan verifikator</div>
                                <p class="mb-0 text-alternate">{{ $catatan }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pengajuan->verified_at)
                    <div class="row g-3 mt-2 pt-3 border-top border-separator">
                        <div class="col-md-6">
                            <div class="text-muted text-small text-uppercase mb-1">Diverifikasi pada</div>
                            <div class="fw-semibold">{{ $pengajuan->verified_at->translatedFormat('d F Y H:i') }}</div>
                        </div>
                        @if($pengajuan->verifiedBy)
                        <div class="col-md-6">
                            <div class="text-muted text-small text-uppercase mb-1">Diverifikasi oleh</div>
                            <div class="fw-semibold">{{ $pengajuan->verifiedBy->nama ?? $pengajuan->verifiedBy->email ?? '—' }}</div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($pengajuan->logs->isNotEmpty())
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 mb-4 flex-shrink-0">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-foreground border border-separator p-2">
                            <i data-acorn-icon="clock" data-acorn-size="18"></i>
                        </span>
                        <div>
                            <h2 class="small-title mb-0">Riwayat aksi</h2>
                            <p class="text-muted small mb-0">Alur status dan catatan dari sistem.</p>
                        </div>
                    </div>

                    <div class="pengajuan-timeline-wrap flex-grow-1">
                        @foreach ($pengajuan->logs as $log)
                        <div class="pengajuan-timeline-item">
                            <span class="pengajuan-timeline-dot" aria-hidden="true"></span>
                            <div class="card pengajuan-log-card border border-separator shadow-sm">
                                <div class="card-body p-3 p-md-4">
                                @php
                                $badgeLog = match ($log->status_to) {
                                    \App\Enums\PengajuanStatus::DRAFT->value => 'secondary',
                                    \App\Enums\PengajuanStatus::DIAJUKAN->value => 'info',
                                    \App\Enums\PengajuanStatus::DISETUJUI->value => 'success',
                                    \App\Enums\PengajuanStatus::DITOLAK->value => 'danger',
                                    default => 'secondary',
                                };
                                @endphp

                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                    <div class="d-flex flex-column flex-grow-1 min-w-0">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                            <span class="badge bg-primary">{{ $log->action }}</span>
                                            @if ($log->status_to)
                                            <span class="badge bg-{{ $badgeLog }}">{{ strtoupper($log->status_to) }}</span>
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            <i data-acorn-icon="calendar" class="align-text-bottom me-1" data-acorn-size="14"></i>
                                            {{ $log->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                                            @if($log->user)
                                            <span class="mx-1">·</span>
                                            {{ $log->user?->nama ?? $log->user?->email }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if ($log->catatan)
                                <div class="mt-3 pt-3 border-top border-separator">
                                    <div class="text-muted text-small text-uppercase mb-1">Catatan</div>
                                    <div class="bg-foreground rounded-3 p-3 border border-separator text-body small mb-0">{{ $log->catatan }}</div>
                                </div>
                                @endif

                                @php
                                $updateChanges = data_get($log->metadata, 'changes');
                                @endphp
                                @if ($log->action === 'updated' && is_array($updateChanges) && $updateChanges !== [])
                                @php
                                $fmtUpdateVal = function (string $field, $value) use ($desaNamaById) {
                                    if ($value === null || $value === '') {
                                        return '—';
                                    }
                                    if ($field === 'desa_id') {
                                        return $desaNamaById[$value] ?? $value;
                                    }
                                    if ($field === 'nilai') {
                                        return number_format((float) $value, 2, ',', '.');
                                    }
                                    if ($field === 'file_pengajuan') {
                                        return match ($value) {
                                            'berkas_ada' => 'Berkas ada',
                                            'berkas_diunggah' => 'Berkas diunggah',
                                            default => (string) $value,
                                        };
                                    }

                                    return (string) $value;
                                };
                                $labelUpdateField = fn (string $field) => match ($field) {
                                    'judul' => 'Judul',
                                    'lokasi' => 'Lokasi',
                                    'desa_id' => 'Desa',
                                    'nilai' => 'Nilai',
                                    'file_pengajuan' => 'Berkas pengajuan',
                                    default => $field,
                                };
                                @endphp
                                <div class="mt-3 pt-3 border-top border-separator">
                                    <div class="text-muted text-small text-uppercase mb-2">Perubahan data</div>
                                    <div class="table-responsive rounded-3 border border-separator">
                                        <table class="table table-sm table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-3">Data</th>
                                                    <th>Sebelum</th>
                                                    <th class="pe-3">Sesudah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($updateChanges as $field => $pair)
                                                @if (is_array($pair) && array_key_exists('from', $pair) && array_key_exists('to', $pair))
                                                <tr>
                                                    <td class="ps-3 text-body small fw-medium">{{ $labelUpdateField($field) }}</td>
                                                    <td class="text-muted small">{{ $fmtUpdateVal($field, $pair['from']) }}</td>
                                                    <td class="pe-3 text-body small">{{ $fmtUpdateVal($field, $pair['to']) }}</td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>
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
                                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                        <div class="small-title text-muted mb-0">Bantuan uang</div>
                                        <div class="text-muted small">Total: <span class="fw-semibold text-body">{{ number_format((float) $totalBantuanUang, 2, ',', '.') }}</span></div>
                                    </div>
                                    <div class="table-responsive rounded-3 border border-separator">
                                        <table class="table table-sm table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-3">Penduduk</th>
                                                    <th class="text-end pe-3" style="width: 180px;">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bantuanUang as $row)
                                                <tr>
                                                    <td class="ps-3">{{ $row->penduduk?->nama ?? $row->penduduk_id }}</td>
                                                    <td class="text-end pe-3">{{ number_format((float) $row->nilai, 2, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <th class="text-end ps-3">Total</th>
                                                    <th class="text-end pe-3 fw-semibold">{{ number_format((float) $totalBantuanUang, 2, ',', '.') }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                @endif

                                @if ($bantuanBarangJasa->isNotEmpty())
                                <div class="mt-3">
                                    @php($totalBarangJasa = $bantuanBarangJasa->sum(fn($row) => (float) $row->harga_satuan * (int) $row->qty))
                                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                        <div class="small-title text-muted mb-0">Bantuan barang / jasa</div>
                                        <div class="text-muted small">Total: <span class="fw-semibold text-body">{{ number_format((float) $totalBarangJasa, 2, ',', '.') }}</span></div>
                                    </div>
                                    <div class="table-responsive rounded-3 border border-separator">
                                        <table class="table table-sm table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-3">Nama</th>
                                                    <th style="width: 120px;">Satuan</th>
                                                    <th class="text-end" style="width: 90px;">Qty</th>
                                                    <th class="text-end" style="width: 160px;">Harga satuan</th>
                                                    <th class="text-end pe-3" style="width: 180px;">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bantuanBarangJasa as $row)
                                                @php($subtotal = (float) $row->harga_satuan * (int) $row->qty)
                                                <tr>
                                                    <td class="ps-3">
                                                        <div class="fw-semibold">{{ $row->nama_barang }}</div>
                                                        <div class="text-muted small">{{ $row->spesifikasi }}</div>
                                                    </td>
                                                    <td>{{ $row->satuan }}</td>
                                                    <td class="text-end">{{ $row->qty }}</td>
                                                    <td class="text-end">{{ number_format((float) $row->harga_satuan, 2, ',', '.') }}</td>
                                                    <td class="text-end pe-3">{{ number_format((float) $subtotal, 2, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <th colspan="4" class="text-end ps-3">Total</th>
                                                    <th class="text-end pe-3 fw-semibold">{{ number_format((float) $totalBarangJasa, 2, ',', '.') }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                @endif
                                @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>


</div>
<!-- Page Content End -->
@endsection

@push('js_vendor')
<script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@endpush

@push('js_page')
<script>
    document.querySelectorAll('form.form-ajukan-pengajuan').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var f = this;
            Swal.fire({
                title: 'Ajukan Pengajuan',
                text: 'Apakah Anda yakin ingin mengajukan pengajuan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, ajukan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) f.submit();
            });
        });
    });
</script>
@endpush
