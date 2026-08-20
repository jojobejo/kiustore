# Dokumentasi Modul BRIVA SWITCH

Tanggal dokumen: 19 Agustus 2026

## 1. Ringkasan Eksekutif

BRIVA SWITCH adalah modul admin untuk mengubah mode pembayaran Virtual Account antara `production` dan `local`.

- `production`: menggunakan flow BRIVA existing melalui library `Brivaws` dan API BRI.
- `local`: hanya untuk development lokal; tidak melakukan request token, create VA, update VA, inquiry status, update status, atau delete VA ke API BRI production.

Default sistem adalah `production` supaya perilaku aplikasi tidak berubah bila setting belum tersedia di database.

## 2. Verified System Facts

| Area | Implementasi | Audit Trail |
|---|---|---|
| Setting switch | Key `briva_payment_mode` pada tabel `settings` | `kiustore_apps/helpers/global_helper.php` |
| Nilai valid | `production`, `local` | `briva_payment_mode()` |
| Admin route | `admin/briva-switch`, `admin/briva-switch/update` | `kiustore_apps/config/routes.php` |
| Admin controller | Halaman dan submit BRIVA SWITCH | `kiustore_apps/modules/admin/controllers/Briva_switch.php` |
| Admin view | Form radio mode production/local | `kiustore_apps/modules/admin/views/briva_switch/index.php` |
| Sidebar admin | Menu `BRIVA SWITCH` setelah Dashboard | `kiustore_apps/modules/admin/views/header.php` |
| Mobile BRIVA | Cabang lokal pada generate/status payment | `kiustore_apps/modules/api/controllers/Mobile.php`, `kiustore_apps/modules/api/models/Mobile_api_model.php` |
| Web customer BRIVA | Cabang lokal pada generate/status/cancel payment | `kiustore_apps/modules/customer/controllers/Orders.php` |
| Admin inquiry BRIVA | Cabang lokal pada inquiry status VA admin | `kiustore_apps/modules/admin/controllers/Orders.php` |
| Seed/migration | Insert default setting `production` bila belum ada | `kiustore_apps/migrasi_database/20260819_briva_switch.sql` |

## 3. Cara Kerja Mode Production

Saat `briva_payment_mode = production`, sistem memakai flow BRIVA existing melalui `Brivaws`: `updateVa`, fallback `createVa`, `inquiryStatusVa`, `inquiryVa`, dan update status berdasarkan response BRI.

## 4. Cara Kerja Mode Local Development

Saat `briva_payment_mode = local`:

1. Sistem tidak memuat atau memanggil library `Brivaws` pada flow payment lokal.
2. Generate payment membuat atau memperbarui record di tabel `briva_api`.
3. VA lokal memakai format yang sama dengan production: `91118` + 8 digit akhir nomor customer.
4. Sistem menandai simulasi pembayaran berhasil:
   - `briva_api.status = 2`
   - `orders.order_status = 10`
5. Response API menyertakan indikator `payment_mode = local` atau `paymentMode = local`.

Mode lokal hanya untuk development dan tidak boleh dianggap settlement bank nyata.

## 5. Panduan Operasional

Aktifkan local:

1. Login admin.
2. Buka `dashboard_admin`.
3. Klik menu `BRIVA SWITCH` setelah Dashboard.
4. Pilih `Local Development`.
5. Klik `Simpan Mode`.

Kembali production:

1. Buka `BRIVA SWITCH`.
2. Pilih `Production`.
3. Klik `Simpan Mode`.

SQL verifikasi:

```sql
SELECT `key`, `content`
FROM `settings`
WHERE `key` = 'briva_payment_mode';
```

SQL restore production:

```sql
UPDATE `settings`
SET `content` = 'production'
WHERE `key` = 'briva_payment_mode';
```
