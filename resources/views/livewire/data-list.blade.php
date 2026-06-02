<div class="data-list">
    @if($title !== '' || $showSearch)
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            @if($title !== '')
                <h5 class="mb-0 fw-bold">{{ $title }}</h5>
            @else
                <div></div>
            @endif

            @if($showSearch)
                <div class="search-input-container border border-separator bg-foreground search-sm" style="min-width: 240px;">
                    <input class="form-control form-control-sm"
                           placeholder="{{ $searchPlaceholder }}"
                           wire:model.live.debounce.350ms="search" />
                    <span class="search-magnifier-icon">
                        <i data-acorn-icon="search"></i>
                    </span>
                </div>
            @endif
        </div>
    @endif

    @include('livewire.partials.data-list-table', ['items' => $items])

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

    @if($enableDetail)
        @include('livewire.partials.data-list-detail-modal', ['selectedRow' => $selectedRow])
    @endif
</div>
