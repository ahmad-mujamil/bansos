@extends('layouts.layout_full')

@section('content_left')
    <style>
        .login-glossy-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.92) 0%, rgba(246, 250, 252, 0.86) 100%);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .login-glossy-table-wrap {
            border: 1px solid rgba(26, 54, 93, 0.12);
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.9) 0%, rgba(241, 248, 250, 0.85) 100%);
            box-shadow: 0 10px 28px -20px rgba(0, 0, 0, 0.55);
        }

        .login-glossy-table thead th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #5f6b76;
            border-bottom: 1px solid rgba(26, 54, 93, 0.12);
            background: rgba(255, 255, 255, 0.66);
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
            background: rgba(1, 119, 142, 0.05);
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

        /* Khusus halaman login: naikkan fokus background agar tidak tertutup panel */
        .fixed-background {
            background-position: center top !important;
        }
    </style>

    <div class="w-100 h-100 d-flex justify-content-center align-items-stretch p-2">
        <div class="w-100 login-glossy-card shadow-deep rounded-xl overflow-hidden d-flex flex-column">
            <div class="px-3 py-3 border-bottom border-separator">
                <h5 class="text-primary mb-1">Pengajuan Bantuan</h5>
                <p class="text-muted mb-0 text-small">Ringkasan data pengajuan.</p>
            </div>

            <div class="px-3 py-3">
                <div class="row g-2">
                    <div class="col-6 col-xl-3">
                        <div class="rounded-lg border border-separator login-compact-stat h-100">
                            <div class="text-small text-muted">Total</div>
                            <div class="h4 mb-0 text-primary fw-bold">{{ number_format($totalPengajuanPublik ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rounded-lg border border-separator login-compact-stat h-100">
                            <div class="text-small text-muted">Diajukan</div>
                            <div class="h4 mb-0 text-primary fw-bold">{{ number_format($totalDiajukan ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rounded-lg border border-separator login-compact-stat h-100">
                            <div class="text-small text-muted">Disetujui</div>
                            <div class="h4 mb-0 text-primary fw-bold">{{ number_format($totalDisetujui ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rounded-lg border border-separator login-compact-stat h-100">
                            <div class="text-small text-muted">Ditolak</div>
                            <div class="h4 mb-0 text-primary fw-bold">{{ number_format($totalDitolak ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-3 pb-3 flex-grow-1 d-flex flex-column min-h-0">
                <div class="rounded-lg login-glossy-table-wrap overflow-hidden d-flex flex-column flex-grow-1 min-h-0">
                    <div class="px-3 py-2 border-bottom border-separator bg-light text-small fw-semibold text-primary">
                        Pengajuan terbaru
                    </div>
                    <div class="table-responsive flex-grow-1">
                        <table class="table login-glossy-table align-middle mb-0">
                            <thead class="text-small text-muted">
                                <tr>
                                    <th>Kode</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($pengajuanTerbaru ?? collect()) as $pengajuan)
                                    <tr>
                                        <td class="text-small fw-semibold">{{ $pengajuan->kode_pengajuan }}</td>
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
                                            <span class="badge {{ $badgeClass }}">
                                                {{ $pengajuan->status?->getDescription() ?? (string) $pengajuan->status }}
                                            </span>
                                        </td>
                                        <td class="text-small text-muted">
                                            {{ $pengajuan->created_at?->translatedFormat('d M Y') ?? '—' }}
                                            <div>Rp {{ number_format((float) ($pengajuan->nilai ?? 0), 0, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada data pengajuan.</td>
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
