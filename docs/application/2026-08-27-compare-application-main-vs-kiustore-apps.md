# Compare Development: Main `application/` vs `kiustore_apps/`

Tanggal audit: 2026-08-27  
Lokasi main: `C:\xampp\htdocs\kiustore\application`  
Lokasi mirror: `C:\xampp\htdocs\kiustore\kiustore_apps`  
Status eksekusi: read-only compare, tidak ada replace/sync file aplikasi.

## Executive Summary

`kiustore_apps/` saat ini adalah mirror parsial, bukan salinan penuh dari folder main `application/`. Secara substansi, mayoritas file yang path-nya sama masih identik setelah line ending dinormalisasi, tetapi ada 21 file yang berbeda isi dan 34 file main yang tidak ada di `kiustore_apps/`.

Temuan strategis paling penting:

1. Main `application/` memiliki development terbaru yang belum masuk ke root `kiustore_apps/`, terutama:
   - Mobile onboarding endpoint: `api/v1/onboarding/complete`.
   - Mobile coupon/voucher endpoint dan logika `coupon_id`.
   - Modul admin Zahir Stock.
   - Perbaikan login customer baru untuk memulai tutorial.
2. `kiustore_apps/` memiliki beberapa artefak yang tidak ada pada path sejajar main, yaitu dokumen lama, satu migration BRIVA, dan folder duplikat `kiustore_apps/application/modules/api/...`.
3. Banyak perbedaan hash mentah bukan perbedaan bisnis/kode, melainkan line ending CRLF/LF. Setelah normalisasi, 209 file terbukti hanya berbeda line ending.
4. Rekomendasi: jangan replace folder secara bulk. Lakukan selective sync dari main ke `kiustore_apps` untuk 21 file substantif dan file main-only yang memang disetujui untuk deployment, terutama modul Zahir Stock dan migration terkait onboarding.

## Metodologi Audit

Audit dilakukan dengan:

- Inventaris file rekursif pada `application/` dan `kiustore_apps/`.
- SHA-256 raw hash per file.
- SHA-256 hash setelah normalisasi CRLF/LF untuk file teks.
- Exclusion operasional: `.DS_Store` dan folder `logs`.
- Diff substansi memakai pembanding line ending aware.
- Database dicek terpisah pada dokumen database agar tidak mencampur fakta schema dengan fakta source code.

## Ringkasan Angka Compare

| Metrik | Nilai |
|---|---:|
| File main `application/` yang diaudit | 300 |
| File `kiustore_apps/` yang diaudit | 278 |
| Path yang sama dan dibandingkan | 266 |
| Sama setelah normalisasi line ending | 245 |
| Berbeda hanya karena line ending | 209 |
| Berbeda secara substansi | 21 |
| Hanya ada di main `application/` | 34 |
| Hanya ada di `kiustore_apps/` | 12 |

## Struktur Folder

| Area | Main `application/` | `kiustore_apps/` | Catatan |
|---|---:|---:|---|
| Root file | 4 | 0 | Main punya `.htaccess`, `brivacoba.php`, `build_helper.php`, `WORK-TO-DO`. |
| `config` | 17 | 2 | Mirror hanya membawa sebagian config. |
| `controllers` | 8 | 8 | Ada file sejajar, sebagian beda substansi/line ending. |
| `core` | 3 | 0 | Tidak ada di mirror. |
| `docs` | 10 | 19 | Mirror membawa dokumen operasional tambahan. |
| `helpers` | 7 | 7 | Sebagian besar sama, `session_helper.php` beda substansi kecil. |
| `libraries` | 2 | 2 | Ada di dua sisi. |
| `migrasi_database` | 0 | 1 | Mirror punya `20260819_briva_switch.sql`. |
| `models` | 4 | 4 | Ada di dua sisi. |
| `modules` | 190 | 186 | Main punya modul tambahan terbaru. |
| `third_party` | 8 | 0 | Tidak ada di mirror. |
| `views` | 47 | 47 | Ada di dua sisi, satu file beda substansi. |

