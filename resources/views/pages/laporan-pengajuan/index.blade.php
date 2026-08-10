@extends('layouts.layout')
@section('title', 'Laporan Pengajuan')
@section('content')
    <div class="col">
        <div class="page-title-container mb-3">
            <div class="row">
                <div class="col mb-2">
                    <h1 class="mb-2 pb-0 display-4">Laporan Pengajuan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:;">Laporan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('laporan-pengajuan.index') }}">Pengajuan</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <livewire:reports.laporan-pengajuan-list />
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}" />
    <style>
        /* Select2 selaras dengan form-select-sm */
        .laporan-pengajuan-list .select2-container--bootstrap4 .select2-selection--single {
            height: 31px;
            font-size: 0.875rem;
        }
        .laporan-pengajuan-list .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 29px;
            padding-top: 0;
            padding-bottom: 0;
        }

        /* Tab kategori */
        .laporan-tabs { padding: 0.25rem 0; }
        .laporan-tab {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 0.9rem;
            border-radius: 0.85rem;
            border: 1.5px solid #e9ecef;
            background: #ffffff;
            color: #6c757d;
            cursor: pointer;
            flex: 1 1 0;
            min-width: 0;
            text-align: left;
            transition: transform 0.18s ease, box-shadow 0.18s ease,
                        background 0.25s ease, color 0.25s ease, border-color 0.25s ease;
        }
        .laporan-tab:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(15, 23, 42, 0.08); }
        .laporan-tab:focus { outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18); }
        .laporan-tab-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 38px; height: 38px;
            border-radius: 0.6rem;
            background: #f1f3f5;
            color: #495057;
            flex-shrink: 0;
            transition: background 0.25s ease, color 0.25s ease;
        }
        .laporan-tab-icon i { width: 18px; height: 18px; }
        .laporan-tab-label { display: flex; flex-direction: column; line-height: 1.2; min-width: 0; }
        .laporan-tab-title { font-weight: 700; font-size: 0.9rem; letter-spacing: 0.01em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .laporan-tab-sub { font-size: 0.68rem; opacity: 0.7; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .laporan-tab-all:hover { border-color: #64748b; color: #334155; }
        .laporan-tab-all:hover .laporan-tab-icon { background: #e2e8f0; color: #334155; }
        .laporan-tab-all.active {
            color: #ffffff; border-color: transparent;
            background: linear-gradient(135deg, #64748b 0%, #334155 100%);
            box-shadow: 0 10px 22px rgba(51, 65, 85, 0.28);
        }
        .laporan-tab-bansos:hover { border-color: #3b82f6; color: #1d4ed8; }
        .laporan-tab-bansos:hover .laporan-tab-icon { background: #dbeafe; color: #1d4ed8; }
        .laporan-tab-bansos.active {
            color: #ffffff; border-color: transparent;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.28);
        }
        .laporan-tab-hibah:hover { border-color: #a855f7; color: #7c3aed; }
        .laporan-tab-hibah:hover .laporan-tab-icon { background: #f3e8ff; color: #7c3aed; }
        .laporan-tab-hibah.active {
            color: #ffffff; border-color: transparent;
            background: linear-gradient(135deg, #c084fc 0%, #7c3aed 100%);
            box-shadow: 0 10px 22px rgba(124, 58, 237, 0.28);
        }
        .laporan-tab-kelompok:hover { border-color: #10b981; color: #047857; }
        .laporan-tab-kelompok:hover .laporan-tab-icon { background: #d1fae5; color: #047857; }
        .laporan-tab-kelompok.active {
            color: #ffffff; border-color: transparent;
            background: linear-gradient(135deg, #34d399 0%, #059669 100%);
            box-shadow: 0 10px 22px rgba(5, 150, 105, 0.28);
        }
        .laporan-tab-subsidi:hover { border-color: #f59e0b; color: #b45309; }
        .laporan-tab-subsidi:hover .laporan-tab-icon { background: #fef3c7; color: #b45309; }
        .laporan-tab-subsidi.active {
            color: #ffffff; border-color: transparent;
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            box-shadow: 0 10px 22px rgba(217, 119, 6, 0.28);
        }
        .laporan-tab.active .laporan-tab-icon { background: rgba(255, 255, 255, 0.22); color: #ffffff; }
        .laporan-tab.active .laporan-tab-sub { opacity: 0.85; }
        @media (max-width: 575.98px) { .laporan-tab { min-width: 0; flex: 1 1 100%; } }
    </style>
@endpush

@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
@endpush
