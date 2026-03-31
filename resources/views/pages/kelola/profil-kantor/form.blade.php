@extends('layouts.layout')
@section('title', 'Profil Kantor')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">Profil Kantor</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Landing Page</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('profil-kantor.edit') }}">Profil Kantor</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <style>
            /* Bingkai foto kompak (3:4), selaras tinggi blok Nama + NIP */
            .profil-kantor-foto-field {
                --profil-foto-w: 140px;
                --profil-foto-h: 187px;
                width: var(--profil-foto-w);
                max-width: 100%;
            }

            .profil-kantor-foto-field .profil-kantor-foto-inner {
                width: var(--profil-foto-w);
                height: var(--profil-foto-h);
                max-width: 100%;
            }

            .profil-kantor-foto-field [data-profil-foto-img] {
                object-fit: cover;
                object-position: center top;
            }

            .profil-kantor-foto-field .profil-kantor-foto-frame {
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .profil-kantor-foto-field.is-dragover .profil-kantor-foto-frame {
                border-color: var(--bs-primary) !important;
                box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.2);
            }

            .profil-kantor-foto-field .profil-kantor-foto-overlay {
                opacity: 0;
                transition: opacity 0.2s ease;
            }

            .profil-kantor-foto-field .profil-kantor-foto-frame:hover .profil-kantor-foto-overlay {
                opacity: 1;
            }

            @media (max-width: 575.98px) {
                .profil-kantor-foto-field {
                    margin-left: auto;
                    margin-right: auto;
                }
            }
        </style>

        <div class="card mb-5">
            <form novalidate enctype="multipart/form-data" action="{{ route('profil-kantor.update') }}" method="POST"
                class="needs-validation">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <h6 class="mb-3 text-primary">Instansi</h6>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase" for="nama_instansi">Nama instansi /
                                dinas</label>
                            <input type="text" id="nama_instansi" name="nama_instansi"
                                class="form-control @error('nama_instansi') is-invalid @enderror"
                                value="{{ old('nama_instansi', $profilKantor->nama_instansi) }}" maxlength="255" />
                            @error('nama_instansi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @php
                        $urlKadisFull = $profilKantor->getFirstMediaUrl('foto_kepala_dinas');
                        $urlSekdisFull = $profilKantor->getFirstMediaUrl('foto_sekdis');
                    @endphp

                    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                        <div class="card-header border-0 py-3 d-flex align-items-center gap-2 bg-primary">
                            <span class="rounded-circle bg-white bg-opacity-25 d-inline-flex p-2">
                                <i data-acorn-icon="user" class="text-white" data-acorn-size="18"></i>
                            </span>
                            <div>
                                <div class="text-white text-small text-uppercase opacity-75 mb-0">Pejabat</div>
                                <div class="text-white fw-semibold">Kepala dinas</div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-sm-row gap-3 gap-lg-4 align-items-start">
                                <div class="flex-shrink-0 text-center text-sm-start mx-auto mx-sm-0">
                                    <p class="form-label text-small text-uppercase mb-2">Foto resmi</p>
                                    @error('foto_kepala_dinas')
                                        <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                                    @enderror
                                    <div class="profil-kantor-foto-field d-inline-block text-center"
                                        data-profil-foto
                                        data-initial-url="{{ $urlKadisFull }}">
                                        <input type="file" id="foto_kepala_dinas" name="foto_kepala_dinas"
                                            class="visually-hidden @error('foto_kepala_dinas') is-invalid @enderror"
                                            accept="image/jpeg,image/png,image/webp,image/gif" data-profil-foto-input
                                            tabindex="-1" />
                                        <label for="foto_kepala_dinas"
                                            class="d-block mb-0 cursor-pointer" data-profil-foto-dropzone>
                                            <div
                                                class="profil-kantor-foto-frame rounded-3 overflow-hidden shadow-sm border border-2 position-relative bg-light mx-auto mx-sm-0">
                                                <div
                                                    class="profil-kantor-foto-inner position-relative overflow-hidden">
                                                    <img src="" alt=""
                                                        class="position-absolute top-0 start-0 w-100 h-100 d-none"
                                                        data-profil-foto-img
                                                        loading="lazy" decoding="async">
                                                    <div
                                                        class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted p-2 text-center d-none"
                                                        data-profil-foto-empty>
                                                        <span
                                                            class="rounded-circle bg-white shadow-sm d-inline-flex p-2 mb-1">
                                                            <i data-acorn-icon="camera" data-acorn-size="20"></i>
                                                        </span>
                                                        <span class="text-extra-small fw-medium">Belum ada foto</span>
                                                        <span class="text-extra-small mt-1 opacity-75 lh-1 px-1">Klik /
                                                            seret</span>
                                                    </div>
                                                    <div class="position-absolute top-0 start-0 m-1 d-none"
                                                        data-profil-foto-badge>
                                                        <span class="badge bg-warning text-dark shadow-sm"
                                                            style="font-size: 0.65rem;">Baru</span>
                                                    </div>
                                                    <div
                                                        class="profil-kantor-foto-overlay position-absolute bottom-0 start-0 end-0 py-1 px-2 text-extra-small text-white text-center"
                                                        style="background: linear-gradient(transparent, rgba(0,0,0,.75));">
                                                        <i data-acorn-icon="upload" class="align-middle"
                                                            data-acorn-size="12"></i>
                                                        <span class="ms-1">Ubah foto</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        <div class="d-flex flex-column gap-2 mt-2">
                                            <label for="foto_kepala_dinas"
                                                class="btn btn-sm btn-primary btn-icon btn-icon-start mb-0">
                                                <i data-acorn-icon="folder"></i>
                                                <span data-profil-foto-btn-label>Pilih file</span>
                                            </label>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary d-none"
                                                data-profil-foto-clear>Batal</button>
                                        </div>
                                        <p class="text-extra-small text-muted mt-1 mb-0 text-truncate px-0"
                                            style="max-width: 140px;"                                             data-profil-foto-name title=""></p>
                                    </div>
                                </div>
                                <div class="flex-grow-1 min-w-0 w-100 pt-sm-1">
                                    <div class="mb-3">
                                        <label class="form-label text-small text-uppercase" for="kepala_dinas">Nama
                                            lengkap</label>
                                        <input type="text" id="kepala_dinas" name="kepala_dinas"
                                            class="form-control form-control-lg @error('kepala_dinas') is-invalid @enderror"
                                            value="{{ old('kepala_dinas', $profilKantor->kepala_dinas) }}"
                                            maxlength="255" placeholder="Nama jabatan resmi" />
                                        @error('kepala_dinas')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label text-small text-uppercase"
                                            for="nip_kepala_dinas">NIP</label>
                                        <input type="text" id="nip_kepala_dinas" name="nip_kepala_dinas"
                                            class="form-control @error('nip_kepala_dinas') is-invalid @enderror"
                                            value="{{ old('nip_kepala_dinas', $profilKantor->nip_kepala_dinas) }}"
                                            maxlength="30" placeholder="Nomor Induk Pegawai" />
                                        @error('nip_kepala_dinas')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                        <div class="card-header border-0 py-3 d-flex align-items-center gap-2 bg-light">
                            <span class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-2">
                                <i data-acorn-icon="diagram-1" class="text-primary" data-acorn-size="18"></i>
                            </span>
                            <div>
                                <div class="text-muted text-small text-uppercase mb-0">Pejabat</div>
                                <div class="fw-semibold">Sekretaris dinas (Sekdis)</div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex flex-column flex-sm-row gap-3 gap-lg-4 align-items-start">
                                <div class="flex-shrink-0 text-center text-sm-start mx-auto mx-sm-0">
                                    <p class="form-label text-small text-uppercase mb-2">Foto resmi</p>
                                    @error('foto_sekdis')
                                        <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                                    @enderror
                                    <div class="profil-kantor-foto-field d-inline-block text-center"
                                        data-profil-foto
                                        data-initial-url="{{ $urlSekdisFull }}">
                                        <input type="file" id="foto_sekdis" name="foto_sekdis"
                                            class="visually-hidden @error('foto_sekdis') is-invalid @enderror"
                                            accept="image/jpeg,image/png,image/webp,image/gif" data-profil-foto-input
                                            tabindex="-1" />
                                        <label for="foto_sekdis"
                                            class="d-block mb-0 cursor-pointer" data-profil-foto-dropzone>
                                            <div
                                                class="profil-kantor-foto-frame rounded-3 overflow-hidden shadow-sm border border-2 position-relative bg-light mx-auto mx-sm-0">
                                                <div
                                                    class="profil-kantor-foto-inner position-relative overflow-hidden">
                                                    <img src="" alt=""
                                                        class="position-absolute top-0 start-0 w-100 h-100 d-none"
                                                        data-profil-foto-img
                                                        loading="lazy" decoding="async">
                                                    <div
                                                        class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted p-2 text-center d-none"
                                                        data-profil-foto-empty>
                                                        <span
                                                            class="rounded-circle bg-white shadow-sm d-inline-flex p-2 mb-1">
                                                            <i data-acorn-icon="camera" data-acorn-size="20"></i>
                                                        </span>
                                                        <span class="text-extra-small fw-medium">Belum ada foto</span>
                                                        <span class="text-extra-small mt-1 opacity-75 lh-1 px-1">Klik /
                                                            seret</span>
                                                    </div>
                                                    <div class="position-absolute top-0 start-0 m-1 d-none"
                                                        data-profil-foto-badge>
                                                        <span class="badge bg-warning text-dark shadow-sm"
                                                            style="font-size: 0.65rem;">Baru</span>
                                                    </div>
                                                    <div
                                                        class="profil-kantor-foto-overlay position-absolute bottom-0 start-0 end-0 py-1 px-2 text-extra-small text-white text-center"
                                                        style="background: linear-gradient(transparent, rgba(0,0,0,.75));">
                                                        <i data-acorn-icon="upload" class="align-middle"
                                                            data-acorn-size="12"></i>
                                                        <span class="ms-1">Ubah foto</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        <div class="d-flex flex-column gap-2 mt-2">
                                            <label for="foto_sekdis"
                                                class="btn btn-sm btn-primary btn-icon btn-icon-start mb-0">
                                                <i data-acorn-icon="folder"></i>
                                                <span data-profil-foto-btn-label>Pilih file</span>
                                            </label>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary d-none"
                                                data-profil-foto-clear>Batal</button>
                                        </div>
                                        <p class="text-extra-small text-muted mt-1 mb-0 text-truncate px-0"
                                            style="max-width: 140px;"                                             data-profil-foto-name title=""></p>
                                    </div>
                                </div>
                                <div class="flex-grow-1 min-w-0 w-100 pt-sm-1">
                                    <div class="mb-3">
                                        <label class="form-label text-small text-uppercase" for="sekdis">Nama
                                            lengkap</label>
                                        <input type="text" id="sekdis" name="sekdis"
                                            class="form-control form-control-lg @error('sekdis') is-invalid @enderror"
                                            value="{{ old('sekdis', $profilKantor->sekdis) }}" maxlength="255"
                                            placeholder="Nama jabatan resmi" />
                                        @error('sekdis')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label text-small text-uppercase" for="nip_sekdis">NIP</label>
                                        <input type="text" id="nip_sekdis" name="nip_sekdis"
                                            class="form-control @error('nip_sekdis') is-invalid @enderror"
                                            value="{{ old('nip_sekdis', $profilKantor->nip_sekdis) }}" maxlength="30"
                                            placeholder="Nomor Induk Pegawai" />
                                        @error('nip_sekdis')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="mb-3 mt-4 text-primary">Kontak & lokasi</h6>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase" for="lokasi_kantor">Lokasi kantor</label>
                            <textarea id="lokasi_kantor" name="lokasi_kantor" rows="3" maxlength="2000"
                                class="form-control @error('lokasi_kantor') is-invalid @enderror">{{ old('lokasi_kantor', $profilKantor->lokasi_kantor) }}</textarea>
                            @error('lokasi_kantor')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase" for="no_telepon">Nomor telepon</label>
                            <input type="text" id="no_telepon" name="no_telepon"
                                class="form-control @error('no_telepon') is-invalid @enderror"
                                value="{{ old('no_telepon', $profilKantor->no_telepon) }}" maxlength="30" />
                            @error('no_telepon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-small text-uppercase" for="email">Email</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $profilKantor->email) }}" maxlength="255" />
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase" for="website">Website</label>
                            <input type="url" id="website" name="website"
                                class="form-control @error('website') is-invalid @enderror"
                                value="{{ old('website', $profilKantor->website) }}" maxlength="500"
                                placeholder="https://" />
                            @error('website')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        (function () {
            function initProfilFoto(root) {
                const initialUrl = (root.dataset.initialUrl || '').trim();
                const input = root.querySelector('[data-profil-foto-input]');
                const img = root.querySelector('[data-profil-foto-img]');
                const empty = root.querySelector('[data-profil-foto-empty]');
                const dropzone = root.querySelector('[data-profil-foto-dropzone]');
                const clearBtn = root.querySelector('[data-profil-foto-clear]');
                const nameEl = root.querySelector('[data-profil-foto-name]');
                const badge = root.querySelector('[data-profil-foto-badge]');
                const btnLabel = root.querySelector('[data-profil-foto-btn-label]');

                let objectUrl = null;

                function revoke() {
                    if (objectUrl) {
                        URL.revokeObjectURL(objectUrl);
                        objectUrl = null;
                    }
                }

                function showImage(src, isBlobPreview) {
                    empty.classList.add('d-none');
                    img.classList.remove('d-none');
                    img.src = src;
                    if (badge) {
                        badge.classList.toggle('d-none', !isBlobPreview);
                    }
                    if (btnLabel) {
                        if (isBlobPreview) {
                            btnLabel.textContent = 'Ganti file';
                        } else if (src) {
                            btnLabel.textContent = 'Ganti foto';
                        }
                    }
                }

                function showEmpty() {
                    img.classList.add('d-none');
                    img.removeAttribute('src');
                    empty.classList.remove('d-none');
                    if (badge) {
                        badge.classList.add('d-none');
                    }
                    if (btnLabel) {
                        btnLabel.textContent = 'Pilih file';
                    }
                }

                function applyInitial() {
                    revoke();
                    input.value = '';
                    nameEl.textContent = '';
                    nameEl.removeAttribute('title');
                    clearBtn.classList.add('d-none');
                    if (initialUrl !== '') {
                        showImage(initialUrl, false);
                    } else {
                        showEmpty();
                    }
                }

                function previewLocalFile(file) {
                    if (!file || !file.type.startsWith('image/')) {
                        return;
                    }
                    revoke();
                    objectUrl = URL.createObjectURL(file);
                    showImage(objectUrl, true);
                    nameEl.textContent = file.name;
                    nameEl.setAttribute('title', file.name);
                    clearBtn.classList.remove('d-none');
                }

                if (initialUrl !== '') {
                    showImage(initialUrl, false);
                } else {
                    showEmpty();
                }

                input.addEventListener('change', function () {
                    const file = input.files && input.files[0];
                    if (file) {
                        previewLocalFile(file);
                    } else {
                        applyInitial();
                    }
                });

                clearBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    applyInitial();
                });

                ['dragenter', 'dragover'].forEach(function (ev) {
                    dropzone.addEventListener(ev, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        root.classList.add('is-dragover');
                    });
                });

                dropzone.addEventListener('dragleave', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    root.classList.remove('is-dragover');
                });

                dropzone.addEventListener('drop', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    root.classList.remove('is-dragover');
                    const file = e.dataTransfer.files && e.dataTransfer.files[0];
                    if (file) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        previewLocalFile(file);
                    }
                });
            }

            document.querySelectorAll('[data-profil-foto]').forEach(initProfilFoto);
        })();
    </script>
@endsection
@include('components.form_validation')
