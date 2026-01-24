<div class="space-y-3">
    <div wire:ignore>
        <label class="block text-sm font-medium mb-1">Kecamatan</label>
        <select id="select-kecamatan" class="form-control select2">
            <option value="">-- Pilih Kecamatan --</option>
            @foreach($kecamatanOptions as $item)
                <option value="{{ $item->id }}" {{ $kecamatan == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
            @endforeach
        </select>
        @error('kecamatan') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <div wire:ignore>
        <label class="block text-sm font-medium mb-1">Desa / Kelurahan</label>
        <select id="select-desa" class="form-control select2" @disabled(empty($desaOptions))>
            <option value="">-- Pilih Desa/Kelurahan --</option>
            @foreach($desaOptions as $item)
                <option value="{{ $item['id'] }}" {{ $desa == $item['id'] ? 'selected' : '' }}>{{ $item['nama'] }}</option>
            @endforeach
        </select>
        @error('desa') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}" />
@endpush

@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
@endpush

@push('js_page')
<script>
    $(document).ready(function() {
        function initSelect2() {
            $('#select-kecamatan').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih Kecamatan --',
                allowClear: true
            }).on('change', function (e) {
                @this.set('kecamatan', e.target.value);
            });

            $('#select-desa').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih Desa/Kelurahan --',
                allowClear: true
            }).on('change', function (e) {
                @this.set('desa', e.target.value);
            });
        }

        initSelect2();

        Livewire.on('resyncSelect2', () => {
            $('#select-desa').empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>');
            @this.desaOptions.forEach(function(item) {
                $('#select-desa').append(new Option(item.nama, item.id, false, false));
            });
            $('#select-desa').trigger('change');
        });
    });
</script>
@endpush
