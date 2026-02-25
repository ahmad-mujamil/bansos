@extends('layouts.layout')
@section('title', 'Pengajuan')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Pengajuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Pengajuan</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('pengajuan.create') }}" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="plus"></i>
                        <span>Tambah Pengajuan</span>
                    </a>
                </div>
            </div>
        </div>

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                @if($pengajuan->isEmpty())
                    <p class="text-muted mb-0">Belum ada pengajuan. Klik <strong>Tambah Pengajuan</strong> untuk membuat pengajuan baru.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase">Kode</th>
                                    <th class="text-muted text-small text-uppercase">Jenis</th>
                                    <th class="text-muted text-small text-uppercase">Judul Usulan</th>
                                    <th class="text-muted text-small text-uppercase">Status</th>
                                    <th class="text-muted text-small text-uppercase">Tanggal</th>
                                    <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuan as $p)
                                    @php $detail = $p->details->first(); @endphp
                                    <tr>
                                        <td>{{ $p->kode_pengajuan }}</td>
                                        <td>{{ $p->jenis->getDescription() }}</td>
                                        <td>{{ $detail?->judul_usulan ?? '-' }}</td>
                                        <td>
                                            @php
                                                $status = $p->status;
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
                                            <span class="badge bg-{{ $badge }}">{{ $status->getDescription() }}</span>
                                        </td>
                                        <td>{{ $p->created_at->translatedFormat('d M Y') }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('pengajuan.show', $p) }}" class="btn btn-sm btn-outline-primary" title="Lihat">Lihat</a>
                                                @if($p->canEdit())
                                                    <a href="{{ route('pengajuan.edit', $p) }}" class="btn btn-sm btn-outline-secondary" title="Edit">Edit</a>
                                                @endif
                                                @if($p->canSubmit())
                                                    <form action="{{ route('pengajuan.submit', $p) }}" method="POST" class="d-inline form-ajukan-pengajuan">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Ajukan">Ajukan</button>
                                                    </form>
                                                @endif
                                            </div>
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
