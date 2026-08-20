# Development - Modernisasi Tombol Profile Customer

Tanggal: 2026-08-20

## Ringkasan

Perbaikan dilakukan pada halaman customer profile agar tombol aksi tampil sebagai ikon modern dengan teks singkat, sesuai tema warna aplikasi, dan tetap rapi saat dibuka melalui mobile webview maupun browser desktop.

## Verified System Facts

- Route customer profile: `profile`
- Mapping route: `application/config/routes.php`
- Controller: `application/modules/customer/controllers/Profile.php`
- View source yang diubah: `application/modules/customer/views/profile.php`
- View aplikasi sinkron: `kiustore_apps/modules/customer/views/profile.php`
- Tombol yang terdampak:
  - Reset Data Alamat
  - Edit Profile
  - Tutorial
  - Guide Book Customer
  - Ganti Password

## Root Cause

Layout tombol sebelumnya memakai row Bootstrap dengan `flex-wrap: nowrap`, `overflow-x: auto`, dan lebar tombol minimum berbeda. Pada viewport mobile kecil atau webview, pola ini membuat tombol terlihat tidak sejajar, sebagian terdorong horizontal, dan tidak membentuk susunan aksi yang konsisten.

## Implementasi

- Mempertahankan container tombol sebagai `.profile-action-grid`.
- Menggunakan CSS Grid 5 tombol agar seluruh aksi utama terlihat dalam satu baris ringkas.
- Mengubah tombol Bootstrap standar menjadi tombol ikon modern dengan class `.profile-action-btn`.
- Menambahkan ikon Feather pada setiap tombol:
  - `map-pin` untuk Reset Data Alamat.
  - `edit-3` untuk Edit Profile.
  - `play-circle` untuk Tutorial.
  - `book-open` untuk Guide Book Customer.
  - `lock` untuk Ganti Password.
- Menambahkan teks pendek di bawah ikon:
  - `Alamat`
  - `Profil`
  - `Tutorial`
  - `Panduan`
  - `Password`
- Menambahkan `aria-label` dan `title` pada setiap tombol agar fungsi tombol tetap jelas secara aksesibilitas.
- Menyesuaikan warna tombol dengan tema aplikasi:
  - Primary customer theme: `#0baf9a`.
  - Aksen app/table header: `#1f6fbe`.
- Menambahkan hover/focus state berupa lift ringan, shadow, dan gradient tema aplikasi.
- Menyediakan ukuran teks dan tombol lebih kecil pada viewport `<= 360px` agar tombol tetap muat.

## Dampak Aplikasi

- Perubahan hanya pada tampilan halaman customer profile.
- Link, route, controller, dan proses bisnis tombol tidak berubah.
- Tidak ada perubahan pada authentication/session customer.
- Tidak ada perubahan pada payload form profile.
- Tidak ada perubahan akses role atau validasi server-side.

## Rekomendasi UAT

1. Login sebagai customer.
2. Buka halaman `profile` melalui mobile webview.
3. Validasi tombol tampil sebagai ikon dengan teks singkat dan tidak perlu scroll horizontal.
4. Validasi ikon serta teks tetap muat pada viewport mobile kecil.
5. Klik setiap tombol untuk memastikan route masih berjalan:
   - Reset Data Alamat
   - Edit Profile
   - Tutorial
   - Guide Book Customer
   - Ganti Password
