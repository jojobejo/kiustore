# KIU Store Regression Test Report

Tanggal eksekusi: 2026-08-21 17:58:40 WIB
Environment: PHP CLI 7.3.33, source-contract mode, database tidak disentuh.

## Ringkasan Eksekutif

| Metrik | Nilai |
|---|---:|
| Total test | 13 |
| Pass | 13 |
| Fail | 0 |

## Taksonomi Data

- Verified System Facts: status test di bawah berasal dari file source aktual, route aktual, dan helper/controller/model aktual.
- Milestone Completion %: belum dihitung sebagai kesiapan modul penuh karena test database, browser, dan API HTTP live belum dijalankan.
- Business Outcome KPIs: UNVERIFIED. Tidak ada baseline data produksi berjalan pada eksekusi ini.

## Detail Test Case

### CUS-AUTH-001 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Register |
| SCENARIO | Registrasi customer berhasil |
| PRECONDITION | Email dan nomor HP belum ada. |
| TEST STEPS | Trace route `/register`, controller `Register::verify`, model register, validasi unik, insert database, redirect notifikasi. |
| EXPECTED RESULT | User role customer dan row customers dibuat, lalu redirect ke notifikasi register. |
| AUDIT TRAIL | application/config/routes.php; application/controllers/auth/Register.php; application/models/auth/Register_model.php |
| ROUTE | /register |
| CONTROLLER | application/controllers/auth/Register.php |
| MODEL | application/models/auth/Register_model.php |
| HELPER | form/session helper via autoload |
| LIBRARY | form_validation, encryption |
| DATABASE TABLE | users, customers |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Public |
| ROLE REQUIREMENT | customer setelah registrasi |
| TEST TYPE | Source Contract / Integration Candidate |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### CUS-AUTH-004 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Login |
| SCENARIO | Login customer berhasil |
| PRECONDITION | Akun customer aktif dan password valid. |
| TEST STEPS | Trace `/login`, `Login::do_login`, `Login_model`, session encrypted, redirect customer. |
| EXPECTED RESULT | Session customer dibuat dan redirect ke `/home` atau tutorial home untuk customer baru. |
| AUDIT TRAIL | application/controllers/auth/Login.php; application/models/auth/Login_model.php; application/helpers/session_helper.php |
| ROUTE | /login |
| CONTROLLER | application/controllers/auth/Login.php |
| MODEL | application/models/auth/Login_model.php |
| HELPER | application/helpers/session_helper.php |
| LIBRARY | form_validation, encryption, session |
| DATABASE TABLE | users, customers |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Public login |
| ROLE REQUIREMENT | customer |
| TEST TYPE | Source Contract / Integration Candidate |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### CUS-AUTH-005 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Login |
| SCENARIO | Password salah |
| PRECONDITION | Akun aktif, password tidak cocok. |
| TEST STEPS | Trace branch password gagal pada `Login::do_login`. |
| EXPECTED RESULT | Redirect login dengan flash `Password salah!` dan old email tersimpan. |
| AUDIT TRAIL | application/controllers/auth/Login.php |
| ROUTE | /login |
| CONTROLLER | application/controllers/auth/Login.php |
| MODEL | application/models/auth/Login_model.php |
| HELPER | session_helper |
| LIBRARY | form_validation, encryption |
| DATABASE TABLE | users |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Public login |
| ROLE REQUIREMENT | customer/admin sesuai akun |
| TEST TYPE | Source Contract |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### CUS-AUTH-006 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Login |
| SCENARIO | User tidak aktif |
| PRECONDITION | users.status bukan aktif. |
| TEST STEPS | Trace `Login_model::is_user_active` dan branch controller. |
| EXPECTED RESULT | Login ditolak dengan pesan akun tidak aktif/belum diverifikasi admin. |
| AUDIT TRAIL | application/controllers/auth/Login.php; application/models/auth/Login_model.php |
| ROUTE | /login |
| CONTROLLER | application/controllers/auth/Login.php |
| MODEL | application/models/auth/Login_model.php |
| HELPER | session_helper |
| LIBRARY | form_validation, encryption |
| DATABASE TABLE | users |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Public login |
| ROLE REQUIREMENT | customer/admin sesuai akun |
| TEST TYPE | Source Contract |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### CUS-AUTH-007 / SEC-002 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Customer Access Guard |
| SCENARIO | Akses halaman customer tanpa session |
| PRECONDITION | Belum login. |
| TEST STEPS | Trace route `/cart`, `/profile`, constructor controller, dan helper `verify_session`. |
| EXPECTED RESULT | Protected customer route redirect ke `auth/login?redir_to=...`. |
| AUDIT TRAIL | application/config/routes.php; application/modules/customer/controllers/Shop.php; application/modules/customer/controllers/Profile.php; application/helpers/session_helper.php |
| ROUTE | /cart, /profile |
| CONTROLLER | application/modules/customer/controllers/Shop.php; application/modules/customer/controllers/Profile.php |
| MODEL | application/modules/customer/models/Profile_model.php; Product_model.php |
| HELPER | application/helpers/session_helper.php |
| LIBRARY | session, encryption |
| DATABASE TABLE | users, customers |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Wajib login |
| ROLE REQUIREMENT | customer |
| TEST TYPE | Source Contract / Security Regression |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### CUS-AUTH-008 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Logout |
| SCENARIO | Logout customer |
| PRECONDITION | Customer login via cookie/session. |
| TEST STEPS | Trace `/logout` dan `Logout::index`. |
| EXPECTED RESULT | Cart dihancurkan, active session/cookie dihapus, redirect login. |
| AUDIT TRAIL | application/config/routes.php; application/controllers/auth/Logout.php |
| ROUTE | /logout |
| CONTROLLER | application/controllers/auth/Logout.php |
| MODEL | UNVERIFIED |
| HELPER | cookie, session_helper |
| LIBRARY | cart, encryption |
| DATABASE TABLE | Session store |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Session aktif direkomendasikan |
| ROLE REQUIREMENT | customer/admin |
| TEST TYPE | Source Contract |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### ADM-AUTH-001 / SEC-001 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Admin Access Guard |
| SCENARIO | Admin dashboard protected route |
| PRECONDITION | Admin belum/ sudah login sesuai skenario. |
| TEST STEPS | Trace route dashboard, constructor Dashboard, dan role helper. |
| EXPECTED RESULT | Dashboard admin dilindungi `verify_session(admin)` dan role helper mengenali semua role admin operasional. |
| AUDIT TRAIL | application/config/routes.php; application/modules/admin/controllers/Dashboard.php; application/helpers/session_helper.php |
| ROUTE | /dashboard_admin |
| CONTROLLER | application/modules/admin/controllers/Dashboard.php |
| MODEL | Admin_model, Product_model, Customer_model, Order_model, Payment_model |
| HELPER | application/helpers/session_helper.php |
| LIBRARY | session, encryption |
| DATABASE TABLE | users |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Wajib login |
| ROLE REQUIREMENT | admin/adminonline/keuangan/salesman/distribusi/kadep |
| TEST TYPE | Source Contract / Security Regression |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### ADM-AUTH-REGRESSION-001 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Login Redirect Role Guard |
| SCENARIO | User admin aktif mengakses login kembali |
| PRECONDITION | Session login aktif dengan role admin operasional. |
| TEST STEPS | Bandingkan role `is_admin()` dengan role redirect di `verify_login()`. |
| EXPECTED RESULT | Semua role admin operasional diarahkan ke dashboard_admin saat sudah login. |
| AUDIT TRAIL | application/helpers/session_helper.php |
| ROUTE | /login |
| CONTROLLER | application/controllers/auth/Login.php |
| MODEL | application/models/auth/Login_model.php |
| HELPER | application/helpers/session_helper.php |
| LIBRARY | session, encryption |
| DATABASE TABLE | users |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Session aktif |
| ROLE REQUIREMENT | admin/adminonline/keuangan/salesman/distribusi/kadep |
| TEST TYPE | Source Contract / Security Regression |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### API-ROUTES-001 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Mobile API Routing |
| SCENARIO | Endpoint API mobile v1 terdaftar |
| PRECONDITION | Route config tersedia. |
| TEST STEPS | Trace route `/api/v1/*` ke `api/mobile/*` dan method controller. |
| EXPECTED RESULT | Endpoint utama API mobile terdaftar dan method controller ada. |
| AUDIT TRAIL | application/config/routes.php; application/modules/api/controllers/Mobile.php |
| ROUTE | /api/v1/* |
| CONTROLLER | application/modules/api/controllers/Mobile.php |
| MODEL | application/modules/api/models/Mobile_api_model.php |
| HELPER | UNVERIFIED |
| LIBRARY | output, upload |
| DATABASE TABLE | mobile_api_tokens, mobile_cart_items, mobile_shipping_quotes, users, customers |
| EXTERNAL DEPENDENCY | RajaOngkir/Komerce, BRIVA untuk subset endpoint |
| AUTHENTICATION REQUIREMENT | Bearer token untuk endpoint private |
| ROLE REQUIREMENT | customer |
| TEST TYPE | Source Contract / API Regression |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### API-006 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Mobile API Account |
| SCENARIO | Account detail mobile |
| PRECONDITION | Bearer token valid. |
| TEST STEPS | Trace route `/api/v1/account` dan method `Mobile::account`. |
| EXPECTED RESULT | Sesuai dokumen use case: `GET /api/v1/account` mengembalikan detail account/customer. |
| AUDIT TRAIL | docs/USECASE_LIST_TESTING_CUSTOMER_ADMIN_20260821.md; application/modules/api/controllers/Mobile.php |
| ROUTE | GET /api/v1/account |
| CONTROLLER | application/modules/api/controllers/Mobile.php |
| MODEL | application/modules/api/models/Mobile_api_model.php |
| HELPER | UNVERIFIED |
| LIBRARY | output |
| DATABASE TABLE | users, customers, mobile_api_tokens |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Bearer token valid |
| ROLE REQUIREMENT | customer |
| TEST TYPE | Source Contract / API Regression |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### API-029 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Mobile API Authentication |
| SCENARIO | Token invalid/revoked |
| PRECONDITION | Endpoint private diakses tanpa/ dengan token invalid. |
| TEST STEPS | Trace `Mobile::authenticate`, bearer parser, dan model token. |
| EXPECTED RESULT | Response unauthorized 401 untuk token kosong atau invalid. |
| AUDIT TRAIL | application/modules/api/controllers/Mobile.php; application/modules/api/models/Mobile_api_model.php |
| ROUTE | /api/v1/orders, /api/v1/cart, private endpoints |
| CONTROLLER | application/modules/api/controllers/Mobile.php |
| MODEL | application/modules/api/models/Mobile_api_model.php |
| HELPER | UNVERIFIED |
| LIBRARY | output |
| DATABASE TABLE | mobile_api_tokens, users, customers |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Bearer token |
| ROLE REQUIREMENT | customer |
| TEST TYPE | Source Contract / API Security Regression |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### API-030 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Mobile API Method Guard |
| SCENARIO | HTTP method salah |
| PRECONDITION | Endpoint POST-only dipanggil dengan method lain. |
| TEST STEPS | Trace `require_method`, `method_not_allowed`, dan response builder. |
| EXPECTED RESULT | Response 405 dengan header Allow. |
| AUDIT TRAIL | application/modules/api/controllers/Mobile.php |
| ROUTE | /api/v1/* |
| CONTROLLER | application/modules/api/controllers/Mobile.php |
| MODEL | UNVERIFIED |
| HELPER | UNVERIFIED |
| LIBRARY | output |
| DATABASE TABLE | UNVERIFIED |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Bervariasi per endpoint |
| ROLE REQUIREMENT | customer untuk endpoint private |
| TEST TYPE | Source Contract / API Regression |
| RISK LEVEL | CRITICAL |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

### SEC-005 - PASS

| Field | Nilai |
|---|---|
| TEST MODULE | Input Sanitization |
| SCENARIO | Search dengan HTML/script |
| PRECONDITION | Query search dikirim dari URL. |
| TEST STEPS | Trace customer search dan admin product search. |
| EXPECTED RESULT | Query di-escape sebelum masuk title/parameter pencarian. |
| AUDIT TRAIL | application/modules/customer/controllers/Home.php; application/modules/admin/controllers/Products.php |
| ROUTE | /search, /admin/products/search |
| CONTROLLER | Home.php; Products.php |
| MODEL | Product_model.php |
| HELPER | global/url/form helper |
| LIBRARY | pagination |
| DATABASE TABLE | products, product_category |
| EXTERNAL DEPENDENCY | Tidak ada |
| AUTHENTICATION REQUIREMENT | Public customer search; admin search tergantung controller |
| ROLE REQUIREMENT | customer/admin sesuai modul |
| TEST TYPE | Source Contract / Security Regression |
| RISK LEVEL | HIGH |
| RESULT | PASS |
| BUG ANALYSIS | Tidak ada defect pada kontrak source yang diuji. |

## Next Steps & Risk Mitigation

| Akar Masalah | Dampak Bisnis | Strategi Mitigasi | Owner |
|---|---|---|---|
| Belum ada framework PHPUnit/Composer di repo | Test belum dapat mengeksekusi controller dengan database transaction otomatis | Tambahkan test bootstrap CodeIgniter atau PHPUnit pada environment test terisolasi | Engineering |
| Kontrak dokumen dan controller API bisa bergeser | Mobile app dapat menerima HTTP method/response yang tidak sesuai ekspektasi | Jadikan source-contract test ini bagian dari regression gate | QA + Backend |
| Database test belum dipisahkan eksplisit dari config default | Risiko test menulis ke database lokal/prod bila integration test dibuat tergesa-gesa | Tambahkan `DATABASE_TEST` dan cleanup prefix `TEST_*` sebelum integration test | DevOps + QA |

