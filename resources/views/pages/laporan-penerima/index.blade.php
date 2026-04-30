@extends('layouts.layout')
@section('title', 'Laporan Penerima Bantuan')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Laporan Penerima Bantuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Laporan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('laporan-penerima.index') }}">Penerima Bantuan</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-8 col-xxl-7 mb-3">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <div class="flex-grow-1" style="min-width: 8rem;">
                                <select id="filter-bulan" class="form-select form-select-sm">
                                    <option value="">Semua Bulan</option>
                                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bulan)
                                        <option value="{{ $i + 1 }}">{{ $bulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1" style="min-width: 6rem;">
                                <select id="filter-tahun" class="form-select form-select-sm">
                                    <option value="">Semua Tahun</option>
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="flex-grow-1" style="min-width: 12rem;">
                                <select id="filter-jenis-penerima" class="form-select form-select-sm">
                                    <option value="">Semua Jenis Penerimaan</option>
                                    @foreach(\App\Enums\JenisPenerimaBantuan::cases() as $jenis)
                                        <option value="{{ $jenis->value }}">{{ $jenis->getDescription() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1 search-input-container border border-separator bg-foreground search-sm" style="min-width: 10rem;">
                                <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#datatable-laporan-penerima" />
                                <span class="search-magnifier-icon"><i data-acorn-icon="search"></i></span>
                                <span class="search-delete-icon d-none"><i data-acorn-icon="close"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 col-xxl-5 text-lg-end mb-3">
                        <div class="d-inline-flex flex-wrap gap-2 justify-content-lg-end align-items-center">
                            <button
                                class="btn btn-sm btn-outline-primary datatable-print"
                                type="button"
                                data-datatable="#datatable-laporan-penerima"
                            >
                                <i data-acorn-icon="print"></i>
                                <span class="ms-1">Cetak</span>
                            </button>
                            <a id="btn-export" href="{{ route('laporan-penerima.export') }}" class="btn btn-sm btn-outline-success">
                                <i data-acorn-icon="download"></i>
                                <span class="ms-1">Export Excel</span>
                            </a>
                            <div class="d-inline-block datatable-export" data-datatable="#datatable-laporan-penerima">
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
                    id="datatable-laporan-penerima">
                    <thead>
                        <tr>
                            <th class="text-muted text-small text-uppercase">NIK</th>
                            <th class="text-muted text-small text-uppercase">Nama</th>
                            <th class="text-muted text-small text-uppercase">Alamat</th>
                            <th class="text-muted text-small text-uppercase">Jenis Kelamin</th>
                            <th class="text-muted text-small text-uppercase">Desa/Kelurahan</th>
                            <th class="text-muted text-small text-uppercase">Kecamatan</th>
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
        #datatable-laporan-penerima tbody tr:not(.child) { cursor: pointer; }
        #datatable-laporan-penerima tbody tr.shown > td:first-child::before {
            content: "▾ ";
            color: var(--bs-primary);
            font-size: 0.8rem;
        }
        #datatable-laporan-penerima tbody tr:not(.shown):not(.child) > td:first-child::before {
            content: "▸ ";
            color: #aaa;
            font-size: 0.8rem;
        }
    </style>
@endpush

@push('js_vendor')
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        _extendDatatables();

        const $filterBulan        = $('#filter-bulan');
        const $filterTahun        = $('#filter-tahun');
        const $filterJenisPenerima = $('#filter-jenis-penerima');

        [$filterBulan, $filterTahun, $filterJenisPenerima].forEach(function ($el) {
            $el.select2({ theme: 'bootstrap4', width: '100%' });
        });

        const tableLaporanPenerima = $('#datatable-laporan-penerima').DataTable({
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
                url: "{!! route('laporan-penerima.index') !!}",
                data: function (d) {
                    d.bulan         = $filterBulan.val();
                    d.tahun         = $filterTahun.val();
                    d.jenis_penerima = $filterJenisPenerima.val();
                },
            },
            columns: [
                { data: 'nik',            name: 'nik' },
                { data: 'nama',           name: 'nama' },
                { data: 'alamat',         name: 'alamat' },
                { data: 'jk_label',       name: 'jk_label',       searchable: false, orderable: false },
                { data: 'desa_nama',      name: 'desa_nama',      searchable: false, orderable: false },
                { data: 'kecamatan_nama', name: 'kecamatan_nama', searchable: false, orderable: false },
                { data: 'id',             name: 'id',             visible: false, searchable: false },
            ],
        });

        [$filterBulan, $filterTahun, $filterJenisPenerima].forEach(function ($el) {
            $el.on('change', function () { tableLaporanPenerima.ajax.reload(); });
        });

        const basePenerimaanUrl = "{!! url('laporan-penerima') !!}";

        function rupiahFormat(nilai) {
            return 'Rp ' + Number(nilai).toLocaleString('id-ID');
        }

        function penerimaanRowHtml(items) {
            if (!items.length) {
                return '<tr><td colspan="7" class="text-center text-muted fst-italic py-2">Belum ada penerimaan bantuan</td></tr>';
            }
            return items.map(function (item) {
                return '<tr>' +
                    '<td>' + item.kode_pengajuan + '</td>' +
                    '<td>' + item.jenis_bantuan + '</td>' +
                    '<td class="text-end">' + rupiahFormat(item.nilai) + '</td>' +
                    '<td>' + item.cara_penerimaan + '</td>' +
                    '<td>' + (item.nama_kelompok !== '-' ? item.nama_kelompok : '<span class="text-muted">-</span>') + '</td>' +
                    '<td>' + item.tanggal + '</td>' +
                    '<td><span class="badge bg-' + item.badge + '">' + item.status + '</span></td>' +
                '</tr>';
            }).join('');
        }

        $('#datatable-laporan-penerima tbody').on('click', 'tr:not(.child)', function () {
            var tr  = $(this);
            var row = tableLaporanPenerima.row(tr);

            if (!row.data()) { return; }

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                return;
            }

            var pendudukId = row.data().id;
            var childDiv = $('<div class="p-2"><span class="text-muted fst-italic">Memuat data penerimaan…</span></div>');
            row.child(childDiv).show();
            tr.addClass('shown');

            var params = new URLSearchParams();
            if ($filterBulan.val())         params.set('bulan', $filterBulan.val());
            if ($filterTahun.val())         params.set('tahun', $filterTahun.val());
            if ($filterJenisPenerima.val()) params.set('jenis_penerima', $filterJenisPenerima.val());

            var query = params.toString();
            $.getJSON(basePenerimaanUrl + '/' + pendudukId + '/penerimaan' + (query ? '?' + query : ''), function (data) {
                childDiv.html(
                    '<div class="px-1 py-2">' +
                    '<table class="table table-sm table-bordered mb-0" style="background:#f8f9fa">' +
                        '<thead class="table-secondary"><tr>' +
                            '<th>Kode Pengajuan</th>' +
                            '<th>Jenis Bantuan</th>' +
                            '<th class="text-end">Nilai Bantuan</th>' +
                            '<th>Cara Penerimaan</th>' +
                            '<th>Nama Kelompok</th>' +
                            '<th>Tanggal</th>' +
                            '<th>Status</th>' +
                        '</tr></thead>' +
                        '<tbody>' + penerimaanRowHtml(data) + '</tbody>' +
                    '</table></div>'
                );
            }).fail(function () {
                childDiv.html('<span class="text-danger">Gagal memuat data penerimaan.</span>');
            });
        });

        $('#btn-export').on('click', function (e) {
            e.preventDefault();
            const params = new URLSearchParams();
            if ($filterBulan.val())         params.set('bulan', $filterBulan.val());
            if ($filterTahun.val())         params.set('tahun', $filterTahun.val());
            if ($filterJenisPenerima.val()) params.set('jenis_penerima', $filterJenisPenerima.val());
            const query = params.toString();
            window.location.href = "{!! route('laporan-penerima.export') !!}" + (query ? '?' + query : '');
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush
