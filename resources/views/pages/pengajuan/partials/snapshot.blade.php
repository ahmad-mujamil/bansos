{{--
    Snapshot beku data kelompok + penerima pada momen diajukan/disetujui.
    Data historis SELALU dibaca dari tabel snapshot, bukan relasi live.
    Butuh variabel: $pengajuan
--}}
@php
    use App\Enums\MomenSnapshot;

    $snapDisetujui = $pengajuan->snapshotMomen(MomenSnapshot::DISETUJUI);
    $snapDiajukan = $pengajuan->snapshotMomen(MomenSnapshot::DIAJUKAN);
    $snapKelompok = $snapDisetujui ?? $snapDiajukan;
    $snapKelompokMomen = $snapDisetujui ? 'Saat Disetujui' : 'Saat Diajukan';

    $penerimaDisetujui = $pengajuan->penerimaMomen(MomenSnapshot::DISETUJUI);
    $penerimaDiajukan = $pengajuan->penerimaMomen(MomenSnapshot::DIAJUKAN);
    $penerima = $penerimaDisetujui->isNotEmpty() ? $penerimaDisetujui : $penerimaDiajukan;
    $penerimaMomen = $penerimaDisetujui->isNotEmpty() ? 'Saat Disetujui' : 'Saat Diajukan';
@endphp

@if($snapKelompok)
<div class="col-12">
    <div class="pengajuan-field-tile border border-separator bg-foreground p-3 h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="text-muted text-small text-uppercase">Data kelompok (arsip)</div>
            <span class="badge bg-info">{{ $snapKelompokMomen }}</span>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-12 col-sm-6">
                <div class="text-muted small mb-0">Nama kelompok</div>
                <div class="fw-semibold text-body">{{ $snapKelompok->nama_kelompok ?? '—' }}</div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-muted small mb-0">Nomor / SK</div>
                <div class="fw-semibold text-body">{{ $snapKelompok->nomor ?? '—' }}</div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-muted small mb-0">Jenis kelompok</div>
                <div class="fw-semibold text-body">{{ $snapKelompok->jenis_kelompok ?? '—' }}</div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="text-muted small mb-0">Wilayah</div>
                <div class="fw-semibold text-body">{{ $snapKelompok->wilayah_label ?? '—' }}</div>
            </div>
        </div>

        <div class="text-muted small mb-1">Anggota ({{ $snapKelompok->jumlah_anggota }})</div>
        @if($snapKelompok->anggota->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:36px">#</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($snapKelompok->anggota as $i => $anggota)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $anggota->nama }}</td>
                        <td>{{ $anggota->nik ?? '—' }}</td>
                        <td>{{ $anggota->jabatan ?? '—' }}</td>
                        <td>
                            @if($anggota->is_valid)
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-secondary">Belum</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-muted small">Tidak ada anggota tercatat.</div>
        @endif
        <div class="text-muted small mt-2">
            Data ini dibekukan pada momen pengajuan dan tidak ikut berubah walau kelompok/anggota diperbarui.
        </div>
    </div>
</div>
@endif

@if($penerima->isNotEmpty())
<div class="col-12">
    <div class="pengajuan-field-tile border border-separator bg-foreground p-3 h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="text-muted text-small text-uppercase">Penerima (arsip)</div>
            <span class="badge bg-info">{{ $penerimaMomen }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:36px">#</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Nilai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penerima as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->nik ?? '—' }}</td>
                        <td>{{ $row->nilai !== null ? 'Rp ' . number_format((float) $row->nilai, 0, ',', '.') : '—' }}</td>
                        <td>
                            @if($row->is_valid)
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-secondary">Belum</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-muted small mt-2">
            Data penerima dibekukan pada momen pengajuan.
        </div>
    </div>
</div>
@endif
