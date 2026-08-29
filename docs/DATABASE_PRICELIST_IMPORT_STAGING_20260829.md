# Database Note: Staging Import Pricelist Harga

Tanggal: 2026-08-29

## Kesimpulan Skema

Ada perubahan schema database untuk module staging import pricelist.

Migration:

```text
db/migrations/20260829_pricelist_import_tracking.sql
```

Perubahan ini tidak menambah kolom pada `products`. Field `Tgl Info` dan `Keterangan Asal Info Perubahan Harga` disimpan di tabel item import agar riwayat perubahan harga tetap audit-ready tanpa mencemari master produk.

## Tabel Baru

### `pricelist_import_batches`

Menyimpan header batch import:

- nama file asli,
- nama file tersimpan,
- total raw rows,
- total data olahan,
- total match,
- total barang hanya di pricelist,
- total barang hanya di Karisma Online,
- total harga berubah,
- total invalid,
- total duplikat,
- total konflik,
- status batch,
- user import dan approve,
- waktu import dan approve.

### `pricelist_import_items`

Menyimpan detail staging dan riwayat per item:

- row Excel asal,
- kode barang,
- deskripsi raw,
- deskripsi bersih,
- supplier,
- harga baru untuk `price`, `price_2`, `price_3`,
- `tgl_info`,
- `keterangan_asal_info`,
- payload raw,
- jumlah source rows yang digroup,
- `product_id` dan `product_name` bila match,
- harga lama/current untuk `price`, `price_2`, `price_3`,
- status match,
- status perubahan,
- pesan validasi,
- status update,
- user dan waktu update.

## Proses Update Saat Approve

Approve hanya menjalankan update untuk item yang memenuhi kondisi:

- `match_status = 'MATCHED'`
- `change_status = 'PRICE_CHANGED'`
- `update_status = 'PENDING'`

SQL efektif:

```sql
UPDATE products
SET price = <new_price>,
    price_2 = <new_price_2>,
    price_3 = <new_price_3>
WHERE id = <product_id_match>;
```

Sebelum update, sistem membaca ulang harga produk terkini dan menyimpannya ke `pricelist_import_items.current_price`, `current_price_2`, dan `current_price_3`. Setelah update berhasil, item diberi `update_status = 'UPDATED'`, `updated_by`, dan `updated_at`.

## Status Match

- `MATCHED`: deskripsi bersih pricelist cocok dengan `products.name`.
- `PRICELIST_ONLY`: barang ada di pricelist tetapi belum ada di Karisma Online.
- `PRODUCT_ONLY`: barang ada di Karisma Online tetapi tidak ada di pricelist.
- `INVALID`: data tidak boleh dipakai update karena deskripsi/harga invalid atau konflik.

## Production Migration

Jalankan SQL berikut pada environment tujuan sebelum module dipakai:

```powershell
Get-Content db\migrations\20260829_pricelist_import_tracking.sql | C:\xampp\mysql\bin\mysql.exe -u <user> -p <database>
```

Untuk hosting tanpa PowerShell, jalankan isi file SQL tersebut melalui phpMyAdmin atau client MySQL production.

## Audit Trail

Riwayat harga dapat ditelusuri dari:

- file import pada `pricelist_import_batches.source_file_name`,
- file tersimpan pada `pricelist_import_batches.stored_file_name`,
- admin import pada `imported_by`,
- admin approve pada `approved_by` atau `pricelist_import_items.updated_by`,
- waktu import/approve,
- old price dan new price di `pricelist_import_items`,
- `tgl_info` dan `keterangan_asal_info` dari Keuangan.

## Export Excel Sajian Data

Export Excel pada card `Sajian Data Hasil Olah Pricelist` bersifat read-only dan tidak mengubah schema atau data.

- Export barang ada di pricelist tetapi tidak ada di Karisma Online membaca `pricelist_import_items` dengan `match_status = 'PRICELIST_ONLY'`.
- Export barang ada di Karisma Online tetapi tidak ada di pricelist membaca `pricelist_import_items` dengan `match_status = 'PRODUCT_ONLY'`.

Tidak ada perubahan tabel, kolom, index, view, trigger, atau stored procedure untuk penambahan tombol export ini.
