@extends('layouts.layout')
@section('title', 'Pengajuan OPD')
@section('content')
<div class="col">
    <div class="page-title-container mb-3">
        <div class="row">
            <div class="col mb-2">
                <h1 class="mb-2 pb-0 display-4">Pengajuan OPD</h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:;">Pengajuan OPD</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                <button type="button" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#modalPilihJenisPengajuanOpd">
                    <i data-acorn-icon="plus"></i>
                    <span>Tambah Pengajuan</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-6 col-xxl-6 mb-3">
                    <div class="d-flex gap-2 w-100 align-items-center">
                        <div class="flex-grow-1">
                            <select id="filter-status-pengajuan-opd" class="form-select form-select-sm">
                                <option value="all" selected>Semua Status</option>
                                <option value="{{ \App\Enums\PengajuanStatus::DRAFT->value }}">Draft</option>
                                <option value="{{ \App\Enums\PengajuanStatus::DIAJUKAN->value }}">Diajukan</option>
                                <option value="{{ \App\Enums\PengajuanStatus::DISETUJUI->value }}">Disetujui</option>
                                <option value="{{ \App\Enums\PengajuanStatus::DITOLAK->value }}">Ditolak</option>
                            </select>
                        </div>
                        <div class="flex-grow-1 search-input-container border border-separator bg-foreground search-sm">
                            <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#datatable-pengajuan-opd" />
                            <span class="search-magnifier-icon"><i data-acorn-icon="search"></i></span>
                            <span class="search-delete-icon d-none"><i data-acorn-icon="close"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-6 col-xxl-6 text-end mb-3">
                    <div class="d-inline-block">
                        <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#datatable-pengajuan-opd">
                            <i data-acorn-icon="print"></i>
                        </button>
                        <div class="d-inline-block datatable-export" data-datatable="#datatable-pengajuan-opd">
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

            <table class="data-table data-table-pagination data-table-standard responsive nowrap stripe" id="datatable-pengajuan-opd">
                <thead>
                    <tr>
                        <th class="text-muted text-small text-uppercase">Kode</th>
                        <th class="text-muted text-small text-uppercase">Jenis Bantuan</th>
                        <th class="text-muted text-small text-uppercase">Pemohon</th>
                        <th class="text-muted text-small text-uppercase">Judul</th>
                        <th class="text-muted text-small text-uppercase">Status</th>
                        <th class="text-muted text-small text-uppercase">Tanggal</th>
                        <th class="text-muted text-small text-uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-alternate text-medium"></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalPilihJenisPengajuanOpd" tabindex="-1" aria-labelledby="modalPilihJenisPengajuanOpdLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPilihJenisPengajuanOpdLabel">Pilih Jenis Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">Silakan pilih jenis bantuan yang ingin Anda ajukan. Klik kartu untuk melanjutkan.</p>
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('pengajuan-opd.create', ['jenis' => 'bansos']) }}" class="text-decoration-none d-block h-100">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                    <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #ea580c 0%, #c2410c 50%, #9a3412 100%); min-height: 200px;">
                                        <div class="p-4 position-relative z-1">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                                <i data-acorn-icon="heart" data-acorn-size="28" class="text-white"></i>
                                            </div>
                                            <h5 class="text-white fw-bold mb-2">Bantuan Sosial</h5>
                                            <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan sosial untuk meringankan beban dan mendukung kebutuhan dasar penerima manfaat.</p>
                                            <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                                Ajukan sekarang
                                                <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                            </span>
                                        </div>
                                        <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                            <i data-acorn-icon="heart" class="text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('pengajuan-opd.create', ['jenis' => 'bantuan_kelompok']) }}" class="text-decoration-none d-block h-100">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                    <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #0d9488 0%, #0f766e 50%, #115e59 100%); min-height: 200px;">
                                        <div class="p-4 position-relative z-1">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                                <i data-acorn-icon="grid-1" data-acorn-size="28" class="text-white"></i>
                                            </div>
                                            <h5 class="text-white fw-bold mb-2">Bantuan ke Masyarakat</h5>
                                            <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan untuk program pemberdayaan dan peningkatan kesejahteraan kelompok masyarakat.</p>
                                            <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                                Ajukan sekarang
                                                <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                            </span>
                                        </div>
                                        <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                            <i data-acorn-icon="grid-1" class="text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('pengajuan-opd.create', ['jenis' => 'hibah']) }}" class="text-decoration-none d-block h-100">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                    <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #6366f1 0%, #4f46e5 50%, #4338ca 100%); min-height: 200px;">
                                        <div class="p-4 position-relative z-1">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                                <i data-acorn-icon="gift" data-acorn-size="28" class="text-white"></i>
                                            </div>
                                            <h5 class="text-white fw-bold mb-2">Hibah</h5>
                                            <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan hibah untuk mendukung kegiatan atau program yang Anda jalankan.</p>
                                            <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                                Ajukan sekarang
                                                <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                            </span>
                                        </div>
                                        <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                            <i data-acorn-icon="gift" class="text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('pengajuan-opd.create', ['jenis' => 'subsidi_bunga']) }}" class="text-decoration-none d-block h-100">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                    <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #d97706 0%, #b45309 50%, #92400e 100%); min-height: 200px;">
                                        <div class="p-4 position-relative z-1">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                                <i data-acorn-icon="dollar" data-acorn-size="28" class="text-white"></i>
                                            </div>
                                            <h5 class="text-white fw-bold mb-2">Subsidi Bunga</h5>
                                            <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan subsidi bunga untuk kelompok masyarakat penerima manfaat.</p>
                                            <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                                Ajukan sekarang
                                                <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                            </span>
                                        </div>
                                        <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                            <i data-acorn-icon="dollar" class="text-white"></i>
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
    <style>
        .menu-bantuan-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
        }
    </style>
