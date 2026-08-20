# Audit Perbandingan Application Lokal vs Production - 2026-08-20

## Scope

Sumber audit:
- `compare/application_lokal`
- `compare/application_production`

Audit bersifat read-only terhadap folder pembanding. Tidak ada file aplikasi yang disinkronkan atau diubah.

## Ringkasan Eksekutif

Lokal bukan salinan identik production. Dari sisi isi file:

- Lokal: 300 file.
- Production: 293 file.
- File sama persis: 235.
- File beda hash: 56.
- Setelah normalisasi CRLF/LF: 23 file beda isi secara bermakna, 31 file hanya beda line ending/encoding, 2 file metadata biner `.DS_Store`.
- File hanya ada di lokal: 9.
- File hanya ada di production: 2.

Kesimpulan bisnis: lokal membawa beberapa fitur baru yang belum masuk production, tetapi juga membawa risiko regresi PHP 8.x pada HMVC karena beberapa file production lebih siap PHP modern daripada lokal. Deploy tidak boleh dilakukan dengan overwrite folder penuh.

## File Hanya Ada Di Lokal

- `core/MY_URI.php`
- `modules/admin/controllers/Briva_switch.php`
- `modules/admin/views/briva_switch/index.php`
- `modules/api/.DS_Store`
- `modules/api/api.zip`
- `modules/api/controllers.4612/Mobile.php`
- `modules/api/models.5789/Mobile_api_model.php`
- `modules/customer/views/profile_guide_book.php`
- `modules/customer/views/profile_tutorial.php`

Makna:
- `Briva_switch.php` dan `briva_switch/index.php` adalah fitur lokal untuk memilih mode BRIVA `local` atau `production`.
- `profile_guide_book.php` dan `profile_tutorial.php` mendukung panduan customer.
- `controllers.4612`, `models.5789`, `.DS_Store`, dan zip adalah artefak/snapshot, bukan source utama yang ideal untuk deployment production.

## File Hanya Ada Di Production

- `apps.zip`
- `controllers/.DS_Store`

Makna:
- `apps.zip` perlu dipastikan apakah arsip deployment/manual backup. Jangan hapus dari production sebelum asal-usulnya jelas.
- `.DS_Store` adalah metadata, tidak relevan untuk aplikasi.

## File Beda Isi Bermakna

Perbedaan utama terjadi pada:

- Konfigurasi: `config/config.php`, `config/database.php`, `config/routes.php`.
- Runtime/HMVC: `core/MY_Router.php`, `third_party/MX/Controller.php`, `third_party/MX/Loader.php`, `third_party/MX/Modules.php`.
- Helper: `helpers/global_helper.php`.
- Admin order/payment: `modules/admin/controllers/Orders.php`, `modules/admin/views/header.php`.
- Mobile API: `modules/api/controllers/Mobile.php`, `modules/api/models/Mobile_api_model.php`.
- Customer flow: `modules/customer/controllers/Home.php`, `Orders.php`, `Profile.php`, `Shop.php`, serta view `home.php`, `profile.php`, `message.php`, footer/header, dan `shop/product_detail.php`.

## Perbedaan Fitur Lokal Yang Belum Ada Di Production

### 1. BRIVA Switch

Lokal menambahkan route:
- `admin/briva-switch`
- `admin/briva-switch/update`

Lokal menambahkan:
- controller `modules/admin/controllers/Briva_switch.php`
- view `modules/admin/views/briva_switch/index.php`
- menu admin `BRIVA SWITCH` di `modules/admin/views/header.php`
- helper `briva_payment_mode()` dan `is_briva_payment_local()` di `helpers/global_helper.php`

Fungsi bisnis:
- Admin role `admin` dapat memilih mode `local` atau `production`.
- Mode `local` membuat pembayaran BRIVA simulasi tanpa request ke API BRI production.

Catatan deployment:
- Kode mengandalkan setting key `briva_payment_mode` melalui `settings`.
- Dump SQL pembanding tidak berisi data `INSERT`, sehingga nilai awal setting ini tidak bisa diverifikasi dari dump.
- Jika fitur ini dipromosikan ke production, siapkan seed/operational SOP: default harus `production` kecuali sengaja UAT lokal.

