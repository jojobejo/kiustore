# Database Change: Zahir Stock Product Alias

Tanggal: 2026-08-31

## Tujuan Database

Menambahkan master mapping nama barang Zahir Digital ke produk Karisma Online untuk mendukung pengecualian nama tanpa mengubah master `products.name` dan tanpa mengubah data di Zahir Digital.

## Migration

```text
db/migrations/20260831_zahir_stock_product_aliases.sql
```

## Tabel Baru

### `zahir_stock_product_aliases`

Menyimpan mapping alias aktif dari nama Zahir ke produk target Karisma Online.

Kolom:

- `id`: primary key.
- `zahir_name`: nama barang sebagaimana muncul dari export/API Zahir.
- `normalized_zahir_name`: nama Zahir yang sudah dinormalisasi untuk lookup cepat.
- `product_id`: referensi produk Karisma bila sudah berhasil di-resolve.
- `product_name`: nama produk target Karisma dari file mapping.
- `active`: status aktif mapping.
- `notes`: catatan sumber mapping.
- `created_by`: reserved untuk user pembuat mapping.
- `approved_by`: reserved untuk user approval mapping.
- `created_at`: waktu insert awal.
- `updated_at`: waktu update mapping.

Index:

- Unique `normalized_zahir_name` untuk mencegah satu nama Zahir aktif mengarah ke banyak mapping.
- Index `product_id` untuk lookup produk target.
- Index `active` untuk filter mapping yang boleh dipakai integrasi.

Collation:

- Tabel dikunci ke `utf8mb4_general_ci` agar selaras dengan `products.name`.
- Ini mencegah error production `#1267 - Illegal mix of collations` saat migration melakukan resolve `product_id` dengan perbandingan nama produk.

## Seed Awal

Migration melakukan seed 48 mapping dari:

```text
C:\Users\bram\Documents\mapping_zahir_barang.xlsx
```

Kolom Excel:

- `Nama Barang` -> `zahir_name`
- `Karisma Online` -> `product_name`

Setelah insert/update mapping, migration menjalankan resolve `product_id` dengan rule:

```sql
(LOWER(TRIM(REPLACE(products.name, '*', ''))) COLLATE utf8mb4_general_ci) = (LOWER(TRIM(REPLACE(zahir_stock_product_aliases.product_name, '*', ''))) COLLATE utf8mb4_general_ci)
```

Rule ini selaras dengan proses aplikasi yang menghapus karakter `*` sebelum pencocokan.

## Dampak Ke Tabel Existing

Tidak ada perubahan struktur pada tabel existing:

- `products`
- `zahir_stock_import_batches`
- `zahir_stock_import_items`

Namun nilai `zahir_stock_import_items.match_status` kini dapat berisi status tambahan:

```text
ALIAS_MATCHED
```

Kolom existing bertipe `VARCHAR(32)`, sehingga tidak membutuhkan alter table.

## Proses Update Stock

Approve tetap menjalankan:

```sql
UPDATE products
SET stock = <qty_zahir>
WHERE id = <product_id_match>;
```

Untuk lebih dari satu nama Zahir yang mengarah ke `product_id` sama, aplikasi menjumlahkan Qty terlebih dahulu dan melakukan update satu kali ke produk target.

## Rollback

Rollback perubahan schema dapat dilakukan dengan:

```sql
DROP TABLE IF EXISTS `zahir_stock_product_aliases`;
```

Rollback ini hanya menghapus master mapping alias. Data produk, batch import, dan stock yang sudah pernah diapprove tidak otomatis dikembalikan.

## Kesimpulan Schema

Ada perubahan schema berupa penambahan tabel `zahir_stock_product_aliases`. Tidak ada perubahan schema pada tabel produk dan tracking import existing.

## Validasi Lokal

Migration berhasil dijalankan pada database lokal `kiucoid_kiustore`:

- total alias: 48
- alias aktif: 48
- berhasil resolve ke `product_id`: 48
- belum resolve: 0

Contoh validasi:

- `Aneto 50 EC 50 X 250 ml + 1 pcs Neptune 8 gr` resolve ke `products.id = 610`, `products.name = Aneto 50 EC 50 X 250 ml`.
- `Pinamec 20 EC 20 X 1 ltr (Hitam)` memiliki 2 alias Zahir; aplikasi menjumlahkan Qty per produk saat approve.
