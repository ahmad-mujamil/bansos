@extends('layouts.layout')
@section('title', 'Tahun Anggaran')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Tahun Anggaran</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('tahun-anggaran.index') }}">Tahun Anggaran</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('tahun-anggaran.create') }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="plus"></i>
                        <span>Tambah Data</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-3">
                        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
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

                <table
                    class="data-table data-table-pagination data-table-standard responsive nowrap stripe"
                    id="datatable-serverside">
                    <thead>
                    <tr>
                        <th class="text-muted text-small text-uppercase">Tahun</th>
                        <th class="text-muted text-small text-uppercase">Label</th>
                        <th class="text-muted text-small text-uppercase">Keterangan</th>
                        <th class="text-muted text-small text-uppercase">Status</th>
                        <th class="text-muted text-small text-uppercase w-10">Aksi</th>
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
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css')}}" />
@endpush
@push('js_vendor')
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        _extendDatatables()
        $('#datatable-serverside').DataTable({
            language: {
                paginate: {
                    previous: '<i class="cs-chevron-left"></i>',
                    next: '<i class="cs-chevron-right"></i>',
                },
            },
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row align-items-center mt-3"<"col-sm-6"l><"col-sm-6"p>>',
            ajax: "{!! route('tahun-anggaran.index') !!}",
            columns: [
                { data: 'tahun', name: 'tahun' },
                { data: 'label_tampil', name: 'label' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'status', name: 'is_terkunci' },
                { data: 'action', name: 'action' }
            ],
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush
