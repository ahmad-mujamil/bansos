@extends('layouts.layout')
@section('title', $judul)
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">{{ $judul }}</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Organisasi Teregistrasi</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-icon btn-icon-start">
                        <i data-acorn-icon="chevron-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <div class="mb-3 text-muted text-small">
                    Menampilkan <strong>{{ number_format($organisasi->count()) }}</strong> organisasi.
                </div>

                @if ($organisasi->isEmpty())
                    <p class="text-muted mb-0">Tidak ada organisasi teregistrasi untuk jenis ini.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase">Nama</th>
                                    <th class="text-muted text-small text-uppercase">Jenis</th>
                                    <th class="text-muted text-small text-uppercase">Nomor</th>
                                    <th class="text-muted text-small text-uppercase">Wilayah</th>
                                    <th class="text-muted text-small text-uppercase">OPD</th>
                                    <th class="text-muted text-small text-uppercase">Tgl Pembentukan</th>
                                    <th class="text-muted text-small text-uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($organisasi as $org)
                                    @php
                                        $jenisOrg = \App\Enums\JenisOrganisasi::tryFrom($org->jenis);
                                        $wilayah = collect([$org->desa?->nama, $org->desa?->kecamatan?->nama])
                                            ->filter()->implode(', ');
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $org->nama }}</td>
                                        <td>{{ $jenisOrg?->getDescription() ?? $org->jenis }}</td>
                                        <td>{{ $org->nomor ?? '-' }}</td>
                                        <td>{{ $wilayah !== '' ? $wilayah : '-' }}</td>
                                        <td>{{ $org->opd?->nama ?? '-' }}</td>
                                        <td>{{ $org->tgl_pembentukan?->translatedFormat('d M Y') ?? '-' }}</td>
                                        <td>
                                            @if ($org->is_blacklist)
                                                <span class="badge bg-danger">Blacklist</span>
                                            @elseif ($org->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
