<?php

return [
    [
        'id' => 'PROFILE',
        'icon' => '',
        'title' => 'Profile',
        'url' => 'javascript:;',
        'caret' => true,
        'sub_menu' => [
            [
                'id' => 'PROFILE_PERORANGAN',
                'icon' => 'user',
                'url' => '/profile-perorangan',
                'title' => 'Perorangan'
            ],
            [
                'id' => 'PROFILE_ORGANISASI',
                'icon' => 'building',
                'url' => '/profile-organisasi',
                'title' => 'Organisasi'
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
                'icon' => 'file-text',
                'url' => '/lap-pengajuan',
                'title' => 'Pengajuan'
            ],
            [
                'id' => 'LAP_REALISASI',
                'icon' => 'file-text',
                'url' => '/lap-realisasi',
                'title' => 'Realisasi'
            ],
        ],
    ],

];
