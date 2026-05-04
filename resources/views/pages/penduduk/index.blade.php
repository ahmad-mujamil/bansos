@extends('layouts.layout')
@section('title', 'Penduduk')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Penduduk</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('penduduk.index') }}">Penduduk</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Add New Button Start -->
                    <a href="{{ route('penduduk.create') }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="plus"></i>
                        <span>Tambah Data</span>
                    </a>
                    <!-- Add New Button End -->
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <div class="card mb-5">
            <div class="card-body">
                <!--  Controls Start -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-6 col-xxl-6 mb-3">
                        <div class="d-flex gap-2 w-100 align-items-center">
                            <div class="flex-grow-1">
                                <select id="filter-status-verifikasi" class="form-select form-select-sm">
                                    <option value="all" selected>Semua Status</option>
                                    <option value="1">Terverifikasi</option>
                                    <option value="0">Belum Diverifikasi</option>
                                    <option value="2">Tidak Valid</option>
                                </select>
                            </div>
                            <div class="flex-grow-1 search-input-container border border-separator bg-foreground search-sm">
                                <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#datatable-serverside" />
                                <span class="search-magnifier-icon">
                                  <i data-acorn-icon="search"></i>
                                </span>
                                <span class="search-delete-icon d-none">
                                  <i data-acorn-icon="close"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6 col-xxl-6 text-end mb-3">
                        <div class="d-inline-block">
                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#datatable-serverside">
                                <i data-acorn-icon="print"></i>
                            </button>

                            <div class="d-inline-block datatable-export" data-datatable="#datatable-serverside">
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
                <!-- Controls End -->

                <!-- Table Start -->
                <table
                    class="data-table data-table-pagination data-table-standard responsive nowrap stripe penduduk-table"
                    id="datatable-serverside">
                    <thead>
                    <tr>
                        <th class="text-muted text-small text-uppercase">Nama / NIK</th>
                        <th class="text-muted text-small text-uppercase">Desa / Kecamatan</th>
                        <th class="text-muted text-small text-uppercase">Desil</th>
                        <th class="text-muted text-small text-uppercase">Kelompok / OPD</th>
                        <th class="text-muted text-small text-uppercase">Status Verifikasi</th>
                        <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="text-alternate text-medium">
                    </tbody>
                </table>
                <!-- Table End -->
            </div>
        </div>
        <!-- Content Start -->
    </div>
    <!-- Page Content End -->
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
    <style>
        .penduduk-table td,
        .penduduk-table th {
            padding: 0.4rem 0.5rem;
            vertical-align: middle;
            font-size: 0.82rem;
        }
        .penduduk-table .badge {
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
            font-weight: 500;
        }
        .penduduk-table small {
            font-size: 0.72rem;
        }
        .penduduk-table .breadcrumb {
            margin-bottom: 0;
            font-size: 0.78rem;
        }
    </style>
@endpush
@push('js_vendor')
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        $('#filter-status-verifikasi').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        _extendDatatables()
        const table = $('#datatable-serverside').DataTable({
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
            lengthChange: true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row align-items-center mt-3"<"col-sm-6"l><"col-sm-6"p>>',
            ajax: {
                url: "{!! route('penduduk.index') !!}",
                data: function (d) {
                    d.status = $('#filter-status-verifikasi').val();
                }
            },
            columns: [
                {
                    data: 'nik_nama',
                    name: 'nama',
                    defaultContent: '-'
                },
                {
                    data: 'desa_kecamatan',
                    name: 'desa.nama',
                    defaultContent: '-'
                },
                {
                    data: 'level_desil',
                    name: 'level_desil'
                },
                {
                    data: 'kelompok_opd',
                    name: 'organisasiDetails.organisasi.nama',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status_verifikasi',
                    name: 'is_valid',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#filter-status-verifikasi').on('change', function () {
            table.ajax.reload();
        });

        table.on('draw.dt', function () {
            $('#datatable-serverside [data-bs-toggle="tooltip"]').each(function () {
                const existing = bootstrap.Tooltip.getInstance(this);
                if (existing) existing.dispose();
                new bootstrap.Tooltip(this);
            });
        });

        function _extendDatatables() {
            new DatatableExtend();
        }

    </script>
@endpush
