@if(session()->has('impersonator_id'))
    <div class="alert alert-warning d-flex align-items-center justify-content-between mb-3 py-2"
         style="position: sticky; top: 0; z-index: 1050; border-left: 4px solid #f59e0b;">
        <div class="d-flex align-items-center gap-2">
            <i data-acorn-icon="shield"></i>
            <div class="small">
                <strong>Mode Impersonasi.</strong>
                Anda sedang login sebagai
                <strong>{{ auth()->user()->nama ?? auth()->user()->email }}</strong>
                ({{ auth()->user()->role?->getDescription() }}).
            </div>
        </div>
        <form method="POST" action="{{ route('pengguna.stop-impersonating') }}" class="mb-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-warning">Keluar Mode Impersonasi</button>
        </form>
    </div>
@endif
