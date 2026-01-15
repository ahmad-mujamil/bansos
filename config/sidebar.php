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
                'id' => 'PROFILE_PERORANGAN',
                'icon' => 'user',
                'url' => '/profile-perorangan',
                'title' => 'Profile Perorangan'
            ],
            [
                'id' => 'PROFILE_ORGANISASI',
                'icon' => 'building',
                'url' => '/profile-organisasi',
                'title' => 'PROFILE ORGANISASI'
            ],
        ]
    ],
    [
        'id' => 'BANTUAN_SOSIAL',
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
                'id' => 'LAP_PENGAJUAN',
                'icon' => 'copy',
                'url' => '/lap-pengajuan',
                'title' => 'Pengajuan'
            ],
            [
                'id' => 'LAP_REALISASI',
                'icon' => 'copy',
                'url' => '/lap-realisasi',
                'title' => 'Realisasi'
            ],
        ]
    ],

];