</div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
    <style>
        #filter-status-pengajuan-opd + .select2-container--bootstrap4 .select2-selection--single {
            height: 31px;
        }
        #filter-status-pengajuan-opd + .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 31px;
            padding-top: 0;
            padding-bottom: 0;
        }
    </style>
@endpush

@push('js_vendor')
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
@endpush

@push('js_page')
<script>
    $('#filter-status-pengajuan-opd').select2({
        theme: 'bootstrap4',
        width: '100%',
    });

    new DatatableExtend();

    const tablePengajuanOpd = $('#datatable-pengajuan-opd').DataTable({
        language: {
            paginate: {
                previous: '<i class="cs-chevron-left"></i>',
                next: '<i class="cs-chevron-right"></i>',
            },
            emptyTable: 'Belum ada pengajuan OPD.',
            zeroRecords: 'Tidak ada data yang cocok.',
        },
        buttons: ['copy', 'excel', 'csv', 'print'],
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: true,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
        order: [[5, 'desc']],
        sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row align-items-center mt-3"<"col-sm-6"l><"col-sm-6"p>>',
        ajax: {
            url: "{!! route('pengajuan-opd.index') !!}",
            data: function(d) {
                d.status = $('#filter-status-pengajuan-opd').val();
            }
        },
        columns: [
            { data: 'kode_pengajuan', name: 'kode_pengajuan' },
            { data: 'jenis_bantuan', name: 'jenis_bantuan', orderable: false, searchable: false },
            { data: 'pemohon', name: 'pemohon', orderable: false, searchable: false },
            { data: 'judul', name: 'judul' },
            { data: 'status', name: 'status' },
            { data: 'tanggal', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
    });

    $('#filter-status-pengajuan-opd').on('change', function() {
        tablePengajuanOpd.ajax.reload();
    });

    $(document).on('submit', 'form.form-ajukan-pengajuan', function(e) {
        e.preventDefault();
        const f = this;
        Swal.fire({
            title: 'Ajukan Pengajuan',
            text: 'Apakah Anda yakin ingin mengajukan pengajuan ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, ajukan',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) f.submit();
        });
    });

    $(document).on('submit', 'form.form-hapus-pengajuan', function(e) {
        e.preventDefault();
        const f = this;
        Swal.fire({
            title: 'Hapus Pengajuan',
            text: 'Pengajuan yang dihapus tidak dapat dikembalikan. Lanjutkan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545'
        }).then(function(result) {
            if (result.isConfirmed) f.submit();
        });
    });
</script>
@endpush
