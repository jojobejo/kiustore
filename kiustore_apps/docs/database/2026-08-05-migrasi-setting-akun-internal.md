# Migrasi Database - Setting Akun Internal

Tanggal: 2026-08-05
File migrasi: `db/migrations/20260805_add_users_internal_flag.sql`

## Cara Menjalankan Di Local XAMPP

```powershell
& 'C:\xampp\mysql\bin\mysql.exe' -uroot kiucoid_kiustore < db\migrations\20260805_add_users_internal_flag.sql
```

## Cara Menjalankan Di Hosting/Production

1. Backup database production.
2. Buka phpMyAdmin atau client MySQL production.
3. Jalankan isi file `db/migrations/20260805_add_users_internal_flag.sql`.
4. Pastikan query selesai tanpa error.

## Validasi Setelah Migrasi

```sql
SHOW COLUMNS FROM users LIKE 'is_internal';
SHOW INDEX FROM users WHERE Key_name = 'idx_users_is_internal_role';
SELECT is_internal, role, COUNT(*) AS total
FROM users
GROUP BY is_internal, role
ORDER BY is_internal, role;
```

## Rollback Manual

Gunakan hanya jika fitur dibatalkan dan kode aplikasi sudah dikembalikan.

```sql
ALTER TABLE users DROP INDEX idx_users_is_internal_role;
ALTER TABLE users DROP COLUMN is_internal;
```

## Catatan Migrasi

Script migrasi dibuat rerunnable. Jika kolom atau index sudah ada, script hanya menampilkan pesan dan tidak menjalankan `ALTER TABLE` ulang.