## File Yang Hanya Ada Di Main `application/`

File berikut ada di main tetapi tidak ada pada path sejajar `kiustore_apps/`:

| File | Status/Dampak |
|---|---|
| `.htaccess` | Runtime routing CodeIgniter, tidak ada di mirror. |
| `brivacoba.php` | File coba/diagnostic BRIVA, tidak ada di mirror. |
| `build_helper.php` | Helper build, tidak ada di mirror. |
| `WORK-TO-DO` | Catatan kerja, tidak ada di mirror. |
| `config/autoload.php` | Config core CI, tidak ada di mirror. |
| `config/briva.php` | Config BRIVA lama/pendukung, tidak ada di mirror. |
| `config/constants.php` | Config core CI, tidak ada di mirror. |
| `config/database.php` | Koneksi database main, tidak ada di mirror. |
| `config/doctypes.php` | Config core CI, tidak ada di mirror. |
| `config/foreign_chars.php` | Config core CI, tidak ada di mirror. |
| `config/hooks.php` | Config core CI, tidak ada di mirror. |
| `config/memcached.php` | Config core CI, tidak ada di mirror. |
| `config/migration.php` | Config migration CI, tidak ada di mirror. |
| `config/mimes.php` | Config core CI, tidak ada di mirror. |
| `config/profiler.php` | Config profiler CI, tidak ada di mirror. |
| `config/rajaongkir.php` | Config RajaOngkir, tidak ada di mirror. |
| `config/smileys.php` | Config core CI, tidak ada di mirror. |
| `config/user_agents.php` | Config core CI, tidak ada di mirror. |
| `config/zahirdigital.php` | Config integrasi Zahir Digital, wajib bila modul Zahir Stock disync. |
| `core/MY_Loader.php` | Override CI core, tidak ada di mirror. |
| `core/MY_Router.php` | Override CI core, tidak ada di mirror. |
| `core/MY_URI.php` | Override CI core, tidak ada di mirror. |
| `modules/admin/controllers/Zahir_stock.php` | Modul baru integrasi stock Zahir Digital, belum ada di mirror. |
| `modules/admin/models/Zahir_stock_model.php` | Model modul Zahir Stock, belum ada di mirror. |
| `modules/admin/views/zahir_stock/index.php` | View modul Zahir Stock, belum ada di mirror. |
| `modules/api/api.zip` | Archive/snapshot, tidak direkomendasikan ikut sync otomatis. |
| `third_party/MX/Base.php` | HMVC runtime, tidak ada di mirror. |
| `third_party/MX/Ci.php` | HMVC runtime, tidak ada di mirror. |
| `third_party/MX/Config.php` | HMVC runtime, tidak ada di mirror. |
| `third_party/MX/Controller.php` | HMVC runtime, tidak ada di mirror. |
| `third_party/MX/Lang.php` | HMVC runtime, tidak ada di mirror. |
| `third_party/MX/Loader.php` | HMVC runtime, tidak ada di mirror. |
| `third_party/MX/Modules.php` | HMVC runtime, tidak ada di mirror. |
| `third_party/MX/Router.php` | HMVC runtime, tidak ada di mirror. |

## File Yang Hanya Ada Di `kiustore_apps/`

