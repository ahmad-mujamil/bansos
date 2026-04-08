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
                <a href="{{ route('pengajuan-opd.create') }}" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto">
                    <i data-acorn-icon="plus"></i>
                    <span>Tambah Pengajuan</span>
                </a>
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
