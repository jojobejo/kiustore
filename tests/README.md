# KIU Store Automated Regression Tests

Suite ini mengubah use case prioritas kritikal menjadi automated source-contract regression test yang bisa dijalankan tanpa dependency eksternal.

## Cara Menjalankan

```bash
php tests/run_regression.php
```

Runner akan:

- membaca route aktual dari `application/config/routes.php`;
- memverifikasi controller, method, model/helper, dan branch business logic yang relevan;
- menghasilkan report audit di `docs/test-reports/latest-regression-report.md`;
- keluar dengan exit code `0` jika semua test pass, dan `1` jika ada defect.

## Scope Saat Ini

Coverage awal berfokus pada Priority 1 dan API regression guard:

- `CUS-AUTH-*`: register, login, invalid password, inactive user, protected customer route, logout;
- `ADM-AUTH-*` dan `SEC-001`: admin route guard dan role redirect consistency;
- `API-006`, `API-029`, `API-030`: account endpoint, bearer token guard, method guard;
- `SEC-005`: sanitasi input search customer/admin.

## Batasan Audit

Mode ini tidak menulis ke database dan tidak memanggil service eksternal. Integration, API HTTP live, dan browser E2E harus ditambahkan setelah tersedia `DATABASE_TEST` yang terisolasi dan data prefix `TEST_*`.
