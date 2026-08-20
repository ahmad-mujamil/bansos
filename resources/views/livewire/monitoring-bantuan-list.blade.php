<div>
    @include('livewire.partials.acorn-icon-observer')

    <style>
        .monitoring-row { cursor: pointer; }
        .monitoring-row .chevron { transition: transform .2s ease; }
        .monitoring-row[aria-expanded="true"] .chevron { transform: rotate(90deg); }
        .monitoring-row:hover { background-color: rgba(0, 0, 0, .02); }

        .laporan-tabs { padding: 0.25rem 0; }
        .laporan-tab {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 0.9rem;
            border-radius: 0.85rem;
            border: 1.5px solid #e9ecef;
            background: #ffffff;
            color: #6c757d;
            cursor: pointer;
            flex: 1 1 0;
            min-width: 0;
            text-align: left;
            transition: transform 0.18s ease, box-shadow 0.18s ease,
                        background 0.25s ease, color 0.25s ease, border-color 0.25s ease;
        }
        .laporan-tab:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(15, 23, 42, 0.08); }
        .laporan-tab:focus { outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18); }
        .laporan-tab-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 38px; height: 38px;
            border-radius: 0.6rem;
            background: #f1f3f5;
            color: #495057;
            flex-shrink: 0;
            transition: background 0.25s ease, color 0.25s ease;
        }
        .laporan-tab-icon i { width: 18px; height: 18px; }
        .laporan-tab-label { display: flex; flex-direction: column; line-height: 1.2; min-width: 0; }
        .laporan-tab-title { font-weight: 700; font-size: 0.9rem; letter-spacing: 0.01em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .laporan-tab-sub { font-size: 0.68rem; opacity: 0.7; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .laporan-tab-all:hover { border-color: #64748b; color: #334155; }
        .laporan-tab-all:hover .laporan-tab-icon { background: #e2e8f0; color: #334155; }
        .laporan-tab-all.active {
            color: #ffffff; border-color: transparent;
            background: linear-gradient(135deg, #64748b 0%, #334155 100%);
            box-shadow: 0 10px 22px rgba(51, 65, 85, 0.28);
        }
        .laporan-tab-bansos:hover { border-color: #3b82f6; color: #1d4ed8; }
        .laporan-tab-bansos:hover .laporan-tab-icon { background: #dbeafe; color: #1d4ed8; }
        .laporan-tab-bansos.active {
            color: #ffffff; border-color: transparent;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.28);
        }
        .laporan-tab-hibah:hover { border-color: #a855f7; color: #7c3aed; }
        .laporan-tab-hibah:hover .laporan-tab-icon { background: #f3e8ff; color: #7c3aed; }
        .laporan-tab-hibah.active {
            color: #ffffff; border-color: transparent;
            background: linear-gradient(135deg, #c084fc 0%, #7c3aed 100%);
            box-shadow: 0 10px 22px rgba(124, 58, 237, 0.28);
        }
        .laporan-tab-kelompok:hover { border-color: #10b981; color: #047857; }
        .laporan-tab-kelompok:hover .laporan-tab-icon { background: #d1fae5; color: #047857; }
        .laporan-tab-kelompok.active {
            color: #ffffff; border-color: transparent;
            background: linear-gradient(135deg, #34d399 0%, #059669 100%);
            box-shadow: 0 10px 22px rgba(5, 150, 105, 0.28);
        }
        .laporan-tab-subsidi:hover { border-color: #f59e0b; color: #b45309; }
        .laporan-tab-subsidi:hover .laporan-tab-icon { background: #fef3c7; color: #b45309; }
        .laporan-tab-subsidi.active {
            color: #ffffff; border-color: transparent;
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            box-shadow: 0 10px 22px rgba(217, 119, 6, 0.28);
        }
        .laporan-tab.active .laporan-tab-icon { background: rgba(255, 255, 255, 0.22); color: #ffffff; }
        .laporan-tab.active .laporan-tab-sub { opacity: 0.85; }
        @media (max-width: 575.98px) { .laporan-tab { min-width: 0; flex: 1 1 100%; } }
    </style>

    {{-- Tab jenis bantuan (selaras dengan laporan pengajuan) --}}
    <div class="laporan-tabs d-flex flex-wrap gap-2 mb-3" role="tablist">
        @php
            $tabMeta = [
                'all'                                               => ['class' => 'laporan-tab-all',      'icon' => 'layout-3', 'title' => 'Semua',            'sub' => 'Semua Jenis Bantuan'],
                \App\Enums\JenisPengajuan::BANSOS->value             => ['class' => 'laporan-tab-bansos',   'icon' => 'user',     'title' => 'Bansos',           'sub' => 'Bantuan Sosial'],
                \App\Enums\JenisPengajuan::HIBAH->value              => ['class' => 'laporan-tab-hibah',    'icon' => 'gift',     'title' => 'Hibah',            'sub' => 'Bantuan Hibah'],
                \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK->value   => ['class' => 'laporan-tab-kelompok', 'icon' => 'building', 'title' => 'Bantuan Kelompok', 'sub' => 'Barang Diserahkan ke Masyarakat'],
                \App\Enums\JenisPengajuan::SUBSIDI_BUNGA->value      => ['class' => 'laporan-tab-subsidi',  'icon' => 'dollar',   'title' => 'Subsidi Bunga',    'sub' => 'Subsidi Bunga Kredit'],
            ];
        @endphp
        @foreach ($tabMeta as $value => $meta)
            <button type="button"
                wire:click="setKategori('{{ $value }}')"
                class="laporan-tab {{ $meta['class'] }} {{ $kategori === $value ? 'active' : '' }}"
                role="tab"
                aria-selected="{{ $kategori === $value ? 'true' : 'false' }}">
                <span class="laporan-tab-icon"><i data-acorn-icon="{{ $meta['icon'] }}"></i></span>
                <span class="laporan-tab-label">
                    <span class="laporan-tab-title">{{ $meta['title'] }}</span>
                    <span class="laporan-tab-sub">{{ $meta['sub'] }}</span>
                </span>
            </button>
        @endforeach
    </div>

    {{-- Filter selalu tampil di atas (berlaku untuk kedua tab) --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-lg-3">
                    <label for="tahap-monitoring" class="form-label text-small text-uppercase text-muted">Tahap</label>
                    <div wire:ignore>
                        <select id="tahap-monitoring" class="form-select" data-placeholder="Pilih tahap">
                            <option value="semua">Semua</option>
                            <option value="belum_bast">Belum BAST</option>
                            <option value="sudah_bast">Sudah BAST</option>
                        </select>
                    </div>
                </div>
                @if ($showOpdFilter)
                    <div class="col-12 col-lg-3">
                        <label for="opd-monitoring" class="form-label text-small text-uppercase text-muted">OPD</label>
                        <div wire:ignore>
                            <select id="opd-monitoring" class="form-select" data-placeholder="Semua OPD">
                                <option value="all" @selected($opdId === '')>Semua OPD</option>
                                @foreach ($opdOptions as $opd)
                                    <option value="{{ $opd->id }}" @selected($opdId === $opd->id)>{{ $opd->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col-12 col-lg-2">
                    <label for="tahun-monitoring" class="form-label text-small text-uppercase text-muted">Tahun</label>
                    <div class="input-group">
                        <input
                            id="tahun-monitoring"
                            type="text"
                            class="form-control"
                            wire:model.live.debounce.500ms="tahun"
                            inputmode="numeric"
                            maxlength="4"
                            placeholder="Semua"
                        >
                        <button
                            class="btn btn-outline-secondary"
                            type="button"
                            wire:click="setSemuaTahun"
                            @disabled($tahun === '')
                        >
                            Semua
                        </button>
                    </div>
                    <div class="text-small text-muted mt-1">Isi 4 digit (contoh: 2026)</div>
                </div>
                <div class="col-12 {{ $showOpdFilter ? 'col-lg-4' : 'col-lg-7' }}">
                    <label for="search-monitoring" class="form-label text-small text-uppercase text-muted">Cari pengajuan</label>
                    <input
                        id="search-monitoring"
                        type="text"
                        class="form-control"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Kode, judul, pemohon, atau jenis bantuan"
                    >
                </div>
            </div>

            <div class="mt-2 text-muted text-small" wire:loading wire:target="tahap,kategori,setKategori,search,opdId,tahun,setSemuaTahun">
                Memuat data...
            </div>
        </div>
    </div>

    {{-- Tab navigasi --}}
    <ul class="nav nav-tabs mb-3" id="monitoring-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data-pane"
                type="button" role="tab" aria-controls="data-pane" aria-selected="true">
                Data Bantuan
                <span class="badge bg-secondary ms-1">{{ number_format($pengajuanList->total()) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ringkasan-tab" data-bs-toggle="tab" data-bs-target="#ringkasan-pane"
                type="button" role="tab" aria-controls="ringkasan-pane" aria-selected="false">
                Ringkasan &amp; Grafik
            </button>
        </li>
    </ul>

    {{-- Pembawa data chart, ikut ter-update tiap morph Livewire --}}
    <div id="monitoring-chart-data"
        data-summary='@json($chartSummary)'
        data-trend='@json($chartTrend)'
        hidden></div>

    <div class="tab-content">
        {{-- ============ TAB DATA BANTUAN ============ --}}
        <div class="tab-pane fade show active" id="data-pane" role="tabpanel" aria-labelledby="data-tab">
            @if ($pengajuanList->isEmpty())
                <div class="alert alert-light border" role="alert">
                    Tidak ada data monitoring bantuan untuk filter yang dipilih.
                </div>
            @else
                <div class="list-group shadow-sm">
                    @foreach ($pengajuanList as $pengajuan)
                        @php
                            $nilaiPengajuan = $pengajuan->nilai !== null ? 'Rp '.number_format((float) $pengajuan->nilai, 0, ',', '.') : '-';
                            $nilaiRekomendasiRaw = $pengajuan->verifikasiPengajuan?->nilai_rekomendasi;
                            $nilaiRekomendasi = $nilaiRekomendasiRaw !== null ? 'Rp '.number_format((float) $nilaiRekomendasiRaw, 0, ',', '.') : '-';
                            $dokumenPengajuanUrl = $pengajuan->getFirstMediaUrl('pengajuan');
                            $dokumenBaVerifikasiUrl = $pengajuan->verifikasiPengajuan?->getFirstMediaUrl('ba-verifikasi');
                            $dokumenBastUrl = $pengajuan->bast?->getFirstMediaUrl('dokumen');
                            $isAdmin = (bool) (auth()->user()?->is_admin() || auth()->user()?->is_super());
                            $sudahDiverifikasi = in_array($pengajuan->status, [
                                \App\Enums\PengajuanStatus::DISETUJUI,
                                \App\Enums\PengajuanStatus::DITOLAK,
                            ], true);
                            $bisaBatalVerifikasi = $sudahDiverifikasi && ($isAdmin || ! $dokumenBaVerifikasiUrl);
                            $detailId = 'monitoring-detail-'.$pengajuan->id;

                            // Info penerima — utamakan snapshot beku (saat disetujui, lalu diajukan),
                            // fallback ke data live bila pengajuan lama belum punya snapshot.
                            $momenDisetujui = \App\Enums\MomenSnapshot::DISETUJUI;
                            $momenDiajukan = \App\Enums\MomenSnapshot::DIAJUKAN;

                            $snapKelompok = $pengajuan->kelompokSnapshots->firstWhere('momen', $momenDisetujui)
                                ?? $pengajuan->kelompokSnapshots->firstWhere('momen', $momenDiajukan);

                            $penerimaSnap = $pengajuan->penerimaSnapshots->where('momen', $momenDisetujui);
                            if ($penerimaSnap->isEmpty()) {
                                $penerimaSnap = $pengajuan->penerimaSnapshots->where('momen', $momenDiajukan);
                            }

                            $isKelompok = $pengajuan->organisasi_id !== null;
                            if ($isKelompok) {
                                $namaPenerima = $snapKelompok?->nama_kelompok ?? $pengajuan->organisasi?->nama ?? '-';
                                $jumlahPenerima = $snapKelompok?->jumlah_anggota;
                                $nikPenerima = null;
                            } else {
                                $penerimaUtama = $penerimaSnap->first() ?? $pengajuan->details->first()?->penduduk;
                                $namaPenerima = $penerimaUtama?->nama ?? '-';
                                $nikPenerima = $penerimaUtama?->nik;
                                $jumlahPenerima = $penerimaSnap->isNotEmpty()
                                    ? $penerimaSnap->count()
                                    : $pengajuan->details->count();
                            }
                        @endphp
                        <div class="list-group-item p-0" wire:key="monitoring-{{ $pengajuan->id }}">
                            {{-- Baris ringkasan --}}
                            <div class="d-flex align-items-center gap-3 p-3">
                                {{-- Area klik untuk expand --}}
                                <div class="monitoring-row d-flex align-items-center gap-3 flex-grow-1" style="min-width: 0;"
                                    role="button" data-bs-toggle="collapse" data-bs-target="#{{ $detailId }}"
                                    aria-expanded="false" aria-controls="{{ $detailId }}">
                                    <span class="chevron d-inline-flex flex-shrink-0 text-muted">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M6 4l4 4-4 4V4z"/></svg>
                                    </span>
                                    <div style="min-width: 0;">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="text-small text-muted text-uppercase">{{ $pengajuan->kode_pengajuan }}</span>
                                            <span class="badge bg-{{ $pengajuan->status?->badgeColor() ?? 'secondary' }}">
                                                {{ $pengajuan->status?->getDescription() ?? '-' }}
                                            </span>
                                            @if ($pengajuan->bast)
                                                <span class="badge bg-success">Sudah BAST</span>
                                            @else
                                                <span class="badge bg-info text-white">Siap BAST</span>
                                            @endif
                                        </div>
                                        <div class="fw-semibold text-truncate" title="{{ $pengajuan->judul }}">{{ $pengajuan->judul ?? '-' }}</div>
                                        <div class="text-small text-truncate" title="Penerima: {{ $namaPenerima }}">
                                            <span class="badge bg-{{ $isKelompok ? 'primary' : 'secondary' }} me-1">
                                                {{ $isKelompok ? 'Kelompok' : 'Individu' }}
                                            </span>
                                            <span class="fw-semibold text-body">{{ $namaPenerima }}</span>
                                            @if ($isKelompok && $jumlahPenerima)
                                                <span class="text-muted">· {{ $jumlahPenerima }} anggota</span>
                                            @elseif (! $isKelompok && $nikPenerima)
                                                <span class="text-muted">· NIK {{ $nikPenerima }}</span>
                                            @endif
                                        </div>
                                        <div class="text-small text-muted text-truncate">
                                            {{ $pengajuan->jenisBantuan?->nama ?? '-' }} ·
                                            {{ $pengajuan->user?->nama ?? $pengajuan->user?->email ?? '-' }} ·
                                            @if ($pengajuan->opd)
                                                <span title="{{ $pengajuan->opd->nama }}">{{ $pengajuan->opd->singkatan }}</span> ·
                                            @endif
                                            {{ $pengajuan->created_at?->translatedFormat('d M Y') ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol aksi di kanan --}}
                                <div class="monitoring-actions d-flex flex-wrap gap-1 justify-content-end flex-shrink-0">
                                    @if (auth()->user()?->is_opd())
                                        <a href="{{ route('verifikasi-pengajuan.show', $pengajuan) }}" class="btn btn-sm btn-outline-primary">Detail Verifikasi</a>
                                    @endif
                                    @if ($pengajuan->bast)
                                        <a href="{{ route('bast.show', $pengajuan->bast) }}" class="btn btn-sm btn-outline-success">Lihat BAST</a>
                                    @endif
                                    @if ($pengajuan->bast && $isBendahara)
                                        @if ($pengajuan->sp2d)
                                            <button type="button" class="btn btn-sm btn-success" wire:click="viewSp2d('{{ $pengajuan->id }}')" title="Lihat detail SP2D">
                                                Sudah SP2D
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-primary" wire:click="openSp2d('{{ $pengajuan->id }}')">
                                                Input SP2D
                                            </button>
                                        @endif
                                    @endif
                                    @if ($bisaBatalVerifikasi)
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-batal-verifikasi" data-url="{{ route('verifikasi-pengajuan.batal-verifikasi', $pengajuan) }}">
                                            Batal Verifikasi
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Detail (expand) --}}
                            <div class="collapse" id="{{ $detailId }}">
                                <div class="border-top bg-light p-3">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Pemohon</div>
                                            <div class="fw-semibold">{{ $pengajuan->user?->nama ?? $pengajuan->user?->email ?? '-' }}</div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Penerima</div>
                                            <div class="fw-semibold">{{ $namaPenerima }}</div>
                                            <div class="text-small text-muted">
                                                {{ $isKelompok ? 'Kelompok' : 'Individu' }}
                                                @if ($isKelompok && $jumlahPenerima)
                                                    · {{ $jumlahPenerima }} anggota
                                                @elseif (! $isKelompok && $nikPenerima)
                                                    · NIK {{ $nikPenerima }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Dinas / OPD</div>
                                            <div class="fw-semibold">{{ $pengajuan->opd?->nama ?? '-' }}</div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Nilai Pengajuan</div>
                                            <div class="fw-semibold">{{ $nilaiPengajuan }}</div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Nilai Rekomendasi</div>
                                            <div class="fw-semibold">{{ $nilaiRekomendasi }}</div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">Status BAST</div>
                                            @if ($pengajuan->bast)
                                                <div class="fw-semibold">No. {{ $pengajuan->bast->nomor }}</div>
                                                <div class="text-small text-muted">{{ $pengajuan->bast->tanggal?->translatedFormat('d M Y') ?? '-' }}</div>
                                            @else
                                                <span class="badge bg-info text-white">Belum ada BAST</span>
                                            @endif
                                        </div>
                                        @if ($pengajuan->sp2d)
                                        <div class="col-12 col-md-3">
                                            <div class="text-small text-uppercase text-muted mb-1">SP2D <span class="badge bg-success ms-1">Diperiksa Bendahara</span></div>
                                            <div class="fw-semibold">No. {{ $pengajuan->sp2d->nomor }}</div>
                                            <div class="text-small text-muted">
                                                {{ $pengajuan->sp2d->tanggal?->translatedFormat('d M Y') ?? '-' }}
                                                @if ($pengajuan->sp2d->nilai !== null) · Rp {{ number_format((float) $pengajuan->sp2d->nilai, 0, ',', '.') }} @endif
                                            </div>
                                            <div class="text-small text-muted">Oleh: {{ $pengajuan->sp2d->user?->nama ?? 'bendahara' }}</div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <div class="text-small text-uppercase text-muted mb-2">Dokumen Terkait</div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if ($dokumenPengajuanUrl)
                                                <a href="{{ $dokumenPengajuanUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">Dokumen Pengajuan</a>
                                            @endif
                                            @if ($dokumenBaVerifikasiUrl)
                                                <a href="{{ $dokumenBaVerifikasiUrl }}" target="_blank" class="btn btn-sm btn-outline-info">BA Verifikasi</a>
                                            @elseif (auth()->user()?->is_opd())
                                                <div class="btn-group">
                                                    <a href="{{ route('verifikasi-pengajuan.download-ba-verifikasi', $pengajuan) }}" target="_blank" class="btn btn-sm btn-outline-info">Download BA Verifikasi (PDF)</a>
                                                    <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span class="visually-hidden">Pilih format</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('verifikasi-pengajuan.download-ba-verifikasi', $pengajuan) }}" target="_blank">Download sebagai PDF</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('verifikasi-pengajuan.download-ba-verifikasi', ['pengajuan' => $pengajuan, 'format' => 'word']) }}" target="_blank">Download sebagai Word</a></li>
                                                    </ul>
                                                </div>
                                            @endif
                                            @if ($dokumenBastUrl)
                                                <a href="{{ $dokumenBastUrl }}" target="_blank" class="btn btn-sm btn-outline-success">Dokumen BAST</a>
                                            @endif
                                            @php $dokumenSp2dUrl = $pengajuan->sp2d?->getFirstMediaUrl('dokumen'); @endphp
                                            @if ($dokumenSp2dUrl)
                                                <a href="{{ $dokumenSp2dUrl }}" target="_blank" class="btn btn-sm btn-outline-warning">Dokumen SP2D</a>
                                            @endif
                                            @if (! $dokumenPengajuanUrl && ! $dokumenBaVerifikasiUrl && ! $dokumenBastUrl && ! auth()->user()?->is_opd())
                                                <span class="text-small text-muted">Belum ada dokumen.</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
                    <div class="text-small text-muted">
                        Menampilkan {{ $pengajuanList->firstItem() }}-{{ $pengajuanList->lastItem() }}
                        dari {{ $pengajuanList->total() }} data.
                    </div>
                    <div>
                        {{ $pengajuanList->links() }}
                    </div>
                </div>
            @endif
        </div>

        {{-- ============ TAB RINGKASAN & GRAFIK ============ --}}
        <div class="tab-pane fade" id="ringkasan-pane" role="tabpanel" aria-labelledby="ringkasan-tab">
            <div class="card mb-3">
                <div class="card-body">
                    <p class="text-muted mb-3">
                        <strong>Semua (default):</strong> gabungan pengajuan siap input BAST dan yang sudah punya BAST.
                        <br>
                        <strong>Belum BAST:</strong> disetujui, BA verifikasi bertanda tangan sudah diunggah, belum ada data BAST.
                        <br>
                        <strong>Sudah BAST:</strong> pengajuan yang sudah memiliki Berita Acara Serah Terima.
                    </p>

                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="text-small text-uppercase text-muted mb-1">Siap input BAST</div>
                                <h3 class="mb-0">{{ number_format($stats['belum_bast']) }}</h3>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="text-small text-uppercase text-muted mb-1">Sudah BAST</div>
                                <h3 class="mb-0">{{ number_format($stats['sudah_bast']) }}</h3>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="text-small text-uppercase text-muted mb-1">Total ditampilkan</div>
                                <h3 class="mb-0">{{ number_format($pengajuanList->total()) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-12 col-xl-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-small text-uppercase text-muted mb-2">Komposisi Tahap BAST</div>
                            <div class="position-relative" style="height: 260px;">
                                <canvas id="monitoring-summary-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-8">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-small text-uppercase text-muted mb-2">Tren Monitoring</div>
                            <div class="position-relative" style="height: 260px;">
                                <canvas id="monitoring-trend-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($isBendahara && $showSp2dModal)
            {{-- Modal Input SP2D (bendahara) --}}
            <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,.5);" wire:click.self="closeSp2d">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form wire:submit="saveSp2d">
                            <div class="modal-header">
                                <h5 class="modal-title">Input SP2D</h5>
                                <button type="button" class="btn-close" wire:click="closeSp2d" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted small mb-3">Menginput SP2D menandai pengajuan telah diperiksa/diverifikasi oleh bendahara.</p>
                                <div class="mb-3">
                                    <label class="form-label">Nomor SP2D <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('sp2dNomor') is-invalid @enderror" wire:model.blur="sp2dNomor">
                                    @error('sp2dNomor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal SP2D <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('sp2dTanggal') is-invalid @enderror" wire:model.blur="sp2dTanggal">
                                        @error('sp2dTanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nilai (Rp)</label>
                                        <input type="text" class="form-control bg-light"
                                               value="{{ ($sp2dNilai !== null && $sp2dNilai !== '') ? 'Rp '.number_format((float) $sp2dNilai, 0, ',', '.') : '-' }}"
                                               readonly disabled>
                                        <small class="text-muted">Mengikuti nilai rekomendasi, tidak dapat diubah.</small>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea class="form-control @error('sp2dKeterangan') is-invalid @enderror" rows="2" wire:model.blur="sp2dKeterangan"></textarea>
                                    @error('sp2dKeterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Dokumen SP2D (PDF, opsional)</label>
                                    <input type="file" accept="application/pdf" class="form-control @error('sp2dDokumen') is-invalid @enderror" wire:model="sp2dDokumen">
                                    <div wire:loading wire:target="sp2dDokumen" class="form-text">Mengunggah…</div>
                                    @error('sp2dDokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                @error('sp2dPengajuanId') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" wire:click="closeSp2d">Batal</button>
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveSp2d,sp2dDokumen">
                                    <span wire:loading.remove wire:target="saveSp2d">Simpan SP2D</span>
                                    <span wire:loading wire:target="saveSp2d">Menyimpan…</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    @endif

    @if($showSp2dDetail && !empty($sp2dDetail))
    {{-- Modal Detail SP2D (read-only) --}}
    <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,.5);" wire:click.self="closeSp2dDetail">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail SP2D</h5>
                    <button type="button" class="btn-close" wire:click="closeSp2dDetail" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="text-small text-uppercase text-muted mb-1">Kode Pengajuan</div>
                            <div class="fw-semibold">{{ $sp2dDetail['kode_pengajuan'] ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-small text-uppercase text-muted mb-1">Nomor SP2D</div>
                            <div class="fw-semibold">{{ $sp2dDetail['nomor'] ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-small text-uppercase text-muted mb-1">Tanggal SP2D</div>
                            <div class="fw-semibold">{{ $sp2dDetail['tanggal'] ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-small text-uppercase text-muted mb-1">Nilai</div>
                            <div class="fw-semibold">{{ $sp2dDetail['nilai'] ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-small text-uppercase text-muted mb-1">Diperiksa oleh</div>
                            <div class="fw-semibold">{{ $sp2dDetail['oleh'] ?? '-' }}</div>
                            <div class="text-small text-muted">{{ $sp2dDetail['dibuat'] ?? '' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="text-small text-uppercase text-muted mb-1">Keterangan</div>
                            <div>{{ $sp2dDetail['keterangan'] ?? '-' }}</div>
                        </div>
                        @if(!empty($sp2dDetail['dokumen_url']))
                        <div class="col-12">
                            <a href="{{ $sp2dDetail['dokumen_url'] }}" target="_blank" class="btn btn-sm btn-outline-warning">Lihat Dokumen SP2D</a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" wire:click="closeSp2dDetail">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@script
<script>
    let monitoringSummaryChart = null;
    let monitoringTrendChart = null;
    let activeMonitoringTab = '#data-pane';

    const readMonitoringChartData = () => {
        const el = document.getElementById('monitoring-chart-data');
        if (! el) {
            return null;
        }

        try {
            return {
                summary: JSON.parse(el.dataset.summary),
                trend: JSON.parse(el.dataset.trend),
            };
        } catch (e) {
            return null;
        }
    };

    const initMonitoringCharts = () => {
        if (typeof Chart === 'undefined') {
            setTimeout(initMonitoringCharts, 50);

            return;
        }

        const data = readMonitoringChartData();
        if (! data) {
            return;
        }

        const summaryData = data.summary;
        const trendData = data.trend;

        const summaryCanvas = document.getElementById('monitoring-summary-chart');
        if (summaryCanvas) {
            if (monitoringSummaryChart) {
                monitoringSummaryChart.destroy();
            }

            monitoringSummaryChart = new Chart(summaryCanvas, {
                type: 'doughnut',
                data: {
                    labels: summaryData.labels,
                    datasets: [{
                        data: summaryData.values,
                        backgroundColor: ['#3abff8', '#2fb344'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                    },
                },
            });
        }

        const trendCanvas = document.getElementById('monitoring-trend-chart');
        if (trendCanvas) {
            if (monitoringTrendChart) {
                monitoringTrendChart.destroy();
            }

            monitoringTrendChart = new Chart(trendCanvas, {
                type: 'line',
                data: {
                    labels: trendData.labels,
                    datasets: [
                        {
                            label: 'Belum BAST',
                            data: trendData.series.belum_bast,
                            borderColor: '#3abff8',
                            backgroundColor: 'rgba(58, 191, 248, 0.12)',
                            tension: 0.35,
                            fill: true,
                        },
                        {
                            label: 'Sudah BAST',
                            data: trendData.series.sudah_bast,
                            borderColor: '#2fb344',
                            backgroundColor: 'rgba(47, 179, 68, 0.12)',
                            tension: 0.35,
                            fill: true,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                            },
                        },
                    },
                },
            });
        }
    };

    const initMonitoringTabs = () => {
        document.querySelectorAll('#monitoring-tab button[data-bs-toggle="tab"]').forEach((btn) => {
            if (btn.dataset.tabBound) {
                return;
            }
            btn.dataset.tabBound = '1';

            btn.addEventListener('shown.bs.tab', (event) => {
                activeMonitoringTab = event.target.getAttribute('data-bs-target');
                if (activeMonitoringTab === '#ringkasan-pane') {
                    initMonitoringCharts();
                }
            });
        });
    };

    const restoreMonitoringTab = () => {
        if (activeMonitoringTab === '#data-pane' || typeof bootstrap === 'undefined') {
            return;
        }

        const trigger = document.querySelector(`#monitoring-tab button[data-bs-target="${activeMonitoringTab}"]`);
        if (trigger) {
            bootstrap.Tab.getOrCreateInstance(trigger).show();
        }
    };

    const initMonitoringFilterSelect2 = () => {
        if (typeof $ === 'undefined' || !$.fn.select2) {
            setTimeout(initMonitoringFilterSelect2, 50);

            return;
        }

        const tahapValue = $wire.get('tahap');
        const $tahapSelect = $('#tahap-monitoring');
        if ($tahapSelect.length) {
            if (! $tahapSelect.hasClass('select2-hidden-accessible')) {
                $tahapSelect.select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    minimumResultsForSearch: Infinity,
                });
            }

            $tahapSelect.val(tahapValue).trigger('change.select2');
            $tahapSelect.off('change.monitoringTahap').on('change.monitoringTahap', function () {
                const value = $(this).val() || 'semua';

                $wire.set('tahap', value);
            });
        }

        const opdValue = $wire.get('opdId') || 'all';
        const $opdSelect = $('#opd-monitoring');
        if ($opdSelect.length) {
            if (! $opdSelect.hasClass('select2-hidden-accessible')) {
                $opdSelect.select2({
                    theme: 'bootstrap4',
                    minimumResultsForSearch: Infinity,
                    width: '100%',
                });
            }

            $opdSelect.val(opdValue).trigger('change.select2');
            $opdSelect.off('change.monitoringOpd').on('change.monitoringOpd', function () {
                const selectedValue = $(this).val();
                const value = selectedValue === 'all' ? '' : (selectedValue || '');

                $wire.set('opdId', value);
            });
        }
    };

    const initBatalVerifikasi = () => {
        $($el).off('click.batalVerifikasi', '.btn-batal-verifikasi').on('click.batalVerifikasi', '.btn-batal-verifikasi', function () {
            const url = $(this).data('url');

            Swal.fire({
                title: 'Batal Verifikasi?',
                text: 'Pengajuan akan kembali ke status Diajukan dan data verifikasi akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#dc3545',
            }).then(function (result) {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    };

    initMonitoringTabs();
    initMonitoringFilterSelect2();
    initBatalVerifikasi();

    Livewire.hook('morph.updated', () => {
        setTimeout(() => {
            initMonitoringTabs();
            restoreMonitoringTab();
            initMonitoringFilterSelect2();
            initBatalVerifikasi();
            if (activeMonitoringTab === '#ringkasan-pane') {
                initMonitoringCharts();
            }
        }, 10);
    });
</script>
@endscript
