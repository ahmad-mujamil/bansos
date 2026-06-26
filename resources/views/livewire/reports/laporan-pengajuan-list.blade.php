<div class="laporan-pengajuan-list">
    {{-- Tab kategori --}}
    <div class="laporan-tabs d-flex flex-wrap gap-3 mb-4" role="tablist">
        @php
            $tabMeta = [
                'all'                                               => ['class' => 'laporan-tab-all',      'icon' => 'layout-3',  'title' => 'Semua',           'sub' => 'Semua Jenis Pengajuan'],
                \App\Enums\JenisPengajuan::BANSOS->value             => ['class' => 'laporan-tab-bansos',   'icon' => 'user',     'title' => 'Bansos',           'sub' => 'Bantuan Sosial'],
                \App\Enums\JenisPengajuan::HIBAH->value              => ['class' => 'laporan-tab-hibah',    'icon' => 'gift',     'title' => 'Hibah',            'sub' => 'Bantuan Hibah'],
                \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK->value   => ['class' => 'laporan-tab-kelompok', 'icon' => 'building', 'title' => 'Bantuan Kelompok', 'sub' => 'Barang Diserahkan ke Masyarakat'],
            ];
        @endphp
        @foreach($tabMeta as $value => $meta)
            <button type="button"
                wire:click="setKategori('{{ $value }}')"
                class="laporan-tab {{ $meta['class'] }} {{ $kategori === $value ? 'active' : '' }}"
                role="tab"
                aria-selected="{{ $kategori === $value ? 'true' : 'false' }}">
                <span class="laporan-tab-icon"><i data-acorn-icon="{{ $meta['icon'] }}"></i></span>
                <span class="laporan-tab-label">
                    <span class="laporan-tab-title">{{ $meta['title'] }}</span>
                    <span class="laporan-tab-sub">{{ $meta['sub'] }}</span>
                </span>
            </button>
        @endforeach
    </div>

    {{-- Filter row --}}
    <div class="row g-2 align-items-center mb-3">
        @if($this->showOpdFilter())
            <div class="col-12 col-md-3" wire:ignore>
                <select id="laporan-filter-opd" class="form-select form-select-sm" data-placeholder="Semua OPD">
                    <option value="all" @selected($opd === 'all')>Semua OPD</option>
                    @foreach($opdOptions as $op)
                        <option value="{{ $op->id }}" @selected($opd === $op->id)>{{ $op->nama }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="col-12 col-md-{{ $this->showOpdFilter() ? '2' : '3' }}">
            <select class="form-select form-select-sm" wire:model.live="status">
                <option value="all">Semua status</option>
                @foreach($statusOptions as $st)
                    <option value="{{ $st->value }}">{{ $st->getDescription() }}</option>
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
        <div class="col-12 col-md-3 text-md-end">
            <a href="{{ route('laporan-pengajuan.export', ['kategori' => $kategori, 'status' => $status, 'opd' => $opd]) }}"
               class="btn btn-sm btn-success">
                <i data-acorn-icon="download" class="me-1"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- Table + pagination --}}
    @include('livewire.partials.data-list-table', [
        'items' => $items,
        'inlineExpand' => true,
        'expandedId' => $expandedId,
        'inlineExpandView' => 'livewire.reports.partials.pengajuan-anggota',
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
    const initLaporanOpdSelect2 = () => {
        if (typeof $ === 'undefined' || !$.fn.select2) {
            setTimeout(initLaporanOpdSelect2, 50);

            return;
        }

        const $sel = $('#laporan-filter-opd');
        if (! $sel.length) {
            return;
        }

        if (! $sel.hasClass('select2-hidden-accessible')) {
            $sel.select2({
                theme: 'bootstrap4',
                width: '100%',
            });
        }

        $sel.val($wire.get('opd')).trigger('change.select2');
        $sel.off('change.laporanOpd').on('change.laporanOpd', function () {
            $wire.set('opd', $(this).val());
        });
    };

    initLaporanOpdSelect2();

    Livewire.hook('morph.updated', () => {
        setTimeout(initLaporanOpdSelect2, 0);
    });
</script>
@endscript
