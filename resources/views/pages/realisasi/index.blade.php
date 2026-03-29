@extends('layouts.layout')
@section('title', 'Realisasi')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Realisasi</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Pengajuan Bantuan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('realisasi.index') }}">Realisasi</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card mb-5">
            <div class="card-body">
                <p class="text-alternate mb-4">
                    Daftar pengajuan yang sudah memiliki BAST. Unggah dokumen laporan kegiatan sebagai bukti realisasi bantuan.
                    <span class="badge bg-danger text-wrap text-start fw-normal lh-base d-inline-block mt-2 px-3 py-2">
                        Wajib melakukan realisasi sesuai jadwal; jika tidak, penerima dapat dimasukkan ke daftar hitam (blacklist).
                    </span>
                </p>
                <div class="row">
                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-3">
                        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                            <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#datatable-realisasi" />
                            <span class="search-magnifier-icon">
                                <i data-acorn-icon="search"></i>
                            </span>
                            <span class="search-delete-icon d-none">
                                <i data-acorn-icon="close"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-7 col-lg-9 col-xxl-10 text-end mb-3">
                        <div class="d-inline-block">
                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#datatable-realisasi">
                                <i data-acorn-icon="print"></i>
                            </button>
                            <div class="d-inline-block datatable-export" data-datatable="#datatable-realisasi">
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
                    id="datatable-realisasi">
                    <thead>
                    <tr>
                        <th class="text-muted text-small text-uppercase">Kode Pengajuan</th>
                        <th class="text-muted text-small text-uppercase">Judul</th>
                        <th class="text-muted text-small text-uppercase">Nomor BAST</th>
                        <th class="text-muted text-small text-uppercase">Tanggal BAST</th>
                        <th class="text-muted text-small text-uppercase">Nilai Rekomendasi</th>
                        <th class="text-muted text-small text-uppercase">Realisasi</th>
                        <th class="text-muted text-small text-uppercase w-15">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="text-alternate text-medium">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css') }}" />
@endpush
@push('js_vendor')
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        _extendDatatables()
        $('#datatable-realisasi').DataTable({
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
            order: [[0, 'desc']],
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
            ajax: "{!! route('realisasi.index') !!}",
            columns: [
                { data: 'kode_pengajuan', name: 'kode_pengajuan' },
                { data: 'judul', name: 'judul' },
                { data: 'bast_nomor', name: 'bast_nomor', orderable: false, searchable: false },
                { data: 'bast_tanggal', name: 'bast_tanggal', orderable: false, searchable: false },
                { data: 'nilai_rekomendasi', name: 'nilai_rekomendasi', orderable: false, searchable: false },
                { data: 'status_realisasi', name: 'status_realisasi', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush
