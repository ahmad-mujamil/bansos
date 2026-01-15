<?php

return [
    [
        'id' => 'MANAGEMENT',
        'icon' => '',
        'title' => 'Management',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'USER',
                'icon' => 'user',
                'url' => '/pengguna',
                'title' => 'Pengguna'
            ],
            [
                'id' => 'OPD',
                'icon' => 'building',
                'url' => '/opd',
                'title' => 'OPD'
            ],
        ]
    ],

    [
        'id' => 'MASTER_DATA',
        'icon' => '',
        'title' => 'Master Data',
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
                'title' => 'Desa'
            ],
            [
                'id' => 'ORGANISASI',
                'icon' => 'diagram-1',
                'url' => '/organisasi',
                'title' => 'Organisasi'
            ],
            [
                'id' => 'PENDUDUK',
                'icon' => 'content',
                'url' => '/id-penduduk',
                'title' => 'ID Penduduk'
            ],
        ]
    ],
    [
        'id' => 'BANTUAN SOSIAL',
        'icon' => '',
        'title' => 'Bantuan Sosial',
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
            [
                'id' => 'VERIFIKASI',
                'icon' => 'check-square',
                'url' => '/verifikasi',
                'title' => 'Verifikasi'
            ],
        ]
    ],
    [
        'id' => 'LAPORAN',
        'icon' => '',
        'title' => 'Laporan',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'REALISASI_OPD',
                'icon' => 'file-text',
                'url' => '/realisasi-opd',
                'title' => 'Realisasis OPD'
            ],

            [
                'id' => 'REALISASI_ORGAN',
                'icon' => 'file-text',
                'url' => '/realisasi-organisasi',
                'title' => 'Realisasi Organisasi'
            ],

            [
                'id' => 'REALISASI_PERORANGAN',
                'icon' => 'file-text',
                'url' => '/realisasi-perorangan',
                'title' => 'Realisasi Perorangan'
            ],
        ]
    ],

];
