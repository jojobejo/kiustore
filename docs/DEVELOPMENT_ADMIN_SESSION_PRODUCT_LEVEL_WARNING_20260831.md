# Development Note: Admin Session Product Level Warning

Tanggal: 2026-08-31

## Tujuan

Menghilangkan PHP warning pada halaman customer/home saat sesi login admin aktif dan sistem tetap memanggil query produk yang membutuhkan `level_user()`.

## Error Produksi Yang Ditangani

- `Undefined property: stdClass::$user_level` pada `application/helpers/global_helper.php`.
- `mysqli::real_escape_string(): Passing null to parameter #1 ($string) of type string is deprecated` saat `level_user()` bernilai `null` dan dipakai oleh query builder `like()` pada `Product_model`.

## Root Cause

Sesi login customer menyimpan `user_level`, sedangkan sesi login admin hanya menyimpan identitas admin dan tidak menyimpan `user_level`. Saat route customer/home tetap dirender dalam sesi admin, helper `level_user()` membaca property yang tidak tersedia.

## File Aplikasi Yang Diubah

- `application/helpers/global_helper.php`

## Perubahan Teknis

- `level_user()` sekarang memakai default level `1` untuk sesi publik, sesi admin, atau sesi login lain yang tidak memiliki `user_level`.
- `level_user()` hanya memakai nilai session jika property `user_level` tersedia dan tidak kosong.
- `get_price()` dan `get_v_price()` dibuat aman terhadap session tanpa property `is_login` atau `user_level`.
- `get_v_price()` diberi fallback return `price1` untuk level di luar 1, 2, dan 3.

## Dampak Bisnis

- Admin dapat membuka halaman yang memuat katalog produk tanpa warning PHP.
- Customer tetap mendapatkan harga sesuai `user_level` session.
- Pengunjung publik atau role non-customer tetap melihat harga level dasar.

## Validasi

- PHP lint untuk `application/helpers/global_helper.php`: lulus.
- Script mirror project tidak dapat dijalankan penuh karena `rsync` tidak tersedia pada environment lokal ini.
- Sinkronisasi perubahan dilakukan selektif untuk `helpers/global_helper.php` ke:
  - `C:\xampp\htdocs\kiustore\kiustore_apps\helpers\global_helper.php`
  - `C:\xampp\htdocs\kiustore_apps\helpers\global_helper.php`
- SHA-256 source dan kedua mirror diverifikasi identik.
- UAT browser login admin pada production tetap perlu dilakukan setelah deployment untuk memastikan log bersih di environment PHP produksi.
