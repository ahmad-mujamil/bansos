<div class="laporan-pengajuan-list">
    {{-- Filter row --}}
    <div class="row g-2 align-items-center mb-3">
        <div class="col-12 col-md-4" wire:ignore>
            <select id="organisasi-filter-kecamatan" class="form-select form-select-sm" data-placeholder="Semua kecamatan">
                <option value="all" @selected($kecamatanId === 'all')>Semua kecamatan</option>
                @foreach($kecamatanOptions as $kec)
                    <option value="{{ $kec->id }}" @selected($kecamatanId === $kec->id)>{{ $kec->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select class="form-select form-select-sm" wire:model.live="status">
                <option value="all">Semua status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
                <option value="blacklist">Blacklist</option>
            </select>
        </div>
        <div class="col-6 col-md-5">
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
    @include('livewire.partials.data-list-table', [
        'items' => $items,
        'inlineExpand' => true,
        'expandedId' => $expandedId,
        'inlineExpandView' => 'livewire.reports.partials.organisasi-anggota',
    ])

    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
        <small class="text-muted">
            @if($items->total() > 0)
                Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} data
            @else
                Tidak ada data
            @endif
        </small>
        <div>{{ $items->onEachSide(1)->links() }}</div>
    </div>
</div>

@script
<script>
    const initOrganisasiKecamatanSelect2 = () => {
        if (typeof $ === 'undefined' || !$.fn.select2) {
            setTimeout(initOrganisasiKecamatanSelect2, 50);

            return;
        }

        const $sel = $('#organisasi-filter-kecamatan');
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
        $sel.off('change.organisasiKecamatan').on('change.organisasiKecamatan', function () {
            $wire.set('kecamatanId', $(this).val());
        });
    };

    initOrganisasiKecamatanSelect2();

    Livewire.hook('morph.updated', () => {
        setTimeout(initOrganisasiKecamatanSelect2, 0);
    });
</script>
@endscript
