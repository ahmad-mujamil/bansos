<div class="laporan-pengajuan-list">
    {{-- Filter row --}}
    <div class="row g-2 align-items-center mb-3">
        <div class="col-12 col-md-3" wire:ignore>
            <select id="penduduk-filter-kecamatan" class="form-select form-select-sm" data-placeholder="Semua kecamatan">
                <option value="all" @selected($kecamatanId === 'all')>Semua kecamatan</option>
                @foreach($kecamatanOptions as $kec)
                    <option value="{{ $kec->id }}" @selected($kecamatanId === $kec->id)>{{ $kec->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-4">
            <div class="search-input-container border border-separator bg-foreground search-sm">
                <input type="text"
                       class="form-control form-control-sm"
                       placeholder="{{ $searchPlaceholder }}"
                       wire:model.live.debounce.350ms="search" />
                <span class="search-magnifier-icon"><i data-acorn-icon="search"></i></span>
            </div>
        </div>
    </div>

    {{-- Table + pagination --}}
    @include('livewire.partials.data-list-table', ['items' => $items])

    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
        <small class="text-muted">
            @if($items->total() > 0)
                Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} penduduk
            @else
                Tidak ada penduduk yang menunggu verifikasi NIK
            @endif
        </small>
        <div>{{ $items->onEachSide(1)->links() }}</div>
    </div>
</div>

@script
<script>
    const initPendudukFilterSelect2 = () => {
        if (typeof $ === 'undefined' || !$.fn.select2) {
            setTimeout(initPendudukFilterSelect2, 50);

            return;
        }

        const $sel = $('#penduduk-filter-kecamatan');
        if (! $sel.length) {
            return;
        }

        if (! $sel.hasClass('select2-hidden-accessible')) {
            $sel.select2({
                theme: 'bootstrap4',
                width: '100%',
            });
        }

        $sel.val($wire.get('kecamatanId')).trigger('change.select2');
        $sel.off('change.pendudukFilter').on('change.pendudukFilter', function () {
            $wire.set('kecamatanId', $(this).val());
        });
    };

    initPendudukFilterSelect2();

    Livewire.hook('morph.updated', () => {
        setTimeout(initPendudukFilterSelect2, 0);
    });
</script>
@endscript