| File | Status/Dampak |
|---|---|
| `application/modules/api/controllers/Mobile.php` | Duplikasi nested API, tidak sejajar dengan main. Perlu keputusan: hapus/abaikan/sync setelah validasi. |
| `application/modules/api/models/Mobile_api_model.php` | Duplikasi nested API, ukurannya berbeda dari root API mirror dan main. Berisiko membingungkan deployment. |
| `docs/BRIVA_SWITCH_MODULE_20260819.md` | Dokumentasi mirror lama. Main memiliki dokumen BRIVA di `docs/` root project, bukan di `application/docs`. |
| `docs/DATABASE_PRODUCT_DETAIL_ACTION_BUTTONS_20260820.md` | Dokumentasi mirror lama. |
| `docs/DATABASE_PRODUCT_STOCK_CHAT_PREFILL_20260820.md` | Dokumentasi mirror lama. |
| `docs/DATABASE_PROFILE_MOBILE_BUTTON_LAYOUT_20260820.md` | Dokumentasi mirror lama. |
| `docs/DATABASE_WEBVIEW_LOCAL_XAMPP_20260820.md` | Dokumentasi mirror lama. |
| `docs/DEVELOPMENT_PRODUCT_DETAIL_ACTION_BUTTONS_20260820.md` | Dokumentasi mirror lama. |
| `docs/DEVELOPMENT_PRODUCT_STOCK_CHAT_PREFILL_20260820.md` | Dokumentasi mirror lama. |
| `docs/DEVELOPMENT_PROFILE_MOBILE_BUTTON_LAYOUT_20260820.md` | Dokumentasi mirror lama. |
| `docs/DEVELOPMENT_WEBVIEW_LOCAL_XAMPP_20260820.md` | Dokumentasi mirror lama. |
| `migrasi_database/20260819_briva_switch.sql` | Migration BRIVA ada di mirror, tetapi main juga punya versi canonical di `db/migrations/20260819_briva_switch.sql`. |

## File Beda Substansi Pada Path Yang Sama

| File | Isi beda utama | Dampak |
|---|---|---|
| `config/routes.php` | Main punya route `api/v1/onboarding/complete` dan route admin `zahir-stock`; mirror belum punya. | Mobile onboarding dan Zahir Stock tidak aktif bila mirror dipakai sebagai source deploy. |
| `controllers/auth/Login.php` | Main punya flow customer baru: set session `kiu_start_customer_tutorial`, redirect `home?start_tutorial=1`, dan memakai `$this->session->set_userdata`. Mirror masih memakai `$this->input->set_userdata` pada bagian session aktif. | Main lebih aman untuk flow login/tutorial; mirror berisiko bug session. |
| `controllers/auth/Register.php` | Ada perbedaan logic registrasi customer baru. | Perlu sync bila flow onboarding customer baru harus konsisten. |
| `helpers/session_helper.php` | Beda satu baris substansi. | Perlu review kecil sebelum sync karena menyangkut auth guard. |
| `modules/admin/controllers/Products.php` | Main punya perubahan produk terbaru. | Jangan overwrite tanpa memastikan kebutuhan promo/coupon/product terbaru. |
| `modules/admin/models/Order_model.php` | Beda kecil. | Terkait order/payment; perlu selective diff sebelum deploy. |
| `modules/admin/models/Product_model.php` | Beda 14 baris substansi. | Terkait produk/promo/coupon. |
| `modules/admin/views/header.php` | Main menambahkan menu `admin/zahir-stock`. | Wajib bila modul Zahir Stock diaktifkan. |
| `modules/admin/views/orders/view.php` | Beda area inquiry/status VA. | Terkait BRIVA/status order; perlu UAT admin. |
| `modules/admin/views/products/coupons.php` | Main lebih baru untuk coupon admin. | Terkait voucher/coupon mobile. |
| `modules/admin/views/products/promo.php` | Main lebih baru untuk promo admin. | Terkait diskon/promo produk. |
| `modules/api/controllers/Mobile.php` | Main punya endpoint `coupon_check`, `apply_order_coupon`, dan payload checkout `voucher_code`. | Mobile coupon tidak tersedia bila mirror dipakai apa adanya. |
| `modules/api/models/Mobile_api_model.php` | Main punya `validate_coupon`, penghitungan discount, `coupon_id`, `apply_order_coupon`, dan helper order coupon. | Mobile API di mirror tertinggal untuk coupon/voucher. |
| `modules/customer/controllers/Home.php` | Main membaca session `kiu_start_customer_tutorial`, bukan hanya query string. | Flow tutorial setelah registrasi/login hanya lengkap di main. |
| `modules/customer/controllers/Shop.php` | Beda substansi pada proses shopping/cart/checkout. | Perlu UAT customer checkout sebelum sync. |
| `modules/customer/views/footer.php` | Beda kecil. | Dampak UI kecil. |
| `modules/customer/views/header.php` | Beda UI/navigation customer. | Terkait entry tutorial/guide/customer nav. |
| `modules/customer/views/home.php` | Main punya onboarding splash/tutorial customer. | UI onboarding tidak sama di mirror. |
| `modules/customer/views/shop/cart.php` | Beda proses tampilan cart. | Perlu UAT cart. |
| `modules/customer/views/shop/checkout.php` | Beda proses tampilan checkout. | Perlu UAT checkout. |
| `views/auth/notif_register.php` | Beda kecil pada notifikasi register. | Terkait UX registrasi. |

