# Scanning Aplikasi dan API Mobile Android KIU Store

Tanggal audit: 2026-08-18
Project: `C:\xampp\htdocs\kiustore`
Fokus: alur bisnis customer mobile, kontrak REST API, dan kesiapan sebagai backend Android native Kotlin.

## Executive Summary

KIU Store sudah memiliki backend REST API mobile v1 yang terpisah dari flow session web CodeIgniter. Modul API berada pada:

- `application/modules/api/controllers/Mobile.php`
- `application/modules/api/models/Mobile_api_model.php`
- Route prefix: `/api/v1`
- Dokumentasi lama referensi: `docs/MOBILE_API.md`

Hasil validasi lokal:

- `https://localhost/kiustore/api/v1` mengembalikan HTTP 200.
- Response health: `success=true`, `name=KIU Store Mobile API`, `version=v1`, `database_ready=true`.
- `http://localhost/kiustore/api/v1` gagal pada sisi SSL connection, sehingga Android debug harus diarahkan ke HTTPS local/staging atau dikonfigurasi dengan network security untuk certificate debug.
- PHP lint dengan `C:\xampp\php\php.exe -l` lulus untuk `Mobile.php` dan `Mobile_api_model.php`.

## Verified System Facts

### 1. Struktur Modul

API mobile tidak memakai controller customer web biasa. Route mobile eksplisit didefinisikan di `application/config/routes.php` untuk:

- Auth: register, login, logout, delete account.
- Profil customer.
- Banner, kategori, produk.
- Cart database.
- Shipping RajaOngkir/Komerce.
- Payment method, bank transfer, BRIVA.
- Order list, detail, checkout, cancel, complete.
- Messages/live chat.

### 2. Autentikasi

API memakai Bearer token custom berbasis tabel `mobile_api_tokens`.

Flow:

1. `POST /api/v1/auth/register`
2. `POST /api/v1/auth/login`
3. Server menerbitkan token plain sekali ke client.
4. Server menyimpan `sha256(token)` di database.
5. Endpoint privat memakai header `Authorization: Bearer TOKEN`.
6. Token expired 30 hari dan dapat dicabut saat logout.

Implikasi Android:

- Simpan token di EncryptedSharedPreferences atau DataStore yang dienkripsi.
- Jangan simpan password setelah login.
- Tambahkan interceptor OkHttp untuk menyisipkan Bearer token.
- Jika HTTP 401, arahkan user ke login dan bersihkan token lokal.

### 3. Guest Browsing

Produk dan kategori dapat dibaca tanpa token:

- `GET /api/v1/categories`
- `GET /api/v1/banners`
- `GET /api/v1/products`
- `GET /api/v1/products/{id}`

Harga produk:

- Tanpa token: level customer default `1`.
- Dengan token: harga mengikuti `customers.level` user login.

Implikasi Android:

- Home, kategori, promo, search, dan detail produk boleh dibuka sebagai guest.
- Aksi cart, checkout, order, payment, chat, profile wajib login.

### 4. Flow Bisnis Customer Mobile

Alur bisnis yang harus direplikasi di Android:

1. Customer melihat katalog sebagai guest atau login.
2. Customer login/register.
3. Customer memilih produk, satuan, dan quantity.
4. `POST /api/v1/cart` menolak penambahan jika customer masih memiliki transaksi berjalan.
5. Customer membuat quote ongkir dari cart aktif.
6. Customer checkout memakai quote ongkir yang belum expired.
7. Backend membuat order mobile dengan `order_status = 1` dan `payment_method = null`.
8. Admin web mengonfirmasi order sampai status menjadi `2`.
9. Aplikasi menampilkan invoice dan membuka pilihan metode pembayaran.
10. Customer memilih `payment_method = 2` untuk BRIVA atau `3` untuk transfer bank.
11. Untuk BRIVA, aplikasi generate VA dan polling status VA.
12. Untuk transfer bank, aplikasi upload bukti transfer dan order masuk status `8`.
13. Admin memverifikasi pembayaran/pengiriman dari web admin.
14. Jika order status `4`, customer dapat menyelesaikan order dengan rating.

### 5. Status Order Yang Dipakai Mobile

Label dari `Mobile_api_model::format_order()`:

| Status | Label Mobile | Makna Operasional |
| --- | --- | --- |
| 1 | Menunggu diproses | Order baru dari mobile, menunggu admin |
| 2 | Menunggu pembayaran | Admin sudah konfirmasi, user bisa pilih metode bayar |
| 3 | Dikemas | Order sedang diproses gudang/admin |
| 4 | Dikirim | Order dikirim, user bisa complete dengan rating |
| 5 | Selesai | Selesai normal |
| 6 | Selesai | Status selesai historis |
| 7 | Dibatalkan | Order batal |
| 8 | Sedang ditinjau oleh admin | Bukti transfer menunggu verifikasi |
| 9 | Menunggu persetujuan | Status approval historis |
| 10 | Payment Verify | BRIVA terbayar/terverifikasi |
| 11 | Tentukan metode pengiriman | Status pengiriman historis |

Aturan transaksi aktif: selama status bukan `5`, `6`, atau `7`, user tidak dapat menambah cart baru.

### 6. Checkout dan Ongkir

Endpoint shipping:

- `GET /api/v1/shipping/provinces`
- `GET /api/v1/shipping/cities?province_id=...`
- `GET /api/v1/shipping/districts?city_id=...`
- `POST /api/v1/shipping/quotes`

Fakta teknis:

