<?php

namespace App\Livewire;

use App\Enums\PengajuanStatus;
use App\Enums\RupaBantuan;
use App\Enums\SatuanBarang;
use App\Models\BantuanBarangJasa;
use App\Models\BantuanUang;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use App\Models\PengajuanPemeriksa;
use App\Models\VerifikasiPengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class VerifikasiPengajuanForm extends Component
{
    public Pengajuan $pengajuan;

    public bool $lulus_kriteria = false;

    public bool $lulus_administrasi = false;

    public bool $lulus_kesesuaian = false;

    public bool $sesuai_program_pemda = false;

    public null|float|string $nilai_rekomendasi = null;

    public ?string $rupa_bantuan = null;

    public ?string $catatan = null;

    public ?string $disahkan_oleh = null;

    public ?string $disahkan_nip = null;

    public string $lokasi_pengesahan = 'Gerung';

    /**
     * Daftar pemeriksa dinamis (minimal {@see PengajuanPemeriksa::MIN_VERIFIKASI_COUNT}, maksimal {@see PengajuanPemeriksa::MAX_VERIFIKASI_COUNT}); setiap baris wajib lengkap.
     *
     * @var array<int, array{nama: string, nip: string, jabatan: string}>
     */
    public array $pemeriksa = [];

    /** @var array<string, array{penduduk_id:string, nilai:float|int|string|null}> */
    public array $detail = [];

    /**
     * @var array<int, array{
     *   nama_barang: string|null,
     *   satuan: string|null,
     *   spesifikasi: string|null,
     *   harga_satuan: float|int|string|null,
     *   qty: int|string|null
     * }>
     */
    public array $items = [];

    public function mount(Pengajuan $pengajuan): void
    {
        $this->pengajuan = $pengajuan->loadMissing(['details.penduduk', 'logs', 'user', 'verifiedBy']);

        $this->disahkan_oleh = Auth::user()?->opd?->kepala_opd ?? null;
        $this->disahkan_nip = auth()->user()?->opd?->nip ?? null;

        foreach ($this->pengajuan->details as $row) {
            $this->detail[(string) $row->id] = [
                'penduduk_id' => (string) $row->penduduk_id,
                'nilai' => $row->nilai,
            ];
        }

        $this->items = [
            $this->blankItem(),
        ];

        $this->pemeriksa = [
            $this->blankPemeriksa(),
            $this->blankPemeriksa(),
            $this->blankPemeriksa(),
        ];
    }

    public function addPemeriksa(): void
    {
        if (count($this->pemeriksa) >= PengajuanPemeriksa::MAX_VERIFIKASI_COUNT) {
            return;
        }

        $this->pemeriksa[] = $this->blankPemeriksa();
    }

    public function removePemeriksaAt(int $index): void
    {
        if (count($this->pemeriksa) <= PengajuanPemeriksa::MIN_VERIFIKASI_COUNT) {
            return;
        }

        if (! array_key_exists($index, $this->pemeriksa)) {
            return;
        }

        unset($this->pemeriksa[$index]);
        $this->pemeriksa = array_values($this->pemeriksa);
    }

    /**
     * @return array{nama: string, nip: string, jabatan: string}
     */
    private function blankPemeriksa(): array
    {
        return [
            'nama' => '',
            'nip' => '',
            'jabatan' => '',
        ];
    }

    public function rules(): array
    {
        $rules = [
            'lulus_kriteria' => ['required', 'boolean'],
            'lulus_administrasi' => ['required', 'boolean'],
            'lulus_kesesuaian' => ['required', 'boolean'],
            'sesuai_program_pemda' => ['required', 'boolean'],
            'nilai_rekomendasi' => ['nullable', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'disahkan_oleh' => ['nullable', 'string', 'max:255'],
            'disahkan_nip' => ['nullable', 'string', 'max:18'],
            'lokasi_pengesahan' => ['required', 'string', 'max:255'],
            'pemeriksa' => ['required', 'array', 'min:'.PengajuanPemeriksa::MIN_VERIFIKASI_COUNT, 'max:'.PengajuanPemeriksa::MAX_VERIFIKASI_COUNT],
            'pemeriksa.*.nama' => ['required', 'string', 'max:255'],
            'pemeriksa.*.nip' => ['required', 'digits_between:1,18'],
            'pemeriksa.*.jabatan' => ['required', 'string', 'max:255'],
        ];

        if ($this->allPassed()) {
            $rules['rupa_bantuan'] = ['required', Rule::in(array_map(fn (RupaBantuan $e) => $e->value, RupaBantuan::cases()))];

            if ($this->rupa_bantuan === RupaBantuan::UANG->value) {
                $rules['detail'] = ['required', 'array', 'min:1'];
                $rules['detail.*.penduduk_id'] = ['required', 'uuid'];
                $rules['detail.*.nilai'] = ['required', 'numeric', 'min:0'];
            }

            if (in_array($this->rupa_bantuan, [RupaBantuan::BARANG->value, RupaBantuan::JASA->value], true)) {
                $rules['items'] = ['required', 'array', 'min:1'];
                $rules['items.*.nama_barang'] = ['required', 'string', 'max:255'];
                $rules['items.*.satuan'] = ['required', Rule::in(array_map(fn (SatuanBarang $e) => $e->value, SatuanBarang::cases()))];
                $rules['items.*.spesifikasi'] = ['required', 'string', 'max:2000'];
                $rules['items.*.harga_satuan'] = ['required', 'numeric', 'min:0'];
                $rules['items.*.qty'] = ['required', 'integer', 'min:1'];
            }
        } else {
            $rules['rupa_bantuan'] = ['nullable', Rule::in(array_map(fn (RupaBantuan $e) => $e->value, RupaBantuan::cases()))];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'rupa_bantuan.required' => 'Rupa bantuan wajib dipilih jika semua verifikasi lulus.',
            'pemeriksa.min' => 'Minimal '.PengajuanPemeriksa::MIN_VERIFIKASI_COUNT.' data pemeriksa harus diisi.',
            'pemeriksa.max' => 'Pemeriksa paling banyak '.PengajuanPemeriksa::MAX_VERIFIKASI_COUNT.' orang.',
            'pemeriksa.*.nip.digits_between' => 'NIP pemeriksa harus berupa angka paling banyak 18 digit.',
        ];
    }

    public function allPassed(): bool
    {
        return $this->lulus_kriteria
            && $this->lulus_administrasi
            && $this->lulus_kesesuaian
            && $this->sesuai_program_pemda;
    }

    /**
     * @return array{
     *   nama_barang: null,
     *   satuan: null,
     *   spesifikasi: null,
     *   harga_satuan: null,
     *   qty: int
     * }
     */
    private function blankItem(): array
    {
        return [
            'nama_barang' => null,
            'satuan' => null,
            'spesifikasi' => null,
            'harga_satuan' => null,
            'qty' => 1,
        ];
    }

    public function addItem(): void
    {
        $this->items[] = $this->blankItem();
    }

    public function removeItem(int $index): void
    {
        if (! array_key_exists($index, $this->items)) {
            return;
        }

        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    private function sanitizeItemsHargaSatuanFromInput(): void
    {
        foreach ($this->items as $index => $row) {
            if (! array_key_exists('harga_satuan', $row)) {
                continue;
            }

            $value = $row['harga_satuan'];

            if ($value === null || $value === '') {
                $this->items[$index]['harga_satuan'] = null;

                continue;
            }

            if (is_string($value)) {
                $clean = str_replace(',', '', $value);
                $this->items[$index]['harga_satuan'] = is_numeric($clean) ? (float) $clean : null;

                continue;
            }

            if (is_numeric($value)) {
                $this->items[$index]['harga_satuan'] = (float) $value;
            }
        }
    }

    public function updatedNilaiRekomendasi(mixed $value): void
    {
        if ($value === null || $value === '') {
            $this->nilai_rekomendasi = null;

            return;
        }

        if (is_string($value)) {
            $clean = str_replace(',', '', $value);
            $this->nilai_rekomendasi = is_numeric($clean) ? (float) $clean : null;

            return;
        }

        if (is_numeric($value)) {
            $this->nilai_rekomendasi = (float) $value;
        }
    }

    public function submit(): void
    {
        if ($this->pengajuan->status !== PengajuanStatus::DIAJUKAN) {
            $this->addError('form', 'Pengajuan ini sudah diproses. Tidak ada aksi verifikasi yang tersedia.');

            return;
        }

        $this->sanitizeItemsHargaSatuanFromInput();

        $validated = $this->validate();

        $oldStatus = $this->pengajuan->status;
        $newStatus = $this->allPassed() ? PengajuanStatus::DISETUJUI : PengajuanStatus::DITOLAK;

        try {
            DB::beginTransaction();
            $verifikasi = VerifikasiPengajuan::create([
                'pengajuan_id' => $this->pengajuan->id,
                'user_id' => Auth::id(),
                'catatan' => $validated['catatan'] ?? null,
                'nilai_rekomendasi' => $validated['nilai_rekomendasi'] ?? null,
                'rupa_bantuan' => $validated['rupa_bantuan'] ?? null,
                'lulus_kriteria' => (bool) $validated['lulus_kriteria'],
                'lulus_administrasi' => (bool) $validated['lulus_administrasi'],
                'lulus_kesesuaian' => (bool) $validated['lulus_kesesuaian'],
                'sesuai_program_pemda' => (bool) $validated['sesuai_program_pemda'],
                'disahkan_oleh' => $validated['disahkan_oleh'] ?? null,
                'disahkan_nip' => $validated['disahkan_nip'] ?? null,
                'lokasi_pengesahan' => $validated['lokasi_pengesahan'],
            ]);

            foreach ($validated['pemeriksa'] as $pemeriksaRow) {
                PengajuanPemeriksa::create([
                    'pengajuan_id' => $this->pengajuan->id,
                    'nama' => $pemeriksaRow['nama'],
                    'nip' => $pemeriksaRow['nip'],
                    'jabatan' => $pemeriksaRow['jabatan'],
                ]);
            }

            $this->pengajuan->forceFill([
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'status' => $newStatus,
            ])->save();

            PengajuanLog::create([
                'pengajuan_id' => $this->pengajuan->id,
                'user_id' => Auth::id(),
                'action' => 'verifikasi',
                'status_from' => $oldStatus?->value,
                'status_to' => $newStatus->value,
                'catatan' => $validated['catatan'] ?? null,
                'metadata' => [
                    'verifikasi_pengajuan_id' => $verifikasi->id,
                ],
            ]);

            if ($newStatus === PengajuanStatus::DISETUJUI) {
                if (($validated['rupa_bantuan'] ?? null) === RupaBantuan::UANG->value) {
                    foreach (($validated['detail'] ?? []) as $row) {
                        BantuanUang::create([
                            'verifikasi_pengajuan_id' => $verifikasi->id,
                            'penduduk_id' => $row['penduduk_id'],
                            'nilai' => $row['nilai'],
                        ]);
                    }
                }

                if (in_array($validated['rupa_bantuan'] ?? null, [RupaBantuan::BARANG->value, RupaBantuan::JASA->value], true)) {
                    foreach (($validated['items'] ?? []) as $item) {
                        BantuanBarangJasa::create([
                            'verifikasi_pengajuan_id' => $verifikasi->id,
                            'nama_barang' => $item['nama_barang'],
                            'satuan' => $item['satuan'],
                            'spesifikasi' => $item['spesifikasi'],
                            'harga_satuan' => $item['harga_satuan'],
                            'qty' => $item['qty'],
                        ]);
                    }
                }
            }

            DB::commit();

            toast()->success('Berhasil', 'Pengajuan berhasil diverifikasi.');

            $this->redirectRoute('verifikasi-pengajuan.index');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('VerifikasiPengajuan submit error: '.$e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            toast()->error('Gagal', $e->getMessage() ?: get_class($e));
        }
    }

    public function render()
    {
        return view('livewire.verifikasi-pengajuan-form', [
            'canVerify' => $this->pengajuan->status === PengajuanStatus::DIAJUKAN,
            'rupaOptions' => RupaBantuan::cases(),
            'satuanOptions' => SatuanBarang::cases(),
        ]);
    }
}
