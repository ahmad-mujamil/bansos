@extends('layouts.layout')
@section('title', 'Anggota - ' . $organisasi->nama)
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Anggota Organisasi</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Organisasi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelompok-masyarakat.index') }}">Kelompok Masyarakat</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelompok-masyarakat.anggota.index', $organisasi->id) }}">Anggota - {{ $organisasi->nama }}</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-2">
                    <a href="{{ route('kelompok-masyarakat.index') }}" class="btn btn-outline-secondary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto" id="btnTambahAnggota">
                        <i data-acorn-icon="plus"></i>
                        <span>Tambah Anggota</span>
                    </button>
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <div class="card mb-3">
            <div class="card-body py-3">
                <strong>{{ $organisasi->nama }}</strong>
                <span class="text-muted ms-2">— {{ $organisasi->nomor }}</span>
            </div>
        </div>
        <div class="card mb-5">
            <div class="card-body">
                <!-- Controls Start -->
                <div class="row">
                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-3">
                        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                            <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#datatable-anggota" />
                            <span class="search-magnifier-icon"><i data-acorn-icon="search"></i></span>
                            <span class="search-delete-icon d-none"><i data-acorn-icon="close"></i></span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-7 col-lg-9 col-xxl-10 text-end mb-3">
                        <div class="d-inline-block">
                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#datatable-anggota">
                                <i data-acorn-icon="print"></i>
                            </button>
                            <div class="d-inline-block datatable-export" data-datatable="#datatable-anggota">
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
                <!-- Controls End -->
                <table class="data-table data-table-pagination data-table-standard responsive nowrap stripe" id="datatable-anggota">
                    <thead>
                    <tr>
                        <th class="text-muted text-small text-uppercase">Nama</th>
                        <th class="text-muted text-small text-uppercase">NIK</th>
                        <th class="text-muted text-small text-uppercase">Jabatan</th>
                        <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form Anggota (Tambah / Edit) -->
    <div class="modal fade" id="modalFormAnggota" tabindex="-1" aria-labelledby="modalFormAnggotaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormAnggotaLabel">Tambah Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAnggota">
                    @csrf
                    <div class="modal-body">
                        <div id="modalAnggotaErrors" class="alert alert-danger d-none"></div>
                        <div class="mb-3">
                            <label class="form-label">Penduduk / Anggota <span class="text-danger">*</span></label>
                            <select name="penduduk_id" id="modal_penduduk_id" class="form-select" required>
                                <option value="">Pilih Penduduk</option>
                                @foreach($penduduks as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }} — {{ $p->nik }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="modal_penduduk_id_error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <select name="jabatan" id="modal_jabatan" class="form-select" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach(\App\Enums\JabatanOrganisasi::cases() as $jabatanOption)
                                    <option value="{{ $jabatanOption->value }}">{{ $jabatanOption->getDescription() }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="modal_jabatan_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpanAnggota">
                            <span class="btn-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('/css/vendor/datatables.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
@endpush
@push('js_vendor')
    <script src="{{ asset('js/cs/datatable.extend.js') }}"></script>
    <script src="{{ asset('js/vendor/datatables.min.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script>
        _extendDatatables();
        var tableAnggota = $('#datatable-anggota').DataTable({
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
            ajax: "{{ route('kelompok-masyarakat.anggota.index', $organisasi->id) }}",
            columns: [
                { data: 'nama_penduduk', name: 'nama_penduduk' },
                { data: 'nik_penduduk', name: 'nik_penduduk' },
                { data: 'jabatan_label', name: 'jabatan_label' },
                { data: 'action', name: 'action' }
            ],
            order: [[0, 'asc']]
        });
        function _extendDatatables() { new DatatableExtend(); }

        var organisasiId = "{{ $organisasi->id }}";
        var storeUrl = "{{ route('kelompok-masyarakat.anggota.store', $organisasi->id) }}";
        var updateUrlTemplate = "{{ route('kelompok-masyarakat.anggota.update', [$organisasi->id, '_ID_']) }}";
        var modalEl = document.getElementById('modalFormAnggota');
        var isEditMode = false;

        function openModalAdd() {
            isEditMode = false;
            $('#modalFormAnggotaLabel').text('Tambah Anggota');
            $('#formAnggota').attr('action', storeUrl);
            $('#formAnggota').find('input[name="_method"]').remove();
            $('#formAnggota')[0].reset();
            $('#modalAnggotaErrors').addClass('d-none').empty();
            $('#modal_penduduk_id, #modal_jabatan').removeClass('is-invalid');
            $('#modal_penduduk_id_error, #modal_jabatan_error').text('');
            if ($('#modal_penduduk_id').data('select2')) {
                $('#modal_penduduk_id').val('').trigger('change');
            }
            if ($('#modal_jabatan').data('select2')) {
                $('#modal_jabatan').val('').trigger('change');
            }
            new bootstrap.Modal(modalEl).show();
        }

        function openModalEdit(anggotaId, pendudukId, jabatan) {
            isEditMode = true;
            var updateUrl = updateUrlTemplate.replace('_ID_', anggotaId);
            $('#modalFormAnggotaLabel').text('Edit Anggota');
            $('#formAnggota').attr('action', updateUrl);
            $('#formAnggota').find('input[name="_method"]').remove();
            $('#formAnggota').append('<input type="hidden" name="_method" value="PUT">');
            $('#modal_penduduk_id').val(pendudukId).trigger('change');
            $('#modal_jabatan').val(jabatan).trigger('change');
            $('#modalAnggotaErrors').addClass('d-none').empty();
            $('#modal_penduduk_id, #modal_jabatan').removeClass('is-invalid');
            $('#modal_penduduk_id_error, #modal_jabatan_error').text('');
            new bootstrap.Modal(modalEl).show();
        }

        $('#btnTambahAnggota').on('click', function () {
            openModalAdd();
        });

        $(document).on('click', '.btn-edit-anggota', function (e) {
            e.preventDefault();
            var id = $(this).data('anggota-id');
            var pendudukId = $(this).data('penduduk-id');
            var jabatan = $(this).data('jabatan') || '';
            openModalEdit(id, pendudukId, jabatan);
        });

        $('#modal_penduduk_id').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Penduduk',
            allowClear: true,
            dropdownParent: $('#modalFormAnggota')
        });
        $('#modal_jabatan').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Jabatan',
            allowClear: false,
            dropdownParent: $('#modalFormAnggota')
        });

        function submitForm(e) {
            e.preventDefault();
            var $form = $('#formAnggota');
            var $btn = $('#btnSimpanAnggota');
            $btn.prop('disabled', true).find('.btn-text').text('Menyimpan...');

            $('#modalAnggotaErrors').addClass('d-none').empty();
            $('#modal_penduduk_id, #modal_jabatan').removeClass('is-invalid');
            $('#modal_penduduk_id_error, #modal_jabatan_error').text('');

            var successMsg = isEditMode ? 'Data anggota berhasil disimpan' : 'Anggota berhasil ditambahkan';

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function () {
                    if (typeof bootstrap !== 'undefined' && modalEl) {
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }
                    tableAnggota.ajax.reload(null, false);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: successMsg });
                    } else if (typeof toastr !== 'undefined') {
                        toastr.success(successMsg);
                    } else {
                        alert(successMsg);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        var err = xhr.responseJSON.errors;
                        $.each(err, function (field, msg) {
                            var id = 'modal_' + field.replace('.', '_');
                            var $el = $('#' + id);
                            if ($el.length) {
                                $el.addClass('is-invalid');
                                $('#' + id + '_error').text(Array.isArray(msg) ? msg[0] : msg);
                            }
                        });
                        $('#modalAnggotaErrors').removeClass('d-none').html('<ul class="mb-0">' +
                            $.map(err, function (m) { return '<li>' + (Array.isArray(m) ? m[0] : m) + '</li>'; }).join('') + '</ul>');
                    } else {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan. Silakan coba lagi.';
                        $('#modalAnggotaErrors').removeClass('d-none').html(msg);
                    }
                },
                complete: function () {
                    $btn.prop('disabled', false).find('.btn-text').text('Simpan');
                }
            });
        }

        $('#formAnggota').on('submit', submitForm);
    </script>
@endpush
