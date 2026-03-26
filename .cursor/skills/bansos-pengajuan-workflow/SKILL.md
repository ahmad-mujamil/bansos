---
name: bansos-pengajuan-workflow
description: Maintains the Pengajuan workflow across validation, database transactions, logging, and Blade UI. Use when editing `PengajuanController`, `Pengajuan` models, or Blade pages under `resources/views/pages/pengajuan/`.
---

# Bansos Pengajuan Workflow

## Tujuan
Skill ini memastikan perubahan pada fitur `pengajuan` tetap konsisten dari sisi:
- validasi input
- pembuatan/perbaruan data `Pengajuan` dan `PengajuanDetail`
- pencatatan `PengajuanLog`
- transisi status dan batasan yang ditentukan `Pengajuan::canEdit()`/`canSubmit()`
- sinkronisasi kebutuhan field di form Blade dengan aturan validasi di controller

## Trigger (kapan skill dipakai)
Gunakan skill ini jika user meminta bantuan untuk salah satu hal berikut:
- menambah field atau mengubah aturan validasi pada `PengajuanController`
- memperbaiki proses submit, update, atau store pengajuan
- mengubah UI form `resources/views/pages/pengajuan/form.blade.php`
- menyesuaikan tombol/action di `resources/views/pages/pengajuan/index.blade.php` atau `show.blade.php`
- menambahkan status baru atau mengubah mapping badge/status display

## Aturan Inti (Controller)
1. Selalu gunakan `PengajuanController::authorizeUser()` (cek `pengajuan->user_id === auth()->id()`) sebelum update/submit/show.
2. Semua penulisan data `Pengajuan` + `PengajuanDetail` harus berada dalam transaksi DB:
   - `DB::beginTransaction()`
   - buat/update `Pengajuan`
   - hapus detail lama saat update: `details()->delete()`
   - simpan `PengajuanDetail` via helper `saveDetail()`
   - `DB::commit()` atau `DB::rollBack()` pada exception
3. Untuk `store`:
   - set `user_id` ke `auth()->id()`
   - set `kode_pengajuan` via `generateKodePengajuan()`
   - set `status` awal ke `PengajuanStatus::DRAFT`
   - lakukan `logPengajuan($pengajuan, 'created', null, PengajuanStatus::DRAFT->value)`
4. Untuk `update`:
   - pastikan `if (! $pengajuan->canEdit())` memberi guard + redirect sesuai pola yang ada
   - log `updated` dengan status from/to yang benar (di kode saat ini, to mengikuti `pengajuan->status->value`)
5. Untuk `submit`:
   - pastikan guard `if (! $pengajuan->canSubmit())` aktif
   - lakukan transisi status dari `DRAFT` menjadi `DIAJUKAN`
   - log `status_changed` dari status lama ke `PengajuanStatus::DIAJUKAN->value`

## Aturan Inti (Validasi)
Validasi utama ada di `PengajuanController::validatePengajuan()`:
- `jenis` wajib dan harus salah satu nilai dari `JenisPengajuan::cases()`
- `judul_usulan` wajib string max 255
- `nilai_usulan` wajib numeric min 0
- aturan dinamis berdasarkan `jenis`:
  - jika `jenis === BANTUAN_KELOMPOK`:
    - `jenis_bantuan_id` wajib
  - jika `jenis === BANSOS`:
    - `penduduk_id` wajib
    - `kelompok_id` nullable
  - selain itu (HIBAH / selain BANSOS):
    - `kelompok_id` wajib
    - `penduduk_id` nullable

Saat mengubah form Blade:
- pastikan field yang “required” di UI mengikuti logika validasi di atas
- pastikan nilai yang dikirim sesuai nama field input: `jenis`, `penduduk_id`, `kelompok_id`, `jenis_bantuan_id`, `judul_usulan`, dll.

## Aturan Inti (Blade Sinkronisasi UI)
1. Form harus:
   - mengirim `jenis` melalui input hidden `name="jenis"` (controller menentukan nilai `jenis`)
   - menampilkan/menyembunyikan blok berdasarkan jenis (pakai JS toggle seperti yang sudah ada)
   - mengatur atribut `required` di JS sesuai kebutuhan validasi (contoh: `#penduduk_id`, `#kelompok_id`, `#jenis_bantuan_id`)
2. Halaman daftar/detail harus:
   - menampilkan tombol `Edit` hanya jika `$pengajuan->canEdit()`
   - menampilkan tombol `Ajukan` hanya jika `$pengajuan->canSubmit()`
   - mengambil `detail` dengan pola: `$pengajuan->details->first()`

## Aturan Inti (Log)
Gunakan `logPengajuan()` untuk setiap perubahan status/data utama:
- `action` yang konsisten dengan pola yang ada: `created`, `updated`, `status_changed`
- isi `status_from` dan `status_to` sesuai kebutuhan audit
- `metadata` opsional (jika ditambahkan, pastikan format JSON/array konsisten)

## Safety Check (Konsistensi Enum dan UI)
Sebelum final:
1. Pastikan semua status yang direferensikan di Blade untuk badge/match ada di `app/Enums/PengajuanStatus.php`.
2. Jika Anda menambah/menghapus status:
   - update enum
   - update mapping badge di `index.blade.php` dan `show.blade.php`
   - update transisi di controller
   - update guards `canEdit()`/`canSubmit()` bila perlu

## Checklist Perubahan Cepat
- [ ] Validasi dinamis sesuai `JenisPengajuan`
- [ ] Form Blade menyetel required/toggle sesuai validasi
- [ ] Update/store menggunakan transaksi + `saveDetail()`
- [ ] Update menghapus detail lama (`details()->delete()`)
- [ ] Semua perubahan punya `PengajuanLog`
- [ ] Tombol UI sesuai `canEdit()`/`canSubmit()`
- [ ] Mapping status di Blade konsisten dengan enum

