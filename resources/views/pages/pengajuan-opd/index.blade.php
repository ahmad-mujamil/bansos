@extends('layouts.layout')
@section('title', 'Pengajuan OPD')
@section('content')
<div class="col">
    <div class="page-title-container mb-3">
        <div class="row">
            <div class="col mb-2">
                <h1 class="mb-2 pb-0 display-4">Pengajuan OPD</h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:;">Pengajuan OPD</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                <button type="button" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#modalPilihJenisPengajuanOpd">
                    <i data-acorn-icon="plus"></i>
                    <span>Tambah Pengajuan</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($pengajuan->isEmpty())
                <p class="text-muted mb-0">Belum ada pengajuan OPD.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Kelompok</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengajuan as $p)
                                <tr>
                                    <td>{{ $p->kode_pengajuan }}</td>
                                    <td>{{ $p->organisasi?->nama ?? '-' }}</td>
                                    <td>{{ $p->judul }}</td>
                                    <td><span class="badge bg-{{ $p->status?->badgeColor() ?? 'secondary' }}">{{ $p->status->getDescription() }}</span></td>
                                    <td>{{ $p->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="d-flex gap-1">
                                        <a href="{{ route('pengajuan-opd.show', $p) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                                        @if($p->canEdit())
                                            <a href="{{ route('pengajuan-opd.edit', $p) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        @endif
                                        @if($p->canSubmit())
                                            <form action="{{ route('pengajuan-opd.submit', $p) }}" method="POST" class="form-ajukan-pengajuan">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Ajukan</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="modalPilihJenisPengajuanOpd" tabindex="-1" aria-labelledby="modalPilihJenisPengajuanOpdLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPilihJenisPengajuanOpdLabel">Pilih Jenis Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">Silakan pilih jenis bantuan yang ingin Anda ajukan. Klik kartu untuk melanjutkan.</p>
                    <div class="row g-4">
                        <div class="col-12 col-md-4">
                            <a href="{{ route('pengajuan-opd.create', ['jenis' => 'bansos']) }}" class="text-decoration-none d-block h-100">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                    <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #ea580c 0%, #c2410c 50%, #9a3412 100%); min-height: 200px;">
                                        <div class="p-4 position-relative z-1">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                                <i data-acorn-icon="heart" data-acorn-size="28" class="text-white"></i>
                                            </div>
                                            <h5 class="text-white fw-bold mb-2">Bantuan Sosial</h5>
                                            <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan sosial untuk meringankan beban dan mendukung kebutuhan dasar penerima manfaat.</p>
                                            <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                                Ajukan sekarang
                                                <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                            </span>
                                        </div>
                                        <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                            <i data-acorn-icon="heart" class="text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-4">
                            <a href="{{ route('pengajuan-opd.create', ['jenis' => 'bantuan_kelompok']) }}" class="text-decoration-none d-block h-100">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                    <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #0d9488 0%, #0f766e 50%, #115e59 100%); min-height: 200px;">
                                        <div class="p-4 position-relative z-1">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                                <i data-acorn-icon="grid-1" data-acorn-size="28" class="text-white"></i>
                                            </div>
                                            <h5 class="text-white fw-bold mb-2">Bantuan ke Masyarakat</h5>
                                            <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan untuk program pemberdayaan dan peningkatan kesejahteraan kelompok masyarakat.</p>
                                            <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                                Ajukan sekarang
                                                <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                            </span>
                                        </div>
                                        <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                            <i data-acorn-icon="grid-1" class="text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-4">
                            <a href="{{ route('pengajuan-opd.create', ['jenis' => 'hibah']) }}" class="text-decoration-none d-block h-100">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden menu-bantuan-card" style="border-radius: 16px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                                    <div class="card-body p-0 position-relative" style="background: linear-gradient(145deg, #6366f1 0%, #4f46e5 50%, #4338ca 100%); min-height: 200px;">
                                        <div class="p-4 position-relative z-1">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 mb-3" style="width: 56px; height: 56px; background: rgba(255,255,255,0.25);">
                                                <i data-acorn-icon="gift" data-acorn-size="28" class="text-white"></i>
                                            </div>
                                            <h5 class="text-white fw-bold mb-2">Hibah</h5>
                                            <p class="text-white mb-0 small opacity-90" style="font-size: 0.875rem; line-height: 1.5;">Bantuan hibah untuk mendukung kegiatan atau program yang Anda jalankan.</p>
                                            <span class="d-inline-flex align-items-center mt-3 text-white fw-semibold" style="font-size: 0.9rem;">
                                                Ajukan sekarang
                                                <i data-acorn-icon="chevron-right" data-acorn-size="18" class="ms-1"></i>
                                            </span>
                                        </div>
                                        <div class="position-absolute bottom-0 end-0 opacity-10" style="font-size: 6rem; line-height: 1;">
                                            <i data-acorn-icon="gift" class="text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .menu-bantuan-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
        }
    </style>
</div>
@endsection

@push('js_vendor')
<script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
@endpush

@push('js_page')
<script>
    document.querySelectorAll('form.form-ajukan-pengajuan').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var f = this;
            Swal.fire({
                title: 'Ajukan Pengajuan',
                text: 'Apakah Anda yakin ingin mengajukan pengajuan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, ajukan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) f.submit();
            });
        });
    });
</script>
@endpush
