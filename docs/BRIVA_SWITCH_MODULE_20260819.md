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
| Setting switch | Key `briva_payment_mode` pada tabel `settings` | `application/helpers/global_helper.php` |
| Nilai valid | `production`, `local` | `briva_payment_mode()` |
| Admin route | `admin/briva-switch`, `admin/briva-switch/update` | `application/config/routes.php` |
| Admin controller | Halaman dan submit BRIVA SWITCH | `application/modules/admin/controllers/Briva_switch.php` |
| Admin view | Form radio mode production/local | `application/modules/admin/views/briva_switch/index.php` |
| Sidebar admin | Menu `BRIVA SWITCH` setelah Dashboard | `application/modules/admin/views/header.php` |
| Mobile BRIVA | Cabang lokal pada generate/status payment | `application/modules/api/controllers/Mobile.php`, `application/modules/api/models/Mobile_api_model.php` |
| Web customer BRIVA | Cabang lokal pada generate/status/cancel payment | `application/modules/customer/controllers/Orders.php` |
| Admin inquiry BRIVA | Cabang lokal pada inquiry status VA admin | `application/modules/admin/controllers/Orders.php` |
| Seed/migration | Insert default setting `production` bila belum ada | `db/migrations/20260819_briva_switch.sql` |

## 3. Cara Kerja Mode Production

Saat `briva_payment_mode = production`:

1. Endpoint mobile `POST /api/v1/orders/{id}/payments/briva` memuat library `Brivaws`.
2. Sistem menjalankan flow existing: `updateVa`, fallback `createVa` bila response BRIVA menunjukkan VA belum ada.
3. Endpoint status menjalankan `inquiryStatusVa` dan `inquiryVa`.
4. Jika BRI mengembalikan `paidStatus = Y`, sistem mengubah:
   - `briva_api.status = 2`
   - `orders.order_status = 10`
5. Jika VA expired, sistem mengubah:
   - `briva_api.status = 3`
   - `orders.order_status = 7`

## 4. Cara Kerja Mode Local Development

Saat `briva_payment_mode = local`:

1. Sistem tidak memuat atau memanggil library `Brivaws` pada flow payment lokal.
2. Generate payment membuat atau memperbarui record di tabel `briva_api`.
3. VA lokal memakai format yang sama dengan production: `91118` + 8 digit akhir nomor customer.
4. Sistem menandai simulasi pembayaran berhasil:
   - `briva_api.status = 2`
   - `orders.order_status = 10`
5. Response API menyertakan indikator `payment_mode = local` atau `paymentMode = local`.

Mode lokal tidak boleh dipakai untuk klaim settlement bank nyata. Mode ini hanya memvalidasi alur aplikasi, tampilan invoice, dan perpindahan status order pada environment development.

## 5. Matrix Risiko & Mitigasi

| Akar Masalah | Dampak Bisnis | Strategi Mitigasi | Owner |
|---|---|---|---|
| Tester lokal memakai API BRIVA production | Risiko request bank tidak sengaja dan audit transaksi tercampur | Mode `local` memutus seluruh pemanggilan `Brivaws` pada flow customer/mobile/admin inquiry | Tim IT |
| Setting switch belum ada di database | Sistem error atau mode tidak jelas | Helper default ke `production`, migrasi menambah key default | Tim IT |
| Akses switch terlalu luas | Risiko perubahan mode oleh user non-otoritatif | Controller `Briva_switch` dibatasi hanya `admin` | Administrator Sistem |
| Mode lokal aktif di environment salah | Payment dapat tersimulasi lunas tanpa settlement bank | Label halaman admin menampilkan mode aktif dan dokumentasi mewajibkan local hanya untuk development | Administrator Sistem |

## 6. Panduan Operasional

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

## 7. Batasan

- Modul ini tidak mengubah credential BRIVA.
- Modul ini tidak membuat tabel baru.
- Modul ini tidak menghapus data payment lama.
- Modul ini tidak menyatakan KPI bisnis seperti omzet, retensi, atau CSAT karena tidak ada baseline data produksi dalam perubahan kode ini.
