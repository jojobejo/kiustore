# Compare `kiustore_compare.sql` vs Database Aktif `kiucoid_kiustore`

Tanggal audit: 2026-08-27  
File pembanding: `C:\xampp\htdocs\kiustore\kiustore_compare.sql`  
Database live: `kiucoid_kiustore`  
Database sementara audit: `kiustore_compare_tmp_codex`  
Status eksekusi: read-only terhadap database live; import hanya dilakukan ke database sementara.

## Executive Summary

Schema pada `kiustore_compare.sql` cocok dengan database aktif `kiucoid_kiustore` untuk struktur utama: jumlah objek, daftar tabel/view, kolom, index/key, foreign key, dan logic view sama.

Tidak ditemukan gap table, gap column, gap index, ataupun gap foreign key antara dump pembanding dan database live.

Perbedaan yang perlu dicatat bukan perbedaan struktur bisnis, melainkan metadata environment:

1. `kiustore_compare.sql` memakai collation `utf8mb4_uca1400_ai_ci` pada 3 tabel mobile, sementara database live lokal memakai `utf8mb4_general_ci`.
2. View pada SQL dump memakai definer `u676129830_kiustoreonline`@`127.0.0.1`, sedangkan view live lokal memakai definer `root`@`localhost`.
3. Nilai `AUTO_INCREMENT` live lebih besar dari database sementara karena live memiliki data, sedangkan dump pembanding tidak membawa `INSERT INTO`.

## Metodologi Audit

1. Membaca `kiustore_compare.sql` tanpa mengubah file.
2. Membuat database sementara `kiustore_compare_tmp_codex`.
3. Import dump ke database sementara.
4. Import awal gagal untuk 3 tabel mobile karena XAMPP lokal tidak mengenali collation `utf8mb4_uca1400_ai_ci`.
5. Import ulang dilakukan dengan substitusi runtime `utf8mb4_uca1400_ai_ci` menjadi `utf8mb4_general_ci` hanya untuk database sementara. File asli tidak diubah.
6. Membandingkan `information_schema` antara `kiustore_compare_tmp_codex` dan `kiucoid_kiustore`.

## Ringkasan Hasil

| Area Compare | `kiustore_compare.sql` setelah import sementara | Database live | Status |
|---|---:|---:|---|
| Base table | 28 | 28 | Cocok |
| View | 2 | 2 | Cocok |
| Total kolom | 297 | 297 | Cocok |
| Total entri index/statistic | 54 | 54 | Cocok |
| Foreign key | 4 | 4 | Cocok |
| Gap table | 0 | 0 | Tidak ada |
| Gap column | 0 | 0 | Tidak ada |
| Gap index | 0 | 0 | Tidak ada |
| Gap foreign key | 0 | 0 | Tidak ada |

## Daftar Objek Cocok

Base table yang ada di dua sisi:

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

View yang ada di dua sisi:

- `v_products`
- `v_tagihan`

## Perbedaan Metadata Environment

### 1. Collation Tabel Mobile

Pada file `kiustore_compare.sql`, 3 tabel berikut memakai `utf8mb4_uca1400_ai_ci`:

| Tabel | Collation di file SQL | Collation live lokal |
|---|---|---|
| `mobile_api_tokens` | `utf8mb4_uca1400_ai_ci` | `utf8mb4_general_ci` |
| `mobile_cart_items` | `utf8mb4_uca1400_ai_ci` | `utf8mb4_general_ci` |
| `mobile_shipping_quotes` | `utf8mb4_uca1400_ai_ci` | `utf8mb4_general_ci` |

Catatan risiko: `utf8mb4_uca1400_ai_ci` adalah collation yang tidak dikenali oleh XAMPP lokal saat audit ini. Jika dump ini akan di-import ke server yang tidak mendukung UCA 1400, import akan gagal pada 3 tabel mobile tersebut. Untuk deployment lintas environment, collation perlu disesuaikan dengan versi MariaDB/MySQL target.

### 2. Definer View

`kiustore_compare.sql` mendefinisikan view dengan:

- `DEFINER='u676129830_kiustoreonline'@'127.0.0.1'`

