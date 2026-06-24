@php
    $organisasi = $row->organisasi;

    $anggota = $organisasi
        ? $organisasi->organisasiDetail
            ->sortBy(fn ($d) => array_search(
                $d->jabatan?->value,
                ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Admin', 'Anggota'],
                true
            ))
            ->values()
        : collect();

    if ($anggota->isNotEmpty()) {
        // Kelompok: tampilkan anggota beserta jabatan.
        $judul = 'Anggota Kelompok';
        $showJabatan = true;
        $penerima = $anggota->map(fn ($d) => [
            'penduduk' => $d->penduduk,
            'jabatan'  => $d->jabatan?->value ?? '-',
        ]);
    } else {
        // Bansos / individu: tampilkan penerima dari detail pengajuan.
        $judul = 'Penerima Bantuan';
        $showJabatan = false;
        $penerima = $row->details
            ->filter(fn ($d) => $d->penduduk)
            ->map(fn ($d) => ['penduduk' => $d->penduduk, 'jabatan' => '-'])
            ->values();
    }
@endphp

<div class="p-3 bg-light border-top">
    <div class="fw-bold text-uppercase text-muted small mb-2">
        <i data-acorn-icon="user" data-acorn-size="15" class="me-1 align-middle"></i>
        {{ $judul }} ({{ $penerima->count() }})
    </div>

    @if($penerima->isEmpty())
        <div class="text-muted fst-italic small">Belum ada data penerima.</div>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle mb-0 bg-white">
                <thead class="table-secondary">
                    <tr>
                        <th style="width: 2.5rem;">No</th>
                        <th style="width: 11rem;">NIK</th>
                        <th>Nama</th>
                        @if($showJabatan)
                            <th style="width: 8rem;">Jabatan</th>
                        @endif
                        <th style="width: 12rem;">Desil</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penerima as $i => $p)
                        @php $desil = $p['penduduk']?->level_desil; @endphp
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>{{ $p['penduduk']?->nik ?? '-' }}</td>
                            <td class="fw-semibold">{{ $p['penduduk']?->nama ?? '-' }}</td>
                            @if($showJabatan)
                                <td><span class="badge bg-secondary">{{ $p['jabatan'] }}</span></td>
                            @endif
                            <td>
                                @if($desil)
                                    <span class="badge bg-info text-dark">{{ $desil->value }}</span>
                                    {{ $desil->getDescription() }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
