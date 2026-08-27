# Compare Database: Main KIU Store vs `kiustore_apps`

Tanggal audit: 2026-08-27  
Database aktif dari `application/config/database.php`: `kiucoid_kiustore`  
User database lokal: `root` pada `localhost`  
Status eksekusi: read-only schema inspection, tidak ada perubahan struktur dan tidak ada update data.

## Executive Summary

Database live `kiucoid_kiustore` memiliki 28 base table dan 2 view. Struktur mobile utama sudah ada untuk `mobile_api_tokens`, `mobile_cart_items`, dan `mobile_shipping_quotes`, tetapi tabel `mobile_account_deletions` dan `mobile_onboarding_flags` belum ada pada database aktif saat audit.

Perbedaan penting terkait folder:

1. Main project memiliki migration canonical di `db/migrations/` sebanyak 4 file.
2. `kiustore_apps/migrasi_database/` hanya memiliki `20260819_briva_switch.sql`.
3. `application/migrasi_database/` tidak berisi file migration aktif.
4. Karena `kiustore_apps/` hanya mirror parsial, struktur database tidak boleh disimpulkan hanya dari folder mirror. Fakta schema yang valid adalah hasil inspeksi live database dan file migration canonical.

## Sumber Fakta

| Sumber | Status | Catatan |
|---|---|---|
| `application/config/database.php` | Verified | Database aktif menunjuk ke `kiucoid_kiustore`. |
| Live `information_schema` MySQL | Verified | 28 base table, 2 view. |
| `db/migrations/20260629_mobile_api.sql` | Verified file | Membuat mobile API tables termasuk `mobile_account_deletions`. |
| `db/migrations/20260728_mobile_account_deletion.sql` | Verified file | Membuat `mobile_account_deletions`. |
| `db/migrations/20260819_briva_switch.sql` | Verified file | Insert setting `briva_payment_mode = production` jika belum ada. |
| `db/migrations/20260820_mobile_onboarding_flags.sql` | Verified file | Membuat `mobile_onboarding_flags`. |
| `kiustore_apps/migrasi_database/20260819_briva_switch.sql` | Verified file | Mirror hanya membawa migration BRIVA switch. |

## Inventaris Migration

| Lokasi | File | Status terhadap DB aktif |
|---|---|---|
| `db/migrations/20260629_mobile_api.sql` | Mobile API token/cart/shipping/account deletion | Sebagian sudah tercermin: token/cart/shipping ada; account deletion belum ada. |
| `db/migrations/20260728_mobile_account_deletion.sql` | Mobile account deletion audit | Belum tercermin pada DB aktif karena tabel belum ada. |
| `db/migrations/20260819_briva_switch.sql` | Setting BRIVA mode | File ada di main dan mirror; status row `settings` tidak dimutasi dalam audit ini. |
| `db/migrations/20260820_mobile_onboarding_flags.sql` | Mobile onboarding flags | Belum tercermin pada DB aktif karena tabel belum ada. |
| `kiustore_apps/migrasi_database/20260819_briva_switch.sql` | Setting BRIVA mode | Ada di mirror, tetapi hanya satu migration. |

## Ringkasan Struktur Live Database

| Metrik | Nilai |
|---|---:|
| Base table | 28 |
| View | 2 |
| Total objek schema | 30 |

### Daftar Table dan View

| Nama | Tipe | Engine | Collation |
|---|---|---|---|
| `banner_product` | BASE TABLE | InnoDB | latin1_swedish_ci |
| `briva_api` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `ci_sessions` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `contacts` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `coupons` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `customers` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `customers_bk` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `customer_location` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `generate_kdchart` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `message` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `mobile_api_tokens` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `mobile_cart_items` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `mobile_shipping_quotes` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `orders` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `orders_bk` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `order_items` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `order_items_bk` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `payments` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `piutang` | BASE TABLE | InnoDB | latin1_swedish_ci |
| `products` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `product_category` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `promo` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `reviews` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `settings` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `tbtestongkir` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `tmp_cart` | BASE TABLE | InnoDB | utf8mb4_general_ci |
| `users` | BASE TABLE | InnoDB | utf8mb4_unicode_ci |
| `users_bk` | BASE TABLE | InnoDB | utf8mb4_unicode_ci |
| `v_products` | VIEW | - | - |
| `v_tagihan` | VIEW | - | - |

## Gap Migration Terhadap Live Schema

| Objek | Ada di migration | Ada di DB aktif | Dampak |
|---|---|---|---|
| `mobile_api_tokens` | Ya | Ya | API bearer token tersedia. |
| `mobile_cart_items` | Ya | Ya | Cart mobile database-backed tersedia. |
| `mobile_shipping_quotes` | Ya | Ya | Shipping quote mobile tersedia. |
| `mobile_account_deletions` | Ya | Tidak | Audit delete account tidak tersimpan bila fitur dijalankan; kode memiliki guard `table_exists`, jadi tidak fatal tetapi audit gap. |
| `mobile_onboarding_flags` | Ya | Tidak | Onboarding state mobile tidak persist di DB; kode memiliki guard, tetapi fitur persistent onboarding belum aktif secara schema. |
| `settings.briva_payment_mode` | Ya, insert row | Tidak dicek isi row agar audit tetap read-only | Perlu precheck data sebelum klaim mode BRIVA aktif. |

## Struktur Kolom Inti Per Area

### Mobile API

| Table | Kolom live |
|---|---|
| `mobile_api_tokens` | `id` bigint unsigned PK auto_increment; `user_id` bigint unsigned indexed; `token_hash` char(64) unique; `device_name` varchar(100); `expires_at` datetime indexed; `last_used_at` datetime; `revoked_at` datetime; `created_at` datetime. |
| `mobile_cart_items` | `id` bigint unsigned PK auto_increment; `user_id` bigint unsigned indexed; `product_id` bigint unsigned indexed; `quantity` int unsigned default 1; `unit_type` tinyint unsigned default 1; `created_at` datetime; `updated_at` datetime. |
| `mobile_shipping_quotes` | `id` bigint unsigned PK auto_increment; `user_id` bigint unsigned indexed; `origin_id` int unsigned; `destination_id` int unsigned; `weight` int unsigned; `courier` varchar(50); `options_json` mediumtext; `expires_at` datetime indexed; `used_at` datetime; `created_at` datetime. |
| `mobile_account_deletions` | Tidak ada di DB aktif. |
| `mobile_onboarding_flags` | Tidak ada di DB aktif. |

### Customer, User, Order

| Table | Kolom live yang relevan |
|---|---|
| `users` | `id` bigint unsigned PK auto_increment; `name`; `email` unique; `email_verified_at`; `password`; `profile_picture`; `role` default `0`; `register_date`; `status` default 0. |
| `customers` | `id` bigint PK auto_increment; `user_id` indexed; `nik`; `npwp`; `name`; `phone_number`; `province_id`; `kota_id`; `subdistrict_id`; `address`; `shop_name`; `shop_address`; `alamat_kirim`; `max_credit`; `level`; `profile_picture`; `salesman_id`; `kode_customer`; `va_code`. |
| `orders` | `id` bigint PK auto_increment; `user_id` indexed; `coupon_id` indexed; `order_number`; `kd_faktur`; `invoice_number`; `ttb_number`; `order_status` enum `1` sampai `12`; `order_date`; `total_price`; `total_items`; `payment_method`; `shipping_method`; `delivery_data`; `delivered_date`; `deliver_by`; `finish_date`; `due_date`; `jenis_pengiriman`; `nama_ekspedisi`; `estimasi_kirim`; `shipping_cost`; `insurance`; `rating`; `rating_desc`. |
| `order_items` | `id` bigint PK auto_increment; `order_id` indexed; `product_id` indexed; `order_qty`; `order_price`; `satuan`; `satuan_text`; `satuan_qty`. |
| `payments` | `id` bigint PK auto_increment; `order_id` indexed; `payment_price`; `payment_date`; `picture_name`; `payment_status` enum `1`,`2`,`3`; `confirmed_date`; `payment_data`. |

### Product, Promo, Coupon

| Table | Kolom live yang relevan |
|---|---|
| `products` | `id` bigint PK auto_increment; `category_id` indexed; `sku`; `name`; `description`; `picture_name`; `price`; `price_3`; `price_2`; `stock`; `current_discount`; `product_unit`; `product_unit_1`; `product_unit_2`; `product_unit_value`; `product_type`; `product_unit_weight`; `is_available`; `add_date`; `user_level`. |
| `product_category` | `id` int PK auto_increment; `name`. |
| `promo` | `id` bigint PK auto_increment; `product_id`; `credit`; `start_date`; `expired_date`; `is_active` default 1. |
| `coupons` | `id` bigint PK auto_increment; `name`; `code`; `credit`; `start_date`; `expired_date`; `is_active` default 1. |
| `banner_product` | `id` int PK auto_increment; `product_id`; `banner_image`; `created_at`. |

### Operational/Support

