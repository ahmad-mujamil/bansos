@extends('layouts.layout')
@section('title', 'Laporan Pengajuan Kelompok')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Laporan Pengajuan (Kelompok)</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Laporan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('laporan-pengajuan.index') }}">Pengajuan Bansos</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <p class="text-muted mb-3">
                    Ringkasan pengajuan <strong>bantuan kelompok</strong>: data kelompok pemohon, informasi usulan, dan hasil verifikasi.
                </p>

                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-4 col-xxl-3 mb-3">
                        <div class="d-flex gap-2 w-100 align-items-center">
                            <div class="flex-grow-1">
                                <select id="filter-status-laporan" class="form-select form-select-sm">
                                    <option value="all" selected>Semua status</option>
                                    <option value="{{ \App\Enums\PengajuanStatus::DRAFT->value }}">Draft</option>
                                    <option value="{{ \App\Enums\PengajuanStatus::DIAJUKAN->value }}">Diajukan</option>
                                    <option value="{{ \App\Enums\PengajuanStatus::DISETUJUI->value }}">Disetujui</option>
                                    <option value="{{ \App\Enums\PengajuanStatus::DITOLAK->value }}">Ditolak</option>
                                </select>
                            </div>
                            <div class="flex-grow-1 search-input-container border border-separator bg-foreground search-sm">
                                <input class="form-control form-control-sm datatable-search" placeholder="Cari…" data-datatable="#datatable-laporan-pengajuan" />
                                <span class="search-magnifier-icon">
                                    <i data-acorn-icon="search"></i>
                                </span>
                                <span class="search-delete-icon d-none">
                                    <i data-acorn-icon="close"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-8 col-xxl-9 text-end mb-3">
                        <div class="d-inline-block">
                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#datatable-laporan-pengajuan">
                                <i data-acorn-icon="print"></i>
                            </button>
                            <div class="d-inline-block datatable-export" data-datatable="#datatable-laporan-pengajuan">
                                <button
                                    class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown"
                                    data-bs-toggle="dropdown"
                                    type="button"
                                    data-bs-offset="0,3"
                                >
                                    <i data-acorn-icon="download"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                    <button class="dropdown-item export-copy" type="button">Copy</button>
                                    <button class="dropdown-item export-excel" type="button">Excel</button>
                                    <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table
                        class="data-table data-table-pagination data-table-standard responsive nowrap stripe w-100"
                        id="datatable-laporan-pengajuan"
                    >
                        <thead>
                            <tr>
                                <th class="text-muted text-small text-uppercase">Kelompok</th>
                                <th class="text-muted text-small text-uppercase">Kode</th>
                                <th class="text-muted text-small text-uppercase">Judul</th>
                                <th class="text-muted text-small text-uppercase">Jenis bantuan</th>
                                <th class="text-muted text-small text-uppercase">Nilai usulan</th>
                                <th class="text-muted text-small text-uppercase">OPD</th>
                                <th class="text-muted text-small text-uppercase">Status</th>
                                <th class="text-muted text-small text-uppercase">Tgl pengajuan</th>
                                <th class="text-muted text-small text-uppercase">Keputusan</th>
                                <th class="text-muted text-small text-uppercase">Nilai rekom.</th>
                                <th class="text-muted text-small text-uppercase">Kriteria</th>
                                <th class="text-muted text-small text-uppercase">Adm.</th>
                                <th class="text-muted text-small text-uppercase">Kesesuaian</th>
                                <th class="text-muted text-small text-uppercase">Prog. Pemda</th>
                                <th class="text-muted text-small text-uppercase">Catatan</th>
                                <th class="text-muted text-small text-uppercase">Verifikator</th>
                                <th class="text-muted text-small text-uppercase">Tgl verifikasi</th>
                                <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-alternate text-medium"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
    <style>
        #filter-status-laporan + .select2-container--bootstrap4 .select2-selection--single {
            height: 31px;
        }
        #filter-status-laporan + .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 31px;
            padding-top: 0;
            padding-bottom: 0;
        }
    </style>
@endpush

@push('js_vendor')
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        $('#filter-status-laporan').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        _extendDatatables();
        const tableLaporan = $('#datatable-laporan-pengajuan').DataTable({
            language: {
                paginate: {
                    previous: '<i class="cs-chevron-left"></i>',
                    next: '<i class="cs-chevron-right"></i>',
                },
            },
            buttons: ['copy', 'excel', 'csv', 'print'],
            processing: true,
            serverSide: true,
            responsive: true,
            scrollX: true,
            lengthChange: true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row align-items-center mt-3"<"col-sm-6"l><"col-sm-6"p>>',
            ajax: {
                url: "{!! route('laporan-pengajuan.index') !!}",
                data: function (d) {
                    d.status = $('#filter-status-laporan').val();
                },
            },
            columns: [
                { data: 'kelompok', name: 'organisasi.nama', orderable: false },
                { data: 'kode_pengajuan', name: 'kode_pengajuan' },
                { data: 'judul', name: 'judul' },
                { data: 'jenis_bantuan', name: 'jenisBantuan.nama' },
                { data: 'nilai_usulan', name: 'nilai' },
                { data: 'opd', name: 'opd.nama' },
                { data: 'status', name: 'status', orderable: false },
                { data: 'tanggal_pengajuan', name: 'created_at' },
                { data: 'keputusan', name: 'status', orderable: false },
                { data: 'nilai_rekomendasi', name: 'verifikasiPengajuan.nilai_rekomendasi', orderable: false },
                { data: 'vk', name: 'verifikasiPengajuan.lulus_kriteria', orderable: false },
                { data: 'va', name: 'verifikasiPengajuan.lulus_administrasi', orderable: false },
                { data: 'vks', name: 'verifikasiPengajuan.lulus_kesesuaian', orderable: false },
                { data: 'vpp', name: 'verifikasiPengajuan.sesuai_program_pemda', orderable: false },
                { data: 'catatan_verifikasi', name: 'verifikasiPengajuan.catatan', orderable: false },
                { data: 'verifikator', name: 'verifikasiPengajuan.user.nama', orderable: false },
                { data: 'tanggal_verifikasi', name: 'verified_at', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });

        $('#filter-status-laporan').on('change', function () {
            tableLaporan.ajax.reload();
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush
