# Dokumentasi Database - Setting Akun Internal

Tanggal: 2026-08-05
Database lokal terverifikasi: `kiucoid_kiustore`

## Tabel Yang Berubah

Tabel: `users`

Kolom baru:

| Kolom | Tipe | Null | Default | Fungsi |
| --- | --- | --- | --- | --- |
| `is_internal` | `TINYINT(1)` | `NOT NULL` | `0` | Penanda akun internal. `1` = internal, `0` = non internal. |

Index baru:

| Index | Kolom | Fungsi |
| --- | --- | --- |
| `idx_users_is_internal_role` | `is_internal`, `role` | Mempercepat filter akun internal dan role pada modul admin. |

## Kontrak Data

- `users.is_internal = 0`: akun normal, ikut dashboard dan laporan bisnis.
- `users.is_internal = 1`: akun internal, tetap bisa login sesuai role, tetapi dikecualikan dari ringkasan dan laporan bisnis yang sudah disesuaikan.

## Dampak Relasi

Tidak ada foreign key baru.

Relasi yang digunakan untuk pengecualian data:

- `orders.user_id -> users.id`
- `customers.user_id -> users.id`
- `payments.order_id -> orders.id -> users.id`

## Catatan

Migrasi tidak mengubah data role, password, status, order, customer, payment, atau produk. Semua akun existing otomatis menjadi non internal karena default kolom baru adalah `0`.
