<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kelompok Masyarakat</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            background: #fff;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .header p { font-size: 11px; }

        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr th {
            background: #e8e8e8;
            border: 1px solid #999;
            padding: 5px 6px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
        }

        tbody tr td {
            border: 1px solid #bbb;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 10px;
        }

        tbody tr:nth-child(even) td { background: #f9f9f9; }

        tr.group-header td {
            background: #1a56a0 !important;
            color: #fff;
            font-weight: bold;
            font-size: 10px;
            padding: 5px 8px;
            border: 1px solid #1a56a0;
        }

        .badge-aktif    { color: #155724; font-weight: bold; }
        .badge-nonaktif { color: #721c24; font-weight: bold; }

        .footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            font-size: 10px;
        }

        .footer .ttd { text-align: center; min-width: 180px; }
        .footer .ttd .space { height: 60px; }

        .no-data { text-align: center; padding: 20px; color: #666; font-style: italic; }

        @media print {
            body { padding: 10px; }
            .no-print { display: none !important; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom: 14px; display: flex; gap: 8px;">
    <button onclick="window.print()" style="padding: 6px 14px; background: #0d6efd; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
        &#128438; Cetak
    </button>
    <button onclick="window.close()" style="padding: 6px 14px; background: #6c757d; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
        Tutup
    </button>
</div>

<div class="header">
    <h2>Rekap Data Kelompok Masyarakat</h2>
    @if ($selectedOpd)
        <p>OPD: {{ $selectedOpd->nama }}</p>
    @else
        <p>Semua OPD</p>
    @endif
    <p>Status: {{ $statusLabel }}</p>
</div>

<div class="meta">
    <span>Dicetak oleh: {{ auth()->user()->name }}</span>
    <span>Tanggal cetak: {{ now()->translatedFormat('d F Y H:i') }}</span>
</div>

@php
    $grouped = $kelompoks->groupBy(fn ($k) => $k->opd?->nama ?? '-');
    $totalAnggota = $kelompoks->sum('organisasi_detail_count');
    $no = 1;
@endphp

<table>
    <thead>
        <tr>
            <th style="width: 28px;">No</th>
            <th>Nama Kelompok</th>
            <th>Nomor SK</th>
            <th style="width: 80px;">Tgl Pembentukan</th>
            <th>Jenis Kelompok</th>
            <th>Kecamatan</th>
            <th>Desa/Kelurahan</th>
            <th style="width: 50px; text-align: center;">Anggota</th>
            <th style="width: 55px; text-align: center;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($grouped as $opdNama => $rows)
            <tr class="group-header">
                <td colspan="9">&#9776; {{ $opdNama }}</td>
            </tr>
            @foreach ($rows as $row)
                <tr>
                    <td style="text-align: center;">{{ $no++ }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->nomor ?? '-' }}</td>
                    <td style="text-align: center;">{{ $row->tgl_pembentukan?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $row->jenisKelompokMasyarakat?->nama ?? '-' }}</td>
                    <td>{{ $row->kecamatan?->nama ?? '-' }}</td>
                    <td>{{ $row->desa?->nama ?? '-' }}</td>
                    <td style="text-align: center;">{{ $row->organisasi_detail_count }}</td>
                    <td style="text-align: center;">
                        @if ($row->is_active)
                            <span class="badge-aktif">Aktif</span>
                        @else
                            <span class="badge-nonaktif">Nonaktif</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="9" class="no-data">Tidak ada data kelompok yang ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($kelompoks->isNotEmpty())
    <div style="margin-top: 10px; font-size: 10px;">
        Total: <strong>{{ $kelompoks->count() }}</strong> kelompok,
        Jumlah anggota: <strong>{{ $totalAnggota }}</strong> orang
    </div>
@endif

<div class="footer">
    <div class="ttd">
        <p>.............................., {{ now()->translatedFormat('d F Y') }}</p>
        <p>Mengetahui,</p>
        <div class="space"></div>
        <p><strong>( _____________________________ )</strong></p>
    </div>
</div>

</body>
</html>