| Table | Kolom live yang relevan |
|---|---|
| `settings` | `id` int PK auto_increment; `key`; `content`. |
| `briva_api` | `id` int PK auto_increment; `order_number`; `kd_faktur`; `user_id`; `name`; `va_code`; `userno`; `total_price_topay`; `exp_date`; `status`; `create_at`. |
| `ci_sessions` | `id`; `ip_address`; `timestamp` indexed; `data` blob. |
| `contacts` | `id` int PK auto_increment; `parent_id` indexed; `name`; `subject`; `email`; `message`; `contact_date`; `status`; `reply_at`. |
| `message` | `id` int PK auto_increment; `salesman_id` indexed; `customer_id`; `message`; `chat_from`; `created_at`; `status`; `reply_at`. |
| `reviews` | `id` bigint PK auto_increment; `user_id` indexed; `order_id` indexed; `title`; `review_text`; `review_date`; `status`. |
| `customer_location` | `id` int PK auto_increment; `user_id` indexed; `provinsi`; `kota`; `sub_kota`. |
| `generate_kdchart` | `id` int PK auto_increment; `kdchart` indexed; `create_at`. |
| `tmp_cart` | `id` int PK auto_increment; `kdchart`; `idbarang`; `idcustomer`; `qty`; `satuan`; `satuan_text`; `satuan_qty`; `price`; `name`; `product_type`; `product_weight`; `total_weight`; `sts_ongkir`; `create_at`; `last_action`. |
| `tbtestongkir` | `id` int PK auto_increment; `jsongkir`; `kd_faktur`; `sjasa`; `idcustomer`; `no_resi`; `resi_sts`; `status`; `create_at`. |
| `piutang` | `id` int PK auto_increment; `no_faktur`; `name`; `address`; `payment_price`; `pay` default 0; `payment_date`; `payment_status`; `confirm_date`. |

## Struktur View

| View | Fungsi |
|---|---|
| `v_products` | Menggabungkan `products` dengan promo aktif untuk menghasilkan harga promo, diskon, stock, dan level product. |
| `v_tagihan` | Menghitung tagihan customer dari order kredit (`payment_method = 1`) dengan `order_status < 6`, group by `user_id`, dan membawa `max_credit`. |

## Accomplishment

- Struktur live DB berhasil diverifikasi langsung via `information_schema`.
- Migration main dan migration mirror berhasil dipetakan.
- Gap antara migration dan live schema sudah dipisahkan tanpa melakukan perubahan.

## Issues & Root Cause

| Issue | Root Cause | Dampak |
|---|---|---|
| `mobile_account_deletions` belum ada di DB aktif. | Migration tersedia tetapi belum tercermin pada schema live. | Audit delete account mobile tidak persist. |
| `mobile_onboarding_flags` belum ada di DB aktif. | Migration tersedia di main `db/migrations`, tetapi tidak ada di mirror dan belum tercermin live. | Onboarding mobile tidak persist secara table-based. |
| `kiustore_apps/` hanya membawa satu migration. | Mirror database migration tidak lengkap terhadap main project. | Deploy dari mirror saja dapat melewatkan kebutuhan schema mobile terbaru. |
| Collation tidak seragam (`latin1_swedish_ci`, `utf8mb4_general_ci`, `utf8mb4_unicode_ci`). | Riwayat dump/migration berbeda. | Risiko compare string/sorting lintas tabel, perlu perhatian saat migration production. |

## Next Steps & Risk Mitigation

| Prioritas | Rekomendasi | SQL/Validasi |
|---|---|---|
| P1 | Sebelum aktivasi fitur onboarding mobile, jalankan migration `db/migrations/20260820_mobile_onboarding_flags.sql` pada backup/staging. | `SHOW TABLES LIKE 'mobile_onboarding_flags';` |
| P1 | Jika fitur delete account mobile wajib audit, jalankan migration `db/migrations/20260728_mobile_account_deletion.sql` atau migration induk yang setara. | `SHOW TABLES LIKE 'mobile_account_deletions';` |
| P1 | Jangan menganggap `kiustore_apps/migrasi_database` sebagai migration lengkap. Gunakan `db/migrations` sebagai sumber canonical saat ini. | Bandingkan daftar file migration sebelum deploy. |
| P2 | Precheck row `settings.briva_payment_mode` sebelum klaim BRIVA switch aktif. | `SELECT * FROM settings WHERE \`key\` = 'briva_payment_mode';` |
| P2 | Jika akan deploy ke production, dump schema dulu lalu compare object/table/column/collation. | Gunakan `mysqldump --no-data` dan compare dengan schema target. |

## Status Akhir

Tidak ada perubahan schema pada audit ini. Kesimpulan database: main memiliki kebutuhan migration lebih lengkap daripada `kiustore_apps`, dan DB aktif masih memiliki dua gap schema untuk audit delete account serta persistent mobile onboarding.
