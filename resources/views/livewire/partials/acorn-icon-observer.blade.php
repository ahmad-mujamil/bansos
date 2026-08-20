{{--
    Re-render acorn icons whenever new <i data-acorn-icon> tags appear in the
    DOM. Livewire morphs swap already-rendered <svg> icons back to raw
    <i data-acorn-icon> tags, which the icon library only processes once at page
    load. A MutationObserver re-applies them regardless of Livewire's hook
    names/timing.

    Dipakai bersama oleh data-list-table (laporan/dashboard) dan halaman
    Livewire lain yang menampilkan ikon acorn, mis. monitoring bantuan.
--}}
@once
    @push('js_page')
        <script>
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
