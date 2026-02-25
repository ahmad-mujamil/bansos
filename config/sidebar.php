<?php

return [

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
        'id' => 'BANTUAN_SOSIAL',
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



];
