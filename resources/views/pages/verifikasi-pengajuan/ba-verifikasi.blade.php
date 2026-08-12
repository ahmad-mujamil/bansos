<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>BA Verifikasi</title>
    <style>
        @page {
            margin: 32pt 40pt 48pt 40pt;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            line-height: 1.3;
        }

        /* Footer tiap halaman (Dompdf mengulang elemen position: fixed) */
        .ba-doc-footer {
            position: fixed;
            bottom: 5pt;
            left: 44pt;
            right: 44pt;
            width: auto;
            margin: 0;
            padding: 0;
            text-align: right;
            font-size: 10px;
            font-style: italic;
            color: #4a4a4a;
        }

        .ba-doc-footer-app {
            font-weight: 700;
            font-style: italic;
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

        table.table-pemeriksa thead th.pemeriksa-corner {
            width: 22%;
        }

        table.table-pemeriksa thead th.pemeriksa-head {
            text-align: center;
            font-weight: 700;
        }

        table.table-pemeriksa tbody td.pemeriksa-label-col {
            text-align: left;
        }

        table.table-pemeriksa tbody td.pemeriksa-value {
            text-align: center;
        }

        table.table-pemeriksa tbody tr.pemeriksa-paraf-row td.pemeriksa-paraf-cell {
            min-height: 44px;
            height: 44px;
            vertical-align: middle;
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

        /* Penutup rekomendasi + tanda tangan: jaga tetap satu kesatuan saat ganti halaman */
        .rekomendasi-penutup {
            page-break-inside: avoid;
        }

        /* Tanda tangan: blok mepet ke kanan */
        .ttd-wrap {
            margin-top: 20px;
            width: 100%;
            text-align: right;
            padding-right: 0;
            page-break-inside: avoid;
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
            min-height: 44px;
            height: 44px;
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
        // Subsidi bunga: rupa bantuan dikunci ke subsidi_bunga saat verifikasi, tapi
        // kategori pengajuan tetap diperiksa agar dokumen lama tanpa rupa ikut terbaca.
        $isSubsidiBunga = $pengajuan->kategori_pengajuan === \App\Enums\JenisPengajuan::SUBSIDI_BUNGA
            || $verifikasi->rupa_bantuan === \App\Enums\RupaBantuan::SUBSIDI_BUNGA;
        $opd = $pengajuan->organisasi?->opd ?? $pengajuan->opd;
        $logoPath = public_path('img/logo-only.png');
        $logoSrc = is_file($logoPath)
            ? 'data:image/png;base64,' . base64_encode((string) file_get_contents($logoPath))
            : '';
    @endphp

    <table class="kop-table">
        <tr>
            <td class="kop-logo-cell">
                @if (!empty($logoSrc))
                    <img class="kop-logo" src="{{ $logoSrc }}" width="64" height="82" alt="Logo" />
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

    <div class="title" style="text-transform: capitalize;">
        @if ($pengajuan->kategori_pengajuan === \App\Enums\JenisPengajuan::BANSOS)
            Berita Acara Hasil Verifikasi Usulan Permohonan Bantuan Sosial Tahun Anggaran {{ $tahunAnggaran }}
        @elseif ($pengajuan->kategori_pengajuan === \App\Enums\JenisPengajuan::HIBAH)
            Berita Acara Hasil Verifikasi Usulan Permohonan Bantuan Hibah Tahun Anggaran {{ $tahunAnggaran }}
        @elseif ($isSubsidiBunga)
            Berita Acara Hasil Verifikasi Usulan Permohonan Bantuan Subsidi Bunga Tahun Anggaran {{ $tahunAnggaran }}
        @else
            Berita Acara Hasil Verifikasi Usulan Permohonan Belanja Barang
            Diserahkan Kepada Masyarakat Tahun Anggaran {{ $tahunAnggaran }}
        @endif
    </div>

    <div class="section">
        <div class="bold lvl-0">I. Evaluasi</div>

        <div class="row lvl-1 bold" style="margin-top: 6px;">A. Data Proposal</div>

        <div class="lvl-2">
            <table class="proposal-list">
                <tr>
                    <td class="num">1.</td>
                    <td class="lbl">Nama {{ $isIndividu ? 'Individu' : 'Kelompok' }}</td>
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
                    <td class="lbl">Jenis Barang/Bantuan</td>
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
            @php($pemeriksaKolom = max(\App\Models\PengajuanPemeriksa::MIN_VERIFIKASI_COUNT, $pemeriksa->count()))
            <table class="table table-pemeriksa">
                <thead>
                    <tr>
                        <th class="pemeriksa-corner" scope="col"></th>
                        @for ($n = 1; $n <= $pemeriksaKolom; $n++)
                            <th class="pemeriksa-head" scope="col">Pemeriksa {{ $n }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="pemeriksa-label-col" style="width: 60px;">Nama</td>
                        @for ($i = 0; $i < $pemeriksaKolom; $i++)
                            <td class="pemeriksa-value">{{ $pemeriksa->get($i)?->nama ?? '' }}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td class="pemeriksa-label-col" style="width: 60px;">NIP</td>
                        @for ($i = 0; $i < $pemeriksaKolom; $i++)
                            <td class="pemeriksa-value">{{ $pemeriksa->get($i)?->nip ?? '' }}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td class="pemeriksa-label-col" style="width: 60px;">Jabatan</td>
                        @for ($i = 0; $i < $pemeriksaKolom; $i++)
                            <td class="pemeriksa-value">{{ $pemeriksa->get($i)?->jabatan ?? '' }}</td>
                        @endfor
                    </tr>
                    <tr class="pemeriksa-paraf-row">
                        <td class="pemeriksa-label-col" style="width: 60px;">Paraf</td>
                        @for ($i = 0; $i < $pemeriksaKolom; $i++)
                            <td class="pemeriksa-value pemeriksa-paraf-cell">&nbsp;</td>
                        @endfor
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        @php($tglVerifikasi = $pengajuan->verified_at->locale('id'))
        <div class="lvl-1">Berdasarkan hasil verifikasi administrasi terhadap permohonan/proposal yang dilaksanakan
            pada hari {{ $tglVerifikasi->translatedFormat('l') }} tanggal
            {{ $tglVerifikasi->translatedFormat('d') }} bulan
            {{ $tglVerifikasi->translatedFormat('F') }}
            tahun {{ $tglVerifikasi->translatedFormat('Y') }}, dengan hasil sebagai berikut:
        </div>

        <div class="lvl-2">
            <table class="kriteria-list">
                <tr>
                    <td class="num">1.</td>
                    <td class="lbl bold">Memenuhi kriteria pemberian {{ $isSubsidiBunga ? 'subsidi bunga' : 'bantuan barang' }}</td>
                    <td class="sep">:</td>
                    <td>{{ $verifikasi->lulus_kriteria ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <td class="num">2.</td>
                    <td class="lbl bold">Kelengkapan administrasi penerima {{ $isSubsidiBunga ? 'subsidi bunga' : 'bantuan barang' }} sesuai perbup</td>
                    <td class="sep">:</td>
                    <td>{{ $verifikasi->lulus_administrasi ? 'Lengkap' : 'Tidak Lengkap' }}</td>
                </tr>
                <tr>
                    <td class="num">3.</td>
                    <td class="lbl bold">Kesesuaian kegiatan dengan proposal</td>
                    <td class="sep">:</td>
                    <td>{{ $verifikasi->lulus_kesesuaian ? 'Sesuai' : 'Tidak Sesuai' }}</td>
                </tr>
                <tr>
                    <td class="num">4.</td>
                    <td class="lbl bold">Kegiatan tersebut menunjang pencapaian sasaran program dan kegiatan pemerintah
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
            @if ($isSubsidiBunga)
                Berkenaan dengan hal tersebut, permohonan/proposal dinilai layak untuk diberikan bantuan subsidi bunga
                sebagaimana tercantum dalam tabel di atas dengan nilai sebesar Rp.
            @else
                Berkenaan dengan hal tersebut, permohonan/proposal dinilai layak untuk diberikan belanja barang yang
                diserahkan kepada masyarakat berupa sebagaimana tercantum dalam tabel di atas dengan nilai sebesar Rp.
            @endif
            {{ $nilaiBesar }}
            ({{ $nilaiBesarTerbilang }}).
        </div>
        <div class="rekomendasi-penutup">
            <div class="row lvl-1" style="margin-top: 10px;">
                Demikian bagian rekomendasi hasil evaluasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
            </div>
            <table class="ttd-wrap" style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="border: none; padding: 0;">&nbsp;</td>
                    <td style="border: none; padding: 0; width: 260px; text-align: center; vertical-align: top;">
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
                        <div class="bold ttd-kepala-nama">{{ $verifikasi->disahkan_oleh ?: '-' }}</div>
                        <div class="ttd-kepala-garis">...................................................</div>
                        <div class="ttd-kepala-nip">NIP: {{ $verifikasi->disahkan_nip ?? '' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="ba-doc-footer">
        Dokumen ini dicetak melalui aplikasi <span class="ba-doc-footer-app">Bantu In</span>
    </div>
</body>

</html>
