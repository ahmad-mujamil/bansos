<div class="space-y-3">
    <div>
        <label class="block text-sm font-medium mb-1">Kecamatan</label>
        <select wire:model.live="kecamatan" class="border rounded w-full p-2">
            <option value="">-- Pilih Kecamatan --</option>
            @foreach($kecamatanOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        @error('kecamatan') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Desa / Kelurahan</label>
        <select wire:model.live="desa" class="border rounded w-full p-2" @disabled(empty($desaOptions))>
            <option value="">-- Pilih Desa/Kelurahan --</option>
            @foreach($desaOptions as $nama)
                <option value="{{ $nama }}">{{ $nama }}</option>
            @endforeach
        </select>
        @error('desa') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <button wire:click="save" class="px-4 py-2 rounded border">
        Simpan
    </button>
</div>