### 2. Mobile API BRIVA Lokal

Lokal mengubah method:
- `Mobile::generate_briva_payment`
- `Mobile::briva_payment_status`
- `Mobile_api_model::generate_briva_payment`
- `Mobile_api_model::briva_payment_status`

Lokal menambah method model:
- `generate_local_briva_payment`
- `local_briva_payment_status`

Fungsi bisnis:
- Saat mode BRIVA lokal aktif, API mobile tidak memuat library `Brivaws`.
- Sistem membuat/simulasi status pembayaran di tabel `briva_api` dan update `orders.order_status` menjadi `10`.

Risiko:
- Sangat berguna untuk development/UAT tanpa menyentuh BRI production.
- Berbahaya jika production tanpa sengaja diset ke mode `local`, karena payment dapat dianggap diterima oleh simulasi lokal.

### 3. Tutorial Dan Guide Book Customer

Lokal menambahkan route:
- `profile/tutorial`
- `profile/guide-book-customer`

Lokal menambahkan method:
- `Profile::tutorial`
- `Profile::guide_book_customer`

Lokal menambahkan UI:
- tombol `Tutorial` dan `Panduan` di profile.
- overlay tutorial di `modules/customer/views/home.php` berbasis `data-tour`.

Catatan:
- Ini fitur UI/UX customer dan tidak membutuhkan perubahan struktur database.

## Perbedaan Yang Justru Membuat Lokal Tertinggal Dari Production

Production memiliki hardening PHP 8.x yang tidak sama dengan lokal:

- `third_party/MX/Modules.php` production sudah mengganti pemakaian `each()` dengan pola kompatibel PHP modern.
- `third_party/MX/Controller.php` production memiliki `#[\AllowDynamicProperties]` dan property `public $load`.
- Lokal masih memakai `each()` pada `Modules::load()`, yang tidak kompatibel dengan PHP 8.
- Lokal memang menambahkan `core/MY_URI.php` dan `MY_Router::$uri`, tetapi itu belum cukup bila file HMVC utama masih memakai API lama.

Rekomendasi:
- Jangan overwrite file HMVC production dengan versi lokal.
- Jika fitur lokal akan dipromosikan, merge selektif: ambil fitur BRIVA/tutorial/API, tetapi pertahankan hardening PHP 8.x production.

## Temuan Non-Gap Yang Tetap Penting

`modules/admin/controllers/Orders.php::inquiry_status_va()` di kedua folder mengeluarkan `debug_raw` lalu `return`. Blok di bawahnya yang seharusnya memproses `paidStatus` dan insert payment tidak pernah berjalan.

Status:
- Ini bukan perbedaan lokal vs production baru.
- Ini risiko existing yang ada di kedua sisi.

## Rekomendasi Deployment

Prioritas 1 - Jangan full replace:
- Hindari mengganti seluruh `application_production` dengan `application_lokal`.
- Risiko terbesar: regresi PHP 8.x dan artefak lokal ikut masuk production.

Prioritas 2 - Merge selektif:
- Ambil fitur BRIVA Switch hanya jika bisnis memang membutuhkan mode simulasi terkendali.
- Ambil tutorial/guide customer jika sudah disetujui UI/operasional.
- Ambil perubahan API mobile BRIVA lokal hanya dengan default setting `production`.

Prioritas 3 - Bersihkan artefak sebelum deploy:
- Jangan deploy `.DS_Store`.
- Jangan deploy `modules/api/controllers.4612`, `modules/api/models.5789`, `modules/api/api.zip` kecuali memang dipakai sebagai arsip resmi.

Prioritas 4 - UAT wajib:
- Test login admin dan akses `admin/briva-switch`.
- Test mode `production`: pastikan request tetap memakai library `Brivaws`.
- Test mode `local`: pastikan tidak ada request ke API BRI dan status order berubah sesuai desain.
- Test customer profile, tutorial, guide book, home overlay, cart, checkout, order history, dan payment.

