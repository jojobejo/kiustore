# Database Hotfix: Zahir Stock Product Alias Production

Tanggal: 2026-08-31

## Root Cause

Production sempat gagal pada tahap resolve `product_id` karena query `UPDATE ... JOIN ... SET` terbaca tanpa pemisah whitespace pada sebagian token:

```text
zaJOIN ... )SET
```

Secara logika SQL, statement tersebut valid bila whitespace antar token tetap ada. Hotfix ini menulis statement resolve dalam satu baris eksplisit agar aman saat dijalankan melalui SQL runner/copy pipeline yang memadatkan newline.

Error lanjutan production:

```text
#1267 - Illegal mix of collations (utf8mb4_general_ci,IMPLICIT) and (utf8mb4_uca1400_ai_ci,IMPLICIT) for operation '='
```

Root cause lanjutan: tabel alias di production terbentuk mengikuti default collation server `utf8mb4_uca1400_ai_ci`, sementara `products.name` memakai `utf8mb4_general_ci`. Hotfix menyelaraskan collation tabel alias ke `utf8mb4_general_ci` dan query resolve memakai `COLLATE utf8mb4_general_ci` eksplisit pada kedua ekspresi pembanding.

## File Hotfix

```text
db/migrations/20260831_zahir_stock_product_aliases_production_hotfix.sql
```

## Cara Jalankan Production

Jalankan hotfix setelah tabel `zahir_stock_product_aliases` dan seed alias sudah terbentuk:

```bash
mysql -u <user> -p <database> < db/migrations/20260831_zahir_stock_product_aliases_production_hotfix.sql
```

Untuk PowerShell:

```powershell
Get-Content .\db\migrations\20260831_zahir_stock_product_aliases_production_hotfix.sql | mysql.exe -u <user> -p <database>
```

## Validasi

Hotfix akan menampilkan:

- `total_alias`
- `resolved_alias`
- `unresolved_alias`

Target validasi untuk seed awal adalah `unresolved_alias = 0`.

## Dampak Schema

Ada perubahan metadata collation pada tabel baru `zahir_stock_product_aliases`:

```sql
ALTER TABLE `zahir_stock_product_aliases` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Tidak ada tabel baru tambahan, tidak ada kolom baru tambahan, dan tidak ada perubahan pada tabel existing `products`. Setelah penyelarasan collation, hotfix mengisi ulang `zahir_stock_product_aliases.product_id` berdasarkan `zahir_stock_product_aliases.product_name` dan `products.name`.
