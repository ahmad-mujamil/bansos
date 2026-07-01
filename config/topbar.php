<?php

return [
//    [
//        'id' => 'ADMIN',
//        'url' => 'javascript:;',
//        'icon' => 'shield-check',
//        'title' => 'Administrator',
//        'caret' => true,
//        'sub_menu' => [
//            [
//                'id' => 'PENGGUNA',
//                'url' => '/pengguna',
//                'title' => 'Pengguna',
//            ],
//        ],
//    ],
    [
        'id' => 'MASTER_DATA',
        'icon' => 'gear',
        'title' => 'Master Data',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'PENDUDUK',
                'url' => '/penduduk',
                'title' => 'Penduduk',
            ],
            [
                'id' => 'JENIS_BANTUAN',
                'url' => '/jenis-bantuan',
                'title' => 'Jenis Bantuan',
            ],
            [
                'id' => 'TAHUN_ANGGARAN',
                'url' => '/tahun-anggaran',
                'title' => 'Tahun Anggaran',
            ],
            [
                'id' => 'OPD',
                'url' => '/opd',
                'title' => 'OPD',
            ],
            [
                'id' => 'PENGGUNA',
                'url' => '/pengguna',
                'title' => 'Pengguna',
            ],


        ],
    ],
    [
        'id' => 'LANDING_PAGE',
        'url' => 'javascript:;',
        'icon' => 'board-3',
        'title' => 'Landing Page',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'BERITA',
                'url' => '/kelola/berita',
                'title' => 'Berita',
            ],
            [
                'id' => 'SLIDER',
                'url' => '/kelola/slider',
                'title' => 'Slider',
            ], [
                'id' => 'GALERI',
                'url' => '/kelola/gallery',
                'title' => 'Gallery',
            ], [
                'id' => 'PROFILE',
                'url' => '/profile',
                'title' => 'Profile',
            ], [
                'id' => 'ALUR-BANTUAN',
                'url' => '/kelola/alur-bantuan',
                'title' => 'Alur Bantuan',
            ],
        ],
    ],
    [
        'id' => 'DATA_KELOMPOK_MASYARAKAT',
        'icon' => 'diagram-1',
        'title' => 'Kelompok Masyarakat',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'JENIS_KELOMPOK_MASYARAKAT',
                'url' => '/jenis-kelompok-masyarakat',
                'title' => 'Jenis Kelompok',
            ],
            [
                'id' => 'KELOMPOK_MASYARAKAT',
                'url' => '/kelompok-masyarakat',
                'title' => 'Data Kelompok',
            ],

        ],
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
                'title' => 'Kecamatan',
            ],
            [
                'id' => 'DESA',
                'icon' => 'destination',
                'url' => '/desa',
                'title' => 'Desa/Kelurahan',
            ],

        ],
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
                'title' => 'Laporan Pengajuan',
            ],
            [
                'id' => 'LAP_PENERIMA_BANTUAN',
                'url' => '/laporan-penerima',
                'title' => 'Penerima Bantuan',
            ],
            [
                'id' => 'LAP_REALISASI',
                'url' => '/laporan-realisasi',
                'title' => 'Realisasi',
            ],
            [
                'id' => 'LAP_KELOMPOK',
                'url' => '/laporan-kelompok',
                'title' => 'Rekap Kelompok',
            ],
            [
                'id' => 'LAP_ANGGOTA_KELOMPOK',
                'icon' => 'user',
                'url' => '/laporan-anggota-kelompok',
                'title' => 'Anggota Kelompok',
            ],
        ],
    ],

];
