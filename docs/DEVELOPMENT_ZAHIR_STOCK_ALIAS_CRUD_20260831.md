# Development Module: CRUD Alias Nama Barang Zahir

Tanggal: 2026-08-31

## Tujuan

Menyediakan halaman admin untuk mengolah master alias nama barang Zahir Digital ke produk Karisma Online tanpa edit langsung ke database dan tanpa mengubah nama barang di Zahir maupun nama produk di Karisma Online.

## Route Baru

- `admin/zahir-stock-alias`
- `admin/zahir-stock-alias/store`
- `admin/zahir-stock-alias/update/<id>`
- `admin/zahir-stock-alias/delete/<id>`

## File Aplikasi

- `application/config/routes.php`
- `application/modules/admin/controllers/Zahir_stock_alias.php`
- `application/modules/admin/models/Zahir_stock_model.php`
- `application/modules/admin/views/zahir_stock_alias/index.php`
- `application/modules/admin/views/zahir_stock/index.php`
- `application/modules/admin/views/header.php`

## Alur UI

1. Admin membuka `admin/zahir-stock`.
2. Pada baris card statistik, admin dapat klik card `Setting Alias Nama Barang Zahir`.
3. Sistem membuka halaman `admin/zahir-stock-alias`.
4. Admin dapat:
   - melihat daftar alias,
   - menambah alias baru,
   - edit alias existing,
   - memilih produk Karisma Online melalui dropdown pencarian,
   - mengaktifkan atau menonaktifkan alias,
   - menonaktifkan alias melalui tombol hapus.

## Perubahan Pada Halaman Stock Zahir

- Card `Match Produk` tidak lagi menampilkan teks kecil `Alias <total>`.
- Tombol `Setting Alias Nama Barang Zahir` ditambahkan sebagai card tekan dalam satu baris dengan card statistik.
- Tabel match tetap menampilkan jenis match pada kolom `Jenis Match` agar admin dapat membedakan match normal dan match alias saat melakukan approve.

## Aturan CRUD

- Nama barang Zahir wajib diisi.
- Produk Karisma Online wajib dipilih.
- Nama barang Zahir dinormalisasi dengan rule yang sama seperti integrasi stock:
  - strip HTML,
  - remove karakter `*`,
  - normalisasi spasi,
  - lowercase untuk key pencocokan.
- Satu `normalized_zahir_name` hanya boleh terdaftar satu kali.
- Delete pada UI berarti menonaktifkan alias (`active = 0`), bukan hard delete, agar keputusan mapping tetap bisa ditelusuri.

## Kontrol Risiko

- CRUD alias tidak mengubah `products.name`.
- CRUD alias tidak mengubah stok.
- Alias baru hanya mempengaruhi proses compare berikutnya pada `admin/zahir-stock`.
- Update stock tetap hanya terjadi setelah admin approve pada module integrasi stock.
- Jika alias dinonaktifkan, nama Zahir tersebut tidak lagi dipakai sebagai match alias.

## Validasi Development

- PHP lint source dan mirror untuk controller, model, view, route, dan header: lulus.
- Sync selektif ke `kiustore_apps` diverifikasi dengan hash file yang sama.
- Browser UAT login admin tetap perlu dilakukan untuk memastikan flow klik card, tambah, edit, dan nonaktifkan berjalan pada session aplikasi.