Database live lokal memakai definer lokal:

- `root`@`localhost`

Logic view tetap cocok. Perbedaan definer ini harus diperhatikan saat restore ke production/staging karena user definer yang tidak ada di server target dapat menyebabkan error import atau error akses view.

### 3. AUTO_INCREMENT

Nilai `AUTO_INCREMENT` live berbeda dari database sementara karena database live berisi data, sedangkan `kiustore_compare.sql` tidak membawa data insert.

Contoh:

| Tabel | Live `AUTO_INCREMENT` | Database sementara |
|---|---:|---:|
| `products` | 834 | 1 |
| `users` | 156 | 1 |
| `tmp_cart` | 253 | 1 |
| `orders` | 85 | 1 |
| `order_items` | 98 | 1 |

Kesimpulan: perbedaan `AUTO_INCREMENT` adalah perbedaan data state, bukan schema drift.

## Foreign Key Cocok

| Constraint | Table | Referensi | Update Rule | Delete Rule |
|---|---|---|---|---|
| `FK_contacts_contacts` | `contacts` | `contacts(id)` | NO ACTION | CASCADE |
| `FK_customers_users` | `customers` | `users(id)` | CASCADE | NO ACTION |
| `FK_orders_coupons` | `orders` | `coupons(id)` | CASCADE | SET NULL |
| `FK_orders_users` | `orders` | `users(id)` | CASCADE | NO ACTION |

## View Logic

| View | Status Compare |
|---|---|
| `v_products` | Logic cocok. Perbedaan hanya schema qualifier dan definer environment. |
| `v_tagihan` | Logic cocok. Perbedaan hanya schema qualifier dan definer environment. |

## Yang Tidak Dibandingkan

Audit ini tidak membandingkan isi data karena `kiustore_compare.sql` tidak memuat statement `INSERT INTO`. Maka audit ini hanya menyatakan kesetaraan schema, bukan kesetaraan data.

## Accomplishment

- SQL dump berhasil dibaca dan di-import ke database sementara.
- Struktur live `kiucoid_kiustore` berhasil dibandingkan dengan hasil import sementara.
- Tidak ada mismatch pada objek, kolom, index, key, foreign key, dan logic view.
- Database live tidak dimutasi.

## Issues & Root Cause

| Issue | Root Cause | Dampak |
|---|---|---|
| Import langsung dump ke XAMPP lokal gagal pada 3 tabel mobile. | Collation `utf8mb4_uca1400_ai_ci` tidak dikenali environment lokal. | Dump perlu adaptasi collation bila target server tidak mendukung UCA 1400. |
| Definer view berbeda. | Dump berasal dari environment/user hosting berbeda. | Restore ke server lain perlu mengganti definer atau memakai definer yang valid di target. |
| Data tidak bisa dibandingkan. | File SQL tidak memiliki `INSERT INTO`. | Kesimpulan audit hanya schema-level. |

## Next Steps & Risk Mitigation

| Prioritas | Rekomendasi | Validasi |
|---|---|---|
| P1 | Jika dump akan dipakai restore lokal, ganti `utf8mb4_uca1400_ai_ci` menjadi collation yang didukung target, misalnya `utf8mb4_general_ci`, setelah approval. | Test import ke database sementara. |
| P1 | Jika dump akan dipakai production, cek dulu versi MariaDB/MySQL production sebelum mengubah collation. | `SELECT VERSION();` |
| P1 | Untuk restore view, sesuaikan definer dengan user database target atau hapus clause definer jika policy deployment mengharuskan. | `SHOW CREATE VIEW v_products; SHOW CREATE VIEW v_tagihan;` |
| P2 | Jika butuh compare data, minta dump yang berisi `INSERT INTO` atau akses database sumber. | Compare row count, checksum, dan sample business key. |

## Status Akhir

Schema `kiustore_compare.sql` setara dengan database aktif `kiucoid_kiustore`. Tidak ada query migration yang perlu dijalankan untuk menyamakan struktur berdasarkan file ini. Perhatian hanya pada collation 3 tabel mobile dan definer view jika file dipakai lintas server.
