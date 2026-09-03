# Development Customer Home Product Image Fallback

Tanggal: 2026-09-03
Modul: Customer - Home

## Root Cause

Production mengalami fatal error di file home customer:

`Call to undefined function get_product_image_url()`

Fungsi `get_product_image_url()` tersedia di `application/helpers/global_helper.php`, tetapi pada production/mirror tertentu fungsi tersebut belum tersedia saat view `home.php` dirender.

## Perubahan

- Menambahkan fallback lokal di `application/modules/customer/views/home.php`.
- Fallback hanya dideklarasikan jika `get_product_image_url()` belum ada, sehingga tidak bentrok dengan helper utama.
- Urutan resolusi gambar:
  - gunakan `assets/uploads/products/{picture_name}` jika file ada;
  - gunakan `assets/uploads/products/default.jpg` jika tersedia;
  - gunakan fallback theme `assets/themes/fastkart/images/product/1.png`.

## Validasi

- `C:\xampp\php\php.exe -l application\modules\customer\views\home.php` lulus tanpa syntax error.
- File perlu disinkronkan ke mirror production yang menjalankan module customer.

## Catatan UAT

- Buka halaman customer home production.
- Pastikan error fatal `get_product_image_url()` tidak muncul.
- Pastikan gambar produk promo, produk terlaris, dan semua produk tetap tampil dengan fallback bila file produk tidak ditemukan.
