@extends('layouts.layout')
@section('title', 'Laporan Anggota Kelompok')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Laporan Anggota Kelompok</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Laporan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('laporan-anggota-kelompok.index') }}">Anggota Kelompok</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                

                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-4 col-xxl-3 mb-3">
                        <div class="d-flex gap-2 w-100 align-items-center">
                            <div class="flex-grow-1 search-input-container border border-separator bg-foreground search-sm">
                                <input class="form-control form-control-sm datatable-search" placeholder="Cari nama, NIK, atau nama kelompok…" data-datatable="#datatable-laporan-anggota-kelompok" />
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
                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#datatable-laporan-anggota-kelompok">
                                <i data-acorn-icon="print"></i>
                            </button>
                            <div class="d-inline-block datatable-export" data-datatable="#datatable-laporan-anggota-kelompok">
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
                        id="datatable-laporan-anggota-kelompok"
                    >
                        <thead>
                            <tr>
                                <th class="text-muted text-small text-uppercase">Nama penduduk</th>
                                <th class="text-muted text-small text-uppercase">NIK</th>
                                <th class="text-muted text-small text-uppercase">Desa / Kecamatan</th>
                                <th class="text-muted text-small text-uppercase">Nama kelompok</th>
                                <th class="text-muted text-small text-uppercase">Jumlah kelompok</th>
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
@endpush

@push('js_vendor')
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        _extendDatatables();
        $('#datatable-laporan-anggota-kelompok').DataTable({
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
            lengthChange: false,
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
            ajax: {
                url: "{!! route('laporan-anggota-kelompok.index') !!}",
            },
            columns: [
                { data: 'nama_penduduk', name: 'penduduk.nama' },
                { data: 'nik', name: 'penduduk.nik' },
                { data: 'wilayah', name: 'desa.nama', orderable: false, searchable: false },
                { data: 'daftar_kelompok', name: 'daftar_kelompok', orderable: false },
                { data: 'jumlah_kelompok', name: 'jumlah_kelompok', searchable: false },
            ],
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush
