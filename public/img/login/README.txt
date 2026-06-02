Letakkan dua file gambar berikut di folder ini:

1) officials.png
   - Foto Bupati & Wakil Bupati Lombok Barat (atau pejabat lain)
   - PNG TRANSPARAN — background sudah dihapus (cut-out)
   - Komposisi figure berdiri menghadap kamera, full-body / hip-up
   - Resolusi tinggi (idealnya 1200×1600 px atau lebih)
   - Posisi rendering: menempel di kiri-bawah hero (kaki di garis bawah)

2) lombok-barat-logo.png
   - Logo / lambang resmi Kabupaten Lombok Barat (perisai/heraldik)
   - PNG TRANSPARAN — background sudah dihapus
   - Idealnya berbentuk persegi atau perisai, minimal 512×512 px
   - Akan ditampilkan 72×72 px di pojok kanan-atas

Jika kedua file tidak ada, halaman tetap berfungsi:
- officials.png hilang → area kiri kosong (gradient teal tetap tampak)
- lombok-barat-logo.png hilang → element hilang (onerror display:none)

Catatan untuk pemasangan:
- File dimuat melalui CSS pada `resources/views/auth/login.blade.php`
  (cari `.login-hero-officials` dan `.login-hero-logo-daerah`).
- Untuk ganti ke ekstensi lain (jpg/webp), edit url di file blade tersebut.
