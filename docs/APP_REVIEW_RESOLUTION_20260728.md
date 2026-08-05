# App Review Resolution 2026-07-28

Dokumen ini mencatat development API `kiustore` untuk menyelesaikan issue Apple Review pada app iOS Karisma Online.

## Issue Apple

- App mendukung register akun tetapi belum menyediakan penghapusan akun.
- User perlu dapat browsing produk tanpa login.
- Screenshot App Store harus menampilkan UI app versi terbaru.
- Flow iPad harus memiliki tombol kembali/tutup yang jelas.

## Development API

Path project API:

```text
/Applications/XAMPP/xamppfiles/htdocs/kiustore
```

File yang diubah:

- `application/config/routes.php`
- `application/modules/api/controllers/Mobile.php`
- `application/modules/api/models/Mobile_api_model.php`
- `db/migrations/20260629_mobile_api.sql`
- `docs/MOBILE_API.md`

File baru:

- `db/migrations/20260728_mobile_account_deletion.sql`
- `docs/APP_REVIEW_RESOLUTION_20260728.md`

Endpoint baru:

```http
DELETE /api/v1/account
Authorization: Bearer {token}
Accept: application/json
```

Route:

```php
$route['api/v1/account'] = 'api/mobile/account';
```

## Database

Migration tambahan:

```text
db/migrations/20260728_mobile_account_deletion.sql
```

Tabel baru:

```sql
mobile_account_deletions
```

Kolom:

- `id`: primary key.
- `user_id`: id user yang menghapus akun.
- `email_hash`: SHA-256 dari email lama, bukan email asli.
- `deleted_at`: waktu penghapusan.
- `created_at`: waktu audit row dibuat.

Strategi data:

- `mobile_api_tokens`: token user di-revoke.
- `mobile_cart_items`: cart user dihapus.
- `mobile_shipping_quotes`: quote ongkir sementara dihapus.
- `customers`: data personal dianonimkan dan `user_id` dibuat `NULL`.
- `orders`: `user_id` dibuat `NULL`.
- `reviews`: `user_id` dibuat `NULL`.
- `message`: `customer_id` dibuat `0`.
- `users`: email/password/nama/profile dianonimkan, status dinonaktifkan.

Order tidak dihapus karena transaksi e-commerce dapat dibutuhkan untuk pembukuan, audit operasional, invoice, dan rekonsiliasi pembayaran. Relasi PII dilepas agar akun customer tidak lagi aktif.

## Alur Bisnis

### Guest Browsing

1. User membuka app iOS.
2. User memilih `Lihat Produk Tanpa Login`.
3. User dapat membuka home, katalog, kategori, search, promo, dan detail produk.
4. User baru diminta login saat memakai fitur berbasis akun: cart, checkout, order history, chat, profil, dan hapus akun.

### Account Deletion

1. User login.
2. User membuka `Profil`.
3. User membuka `Pengaturan`.
4. User memilih `Hapus Akun`.
5. iOS menampilkan confirmation dialog.
6. iOS memanggil `DELETE /api/v1/account`.
7. API membersihkan/anonymize data user.
8. iOS membersihkan token lokal dan kembali ke layar auth.

## Alur Penggunaan Untuk Apple Review

Rekam dari physical device:

1. Buka app.
2. Tap `Lihat Produk Tanpa Login`.
3. Tunjukkan produk/kategori/detail bisa dibuka.
4. Login atau buat akun demo.
5. Buka `Profil > Pengaturan > Hapus Akun`.
6. Konfirmasi hapus akun.
7. Tunjukkan app kembali ke layar login/auth.

Catatan App Review:

```text
Users can browse products without login by tapping "Lihat Produk Tanpa Login".
Login is only required for account-based features: cart, checkout, order history, chat, profile, and account deletion.
The account deletion flow is available at Profil > Pengaturan > Hapus Akun.
```

## Module Development Baru

Nama module:

```text
Mobile Account Deletion
```

Scope:

- Endpoint `DELETE /api/v1/account`.
- Database audit non-PII `mobile_account_deletions`.
- Anonymize akun customer.
- Revoke token dan hapus temporary mobile data.
- Dokumentasi App Review dan API.

Checklist deployment:

- Jalankan `db/migrations/20260728_mobile_account_deletion.sql` jika database sudah pernah menjalankan migration mobile awal.
- Deploy perubahan route/controller/model.
- Test `DELETE /api/v1/account` dengan Bearer token valid.
- Build dan upload iOS baru.
- Update screenshot 6.5-inch di App Store Connect.
