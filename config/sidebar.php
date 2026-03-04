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
    [
        //ROLE OPD
        'id' => 'SIDE_MASTER_DATA',
        'icon' => '',
        'title' => 'Master Data',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'PENDUDUK',
                'icon' => 'user',
                'url' => '/penduduk',
                'title' => 'Penduduk'
            ],
            [
                'id' => 'KELOMPOK_MASYARAKAT',
                'icon' => 'users',
                'url' => '/kelompok-masyarakat',
                'title' => 'Kelompok Masyarakat'
            ],
        ],
    ],

    [
        'id' => 'SIDE_VERIFIKASI',
        'url' => 'javascript:;',
        'title' => 'Verifikasi',
        'icon' => '',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'VERIFY_PENGGUNA',
                'icon' => 'check-square',
                'url' => '/verifikasi-pengguna',
                'title' => 'Verifikasi Pengguna'
            ],

            [
                'id' => 'VERIFY_BANTUAN',
                'icon' => 'check-square',
                'url' => '/verifikasi-bantuan',
                'title' => 'Pengajuan Bantuan'
            ],
        ]
    ],

    [
        'id' => 'PENGAJUAN_DAN_BAST',
        'url' => 'javascript:;',
        'title' => 'Verifikasi',
        'icon' => '',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'PENGAJUAN_BANTUAN',
                'icon' => 'check-square',
                'url' => '/pengajuan-bantuan',
                'title' => 'Pengajuan Bantuan'
            ],

            [
                'id' => 'BAST',
                'icon' => 'check-square',
                'url' => '/bast',
                'title' => 'BA Serah Terima'
            ],
        ]
    ],

    [
        'id' => 'MONITORING_DAN_REALISASI',
        'url' => 'javascript:;',
        'title' => 'Verifikasi',
        'icon' => '',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'BLACKLIST',
                'icon' => 'check-square',
                'url' => '/blacklist',
                'title' => 'Blacklist Pengguna'
            ],
            [
                'id' => 'MONITORING',
                'icon' => 'check-square',
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
