@extends('layouts.layout')
@section('title', 'Dokumen - ' . $organisasi->nama)
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Dokumen Organisasi</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelompok-masyarakat.index') }}">Kelompok Masyarakat</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('kelompok-masyarakat.dokumen.index', $organisasi->id) }}">Dokumen - {{ $organisasi->nama }}</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-2">
                    <a href="{{ route('kelompok-masyarakat.index') }}" class="btn btn-outline-secondary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto" id="btnUploadDokumen">
                        <i data-acorn-icon="upload"></i>
                        <span>Upload Dokumen</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body py-3">
                <strong>{{ $organisasi->nama }}</strong>
                <span class="text-muted ms-2">— {{ $organisasi->nomor }}</span>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-3">
                        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                            <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#datatable-dokumen" />
                            <span class="search-magnifier-icon"><i data-acorn-icon="search"></i></span>
                            <span class="search-delete-icon d-none"><i data-acorn-icon="close"></i></span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-7 col-lg-9 col-xxl-10 text-end mb-3">
                        <div class="d-inline-block">
                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#datatable-dokumen">
                                <i data-acorn-icon="print"></i>
                            </button>
                            <div class="d-inline-block datatable-export" data-datatable="#datatable-dokumen">
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
                <table class="data-table data-table-pagination data-table-standard responsive nowrap stripe" id="datatable-dokumen">
                    <thead>
                    <tr>
                        <th class="text-muted text-small text-uppercase">Jenis Dokumen</th>
                        <th class="text-muted text-small text-uppercase">Keterangan</th>
                        {{-- <th class="text-muted text-small text-uppercase">File</th> --}}
                        <th class="text-muted text-small text-uppercase w-10">Aksi</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Preview Dokumen -->
    <div class="modal fade" id="modalPreviewDokumen" tabindex="-1" aria-labelledby="modalPreviewDokumenLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-3">
                    <h5 class="modal-title fw-bold text-primary" id="modalPreviewDokumenLabel">Preview Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-light" style="min-height: 70vh;">
                    <iframe id="previewDokumenIframe" class="d-none w-100 border-0" style="height: 70vh;" title="Preview PDF"></iframe>
                    <div id="previewDokumenImageWrap" class="d-none text-center p-3">
                        <img id="previewDokumenImage" src="" alt="Preview" class="img-fluid" style="max-height: 70vh; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Dokumen -->
    <div class="modal fade" id="modalFormDokumenUpload" tabindex="-1" aria-labelledby="modalFormDokumenUploadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormDokumenUploadLabel">Upload Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formDokumenUpload" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div id="modalDokumenUploadErrors" class="alert alert-danger d-none"></div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                            <select name="jenis_dokumen" id="upload_jenis_dokumen" class="form-select" required>
                                <option value="">Pilih Jenis Dokumen</option>
                                @foreach(\App\Enums\JenisDokumen::cases() as $j)
                                    <option value="{{ $j->value }}">{{ $j->getDescription() }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="upload_jenis_dokumen_error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" name="keterangan" id="upload_keterangan" class="form-control" required maxlength="255" placeholder="Contoh: NPWP atas nama organisasi">
                            <div class="invalid-feedback" id="upload_keterangan_error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="upload_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                            <small class="text-muted">PDF, JPG, PNG, WebP. Maks. 10 MB</small>
                            <div class="invalid-feedback" id="upload_file_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Dokumen -->
    <div class="modal fade" id="modalFormDokumenEdit" tabindex="-1" aria-labelledby="modalFormDokumenEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormDokumenEditLabel">Edit Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formDokumenEdit" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <div id="modalDokumenEditErrors" class="alert alert-danger d-none"></div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                            <select name="jenis_dokumen" id="edit_jenis_dokumen" class="form-select" required>
                                <option value="">Pilih Jenis Dokumen</option>
                                @foreach(\App\Enums\JenisDokumen::cases() as $j)
                                    <option value="{{ $j->value }}">{{ $j->getDescription() }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="edit_jenis_dokumen_error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" name="keterangan" id="edit_keterangan" class="form-control" required maxlength="255" placeholder="Contoh: NPWP atas nama organisasi">
                            <div class="invalid-feedback" id="edit_keterangan_error"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ganti File</label>
                            <input type="file" name="file" id="edit_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah file. PDF, JPG, PNG, WebP. Maks. 10 MB</small>
                            <div class="invalid-feedback" id="edit_file_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
        _extendDatatables();
        var tableDokumen = $('#datatable-dokumen').DataTable({
            language: {
                paginate: { previous: '<i class="cs-chevron-left"></i>', next: '<i class="cs-chevron-right"></i>' },
            },
            buttons: ['copy', 'excel', 'csv', 'print'],
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
            ajax: "{{ route('kelompok-masyarakat.dokumen.index', $organisasi->id) }}",
            columns: [
                { data: 'jenis_label', name: 'jenis_label' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'action', name: 'action' }
            ],
            order: [[0, 'asc']]
        });
        function _extendDatatables() { new DatatableExtend(); }

        // Preview dokumen di modal
        $(document).on('click', '.btn-preview-dokumen', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            var mime = ($(this).data('mime') || '').toLowerCase();
            var filename = $(this).data('filename') || 'Dokumen';

            $('#modalPreviewDokumenLabel').text(filename);
            var $iframe = $('#previewDokumenIframe');
            var $imgWrap = $('#previewDokumenImageWrap');
            var $img = $('#previewDokumenImage');

            if (mime.indexOf('pdf') !== -1) {
                $imgWrap.addClass('d-none');
                $img.attr('src', '');
                $iframe.removeClass('d-none').attr('src', url);
            } else {
                $iframe.addClass('d-none').attr('src', '');
                $img.attr('src', url);
                $imgWrap.removeClass('d-none');
            }

            new bootstrap.Modal(document.getElementById('modalPreviewDokumen')).show();
        });

        // Kosongkan iframe saat modal ditutup agar tidak tetap load
        document.getElementById('modalPreviewDokumen').addEventListener('hidden.bs.modal', function () {
            $('#previewDokumenIframe').attr('src', '');
            $('#previewDokumenImage').attr('src', '');
        });

        // --- Upload & Edit Dokumen (modal) ---
        var storeDokumenUrl = "{{ route('kelompok-masyarakat.dokumen.store', $organisasi->id) }}";
        var updateDokumenUrlTemplate = "{{ route('kelompok-masyarakat.dokumen.update', [$organisasi->id, '_ID_']) }}";

        function clearUploadFormErrors() {
            $('#modalDokumenUploadErrors').addClass('d-none').empty();
            $('#upload_jenis_dokumen, #upload_keterangan, #upload_file').removeClass('is-invalid');
            $('#upload_jenis_dokumen_error, #upload_keterangan_error, #upload_file_error').text('');
        }

        function clearEditFormErrors() {
            $('#modalDokumenEditErrors').addClass('d-none').empty();
            $('#edit_jenis_dokumen, #edit_keterangan, #edit_file').removeClass('is-invalid');
            $('#edit_jenis_dokumen_error, #edit_keterangan_error, #edit_file_error').text('');
        }

        $('#btnUploadDokumen').on('click', function () {
            clearUploadFormErrors();
            $('#formDokumenUpload')[0].reset();
            $('#formDokumenUpload').attr('action', storeDokumenUrl);
            $('#formDokumenUpload').find('input[name="_method"]').remove();
            $('#upload_file').prop('required', true);
            new bootstrap.Modal(document.getElementById('modalFormDokumenUpload')).show();
        });

        $(document).on('click', '.btn-edit-dokumen', function (e) {
            e.preventDefault();
            var dokumenId = $(this).data('dokumen-id');
            var jenisDokumen = $(this).data('jenis-dokumen');
            var keterangan = $(this).data('keterangan');
            var updateUrl = updateDokumenUrlTemplate.replace('_ID_', dokumenId);
            clearEditFormErrors();
            $('#formDokumenEdit').attr('action', updateUrl);
            $('#edit_jenis_dokumen').val(jenisDokumen || '');
            $('#edit_keterangan').val(keterangan || '');
            $('#edit_file').val('').prop('required', false);
            new bootstrap.Modal(document.getElementById('modalFormDokumenEdit')).show();
        });

        $('#formDokumenUpload').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var fd = new FormData(this);
            clearUploadFormErrors();
            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                success: function (data) {
                    bootstrap.Modal.getInstance(document.getElementById('modalFormDokumenUpload')).hide();
                    tableDokumen.ajax.reload(null, false);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message || 'Dokumen berhasil diunggah' });
                    } else {
                        alert(data.message || 'Dokumen berhasil diunggah');
                    }
                },
                error: function (xhr) {
                    var d = xhr.responseJSON;
                    if (d && d.errors) {
                        var msg = [];
                        $.each(d.errors, function (k, v) { msg.push(v[0]); });
                        $('#modalDokumenUploadErrors').removeClass('d-none').html('<ul class="mb-0">' + msg.map(function(m){ return '<li>'+m+'</li>'; }).join('') + '</ul>');
                    } else {
                        $('#modalDokumenUploadErrors').removeClass('d-none').text(d && d.message ? d.message : 'Terjadi kesalahan.');
                    }
                }
            });
        });

        $('#formDokumenEdit').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var fd = new FormData(this);
            clearEditFormErrors();
            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                success: function (data) {
                    bootstrap.Modal.getInstance(document.getElementById('modalFormDokumenEdit')).hide();
                    tableDokumen.ajax.reload(null, false);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message || 'Dokumen berhasil diperbarui' });
                    } else {
                        alert(data.message || 'Dokumen berhasil diperbarui');
                    }
                },
                error: function (xhr) {
                    var d = xhr.responseJSON;
                    if (d && d.errors) {
                        var msg = [];
                        $.each(d.errors, function (k, v) { msg.push(v[0]); });
                        $('#modalDokumenEditErrors').removeClass('d-none').html('<ul class="mb-0">' + msg.map(function(m){ return '<li>'+m+'</li>'; }).join('') + '</ul>');
                    } else {
                        $('#modalDokumenEditErrors').removeClass('d-none').text(d && d.message ? d.message : 'Terjadi kesalahan.');
                    }
                }
            });
        });
    </script>
@endpush
