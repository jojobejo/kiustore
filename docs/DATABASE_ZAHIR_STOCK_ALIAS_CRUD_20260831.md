# Database Note: CRUD Alias Nama Barang Zahir

Tanggal: 2026-08-31

## Kesimpulan Database

Tidak ada migration baru untuk tahap CRUD alias.

Module CRUD memakai tabel yang sudah dibuat pada migration:

```text
db/migrations/20260831_zahir_stock_product_aliases.sql
```

## Tabel Yang Dipakai

### `zahir_stock_product_aliases`

CRUD alias membaca dan menulis kolom:

- `zahir_name`
- `normalized_zahir_name`
- `product_id`
- `product_name`
- `active`
- `notes`
- `created_by`
- `approved_by`
- `created_at`
- `updated_at`

## Operasi Database

### Create

Menambahkan baris alias baru dengan `active = 1` bila checkbox aktif dipilih. `product_name` disalin dari `products.name` berdasarkan `product_id` yang dipilih admin.

### Read

Menampilkan semua alias, baik aktif maupun nonaktif, dengan join ke `products` untuk menampilkan nama produk target terkini.

### Update

Memperbarui nama Zahir, produk target, catatan, dan status aktif alias.

### Delete

Delete UI tidak menjalankan hard delete. Sistem hanya mengubah:

```sql
UPDATE zahir_stock_product_aliases
SET active = 0,
    updated_at = NOW()
WHERE id = <alias_id>;
```

## Dampak Ke Tabel Existing

Tidak ada perubahan schema pada:

- `products`
- `zahir_stock_import_batches`
- `zahir_stock_import_items`

CRUD alias juga tidak mengubah stock produk. Perubahan stock tetap hanya dilakukan oleh proses approve pada `admin/zahir-stock`.
