@extends('layouts.layout')
@section('title', 'BA Serah Terima')
@section('content')
    <!-- Page Content Start -->
    <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container mb-3">
            <div class="row">
                <!-- Title Start -->
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4" id="title">BA Serah Terima</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">BA Serah Terima</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('bast.index') }}">BAST</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">{{ request()->routeIs('bast.create') ? 'Tambah Data' : 'Edit Data' }}</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <a href="{{ route('bast.index') }}" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                        <i data-acorn-icon="arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-5">
            @php
                $route  = request()->routeIs('bast.create') ? route('bast.store') : route('bast.update', $bast->id ?? '');
                $method = request()->routeIs('bast.create') ? 'POST' : 'PUT';
            @endphp
            <form novalidate enctype="multipart/form-data" action="{{ $route }}" method="POST" class="needs-validation">
                @csrf
                @method($method)
                <div class="card-body">
                    {{-- Langkah 1: pilih pengajuan lebih dulu --}}
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label text-small text-uppercase">Pengajuan <span class="text-danger">*</span></label>
                            <select name="pengajuan_id" id="pengajuan_id"
                                    class="form-control @error('pengajuan_id') is-invalid @enderror" required>
                                <option value="">Pilih Pengajuan</option>
                                @foreach($pengajuan as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('pengajuan_id', $bast->pengajuan_id ?? '') === $p->id ? 'selected' : '' }}>
                                        {{ $p->kode_pengajuan." - ".$p->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pengajuan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih pengajuan yang akan dibuatkan BAST terlebih dahulu.</small>
                        </div>
                    </div>

                    {{-- Resume singkat pengajuan terpilih --}}
                    <div class="row" id="resume-row" style="display:none;">
                        <div class="col-12 mb-3">
                            <div class="border rounded p-3 bg-light" id="resume-pengajuan"></div>
                        </div>
                    </div>

                    {{-- Nilai rekomendasi (khusus subsidi bunga, diisi saat BAST) --}}
                    <div class="row" id="nilai-rekomendasi-row" style="display:none;">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">Nilai Usulan Rekomendasi (Kredit) <span class="text-danger">*</span></label>
                            <input type="text" inputmode="numeric" autocomplete="off"
                                   class="form-control @error('nilai_rekomendasi') is-invalid @enderror"
                                   id="nilai_rekomendasi" name="nilai_rekomendasi"
                                   value="{{ old('nilai_rekomendasi', isset($bast) && $bast->pengajuan?->verifikasiPengajuan?->nilai_rekomendasi !== null ? number_format((float) $bast->pengajuan->verifikasiPengajuan->nilai_rekomendasi, 0, ',', '.') : '') }}"
                                   placeholder="0" />
                            @error('nilai_rekomendasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Diisi khusus untuk pengajuan subsidi bunga.</small>
                        </div>
                    </div>

                    {{-- Langkah 2: detail BAST (muncul setelah pengajuan dipilih) --}}
                    <div id="bast-detail-fields" style="display:none;">
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label class="form-label text-small text-uppercase">Nomor BAST</label>
                                <input type="text" class="form-control @error('nomor') is-invalid @enderror" name="nomor"
                                       value="{{ old('nomor', $bast->nomor ?? '') }}" required />
                                @error('nomor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label class="form-label text-small text-uppercase">Tanggal</label>
                                <input type="text" id="tanggal" autocomplete="off"
                                       class="form-control @error('tanggal') is-invalid @enderror" name="tanggal"
                                       value="{{ old('tanggal', isset($bast) ? $bast->tanggal?->format('d-m-Y') : '') }}"
                                       placeholder="dd-mm-yyyy" required />
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label text-small text-uppercase">Penerima</label>
                                <input type="text" class="form-control @error('penerima') is-invalid @enderror" name="penerima"
                                       value="{{ old('penerima', $bast->penerima ?? '') }}" required />
                                @error('penerima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">
                                Dokumen PDF
                                @if(!request()->routeIs('bast.create'))
                                    <span class="text-muted">(kosongkan jika tidak ingin mengubah)</span>
                                @endif
                            </label>
                            <input type="file" class="form-control @error('dokumen') is-invalid @enderror" name="dokumen"
                                   accept=".pdf" {{ request()->routeIs('bast.create') ? 'required' : '' }} />
                            @error('dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(isset($bast) && $bast->getFirstMedia('dokumen'))
                                <div class="mt-2">
                                    <a href="{{ $bast->getFirstMediaUrl('dokumen') }}" target="_blank" class="text-primary text-small">
                                        <i data-acorn-icon="file-text"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                            <label class="form-label text-small text-uppercase">
                                Foto
                                @if(!request()->routeIs('bast.create'))
                                    <span class="text-muted">(upload baru akan menggantikan foto lama)</span>
                                @endif
                            </label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto[]"
                                   accept="image/*" multiple {{ request()->routeIs('bast.create') ? 'required' : '' }} />
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('foto.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @if(isset($bast) && $bast->getMedia('foto')->count())
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    @foreach($bast->getMedia('foto') as $foto)
                                        <img src="{{ $foto->getUrl() }}" alt="Foto" class="rounded" style="height: 80px; width: 80px; object-fit: cover;" />
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                        <button type="submit" class="btn btn-primary mt-3">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Page Content End -->
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-datepicker3.standalone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}">
@endpush
@push('js_vendor')
    <script src="{{ asset('js/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    @php
        $pengajuanResume = $pengajuan->mapWithKeys(function ($p) {
            $isSubsidi = $p->kategori_pengajuan === \App\Enums\JenisPengajuan::SUBSIDI_BUNGA;
            $pemohon = $p->organisasi_id ? $p->organisasi?->nama : $p->details->first()?->penduduk?->nama;
            $rek = $p->verifikasiPengajuan?->nilai_rekomendasi;

            return [$p->id => [
                'is_subsidi'        => $isSubsidi,
                'jenis'             => $p->kategori_pengajuan?->getDescription() ?? '-',
                'tipe_pemohon'      => $p->organisasi_id ? 'Kelompok' : 'Individu',
                'pemohon'           => $pemohon ?: '-',
                'judul_label'       => $isSubsidi ? 'Nama Usaha' : 'Judul',
                'judul'             => $p->judul ?: '-',
                'nilai_label'       => $isSubsidi ? 'Nilai Usulan Kredit' : 'Nilai Usulan',
                'nilai'             => $p->nilai !== null ? 'Rp '.number_format((float) $p->nilai, 0, ',', '.') : '-',
                'nilai_rekomendasi' => $rek !== null ? 'Rp '.number_format((float) $rek, 0, ',', '.') : null,
                'jenis_bantuan'     => $p->jenisBantuan?->nama,
                'wilayah'           => trim(($p->desa?->nama ?? '').($p->desa?->kecamatan?->nama ? ', '.$p->desa->kecamatan->nama : '')) ?: '-',
            ]];
        });
    @endphp
    <script>
        var pengajuanResume = @json($pengajuanResume);

        function renderResumePengajuan(id) {
            var d = pengajuanResume[id];
            if (!d) {
                $('#resume-row').hide();
                $('#nilai-rekomendasi-row').hide();
                $('#bast-detail-fields').hide();
                $('#nilai_rekomendasi').prop('required', false);
                return;
            }

            // Pengajuan terpilih → tampilkan field detail BAST.
            $('#bast-detail-fields').show();

            var lbl = function (t) { return '<span class="text-muted text-uppercase" style="font-size:.7rem">' + t + '</span>'; };
            var cell = function (t, v) { return '<div class="col-md-6 mb-1">' + lbl(t) + '<div class="fw-semibold">' + v + '</div></div>'; };

            var html = ''
                + '<div class="d-flex flex-wrap align-items-center gap-2 mb-2">'
                +   '<span class="badge bg-info text-dark">' + d.jenis + '</span>'
                +   '<span class="badge bg-secondary">' + d.tipe_pemohon + '</span>'
                + '</div>'
                + '<div class="row g-2 small">'
                +   cell('Pemohon', d.pemohon)
                +   cell(d.judul_label, d.judul)
                +   cell(d.nilai_label, d.nilai)
                +   (d.nilai_rekomendasi ? cell('Nilai Rekomendasi', d.nilai_rekomendasi) : '')
                +   cell('Wilayah', d.wilayah)
                +   (d.jenis_bantuan ? cell('Jenis Bantuan', d.jenis_bantuan) : '')
                + '</div>';

            $('#resume-pengajuan').html(html);
            $('#resume-row').show();

            if (d.is_subsidi) {
                $('#nilai-rekomendasi-row').show();
                $('#nilai_rekomendasi').prop('required', true);
            } else {
                $('#nilai-rekomendasi-row').hide();
                $('#nilai_rekomendasi').prop('required', false);
            }
        }

        $("document").ready(function () {
            $('#tanggal').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                orientation: 'bottom',
            });
            $('#pengajuan_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Pengajuan',
                allowClear: true,
            });

            // Format ribuan pada input nilai rekomendasi.
            $('#nilai_rekomendasi').on('input', function () {
                var v = this.value.replace(/[^\d]/g, '');
                this.value = v ? Number(v).toLocaleString('id-ID') : '';
            });

            $('#pengajuan_id').on('change', function () {
                renderResumePengajuan($(this).val());
            });

            // Tampilkan resume awal (mis. saat edit / old input).
            renderResumePengajuan($('#pengajuan_id').val());
        });
    </script>
@endpush
@include('components.form_validation')
