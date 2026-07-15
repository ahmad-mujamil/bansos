@extends('layouts.layout')
@section('title', 'Data Kelompok Masyarakat')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Data Kelompok Masyarakat</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Kelompok Masyarakat</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelompok-masyarakat.index') }}">Data Kelompok
                                    Masyarakat</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Add New Button Start -->
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto"
                        data-bs-toggle="modal" data-bs-target="#modalPilihJenisKelompokMasyarakat">
                        <i data-acorn-icon="plus"></i>
                        <span>Tambah Data</span>
                    </button>
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
                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-3">
                        <div
                            class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                            <input class="form-control form-control-sm datatable-search" placeholder="Search"
                                data-datatable="#datatable-serverside" />
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
                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print"
                                type="button" data-datatable="#datatable-serverside">
                                <i data-acorn-icon="print"></i>
                            </button>

                            <div class="d-inline-block datatable-export" data-datatable="#datatable-serverside">
                                <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown"
                                    data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
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
                <table class="data-table data-table-pagination data-table-standard responsive nowrap stripe"
                    id="datatable-serverside">
                    <thead>
                        <tr>
                            <th class="text-muted text-small text-uppercase">Nama</th>
                            <th class="text-muted text-small text-uppercase">Tahun</th>
                            <th class="text-muted text-small text-uppercase">Nomor SK / Tgl Pembentukan</th>
                            <th class="text-muted text-small text-uppercase">Kecamatan / Desa</th>
                            <th class="text-muted text-small text-uppercase">Anggota</th>
                            <th class="text-muted text-small text-uppercase">Dokumen</th>
                            <th class="text-muted text-small text-uppercase">Status</th>
                            <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-alternate text-medium">
                    </tbody>
                </table>
                <!-- Table End -->
            </div>
        </div>
        <div class="modal fade" id="modalPilihJenisKelompokMasyarakat" tabindex="-1"
            aria-labelledby="modalPilihJenisKelompokMasyarakatLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPilihJenisKelompokMasyarakatLabel">Pilih Jenis Bantuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-4">Silakan pilih jenis bantuan yang ingin ditambahkan. Klik kartu untuk melanjutkan.</p>
                        <div class="row g-4">
                            <div class="col-12 col-md-4">
                                <a href="{{ route('kelompok-masyarakat.create', ['jenis' => \App\Enums\JenisPengajuan::BANSOS->value]) }}"
                                    class="text-decoration-none d-block h-100">
                                    <div class="card border-0 shadow-sm h-100 overflow-hidden menu-jenis-card"
                                        style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                        <div class="card-body p-0 position-relative"
                                            style="background: linear-gradient(145deg, #ea580c 0%, #c2410c 50%, #9a3412 100%); min-height: 200px;">
                                            <div class="p-4 position-relative z-1">
                                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3"
                                                    style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                                    <i data-acorn-icon="heart" data-acorn-size="28" class="text-white"></i>
                                                </div>
                                                <h5 class="text-white fw-bold mb-2">Bantuan Sosial</h5>
                                                <p class="text-white mb-0 small opacity-90"
                                                    style="font-size: 0.875rem; line-height: 1.5;">Untuk kategori bansos, form akan menyesuaikan pilihan jenis organisasi yang relevan sebelum data disimpan.</p>
                                                <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold"
                                                    style="font-size: 0.9rem;">
                                                    Lanjutkan
                                                    <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                                </span>
                                            </div>
                                            <div class="position-absolute bottom-0 end-0 opacity-10"
                                                style="font-size: 6rem; line-height: 1;">
                                                <i data-acorn-icon="heart" class="text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a href="{{ route('kelompok-masyarakat.create', ['jenis' => \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK->value]) }}"
                                    class="text-decoration-none d-block h-100">
                                    <div class="card border-0 shadow-sm h-100 overflow-hidden menu-jenis-card"
                                        style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                        <div class="card-body p-0 position-relative"
                                            style="background: linear-gradient(145deg, #0d9488 0%, #0f766e 50%, #115e59 100%); min-height: 200px;">
                                            <div class="p-4 position-relative z-1">
                                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3"
                                                    style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                                    <i data-acorn-icon="grid-1" data-acorn-size="28" class="text-white"></i>
                                                </div>
                                                <h5 class="text-white fw-bold mb-2">Bantuan Kelompok</h5>
                                                <p class="text-white mb-0 small opacity-90"
                                                    style="font-size: 0.875rem; line-height: 1.5;">Kategori ini langsung mengarahkan ke jenis organisasi kelompok masyarakat yang sesuai untuk bantuan kelompok.</p>
                                                <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold"
                                                    style="font-size: 0.9rem;">
                                                    Lanjutkan
                                                    <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                                </span>
                                            </div>
                                            <div class="position-absolute bottom-0 end-0 opacity-10"
                                                style="font-size: 6rem; line-height: 1;">
                                                <i data-acorn-icon="grid-1" class="text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-md-4">
                                <a href="{{ route('kelompok-masyarakat.create', ['jenis' => \App\Enums\JenisPengajuan::HIBAH->value]) }}"
                                    class="text-decoration-none d-block h-100">
                                    <div class="card border-0 shadow-sm h-100 overflow-hidden menu-jenis-card"
                                        style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                        <div class="card-body p-0 position-relative"
                                            style="background: linear-gradient(145deg, #6366f1 0%, #4f46e5 50%, #4338ca 100%); min-height: 200px;">
                                            <div class="p-4 position-relative z-1">
                                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3"
                                                    style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                                    <i data-acorn-icon="gift" data-acorn-size="28" class="text-white"></i>
                                                </div>
                                                <h5 class="text-white fw-bold mb-2">Hibah</h5>
                                                <p class="text-white mb-0 small opacity-90"
                                                    style="font-size: 0.875rem; line-height: 1.5;">Untuk hibah, form akan menampilkan pilihan jenis organisasi yang termasuk kategori penerima hibah.</p>
                                                <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold"
                                                    style="font-size: 0.9rem;">
                                                    Lanjutkan
                                                    <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                                </span>
                                            </div>
                                            <div class="position-absolute bottom-0 end-0 opacity-10"
                                                style="font-size: 6rem; line-height: 1;">
                                                <i data-acorn-icon="gift" class="text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Start -->
    </div>
    <!-- Page Content End -->
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css') }}" />
    <style>
        .menu-jenis-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
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
            buttons: ['copy', 'excel', 'csv', 'print'],
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row align-items-center mt-3"<"col-sm-6"l><"col-sm-6"p>>',
            ajax: "{!! route('kelompok-masyarakat.index') !!}",
            columns: [
                { data: 'nama', name: 'nama' },
                { data: 'tahun_anggaran', name: 'tahun_anggaran' },
                { data: 'nomor_tgl', name: 'nomor_tgl' },
                { data: 'wilayah', name: 'wilayah' },
                { data: 'anggota', name: 'anggota' },
                { data: 'dokumen', name: 'dokumen' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' }
            ],
            order: [
                [0, 'asc']
            ]
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush
