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
                'icon' => 'diagram-1',
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
                'id' => 'VERIFY_PENGAJUAN',
                'icon' => 'check-square',
                'url' => '/verifikasi-pengajuan',
                'title' => 'Pengajuan Bantuan'
            ],
        ]
    ],

    [
        'id' => 'PENGAJUAN_DAN_BAST',
        'url' => 'javascript:;',
        'title' => 'Pengajuan & BAST',
        'icon' => '',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'PENGAJUAN_ANGGARAN',
                'icon' => 'box',
                'url' => '/pengajuan-anggaran',
                'title' => 'Pengajuan Anggaran'
            ],

            [
                'id' => 'BAST',
                'icon' => 'book',
                'url' => '/bast',
                'title' => 'BA Serah Terima'
            ],
        ]
    ],

    [
        'id' => 'MONITORING_DAN_REALISASI',
        'url' => 'javascript:;',
        'title' => 'Monitoring & Realisasi',
        'icon' => '',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'BLACKLIST',
                'icon' => 'close-circle',
                'url' => '/blacklist',
                'title' => 'Blacklist Pengguna'
            ],
            [
                'id' => 'MONITORING',
                'icon' => 'activity',
                'url' => '/monitoring-bantuan',
                'title' => 'Monitoring Bantuan'
            ],
            [
                'id' => 'REALISASI_BANTUAN',
                'icon' => 'delivery-truck',
                'url' => '/realisasi-bantuan',
                'title' => 'Realisasi Bantuan'
            ],
        ]
    ],
];
