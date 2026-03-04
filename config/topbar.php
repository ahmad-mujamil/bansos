<?php

return [
    [
        'id' => 'ADMIN',
        'url' => 'javascript:;',
        'icon' => 'shield-check',
        'title' => 'Administrator',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'PENGGUNA',
                'url' => '/pengguna',
                'title' => 'Pengguna'
            ],
        ]
    ],[
        'id' => 'MASTER_DATA',
        'icon' => 'gear',
        'title' => 'Master Data',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'PENDUDUK',
                'url' => '/penduduk',
                'title' => 'Penduduk'
            ],
            [
                'id' => 'KELOMPOK_MASYARAKAT',
                'url' => '/kelompok-masyarakat',
                'title' => 'Kelompok Masyarakat'
            ],
            [
                'id' => 'JENIS_BANTUAN',
                'url' => '/jenis-bantuan',
                'title' => 'Jenis Bantuan'
            ],
            [
                'id' => 'OPD',
                'url' => '/opd',
                'title' => 'OPD'
            ],

        ]
    ],
    [
        'id' => 'WILAYAH_ADMINISTRASI',
        'icon' => 'pin',
        'title' => 'Wilayah',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'KECAMATAN',
                'icon' => 'pin',
                'url' => '/kecamatan',
                'title' => 'Kecamatan'
            ],
            [
                'id' => 'DESA',
                'icon' => 'destination',
                'url' => '/desa',
                'title' => 'Desa/Kelurahan'
            ],

        ]
    ],
    [
        'id' => 'LAPORAN',
        'url' => 'javascript:;',
        'icon' => 'file-text',
        'title' => 'Laporan',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'LAP_PENGAJUAN',
                'url' => '/laporan-pengajuan',
                'title' => 'Pengajuan Bansos'
            ],
            [
                'id' => 'LAP_REALISASI',
                'url' => '/laporan-realisasi',
                'title' => 'Realisasi'
            ],
        ]
    ],

];
