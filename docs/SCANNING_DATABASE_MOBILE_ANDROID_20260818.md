# Scanning Database Mobile Android KIU Store

Tanggal audit: 2026-08-18
Project: `C:\xampp\htdocs\kiustore`
Database aktif lokal: `kiucoid_kiustore`

## Executive Summary

API mobile memakai kombinasi tabel baru mobile dan tabel transaksi lama KIU Store. Tidak ada perubahan schema yang dilakukan dalam audit ini. Hasil cek langsung database aktif lokal menunjukkan:

- `mobile_api_tokens`: ada.
- `mobile_cart_items`: ada.
- `mobile_shipping_quotes`: ada.
- `mobile_account_deletions`: belum ada di schema aktif lokal.
- `briva_api`, `orders`, `order_items`, `payments`, `customers`, `users`, `products`, `v_products`, dan `message`: ada.

Jumlah data aktif saat audit:

| Tabel | Jumlah |
| --- | ---: |
| `users` | 79 |
| `products` | 892 |
| `orders` | 83 |

## Tabel Mobile Baru

### `mobile_api_tokens`

Fungsi: menyimpan token API mobile.

Kolom aktif:

- `id`
- `user_id`
- `token_hash`
- `device_name`
- `expires_at`
- `last_used_at`
- `revoked_at`
- `created_at`

Catatan:

- Token plain hanya dikirim ke mobile saat login/register.
- Database menyimpan SHA-256 token.
- Token expired 30 hari.

### `mobile_cart_items`

Fungsi: cart mobile berbasis database, tidak memakai session web.

Kolom aktif:

- `id`
- `user_id`
- `product_id`
- `quantity`
- `unit_type`
- `created_at`
- `updated_at`

Catatan:

- Unique key mencegah duplikasi per `user_id`, `product_id`, `unit_type`.
- `unit_type = 1` untuk satuan pertama.
- `unit_type = 2` untuk satuan kedua.

### `mobile_shipping_quotes`

Fungsi: menyimpan quote ongkir sementara.

Kolom aktif:

- `id`
- `user_id`
- `origin_id`
- `destination_id`
- `weight`
- `courier`
- `options_json`
- `expires_at`
- `used_at`
- `created_at`

Catatan:

- Quote berlaku 30 menit.
- Checkout menolak quote jika expired, sudah dipakai, atau berat cart berubah.

### `mobile_account_deletions`

Status aktif lokal: belum ada.

File migrasi tersedia:

- `db/migrations/20260629_mobile_api.sql`
- `db/migrations/20260728_mobile_account_deletion.sql`

Fungsi: audit non-PII delete account.

Kolom menurut migrasi:

- `id`
- `user_id`
- `email_hash`
- `deleted_at`
- `created_at`

Rekomendasi:

```bash
C:\xampp\mysql\bin\mysql.exe -uroot kiucoid_kiustore < db\migrations\20260728_mobile_account_deletion.sql
```

Catatan: jalankan setelah backup database. Perintah di atas belum dijalankan dalam audit ini.

## Tabel Existing Yang Dipakai API Mobile

### `users`

Fungsi:

- Login/register customer.
- Status aktif/nonaktif.
- Role customer.
- Anonimisasi delete account.

Kolom relevan aktif:

- `id`
- `name`
- `email`
- `password`
- `role`
- `register_date`
- `status`
- `akses_lv`
- `is_internal`

Catatan:

- Auth mobile hanya menerima `role = customer`.
- Password diverifikasi dengan `password_verify`.

### `customers`

Fungsi:

- Profil toko/customer.
- Level harga produk.
- Alamat dan ongkir.
- Nomor telepon untuk BRIVA.
- Salesman owner chat.

Kolom relevan aktif:

- `user_id`
- `nik`
- `npwp`
- `name`
- `phone_number`
- `province_id`
- `kota_id`
- `subdistrict_id`
- `address`
- `shop_name`
- `shop_address`
- `alamat_kirim`
- `max_credit`
- `level`
- `salesman_id`
- `kode_customer`
- `va_code`

### `products` dan `v_products`

Fungsi:

- Katalog produk.
- Harga per level customer.
- Promo.
- Stock.
- Unit dan berat untuk cart/ongkir.

`v_products` aktif sebagai VIEW, bukan tabel fisik.

Kolom relevan `v_products`:

