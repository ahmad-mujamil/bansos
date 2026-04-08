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
                    <div class="row mb-3">
                        <div class="col-12 col-md-4 mb-3">
                            <label for="filter-status" class="form-label text-muted text-small text-uppercase">Filter Status</label>
                            <select id="filter-status" class="form-select form-select-sm">
                                <option value="all" selected>Semua</option>
                                <option value="{{ \App\Enums\PengajuanStatus::DRAFT->value }}">Draft</option>
                                <option value="{{ \App\Enums\PengajuanStatus::DIAJUKAN->value }}">Diajukan</option>
                                <option value="{{ \App\Enums\PengajuanStatus::DISETUJUI->value }}">Disetujui</option>
                                <option value="{{ \App\Enums\PengajuanStatus::DITOLAK->value }}">Ditolak</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase">Kode</th>
                                    <th class="text-muted text-small text-uppercase">Judul Usulan</th>
                                    <th class="text-muted text-small text-uppercase">Lokasi</th>
                                    <th class="text-muted text-small text-uppercase">Status</th>
                                    <th class="text-muted text-small text-uppercase">Tanggal</th>
                                    <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuan as $p)
                                    @php $detail = $p; @endphp
                                    <tr data-status="{{ $p->status->value }}">
                                        <td>{{ $p->kode_pengajuan }}</td>
                                        <td>{{ $detail?->judul ?? '-' }}</td>
                                        <td>{{ $detail?->lokasi ?? '-' }}</td>
                                        <td>
                                            @php
                                                $status = $p->status;
                                            @endphp
                                            <span class="badge bg-{{ $status?->badgeColor() ?? 'secondary' }}">{{ $status->getDescription() }}</span>
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

@if(!$pengajuan->isEmpty())
@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
    <style>
        #filter-status + .select2-container--bootstrap4 .select2-selection--single {
            height: 31px;
        }
        #filter-status + .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 31px;
            padding-top: 0;
            padding-bottom: 0;
        }
    </style>
@endpush
@endif

@push('js_vendor')
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    @if(!$pengajuan->isEmpty())
        <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
        <script>
            $(function () {
                var $filter = $('#filter-status');
                $filter.select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    minimumResultsForSearch: Infinity
                });

                function applyFilter() {
                    var value = $filter.val();
                    var showAll = !value || value === 'all';
                    $('table.table tbody tr[data-status]').each(function () {
                        var rowStatus = $(this).attr('data-status') || '';
                        $(this).toggle(showAll || rowStatus === value);
                    });
                }

                $filter.on('change', applyFilter);
                applyFilter();
            });
        </script>
    @endif
@endpush

@push('js_page')
    <script>
        (function () {
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
        })();
    </script>
@endpush
