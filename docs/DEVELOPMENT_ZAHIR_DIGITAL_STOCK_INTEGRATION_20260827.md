# Development Module: Integrasi Stock Zahir Digital

Tanggal: 2026-08-27

## Tujuan

Menyediakan halaman admin baru untuk mengambil data stock ready dari Zahir Digital, mengolah data sesuai rules bisnis, membandingkannya dengan produk Karisma Online, lalu memberi admin fungsi approve sebelum stock Karisma Online diperbarui.

## Route dan File

- Halaman admin: `admin/zahir-stock`
- Endpoint JSON audit data: `admin/zahir-stock/data`
- Endpoint approve update stock: `admin/zahir-stock/approve`
- Endpoint export Excel stock: `admin/zahir-stock/export-stock-excel`
- Controller: `application/modules/admin/controllers/Zahir_stock.php`
- Model: `application/modules/admin/models/Zahir_stock_model.php`
- View: `application/modules/admin/views/zahir_stock/index.php`
- Config sumber: `application/config/zahirdigital.php`
- Menu admin: `application/modules/admin/views/header.php`

## Sumber Data Zahir Digital

URL default:

```text
https://10.10.10.12/zahirdigital/SALES/GLOBAL/stockready_api.php
```

Hasil pengecekan awal terhadap halaman lama `stockready.php` dari development machine pada 2026-08-27 mengembalikan HTTP `401 Authentication required`. Solusinya dibuat di Zahir Digital dengan endpoint JSON baru `stockready_api.php`, lalu Karisma Online mengirim token integrasi:

```php
$config['zahir_stockready_token'] = 'karisma-zahir-stock-20260827';
```

Konfigurasi Basic Auth tetap tersedia sebagai fallback bila endpoint internal berubah:

```php
$config['zahir_stockready_username'] = '';
$config['zahir_stockready_password'] = '';
```

Endpoint baru sudah dites dan mengembalikan JSON `success=true`.

## Rules Olah Data

Rules dijalankan sebelum data dibandingkan dan sebelum API/view menggunakan data tersebut:

1. Karakter `*` dihilangkan dari kolom `Nama Barang`; barisnya tidak dibuang.
2. `Nama Barang` dibersihkan dengan `strip_tags`, `html_entity_decode`, penghapusan karakter `*`, penggabungan spasi berlebih, dan `trim`.
3. Data digroup ulang menjadi list nama barang bersih yang unik dan Qty dijumlahkan berdasarkan nama barang tersebut.
4. View menyajikan data olahan dengan header Excel: `Nama Barang`, `Qty`.

Parser sumber mendukung JSON, HTML table, CSV, semicolon-delimited, dan TSV dengan variasi header umum seperti `nama_barang`, `Nama Barang`, `Qty`, `Qty Ready`, `stock`, atau `stok`.

## Alur Halaman Admin

1. Admin membuka `admin/zahir-stock`.
2. Sistem fetch sumber Zahir Digital dan mengolah data sesuai rules.
3. Sistem membandingkan nama barang Zahir dengan `products.name` Karisma Online menggunakan normalisasi spasi dan lowercase.
4. Halaman menampilkan:
   - Data Zahir Digital yang telah diolah sebagai data pendukung tersembunyi.
   - Produk yang match dan bisa dipilih untuk approve dalam tiga tab: `Selisih` untuk selisih minus, `Plus` untuk selisih positif, dan `Semua` untuk seluruh data match.
   - Barang Zahir yang tidak ada di produk Karisma Online, lengkap dengan aksi centang hijau untuk insert otomatis ke `products`.
   - Produk Karisma Online yang tidak ada di Zahir Digital.
5. Admin memilih produk match dan menekan `Approve Terpilih`; tombol menyala saat ada pilihan dan menampilkan badge total terpilih.
6. Admin dapat menekan `Bulk All Update Data Semua` untuk update seluruh produk match terbaru.
7. Sistem mengambil ulang data Zahir terbaru, mencocokkan ulang produk terpilih atau seluruh produk match, lalu update `products.stock` sesuai Qty Zahir dalam transaksi database.

## Kontrol Risiko

- Tidak ada update otomatis saat halaman dibuka.
- Approve hanya tersedia untuk produk yang match exact setelah normalisasi nama.
- Approve mengambil ulang data Zahir Digital untuk mengurangi risiko memakai data lama dari halaman.
- Produk yang tidak lagi match saat approve akan dilewati dan dihitung sebagai skipped.
- Jika sumber Zahir gagal diambil, approve dibatalkan.
- Checkbox terpilih disimpan di state JavaScript agar pilihan tetap terkirim walaupun tabel match memakai pagination 10 baris per halaman.
- Aksi insert barang Zahir-only memakai AJAX, memvalidasi ulang data Zahir terbaru, lalu menghapus baris dari tabel tanpa reload halaman.
- Export stock memakai tombol server-side di header card `Barang Zahir Tidak Ada di Produk Karisma Online`; tombol export bawaan view tabel/DataTables tidak ditampilkan.

## Insert Produk Dari Barang Zahir Tidak Ada

Tombol centang hijau pada tabel `Barang Zahir Tidak Ada di Produk Karisma Online` menjalankan endpoint:

```text
admin/zahir-stock/insert-product
```

Sistem hanya mengizinkan insert jika nama barang masih berada di daftar Zahir-only pada data terbaru. Field wajib `products` diisi dengan default minimum:

- `name`: nama barang hasil olah Zahir.
- `stock`: Qty Zahir.
- `sku`: auto prefix `ZD`.
- `price`, `price_2`, `price_3`, `current_discount`, `product_unit_weight`, `user_level`: `0`.
- `product_unit`, `product_unit_1`: `PCS`.
- `product_unit_value`: `1`.
- `product_type`: `GENERAL`.
- `is_available`: `1`.
- `description`: penanda import otomatis dari Zahir Digital.

## Export Data Stock

Tombol `Export Data Stock` menjalankan:

```text
admin/zahir-stock/export-stock-excel
```

File Excel berisi dua tabel:

1. `Data Olah Stock Zahir Digital`: hasil hit API Zahir setelah rules olah data, kolom `Nama Barang` dan `Qty`.
2. `Data Stock Products Karisma Online`: data dari tabel `products`, kolom `Nama Barang` dan `Qty`.

## Status Verifikasi

- PHP lint lulus untuk controller, model, view, config, route, dan header admin.
- Fetch halaman lama `stockready.php` mengembalikan HTTP 401 dengan `WWW-Authenticate: Basic realm="admin"`.
- Fetch endpoint baru `stockready_api.php` dengan header `X-Karisma-Stock-Token` berhasil dan mengembalikan JSON `success=true`.
- Authenticated admin browser UAT dan approve transaksi real belum dijalankan.
