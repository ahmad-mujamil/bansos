<div>
    @if(! $canVerify)
        <div class="alert alert-info mb-0">
            Pengajuan ini sudah diproses. Tidak ada aksi verifikasi yang tersedia.
        </div>
    @else
        <form wire:submit.prevent="submit">
            @if($errors->has('form'))
                <div class="alert alert-danger">
                    {{ $errors->first('form') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="row g-2">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="lulus_kriteria" wire:model="lulus_kriteria">
                                <label class="form-check-label" for="lulus_kriteria">Lulus Kriteria</label>
                            </div>
                            @error('lulus_kriteria') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="lulus_administrasi" wire:model="lulus_administrasi">
                                <label class="form-check-label" for="lulus_administrasi">Lulus Administrasi</label>
                            </div>
                            @error('lulus_administrasi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="lulus_kesesuaian" wire:model="lulus_kesesuaian">
                                <label class="form-check-label" for="lulus_kesesuaian">Lulus Kesesuaian</label>
                            </div>
                            @error('lulus_kesesuaian') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sesuai_program_pemda" wire:model="sesuai_program_pemda">
                                <label class="form-check-label" for="sesuai_program_pemda">Sesuai Program Pemda</label>
                            </div>
                            @error('sesuai_program_pemda') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6 mt-3">
                            <label class="form-label text-muted text-small text-uppercase" for="nilai-rekomendasi-verifikasi">Nilai Rekomendasi</label>
                            <div wire:ignore.self>
                                <input
                                    type="text"
                                    inputmode="decimal"
                                    id="nilai-rekomendasi-verifikasi"
                                    class="form-control formatRupiah"
                                    wire:model.lazy="nilai_rekomendasi"
                                    placeholder="Nilai rekomendasi"
                                    autocomplete="off"
                                >
                            </div>
                            @error('nilai_rekomendasi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 col-lg-6 mt-3">
                            <label class="form-label text-muted text-small text-uppercase" for="select-rupa-bantuan">Rupa Bantuan</label>
                            <div wire:ignore>
                                <select class="form-select" id="select-rupa-bantuan" data-placeholder="- Pilih -">
                                    <option value="">- Pilih -</option>
                                    @foreach($rupaOptions as $rupa)
                                        <option value="{{ $rupa->value }}" @selected($rupa_bantuan === $rupa->value)>{{ $rupa->getDescription() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('rupa_bantuan') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <h3 class="small-title mb-2">Data Pemeriksa (3 orang)</h3>
                    <div class="table-responsive border rounded">
                        <table class="table table-sm table-borderless align-middle mb-0">
                            <thead>
                                <tr class="text-muted text-small text-uppercase border-bottom">
                                    <th class="ps-3 py-2" style="width: 2.5rem;">#</th>
                                    <th class="py-2">Nama</th>
                                    <th class="py-2" style="width: 11rem;">NIP</th>
                                    <th class="pe-3 py-2">Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemeriksa as $index => $row)
                                    <tr wire:key="pemeriksa-row-{{ $index }}" @class(['border-bottom border-light' => ! $loop->last])>
                                        <td class="ps-3 py-2 text-muted small">{{ $index + 1 }}</td>
                                        <td class="py-2">
                                            <label class="visually-hidden" for="pemeriksa-{{ $index }}-nama">Nama pemeriksa {{ $index + 1 }}</label>
                                            <input type="text" id="pemeriksa-{{ $index }}-nama" class="form-control form-control-sm" wire:model="pemeriksa.{{ $index }}.nama" autocomplete="name">
                                            @error("pemeriksa.$index.nama") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </td>
                                        <td class="py-2">
                                            <label class="visually-hidden" for="pemeriksa-{{ $index }}-nip">NIP pemeriksa {{ $index + 1 }}</label>
                                            <input type="text" id="pemeriksa-{{ $index }}-nip" class="form-control form-control-sm" wire:model="pemeriksa.{{ $index }}.nip" autocomplete="off">
                                            @error("pemeriksa.$index.nip") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </td>
                                        <td class="pe-3 py-2">
                                            <label class="visually-hidden" for="pemeriksa-{{ $index }}-jabatan">Jabatan pemeriksa {{ $index + 1 }}</label>
                                            <input type="text" id="pemeriksa-{{ $index }}-jabatan" class="form-control form-control-sm" wire:model="pemeriksa.{{ $index }}.jabatan" autocomplete="organization-title">
                                            @error("pemeriksa.$index.jabatan") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @error('pemeriksa') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-lg-6 mt-3">
                    <label class="form-label text-muted text-small text-uppercase" for="disahkan-oleh">Disahkan Oleh</label>
                    <input
                        type="text"
                        id="disahkan-oleh"
                        class="form-control"
                        wire:model="disahkan_oleh"
                        placeholder="Nama kepala OPD"
                    >
                    @error('disahkan_oleh') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-lg-6 mt-3">
                    <label class="form-label text-muted text-small text-uppercase" for="lokasi-pengesahan">Lokasi Pengesahan</label>
                    <input
                        type="text"
                        id="lokasi-pengesahan"
                        class="form-control"
                        wire:model="lokasi_pengesahan"
                    >
                    @error('lokasi_pengesahan') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label text-muted text-small text-uppercase">Catatan</label>
                    <textarea class="form-control" wire:model="catatan" rows="4" placeholder="Tuliskan catatan/verifikasi untuk pengaju"></textarea>
                    @error('catatan') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                @if($rupa_bantuan === \App\Enums\RupaBantuan::UANG->value)
                    <div class="col-12 mt-4">
                        <h3 class="small-title mb-2">Detail Bantuan Uang</h3>
                        @if($pengajuan->details->isEmpty())
                            <div class="alert alert-warning mb-0">Belum ada data `PengajuanDetail` untuk pengajuan ini.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Penduduk</th>
                                        <th style="width: 240px;">Nilai</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pengajuan->details as $row)
                                        <tr>
                                            <td>{{ $row->penduduk?->nama ?? $row->penduduk_id }}</td>
                                            <td>
                                                <input type="hidden" wire:model="detail.{{ $row->id }}.penduduk_id">
                                                <input type="number" min="0" step="0.01" class="form-control" wire:model="detail.{{ $row->id }}.nilai">
                                                @error("detail.$row->id.nilai") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif

                @if(in_array($rupa_bantuan, [\App\Enums\RupaBantuan::BARANG->value, \App\Enums\RupaBantuan::JASA->value], true))
                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h3 class="small-title mb-0">Detail Barang / Jasa</h3>
                            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addItem">
                                Tambah Item
                            </button>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            @foreach($items as $index => $item)
                                <div class="border rounded p-2" wire:key="verifikasi-item-{{ $index }}">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                        <div class="small text-muted fw-semibold mb-0">Item #{{ $index + 1 }}</div>
                                        <button
                                            type="button"
                                            class="btn btn-outline-danger btn-sm py-0 px-2"
                                            wire:click="removeItem({{ $index }})"
                                            @disabled(count($items) === 1)
                                        >
                                            Hapus
                                        </button>
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-12 col-lg-3">
                                            <label class="form-label text-muted text-small text-uppercase mb-0">Nama Barang / Jasa</label>
                                            <input type="text" class="form-control form-control-sm mt-1" wire:model="items.{{ $index }}.nama_barang">
                                            @error("items.$index.nama_barang") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label class="form-label text-muted text-small text-uppercase mb-0" for="select-satuan-item-{{ $index }}">Satuan</label>
                                            <div wire:ignore class="mt-1">
                                                <select
                                                    class="form-select form-select-sm select-satuan-item"
                                                    id="select-satuan-item-{{ $index }}"
                                                    data-item-index="{{ $index }}"
                                                    data-placeholder="Pilih satuan"
                                                >
                                                    <option value="">- Pilih -</option>
                                                    @foreach($satuanOptions as $satuan)
                                                        <option value="{{ $satuan->value }}" @selected(($item['satuan'] ?? null) === $satuan->value)>{{ $satuan->getDescription() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error("items.$index.satuan") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-6 col-lg-1">
                                            <label class="form-label text-muted text-small text-uppercase mb-0">Qty</label>
                                            <input type="number" min="1" step="1" class="form-control form-control-sm mt-1" wire:model="items.{{ $index }}.qty">
                                            @error("items.$index.qty") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <label class="form-label text-muted text-small text-uppercase mb-0">Harga Satuan</label>
                                            <div wire:ignore class="mt-1">
                                                <input
                                                    type="text"
                                                    inputmode="decimal"
                                                    class="form-control form-control-sm formatRupiah item-harga-satuan"
                                                    wire:model.lazy="items.{{ $index }}.harga_satuan"
                                                    placeholder="0,00"
                                                    autocomplete="off"
                                                >
                                            </div>
                                            @error("items.$index.harga_satuan") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <label class="form-label text-muted text-small text-uppercase mb-0">Spesifikasi</label>
                                            <textarea class="form-control form-control-sm mt-1" wire:model="items.{{ $index }}.spesifikasi" rows="2"></textarea>
                                            @error("items.$index.spesifikasi") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @error('items') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                @endif

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                        <span wire:loading.remove>Verifikasi</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>
        </form>
    @endif
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
    const initRupaBantuanSelect2 = () => {
        const $sel = $('#select-rupa-bantuan');
        if (!$sel.length || $sel.data('select2')) {
            return;
        }

        $sel.select2({
            theme: 'bootstrap4',
            placeholder: $sel.data('placeholder') || '- Pilih -',
            allowClear: true,
            width: '100%',
        });

        $sel.on('change.rupaBantuan', function () {
            const v = $(this).val();

            $wire.set('rupa_bantuan', v && v !== '' ? v : null);
        });
    };

    const initItemSatuanSelect2s = () => {
        document.querySelectorAll('select.select-satuan-item').forEach(function (el) {
            const $el = $(el);
            if ($el.hasClass('select2-hidden-accessible')) {
                return;
            }

            const idx = $el.data('item-index');
            if (idx === undefined) {
                return;
            }

            $el.select2({
                theme: 'bootstrap4',
                placeholder: $el.data('placeholder') || 'Pilih satuan',
                allowClear: true,
                width: '100%',
            });

            $el.on('change.satuanItem', function () {
                const v = $(this).val();

                $wire.set('items.' + idx + '.satuan', v && v !== '' ? v : null);
            });
        });
    };

    const initItemHargaMasks = () => {
        if (typeof IMask === 'undefined') {
            return;
        }

        document.querySelectorAll('input.item-harga-satuan.formatRupiah').forEach(function (el) {
            if (el._lwImask) {
                el._lwImask.destroy();
                el._lwImask = null;
            }

            el._lwImask = IMask(el, {
                mask: Number,
                scale: 2,
                thousandsSeparator: ',',
                min: 0,
                radix: '.',
            });
        });
    };

    const initVerifikasiFormUi = () => {
        if (typeof $ === 'undefined' || !$.fn.select2) {
            setTimeout(initVerifikasiFormUi, 50);

            return;
        }

        initRupaBantuanSelect2();
        initItemSatuanSelect2s();

        if (typeof IMask === 'undefined') {
            setTimeout(initVerifikasiFormUi, 50);

            return;
        }

        initItemHargaMasks();
    };

    initVerifikasiFormUi();

    Livewire.hook('morph.updated', () => {
        setTimeout(initVerifikasiFormUi, 10);
    });
</script>
@endscript
