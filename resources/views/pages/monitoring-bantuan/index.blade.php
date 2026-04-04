@extends('layouts.layout')
@section('title', 'Monitoring Bantuan')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Monitoring Bantuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Monitoring &amp; Realisasi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('monitoring-bantuan.index') }}">Monitoring Bantuan</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <p class="text-muted mb-3">
                    <strong>Semua (default):</strong> gabungan pengajuan siap input BAST dan yang sudah punya BAST.
                    <br>
                    <strong>Belum BAST:</strong> disetujui, BA verifikasi bertanda tangan sudah diunggah, belum ada data BAST.
                    <br>
                    <strong>Sudah BAST:</strong> pengajuan yang sudah memiliki Berita Acara Serah Terima.
                </p>

                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-5 col-xxl-4 mb-3">
                        <div class="d-flex gap-2 w-100 align-items-center flex-wrap">
                            <div class="flex-grow-1 border border-separator bg-foreground" style="min-width: 12rem;">
                                <label for="filter-tahap-monitoring" class="visually-hidden">Tahap</label>
                                <select id="filter-tahap-monitoring" class="form-select form-select-sm">
                                    <option value="semua" selected>Semua</option>
                                    <option value="belum_bast">Belum BAST</option>
                                    <option value="sudah_bast">Sudah BAST</option>
                                </select>
                            </div>
                            <div class="flex-grow-1 search-input-container border border-separator bg-foreground search-sm">
                                <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#datatable-monitoring-bantuan" />
                                <span class="search-magnifier-icon">
                                    <i data-acorn-icon="search"></i>
                                </span>
                                <span class="search-delete-icon d-none">
                                    <i data-acorn-icon="close"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-7 col-xxl-8 text-end mb-3">
                        <div class="d-inline-block">
                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#datatable-monitoring-bantuan">
                                <i data-acorn-icon="print"></i>
                            </button>
                            <div class="d-inline-block datatable-export" data-datatable="#datatable-monitoring-bantuan">
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

                <table
                    class="data-table data-table-pagination data-table-standard responsive nowrap stripe"
                    id="datatable-monitoring-bantuan"
                >
                    <thead>
                        <tr>
                            <th class="text-muted text-small text-uppercase">Kode</th>
                            <th class="text-muted text-small text-uppercase">Jenis</th>
                            <th class="text-muted text-small text-uppercase">Judul</th>
                            <th class="text-muted text-small text-uppercase">Status</th>
                            <th class="text-muted text-small text-uppercase">Tanggal</th>
                            <th class="text-muted text-small text-uppercase">Pemohon</th>
                            <th class="text-muted text-small text-uppercase">BAST</th>
                            {{-- <th class="text-muted text-small text-uppercase w-15">Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody class="text-alternate text-medium"></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
    <style>
        #filter-tahap-monitoring + .select2-container--bootstrap4 .select2-selection--single {
            height: 31px;
        }

        #filter-tahap-monitoring + .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 31px;
            padding-top: 0;
            padding-bottom: 0;
        }
    </style>
@endpush

@push('js_vendor')
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        $('#filter-tahap-monitoring').select2({
            theme: 'bootstrap4',
            width: '100%',
            minimumResultsForSearch: Infinity,
        });

        _extendDatatables();
        const table = $('#datatable-monitoring-bantuan').DataTable({
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
            lengthChange: false,
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
            ajax: {
                url: "{!! route('monitoring-bantuan.index') !!}",
                data: function (d) {
                    d.tahap = $('#filter-tahap-monitoring').val();
                },
            },
            columns: [
                { data: 'kode_pengajuan', name: 'kode_pengajuan' },
                { data: 'jenis', name: 'jenis', orderable: false, searchable: false },
                { data: 'judul', name: 'judul' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'tanggal', name: 'created_at' },
                { data: 'user', name: 'user', orderable: false, searchable: false },
                { data: 'bast_info', name: 'bast_info', orderable: false, searchable: false },
                // { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });

        $('#filter-tahap-monitoring').on('change', function () {
            table.ajax.reload();
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush
