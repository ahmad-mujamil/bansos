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
            [
                'id' => 'BLACKLIST',
                'url' => '/blacklist',
                'title' => 'Blacklist Pengguna'
            ],
            [
                'id' => 'PENILAIAN_REALISASI',
                'url' => '/penilaian-realisasi',
                'title' => 'Penilaian Realisasi'
            ],
            [
                'id' => 'MONITORING',
                'url' => '/monitoring',
                'title' => 'Monitoring'
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
        'id' => 'VERIFIKASI',
        'url' => 'javascript:;',
        'title' => 'Verifikasi',
        'icon' => 'check-square',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'VERIFY_PENGGUNA',
                'url' => '/verifikasi-pengguna',
                'title' => 'Verifikasi Pengguna'
            ],
            
            [
                'id' => 'VERIFY_BANSOS',
                'url' => '/verifikasi-bansos',
                'title' => 'Pengajuan Bansos'
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
