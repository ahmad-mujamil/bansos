@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
    <script>
       $('#select2Ajax').select2({
            ajax: {
                url: {{ $url }},
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: {value: params.term},
                        page: params.page,
                    };
                },
                processResults: function (data, page) {
                    return {
                        results: data.data,
                    };
                },
                cache: true,
            },
            placeholder: 'Search',
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 1,
            templateResult: function formatResult(result) {
                if (result.loading) return result.text;
                let markup = '<div class="clearfix"><div>' + result.nama + '</div>';
                if (result.keterangan) {
                    markup += '<div class="text-muted">' + result.keterangan + '</div>';
                }
                return markup;
            },
            templateSelection: function formatResultSelection(result) {
                return result.Name;
            },
       });
    </script>
@endpush
