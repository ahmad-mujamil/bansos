@extends('layouts.layout')
@section('title', 'Blacklist Kelompok/Organisasi')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Blacklist Kelompok/Organisasi</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">OPD</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('blacklist.index') }}">Blacklist</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <p class="text-muted mb-3">
                    Halaman ini digunakan untuk melakukan blacklist/unblacklist pada kelompok/organisasi yang terdaftar di OPD Anda.
                    
                </p>

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
                    <div class="col-12 col-sm-7 col-lg-9 col-xxl-10 text-end mb-3">
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

                <table class="data-table data-table-pagination data-table-standard responsive nowrap stripe" id="datatable-serverside">
                    <thead>
                        <tr>
                            <th class="text-muted text-small text-uppercase">Nama</th>
                            <th class="text-muted text-small text-uppercase">Jenis</th>
                            <th class="text-muted text-small text-uppercase">Status</th>
                            <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-alternate text-medium"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBlacklist" tabindex="-1" aria-labelledby="modalBlacklistLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formBlacklist" method="POST" action="">
                    @csrf
                    <input type="hidden" name="jadi_blacklist" id="jadi_blacklist" value="1">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBlacklistLabel">Ubah Status Blacklist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <div class="text-muted">Target</div>
                            <div class="fw-semibold" id="targetNama">-</div>
                        </div>
                        <div class="mb-3">
                            <label for="alasan" class="form-label text-small text-uppercase fw-semibold">Alasan (opsional)</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3" placeholder="Masukkan alasan blacklist/unblacklist (opsional)"></textarea>
                        </div>
                        <div class="alert alert-warning mb-0">
                            Pastikan keputusan Anda benar. Perubahan status akan langsung berlaku.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitBlacklist">Simpan</button>
                    </div>
                </form>
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
            lengthChange: true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row align-items-center mt-3"<"col-sm-6"l><"col-sm-6"p>>',
            ajax: "{!! route('blacklist.index') !!}",
            columns: [
                { data: 'nama', name: 'nama' },
                { data: 'jenis', name: 'jenis' },
                { data: 'status_blacklist', name: 'status_blacklist', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });

        function _extendDatatables() {
            new DatatableExtend();
        }

        const $modal = $('#modalBlacklist');
        const baseToggleUrl = "{!! rtrim(url('blacklist'), '/') !!}";

        $(document).on('click', '.btn-toggle-blacklist', function () {
            const id = $(this).data('id');
            const target = $(this).data('target');
            const nama = $(this).data('nama');

            $('#targetNama').text(nama);
            $('#jadi_blacklist').val(target ? 1 : 0);
            $('#alasan').val('');

            const actionUrl = `${baseToggleUrl}/${id}/toggle`;
            $('#formBlacklist').attr('action', actionUrl);

            $('#modalBlacklistLabel').text(target ? 'Blacklist' : 'Unblacklist');
            $('#btnSubmitBlacklist').text(target ? 'Blacklist' : 'Unblacklist');

            $modal.modal('show');
        });
    </script>
@endpush

