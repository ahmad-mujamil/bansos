<div>
    <form wire:submit.prevent="save" class="needs-validation">
        <div class="card-body">
            <p class="text-muted mb-4">Silakan isi alur untuk 3 kategori bantuan yang tampil di landing page.</p>
            @foreach($alur as $kategoriIndex => $item)
                <div class="border rounded p-3 mb-4" wire:key="alur-kategori-{{ $item['kategori'] }}">
                    <h4 class="mb-3">{{ $item['label'] }}</h4>

                    @foreach($item['steps'] as $stepIndex => $step)
                        <div class="row border rounded mx-0 mb-3 p-3" wire:key="alur-step-{{ $item['kategori'] }}-{{ $stepIndex }}">
                            <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Step {{ $stepIndex + 1 }}</h6>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    wire:click="removeStep({{ $kategoriIndex }}, {{ $stepIndex }})"
                                    @disabled(count($item['steps']) <= 1)
                                >
                                    Hapus Step
                                </button>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-small text-uppercase">Judul</label>
                                <input type="text" class="form-control" wire:model="alur.{{ $kategoriIndex }}.steps.{{ $stepIndex }}.judul" required>
                                @error("alur.$kategoriIndex.steps.$stepIndex.judul") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-small text-uppercase">Icon (Material Symbols)</label>
                                <input type="text" class="form-control" wire:model="alur.{{ $kategoriIndex }}.steps.{{ $stepIndex }}.icon" placeholder="contoh: fact_check" list="alur-icon-suggestions">
                                @error("alur.$kategoriIndex.steps.$stepIndex.icon") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                <small class="text-muted d-block mt-1">
                                    Isi nama icon Material Symbols (opsional). Jika dikosongkan, sistem memilih icon otomatis dari judul/deskripsi step.
                                </small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-small text-uppercase">Deskripsi</label>
                                <textarea class="form-control" rows="3" wire:model="alur.{{ $kategoriIndex }}.steps.{{ $stepIndex }}.deskripsi" required></textarea>
                                @error("alur.$kategoriIndex.steps.$stepIndex.deskripsi") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endforeach

                    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addStep({{ $kategoriIndex }})">
                        Tambah Step
                    </button>
                    @error("alur.$kategoriIndex.steps") <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                </div>
            @endforeach
            <div class="alert alert-light border mb-4">
                <div class="fw-semibold mb-2">Hint pemilihan icon</div>
                <div class="small text-muted mb-2">
                    Gunakan nama icon dari Material Symbols (huruf kecil + underscore). Contoh yang umum:
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <code>person_search</code>
                    <code>fact_check</code>
                    <code>gavel</code>
                    <code>payments</code>
                    <code>assignment</code>
                    <code>task_alt</code>
                    <code>approval</code>
                    <code>handshake</code>
                </div>
                <div class="small mt-2">
                    Referensi icon:
                    <a href="https://fonts.google.com/icons" target="_blank" rel="noopener noreferrer">fonts.google.com/icons</a>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan Alur Bantuan</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>

    <datalist id="alur-icon-suggestions">
        <option value="person_search"></option>
        <option value="fact_check"></option>
        <option value="gavel"></option>
        <option value="payments"></option>
        <option value="assignment"></option>
        <option value="task_alt"></option>
        <option value="approval"></option>
        <option value="handshake"></option>
        <option value="inventory_2"></option>
        <option value="campaign"></option>
    </datalist>
</div>
