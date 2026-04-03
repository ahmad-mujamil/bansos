<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>BA Verifikasi</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            line-height: 1.35;
        }

        /* Kop surat: tabel agar logo & teks sejajar vertikal di Dompdf */
        table.kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        table.kop-table td.kop-logo-cell {
            width: 72px;
            vertical-align: middle;
            padding: 0 4px 0 0;
        }

        table.kop-table td.kop-text-cell {
            vertical-align: middle;
            text-align: center;
        }

        .kop-logo {
            width: 64px;
            height: auto;
            display: block;
        }

        .kop-line1 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .kop-line2 {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .kop-line3 {
            font-size: 12px;
            margin-top: 2px;
        }

        .kop-line4 {
            font-size: 12px;
            margin-top: 2px;
        }

        .kop-line5 {
            font-size: 12px;
            margin-top: 2px;
        }

        .kop-hr {
            border-top: 1px solid #000;
            margin: 6px 0 10px;
        }

        .title {
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .section {
            margin-top: 10px;
        }

        /* Indent hierarki seperti contoh dokumen */
        .lvl-0 {
            margin-left: 0;
        }

        .lvl-1 {
            margin-left: 18px;
        }

        .lvl-2 {
            margin-left: 36px;
        }

        .row {
            margin: 2px 0;
        }

        /* Daftar data proposal: titik dua sejajar */
        table.proposal-list {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        table.proposal-list td {
            border: none;
            padding: 1px 0;
            vertical-align: top;
        }

        table.proposal-list td.num {
            width: 18px;
            white-space: nowrap;
        }

        table.proposal-list td.lbl {
            width: 200px;
            padding-right: 4px;
        }

        table.proposal-list td.sep {
            width: 8px;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .table td,
        .table th {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        table.table td.pemeriksa-cell {
            text-align: center;
            vertical-align: top;
        }

        .bold {
            font-weight: 700;
        }

        .center {
            text-align: center;
        }

        /* Kriteria verifikasi: indent + label + titik dua */
        table.kriteria-list {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        table.kriteria-list td {
            border: none;
            padding: 2px 0;
            vertical-align: top;
        }

        table.kriteria-list td.num {
            width: 18px;
        }

        table.kriteria-list td.lbl {
            padding-right: 6px;
        }

        table.kriteria-list td.sep {
            width: 10px;
        }

        /* Tanda tangan: blok mepet ke kanan */
        .ttd-wrap {
            margin-top: 28px;
            width: 100%;
            text-align: right;
            padding-right: 0;
        }

        .ttd-inner {
            display: inline-block;
            text-align: center;
            width: 260px;
            margin-left: 0;
        }

        .ttd-paraf {
            margin: 4px 0 2px;
            letter-spacing: 0.02em;
            line-height: 1.2;
        }

        .ttd-date-line {
            white-space: nowrap;
            font-size: 11px;
        }

        .ttd-kepala-jabatan {
            margin: 0;
        }

        /* Ruang kosong untuk tanda tangan basah antara jabatan dan nama */
        .ttd-ruang-tanda-tangan {
            min-height: 52px;
            height: 52px;
        }

        .ttd-kepala-nama {
            margin: 0;
            line-height: 1.25;
        }

        .ttd-kepala-garis {
            margin: 0;
            line-height: 1.15;
        }

        .ttd-kepala-nip {
            margin: 1px 0 0;
            line-height: 1.2;
            font-size: 11px;
        }
    </style>
</head>

<body>
    @php
        $opd = $pengajuan->organisasi?->opd;
        $logoPath = public_path('img/logo-only.png');
        $logoSrc = is_file($logoPath)
            ? 'data:image/png;base64,' . base64_encode((string) file_get_contents($logoPath))
            : '';
    @endphp

    <table class="kop-table">
        <tr>
            <td class="kop-logo-cell">
                @if (!empty($logoSrc))
                    <img class="kop-logo" src="{{ $logoSrc }}" alt="Logo" />
                @endif
            </td>
            <td class="kop-text-cell">
                <div class="kop-line1">PEMERINTAH KABUPATEN LOMBOK BARAT</div>
                <div class="kop-line2">{{ $opd?->nama ?? '' }}</div>
                <div class="kop-line3">{{ $opd?->alamat ?? '' }}</div>
                <div class="kop-line4">
                    Telp: {{ $opd?->no_telp ?? '-' }}
                    @if (!empty($opd?->fax))
                        &nbsp;Faks: {{ $opd?->fax }}
                    @endif
                </div>
                <div class="kop-line5">
                    Email: {{ $opd?->email ?? '-' }}{{ $opd?->website ? ' | Website: ' . $opd?->website : '' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="kop-hr"></div>

    <div class="title">
        Berita Acara Hasil Verifikasi Usulan Permohonan Belanja Barang
        Diserahkan Kepada Masyarakat Tahun Anggaran {{ $tahunAnggaran }}
    </div>

    <div class="section">
        <div class="bold lvl-0">I. Evaluasi</div>

        <div class="row lvl-1 bold" style="margin-top: 6px;">A. Data Proposal</div>

        <div class="lvl-2">
            <table class="proposal-list">
                <tr>
                    <td class="num">1.</td>
                    <td class="lbl">Nama Kelompok</td>
                    <td class="sep">:</td>
                    <td class="bold">{{ $namaKelompok }}</td>
                </tr>
                <tr>
                    <td class="num">2.</td>
                    <td class="lbl">Alamat</td>
                    <td class="sep">:</td>
                    <td class="bold">{{ $alamat ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="num">3.</td>
                    <td class="lbl">Ketua/Pengurus/Pemohon</td>
                    <td class="sep">:</td>
                    <td class="bold">{{ $pemohon }}</td>
                </tr>
                <tr>
                    <td class="num">4.</td>
                    <td class="lbl">Jenis Barang</td>
                    <td class="sep">:</td>
                    <td class="bold">{{ $jenisBarang }}</td>
                </tr>
                <tr>
                    <td class="num">5.</td>
                    <td class="lbl">Spesifikasi Teknis</td>
                    <td class="sep">:</td>
                    <td class="bold">{{ $spesifikasiTeknis ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="num">6.</td>
                    <td class="lbl">Satuan</td>
                    <td class="sep">:</td>
                    <td class="bold">{{ $satuan ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="num">7.</td>
                    <td class="lbl">Jumlah Usulan</td>
                    <td class="sep">:</td>
                    <td class="bold">{{ number_format((float) $jumlahUsulan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="num">8.</td>
                    <td class="lbl">Jumlah Disetujui</td>
                    <td class="sep">:</td>
                    <td class="bold">{{ number_format((float) $jumlahDisetujui, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="row lvl-1 bold" style="margin-top: 8px;">B. Pemeriksa</div>

        <div class="lvl-2">
            <table class="table">
                <tr>
                    @for ($i = 0; $i < 3; $i++)
                        <td class="pemeriksa-cell">
                            <div>{{ $pemeriksa[$i]->nama ?? '' }}</div>
                            <div>{{ $pemeriksa[$i]->jabatan ?? '' }}</div>
                            <div>{{ $pemeriksa[$i]->nip ?? '' }}</div>
                            <div style="margin-top: 12px;">....................</div>
                        </td>
                    @endfor
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="lvl-1">Berdasarkan hasil verifikasi administrasi terhadap permohonan/proposal yang dilaksanakan
            pada hari {{ $pengajuan->verified_at->translatedFormat('l') }} tanggal
            {{ $pengajuan->verified_at->translatedFormat('d') }} bulan
            {{ $pengajuan->verified_at->translatedFormat('F') }}
            tahun {{ $pengajuan->verified_at->translatedFormat('Y') }}, dengan hasil sebagai berikut:
        </div>

        <div class="lvl-2">
            <table class="kriteria-list">
                <tr>
                    <td class="num">1.</td>
                    <td class="lbl bold">Kriteria pemberian bantuan</td>
                    <td class="sep">:</td>
                    <td>{{ $verifikasi->lulus_kriteria ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <td class="num">2.</td>
                    <td class="lbl bold">Kesesuaian administrasi penerimaan bantuan</td>
                    <td class="sep">:</td>
                    <td>{{ $verifikasi->lulus_administrasi ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <td class="num">3.</td>
                    <td class="lbl bold">Kesesuaian kegiatan dengan proposal</td>
                    <td class="sep">:</td>
                    <td>{{ $verifikasi->lulus_kesesuaian ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <td class="num">4.</td>
                    <td class="lbl bold">Kegiatan tersebut memenuhi pencapaian sasaran program dan kegiatan pemerintah
                        daerah</td>
                    <td class="sep">:</td>
                    <td>{{ $verifikasi->sesuai_program_pemda ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <td class="num">5.</td>
                    <td class="lbl bold">Keterangan lainnya</td>
                    <td class="sep">:</td>
                    <td>{{ $verifikasi->catatan ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="bold lvl-0">II. Rekomendasi</div>
        <div class="row lvl-1" style="margin-top: 6px;">
            Berkenaan dengan hal tersebut, permohonan/proposal dinilai layak untuk diberikan bantuan
            sebagai mana tercantum dalam tabel di atas dengan nilai sebesar Rp. {{ $nilaiBesar }}
            ({{ $nilaiBesarTerbilang }}).
        </div>
        <div class="row lvl-1" style="margin-top: 10px;">
            Demikian berita acara hasil evaluasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </div>
        <div class="ttd-wrap">
            <div class="ttd-inner">
                <div class="bold">Mengesahkan</div>
                <div class="ttd-date-line" style="margin-top: 4px; margin-bottom: 8px;">
                    {{ $verifikasi->lokasi_pengesahan ?: 'Gerung' }},
                    @if ($verifikasi->tgl_disahkan)
                        {{ $verifikasi->tgl_disahkan->translatedFormat('d F Y') }}
                    @else
                        .................. {{ now()->translatedFormat('Y') }}
                    @endif
                </div>
                <div class="bold ttd-kepala-jabatan">Kepala SKPD,</div>
                <div class="ttd-ruang-tanda-tangan" aria-hidden="true"></div>
                <div class="bold ttd-kepala-nama">{{ $kepalaSkpd ?: '-' }}</div>
                <div class="ttd-kepala-garis">...................................................</div>
                <div class="ttd-kepala-nip">NIP: {{ $opd?->nip ?? '' }}</div>
            </div>
        </div>
    </div>
</body>

</html>
