# Development Note: Zahir Stock Product Alias Production Hotfix

Tanggal: 2026-08-31

## Scope

Perbaikan difokuskan pada error migrasi production untuk resolve `product_id` pada tabel `zahir_stock_product_aliases`.

## Root Cause Teknis

Statement SQL final pada migration memakai format multi-line:

```sql
UPDATE ... za
JOIN ...
SET ...
```

Log production menunjukkan token SQL menempel sebagai `zaJOIN` dan `)SET`, sehingga query tidak lagi valid saat dieksekusi. Perbaikan dilakukan dengan menulis ulang statement final dalam satu baris eksplisit.

Log production lanjutan menunjukkan error collation:

```text
#1267 - Illegal mix of collations (utf8mb4_general_ci,IMPLICIT) and (utf8mb4_uca1400_ai_ci,IMPLICIT) for operation '='
```

Production membentuk tabel alias dengan default collation server yang berbeda dari `products.name`. Perbaikan lanjutan mengunci DDL tabel alias ke `utf8mb4_general_ci`, menambahkan `ALTER TABLE ... CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci`, dan memakai `COLLATE utf8mb4_general_ci` eksplisit pada query resolve.

## Perubahan Aplikasi

Tidak ada perubahan kode aplikasi, controller, model, view, route, atau asset.

## Perubahan File

- `db/migrations/20260831_zahir_stock_product_aliases.sql`
- `db/migrations/20260831_zahir_stock_product_aliases_production_hotfix.sql`
- `docs/DATABASE_ZAHIR_STOCK_PRODUCT_ALIAS_PRODUCTION_HOTFIX_20260831.md`

## Validasi Lokal

Validasi dilakukan pada database lokal `kiucoid_kiustore` menggunakan transaksi dan `ROLLBACK`.

Hasil:

- `total_alias`: 48
- `resolved_alias`: 48
- `unresolved_alias`: 0
- `zahir_stock_product_aliases.product_name`: `utf8mb4_general_ci`

## Sync Mirror

Tidak diperlukan sync `application/` ke `kiustore_apps/` karena tidak ada perubahan pada folder `application/`.
