<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <label class="block text-sm font-medium mb-1 text-small text-uppercase">Kecamatan</label>
        <div>
            <select id="select-kecamatan" name="kecamatan_id" class="form-control select2">
                <option value="">-- Pilih Kecamatan --</option>
                @foreach($kecamatanOptions as $item)
                    <option value="{{ $item->id }}" {{ $kecamatan === $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        @error('kecamatan_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12">
        <label class="block text-sm font-medium mb-1 text-small text-uppercase">Desa / Kelurahan</label>
        <div id="container-desa">
            <select id="select-desa" name="desa_id" class="form-control select2">
                <option value="">-- Pilih Desa/Kelurahan --</option>
                @foreach($desaOptions as $item)
                    <option value="{{ $item['id'] }}" {{ $desa === $item['id'] ? 'selected' : '' }}>{{ $item['nama'] }}</option>
                @endforeach
            </select>
        </div>
        @error('desa_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
    </div>
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/select2-bootstrap4.min.css') }}" />
@endpush

@push('js_vendor')
    <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
@endpush

@script
<script>
    let isUpdatingFromLivewire = false;

    const initSelect2 = () => {
        const $kecamatan = $('#select-kecamatan');
        const $desa = $('#select-desa');

        if ($kecamatan.data('select2')) {
            $kecamatan.select2('destroy');
        }
        if ($desa.data('select2')) {
            $desa.select2('destroy');
        }

        $kecamatan.select2({
            theme: 'bootstrap4',
            placeholder: '-- Pilih Kecamatan --',
            allowClear: true,
            width: '100%'
        }).on('change', function (e) {
            if (!isUpdatingFromLivewire) {
                $wire.set('kecamatan', e.target.value);
            }
        });

        $desa.select2({
            theme: 'bootstrap4',
            placeholder: '-- Pilih Desa/Kelurahan --',
            allowClear: true,
            width: '100%'
        }).on('change', function (e) {
            if (!isUpdatingFromLivewire) {
                $wire.set('desa', e.target.value);
            }
        });
    }

    const startInit = () => {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            initSelect2();
        } else {
            setTimeout(startInit, 100);
        }
    };

    startInit();

    // Re-initialize Select2 after Livewire updates
    Livewire.hook('morph.updated', () => {
        startInit();
    });

    $wire.on('resync-desa', (event) => {
        const data = Array.isArray(event) ? event[0] : event;
        const desaOptions = data.desaOptions;
        const currentDesa = data.desa;

        isUpdatingFromLivewire = true;

        const $desa = $('#select-desa');

        if ($desa.data('select2')) {
            $desa.select2('destroy');
        }

        $desa.empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>');

        if (desaOptions) {
            desaOptions.forEach(function(item) {
                const option = new Option(item.nama, item.id, false, item.id == currentDesa);
                $desa.append(option);
            });
        }

        $desa.select2({
            theme: 'bootstrap4',
            placeholder: '-- Pilih Desa/Kelurahan --',
            allowClear: true,
            width: '100%'
        }).val(currentDesa).trigger('change.select2');

        setTimeout(() => {
            $desa.on('change', function (e) {
                if (!isUpdatingFromLivewire) {
                    $wire.set('desa', e.target.value);
                }
            });
            isUpdatingFromLivewire = false;
        }, 100);
    });

    $wire.on('set-kecamatan', (event) => {
        const data = Array.isArray(event) ? event[0] : event;
        const kecamatan = data.kecamatan;

        isUpdatingFromLivewire = true;
        const $kecamatan = $('#select-kecamatan');
        $kecamatan.val(kecamatan).trigger('change.select2');

        setTimeout(() => {
            isUpdatingFromLivewire = false;
        }, 100);
    });

    $wire.on('set-desa', (event) => {
        const data = Array.isArray(event) ? event[0] : event;
        const desa = data.desa;

        isUpdatingFromLivewire = true;
        const $desa = $('#select-desa');
        $desa.val(desa).trigger('change.select2');

        setTimeout(() => {
            isUpdatingFromLivewire = false;
        }, 100);
    });
</script>
@endscript
