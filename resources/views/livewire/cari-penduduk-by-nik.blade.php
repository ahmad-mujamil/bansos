<div>
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="small-title mb-3">Cari berdasarkan NIK</h2>
            <form wire:submit="search" class="row g-3 align-items-end">
                <div class="col-12 col-md-8 col-lg-6">
                    <label for="cari-nik" class="form-label text-small text-uppercase">NIK (16 digit)</label>
                    <input id="cari-nik" type="text" class="form-control @error('nik') is-invalid @enderror"
                        wire:model="nik" inputmode="numeric" autocomplete="off" maxlength="32"
                        placeholder="Contoh: 3201010101010001" />
                    @error('nik')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 col-md-4 col-lg-3">
                    <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="search">Cari</span>
                        <span wire:loading wire:target="search">Mencari…</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if ($hasSearched && $penduduk === null)
        <div class="alert alert-warning mb-0" role="alert">
            Penduduk dengan NIK tersebut tidak ditemukan.
        </div>
    @endif

    @if ($penduduk !== null)
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="small-title mb-4">Detail penduduk</h2>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">NIK</span>
                        <div class="fw-semibold">{{ $penduduk->nik }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Nama lengkap</span>
                        <div class="fw-semibold">{{ $penduduk->nama }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">No. KK</span>
                        <div class="fw-semibold">{{ $penduduk->no_kk ?? '—' }}</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <span class="text-small text-uppercase text-muted">Alamat</span>
                        <div>{{ $penduduk->alamat ?? '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Jenis kelamin</span>
                        <div>{{ $penduduk->jk?->getDescription() ?? '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Tempat / tanggal lahir</span>
                        <div>
                            {{ $penduduk->tempat_lahir ?? '—' }}
                            @if ($penduduk->tgl_lahir)
                                , {{ $penduduk->tgl_lahir->translatedFormat('d F Y') }}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Agama</span>
                        <div>{{ $penduduk->agama ?? '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Desa</span>
                        <div>{{ $penduduk->desa?->nama ?? '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Kecamatan</span>
                        <div>{{ $penduduk->kecamatan?->nama ?? ($penduduk->desa?->kecamatan?->nama ?? '—') }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="text-small text-uppercase text-muted">Status validasi</span>
                        <div>
                            @if ($penduduk->is_valid)
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-secondary">Belum terverifikasi</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-2">
            <h2 class="small-title">Keanggotaan kelompok / organisasi</h2>
            <p class="text-small text-muted mb-0">
                Data keanggotaan dari pendaftaran anggota kelompok masyarakat.
            </p>
        </div>

        @if ($keanggotaan->isEmpty())
            <div class="alert alert-light border mb-0" role="alert">
                @if (auth()->user()?->is_opd())
                    Tidak ada keanggotaan kelompok pada OPD Anda untuk penduduk ini.
                @else
                    Penduduk ini belum terdaftar sebagai anggota kelompok manapun.
                @endif
            </div>
        @else
            <div class="row g-3">
                @foreach ($keanggotaan as $detail)
                    @php
                        $org = $detail->organisasi;
                    @endphp
                    <div class="col-12 col-md-6 col-xl-4" wire:key="anggota-{{ $detail->id }}">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <h3 class="h6 mb-0">{{ $org->nama }}</h3>
                                    @if ($org->is_active)
                                        <span class="badge bg-success flex-shrink-0">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary flex-shrink-0">Nonaktif</span>
                                    @endif
                                </div>
                                <div class="text-small text-muted mb-2">
                                    {{-- cast from JenisOrganisasi --}}
                                    @php
                                        $jenis = \App\Enums\JenisOrganisasi::tryFrom($org->jenis ?? '');
                                    @endphp
                                    {{ $jenis?->getDescription() ?? '—' }}
                                </div>
                                <div class="mb-2">
                                    <span class="text-small text-uppercase text-muted">Jabatan</span>
                                    <div>
                                        <span
                                            class="badge bg-primary">{{ $detail->jabatan?->getDescription() ?? $detail->jabatan }}</span>
                                    </div>
                                </div>
                                <div class="mb-2 text-small">
                                    <div><span class="text-muted">Nomor SK / akta:</span> {{ $org->nomor ?? '—' }}
                                    </div>
                                    <div><span class="text-muted">Wilayah:</span>
                                        {{ $org->kecamatan?->nama ?? '—' }} / {{ $org->desa?->nama ?? '—' }}
                                    </div>
                                    <div><span class="text-muted">OPD:</span> {{ $org->opd?->nama ?? '—' }}</div>
                                </div>
                                @if ($org->is_blacklist)
                                    <div class="alert alert-danger py-2 px-3 text-small mb-3" role="alert">
                                        Kelompok dalam daftar blacklist
                                    </div>
                                @endif
                                @if (auth()->user()->is_opd() &&  auth()->user()->opd->organisasi->contains($org->id))
                                    <div class="mt-auto pt-2">
                                        <a href="{{ route('kelompok-masyarakat.edit', $org) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Buka data kelompok
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
