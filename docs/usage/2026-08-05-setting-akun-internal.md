# Panduan Penggunaan - Setting Akun Internal

Tanggal: 2026-08-05
Role pengguna: `admin`

## Membuka Modul

1. Login sebagai admin.
2. Buka menu `Users` atau akses route `admin`.

## Menandai Akun Internal Dari Daftar Users

1. Cari akun pada tabel `Kelola Users`.
2. Gunakan filter `Role` jika ingin melihat role tertentu.
3. Gunakan filter `Akun` untuk melihat akun `Internal` atau `Non Internal`.
4. Klik tombol kuning dengan ikon user pada baris akun:
   - Jika akun masih non internal, tombol akan menandai akun sebagai internal.
   - Jika akun sudah internal, tombol akan mengembalikan akun menjadi non internal.

## Menandai Akun Internal Saat Tambah User

1. Buka `Tambah Users`.
2. Isi nama, email, password, status, dan role.
3. Aktifkan toggle `Akun Internal` jika akun dibuat untuk internal/test.
4. Klik `Tambah User Baru`.

## Menandai Akun Internal Saat Edit User

1. Buka route `admin`.
2. Klik nama user non-customer yang ingin diedit.
3. Aktifkan atau nonaktifkan toggle `Akun Internal`.
4. Kosongkan password jika tidak ingin mengubah password.
5. Klik `Simpan`.

## Dampak Pada Data

- Akun internal tetap bisa login sesuai role dan status aktif/non aktif.
- Data historis akun internal tidak dihapus.
- Dashboard dan laporan utama yang sudah disesuaikan tidak menghitung akun internal.
- Daftar user/customer tetap menampilkan akun internal agar admin dapat audit.

## Standar Operasional

- Gunakan akun internal untuk test order, simulasi pembayaran, training admin, dan validasi alur.
- Jangan menandai akun pelanggan asli sebagai internal kecuali memang milik perusahaan/internal.
- Jika akun internal sudah pernah membuat order, data order tetap tersimpan untuk audit, tetapi tidak ikut ringkasan/laporan yang sudah disesuaikan.
