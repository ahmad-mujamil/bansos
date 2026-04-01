@extends('layouts.layout')
@section('title', 'Informasi Blacklist')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h4>Informasi Blacklist</h4>
                    <div class="text-muted font-heading text-small">Detail status blacklist kelompok/organisasi Anda</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="alert alert-danger mb-4" role="alert">
                    <div class="d-flex align-items-start">
                        <i data-acorn-icon="close-circle" data-acorn-size="22" class="me-2"></i>
                        <div>
                            <div class="fw-bold">Kelompok/Organisasi Anda sedang diblacklist</div>
                            <div class="small opacity-75">Akses menu Pengajuan dibatasi sampai status blacklist dicabut oleh OPD.</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="text-muted text-small text-uppercase">Kelompok/Organisasi</div>
                        <div class="fw-semibold">{{ $organisasi->nama ?? '-' }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted text-small text-uppercase">Jenis</div>
                        <div class="fw-semibold">{{ \App\Enums\JenisOrganisasi::from($organisasi->jenis)->getDescription() ?? '-' }}</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="text-muted text-small text-uppercase">Diblacklist oleh</div>
                        <div class="fw-semibold">
                            {{ $lastBlacklistLog?->user?->nama ?? '-' }}
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="text-muted text-small text-uppercase">Waktu blacklist</div>
                        <div class="fw-semibold">
                            {{ $lastBlacklistLog?->created_at?->translatedFormat('d F Y H:i') ?? '-' }}
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="text-muted text-small text-uppercase">Alasan</div>
                        <div class="fw-semibold">
                            {{ $lastBlacklistLog?->alasan ?: 'Tidak ada alasan yang dicatat.' }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i data-acorn-icon="arrow-left" data-acorn-size="16" class="me-1"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

