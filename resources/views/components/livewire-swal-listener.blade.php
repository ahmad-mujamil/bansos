<script>
    (function () {
        var sweetAlertScriptUrl = @json(asset('vendor/sweetalert/sweetalert.all.js'));

        var ensureSwalLoaded = function () {
            return new Promise(function (resolve) {
                if (typeof Swal !== 'undefined') {
                    resolve(true);
                    return;
                }

                var existing = document.querySelector('script[data-swal-global="1"]');
                if (existing) {
                    existing.addEventListener('load', function () {
                        resolve(typeof Swal !== 'undefined');
                    });
                    existing.addEventListener('error', function () {
                        resolve(false);
                    });
                    return;
                }

                var script = document.createElement('script');
                script.src = sweetAlertScriptUrl;
                script.async = true;
                script.dataset.swalGlobal = '1';
                script.addEventListener('load', function () {
                    resolve(typeof Swal !== 'undefined');
                });
                script.addEventListener('error', function () {
                    resolve(false);
                });
                document.head.appendChild(script);
            });
        };

        var attach = function () {
            if (typeof Livewire === 'undefined') {
                return;
            }

            if (window.__livewireSwalListenerAttached) {
                return;
            }

            window.__livewireSwalListenerAttached = true;

            Livewire.on('app-swal', function (payload) {
                var title = payload.title || 'Informasi';
                var text = payload.text || '';
                var icon = payload.icon || 'info';
                var timer = payload.timer || 2500;

                ensureSwalLoaded().then(function (isReady) {
                    if (!isReady || typeof Swal === 'undefined') {
                        alert(title + (text ? '\n' + text : ''));
                        return;
                    }

                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: text,
                        timer: timer,
                        showConfirmButton: false,
                    });
                });
            });
        };

        if (typeof Livewire !== 'undefined') {
            attach();
        } else {
            document.addEventListener('livewire:load', attach);
        }
    })();
</script>
