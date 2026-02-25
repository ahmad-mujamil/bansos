@extends('layouts.layout')
@section('title', 'Verifikasi Pengguna')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Verifikasi Pengguna</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Verifikasi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('verifikasi-pengguna.index') }}">Verifikasi Pengguna</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <div class="card mb-5">
            <div class="card-body">
                <p class="text-muted mb-3">Daftar pengguna yang belum aktif. Kolom <strong>Status Detail</strong> menandakan apakah pengguna sudah mengisi data diri atau belum.</p>
                <!--  Controls Start -->
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
                <!-- Controls End -->

                <!-- Table Start -->
                <table
                    class="data-table data-table-pagination data-table-standard responsive nowrap stripe"
                    id="datatable-serverside">
                    <thead>
                    <tr>
                        <th class="text-muted text-small text-uppercase">Nama</th>
                        <th class="text-muted text-small text-uppercase">Email</th>
                        <th class="text-muted text-small text-uppercase">Username</th>
                        <th class="text-muted text-small text-uppercase">Role</th>
                        <th class="text-muted text-small text-uppercase">Status Detail</th>
                        <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="text-alternate text-medium">
                    </tbody>
                </table>
                <!-- Table End -->
            </div>
        </div>
        <!-- Content Start -->
    </div>
    <!-- Page Content End -->
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css')}}" />
@endpush
@push('js_vendor')
    <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
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
            lengthChange: false,
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
            ajax: "{!! route('verifikasi-pengguna.index') !!}",
            columns: [
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'desc_role',
                    name: 'role'
                },
                {
                    data: 'status_detail',
                    name: 'status_detail'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        });

        function _extendDatatables() {
            new DatatableExtend();
        }

        document.addEventListener('click', function(e) {
            var el = e.target.closest('[data-confirm-aktifkan]');
            if (!el) return;
            e.preventDefault();
            var href = el.getAttribute('href');
            Swal.fire({
                title: 'Aktifkan Pengguna',
                text: 'Apakah Anda yakin ingin mengaktifkan pengguna ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, aktifkan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) window.location.href = href;
            });
        });
    </script>
@endpush
