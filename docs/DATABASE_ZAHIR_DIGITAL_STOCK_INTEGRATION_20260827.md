# Database Note: Integrasi Stock Zahir Digital

Tanggal: 2026-08-27

## Kesimpulan Skema

Tidak ada perubahan skema database pada module ini.

Module memakai tabel existing:

- `products.id`
- `products.name`
- `products.stock`
- `products.sku`
- field wajib produk lain untuk insert otomatis dari Zahir Digital

Verifikasi lokal pada database `kiucoid_kiustore`:

- `products.name`: `varchar(191) NOT NULL`
- `products.stock`: `int(10) NOT NULL`
- Jumlah produk lokal saat pengecekan: 737

## Proses Update Saat Approve

Approve menjalankan update terbatas pada produk yang dipilih admin dan match dengan data Zahir Digital terbaru:

```sql
UPDATE products
SET stock = <qty_zahir>
WHERE id = <product_id_match>;
```

Update dijalankan dalam transaction CodeIgniter. Jika transaction gagal, sistem menampilkan pesan gagal dan tidak mengklaim approve berhasil.

`Bulk All Update Data Semua` memakai dataset match terbaru dari server dan menjalankan pola update yang sama untuk seluruh produk yang masih match pada saat approve.

## Proses Insert Saat Barang Zahir Belum Ada

Tombol centang hijau pada daftar `Barang Zahir Tidak Ada di Produk Karisma Online` menjalankan insert ke `products` dengan data minimum yang memenuhi schema aktif:

```sql
INSERT INTO products (
  category_id, sku, name, description, picture_name,
  price, price_2, price_3, stock, current_discount,
  product_unit, product_unit_1, product_unit_2, product_unit_value,
  product_type, product_unit_weight, is_available, add_date, user_level
) VALUES (
  NULL, <auto_sku>, <nama_barang_zahir>, <deskripsi_import>, NULL,
  0, 0, 0, <qty_zahir>, 0,
  'PCS', 'PCS', '', '1',
  'GENERAL', 0, 1, NOW(), 0
);
```

Insert divalidasi ulang terhadap data Zahir terbaru dan dibatalkan bila nama barang sudah tidak berada di daftar Zahir-only.

## Export Stock

Export stock bersifat read-only dan membaca:

- Data olahan Zahir dari API integration payload.
- `products.name` sebagai `Nama Barang`.
- `products.stock` sebagai `Qty`.

Tidak ada mutasi database saat export.

## Matching Key

Matching dilakukan dengan `products.name` dibandingkan ke `Nama Barang` Zahir Digital setelah normalisasi:

- hapus tag HTML,
- decode HTML entity,
- hilangkan karakter `*`,
- gabungkan spasi berlebih,
- trim awal/akhir,
- lowercase.

Tidak ada matching berdasarkan SKU karena permintaan bisnis menyebut group dan compare berdasarkan nama barang.

## Catatan Audit Produksi

Sebelum approve produksi, admin wajib melakukan review pada tiga daftar:

1. Produk match yang akan diupdate.
2. Barang Zahir yang tidak ada di produk Karisma Online.
3. Produk Karisma Online yang tidak ada di Zahir Digital.

Karena module ini tidak membuat tabel audit baru, evidence approve produksi disarankan diambil dari screenshot halaman compare, export Excel data olahan, dan backup database sebelum approve massal.

## Sumber API Zahir

Karisma Online membaca endpoint JSON baru:

```text
https://10.10.10.12/zahirdigital/SALES/GLOBAL/stockready_api.php
```

Endpoint Zahir bersifat read-only terhadap Firebird dan tidak mengubah schema atau data di Zahir Digital.
