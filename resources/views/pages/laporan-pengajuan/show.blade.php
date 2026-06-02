@extends('layouts.layout')
@section('title', 'Detail Laporan Pengajuan')
@section('content')
    @php
        $status = $pengajuan->status;
        $statusBadge = $status?->badgeColor() ?? 'secondary';
        $statusLabel = $status?->getDescription() ?? '-';
        $verifikasi = $pengajuan->verifikasiPengajuan;
        $organisasi = $pengajuan->organisasi;
        $desa = $organisasi?->desa;
        $kecamatan = $desa?->kecamatan;
        $kategori = $pengajuan->kategori_pengajuan ?? $pengajuan->jenisBantuan?->kategori;
    @endphp

    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <a href="{{ route('laporan-pengajuan.index') }}"
                       class="btn btn-icon btn-icon-only btn-outline-primary">
                        <i data-acorn-icon="arrow-left"></i>
                    </a>
                </div>
                <div class="col">
                    <h1 class="mb-1 pb-0 display-4">Detail Laporan Pengajuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('laporan-pengajuan.index') }}">Laporan Pengajuan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $pengajuan->kode_pengajuan }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Hero --}}
        <div class="card border-0 shadow-sm mb-4 overflow-hidden">
            <div class="card-body p-4 position-relative" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 55%, #1d4ed8 100%); color: #fff;">
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-md">
                        <div class="text-white-50 text-uppercase small fw-bold mb-1" style="letter-spacing: 0.08em;">
                            Kode Pengajuan
                        </div>
                        <h3 class="text-white fw-bold mb-2">{{ $pengajuan->kode_pengajuan }}</h3>
                        @if($pengajuan->judul)
                            <div class="text-white" style="opacity: 0.92;">{{ $pengajuan->judul }}</div>
                        @endif
                    </div>
                    <div class="col-12 col-md-auto text-md-end">
                        <span class="badge bg-{{ $statusBadge }} px-3 py-2 fs-6">{{ $statusLabel }}</span>
                        <div class="mt-2 text-white-50 small">
                            Diajukan {{ $pengajuan->created_at?->translatedFormat('d M Y') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            {{-- Identitas Pengajuan --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100 detail-card">
                    <div class="card-body p-4">
                        <div class="detail-card-header">
                            <span class="detail-card-badge bg-primary"></span>
                            <span class="detail-card-title">Identitas Pengajuan</span>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-12 col-md-6">
                                <div class="detail-label">Kode Pengajuan</div>
                                <div class="detail-value">{{ $pengajuan->kode_pengajuan ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="detail-label">Kategori Pengajuan</div>
                                <div class="detail-value">{{ $kategori?->getDescription() ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="detail-label">Judul</div>
                                <div class="detail-value">{{ $pengajuan->judul ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="detail-label">Tanggal Pengajuan</div>
                                <div class="detail-value">{{ $pengajuan->created_at?->translatedFormat('d M Y') ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="detail-label">Status</div>
                                <div class="detail-value">
                                    <span class="badge bg-{{ $statusBadge }}">{{ $statusLabel }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pemohon --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100 detail-card">
                    <div class="card-body p-4">
                        <div class="detail-card-header">
                            <span class="detail-card-badge" style="background: #10b981;"></span>
                            <span class="detail-card-title">Pemohon</span>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-12">
                                <div class="detail-label">Nama Pemohon</div>
                                <div class="detail-value">{{ $organisasi?->nama ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="detail-label">Desa</div>
                                <div class="detail-value">{{ $desa?->nama ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="detail-label">Kecamatan</div>
                                <div class="detail-value">{{ $kecamatan?->nama ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bantuan --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100 detail-card">
                    <div class="card-body p-4">
                        <div class="detail-card-header">
                            <span class="detail-card-badge" style="background: #f59e0b;"></span>
                            <span class="detail-card-title">Bantuan</span>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-12 col-md-6">
                                <div class="detail-label">Jenis Bantuan</div>
                                <div class="detail-value">{{ $pengajuan->jenisBantuan?->nama ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="detail-label">OPD</div>
                                <div class="detail-value">{{ $pengajuan->opd?->nama ?? '-' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="detail-label">Nilai Usulan</div>
                                <div class="detail-value">Rp {{ number_format((float) $pengajuan->nilai, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Verifikasi --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100 detail-card">
                    <div class="card-body p-4">
                        <div class="detail-card-header">
                            <span class="detail-card-badge" style="background: #8b5cf6;"></span>
                            <span class="detail-card-title">Verifikasi</span>
                        </div>
                        @if($verifikasi || $pengajuan->verified_at)
                            <div class="row g-3 mt-1">
                                <div class="col-12 col-md-6">
                                    <div class="detail-label">Nilai Rekomendasi</div>
                                    <div class="detail-value">
                                        @if($verifikasi?->nilai_rekomendasi !== null)
                                            Rp {{ number_format((float) $verifikasi->nilai_rekomendasi, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="detail-label">Tgl Verifikasi</div>
                                    <div class="detail-value">{{ $pengajuan->verified_at?->translatedFormat('d M Y H:i') ?? '-' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="detail-label">Verifikator</div>
                                    <div class="detail-value">{{ $verifikasi?->user?->nama ?? $pengajuan->verifiedBy?->nama ?? '-' }}</div>
                                </div>
                                @php
                                    $checks = [
                                        ['label' => 'Lulus Kriteria',       'value' => $verifikasi?->lulus_kriteria],
                                        ['label' => 'Lulus Administrasi',   'value' => $verifikasi?->lulus_administrasi],
                                        ['label' => 'Lulus Kesesuaian',     'value' => $verifikasi?->lulus_kesesuaian],
                                        ['label' => 'Sesuai Program Pemda', 'value' => $verifikasi?->sesuai_program_pemda],
                                    ];
                                @endphp
                                @foreach($checks as $c)
                                    <div class="col-6 col-md-6">
                                        <div class="detail-label">{{ $c['label'] }}</div>
                                        <div class="detail-value">
                                            @if($c['value'] === null)
                                                <span class="text-muted">—</span>
                                            @elseif($c['value'])
                                                <span class="text-success fw-semibold">
                                                    <i data-acorn-icon="check-circle" data-acorn-size="16" class="me-1 align-middle"></i> Ya
                                                </span>
                                            @else
                                                <span class="text-danger fw-semibold">
                                                    <i data-acorn-icon="close-circle" data-acorn-size="16" class="me-1 align-middle"></i> Tidak
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @if($verifikasi?->catatan)
                                    <div class="col-12">
                                        <div class="detail-label">Catatan Verifikasi</div>
                                        <div class="detail-value">{{ $verifikasi->catatan }}</div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i data-acorn-icon="clock" data-acorn-size="28" class="mb-2 d-inline-block"></i>
                                <div>Belum ada verifikasi</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('laporan-pengajuan.index') }}" class="btn btn-outline-primary">
                <i data-acorn-icon="arrow-left" class="me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .detail-card { border-radius: 1rem; }
        .detail-card-header {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed #e5e7eb;
        }
        .detail-card-badge {
            display: inline-block;
            width: 6px;
            height: 24px;
            border-radius: 4px;
        }
        .detail-card-title {
            font-weight: 700;
            font-size: 0.88rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #1e293b;
        }
        .detail-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        .detail-value {
            font-size: 0.95rem;
            color: #0f172a;
            font-weight: 600;
            line-height: 1.4;
            word-break: break-word;
        }
    </style>
@endpush
