@extends('layouts.layout')
@section('title', 'Laporan Pengajuan')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Laporan Pengajuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Laporan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('laporan-pengajuan.index') }}">Pengajuan</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">

                {{-- Tab nav --}}
                <ul class="nav nav-tabs mb-3" id="tab-jenis-pengajuan" role="tablist">
                    @foreach(\App\Enums\KategoriBantuan::cases() as $kat)
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link {{ $loop->first ? 'active' : '' }}"
                                data-kategori="{{ $kat->value }}"
                                type="button"
                                role="tab"
                            >
                                {{ $kat->getDescription() }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                {{-- Filters & toolbar --}}
                <div class="row">
                    <div class="col-12 col-lg-8 col-xxl-7 mb-3">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <div class="flex-grow-1" style="min-width: 9rem;">
                                <select id="filter-status-laporan" class="form-select form-select-sm">
                                    <option value="all">Semua Status</option>
                                    @foreach(\App\Enums\PengajuanStatus::cases() as $st)
                                        <option value="{{ $st->value }}">{{ $st->getDescription() }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                            <div class="flex-grow-1 search-input-container border border-separator bg-foreground search-sm" style="min-width: 10rem;">
                                <input class="form-control form-control-sm datatable-search" placeholder="Cari…" data-datatable="#datatable-laporan-pengajuan" />
                                <span class="search-magnifier-icon"><i data-acorn-icon="search"></i></span>
                                <span class="search-delete-icon d-none"><i data-acorn-icon="close"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 col-xxl-5 text-lg-end mb-3">
                        <div class="d-inline-flex gap-2 align-items-center justify-content-lg-end">
                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#datatable-laporan-pengajuan">
                                <i data-acorn-icon="print"></i>
                            </button>
                            <div class="d-inline-block datatable-export" data-datatable="#datatable-laporan-pengajuan">
                                <button
                                    class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown"
                                    data-bs-toggle="dropdown"
                                    type="button"
                                    data-bs-offset="0,3"
                                    title="Export"
                                >
                                    <i data-acorn-icon="download"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                    <button class="dropdown-item export-copy" type="button">Copy</button>
                                    <button class="dropdown-item export-excel" type="button">Excel</button>
                                    <button class="dropdown-item export-cvs" type="button">CSV</button>
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
                                <th class="text-muted text-small text-uppercase" id="th-kelompok">Kelompok/Organisasi</th>
                                <th class="text-muted text-small text-uppercase">Kode</th>
                                <th class="text-muted text-small text-uppercase">Judul</th>
                                <th class="text-muted text-small text-uppercase">Jenis Bantuan</th>
                                <th class="text-muted text-small text-uppercase">Nilai Usulan</th>
                                <th class="text-muted text-small text-uppercase">OPD</th>
                                <th class="text-muted text-small text-uppercase">Status</th>
                                <th class="text-muted text-small text-uppercase">Tgl Pengajuan</th>
                                <th class="text-muted text-small text-uppercase">Keputusan</th>
                                <th class="text-muted text-small text-uppercase">Nilai Rekom.</th>
                                <th class="text-muted text-small text-uppercase">Kriteria</th>
                                <th class="text-muted text-small text-uppercase">Adm.</th>
                                <th class="text-muted text-small text-uppercase">Kesesuaian</th>
                                <th class="text-muted text-small text-uppercase">Prog. Pemda</th>
                                <th class="text-muted text-small text-uppercase">Catatan</th>
                                <th class="text-muted text-small text-uppercase">Verifikator</th>
                                <th class="text-muted text-small text-uppercase">Tgl Verifikasi</th>
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
@endpush

@push('js_vendor')
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script>
        _extendDatatables();

        const $filterStatus = $('#filter-status-laporan');
        const $filterBulan  = $('#filter-bulan');
        const $filterTahun  = $('#filter-tahun');

        [$filterStatus, $filterBulan, $filterTahun].forEach(function ($el) {
            $el.select2({ theme: 'bootstrap4', width: '100%' });
        });

        // Tab state — default ke tab pertama
        let activeKategori = $('#tab-jenis-pengajuan .nav-link.active').data('kategori');

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
            lengthChange: false,
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
            ajax: {
                url: "{!! route('laporan-pengajuan.index') !!}",
                data: function (d) {
                    d.kategori = activeKategori;
                    d.status   = $filterStatus.val();
                    d.bulan    = $filterBulan.val();
                    d.tahun    = $filterTahun.val();
                },
            },
            columns: [
                { data: 'kelompok',           name: 'organisasi.nama',                        orderable: false },
                { data: 'kode_pengajuan',     name: 'kode_pengajuan' },
                { data: 'judul',              name: 'judul' },
                { data: 'jenis_bantuan',      name: 'jenisBantuan.nama' },
                { data: 'nilai_usulan',       name: 'nilai' },
                { data: 'opd',                name: 'opd.nama' },
                { data: 'status',             name: 'status',                                 orderable: false },
                { data: 'tanggal_pengajuan',  name: 'created_at' },
                { data: 'keputusan',          name: 'status',                                 orderable: false },
                { data: 'nilai_rekomendasi',  name: 'verifikasiPengajuan.nilai_rekomendasi',  orderable: false },
                { data: 'vk',                 name: 'verifikasiPengajuan.lulus_kriteria',     orderable: false },
                { data: 'va',                 name: 'verifikasiPengajuan.lulus_administrasi', orderable: false },
                { data: 'vks',                name: 'verifikasiPengajuan.lulus_kesesuaian',   orderable: false },
                { data: 'vpp',                name: 'verifikasiPengajuan.sesuai_program_pemda', orderable: false },
                { data: 'catatan_verifikasi', name: 'verifikasiPengajuan.catatan',            orderable: false },
                { data: 'verifikator',        name: 'verifikasiPengajuan.user.nama',          orderable: false },
                { data: 'tanggal_verifikasi', name: 'verified_at',                            orderable: false },
                { data: 'action',             name: 'action',                                 orderable: false, searchable: false },
            ],
        });

        // Tab switch
        $('#tab-jenis-pengajuan').on('click', '.nav-link', function () {
            $('#tab-jenis-pengajuan .nav-link').removeClass('active');
            $(this).addClass('active');
            activeKategori = $(this).data('kategori');
            tableLaporan.ajax.reload();
        });

        // Filter change
        [$filterStatus, $filterBulan, $filterTahun].forEach(function ($el) {
            $el.on('change', function () { tableLaporan.ajax.reload(); });
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush
