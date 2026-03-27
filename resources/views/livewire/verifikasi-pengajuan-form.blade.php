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
                            <label class="form-label text-muted text-small text-uppercase">Nilai Rekomendasi</label>
                            <input type="number" step="0.01" min="0" class="form-control" wire:model="nilai_rekomendasi" placeholder="niai rekomendasi">
                            @error('nilai_rekomendasi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 col-lg-6 mt-3">
                            <label class="form-label text-muted text-small text-uppercase">Rupa Bantuan</label>
                            <select class="form-select" wire:model.live="rupa_bantuan">
                                <option value="">- Pilih -</option>
                                @foreach($rupaOptions as $rupa)
                                    <option value="{{ $rupa->value }}">{{ $rupa->getDescription() }}</option>
                                @endforeach
                            </select>
                            @error('rupa_bantuan') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
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

                        <div class="d-flex flex-column gap-3">
                            @foreach($items as $index => $item)
                                <div class="border rounded p-3">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div class="small-title mb-0">Item #{{ $index + 1 }}</div>
                                        <button
                                            type="button"
                                            class="btn btn-outline-danger btn-sm"
                                            wire:click="removeItem({{ $index }})"
                                            @disabled(count($items) === 1)
                                        >
                                            Hapus
                                        </button>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-12 col-lg-3 mb-3">
                                            <label class="form-label text-muted text-small text-uppercase">Nama Barang / Jasa</label>
                                            <input type="text" class="form-control" wire:model="items.{{ $index }}.nama_barang">
                                            @error("items.$index.nama_barang") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12 col-lg-2 mb-3">
                                            <label class="form-label text-muted text-small text-uppercase">Satuan</label>
                                            <input type="text" class="form-control" wire:model="items.{{ $index }}.satuan" placeholder="pcs / paket / hari">
                                            @error("items.$index.satuan") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12 col-lg-1 mb-3">
                                            <label class="form-label text-muted text-small text-uppercase">Qty</label>
                                            <input type="number" min="1" step="1" class="form-control" wire:model="items.{{ $index }}.qty">
                                            @error("items.$index.qty") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12 col-lg-2 mb-3">
                                            <label class="form-label text-muted text-small text-uppercase">Harga Satuan</label>
                                            <input type="number" min="0" step="0.01" class="form-control" wire:model="items.{{ $index }}.harga_satuan">
                                            @error("items.$index.harga_satuan") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12 col-lg-4 mb-0">
                                            <label class="form-label text-muted text-small text-uppercase">Spesifikasi</label>
                                            <textarea class="form-control" wire:model="items.{{ $index }}.spesifikasi" rows="3"></textarea>
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

