<?php

return [
    // ROLE USER
    [
        'id' => 'PROFILE_USER',
        'icon' => 'user',
        'title' => 'Profile User',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'PROFILE_USER_DETAIL',
                'icon' => 'user',
                'url' => '/user-detail',
                'title' => 'Detail Data'
            ],
        ],
    ],
    [
        'id' => 'BANTUAN',
        'icon' => '',
        'title' => 'Pengajuan Bantuan',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'PENGAJUAN',
                'icon' => 'book',
                'url' => '/pengajuan',
                'title' => 'Pengajuan'
            ],
            [
                'id' => 'REALISASI',
                'icon' => 'notebook-1',
                'url' => '/realisasi',
                'title' => 'Realisasi'
            ],
        ]
    ],
    [
        'id' => 'SIDE_LAPORAN',
        'icon' => '',
        'title' => 'Laporan',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'SIDE_LAP_PENGAJUAN',
                'icon' => 'file-text',
                'url' => '/lap-pengajuan',
                'title' => 'Pengajuan'
            ],
            [
                'id' => 'SIDE_LAP_REALISASI',
                'icon' => 'file-text',
                'url' => '/lap-realisasi',
                'title' => 'Realisasi'
            ],
        ],
    ],

    //ROLE OPD
    'id' => 'SIDE_MASTER_DATA',
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
    ],

    [
        'id' => 'SIDE_VERIFIKASI',
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
                'id' => 'VERIFY_BANTUAN',
                'url' => '/verifikasi-bantuan',
                'title' => 'Pengajuan Bantuan'
            ],
        ]
    ],

    [
        'id' => 'PENGAJUAN_DAN_BAST',
        'url' => 'javascript:;',
        'title' => 'Verifikasi',
        'icon' => 'check-square',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'PENGAJUAN_BANTUAN',
                'url' => '/pengajuan-bantuan',
                'title' => 'Pengajuan Bantuan'
            ],

            [
                'id' => 'BAST',
                'url' => '/bast',
                'title' => 'Berita Acara Serah Terima'
            ],
        ]
    ],

    [
        'id' => 'MONITORING_DAN_REALISASI',
        'url' => 'javascript:;',
        'title' => 'Verifikasi',
        'icon' => 'check-square',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'BLACKLIST',
                'url' => '/blacklist',
                'title' => 'Blacklist Pengguna'
            ],
            [
                'id' => 'MONITORING',
                'url' => '/monitoring-bantuan',
                'title' => 'Monitoring Bantuan'
            ],

            [
                'id' => 'REALISASI_BANTUAN',
                'url' => '/realisasi-bantuan',
                'title' => 'Realisasi Bantuan'
            ],
        ]
    ],


];
