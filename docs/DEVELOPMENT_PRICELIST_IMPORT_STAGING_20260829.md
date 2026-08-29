# Development Module: Staging Import Pricelist Harga

Tanggal: 2026-08-29

## Tujuan

Menyediakan module admin untuk upload Excel pricelist, mengolah deskripsi barang, mencocokkan ke `products.name`, menampilkan preview perubahan harga, lalu menunggu approval admin sebelum mengubah `products.price`, `products.price_2`, dan `products.price_3`.

## Route dan File

- Halaman admin: `admin/pricelist-import`
- Endpoint upload: `admin/pricelist-import/import`
- Endpoint approve: `admin/pricelist-import/approve`
- Endpoint export barang pricelist-only: `admin/pricelist-import/export-pricelist-only-excel`
- Endpoint export barang product-only: `admin/pricelist-import/export-product-only-excel`
- Controller: `application/modules/admin/controllers/Pricelist_import.php`
- Model: `application/modules/admin/models/Pricelist_import_model.php`
- View: `application/modules/admin/views/pricelist_import/index.php`
- Menu admin: `application/modules/admin/views/header.php`
- Upload file: `assets/uploads/pricelist_import/`

## Alur Kerja

1. Admin/Keuangan upload file `.xlsx`, `.xls`, `.csv`, `.txt`, atau `.tsv`.
2. Sistem membaca file dan mencari kolom `Deskripsi`, harga, `Tgl Info`, dan `Keterangan Asal Info Perubahan Harga`.
3. Sistem membersihkan deskripsi dengan `strip_tags`, decode HTML entity, menghapus karakter `*`, normalisasi spasi, dan `trim`.
4. Sistem group by deskripsi bersih.
5. Sistem validasi:
   - Deskripsi kosong masuk `INVALID`.
   - Harga dasar/R1 kosong atau tidak valid masuk `INVALID`.
   - Deskripsi bersih sama dengan harga berbeda masuk `CONFLICT`.
   - Deskripsi bersih sama dengan harga sama digroup dan diberi catatan duplikat.
   - Duplikat `products.name` pada key normalisasi yang sama masuk konflik agar tidak salah update produk.
6. Sistem cocokkan data olahan ke `products.name`.
7. Sistem menampilkan preview:
   - Barang tidak ada di Karisma Online tapi ada di pricelist.
   - Barang tidak ada di pricelist tapi ada di Karisma Online.
   - Harga berubah.
   - Data invalid.
8. Admin approve item terpilih atau semua item harga berubah.
9. Sistem update hanya item `MATCHED` + `PRICE_CHANGED` + `PENDING` ke `products.price`, `products.price_2`, dan `products.price_3` dalam transaction.

## Export Excel Sajian Data

Pada card `Sajian Data Hasil Olah Pricelist` tersedia dua tombol export server-side:

- `Export Data barang ada di PL tidak ada pada karisma online`
  - Route: `admin/pricelist-import/export-pricelist-only-excel`
  - Data: item batch aktif dengan `match_status = 'PRICELIST_ONLY'`
  - Kolom: `Kode Barang`, `Deskripsi Bersih`, `Harga`, `Harga R1`, `Harga R2`, `Supplier`, `Tgl Info`, `Keterangan Asal Info Perubahan Harga`
- `Export Data Barang ada di karisma online tidak ada pada PL`
  - Route: `admin/pricelist-import/export-product-only-excel`
  - Data: item batch aktif dengan `match_status = 'PRODUCT_ONLY'`
  - Kolom: `Product ID`, `Produk Karisma Online`, `Harga`, `Harga R1`, `Harga R2`

## Mapping Harga

Mapping mengikuti kolom eksplisit bila tersedia:

- `products.price`: kolom `Harga Umum`, `Harga 1`, atau `price`.
- `products.price_2`: kolom `Harga R1`, `Harga Terendah/QTY/R1/Partai`, `R1`, atau harga dasar dari kolom harga qty.
- `products.price_3`: kolom `Harga R2`, `Harga Ecer/Kios/R2`, `R2`, atau `Harga Ecer`.

Untuk format contoh `docs/format_pricelist.xlsx` yang banyak menyimpan harga dasar/R1 di kolom `Harga Terendah/QTY/R1/Partai` atau matrix `Harga QTY`, sistem mengolah:

- `price_2` = harga dasar/R1 hasil import.
- `price_3` = nilai eksplisit R2 jika ada; jika tidak ada, dihitung `price_2 * 102%`.
- `price` = nilai eksplisit harga umum jika ada; jika tidak ada, dihitung `price_2 * 105%`.

Formula fallback ini dipakai agar tiga field `products.price`, `products.price_2`, dan `products.price_3` tetap terisi saat file hanya membawa harga dasar/R1.

## Kontrol Risiko

- Tidak ada update harga saat upload atau saat halaman dibuka.
- Semua hasil import disimpan sebagai staging batch/item.
- Item invalid, konflik, pricelist-only, product-only, dan harga sama tidak dapat diapprove sebagai update harga.
- Semua item staging dinormalisasi ke daftar kolom database yang sama sebelum `insert_batch`, termasuk kategori `PRODUCT_ONLY`, agar batch campuran tidak menghasilkan query insert tanpa kolom.
- Approve memakai checkbox dengan state lintas halaman DataTables.
- Saat approve, sistem membaca ulang harga produk saat itu dan menyimpannya kembali ke item import sebelum update sebagai audit old value aktual.
- Kolom audit `Tgl Info` dan `Keterangan Asal Info Perubahan Harga` disimpan di item import, bukan di `products`.

## Status Verifikasi

- PHP lint ditargetkan untuk controller, model, view, route, dan header admin.
- Migration lokal ditargetkan ke database `kiucoid_kiustore`.
- Browser UAT authenticated dan approve real perlu dilakukan dengan file representatif sebelum klaim production-ready.
