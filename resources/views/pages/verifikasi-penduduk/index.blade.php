@extends('layouts.layout')
@section('title', 'Verifikasi Penduduk')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Verifikasi Penduduk</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Verifikasi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('verifikasi-penduduk.index') }}">Penduduk</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <div class="card mb-5">
            <div class="card-body">
                <p class="text-muted mb-3">
                    Daftar data penduduk yang perlu diverifikasi. Klik <strong>Lihat</strong> untuk memproses verifikasi.
                </p>

                <!-- Controls Start -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-6 col-xxl-6 mb-3">
                        <div class="d-flex gap-2 w-100 align-items-center">
                            <div class="flex-grow-1">
                                <select id="filter-status-validasi" class="form-select form-select-sm">
                                    <option value="all" @selected(request('status', 'all') === 'all')>Semua Status</option>
                                    <option value="1" @selected(request('status') === '1')>Terverifikasi</option>
                                    <option value="0" @selected(request('status') === '0')>Belum Diverifikasi</option>
                                    <option value="2" @selected(request('status') === '2')>Ditolak</option>
                                    <option value="3" @selected(request('status') === '3')>Sudah Perbaikan</option>
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
                                <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
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
                    class="data-table data-table-pagination data-table-standard responsive nowrap stripe"
                    id="datatable-serverside"
                >
                    <thead>
                        <tr>
                            <th class="text-muted text-small text-uppercase">NIK</th>
                            <th class="text-muted text-small text-uppercase">Nama</th>
                            <th class="text-muted text-small text-uppercase">Desa</th>
                            <th class="text-muted text-small text-uppercase">Kecamatan</th>
                            <th class="text-muted text-small text-uppercase">Status Validasi</th>
                            <th class="text-muted text-small text-uppercase">Tgl Validasi</th>
                            <th class="text-muted text-small text-uppercase">Tgl Input</th>
                            <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-alternate text-medium"></tbody>
                </table>
                <!-- Table End -->
            </div>
        </div>
    </div>
    <!-- Page Content End -->
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
@endpush

@push('js_vendor')
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        $('#filter-status-validasi').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        _extendDatatables();
        const table = $('#datatable-serverside').DataTable({
            language: {
                paginate: {
                    previous: '<i class="cs-chevron-left"></i>',
                    next: '<i class="cs-chevron-right"></i>',
                },
                info: 'Menampilkan _START_–_END_ dari <strong>_TOTAL_</strong> data',
                infoEmpty: 'Menampilkan 0 dari 0 data',
                infoFiltered: '(disaring dari _MAX_ total data)',
            },
            buttons: ['copy', 'excel', 'csv', 'print'],
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: true,
            order: [[6, 'desc']],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row align-items-center mt-3"<"col-sm-4"l><"col-sm-4 text-center text-muted small"i><"col-sm-4"p>>',
            ajax: {
                url: "{!! route('verifikasi-penduduk.index') !!}",
                data: function (d) {
                    d.status = $('#filter-status-validasi').val();
                    d.verifikator = new URLSearchParams(window.location.search).get('verifikator') || '';
                }
            },
            columns: [
                { data: 'nik', name: 'nik' },
                { data: 'nama', name: 'nama' },
                { data: 'desa.nama', name: 'desa.nama', defaultContent: '-' },
                { data: 'kecamatan.nama', name: 'kecamatan.nama', defaultContent: '-' },
                { data: 'is_valid_badge', name: 'is_valid', orderable: true, searchable: false },
                { data: 'validated_at_fmt', name: 'validated_at', searchable: false },
                { data: 'created_at_fmt', name: 'created_at', searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });

        $('#filter-status-validasi').on('change', function () {
            table.ajax.reload();
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush
