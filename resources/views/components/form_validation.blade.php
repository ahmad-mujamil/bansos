@push('js_page')
    <script>
        _initBootstrapValidation();
        function _initBootstrapValidation() {

            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener(
                    'submit',
                    function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    },
                    false,
                );
            });
        }
    </script>
@endpush