- Provider memakai `application/config/rajaongkir.php`.
- Origin mobile memakai `mobile_shipping_origin_id`.
- Quote berlaku 30 menit.
- Checkout menolak quote jika berat cart berubah.
- Checkout menyimpan `orders.shipping_cost`, `jenis_pengiriman`, `estimasi_kirim`, `delivery_data`, dan item ke `order_items`.

Implikasi Android:

- Setelah cart berubah, invalidasi quote lama.
- UI checkout wajib memilih layanan ongkir dari response quote, bukan mengetik manual.
- Tampilkan countdown quote 30 menit.

### 7. Payment

Metode payment yang dibuka mobile:

- `2`: Virtual Account Karisma/BRIVA.
- `3`: Transfer Bank.

Metode kredit `1` tidak didukung di mobile saat ini karena masih bergantung approval/limit kredit web.

BRIVA:

- `POST /api/v1/orders/{id}/payments/briva`
- `GET /api/v1/orders/{id}/payments/briva/status`
- Hanya valid jika order milik user, `order_status = 2`, dan `payment_method = 2`.
- VA memakai prefix `91118` + 8 digit terakhir nomor telepon customer.
- Expired VA 15 menit dari generate.

Transfer bank:

- `GET /api/v1/payments/banks`
- `POST /api/v1/orders/{id}/payments/bank-transfer`
- Bukti transfer dapat dikirim sebagai `picture_base64` atau multipart field `picture`.
- Setelah submit, order berubah ke status `8`.

Implikasi Android:

- Buat dua flow payment berbeda.
- BRIVA perlu layar VA code, expiry countdown, copy VA, dan tombol cek status.
- Transfer perlu form sumber bank, nomor rekening, nama rekening, nominal, bank tujuan, dan upload/compress foto.

### 8. Chat / Message

Endpoint:

- `GET /api/v1/messages?last_id=...`
- `POST /api/v1/messages`

Model bisnis:

- Pesan user disimpan ke tabel `message` dengan `chat_from = 2`.
- Pesan admin/sales dibaca dari tabel sama.
- Customer terkait ke `salesman_id` dari profil customer.

Implikasi Android:

- Buat polling sederhana atau refresh manual terlebih dahulu.
- `last_id` dipakai untuk incremental fetch.

### 9. Delete Account

Endpoint:

- `DELETE /api/v1/account`

Flow kode:

- Revoke semua token user.
- Hapus cart dan shipping quote mobile.
- Anonimkan profil customer.
- Lepas histori order/review dari `user_id`.
- Anonimkan akun `users`.
- Jika tabel `mobile_account_deletions` tersedia, simpan audit hash email.

Catatan audit:

- Schema aktif lokal belum memiliki `mobile_account_deletions`.
- File migrasi tersedia dan perlu dijalankan untuk audit compliance delete-account.

## API Contract Ringkas Untuk Android

Base URL debug yang tervalidasi:

```text
https://localhost/kiustore/api/v1
```

Catatan Android:

- Emulator Android tidak bisa memakai `localhost` untuk host PC. Gunakan `https://10.0.2.2/kiustore/api/v1` jika Apache bind ke localhost, atau `https://IP-LAN-PC/kiustore/api/v1` untuk device fisik.
- Sertifikat lokal XAMPP kemungkinan tidak dipercaya Android. Gunakan network security config debug khusus, atau pakai staging HTTPS dengan sertifikat valid.

Header umum:

```http
Accept: application/json
Content-Type: application/json
Authorization: Bearer <token>  # hanya endpoint privat
```

Response standard:

```json
{
  "success": true,
  "message": "OK",
  "data": {},
  "meta": {}
}
```

Error standard:

```json
{
  "success": false,
  "message": "Pesan error",
  "data": null,
  "errors": {}
}
```

## Risiko dan Rekomendasi

### Accomplishment

- REST API mobile sudah tersedia dan health endpoint aktif.
- Auth token mobile sudah tidak bergantung session web.
- Cart mobile disimpan di database.
- Checkout memakai transaksi database dan validasi ulang harga/stok.
- Payment BRIVA dan transfer bank sudah mempunyai endpoint mobile.
- Delete-account tersedia di API.

### Issues & Root Cause

| Isu | Root Cause | Dampak |
| --- | --- | --- |
| `mobile_account_deletions` belum ada pada schema aktif lokal | Migrasi compliance belum terlihat di database aktif | Delete account tetap berjalan, tetapi audit hash email tidak tercatat |
| HTTP local gagal dan HTTPS berhasil | Konfigurasi local server/SSL | Android debug perlu base URL dan trust config yang benar |
| BRIVA bergantung kredensial dan IP whitelist BRI | Integrasi eksternal SNAP/BRI | Build Android harus memperlakukan BRIVA sebagai backend-only, bukan menyimpan credential di app |
| Order mobile tetap butuh konfirmasi admin web | Proses bisnis existing | UI Android harus menjelaskan status "Menunggu diproses" sebelum payment dibuka |
| Kredit belum didukung mobile | Approval/limit kredit masih web-driven | Jangan tampilkan payment method kredit di Android versi pertama |

### Next Steps

1. Jalankan migrasi `mobile_account_deletions` pada database aktif sebelum rilis compliance.
2. Gunakan endpoint HTTPS staging dengan sertifikat valid untuk QA Android.
3. Bangun Android native Kotlin dengan MVVM, repository API, secure token storage, dan offline cache ringan.
4. UAT minimal: guest catalog, register/login, cart, quote ongkir, checkout, admin confirm, pilih payment, BRIVA/transfer, order status, complete rating, chat, delete account.

