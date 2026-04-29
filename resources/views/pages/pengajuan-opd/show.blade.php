@extends('layouts.layout')
@section('title', 'Detail Pengajuan OPD')
@section('content')
<div class="col">
    <div class="page-title-container mb-3">
        <div class="row">
            <div class="col mb-2">
                <h1 class="mb-2 pb-0 display-4">Detail Pengajuan OPD</h1>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-end gap-2">
                @if($pengajuan->canEdit())
                    <a href="{{ route('pengajuan-opd.edit', $pengajuan) }}" class="btn btn-outline-primary">Edit</a>
                @endif
                @if($pengajuan->canSubmit())
                    <form action="{{ route('pengajuan-opd.submit', $pengajuan) }}" method="POST" class="form-ajukan-pengajuan">
                        @csrf
                        <button type="submit" class="btn btn-success">Ajukan</button>
                    </form>
                @endif
                <a href="{{ route('pengajuan-opd.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small">Kode</div>
                    <div class="fw-semibold">{{ $pengajuan->kode_pengajuan }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Status</div>
                    <div><span class="badge bg-{{ $pengajuan->status?->badgeColor() ?? 'secondary' }}">{{ $pengajuan->status->getDescription() }}</span></div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Jenis penerima bantuan</div>
                    <div class="fw-semibold">{{ $pengajuan->jenis_penerima_bantuan?->getDescription() ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Kelompok</div>
                    <div class="fw-semibold">{{ $pengajuan->organisasi?->nama ?? '-' }}</div>
                </div>
                @if($pengajuan->details->isNotEmpty() && $pengajuan->details->first()?->penduduk)
                <div class="col-md-6">
                    <div class="text-muted small">Penduduk (rincian)</div>
                    <div class="fw-semibold">
                        {{ $pengajuan->details->first()->penduduk->nama }}
                        <span class="text-muted">— NIK {{ $pengajuan->details->first()->penduduk->nik }}</span>
                    </div>
                </div>
                @endif
                <div class="col-md-6">
                    <div class="text-muted small">Nilai</div>
                    <div class="fw-semibold">Rp {{ number_format($pengajuan->nilai, 0, ',', '.') }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Judul</div>
                    <div class="fw-semibold">{{ $pengajuan->judul }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small">Lokasi</div>
                    <div>{{ $pengajuan->lokasi ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Desa</div>
                    <div>{{ $pengajuan->desa?->nama ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Kecamatan</div>
                    <div>{{ $pengajuan->desa?->kecamatan?->nama ?? '-' }}</div>
                </div>
            </div>
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
