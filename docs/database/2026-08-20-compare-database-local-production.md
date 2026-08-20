# Audit Perbandingan Database Lokal vs Production - 2026-08-20

## Scope

Sumber audit:
- `compare/kiucoid_kiustore_local.sql`
- `compare/kiucoid_kiustore_production.sql`

Audit dilakukan terhadap struktur dump SQL. Kedua file tidak berisi `INSERT`, sehingga audit ini tidak menilai isi data transaksi/master/setting.

## Ringkasan Eksekutif

Struktur database lokal dan production hampir sama.

- Tabel lokal: 30.
- Tabel production: 30.
- Semua nama tabel sama.
- Tidak ada tabel yang hanya ada di lokal.
- Tidak ada tabel yang hanya ada di production.
- Semua kolom tabel dan index utama sama menurut parsing struktur.
- View yang terdeteksi sama-sama ada: `v_products` dan `v_tagihan`.

Kesimpulan: tidak ada kebutuhan migrasi tabel/kolom besar dari hasil dump pembanding ini. Perbedaan nyata ada pada collation beberapa tabel mobile, metadata dump, definer view, dan satu indikasi definisi view lokal yang perlu diperbaiki sebelum import.

## Daftar Tabel

Tabel yang sama di kedua dump:

- `banner_product`
- `briva_api`
- `ci_sessions`
- `contacts`
- `coupons`
- `customers`
- `customers_bk`
- `customer_location`
- `generate_kdchart`
- `message`
- `mobile_api_tokens`
- `mobile_cart_items`
- `mobile_shipping_quotes`
- `orders`
- `orders_bk`
- `order_items`
- `order_items_bk`
- `payments`
- `piutang`
- `products`
- `product_category`
- `promo`
- `reviews`
- `settings`
- `tbtestongkir`
- `tmp_cart`
- `users`
- `users_bk`
- `v_products`
- `v_tagihan`

## Perbedaan Struktur Yang Terdeteksi

### 1. Metadata Dump

Lokal:
- phpMyAdmin 5.2.0.
- Host `127.0.0.1`.
- Server MariaDB 10.4.27.
- Database `kiucoid_kiustore`.

Production:
- phpMyAdmin 5.2.2.
- Host `127.0.0.1:3306`.
- Server MariaDB 11.8.8.
- Database `u676129830_kiustoreonline`.

Makna:
- Ini bukan beda struktur aplikasi.
- Ini menjelaskan kenapa collation tertentu di production memakai varian MariaDB baru.

### 2. Collation Tabel Mobile

Tabel berikut berbeda collation:

- `mobile_api_tokens`
- `mobile_cart_items`
- `mobile_shipping_quotes`

Lokal:
- `utf8mb4_general_ci`

Production:
- `utf8mb4_uca1400_ai_ci`

Makna teknis:
- Kolom dan index tetap sama.
- Perbedaan collation dapat memicu masalah saat join/compare string lintas tabel bila collation campur, terutama pada MariaDB versi berbeda.

Rekomendasi:
- Untuk production MariaDB 11.8, pertahankan collation production kecuali ada alasan kuat untuk standardisasi.
- Untuk migration lintas environment, buat script condition-aware bila perlu menormalisasi collation.

### 3. View `v_products`

Definisi query view secara bisnis sama: membaca `products`, left join `promo`, menghitung promo price, discount, stock, availability, dan `level_product`.

Perbedaan:
- Definer lokal: `root`@`localhost`.
- Definer production: `u676129830_kiustoreonline`@`127.0.0.1`.
- Struktur placeholder `CREATE TABLE v_products` memiliki `promo int(1)` di lokal dan `promo int(2)` di production.

Makna:
- Definer harus disesuaikan saat import ke server target.
- Perbedaan `promo int(1)` vs `int(2)` pada placeholder view bukan perubahan logic view utama, tetapi tetap akan muncul dalam diff dump.

### 4. View `v_tagihan`

Production:
- `GROUP BY a.user_id`

Lokal:
- terdeteksi `GROUP BY a.user_id``user_id`

Makna:
- Ini indikasi definisi view lokal rusak/tergabung salah saat dump.
- Jika dump lokal di-import apa adanya, view `v_tagihan` berisiko gagal dibuat atau menghasilkan error identifier.

Rekomendasi:
- Jangan gunakan definisi lokal `v_tagihan` untuk production.
- Pakai definisi production sebagai baseline atau perbaiki lokal menjadi `GROUP BY a.user_id` sebelum import.

## Kaitan Dengan Perbedaan Kode

Kode lokal menambahkan BRIVA Switch yang memakai setting key:

- `briva_payment_mode`
- nilai valid: `local` atau `production`

Namun kedua dump tidak berisi data `INSERT`, sehingga tidak ada bukti bahwa row setting tersebut sudah tersedia.

Rekomendasi seed jika fitur BRIVA Switch dipromosikan:

```sql
INSERT INTO settings (key, value)
SELECT 'briva_payment_mode', 'production'
WHERE NOT EXISTS (
    SELECT 1 FROM settings WHERE `key` = 'briva_payment_mode'
);
```

Catatan: sesuaikan nama kolom `settings` terhadap struktur nyata sebelum dieksekusi, karena dump pembanding hanya dipakai sebagai audit struktur.

## Kesimpulan Database

Tidak ada gap tabel/kolom antara lokal dan production dalam dump ini.

Yang perlu ditangani sebelum production:
- Validasi/perbaiki view `v_tagihan` lokal.
- Jangan menurunkan collation production secara tidak sengaja.
- Siapkan seed setting `briva_payment_mode = production` jika fitur BRIVA Switch dipasang.
- Jangan klaim data setting/transaksi sudah sama, karena dump tidak membawa data `INSERT`.

