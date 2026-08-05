# KIU Store Mobile API v1

Dokumen ini menjelaskan API mobile yang ditambahkan pada project `kiustore`.

## Ringkasan

- Folder/module baru: `application/modules/api`
- Controller baru: `application/modules/api/controllers/Mobile.php`
- Model baru: `application/modules/api/models/Mobile_api_model.php`
- Prefix endpoint: `/api/v1`
- Autentikasi: Bearer token
- Format request utama: JSON
- Format response: JSON
- Keranjang mobile disimpan di database, tidak memakai session web CodeIgniter

## Perubahan Database

API mobile ini membutuhkan tabel baru. File migrasi:

- `db/migrations/20260629_mobile_api.sql`

Tabel yang ditambahkan:

- `mobile_api_tokens`
- `mobile_cart_items`
- `mobile_shipping_quotes`
- `mobile_account_deletions`

Contoh eksekusi migrasi:

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u USER -p DATABASE < db/migrations/20260629_mobile_api.sql
```

Tidak ada perubahan kolom pada tabel lama yang wajib dilakukan oleh migrasi ini.

Catatan:

- API ini membaca tabel lama seperti `users`, `customers`, `products`, `v_products`, `orders`, `order_items`, dan `message`.
- Bila database aktif memiliki kolom tambahan seperti `orders.nama_ekspedisi` atau `customers.alamat_kirim`, API akan menyesuaikan otomatis.

## Header Request

Gunakan header berikut:

```http
Content-Type: application/json
Accept: application/json
```

Untuk endpoint privat:

```http
Authorization: Bearer TOKEN
```

## Format Response

Response berhasil:

```json
{
  "success": true,
  "message": "OK",
  "data": {}
}
```

Response gagal:

```json
{
  "success": false,
  "message": "Pesan kesalahan",
  "data": null,
  "errors": {}
}
```

## Endpoint

### Status API

```http
GET /api/v1
```

### Register

```http
POST /api/v1/auth/register
```

Payload:

```json
{
  "name": "Budi",
  "email": "budi@example.com",
  "password": "rahasia123",
  "phone_number": "08123456789",
  "address": "Jl. Contoh 1",
  "shop_name": "Toko Budi",
  "shop_address": "Jl. Contoh 1",
  "device_name": "Android Budi"
}
```

### Login

```http
POST /api/v1/auth/login
```

Payload:

```json
{
  "email": "budi@example.com",
  "password": "rahasia123",
  "device_name": "Android Budi"
}
```

### Logout

```http
POST /api/v1/auth/logout
```

### Hapus Akun

```http
DELETE /api/v1/account
```

Endpoint ini wajib memakai Bearer token. Flow ini dibuat untuk memenuhi Apple Guideline 5.1.1(v), yaitu app yang mendukung pembuatan akun juga harus menyediakan penghapusan akun dari dalam app.

Response berhasil:

```json
{
  "success": true,
  "message": "Akun berhasil dihapus.",
  "data": {
    "deleted": true
  }
}
```

Efek database:

- Semua token user di `mobile_api_tokens` di-revoke.
- Cart user di `mobile_cart_items` dihapus.
- Quote ongkir sementara di `mobile_shipping_quotes` dihapus.
- Data profil di `customers` dianonimkan dan dilepas dari `user_id`.
- Histori `orders` dan `reviews` dilepas dari `user_id` agar transaksi non-PII tetap bisa disimpan untuk kebutuhan operasional/pembukuan.
- Row `users` dianonimkan, email diganti ke email lokal `deleted-*`, password diganti random hash, dan status dinonaktifkan.
- Audit non-PII disimpan ke `mobile_account_deletions` menggunakan `email_hash`.

### Profil

Ambil profil:

```http
GET /api/v1/profile
```

Update profil:

```http
PUT /api/v1/profile
```

Field yang dapat diperbarui:

```json
{
  "name": "Budi Baru",
  "phone_number": "08123456789",
  "address": "Alamat utama",
  "shop_name": "Toko Budi",
  "shop_address": "Alamat toko",
  "province_id": 18,
  "kota_id": 256,
  "subdistrict_id": 2528,
  "alamat_kirim": "Alamat kirim lengkap"
}
```

### Kategori

```http
GET /api/v1/categories
```

### Produk

List produk:

```http
GET /api/v1/products?page=1&per_page=20&search=alika&category_id=9&promo=1
```

Detail produk:

```http
GET /api/v1/products/1
```

Catatan:

- Tanpa token, harga mengikuti level customer `1`.
- Dengan token, harga mengikuti level customer yang sedang login.

### Keranjang

Ambil keranjang:

```http
GET /api/v1/cart
```

Tambah item:

```http
POST /api/v1/cart
```

```json
{
  "product_id": 1,
  "quantity": 2,
  "unit_type": 1
}
```

Update item:

```http
PUT /api/v1/cart/15
```

```json
{
  "quantity": 3
}
```

Hapus item:

```http
DELETE /api/v1/cart/15
```

Catatan:

- `unit_type = 1` untuk satuan pertama.
- `unit_type = 2` untuk satuan kedua.
- Harga, multiplier, dan stok dihitung ulang oleh server.

### Ongkir

Provinsi:

```http
GET /api/v1/shipping/provinces
```

Kota:

```http
GET /api/v1/shipping/cities?province_id=18
```

Kecamatan:

```http
GET /api/v1/shipping/districts?city_id=256
```

Buat quote ongkir:

```http
POST /api/v1/shipping/quotes
```

```json
{
  "destination_id": 5874,
  "courier": "jne:jnt:sicepat"
}
```

Catatan:

- Berat diambil otomatis dari isi keranjang.
- Quote berlaku 30 menit.
- Quote akan ditolak saat checkout jika berat keranjang berubah.
- Origin saat ini dibaca dari `application/config/rajaongkir.php` dengan key `mobile_shipping_origin_id`.

### Order

Aturan transaksi aktif:

- User tidak dapat menambahkan item ke keranjang jika masih memiliki transaksi berjalan.
- Transaksi dianggap berjalan selama `order_status` bukan `5`, `6`, atau `7`.
- API `POST /api/v1/cart` akan mengembalikan `409 Conflict` dengan arahan agar user melanjutkan transaksi dari menu Riwayat, atau membatalkan/menyelesaikan transaksi tersebut.

Metode pembayaran:

```http
GET /api/v1/payment-methods
```

Rekening tujuan transfer bank:

```http
GET /api/v1/payments/banks
```

List order:

```http
GET /api/v1/orders?page=1&per_page=20
```

Detail order:

```http
GET /api/v1/orders/123
```

Checkout:

```http
POST /api/v1/orders/checkout
```

```json
{
  "shipping_quote_id": 10,
  "shipping_service": "REG",
  "note": "Hubungi sebelum kirim"
}
```

Pilih metode pembayaran setelah admin mengonfirmasi pesanan:

```http
POST /api/v1/orders/123/payment-method
```

```json
{
  "payment_method": 3
}
```

Batalkan order:

```http
POST /api/v1/orders/123/cancel
```

Catatan penting checkout:

- Order mobile baru dibuat dengan `order_status = 1` (`Menunggu diproses`) dan `payment_method = null`.
- Admin mengonfirmasi pesanan dari modul order web. Setelah status menjadi `2` (`Menunggu pembayaran`), aplikasi menampilkan invoice dan membuka pemilihan metode pembayaran.
- Endpoint pemilihan metode pembayaran mendukung `payment_method = 2` untuk Virtual Account dan `payment_method = 3` untuk Transfer Bank.
- Jika user memilih Transfer Bank, upload bukti transfer melalui endpoint konfirmasi pembayaran. Endpoint ini membuat `payments.payment_status = 1` dan mengubah `orders.order_status = 8` (`Dalam proses verifikasi pembayaran`).
- Metode kredit (`payment_method = 1`) belum didukung di mobile karena masih bergantung pada approval dan limit kredit web.
- Harga order item dan stok selalu diverifikasi ulang dari database.
- `total_price` disimpan sebagai subtotal barang, sedangkan `grand_total` pada response sudah memasukkan ongkir dan insurance.

Konfirmasi pembayaran transfer bank:

```http
POST /api/v1/orders/123/payments/bank-transfer
```

Payload JSON:

```json
{
  "source_bank": "BCA",
  "source_account_number": "1234567890",
  "source_account_name": "Budi",
  "transfer_amount": 91500,
  "transfer_to": "bank-bca",
  "picture_mime": "image/jpeg",
  "picture_base64": "BASE64_IMAGE"
}
```

Catatan:

- `transfer_to` memakai `id` dari endpoint `/api/v1/payments/banks`.
- Bukti pembayaran juga dapat dikirim sebagai multipart field `picture` (`jpg`, `jpeg`, atau `png`, maksimal 5MB).
- Setelah konfirmasi berhasil, order masuk status `8` / menunggu konfirmasi pembayaran admin.

### Chat

Ambil pesan:

```http
GET /api/v1/messages?last_id=0
```

Kirim pesan:

```http
POST /api/v1/messages
```

```json
{
  "message": "Mohon update status pesanan saya"
}
```

## File yang Ditambahkan atau Diubah

File baru:

- `application/modules/api/controllers/Mobile.php`
- `application/modules/api/models/Mobile_api_model.php`
- `db/migrations/20260629_mobile_api.sql`
- `db/migrations/20260728_mobile_account_deletion.sql`
- `docs/MOBILE_API.md`
- `docs/APP_REVIEW_RESOLUTION_20260728.md`

File diubah:

- `application/config/routes.php`
- `application/config/rajaongkir.php`

## Catatan Lanjutan

- API key ongkir masih tersimpan di file config project. Untuk production lebih baik dipindah ke environment yang aman.
- Belum ada refresh token.
- Belum ada upload foto profil.
- Belum ada endpoint BRIVA otomatis di flow checkout mobile ini.
- Jika nanti Anda ingin, tahap berikutnya paling masuk akal adalah menambahkan `payment confirmation`, `wishlist`, dan `push notification ready payload`.
