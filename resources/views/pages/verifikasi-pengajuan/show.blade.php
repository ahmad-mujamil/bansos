@extends('layouts.layout')
@section('title', 'Detail Verifikasi Pengajuan')
@section('content')
<div class="col">
    <div class="page-title-container mb-3">
        <div class="row">
            <div class="col mb-2">
                <h1 class="mb-2 pb-0 display-4">{{ ($laporanReadOnly ?? false) ? 'Detail Pengajuan' : 'Detail Verifikasi Pengajuan' }}</h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        @if($laporanReadOnly ?? false)
                            <li class="breadcrumb-item"><a href="javascript:;">Laporan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('laporan-pengajuan.index') }}">Pengajuan Kelompok</a></li>
                        @else
                            <li class="breadcrumb-item"><a href="javascript:;">Verifikasi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('verifikasi-pengajuan.index') }}">Pengajuan
                                    Bantuan</a></li>
                        @endif
                        <li class="breadcrumb-item"><a href="javascript:;">{{ $pengajuan->kode_pengajuan }}</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-12 col-md-5 d-flex align-items-start justify-content-end gap-2">
                <a href="{{ $laporanBackUrl ?? route('verifikasi-pengajuan.index') }}"
                    class="btn btn-outline-secondary btn-icon btn-icon-start w-100 w-md-auto">
                    <i data-acorn-icon="arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    @php
    $status = $pengajuan->status;
    $badge = $status?->badgeColor() ?? 'secondary';
    $catatan = $pengajuan->catatan_verifikator ?? $pengajuan->catatan;
    $laporanReadOnly = $laporanReadOnly ?? false;
    $canVerify = ! $laporanReadOnly && in_array($pengajuan->status, [\App\Enums\PengajuanStatus::DIAJUKAN], true);
    $isSubsidiBunga = $pengajuan->kategori_pengajuan === \App\Enums\JenisPengajuan::SUBSIDI_BUNGA;
    $namaIndividu = $pengajuan->details->first()?->penduduk?->nama;
    @endphp

    <div class="card mb-4">
        <div class="card-body">
            @php
                $kategori = $pengajuan->kategori_pengajuan;
                $jenisWarna = match ($kategori) {
                    \App\Enums\JenisPengajuan::SUBSIDI_BUNGA => 'bg-warning text-dark',
                    \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK => 'bg-primary',
                    \App\Enums\JenisPengajuan::HIBAH => 'bg-info text-dark',
                    \App\Enums\JenisPengajuan::BANSOS => 'bg-success',
                    default => 'bg-secondary',
                };
            @endphp
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                <h2 class="small-title mb-0">Informasi Pengajuan {{ $pengajuan->kode_pengajuan }}</h2>
                <span class="badge {{ $jenisWarna }} fs-6 px-3 py-2">
                    <i data-acorn-icon="tag" data-acorn-size="16" class="me-1 align-middle"></i>
                    {{ $kategori?->getDescription() ?? 'Jenis bantuan tidak diketahui' }}
                </span>
            </div>
            <div class="row">

                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">{{ $isSubsidiBunga ? 'Nama Usaha' : 'Judul' }}</span>
                    <div class="fw-semibold">
                        {{ $pengajuan->judul ?? '-' }}
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">{{ $isSubsidiBunga ? 'Nilai Usulan Kredit' : 'Nilai Usulan' }}</span>
                    <div class="fw-semibold">{{ number_format($pengajuan->nilai, 0, ',', '.') }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Status</span>
                    <div>
                        <span class="badge bg-{{ $badge }}">{{ $pengajuan->status->getDescription() }}</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Tanggal Dibuat</span>
                    <div>{{ $pengajuan->created_at?->translatedFormat('d F Y H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Pengaju</span>
                    <div>{{ $pengajuan->user?->nama ?? ($pengajuan->user ?? '-') }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Pemohon</span>
                    @if($pengajuan->organisasi_id)
                        <div class="fw-semibold"><span class="badge bg-primary me-1">Kelompok</span>{{ $pengajuan->organisasi?->nama ?? '—' }}</div>
                    @else
                        <div class="fw-semibold"><span class="badge bg-secondary me-1">Individu</span>{{ $namaIndividu ?? '—' }}</div>
                    @endif
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Lokasi Kegiatan</span>
                    <div class="fw-semibold">{{ $pengajuan->lokasi ?? '—' }}, {{ $pengajuan->desa?->nama ?? '—' }}, {{ $pengajuan->desa?->kecamatan?->nama ?? '—' }}</div>
                </div>
                @if ($pengajuan->verified_at)
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Diverifikasi Pada</span>
                    <div>{{ $pengajuan->verified_at->translatedFormat('d F Y H:i') }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">Diverifikasi Oleh</span>
                    <div>{{ $pengajuan->verifiedBy?->nama ?? ($pengajuan->verifiedBy?->email ?? '-') }}</div>
                </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="text-small text-uppercase text-muted">{{ $isSubsidiBunga ? 'NIB dan Dokumentasi Usaha' : 'Lampiran' }}</span>
                    <div>
                        @php
                        $lampiran = $pengajuan->getFirstMedia('pengajuan');
                        @endphp
                        @if ($lampiran)
                        <a class="btn btn-sm btn-outline-primary" href="{{ $lampiran->getUrl() }}" target="_blank"
                            rel="noopener noreferrer">
                            Lihat Dokumen
                        </a>
                        <div class="text-muted small mt-1">{{ $lampiran->file_name }}</div>
                        @else
                        <div class="text-muted">-</div>
                        @endif
                    </div>
                </div>
            </div>
            @if ($catatan)
            <div class="mt-3">
                <span class="text-small text-uppercase text-muted">Catatan</span>
                <div class="alert alert-light border mt-1 mb-0">{{ $catatan }}</div>
            </div>
            @endif
        </div>
    </div>

    @if ($pengajuan->pemeriksa->isNotEmpty())
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="small-title mb-4">Pemeriksa</h2>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuan->pemeriksa as $pemeriksa)
                        <tr>
                            <td class="fw-semibold">{{ $pemeriksa->nama }}</td>
                            <td>{{ $pemeriksa->nip }}</td>
                            <td>{{ $pemeriksa->jabatan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($canVerify)
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="small-title mb-4">Verifikasi</h2>
            <livewire:verifikasi-pengajuan-form :pengajuan="$pengajuan" />
        </div>
    </div>
    @else
    @php
        $verifikasi = $pengajuan->verifikasiPengajuan;
        $baMedia = $verifikasi?->getFirstMedia('ba-verifikasi');
        $verifikasiId = $verifikasi?->id;
        $bantuanUang = $verifikasiId ? ($bantuanUangByVerifikasi[$verifikasiId] ?? collect()) : collect();
        $bantuanBarangJasa = $verifikasiId ? ($bantuanBarangJasaByVerifikasi[$verifikasiId] ?? collect()) : collect();
    @endphp

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="small-title mb-4">Hasil Verifikasi</h2>
            @if($verifikasi)
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Lulus Kriteria</p>
                        <span class="badge bg-{{ $verifikasi->lulus_kriteria ? 'success' : 'danger' }}">{{ $verifikasi->lulus_kriteria ? 'Ya' : 'Tidak' }}</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Lulus Administrasi</p>
                        <span class="badge bg-{{ $verifikasi->lulus_administrasi ? 'success' : 'danger' }}">{{ $verifikasi->lulus_administrasi ? 'Ya' : 'Tidak' }}</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Lulus Kesesuaian</p>
                        <span class="badge bg-{{ $verifikasi->lulus_kesesuaian ? 'success' : 'danger' }}">{{ $verifikasi->lulus_kesesuaian ? 'Ya' : 'Tidak' }}</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Sesuai Program Pemda</p>
                        <span class="badge bg-{{ $verifikasi->sesuai_program_pemda ? 'success' : 'danger' }}">{{ $verifikasi->sesuai_program_pemda ? 'Ya' : 'Tidak' }}</span>
                    </div>
                    @if($verifikasi->nilai_rekomendasi !== null)
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">{{ $isSubsidiBunga ? 'Nilai Rekomendasi Kredit' : 'Nilai Rekomendasi' }}</p>
                        <p class="fw-semibold mb-0">Rp {{ number_format($verifikasi->nilai_rekomendasi, 0, ',', '.') }}</p>
                    </div>
                    @endif
                    @if($verifikasi->rupa_bantuan)
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Rupa Bantuan</p>
                        <p class="mb-0">{{ $verifikasi->rupa_bantuan->getDescription() }}</p>
                    </div>
                    @endif
                    @if($verifikasi->disahkan_oleh)
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Disahkan Oleh</p>
                        <p class="mb-0">{{ $verifikasi->disahkan_oleh }}</p>
                    </div>
                    @endif
                    @if($verifikasi->lokasi_pengesahan)
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Lokasi Pengesahan</p>
                        <p class="mb-0">{{ $verifikasi->lokasi_pengesahan }}</p>
                    </div>
                    @endif
                    @if($verifikasi->tgl_disahkan)
                    <div class="col-6 col-md-3">
                        <p class="text-small text-uppercase text-muted mb-1">Tanggal Pengesahan</p>
                        <p class="mb-0">{{ $verifikasi->tgl_disahkan->translatedFormat('d F Y') }}</p>
                    </div>
                    @endif
                    @if($verifikasi->catatan)
                    <div class="col-12">
                        <p class="text-small text-uppercase text-muted mb-1">Catatan</p>
                        <div class="alert alert-light border mb-0">{{ $verifikasi->catatan }}</div>
                    </div>
                    @endif
                    @if($baMedia)
                    <div class="col-12">
                        <p class="text-small text-uppercase text-muted mb-1">Dokumen BA Verifikasi</p>
                        <a href="{{ $baMedia->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-danger btn-icon btn-icon-start">
                            <i data-acorn-icon="file-text"></i>
                            <span>{{ $baMedia->file_name }}</span>
                        </a>
                    </div>
                    @elseif(!($laporanReadOnly ?? false))
                    <div class="col-12">
                        <p class="text-small text-uppercase text-muted mb-1">Dokumen BA Verifikasi</p>
                        <div class="btn-group">
                            <a href="{{ route('verifikasi-pengajuan.download-ba-verifikasi', $pengajuan->id) }}" target="_blank"
                                class="btn btn-sm btn-outline-success btn-icon btn-icon-start">
                                <i data-acorn-icon="file-text"></i>
                                <span>Download BA (PDF)</span>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Pilih format</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('verifikasi-pengajuan.download-ba-verifikasi', $pengajuan->id) }}" target="_blank">Download sebagai PDF</a></li>
                                <li><a class="dropdown-item" href="{{ route('verifikasi-pengajuan.download-ba-verifikasi', ['pengajuan' => $pengajuan->id, 'format' => 'word']) }}" target="_blank">Download sebagai Word</a></li>
                            </ul>
                        </div>
                    </div>
                    @else
                    <div class="col-12">
                        <p class="text-small text-uppercase text-muted mb-1">Dokumen BA Verifikasi</p>
                        <span class="text-muted">Belum diunggah.</span>
                    </div>
                    @endif
                </div>

                @if($bantuanUang->isNotEmpty())
                <hr>
                <h3 class="small-title mb-3 mt-4">Detail Bantuan Uang</h3>
                @php $totalUang = $bantuanUang->sum(fn($r) => (float) $r->nilai); @endphp
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Penduduk</th>
                                <th class="text-end" style="width: 200px;">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bantuanUang as $row)
                            <tr>
                                <td>{{ $row->penduduk?->nama ?? $row->penduduk_id }}</td>
                                <td class="text-end">Rp {{ number_format((float) $row->nilai, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-end">Total</th>
                                <th class="text-end fw-semibold">Rp {{ number_format($totalUang, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif

                @if($bantuanBarangJasa->isNotEmpty())
                <hr>
                <h3 class="small-title mb-3 mt-4">Detail Bantuan Barang / Jasa</h3>
                @php $totalBarangJasa = $bantuanBarangJasa->sum(fn($r) => (float) $r->harga_satuan * (int) $r->qty); @endphp
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th style="width: 120px;">Satuan</th>
                                <th class="text-end" style="width: 70px;">Qty</th>
                                <th class="text-end" style="width: 160px;">Harga Satuan</th>
                                <th class="text-end" style="width: 180px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bantuanBarangJasa as $row)
                            @php $subtotal = (float) $row->harga_satuan * (int) $row->qty; @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $row->nama_barang }}</div>
                                    <div class="text-muted small">{{ $row->spesifikasi }}</div>
                                </td>
                                <td>{{ $row->satuan }}</td>
                                <td class="text-end">{{ $row->qty }}</td>
                                <td class="text-end">Rp {{ number_format((float) $row->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th class="text-end fw-semibold">Rp {{ number_format($totalBarangJasa, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
            @else
                <p class="text-muted mb-0">
                    @if($laporanReadOnly && $pengajuan->status === \App\Enums\PengajuanStatus::DIAJUKAN)
                        Belum diverifikasi.
                    @else
                        Data verifikasi tidak ditemukan.
                    @endif
                </p>
            @endif
        </div>
    </div>
    @endif

    @if ($pengajuan->logs->isNotEmpty())
    <!-- <div class="card mb-4">
        <div class="card-body">
            <h2 class="small-title mb-4">Riwayat Aksi</h2>
            <div class="timeline">
                @foreach ($pengajuan->logs as $log)
                <div class="timeline-item">
                    <div class="timeline-content">
                        @php
                        $badgeLog = \App\Enums\PengajuanStatus::tryFrom($log->status_to)?->badgeColor() ?? 'secondary';
                        @endphp

                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <span class="badge bg-light border text-dark">
                                        <i data-acorn-icon="check-circle" class="me-1"></i>
                                        {{ $log->action }}
                                    </span>
                                    @if ($log->status_to)
                                    <span class="badge bg-{{ $badgeLog }}">
                                        {{ strtoupper($log->status_to) }}
                                    </span>
                                    @endif
                                </div>
                                <div class="text-muted small mt-1">
                                    {{ $log->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                                    @if($log->user)
                                    <span class="mx-1">•</span>
                                    {{ $log->user?->nama ?? $log->user?->email }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($log->catatan)
                        <div class="mt-2">
                            <div class="small-title text-muted mb-1">Catatan</div>
                            <div class="alert alert-light border mb-0">
                                {{ $log->catatan }}
                            </div>
                        </div>
                        @endif

                        @php($verifikasiId = $log->metadata['verifikasi_pengajuan_id'] ?? null)
                        @if ($verifikasiId)
                        @php($bantuanUang = $bantuanUangByVerifikasi[$verifikasiId] ?? collect())
                        @php($bantuanBarangJasa = $bantuanBarangJasaByVerifikasi[$verifikasiId] ?? collect())

                        @if ($bantuanUang->isNotEmpty())
                        <div class="mt-3">
                            @php($totalBantuanUang = $bantuanUang->sum(fn($row) => (float) $row->nilai))
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small-title text-muted mb-0">Bantuan Uang</div>
                                <div class="text-muted small">Total: <span class="fw-semibold text-dark">{{ number_format((float) $totalBantuanUang, 2, ',', '.') }}</span></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Penduduk</th>
                                            <th class="text-end" style="width: 180px;">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bantuanUang as $row)
                                        <tr>
                                            <td>{{ $row->penduduk?->nama ?? $row->penduduk_id }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format((float) $row->nilai, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-end">Total</th>
                                            <th class="text-end fw-semibold">
                                                {{ number_format((float) $totalBantuanUang, 2, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @endif

                        @if ($bantuanBarangJasa->isNotEmpty())
                        <div class="mt-3">
                            @php($totalBarangJasa = $bantuanBarangJasa->sum(fn($row) => (float) $row->harga_satuan * (int) $row->qty))
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small-title text-muted mb-0">Bantuan Barang / Jasa</div>
                                <div class="text-muted small">Total: <span class="fw-semibold text-dark">{{ number_format((float) $totalBarangJasa, 2, ',', '.') }}</span></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th style="width: 120px;">Satuan</th>
                                            <th class="text-end" style="width: 90px;">Qty</th>
                                            <th class="text-end" style="width: 160px;">Harga Satuan</th>
                                            <th class="text-end" style="width: 180px;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bantuanBarangJasa as $row)
                                        @php($subtotal = (float) $row->harga_satuan * (int) $row->qty)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $row->nama_barang }}
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $row->spesifikasi }}
                                                </div>
                                            </td>
                                            <td>{{ $row->satuan }}</td>
                                            <td class="text-end">{{ $row->qty }}</td>
                                            <td class="text-end">
                                                {{ number_format((float) $row->harga_satuan, 2, ',', '.') }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format((float) $subtotal, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">Total</th>
                                            <th class="text-end fw-semibold">
                                                {{ number_format((float) $totalBarangJasa, 2, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div> -->
    @endif
</div>
@endsection

@push('js_vendor')
<script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@endpush

@include('components.number-format')