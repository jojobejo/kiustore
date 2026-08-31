# Development Module: Alias Mapping Stock Zahir Digital

Tanggal: 2026-08-31

## Tujuan

Menambahkan layer pengecualian nama barang untuk route `admin/zahir-stock` agar data stock Zahir Digital tetap dapat diintegrasikan ke produk Karisma Online tanpa mengubah nama barang di Zahir Digital dan tanpa mengubah nama produk di Karisma Online.

## Sumber Data Mapping

Mapping awal berasal dari file:

```text
C:\Users\bram\Documents\mapping_zahir_barang.xlsx
```

File dibaca sebagai data referensi, bukan sebagai instruksi sistem. Sheet aktif yang dipakai adalah `Sheet1` dengan kolom:

- `Nama Barang`: nama barang dari export Zahir Digital.
- `Karisma Online`: nama produk target di Karisma Online.

Total mapping awal yang diambil: 48 baris.

## File Aplikasi Yang Diubah

- `application/modules/admin/controllers/Zahir_stock.php`
- `application/modules/admin/models/Zahir_stock_model.php`
- `application/modules/admin/views/zahir_stock/index.php`

## File Database

- `db/migrations/20260831_zahir_stock_product_aliases.sql`

## Alur Baru

1. Admin membuka `admin/zahir-stock` atau upload file export Zahir melalui form import.
2. Sistem tetap menjalankan rules olah data existing:
   - karakter `*` dihilangkan,
   - spasi dinormalisasi,
   - nama barang digroup,
   - Qty dijumlahkan per nama barang hasil olah.
3. Sistem membaca master alias aktif dari tabel `zahir_stock_product_aliases`.
4. Pencocokan berjalan dengan prioritas:
   - jika nama Zahir ada di master alias aktif, sistem menggunakan produk target dari alias,
   - jika tidak ada alias, sistem memakai exact match normal terhadap `products.name`.
5. Baris yang match via alias ditampilkan di tabel match dengan badge `Alias`.
6. Baris yang match normal ditampilkan dengan badge `Normal`.
7. Admin tetap wajib klik `Approve Terpilih` atau `Bulk All Update Data Semua` sebelum `products.stock` berubah.
8. Jika beberapa nama Zahir mengarah ke satu produk Karisma yang sama, proses approve menggabungkan Qty per `product_id` lalu update stok satu kali ke produk tersebut.

## Dampak Ke Proses Import

Batch import tetap masuk ke tracking `zahir_stock_import_batches` dan `zahir_stock_import_items`.

Nilai `match_status` baru:

- `MATCHED`: match normal berdasarkan nama produk.
- `ALIAS_MATCHED`: match melalui master alias.
- `ZAHIR_ONLY`: nama Zahir belum match dan belum punya alias aktif.
- `PRODUCT_ONLY`: produk Karisma tidak ditemukan di data Zahir.

## Kontrol Risiko

- Tidak ada update otomatis saat halaman dibuka.
- Alias hanya aktif jika tercatat di master database dan `active = 1`.
- Nama barang Zahir dan nama produk Karisma tidak diubah.
- Approve tetap mengambil ulang payload dari source aktif sebelum update stok.
- Jika alias mengarah ke nama produk yang belum ada di `products`, alias tersebut tidak dipakai sebagai match dan barang tetap masuk daftar belum match.
- Mapping bonus/paket seperti `+ Kaos`, `+ Neptune`, atau label baru tidak dipotong otomatis oleh sistem; hanya mapping yang disetujui yang digunakan.

## Cara Pakai Admin

1. Pastikan migration database `20260831_zahir_stock_product_aliases.sql` sudah dijalankan.
2. Buka `admin/zahir-stock`.
3. Upload export Zahir atau gunakan API live jika source aktif.
4. Periksa tabel `Compare Match dan Approve Update Stock`.
5. Baris dengan badge `Alias` berarti nama Zahir berbeda dari produk Karisma, tetapi sudah dimapping resmi.
6. Pilih produk yang akan diupdate, lalu klik `Approve Terpilih`, atau gunakan bulk bila seluruh data match sudah tervalidasi.

## Validasi Development

- PHP lint controller: lulus.
- PHP lint model: lulus.
- PHP lint view: lulus.
- Migration lokal XAMPP berhasil dijalankan ke database `kiucoid_kiustore`.
- Seed alias lokal: 48 aktif, 48 berhasil resolve ke `product_id`, 0 unresolved.
- Browser UAT dengan login admin dan approve transaksi real tetap perlu dilakukan oleh user/admin karena proses tersebut membutuhkan session aplikasi dan data produksi/staging yang valid.
