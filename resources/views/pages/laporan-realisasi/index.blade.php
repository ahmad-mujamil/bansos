@extends('layouts.layout')
@section('title', 'Laporan Realisasi')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Laporan Realisasi</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Laporan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('laporan-realisasi.index') }}">Realisasi</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="card mb-5">
            <div class="card-body">
                {{-- Tab jenis bantuan (selaras dengan laporan pengajuan) --}}
                <div class="laporan-tabs d-flex flex-wrap gap-2 mb-4" role="tablist">
                    @php
                        $tabMeta = [
                            'all'                                               => ['class' => 'laporan-tab-all',      'icon' => 'layout-3', 'title' => 'Semua',            'sub' => 'Semua Jenis Bantuan'],
                            \App\Enums\JenisPengajuan::BANSOS->value             => ['class' => 'laporan-tab-bansos',   'icon' => 'user',     'title' => 'Bansos',           'sub' => 'Bantuan Sosial'],
                            \App\Enums\JenisPengajuan::HIBAH->value              => ['class' => 'laporan-tab-hibah',    'icon' => 'gift',     'title' => 'Hibah',            'sub' => 'Bantuan Hibah'],
                            \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK->value   => ['class' => 'laporan-tab-kelompok', 'icon' => 'building', 'title' => 'Bantuan Kelompok', 'sub' => 'Barang Diserahkan ke Masyarakat'],
                            \App\Enums\JenisPengajuan::SUBSIDI_BUNGA->value      => ['class' => 'laporan-tab-subsidi',  'icon' => 'dollar',   'title' => 'Subsidi Bunga',    'sub' => 'Subsidi Bunga Kredit'],
                        ];
                    @endphp
                    @foreach ($tabMeta as $value => $meta)
                        <button type="button"
                            class="laporan-tab {{ $meta['class'] }} {{ $loop->first ? 'active' : '' }}"
                            data-kategori="{{ $value }}"
                            role="tab"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            <span class="laporan-tab-icon"><i data-acorn-icon="{{ $meta['icon'] }}"></i></span>
                            <span class="laporan-tab-label">
                                <span class="laporan-tab-title">{{ $meta['title'] }}</span>
                                <span class="laporan-tab-sub">{{ $meta['sub'] }}</span>
                            </span>
                        </button>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-12 col-lg-8 col-xxl-7 mb-3">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            @if ($showOpdFilter)
                                <div class="flex-grow-1" style="min-width: 12rem;">
                                    <select id="filter-opd-laporan-realisasi" class="form-select form-select-sm">
                                        <option value="all">Semua OPD</option>
                                        @foreach ($opds as $opd)
                                            <option value="{{ $opd->id }}">{{ $opd->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="flex-grow-1" style="min-width: 10rem;">
                                <select id="filter-realisasi-laporan" class="form-select form-select-sm">
                                    <option value="all">Semua realisasi</option>
                                    <option value="sudah">Sudah realisasi</option>
                                    <option value="belum">Belum realisasi</option>
                                </select>
                            </div>
                            <div class="flex-grow-1" style="min-width: 9rem;">
                                @php
                                    $namaBulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                                @endphp
                                <select id="filter-bulan-laporan-realisasi" class="form-select form-select-sm" title="Periode bulan (tahun mengikuti Tahun Anggaran)">
                                    <option value="all">Semua bulan</option>
                                    @foreach ($namaBulan as $num => $label)
                                        <option value="{{ $num }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1 search-input-container border border-separator bg-foreground search-sm" style="min-width: 10rem;">
                                <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#datatable-laporan-realisasi" />
                                <span class="search-magnifier-icon">
                                    <i data-acorn-icon="search"></i>
                                </span>
                                <span class="search-delete-icon d-none">
                                    <i data-acorn-icon="close"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 col-xxl-5 text-lg-end mb-3">
                        <div class="d-inline-flex flex-wrap gap-2 justify-content-lg-end align-items-center">
                            <button
                                class="btn btn-sm btn-outline-primary datatable-print"
                                type="button"
                                data-datatable="#datatable-laporan-realisasi"
                            >
                                <i data-acorn-icon="print"></i>
                                <span class="ms-1">Cetak</span>
                            </button>
                            <div class="d-inline-block datatable-export" data-datatable="#datatable-laporan-realisasi">
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
                    id="datatable-laporan-realisasi">
                    <thead>
                    <tr>
                        <th class="text-muted text-small text-uppercase">Kode Pengajuan</th>
                        <th class="text-muted text-small text-uppercase">Judul</th>
                        <th class="text-muted text-small text-uppercase">OPD</th>
                        <th class="text-muted text-small text-uppercase">Tanggal BAST</th>
                        <th class="text-muted text-small text-uppercase">Nilai Pengajuan</th>
                        <th class="text-muted text-small text-uppercase">Nilai Rekomendasi</th>
                        <th class="text-muted text-small text-uppercase">Realisasi</th>
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
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
    @include('partials.laporan-tabs-style')
    <style>
        #filter-opd-laporan-realisasi + .select2-container--bootstrap4 .select2-selection--single {
            height: 31px;
        }
        #filter-opd-laporan-realisasi + .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
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
        _extendDatatables()
        let kategoriLaporanRealisasi = 'all';
        const $filterOpd = $('#filter-opd-laporan-realisasi');
        if ($filterOpd.length) {
            $filterOpd.select2({
                theme: 'bootstrap4',
                width: '100%',
            });
        }
        const tableLaporanRealisasi = $('#datatable-laporan-realisasi').DataTable({
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
            order: [[0, 'desc']],
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row align-items-center mt-3"<"col-sm-6"l><"col-sm-6"p>>',
            ajax: {
                url: "{!! route('laporan-realisasi.index') !!}",
                data: function (d) {
                    d.kategori = kategoriLaporanRealisasi;
                    d.opd = $filterOpd.length ? ($filterOpd.val() || 'all') : 'all';
                    d.realisasi = $('#filter-realisasi-laporan').val() || 'all';
                    d.bulan = $('#filter-bulan-laporan-realisasi').val() || 'all';
                },
            },
            columns: [
                { data: 'kode_pengajuan', name: 'kode_pengajuan' },
                { data: 'judul', name: 'judul' },
                { data: 'opd', name: 'opd', orderable: false, searchable: false },
                { data: 'bast_tanggal', name: 'bast_tanggal', orderable: false, searchable: false },
                { data: 'nilai_pengajuan', name: 'nilai_pengajuan', orderable: false, searchable: false },
                { data: 'nilai_rekomendasi', name: 'nilai_rekomendasi', orderable: false, searchable: false },
                { data: 'status_realisasi', name: 'status_realisasi', orderable: false, searchable: false },
            ],
        });

        // Tab jenis bantuan & select filter → muat ulang tabel tanpa reload halaman.
        $('.laporan-tab').on('click', function () {
            const $tab = $(this);
            if ($tab.hasClass('active')) {
                return;
            }
            $('.laporan-tab').removeClass('active').attr('aria-selected', 'false');
            $tab.addClass('active').attr('aria-selected', 'true');
            kategoriLaporanRealisasi = $tab.data('kategori');
            tableLaporanRealisasi.ajax.reload();
        });

        $filterOpd.add('#filter-realisasi-laporan, #filter-bulan-laporan-realisasi').on('change', function () {
            tableLaporanRealisasi.ajax.reload();
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush
