# Development Banner Produk Flexible Redirect

Tanggal: 2026-09-03
Modul: Admin - Banner Produk

## Ringkasan

Module `admin/banner_product` diperluas agar banner yang sudah di-upload admin dapat diubah kembali. Admin sekarang dapat mengisi title banner secara manual, memilih tujuan redirect banner ke produk/kategori/URL manual, serta mengatur urutan dan status tampil banner.

## Verified System Facts

- Source pengembangan berada di folder `application/`.
- Controller utama: `application/modules/admin/controllers/Banner_product.php`.
- Model admin: `application/modules/admin/models/Product_model.php`.
- Form tambah dan edit memakai view yang sama: `application/modules/admin/views/products/add_new_banner_product.php`.
- Halaman daftar banner: `application/modules/admin/views/products/banner_product.php`.
- Konsumsi banner customer: `application/modules/customer/models/Product_model.php` dan `application/modules/customer/views/home.php`.
- Konsumsi banner mobile API: `application/modules/api/models/Mobile_api_model.php`.

## Perubahan Aplikasi

- Menambahkan mode edit banner melalui route controller `admin/banner_product/edit_banner_product/{banner_id}`.
- Menambahkan proses update melalui `admin/banner_product/update_banner_product`.
- Menambahkan input manual `banner_title`.
- Menambahkan input `display_order` dan toggle `is_active`.
- Menambahkan pilihan redirect:
  - `product`: wajib memilih produk tujuan.
  - `category`: wajib memilih kategori tujuan.
  - `custom`: wajib mengisi route internal atau URL eksternal `http/https`.
- Menambahkan tombol `Simpan Setting Tampilan` pada halaman daftar banner untuk adjustment massal urutan dan status tampil.
- Gambar banner pada edit bersifat opsional. Jika gambar baru di-upload dan update berhasil, file lama dihapus dari folder upload.
- Homepage customer menggunakan `target_url` hasil perhitungan model, bukan lagi hard-code ke route produk.
- Homepage customer dan API mobile hanya mengambil banner aktif, diurutkan dari `display_order` terkecil, maksimal 3 item.
- API mobile banner tetap mengirim field lama `product_id`, dan menambah metadata `category_id`, `redirect_type`, `redirect_url`, `target_url`, `display_order`, serta `is_active`.

## Validasi

- Validasi form server-side memastikan title tidak kosong.
- Redirect produk harus merujuk ke produk valid.
- Redirect kategori harus merujuk ke kategori valid.
- Redirect custom menolak URL berbahaya seperti `javascript:`, `data:`, `vbscript:`, dan protocol-relative URL `//domain`.
- Admin tidak dapat menyimpan lebih dari 3 banner aktif.
- Query pembacaan banner dibuat kompatibel dengan data lama melalui pengecekan kolom database.

## Risiko Dan Mitigasi

| Risiko | Dampak Bisnis | Mitigasi |
|---|---|---|
| SQL migrasi belum dijalankan | Simpan banner fleksibel akan ditolak karena kolom target belum tersedia | Jalankan SQL di `docs/database/2026-09-03-banner-product-flexible-redirect.sql` sebelum UAT admin |
| Admin mengisi URL manual salah | Customer diarahkan ke halaman tidak tepat | Validasi format URL dilakukan server-side; UAT perlu mencoba setiap tipe redirect |
| Aplikasi mobile lama belum memakai `target_url` | Mobile lama tetap mungkin hanya memakai `product_id` | Field lama tetap dipertahankan, dan aplikasi mobile baru dapat mulai memakai `target_url` |
| Lebih dari 3 banner aktif | Homepage menjadi terlalu padat dan tidak sesuai batas bisnis | Controller menolak simpan jika banner aktif lebih dari 3 |

## UAT Yang Direkomendasikan

- Login admin, buka `admin/banner_product`.
- Tambah banner dengan title manual dan redirect produk.
- Edit banner yang sama menjadi redirect kategori tanpa mengganti gambar.
- Edit banner menjadi redirect URL manual, misalnya `promo`.
- Atur urutan beberapa banner dari halaman daftar, lalu klik `Simpan Setting Tampilan`.
- Nonaktifkan salah satu banner agar jumlah item yang tampil berkurang.
- Coba aktifkan lebih dari 3 banner dan pastikan sistem menolak.
- Buka homepage customer dan klik banner untuk memastikan target sesuai.
- Panggil endpoint `api/v1/banners` dan pastikan `target_url` sesuai pilihan redirect.
