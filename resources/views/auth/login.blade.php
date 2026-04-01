@extends('layouts.layout_full')

@section('content_left')
    <style>
        :root {
            --login-blue: #0d6efd;
            --login-pink: #d63384;
            --login-teal: #20c997;
            --login-amber: #ffc107;
            --login-green: #198754;
            --login-red: #dc3545;
        }

        .login-glossy-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.92) 0%, rgba(246, 250, 252, 0.86) 100%);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            position: relative;
        }

        /* Gradient frame + subtle pattern (lebih "wah" tapi tetap soft) */
        .login-glossy-card::before {
            content: "";
            position: absolute;
            inset: 0;
            padding: 1px;
            border-radius: 1rem;
            background: linear-gradient(135deg,
                    rgba(13, 110, 253, 0.55) 0%,
                    rgba(214, 51, 132, 0.35) 45%,
                    rgba(32, 201, 151, 0.35) 100%);
            mask:
                linear-gradient(#000 0 0) content-box,
                linear-gradient(#000 0 0);
            -webkit-mask:
                linear-gradient(#000 0 0) content-box,
                linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: 0.9;
        }

        .login-glossy-card::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 1rem;
            background:
                radial-gradient(circle at 20% 18%, rgba(13, 110, 253, 0.10) 0%, rgba(13, 110, 253, 0) 55%),
                radial-gradient(circle at 85% 24%, rgba(214, 51, 132, 0.08) 0%, rgba(214, 51, 132, 0) 52%),
                radial-gradient(circle at 60% 120%, rgba(32, 201, 151, 0.08) 0%, rgba(32, 201, 151, 0) 52%),
                radial-gradient(rgba(26, 54, 93, 0.06) 1px, transparent 1px);
            background-size: auto, auto, auto, 16px 16px;
            background-position: center, center, center, 0 0;
            opacity: 0.55;
            pointer-events: none;
        }

        .login-hero {
            border-bottom: 1px solid rgba(26, 54, 93, 0.12);
            background:
                radial-gradient(980px 340px at 12% 18%, rgba(13, 110, 253, 0.34) 0%, rgba(13, 110, 253, 0.10) 56%, rgba(255, 255, 255, 0) 100%),
                radial-gradient(900px 320px at 90% 18%, rgba(214, 51, 132, 0.20) 0%, rgba(214, 51, 132, 0.06) 55%, rgba(255, 255, 255, 0) 100%),
                radial-gradient(760px 260px at 55% 110%, rgba(32, 201, 151, 0.18) 0%, rgba(32, 201, 151, 0.06) 55%, rgba(255, 255, 255, 0) 100%);
        }

        .login-hero .login-hero-icon {
            width: 38px;
            height: 38px;
            border: 1px solid rgba(1, 119, 142, 0.18);
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.16) 0%, rgba(214, 51, 132, 0.10) 55%, rgba(32, 201, 151, 0.10) 100%);
        }

        .login-hero-title {
            font-size: 1.05rem;
            letter-spacing: 0.2px;
        }

        @media (min-width: 992px) {
            .login-hero-title {
                font-size: 1.15rem;
            }
        }

        .login-hero-subtitle {
            color: rgba(33, 37, 41, 0.62) !important;
        }

        /* Dashboard layout */
        .dash-shell {
            padding: 0.9rem;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            flex: 1 1 auto;
            min-height: 0;
        }

        @media (min-width: 992px) {
            .dash-shell {
                padding: 1rem;
                gap: 0.9rem;
            }
        }

        .dash-topbar {
            border: 1px solid rgba(26, 54, 93, 0.12);
            border-radius: 1rem;
            background:
                radial-gradient(980px 320px at 10% 25%, rgba(13, 110, 253, 0.22) 0%, rgba(13, 110, 253, 0.07) 55%, rgba(255, 255, 255, 0) 100%),
                radial-gradient(900px 280px at 90% 30%, rgba(214, 51, 132, 0.16) 0%, rgba(214, 51, 132, 0.05) 55%, rgba(255, 255, 255, 0) 100%),
                rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 0.9rem 0.95rem;
            position: relative;
            overflow: hidden;
        }

        .dash-topbar::after {
            content: "";
            position: absolute;
            inset: -2px;
            opacity: 0.35;
            background:
                radial-gradient(circle at 18% 40%, rgba(13, 110, 253, 0.35) 0%, rgba(13, 110, 253, 0) 55%),
                radial-gradient(circle at 78% 38%, rgba(214, 51, 132, 0.26) 0%, rgba(214, 51, 132, 0) 52%),
                radial-gradient(circle at 60% 120%, rgba(32, 201, 151, 0.20) 0%, rgba(32, 201, 151, 0) 52%);
            pointer-events: none;
        }

        .dash-topbar > * {
            position: relative;
            z-index: 1;
        }

        .dash-title {
            letter-spacing: 0.2px;
            font-weight: 800;
        }

        .dash-chip {
            border: 1px solid rgba(26, 54, 93, 0.14);
            background: rgba(255, 255, 255, 0.70);
        }

        .dash-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.6rem;
        }

        @media (min-width: 1200px) {
            .dash-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .dash-main {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 0.75rem;
            min-height: 0;
            flex: 1 1 auto;
        }

        @media (min-width: 1200px) {
            .dash-main {
                grid-template-columns: minmax(0, 0.48fr) minmax(0, 0.52fr);
            }
        }

        .dash-widget {
            border: 1px solid rgba(26, 54, 93, 0.12);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.62);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            overflow: hidden;
            min-height: 0;
            position: relative;
        }

        .dash-widget::before {
            content: "";
            position: absolute;
            inset: 0;
            padding: 1px;
            border-radius: 1rem;
            background: linear-gradient(135deg,
                    rgba(13, 110, 253, 0.35) 0%,
                    rgba(214, 51, 132, 0.22) 45%,
                    rgba(32, 201, 151, 0.22) 100%);
            mask:
                linear-gradient(#000 0 0) content-box,
                linear-gradient(#000 0 0);
            -webkit-mask:
                linear-gradient(#000 0 0) content-box,
                linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: 0.7;
        }

        .dash-widget > * {
            position: relative;
            z-index: 1;
        }

        .dash-widget--insight .dash-widget-header {
            background:
                linear-gradient(90deg, rgba(255, 193, 7, 0.20) 0%, rgba(13, 110, 253, 0.10) 45%, rgba(32, 201, 151, 0.10) 100%),
                rgba(255, 255, 255, 0.68);
        }

        .dash-widget--table .dash-widget-header {
            background:
                linear-gradient(90deg, rgba(214, 51, 132, 0.14) 0%, rgba(13, 110, 253, 0.10) 45%, rgba(32, 201, 151, 0.12) 100%),
                rgba(255, 255, 255, 0.68);
        }

        .dash-widget-header {
            padding: 0.75rem 0.85rem;
            border-bottom: 1px solid rgba(26, 54, 93, 0.12);
            background:
                linear-gradient(90deg, rgba(13, 110, 253, 0.10) 0%, rgba(214, 51, 132, 0.07) 45%, rgba(32, 201, 151, 0.07) 100%),
                rgba(255, 255, 255, 0.68);
        }

        .dash-widget-body {
            padding: 0.85rem;
        }

        .dash-meter {
            width: 100%;
            height: 9px;
            border-radius: 999px;
            overflow: hidden;
            appearance: none;
            -webkit-appearance: none;
        }

        .dash-meter::-webkit-progress-bar {
            background: rgba(26, 54, 93, 0.10);
            border-radius: 999px;
        }

        .dash-meter::-webkit-progress-value {
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(13, 110, 253, 0.95) 0%, rgba(214, 51, 132, 0.75) 55%, rgba(32, 201, 151, 0.85) 100%);
        }

        .dash-meter::-moz-progress-bar {
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(13, 110, 253, 0.95) 0%, rgba(214, 51, 132, 0.75) 55%, rgba(32, 201, 151, 0.85) 100%);
        }

        .dash-meter--warn::-webkit-progress-value {
            background: linear-gradient(90deg, rgba(255, 193, 7, 0.95) 0%, rgba(255, 193, 7, 0.55) 60%, rgba(255, 255, 255, 0) 100%);
        }

        .dash-meter--warn::-moz-progress-bar {
            background: linear-gradient(90deg, rgba(255, 193, 7, 0.95) 0%, rgba(255, 193, 7, 0.55) 60%, rgba(255, 255, 255, 0) 100%);
        }

        .dash-meter--success::-webkit-progress-value {
            background: linear-gradient(90deg, rgba(25, 135, 84, 0.95) 0%, rgba(25, 135, 84, 0.55) 60%, rgba(255, 255, 255, 0) 100%);
        }

        .dash-meter--success::-moz-progress-bar {
            background: linear-gradient(90deg, rgba(25, 135, 84, 0.95) 0%, rgba(25, 135, 84, 0.55) 60%, rgba(255, 255, 255, 0) 100%);
        }

        .dash-meter--danger::-webkit-progress-value {
            background: linear-gradient(90deg, rgba(220, 53, 69, 0.95) 0%, rgba(220, 53, 69, 0.55) 60%, rgba(255, 255, 255, 0) 100%);
        }

        .dash-meter--danger::-moz-progress-bar {
            background: linear-gradient(90deg, rgba(220, 53, 69, 0.95) 0%, rgba(220, 53, 69, 0.55) 60%, rgba(255, 255, 255, 0) 100%);
        }

        .login-meta-pill {
            border: 1px solid rgba(26, 54, 93, 0.12);
            background: rgba(255, 255, 255, 0.70);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        .login-glossy-table-wrap {
            border: 1px solid rgba(26, 54, 93, 0.12);
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.9) 0%, rgba(241, 248, 250, 0.85) 100%);
            box-shadow: 0 10px 28px -20px rgba(0, 0, 0, 0.55);
            position: relative;
        }

        .login-glossy-table-wrap::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                    rgba(13, 110, 253, 0.10) 0%,
                    rgba(214, 51, 132, 0.06) 50%,
                    rgba(32, 201, 151, 0.06) 100%);
            opacity: 0.55;
            pointer-events: none;
        }

        .login-glossy-table-wrap > * {
            position: relative;
            z-index: 1;
        }

        .login-stat-card {
            border: 1px solid rgba(26, 54, 93, 0.12);
            background: rgba(255, 255, 255, 0.74);
            transition: transform 160ms ease, box-shadow 160ms ease, border-color 160ms ease, background 160ms ease;
            position: relative;
            overflow: hidden;
        }

        .login-stat-card::after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--stat-accent, linear-gradient(180deg, rgba(13, 110, 253, 0.95) 0%, rgba(13, 110, 253, 0.55) 100%));
            opacity: 0.95;
        }

        .login-stat-card::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0.9;
            background: var(--stat-bg, linear-gradient(135deg, rgba(13, 110, 253, 0.12) 0%, rgba(13, 110, 253, 0.04) 55%, rgba(255, 255, 255, 0) 100%));
            pointer-events: none;
        }

        .login-stat-card > * {
            position: relative;
            z-index: 1;
        }

        .login-stat-card:hover {
            transform: translateY(-2px);
            border-color: rgba(13, 110, 253, 0.25);
            box-shadow:
                0 18px 38px -28px rgba(0, 0, 0, 0.70),
                0 0 0 4px rgba(13, 110, 253, 0.06);
            background: rgba(255, 255, 255, 0.84);
        }

        .login-stat-card .login-stat-icon {
            width: 42px;
            height: 42px;
            border: 1px solid rgba(26, 54, 93, 0.12);
        }

        .login-stat--total {
            --stat-bg: radial-gradient(320px 120px at 16% 18%, rgba(13, 110, 253, 0.20) 0%, rgba(13, 110, 253, 0.06) 55%, rgba(255, 255, 255, 0) 100%);
            --stat-accent: linear-gradient(180deg, rgba(13, 110, 253, 1) 0%, rgba(13, 110, 253, 0.55) 100%);
        }

        .login-stat--diajukan {
            --stat-bg: radial-gradient(320px 120px at 16% 18%, rgba(255, 193, 7, 0.24) 0%, rgba(255, 193, 7, 0.07) 55%, rgba(255, 255, 255, 0) 100%);
            --stat-accent: linear-gradient(180deg, rgba(255, 193, 7, 1) 0%, rgba(255, 193, 7, 0.55) 100%);
        }

        .login-stat--disetujui {
            --stat-bg: radial-gradient(320px 120px at 16% 18%, rgba(25, 135, 84, 0.22) 0%, rgba(25, 135, 84, 0.07) 55%, rgba(255, 255, 255, 0) 100%);
            --stat-accent: linear-gradient(180deg, rgba(25, 135, 84, 1) 0%, rgba(25, 135, 84, 0.55) 100%);
        }

        .login-stat--ditolak {
            --stat-bg: radial-gradient(320px 120px at 16% 18%, rgba(220, 53, 69, 0.22) 0%, rgba(220, 53, 69, 0.07) 55%, rgba(255, 255, 255, 0) 100%);
            --stat-accent: linear-gradient(180deg, rgba(220, 53, 69, 1) 0%, rgba(220, 53, 69, 0.55) 100%);
        }

        .login-stat--total .login-stat-icon {
            background: rgba(13, 110, 253, 0.10);
            border-color: rgba(13, 110, 253, 0.22);
            color: #0d6efd;
        }

        .login-stat--diajukan .login-stat-icon {
            background: rgba(255, 193, 7, 0.12);
            border-color: rgba(255, 193, 7, 0.28);
        }

        .login-stat--disetujui .login-stat-icon {
            background: rgba(25, 135, 84, 0.10);
            border-color: rgba(25, 135, 84, 0.24);
        }

        .login-stat--ditolak .login-stat-icon {
            background: rgba(220, 53, 69, 0.10);
            border-color: rgba(220, 53, 69, 0.24);
        }

        .login-glossy-table thead th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #5f6b76;
            border-bottom: 1px solid rgba(26, 54, 93, 0.12);
            background:
                linear-gradient(90deg, rgba(13, 110, 253, 0.10) 0%, rgba(214, 51, 132, 0.08) 45%, rgba(32, 201, 151, 0.08) 100%),
                rgba(255, 255, 255, 0.72);
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .login-glossy-table tbody td {
            border-color: rgba(26, 54, 93, 0.08);
            vertical-align: top;
            background: transparent;
        }

        .login-glossy-table tbody tr:hover td {
            background: rgba(13, 110, 253, 0.05);
        }

        .login-glossy-table tbody tr {
            transition: background 160ms ease;
        }

        .login-glossy-table tbody tr:nth-child(2n) td {
            background: rgba(255, 255, 255, 0.38);
        }

        .login-glossy-table tbody tr td:first-child {
            padding-left: 0.85rem;
        }

        .login-glossy-table tbody tr td:last-child {
            padding-right: 0.85rem;
        }

        .login-compact-stat {
            padding: 0.7rem 0.75rem !important;
        }

        .login-compact-stat .h4 {
            font-size: 1.25rem;
            line-height: 1.2;
        }

        .login-glossy-table td,
        .login-glossy-table th {
            padding: 0.5rem 0.65rem;
        }

        .login-glossy-table .badge {
            font-size: 0.68rem;
            padding: 0.28rem 0.45rem;
        }

        .login-status-pill {
            border-radius: 999px;
            letter-spacing: 0.06em;
        }

        /* Khusus halaman login: naikkan fokus background agar tidak tertutup panel */
        .fixed-background {
            background-position: center top !important;
        }
    </style>

    <div class="w-100 h-100 d-flex justify-content-center align-items-stretch p-2">
        <div class="w-100 login-glossy-card shadow-deep rounded-xl overflow-hidden d-flex flex-column">
            <div class="dash-shell">
                <div class="dash-topbar">
                    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                        <div class="d-flex align-items-center gap-2 min-w-0">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle login-hero-icon flex-shrink-0">
                                <i data-acorn-icon="dashboard" data-acorn-size="16"></i>
                            </span>
                            <div class="min-w-0">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <div class="dash-title text-primary">Dashboard Pengajuan (Publik)</div>
                                    <span class="badge bg-outline-primary text-uppercase">Ringkasan</span>
                                </div>
                                <div class="text-small login-hero-subtitle">
                                    Snapshot statistik & daftar pengajuan terbaru.
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <span class="dash-chip badge rounded-pill text-body">
                                <i data-acorn-icon="calendar" data-acorn-size="14" class="me-1 text-primary"></i>
                                {{ now()->translatedFormat('d M Y') }}
                            </span>
                            <span class="dash-chip badge rounded-pill text-body d-none d-lg-inline">
                                <i data-acorn-icon="clock" data-acorn-size="14" class="me-1 text-primary"></i>
                                {{ now()->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="dash-grid">
                    <div class="login-stat-card login-stat--total login-compact-stat d-flex align-items-center justify-content-between gap-2 rounded-xl">
                        <div class="min-w-0">
                            <div class="text-small text-muted">Total pengajuan</div>
                            <div class="h4 mb-0 text-primary fw-bold">{{ number_format($totalPengajuanPublik ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle login-stat-icon flex-shrink-0">
                            <i data-acorn-icon="layers" data-acorn-size="16"></i>
                        </span>
                    </div>
                    <div class="login-stat-card login-stat--diajukan login-compact-stat d-flex align-items-center justify-content-between gap-2 rounded-xl">
                        <div class="min-w-0">
                            <div class="text-small text-muted">Diajukan</div>
                            <div class="h4 mb-0 text-warning fw-bold">{{ number_format($totalDiajukan ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle login-stat-icon flex-shrink-0 text-warning">
                            <i data-acorn-icon="send" data-acorn-size="16"></i>
                        </span>
                    </div>
                    <div class="login-stat-card login-stat--disetujui login-compact-stat d-flex align-items-center justify-content-between gap-2 rounded-xl">
                        <div class="min-w-0">
                            <div class="text-small text-muted">Disetujui</div>
                            <div class="h4 mb-0 text-success fw-bold">{{ number_format($totalDisetujui ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle login-stat-icon flex-shrink-0 text-success">
                            <i data-acorn-icon="check" data-acorn-size="16"></i>
                        </span>
                    </div>
                    <div class="login-stat-card login-stat--ditolak login-compact-stat d-flex align-items-center justify-content-between gap-2 rounded-xl">
                        <div class="min-w-0">
                            <div class="text-small text-muted">Ditolak</div>
                            <div class="h4 mb-0 text-danger fw-bold">{{ number_format($totalDitolak ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle login-stat-icon flex-shrink-0 text-danger">
                            <i data-acorn-icon="close" data-acorn-size="16"></i>
                        </span>
                    </div>
                </div>

                @php
                    $total = (int) ($totalPengajuanPublik ?? 0);
                    $diajukan = (int) ($totalDiajukan ?? 0);
                    $disetujui = (int) ($totalDisetujui ?? 0);
                    $ditolak = (int) ($totalDitolak ?? 0);
                    $pct = fn (int $x) => $total > 0 ? min(100, max(0, (int) round(($x / $total) * 100))) : 0;
                @endphp

                <div class="dash-main">
                    <div class="dash-widget dash-widget--insight d-flex flex-column">
                        <div class="dash-widget-header">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i data-acorn-icon="activity" data-acorn-size="14" class="text-primary"></i>
                                    <div class="text-small fw-semibold text-primary">Ringkasan status</div>
                                </div>
                                <span class="badge bg-outline-primary text-uppercase">Insight</span>
                            </div>
                        </div>
                        <div class="dash-widget-body">
                            <div class="d-flex flex-column gap-3">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                        <div class="text-small fw-semibold text-body">Diajukan</div>
                                        <div class="text-small text-muted">{{ number_format($diajukan, 0, ',', '.') }} ({{ $pct($diajukan) }}%)</div>
                                    </div>
                                    <progress class="dash-meter dash-meter--warn" value="{{ $diajukan }}" max="{{ max($total, 1) }}"></progress>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                        <div class="text-small fw-semibold text-body">Disetujui</div>
                                        <div class="text-small text-muted">{{ number_format($disetujui, 0, ',', '.') }} ({{ $pct($disetujui) }}%)</div>
                                    </div>
                                    <progress class="dash-meter dash-meter--success" value="{{ $disetujui }}" max="{{ max($total, 1) }}"></progress>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                        <div class="text-small fw-semibold text-body">Ditolak</div>
                                        <div class="text-small text-muted">{{ number_format($ditolak, 0, ',', '.') }} ({{ $pct($ditolak) }}%)</div>
                                    </div>
                                    <progress class="dash-meter dash-meter--danger" value="{{ $ditolak }}" max="{{ max($total, 1) }}"></progress>
                                </div>
                                <div class="pt-1">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="text-small text-muted">Total</div>
                                        <div class="fw-bold text-primary">{{ number_format($total, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="text-small text-muted mt-1">
                                        Data ini bersifat publik sebagai ringkasan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dash-widget dash-widget--table d-flex flex-column min-h-0">
                        <div class="dash-widget-header">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i data-acorn-icon="clock" data-acorn-size="14" class="text-primary"></i>
                                    <div class="text-small fw-semibold text-primary">Pengajuan terbaru</div>
                                </div>
                                <span class="badge bg-outline-primary">Total: {{ number_format($totalPengajuanPublik ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="table-responsive flex-grow-1">
                            <table class="table login-glossy-table align-middle mb-0">
                                <thead class="text-small text-muted">
                                    <tr>
                                        <th style="width: 1%;">Kode</th>
                                        <th>Judul</th>
                                        <th style="width: 1%;">Status</th>
                                        <th style="width: 1%;">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($pengajuanTerbaru ?? collect()) as $pengajuan)
                                        <tr>
                                            <td class="text-small fw-semibold">
                                                <span class="badge bg-outline-secondary font-monospace">
                                                    {{ $pengajuan->kode_pengajuan }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-body line-clamp-2">{{ $pengajuan->judul }}</div>
                                                @if(($pengajuan->lokasi ?? '') !== '')
                                                    <div class="text-small text-muted line-clamp-1">{{ $pengajuan->lokasi }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusValue = $pengajuan->status?->value ?? (string) $pengajuan->status;
                                                    $badgeClass = match ($statusValue) {
                                                        'disetujui' => 'bg-outline-success',
                                                        'ditolak' => 'bg-outline-danger',
                                                        'diajukan' => 'bg-outline-warning',
                                                        default => 'bg-outline-secondary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }} text-uppercase login-status-pill">
                                                    {{ $pengajuan->status?->getDescription() ?? (string) $pengajuan->status }}
                                                </span>
                                            </td>
                                            <td class="text-small text-muted text-nowrap">
                                                <div class="fw-semibold text-body">
                                                    {{ $pengajuan->created_at?->translatedFormat('d M Y') ?? '—' }}
                                                </div>
                                                <div class="text-small text-muted">
                                                    Rp {{ number_format((float) ($pengajuan->nilai ?? 0), 0, ',', '.') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <div class="d-flex flex-column align-items-center justify-content-center gap-2">
                                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary"
                                                        style="width: 46px; height: 46px;">
                                                        <i data-acorn-icon="inbox" data-acorn-size="18"></i>
                                                    </span>
                                                    <div class="fw-semibold text-body">Belum ada data pengajuan</div>
                                                    <div class="text-small text-muted">
                                                        Data pengajuan terbaru akan muncul di sini setelah ada pengajuan yang masuk.
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if(($pengajuanTerbaru ?? null) && method_exists($pengajuanTerbaru, 'links'))
                            <div class="px-3 py-2 border-top border-separator bg-light">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="text-small text-muted">
                                        Menampilkan {{ $pengajuanTerbaru->count() }} data
                                    </div>
                                    <div class="ms-auto d-flex align-items-center gap-2">
                                        @if ($pengajuanTerbaru->onFirstPage())
                                            <span class="btn btn-sm btn-outline-secondary disabled" aria-disabled="true">
                                                <i data-acorn-icon="chevron-left" data-acorn-size="14"></i>
                                                <span class="ms-1">Sebelumnya</span>
                                            </span>
                                        @else
                                            <a href="{{ $pengajuanTerbaru->previousPageUrl() }}"
                                                class="btn btn-sm btn-outline-primary btn-icon btn-icon-start">
                                                <i data-acorn-icon="chevron-left" data-acorn-size="14"></i>
                                                <span>Sebelumnya</span>
                                            </a>
                                        @endif

                                        @if ($pengajuanTerbaru->hasMorePages())
                                            <a href="{{ $pengajuanTerbaru->nextPageUrl() }}"
                                                class="btn btn-sm btn-primary btn-icon btn-icon-end">
                                                <span>Berikutnya</span>
                                                <i data-acorn-icon="chevron-right" data-acorn-size="14"></i>
                                            </a>
                                        @else
                                            <span class="btn btn-sm btn-outline-secondary disabled" aria-disabled="true">
                                                <span>Berikutnya</span>
                                                <i data-acorn-icon="chevron-right" data-acorn-size="14"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content_right')
    <!-- Right Side Start -->
    <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
        <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
            <div class="sw-lg-50 px-6">
                <div class="sh-11 mb-6">
                    <a href="">
                        <img src="{{ asset('img/logo/logo-wide.png') }}" alt="logo" class="img-fluid"/>

                    </a>
                </div>
                <br>
                <div class="mb-5">
                    <p class="h6">Gunakan Username dan Password untuk masuk kedalam aplikasi</p>
                </div>
                <div>
                    <form id="loginForm" class="tooltip-end-bottom" novalidate action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="user"></i>
                            <input class="form-control" placeholder="Username" name="username" id="username" />
                        </div>
                        <div class="mb-3 filled form-group tooltip-end-top">
                            <i data-acorn-icon="lock-off"></i>
                            <input class="form-control pe-7" name="password" type="password" placeholder="Password" />
                            <!-- <a class="text-small position-absolute t-3 e-3" href="#">Lupa password?</a>
                            <a href=""><span class="badge rounded-pill bg-foreground mt-2">* Pertanyaan yang paling sering diajukan (FAQ's)</span></a>
           -->
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary w-100">Masuk</button>
                    </form>
                    <br>
                    <div class="text-center">
                        <p class="text-small">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
                    </div>
                    <br><br>
                    <span class="badge rounded-pill bg-foreground mt-2">Copyright &copy;2026. Pemerintah Kabupaten Lombok Barat</span>

                </div>
            </div>
        </div>
    </div>
    <!-- Right Side End -->
@endsection
