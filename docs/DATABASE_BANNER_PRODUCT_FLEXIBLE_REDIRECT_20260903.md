# Database Banner Produk Flexible Redirect

Tanggal: 2026-09-03
Database: `kiucoid_kiustore`
Tabel: `banner_product`

## Kesimpulan

Perubahan ini memerlukan perubahan schema database. Tabel `banner_product` perlu ditambah kolom metadata untuk title manual, konfigurasi redirect fleksibel, urutan tampil, dan status aktif.

## Kondisi Sebelum

Berdasarkan dokumen perbandingan database sebelumnya, kolom live yang relevan pada `banner_product` adalah:

| Kolom | Fungsi |
|---|---|
| `id` | Primary key banner |
| `product_id` | Target produk lama |
| `banner_image` | Nama file gambar banner |
| `created_at` | Waktu pembuatan |

## Kolom Baru

| Kolom | Tipe | Fungsi |
|---|---|---|
| `banner_title` | `VARCHAR(150) NULL` | Title manual yang diinput admin |
| `redirect_type` | `ENUM('product','category','custom') NOT NULL DEFAULT 'product'` | Jenis tujuan banner |
| `redirect_product_id` | `INT(11) NULL` | Target produk untuk redirect produk |
| `redirect_category_id` | `INT(11) NULL` | Target kategori untuk redirect kategori |
| `redirect_url` | `VARCHAR(255) NULL` | Target route internal atau URL eksternal |
| `display_order` | `INT(11) NOT NULL DEFAULT 0` | Nomor urut tampilan banner; angka terkecil tampil lebih dulu |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` | Status banner tampil atau disembunyikan |

## SQL Migrasi

File migrasi:

`docs/database/2026-09-03-banner-product-flexible-redirect.sql`

Isi utama:

```sql
ALTER TABLE banner_product
  ADD COLUMN IF NOT EXISTS banner_title VARCHAR(150) NULL AFTER banner_image,
  ADD COLUMN IF NOT EXISTS redirect_type ENUM('product','category','custom') NOT NULL DEFAULT 'product' AFTER banner_title,
  ADD COLUMN IF NOT EXISTS redirect_product_id INT(11) NULL AFTER redirect_type,
  ADD COLUMN IF NOT EXISTS redirect_category_id INT(11) NULL AFTER redirect_product_id,
  ADD COLUMN IF NOT EXISTS redirect_url VARCHAR(255) NULL AFTER redirect_category_id,
  ADD COLUMN IF NOT EXISTS display_order INT(11) NOT NULL DEFAULT 0 AFTER redirect_url,
  ADD COLUMN IF NOT EXISTS is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER display_order;
```

## Backfill Data Lama

Data lama tetap dipertahankan. Banner lama diperlakukan sebagai redirect produk jika `product_id > 0`, title kosong akan diisi dengan pola `Banner Produk #{id}`, `display_order` awal mengikuti `id`, dan `is_active` default aktif saat migrasi dijalankan.

## Aturan Tampilan

- Admin boleh menyimpan banner lebih dari 3 sebagai stok item.
- Hanya maksimal 3 banner berstatus aktif yang boleh dipilih di halaman admin.
- Homepage customer dan API mobile mengambil banner aktif saja, diurutkan dari `display_order` terkecil, maksimal 3 item.

## Catatan Eksekusi

- MySQL lokal tidak dapat dijangkau saat validasi development karena service menolak koneksi ke `localhost`.
- SQL belum dieksekusi pada database lokal dari sesi ini.
- Jalankan SQL migrasi sebelum melakukan UAT simpan/tambah/edit banner fleksibel.
- Setelah migrasi, verifikasi dengan:

```sql
SHOW FULL COLUMNS FROM banner_product;
SELECT id, product_id, banner_title, redirect_type, redirect_product_id, redirect_category_id, redirect_url, display_order, is_active
FROM banner_product
ORDER BY display_order ASC, id DESC;
```