## Accomplishment

- Compare berbasis hash dan normalisasi line ending selesai.
- Daftar file yang ada/tidak ada sudah dipisahkan.
- Perbedaan substansi sudah dipisahkan dari noise CRLF/LF.
- Tidak ada perubahan source code atau database selama audit.

## Issues & Root Cause

| Issue | Root Cause | Dampak Bisnis |
|---|---|---|
| `kiustore_apps/` tertinggal dari main untuk fitur mobile coupon/onboarding. | Sync parsial terakhir belum membawa seluruh perubahan main. | Mobile app atau deployment berbasis mirror dapat kehilangan fitur terbaru. |
| Modul Zahir Stock hanya ada di main. | Development terbaru belum disalin ke mirror. | Admin tidak dapat memakai integrasi stock Zahir Digital dari mirror. |
| Folder nested `kiustore_apps/application/modules/api/...` tidak sejajar. | Ada salinan API yang tersimpan di dalam mirror dengan struktur berbeda. | Risiko salah pilih file saat deploy/manual copy. |
| Banyak file beda hash karena line ending. | CRLF/LF bercampur. | Audit manual mudah salah menyimpulkan banyak file berbeda padahal sama secara substansi. |
| Mirror tidak memiliki runtime lengkap (`core`, `third_party`, config lengkap). | Mirror memang parsial. | Tidak layak dianggap web root penuh tanpa folder root project lain. |

## Next Steps & Risk Mitigation

| Prioritas | Rekomendasi | Owner Teknis | Validasi |
|---|---|---|---|
| P1 | Jangan bulk replace `application/` dari/ke `kiustore_apps/`; lakukan selective sync. | Developer | Hash ulang setelah sync. |
| P1 | Jika mirror akan jadi target deploy, sync 21 file substantif dari main setelah approval. | Developer | `C:\xampp\php\php.exe -l` untuk file PHP terdampak. |
| P1 | Untuk modul Zahir Stock, sync satu paket: route, config `zahirdigital.php`, controller, model, view, dan menu header. | Developer/Admin | UAT admin `admin/zahir-stock`, data, approve, insert, export. |
| P1 | Jalankan/siapkan migration database yang belum ada di mirror: `20260820_mobile_onboarding_flags.sql`, dan pertimbangkan `mobile_account_deletions` jika fitur delete account audit diwajibkan. | DBA/Developer | Cek `information_schema.TABLES` setelah migration. |
| P2 | Rapikan duplikasi `kiustore_apps/application/modules/api/...` agar tidak ada dua source API dalam mirror. | Developer | Satu source API canonical per environment. |
| P2 | Normalisasi line ending atau gunakan compare `--ignore-cr-at-eol` dalam audit berikutnya. | Developer | Diff audit tidak penuh noise. |

## Status Akhir

Audit ini dokumentasi saja. Tidak ada schema change, tidak ada data mutation, dan tidak ada file aplikasi yang direplace ke `kiustore_apps`.
