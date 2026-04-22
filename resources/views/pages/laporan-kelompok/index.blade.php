@extends('layouts.layout')
@section('title', 'Laporan Rekap Kelompok')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Rekap Kelompok Masyarakat</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Laporan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('laporan-kelompok.index') }}">Rekap Kelompok</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-7 col-xxl-6 mb-3">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            @if (auth()->user()->is_super() || auth()->user()->is_admin())
                                <div class="flex-grow-1 border border-separator bg-foreground" style="min-width: 12rem;">
                                    <select id="filter-opd" class="form-select form-select-sm">
                                        <option value="">Semua OPD</option>
                                        @foreach ($opds as $opd)
                                            <option value="{{ $opd->id }}">{{ $opd->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="flex-grow-1 border border-separator bg-foreground" style="min-width: 9rem;">
                                <select id="filter-status" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                            <div class="flex-grow-1 search-input-container border border-separator bg-foreground search-sm" style="min-width: 10rem;">
                                <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#datatable-laporan-kelompok" />
                                <span class="search-magnifier-icon"><i data-acorn-icon="search"></i></span>
                                <span class="search-delete-icon d-none"><i data-acorn-icon="close"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 col-xxl-6 text-lg-end mb-3">
                        <div class="d-inline-flex flex-wrap gap-2 justify-content-lg-end align-items-center">
                            <button id="btn-preview" class="btn btn-sm btn-outline-secondary" type="button">
                                <i data-acorn-icon="eye"></i>
                                <span class="ms-1">Preview</span>
                            </button>
                            <button
                                class="btn btn-sm btn-outline-primary datatable-print"
                                type="button"
                                data-datatable="#datatable-laporan-kelompok"
                            >
                                <i data-acorn-icon="print"></i>
                                <span class="ms-1">Cetak</span>
                            </button>
                            <a id="btn-export" href="{{ route('laporan-kelompok.export') }}" class="btn btn-sm btn-outline-success">
                                <i data-acorn-icon="download"></i>
                                <span class="ms-1">Export Excel</span>
                            </a>
                            <div class="d-inline-block datatable-export" data-datatable="#datatable-laporan-kelompok">
                                <button
                                    class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown"
                                    data-bs-toggle="dropdown"
                                    type="button"
                                    data-bs-offset="0,3"
                                    title="Export lainnya"
                                >
                                    <i data-acorn-icon="more-horizontal"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                    <button class="dropdown-item export-copy" type="button">Copy</button>
                                    <button class="dropdown-item export-cvs" type="button">CSV</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table
                    class="data-table data-table-pagination data-table-standard responsive nowrap stripe"
                    id="datatable-laporan-kelompok">
                    <thead>
                        <tr>
                            <th class="text-muted text-small text-uppercase">Nama Kelompok</th>
                            <th class="text-muted text-small text-uppercase">Nomor SK</th>
                            <th class="text-muted text-small text-uppercase">Tgl Pembentukan</th>
                            <th class="text-muted text-small text-uppercase">Jenis Kelompok</th>
                            <th class="text-muted text-small text-uppercase">Kecamatan</th>
                            <th class="text-muted text-small text-uppercase">Desa/Kelurahan</th>
                            <th class="text-muted text-small text-uppercase">Anggota</th>
                            <th class="text-muted text-small text-uppercase">Status</th>
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
        #filter-opd + .select2-container--bootstrap4 .select2-selection--single,
        #filter-status + .select2-container--bootstrap4 .select2-selection--single {
            height: 31px;
        }
        #filter-opd + .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered,
        #filter-status + .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 31px;
            padding-top: 0;
            padding-bottom: 0;
        }
        tr.group-opd-header td {
            background: var(--bs-primary, #0d6efd) !important;
            color: #fff;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 6px 10px;
            letter-spacing: 0.03em;
        }
    </style>
@endpush

@push('js_vendor')
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        _extendDatatables();

        const $filterOpd = $('#filter-opd');
        const $filterStatus = $('#filter-status');

        [$filterOpd, $filterStatus].forEach(function ($el) {
            if ($el.length) {
                $el.select2({ theme: 'bootstrap4', width: '100%' });
            }
        });

        const VISIBLE_COLS = 8; // jumlah kolom yang tampil (tanpa kolom opd hidden)

        const tableLaporanKelompok = $('#datatable-laporan-kelompok').DataTable({
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
            ordering: false,
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
            ajax: {
                url: "{!! route('laporan-kelompok.index') !!}",
                data: function (d) {
                    if ($filterOpd.length) d.opd_id = $filterOpd.val();
                    d.status = $filterStatus.val();
                },
            },
            columns: [
                { data: 'nama',           name: 'nama' },
                { data: 'nomor',          name: 'nomor' },
                { data: 'tgl_pembentukan',name: 'tgl_pembentukan', searchable: false },
                { data: 'jenis_kelompok', name: 'jenis_kelompok',  searchable: false },
                { data: 'kecamatan',      name: 'kecamatan',       searchable: false },
                { data: 'desa',           name: 'desa',            searchable: false },
                { data: 'jumlah_anggota', name: 'jumlah_anggota',  searchable: false },
                { data: 'status',         name: 'status',          searchable: false },
                { data: 'opd',            name: 'opd',             visible: false, searchable: false },
            ],
            drawCallback: function () {
                var api   = this.api();
                var rows  = api.rows({ page: 'current' }).nodes();
                var last  = null;

                api.column(8, { page: 'current' }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group-opd-header"><td colspan="' + VISIBLE_COLS + '">' +
                            '<i class="me-2">&#9776;</i>' + group +
                            '</td></tr>'
                        );
                        last = group;
                    }
                });
            },
        });

        $filterOpd.on('change', function () { tableLaporanKelompok.ajax.reload(); });
        $filterStatus.on('change', function () { tableLaporanKelompok.ajax.reload(); });

        $('#btn-preview').on('click', function () {
            const params = new URLSearchParams();
            if ($filterOpd.length && $filterOpd.val()) params.set('opd_id', $filterOpd.val());
            if ($filterStatus.val()) params.set('status', $filterStatus.val());
            const query = params.toString();
            window.open("{!! route('laporan-kelompok.preview') !!}" + (query ? '?' + query : ''), '_blank');
        });

        $('#btn-export').on('click', function (e) {
            e.preventDefault();
            const params = new URLSearchParams();
            if ($filterOpd.length && $filterOpd.val()) params.set('opd_id', $filterOpd.val());
            if ($filterStatus.val()) params.set('status', $filterStatus.val());
            const query = params.toString();
            window.location.href = "{!! route('laporan-kelompok.export') !!}" + (query ? '?' + query : '');
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush