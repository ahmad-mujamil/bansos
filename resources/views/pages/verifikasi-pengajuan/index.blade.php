@extends('layouts.layout')
@section('title', 'Verifikasi Pengajuan')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Verifikasi Pengajuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Verifikasi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('verifikasi-pengajuan.index') }}">Pengajuan Bantuan</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <p class="text-muted mb-3">
                    Daftar pengajuan yang menunggu verifikasi. Klik <strong>Lihat</strong> untuk memproses keputusan.
                </p>

                <!-- Controls Start -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-6 col-xxl-6 mb-3">
                        <div class="d-flex gap-2 w-100 align-items-center">
                            <div class="flex-grow-1">
                                <select id="filter-status-verifikasi" class="form-select form-select-sm">
                                    <option value="all" selected>Semua</option>
                                    <option value="{{ \App\Enums\PengajuanStatus::DIAJUKAN->value }}">Diajukan</option>
                                    <option value="{{ \App\Enums\PengajuanStatus::DISETUJUI->value }}">Disetujui</option>
                                    <option value="{{ \App\Enums\PengajuanStatus::DITOLAK->value }}">Ditolak</option>
                                </select>
                            </div>

                            <div class="flex-grow-1 search-input-container border border-separator bg-foreground search-sm">
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
                    <div class="col-12 col-sm-6 col-lg-6 col-xxl-6 text-end mb-3">
                        <div class="d-inline-block">
                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#datatable-serverside">
                                <i data-acorn-icon="print"></i>
                            </button>

                            <div class="d-inline-block datatable-export" data-datatable="#datatable-serverside">
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
                <!-- Controls End -->

                <!-- Table Start -->
                <table
                    class="data-table data-table-pagination data-table-standard responsive nowrap stripe"
                    id="datatable-serverside"
                >
                    <thead>
                        <tr>
                            <th class="text-muted text-small text-uppercase">Kode</th>
                            <th class="text-muted text-small text-uppercase">Jenis</th>
                            <th class="text-muted text-small text-uppercase">Judul Usulan</th>
                            <th class="text-muted text-small text-uppercase">Status</th>
                            <th class="text-muted text-small text-uppercase">Tanggal</th>
                            <th class="text-muted text-small text-uppercase">User</th>
                            <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-alternate text-medium"></tbody>
                </table>
                <!-- Table End -->
            </div>
        </div>
        <!-- Content Start -->
    </div>
    <!-- Page Content End -->

    <!-- Modal Upload BA Verifikasi -->
    <div class="modal fade" id="modalUploadBa" tabindex="-1" aria-labelledby="modalUploadBaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formUploadBa" method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUploadBaLabel">Upload BA Verifikasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tgl_pengesahan" class="form-label text-small text-uppercase fw-semibold">Tanggal Pengesahan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tgl_pengesahan" name="tgl_pengesahan" placeholder="Pilih tanggal..." autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label for="dokumen_ba" class="form-label text-small text-uppercase fw-semibold">Dokumen BA (PDF) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="dokumen_ba" name="dokumen" accept=".pdf,application/pdf" required>
                            <div class="form-text">Maksimal ukuran file 10 MB.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-icon btn-icon-start">
                            <i data-acorn-icon="upload"></i>
                            <span>Upload</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Upload BA Verifikasi -->
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-datepicker3.standalone.min.css') }}">
    <style>
        /* Samakan tinggi Select2 dengan input/selector ukuran kecil */
        #filter-status-verifikasi + .select2-container--bootstrap4 .select2-selection--single {
            height: 31px;
        }

        #filter-status-verifikasi + .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
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
    <script src="{{ asset('js/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/vendor/datepicker/locales/bootstrap-datepicker.id.min.js') }}"></script>
    <script>
        $('#filter-status-verifikasi').select2({
            theme: 'bootstrap4',
            width: '100%',
        });

        _extendDatatables();
        const table = $('#datatable-serverside').DataTable({
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
                url: "{!! route('verifikasi-pengajuan.index') !!}",
                data: function(d) {
                    d.status = $('#filter-status-verifikasi').val();
                }
            },
            columns: [
                { data: 'kode_pengajuan', name: 'kode_pengajuan' },
                { data: 'jenis', name: 'jenis' },
                { data: 'judul', name: 'judul' },
                { data: 'status', name: 'status' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'user', name: 'user' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        });

        $('#filter-status-verifikasi').on('change', function() {
            table.ajax.reload();
        });

        // Bootstrap Datepicker for tgl_pengesahan
        $('#tgl_pengesahan').datepicker({
            format: 'yyyy-mm-dd',
            language: 'id',
            autoclose: true,
            todayHighlight: true,
        });

        // Upload BA Modal
        const $modalUploadBa = $('#modalUploadBa');
        const formUploadBa   = document.getElementById('formUploadBa');
        const baseUploadUrl  = "{!! rtrim(url('verifikasi-pengajuan'), '/') !!}";

        $modalUploadBa.on('hidden.bs.modal', function () {
            formUploadBa.reset();
        });

        $('#datatable-serverside tbody').on('click', '.btn-upload-ba', function () {
            const id = $(this).data('id');
            formUploadBa.action = baseUploadUrl + '/' + id + '/upload-ba';
            $modalUploadBa.modal('show');
        });

        function _extendDatatables() {
            new DatatableExtend();
        }
    </script>
@endpush

