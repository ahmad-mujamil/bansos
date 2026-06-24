@php
    $inlineExpand = $inlineExpand ?? false;
    $expandedId = $expandedId ?? null;
    $inlineExpandView = $inlineExpandView ?? null;
@endphp
<div class="table-responsive">
    <table class="table table-hover align-middle data-list-table mb-0">
        <thead>
            <tr>
                @foreach($columns as $col)
                    @php $isSortable = (bool) ($col['sortable'] ?? false); @endphp
                    <th class="text-muted text-small text-uppercase {{ $isSortable ? 'data-list-sortable' : '' }}"
                        @if($isSortable) wire:click="sortBy('{{ $col['key'] }}')" @endif>
                        {{ $col['label'] ?? $col['key'] }}
                        @if($isSortable && $sortColumn === $col['key'])
                            <i class="ms-1 text-primary" data-acorn-icon="{{ $sortDirection === 'asc' ? 'arrow-top' : 'arrow-bottom' }}"></i>
                        @endif
                    </th>
                @endforeach
                @if($enableDetail)
                    <th class="text-muted text-small text-uppercase text-end" style="width: 1%">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-alternate text-medium">
            @forelse($items as $row)
                @php
                    $rowKey = $row->getKey();
                    $detailHref = ($enableDetail && $detailRoute !== '') ? route($detailRoute, $rowKey) : null;
                    $isExpanded = $inlineExpand && (string) $expandedId === (string) $rowKey;
                @endphp
                <tr class="data-list-row {{ $inlineExpand ? 'data-list-expandable' : '' }} {{ $isExpanded ? 'shown' : '' }}"
                    @if($inlineExpand)
                        wire:click="toggleAnggota('{{ $rowKey }}')"
                    @elseif($detailHref)
                        data-href="{{ $detailHref }}"
                    @elseif($enableDetail)
                        wire:click="showDetail('{{ $rowKey }}')"
                    @endif>
                    @foreach($columns as $col)
                        <td>{!! $this->formatValue($row, $col) !!}</td>
                    @endforeach
                    @if($enableDetail)
                        <td class="text-end">
                            @if($detailHref)
                                <a href="{{ $detailHref }}"
                                   class="btn btn-sm btn-outline-primary"
                                   onclick="event.stopPropagation()">
                                    Detail
                                </a>
                            @else
                                <button type="button"
                                        wire:click.stop="showDetail('{{ $rowKey }}')"
                                        class="btn btn-sm btn-outline-primary">
                                    Detail
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
                @if($isExpanded && $inlineExpandView)
                    <tr class="data-list-detail-row">
                        <td colspan="{{ count($columns) + ($enableDetail ? 1 : 0) }}" class="p-0 border-0">
                            @include($inlineExpandView, ['row' => $row])
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ count($columns) + ($enableDetail ? 1 : 0) }}"
                        class="text-center text-muted py-4">
                        Tidak ada data ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@once
    @push('css')
        <style>
            .data-list-table .data-list-sortable { cursor: pointer; user-select: none; }
            .data-list-table .data-list-sortable:hover { color: #1d4ed8 !important; }
            .data-list-table .data-list-row { transition: background 0.15s ease; }
            .data-list-table .data-list-row[wire\:click],
            .data-list-table .data-list-row[data-href] { cursor: pointer; }
            .data-list-table .data-list-expandable > td:first-child::before {
                content: "\25B8"; /* ▸ */
                display: inline-block;
                margin-right: 0.45rem;
                color: #94a3b8;
                transition: transform 0.15s ease, color 0.15s ease;
            }
            .data-list-table .data-list-expandable.shown > td:first-child::before {
                content: "\25B8";
                transform: rotate(90deg);
                color: var(--bs-primary, #0d6efd);
            }
            .data-list-table .data-list-detail-row > td { background: #f8f9fa; }
        </style>
    @endpush
    @push('js_page')
        <script>
            document.addEventListener('click', function (e) {
                const row = e.target.closest('.data-list-row[data-href]');
                if (!row) return;
                if (e.target.closest('a, button, input, label, .btn')) return;
                window.location.href = row.dataset.href;
            });

            // Re-render acorn icons whenever new <i data-acorn-icon> tags appear
            // in the DOM. Livewire morphs swap already-rendered <svg> icons back
            // to raw <i data-acorn-icon> tags, which the icon library only
            // processes once at page load. A MutationObserver re-applies them
            // regardless of Livewire's hook names/timing.
            (function () {
                if (window.__acornIconObserver) return; // guard against duplicate setup
                var scheduled = false;
                function reapplyIcons() {
                    scheduled = false;
                    if (typeof AcornIcons !== 'undefined') {
                        new AcornIcons().replace();
                    }
                }
                function schedule() {
                    if (scheduled) return;
                    scheduled = true;
                    window.requestAnimationFrame(reapplyIcons);
                }
                var observer = new MutationObserver(function (mutations) {
                    for (var i = 0; i < mutations.length; i++) {
                        var added = mutations[i].addedNodes;
                        for (var j = 0; j < added.length; j++) {
                            var node = added[j];
                            if (node.nodeType !== 1) continue;
                            if ((node.matches && node.matches('[data-acorn-icon]')) ||
                                (node.querySelector && node.querySelector('[data-acorn-icon]'))) {
                                schedule();
                                return;
                            }
                        }
                    }
                });
                observer.observe(document.body, { childList: true, subtree: true });
                window.__acornIconObserver = observer;
            })();
        </script>
    @endpush
@endonce