- `id`
- `category_id`
- `sku`
- `name`
- `description`
- `picture_name`
- `product_unit_value`
- `product_unit_1`
- `product_unit_2`
- `product_type`
- `product_unit_weight`
- `promo`
- `price`, `price_2`, `price_3`
- `promo_price`, `promo_price_2`, `promo_price_3`
- `discount`, `discount_2`, `discount_3`
- `stock`
- `is_available`
- `level_product`

### `orders`

Fungsi:

- Header order mobile dan web.
- Status lifecycle.
- Payment method.
- Ongkir.
- Rating selesai.

Kolom relevan aktif:

- `id`
- `user_id`
- `order_number`
- `kd_faktur`
- `invoice_number`
- `order_status`
- `order_date`
- `total_price`
- `total_items`
- `payment_method`
- `shipping_method`
- `delivery_data`
- `due_date`
- `jenis_pengiriman`
- `nama_ekspedisi`
- `estimasi_kirim`
- `shipping_cost`
- `insurance`
- `rating`
- `rating_desc`

Status order mobile:

- `1`: Menunggu diproses.
- `2`: Menunggu pembayaran.
- `3`: Dikemas.
- `4`: Dikirim.
- `5`: Selesai.
- `6`: Selesai historis.
- `7`: Dibatalkan.
- `8`: Sedang ditinjau oleh admin.
- `9`: Menunggu persetujuan.
- `10`: Payment Verify.
- `11`: Tentukan metode pengiriman.
- `12`: Ada di enum database tetapi belum terlihat dipetakan di formatter mobile.

### `order_items`

Fungsi:

- Detail item order.
- Menyimpan harga dan satuan saat checkout.

Kolom relevan aktif:

- `order_id`
- `product_id`
- `order_qty`
- `order_price`
- `satuan`
- `satuan_text`
- `satuan_qty`

### `payments`

Fungsi:

- Bukti transfer bank manual.
- Status verifikasi pembayaran.

Kolom relevan aktif:

- `order_id`
- `payment_price`
- `payment_date`
- `picture_name`
- `payment_status`
- `confirmed_date`
- `payment_data`

Status payment:

- `1`: menunggu konfirmasi.
- `2`: dikonfirmasi.
- `3`: status historis/ditolak sesuai flow admin.

### `briva_api`

Fungsi:

- Simpan data VA per order.
- Tracking status VA dan expired.

Kolom relevan aktif:

- `order_number`
- `kd_faktur`
- `user_id`
- `name`
- `va_code`
- `userno`
- `total_price_topay`
- `exp_date`
- `status`
- `create_at`

Catatan:

- `va_code` mobile dibentuk dari prefix `91118` + 8 digit terakhir nomor telepon.
- `status = 1` saat VA aktif dibuat.
- `status = 2` saat terbayar.
- `status = 3` saat expired/dibatalkan oleh flow status mobile.

### `message`

Fungsi:

- Chat customer dengan admin/sales.

Kolom relevan aktif:

- `id`
- `salesman_id`
- `customer_id`
- `message`
- `chat_from`
- `created_at`
- `status`
- `reply_at`

Catatan:

- Mobile mengirim pesan dengan `chat_from = 2`.
- Pesan dari admin/sales ditandai `chat_from = 1`.

## Database Gap

| Gap | Fakta | Dampak | Rekomendasi |
| --- | --- | --- | --- |
| `mobile_account_deletions` belum ada di database aktif | `SHOW TABLES LIKE 'mobile_%'` hanya menampilkan token, cart, quote | Delete account tetap bisa berjalan, tetapi audit non-PII tidak tercatat | Jalankan migrasi `20260728_mobile_account_deletion.sql` setelah backup |
| `orders.order_status` enum punya `12`, tetapi formatter mobile belum memberi label | Formatter mobile hanya memetakan 1 sampai 11 | Android bisa menerima label fallback `Status 12` | Android wajib menampilkan fallback status dari API apa adanya |
| `shipping_cost` dan `insurance` bertipe text | Schema historis | Android harus parsing number dari response API, bukan membaca DB langsung | Tetap konsumsi API JSON, jangan direct DB |

## Kesimpulan Database

Backend sudah cukup untuk build Android native Kotlin versi pertama. Syarat sebelum production release:

1. Backup database aktif.
2. Jalankan migrasi `mobile_account_deletions`.
3. Pastikan `v_products` tetap valid setelah deploy database.
4. Pastikan konfigurasi RajaOngkir/Komerce dan BRIVA berjalan dari backend, bukan dari mobile app.

