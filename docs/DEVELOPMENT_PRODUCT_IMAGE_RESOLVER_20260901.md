# Development Note - Product Image Resolver

Tanggal: 2026-09-01

## Ringkasan

Masalah gambar produk tidak tampil berasal dari URL gambar yang dibangun langsung dari `products.picture_name`, sementara nama file di database tidak selalu sama persis dengan file fisik di `assets/uploads/products/`.

Contoh verified:

- Database: `ABADO_50_WP_40gr.jpg`
- File fisik: `ABADO_50WP_40GRAM.jpg`

Pada pengecekan lokal, 951 produk ditemukan di tabel `products`, 799 memiliki `picture_name`, tetapi hanya 16 yang cocok persis dengan file fisik. Setelah resolver toleran diterapkan, 139 gambar dapat diarahkan ke file fisik valid. Sisa produk menggunakan placeholder valid sampai asset gambar dilengkapi atau nama data dibersihkan.

## Perubahan Aplikasi

File utama:

- `application/helpers/global_helper.php`
- `application/modules/api/models/Mobile_api_model.php`
- View customer: home, search, product detail, product list, category detail, promo.
- View admin terkait thumbnail/detail produk, order, report, dan banner product.

Helper baru/diubah:

- `normalize_product_image_name($file_name)`
- `resolve_product_image_name($picture_name)`
- `get_product_image_url($picture_name)`
- `get_product_image($id)`

Perilaku resolver:

1. Cek file persis di `assets/uploads/products/`.
2. Jika tidak ada, cocokkan nama file secara toleran:
   - Case-insensitive.
   - Mengabaikan underscore, spasi, tanda baca.
   - Menyamakan `GRAM` menjadi `GR`.
   - Menyamakan `LITER`/`LTR` menjadi `L`.
   - Mengabaikan suffix copy seperti `(1)`.
3. Jika tetap tidak ada, gunakan `assets/uploads/products/default.jpg` bila tersedia.
4. Jika `default.jpg` tidak tersedia, gunakan placeholder theme `assets/themes/fastkart/images/product/1.png`.

## Root Cause

Root cause teknis:

- View customer dan admin membangun URL gambar secara langsung dari `base_url('assets/uploads/products/' . $picture_name)`.
- `default.jpg` tidak tersedia di `assets/uploads/products/`.
- Banyak nilai `products.picture_name` tidak sama persis dengan nama file aktual.

Root cause operasional:

- Belum ada single resolver untuk URL gambar produk.
- Belum ada validasi/audit rutin yang membandingkan `products.picture_name` dengan folder upload produk.

## Validasi

Perintah validasi:

```bash
C:\xampp\php\php.exe -l application\helpers\global_helper.php
C:\xampp\php\php.exe -l application\modules\api\models\Mobile_api_model.php
```

Validasi HTTP lokal:

- `http://localhost/kiustore/product/2/QOLS00002/` mengembalikan HTTP 200.
- Detail produk ID 2 mengarah ke file valid `assets/uploads/products/ABADO_50WP_40GRAM.jpg`.
- `http://localhost/kiustore/all_products` mengembalikan HTTP 200 dan menggunakan URL gambar hasil resolver.
- `http://localhost/kiustore/api/v1/products?limit=1` mengembalikan HTTP 200 dan `image_url` valid.

## Catatan UAT

UAT browser authenticated admin/customer tetap perlu dilakukan untuk:

- Halaman home customer.
- Halaman semua produk.
- Halaman detail produk.
- Pencarian produk.
- Detail produk admin.
- Order/report yang menampilkan thumbnail produk.

