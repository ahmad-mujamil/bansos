@push('js_vendor')
    <script src="{{ asset('js/vendor/imask.js') }}"></script>
    <script>
        _initMaskInMask();
        function _initMaskInMask() {
            if (document.querySelectorAll('.formatRupiah').length > 0) {
                document.querySelectorAll('.formatRupiah').forEach(element => {
                    IMask(element, {
                        mask: 'num',
                        blocks: {
                            num: {
                                mask: Number,
                                thousandsSeparator: ',',
                            },
                        },
                    });
                });
            }

            if (document.querySelectorAll('.desimal').length > 0) {
                document.querySelectorAll('.desimal').forEach(element => {
                    IMask(element, {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: ',',
                        min : 0,
                        radix: '.',
                    });
                });

                document.querySelectorAll('.persentase').forEach(element => {
                    IMask(element, {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: ',',
                        min : 0,
                        radix: '.',
                        max : 100,
                    });
                });
            }
        }
    </script>
@endpush
