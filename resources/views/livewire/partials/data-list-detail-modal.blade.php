@php
    $sections = [];
    if ($selectedRow) {
        foreach ($detailFields as $col) {
            $section = $col['section'] ?? 'Detail';
            $sections[$section][] = $col;
        }
    }
@endphp

<div class="modal fade data-list-detail-modal"
     tabindex="-1"
     id="data-list-detail-modal"
     wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg detail-modal-content">
            <div class="detail-modal-hero">
                <div class="detail-modal-hero-bg"></div>
                <div class="detail-modal-hero-content">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 text-uppercase small fw-bold mb-1" style="letter-spacing: 0.08em;">
                                Detail Data
                            </div>
                            <h4 class="text-white fw-bold mb-0">
                                {{ $title !== '' ? $title : 'Informasi Lengkap' }}
                            </h4>
                        </div>
                        <button type="button"
                                class="btn btn-light btn-sm rounded-circle detail-modal-close"
                                data-bs-dismiss="modal"
                                wire:click="closeDetail"
                                aria-label="Close">
                            <i data-acorn-icon="close"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-body detail-modal-body">
                @if($selectedRow)
                    @foreach($sections as $section => $fields)
                        <div class="detail-section">
                            <div class="detail-section-header">
                                <span class="detail-section-badge"></span>
                                <span class="detail-section-title">{{ $section }}</span>
                            </div>
                            <div class="row g-3">
                                @foreach($fields as $col)
                                    @php
                                        $width = $col['width'] ?? 'half';
                                        $colClass = $width === 'full' ? 'col-12' : 'col-12 col-md-6';
                                    @endphp
                                    <div class="{{ $colClass }}">
                                        <div class="detail-item">
                                            <div class="detail-item-label">{{ $col['label'] ?? $col['key'] }}</div>
                                            <div class="detail-item-value">{!! $this->formatValue($selectedRow, $col) !!}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-5">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <div>Memuat data…</div>
                    </div>
                @endif
            </div>

            <div class="modal-footer border-0 detail-modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="closeDetail">
                    <i class="me-1" data-acorn-icon="close"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@once
    @push('css')
        <style>
            .detail-modal-content {
                border-radius: 1rem;
                overflow: hidden;
                background: #ffffff;
            }
            .detail-modal-hero {
                position: relative;
                color: #ffffff;
                overflow: hidden;
            }
            .detail-modal-hero-bg {
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, #4f46e5 0%, #6366f1 45%, #8b5cf6 100%);
            }
            .detail-modal-hero-bg::after {
                content: '';
                position: absolute;
                inset: 0;
                background:
                    radial-gradient(circle at 85% 20%, rgba(255,255,255,0.18) 0, transparent 40%),
                    radial-gradient(circle at 15% 90%, rgba(255,255,255,0.10) 0, transparent 45%);
            }
            .detail-modal-hero-content {
                position: relative;
                padding: 1.4rem 1.75rem;
            }
            .detail-modal-close {
                width: 36px;
                height: 36px;
                padding: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 0;
                background: rgba(255, 255, 255, 0.95);
                color: #1e293b;
            }
            .detail-modal-close i { width: 16px; height: 16px; }
            .detail-modal-body {
                background: #f8fafc;
                padding: 1.5rem 1.75rem;
            }
            .detail-modal-footer {
                background: #ffffff;
                padding: 0.85rem 1.75rem;
            }
            .detail-section {
                background: #ffffff;
                border-radius: 0.85rem;
                padding: 1.15rem 1.35rem;
                border: 1px solid #eef2f7;
                box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
                margin-bottom: 1rem;
            }
            .detail-section:last-child { margin-bottom: 0; }
            .detail-section-header {
                display: flex;
                align-items: center;
                gap: 0.7rem;
                margin-bottom: 1rem;
                padding-bottom: 0.6rem;
                border-bottom: 1px dashed #e5e7eb;
            }
            .detail-section-badge {
                display: inline-block;
                width: 5px;
                height: 22px;
                border-radius: 4px;
                background: linear-gradient(180deg, #4f46e5, #8b5cf6);
            }
            .detail-section-title {
                font-weight: 700;
                font-size: 0.86rem;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                color: #1e293b;
            }
            .detail-item-label {
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #64748b;
                margin-bottom: 0.3rem;
                font-weight: 600;
            }
            .detail-item-value {
                font-size: 0.95rem;
                color: #0f172a;
                font-weight: 600;
                line-height: 1.4;
                word-break: break-word;
            }
            .detail-item-value .badge {
                font-size: 0.78rem;
                padding: 0.45em 0.75em;
                font-weight: 600;
            }
            .detail-item-value .text-success { color: #059669 !important; }
            .detail-item-value .text-danger { color: #dc2626 !important; }
        </style>
    @endpush
@endonce

@script
<script>
    const rehydrateAcornIcons = () => {
        if (typeof AcornIcons !== 'undefined') {
            try { new AcornIcons().replace(); } catch (e) { /* noop */ }
        }
    };

    const getModalEl = () => document.getElementById('data-list-detail-modal');

    const getOrCreateModal = () => {
        const el = getModalEl();
        if (!el || !window.bootstrap) return null;
        return window.bootstrap.Modal.getOrCreateInstance(el);
    };

    const showDataListModal = () => {
        const modal = getOrCreateModal();
        if (modal) {
            modal.show();
            setTimeout(rehydrateAcornIcons, 60);
        }
    };

    const hideDataListModal = () => {
        const modal = getOrCreateModal();
        if (modal) modal.hide();
    };

    const modalEl = getModalEl();
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', () => {
            if ($wire.selectedId !== null && $wire.selectedId !== undefined) {
                $wire.closeDetail();
            }
        });
    }

    $wire.on('data-list-detail:show', () => showDataListModal());
    $wire.on('data-list-detail:hide', () => hideDataListModal());

    Livewire.hook('morph.updated', () => {
        setTimeout(rehydrateAcornIcons, 10);
    });

    setTimeout(rehydrateAcornIcons, 30);
</script>
@endscript
