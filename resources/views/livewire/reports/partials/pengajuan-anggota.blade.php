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
@endphp

<div class="p-3 bg-light border-top">
    <div class="fw-bold text-uppercase text-muted small mb-2">
        <i data-acorn-icon="user" data-acorn-size="15" class="me-1 align-middle"></i>
        Anggota Kelompok ({{ $anggota->count() }})
    </div>

    @if($anggota->isEmpty())
        <div class="text-muted fst-italic small">Belum ada anggota.</div>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle mb-0 bg-white">
                <thead class="table-secondary">
                    <tr>
                        <th style="width: 2.5rem;">No</th>
                        <th style="width: 11rem;">NIK</th>
                        <th>Nama</th>
                        <th style="width: 8rem;">Jabatan</th>
                        <th style="width: 12rem;">Desil</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anggota as $i => $d)
                        @php $desil = $d->penduduk?->level_desil; @endphp
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>{{ $d->penduduk?->nik ?? '-' }}</td>
                            <td class="fw-semibold">{{ $d->penduduk?->nama ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ $d->jabatan?->value ?? '-' }}</span></td>
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
