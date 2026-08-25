# Use Case List Testing Customer dan Admin

Tanggal audit: 2026-08-21

## Ringkasan Eksekutif

Dokumen ini adalah daftar use case testing dari hulu ke hilir untuk modul customer, admin, dan API mobile customer pada aplikasi KIU Store berbasis CodeIgniter. Semua skenario di bawah disusun dari route, controller, model, dan helper yang ada di repositori lokal.

## Taksonomi Data

### Verified System Facts

- Front controller aktif memakai folder `application` melalui `index.php`.
- Route utama tersimpan di `application/config/routes.php`.
- Login web memakai `application/controllers/auth/Login.php` dan `application/views/auth/login.php`.
- Registrasi web customer memakai `application/controllers/auth/Register.php`.
- Modul customer berada di `application/modules/customer`.
- Modul admin berada di `application/modules/admin`.
- API mobile customer berada di `application/modules/api`.
- Role admin yang dianggap sah oleh helper adalah `admin`, `adminonline`, `keuangan`, `salesman`, `distribusi`, dan `kadep`.
- Role customer yang dianggap sah oleh helper adalah `customer`.

### Milestone Completion %

Belum ditetapkan. Dokumen ini adalah daftar skenario uji, bukan hasil eksekusi test suite.

### Business Outcome KPIs

Belum tersedia baseline data produksi berjalan. KPI bisnis yang disarankan untuk pengukuran lapangan:

- Conversion rate checkout = jumlah order berhasil dibuat / jumlah sesi keranjang.
- Payment success rate = jumlah pembayaran terverifikasi / jumlah order dengan payment method non-kredit.
- Order cycle time = waktu selesai order - waktu order dibuat.
- Customer support response time = waktu balasan admin - waktu pesan customer.

## Audit Trail Utama

| Area | Sumber Sistem |
|---|---|
| Routing web dan API | `application/config/routes.php` |
| Auth login/logout/register | `application/controllers/auth/Login.php`, `Logout.php`, `Register.php` |
| Session dan role guard | `application/helpers/session_helper.php` |
| Status order dan payment | `application/helpers/global_helper.php` |
| Customer storefront | `application/modules/customer/controllers/Home.php`, `Product.php`, `Shop.php` |
| Customer profile, payment, order | `application/modules/customer/controllers/Profile.php`, `Payments.php`, `Orders.php` |
| Customer chat/contact/review | `application/modules/customer/controllers/Message.php`, `Home.php`, `Reviews.php` |
| Admin dashboard dan menu | `application/modules/admin/controllers/Dashboard.php`, `application/modules/admin/views/header.php` |
| Admin produk/customer/order/payment | `application/modules/admin/controllers/Products.php`, `Customers.php`, `Orders.php`, `Payments.php` |
| API mobile | `application/modules/api/controllers/Mobile.php`, `application/modules/api/models/Mobile_api_model.php` |

## Prasyarat Testing

| Kebutuhan | Detail |
|---|---|
| Environment | Local XAMPP dengan base URL project KIU Store |
| Database | Database test berisi `users`, `customers`, `products`, `product_category`, `orders`, `order_items`, `payments`, `briva_api`, `message`, `contacts`, `settings` |
| Akun customer | Minimal 1 user role `customer` status aktif, level 1 dan level lebih dari 1 |
| Akun admin | Minimal role `admin`, `adminonline`, `keuangan`, `salesman`, `distribusi`, `kadep` |
| Produk | Minimal 1 produk aktif, stok positif, kategori valid, harga level 1/2/3 terisi |
| Pembayaran | `payment_banks` terisi di settings untuk transfer bank |
| Ongkir | Credential RajaOngkir/Komerce aktif atau mode mock/local tersedia |
| BRIVA | Mode local BRIVA atau credential BRI valid, sesuai helper `is_briva_payment_local()` |

## Peta Status Order dan Payment

| Kode | Status Order |
|---|---|
| 1 | Proses oleh Sales |
| 2 | Menunggu Pembayaran |
| 3 | Pengemasan |
| 4 | Pengiriman |
| 5 | Barang Diterima |
| 6 | Selesai |
| 7 | Dibatalkan |
| 8 | Menunggu Konfirmasi Pembayaran |
| 9 | Dalam Pengajuan Kredit |
| 10 | Payment Verify |
| 11 | Tentukan Metode Pengiriman Anda |

| Kode | Status Payment |
|---|---|
| 1 | Menunggu konfirmasi |
| 2 | Berhasil dikonfirmasi |
| 3 | Pembayaran tidak ditemukan / kurang bayar |
| 10 | Menunggu konfirmasi |

## Customer Web - Use Case Testing

| ID | Modul | Skenario | Precondition | Langkah Uji | Expected Result | Audit Trail |
|---|---|---|---|---|---|---|
| CUS-AUTH-001 | Register | Registrasi customer berhasil | Email dan nomor HP belum ada | Buka `/register`, isi password, nama, alamat, no HP, email, submit | User role `customer` dan data `customers` dibuat, diarahkan ke notifikasi register | `Register::verify`, `Register_model` |
| CUS-AUTH-002 | Register | Validasi email duplikat | Email sudah ada di `users` | Submit register dengan email sama | Form gagal, pesan validasi muncul | `Register::verify` |
| CUS-AUTH-003 | Register | Validasi nomor HP duplikat | Phone sudah ada di `customers` | Submit register dengan no HP sama | Form gagal, pesan validasi muncul | `Register::verify` |
| CUS-AUTH-004 | Login | Login customer berhasil | Akun customer aktif | Buka `/login`, isi email dan password valid | Session customer dibuat, redirect ke `/home` | `Login::do_login`, `session_helper::verify_login` |
| CUS-AUTH-005 | Login | Password salah | Akun aktif | Submit password salah | Redirect login dengan flash `Password salah!`, email lama tersimpan | `Login::do_login` |
| CUS-AUTH-006 | Login | User tidak aktif | `users.status` tidak aktif | Submit credential valid | Login ditolak dengan pesan tidak aktif/belum diverifikasi admin | `Login::do_login` |
| CUS-AUTH-007 | Login Redirect | Akses halaman customer tanpa session | Belum login | Buka `/cart` atau `/profile` | Redirect ke `/auth/login?redir_to=...` | `verify_session('customer')` |
| CUS-AUTH-008 | Logout | Logout customer | Customer login | Buka `/logout` | Session login terhapus, user keluar dari area customer | `Logout::index` |
| CUS-HOME-001 | Home | Halaman home tampil | Produk/kategori tersedia | Buka `/home` | Banner, kategori, promo, produk, invoice/tagihan tampil sesuai data | `Home::index` |
| CUS-HOME-002 | Search | Pencarian produk | Produk tersedia | Search query via `/search?search_query=...` | Daftar produk sesuai query, query di-escape | `Home::search`, `Product_model` |
| CUS-CAT-001 | Kategori | List kategori tampil | Kategori ada | Buka `/category` | Kategori produk tampil | `Product::all_categories` |
| CUS-CAT-002 | Produk kategori | Produk per kategori tampil | Kategori dan produk ada | Buka `/category/{id}/{name}` | Produk kategori tampil dengan pagination | `Product::products_in_category` |
| CUS-PROD-001 | Produk | Semua produk tampil | Produk aktif ada | Buka `/all_products` | Produk tampil dengan pagination 10 item | `Product::all_products` |
| CUS-PROD-002 | Produk | Detail produk valid | ID dan SKU valid | Buka `/product/{id}/{sku}` | Detail dan related products tampil | `Product::product` |
| CUS-PROD-003 | Produk | Detail produk invalid | ID/SKU tidak cocok | Buka URL detail invalid | Sistem menampilkan 404 atau error akses tidak sah | `Product::product` |
| CUS-PROMO-001 | Promo | Produk promo tampil | Promo aktif ada | Buka `/promo` | Produk promo tampil | `Product::promo` |
| CUS-CART-001 | Cart | Keranjang kosong | Customer login | Buka `/cart` | Halaman cart tampil tanpa error, total 0 | `Shop::cart` |
| CUS-CART-002 | Cart | Tambah produk ke cart | Produk stok positif | Tambah produk dari detail/list ke cart | Item masuk ke cart dan total berubah | `Shop::cart_api`, `Product_model` |
| CUS-CART-003 | Cart | Update quantity | Cart berisi produk | Ubah qty lalu lanjut checkout | Qty tersimpan di session cart | `Shop::checkout` |
| CUS-CART-004 | Cart | Produk data tidak lengkap | Cart item tidak punya product type | Lanjut checkout | Redirect ke cart dengan flash data produk tidak lengkap | `Shop::_resolve_cart_product_type` |
| CUS-ONGKIR-001 | Ongkir | Hitung ongkir valid | Cart berisi item berbobot, alamat valid | Submit origin, destination, courier | Opsi ongkir tampil | `Shop::district_calculate_cost`, `Rajaongkir_model` |
| CUS-ONGKIR-002 | Ongkir | Hitung ongkir invalid | Parameter kosong | Submit tanpa origin/destination/courier | JSON error code 400 | `Shop::district_calculate_cost` |
| CUS-ONGKIR-003 | Ongkir | Pilih ongkir | Opsi ongkir tersedia | Submit action `addongkir` | Ongkir tersimpan dan status ongkir cart menjadi aktif | `Shop::ongkir` |
| CUS-ONGKIR-004 | Ongkir | Hapus ongkir | Ongkir aktif | Submit action `deleteongkir` | Status ongkir kembali nonaktif | `Shop::ongkir` |
| CUS-CHK-001 | Checkout | Checkout tanpa login | Belum login, cart/session ada | Submit checkout | Redirect login dengan temp coupon/quantity tersimpan | `Shop::checkout` |
| CUS-CHK-002 | Checkout | Checkout tanpa POST | Customer login | Buka `/checkout` langsung | Redirect ke `/home` | `Shop::checkout` |
| CUS-CHK-003 | Checkout | Transaksi BRIVA aktif | Ada order BRIVA berjalan | Submit checkout | Redirect cart dengan flash transaksi berjalan | `Payment_model::briva_is_active` |
| CUS-CHK-004 | Checkout | Kupon valid | Kupon aktif belum expired | Submit coupon valid | Discount masuk ke total dan session coupon_id tersimpan | `Shop::checkout`, `Customer_model` |
| CUS-CHK-005 | Checkout | Kupon tidak terdaftar | Coupon code invalid | Submit coupon invalid | Discount 0, info kupon tidak terdaftar | `Shop::checkout` |
| CUS-CHK-006 | Checkout | Kupon expired/nonaktif | Kupon expired atau inactive | Submit coupon | Discount 0, info expired/nonaktif | `Shop::checkout` |
| CUS-ORDER-001 | Order | Order kredit customer level 1 | Level 1, payment 1 | Submit `/checkout/order` | Order dibuat status 9, item tersimpan, cart dibersihkan sesuai `kd_faktur` | `Shop::checkout('order')` |
| CUS-ORDER-002 | Order | Kredit melewati limit | Total kredit lebih dari `max_credit` | Submit payment 1 | Redirect cart dengan flash limit kredit | `Shop::checkout('order')`, `get_user_limit_transaction` |
| CUS-ORDER-003 | Order | Order Virtual Account | Payment 2 | Submit order VA | Order dibuat status 2 atau flow BRIVA sesuai member/local mode | `Shop::checkout('order')`, `briva_api` |
| CUS-ORDER-004 | Order | Order transfer bank | Payment 3 | Submit order transfer | Order dibuat dan menunggu pembayaran/konfirmasi | `Shop::checkout('order')` |
| CUS-PAY-001 | Payment | List pembayaran customer | Ada payment milik user | Buka `/customer/payments` | List payment milik user tampil | `Payments::index` |
| CUS-PAY-002 | Payment | Form konfirmasi transfer | Ada order butuh transfer | Buka `/customer/payments/confirm?order={id}` | Data order, bank tujuan, countdown tampil | `Payments::confirm` |
| CUS-PAY-003 | Payment | Submit konfirmasi transfer valid | File bukti jpg/png opsional | Isi bank asal, nama, nominal, bank tujuan, submit | Row `payments` status 1 dan `briva_api` status 1 dibuat, redirect order history | `Payments::do_confirm`, `Payment_model` |
| CUS-PAY-004 | Payment | Validasi form transfer | Field wajib kosong | Submit form kosong | Form gagal dengan pesan validasi | `Payments::do_confirm` |
| CUS-PAY-005 | Payment | Bukti bayar invalid | File selain jpg/jpeg/png atau > limit | Upload file invalid | Upload gagal atau picture `-` sesuai kondisi upload | `Payments::do_confirm` |
| CUS-ORDH-001 | Order History | List order customer | Customer punya order | Buka `/order_history` | Order milik customer tampil | `Orders::index`, `Order_model` |
| CUS-ORDH-002 | Order Detail | Detail order valid | Order milik customer | Buka `/order_view/{id}` | Detail order, item, bank/payment tampil | `Orders::view` |
| CUS-ORDH-003 | Order Detail | Akses order milik user lain | Order bukan milik customer | Buka `/order_view/{id}` | Sistem menolak atau 404 | `Order_model::is_order_exist` |
| CUS-ORDH-004 | BRIVA Status | Cek status VA unpaid | Data VA status 1 | Trigger cek VA | Response `UNPAID` | `Orders::cek_va_status` |
| CUS-ORDH-005 | BRIVA Status | Cek status VA paid/local | Data VA paid/local | Trigger status VA | Order/payment terupdate sesuai flow | `Orders::update_briva_status`, `Order_model` |
| CUS-ORDH-006 | Order Action | Batalkan order | Order masih dapat dibatalkan | Trigger cancel | Order menjadi status 7 dan VA ikut dibatalkan bila berlaku | `Orders::update_expired`, `Mobile::cancel_order` untuk API |
| CUS-REV-001 | Review | Tulis review/rating | Order selesai/diterima | Buka review, isi rating dan deskripsi | Rating tersimpan di order/review | `Reviews::write`, `Reviews::write_me` |
| CUS-PROF-001 | Profile | Lihat profil | Customer login | Buka `/profile` | Profil, poin silver/gold/platinum tampil | `Profile::index` |
| CUS-PROF-002 | Profile | Edit data profil | Data valid | Update nama, HP, alamat, toko, gambar opsional | Data `customers` terupdate, flash sukses | `Profile::edit_name`, `Profile_model` |
| CUS-PROF-003 | Profile | Upload foto invalid | File selain jpg/png atau > limit | Submit edit profil | Upload ditolak dengan error | `Profile::edit_name` |
| CUS-PROF-004 | Profile | Edit password | Password minimal 4 | Submit password baru | Password hash di `users` berubah | `Profile::edit_account` |
| CUS-PROF-005 | Profile | Edit email valid | Email valid | Submit email baru | Email `users` berubah | `Profile::edit_email` |
| CUS-PROF-006 | Profile | Update alamat kirim | Data provinsi/kab/kec valid | Submit `cus_edit_customer/3` | Province, kota, subdistrict, alamat_kirim terupdate | `Profile::cus_editdata` |
| CUS-PROF-007 | Profile | Reset pilihan alamat | Customer login | Buka `cus_edit_customer/2` | province/kota/subdistrict direset ke 0 | `Profile::cus_editdata` |
| CUS-GUIDE-001 | Guide Book | Guide book customer | Customer login | Buka `/profile/guide-book-customer` | Halaman guide book customer tampil | `Profile::guide_book_customer` |
| CUS-MSG-001 | Chat | Kirim pesan customer | Customer login | Buka `/message`, kirim pesan | Row `message` dibuat, response JSON sukses | `Message::send`, `Message_model` |
| CUS-MSG-002 | Chat | Fetch chat | Ada pesan | Trigger `/message/fetch` | JSON chat terurut tampil, status unread berubah sesuai flow | `Message::fetch` |
| CUS-MSG-003 | Chat | Hitung unread | Ada pesan dari admin belum dibaca | Trigger `/count_unread_messages` | Count unread sesuai data | `Message::count_unread` |
| CUS-CONT-001 | Contact | Kirim contact form | Data valid | Buka `/contact`, isi form, submit | Row `contacts` dibuat, flash sukses | `Home::send_contact`, `Contact_model` |
| CUS-CONT-002 | Contact | Validasi contact form | Field wajib kosong | Submit kosong | Form gagal dengan pesan validasi | `Home::send_contact` |
| CUS-POL-001 | Policy | Privacy policy | Public/customer | Buka `/privacy-policy` atau `/policys` | Halaman policy tampil | `Terms::policy_privacy` |

## Mobile API Customer - Use Case Testing

| ID | Endpoint | Skenario | Langkah Uji | Expected Result | Audit Trail |
|---|---|---|---|---|---|
| API-001 | `GET /api/v1/status` | Health check | Request endpoint | JSON status OK | `Mobile::index` |
| API-002 | `POST /api/v1/auth/register` | Register mobile | Kirim payload wajib | User, customer, onboarding flag dibuat | `Mobile::register`, `Mobile_api_model` |
| API-003 | `POST /api/v1/auth/login` | Login mobile | Credential valid | Token dibuat di `mobile_api_tokens` | `Mobile::login` |
| API-004 | `POST /api/v1/auth/logout` | Logout token | Bearer token valid | Token revoked | `Mobile::logout` |
| API-005 | `POST /api/v1/onboarding/complete` | Complete onboarding | Token valid | Flag onboarding selesai | `Mobile::onboarding_complete` |
| API-006 | `GET /api/v1/account` | Account detail | Token valid | Data account/customer tampil | `Mobile::account` |
| API-007 | `GET/PUT /api/v1/profile` | Profile mobile | Token valid, payload valid | Data profile/customer terupdate | `Mobile::profile` |
| API-008 | `GET /api/v1/categories` | Kategori mobile | Request public | Kategori tampil | `Mobile::categories` |
| API-009 | `GET /api/v1/products` | Produk mobile | Query optional | Produk dari `v_products` tampil | `Mobile::products` |
| API-010 | `GET /api/v1/products/{id}` | Detail produk mobile | ID valid | Detail produk tampil | `Mobile::product` |
| API-011 | `GET/POST /api/v1/cart` | Cart mobile | Token valid | Cart tampil atau item ditambahkan | `Mobile::cart`, `mobile_cart_items` |
| API-012 | `PUT/DELETE /api/v1/cart/{id}` | Update/delete cart item | Token valid, item milik user | Qty berubah atau item terhapus | `Mobile::cart_item` |
| API-013 | `GET /api/v1/shipping/provinces` | Province list | Request valid | List province tampil | `Mobile::shipping_provinces` |
| API-014 | `GET /api/v1/shipping/cities` | City list | Province valid | List city tampil | `Mobile::shipping_cities` |
| API-015 | `GET /api/v1/shipping/districts` | District list | City valid | List district tampil | `Mobile::shipping_districts` |
| API-016 | `POST /api/v1/shipping/quotes` | Quote ongkir | Cart dan alamat valid | Quote tersimpan di `mobile_shipping_quotes` | `Mobile::shipping_quotes` |
| API-017 | `GET /api/v1/payment-methods` | Metode payment | Token valid | Kredit/VA/transfer sesuai logic tampil | `Mobile::payment_methods` |
| API-018 | `GET /api/v1/payments/banks` | Bank payment | Settings bank terisi | List bank tujuan tampil | `Mobile::payment_banks` |
| API-019 | `POST /api/v1/orders/checkout` | Checkout mobile | Cart dan quote valid | Order dan order_items dibuat, cart mobile kosong | `Mobile::checkout` |
| API-020 | `GET /api/v1/orders` | List order | Token valid | Order milik user tampil | `Mobile::orders` |
| API-021 | `GET /api/v1/orders/{id}` | Detail order | Order milik user | Detail order tampil | `Mobile::order` |
| API-022 | `POST /api/v1/orders/{id}/payment-method` | Pilih metode payment | Order valid | `payment_method` order berubah | `Mobile::select_payment_method` |
| API-023 | `POST /api/v1/orders/{id}/payments/briva` | Generate BRIVA | Order valid | Data `briva_api` dibuat/diupdate | `Mobile::generate_briva_payment` |
| API-024 | `GET /api/v1/orders/{id}/payments/briva/status` | Cek BRIVA | Order valid | Status BRIVA/order dikembalikan | `Mobile::briva_payment_status` |
| API-025 | `POST /api/v1/orders/{id}/payments/bank-transfer` | Konfirmasi transfer | Order valid, bukti optional | Row `payments` dibuat status menunggu konfirmasi | `Mobile::confirm_bank_transfer` |
| API-026 | `POST /api/v1/orders/{id}/complete` | Selesaikan order | Order bisa diselesaikan | Order menjadi selesai/diterima sesuai logic | `Mobile::complete_order` |
| API-027 | `POST /api/v1/orders/{id}/cancel` | Batalkan order | Order bisa dibatalkan | Order status 7 | `Mobile::cancel_order` |
| API-028 | `GET/POST /api/v1/messages` | Chat mobile | Token valid | Pesan tampil atau terkirim | `Mobile::messages` |
| API-029 | Semua endpoint private | Token invalid/revoked | Request dengan token salah | Response unauthorized | `Mobile::authenticate` |
| API-030 | Method guard | Method salah | Kirim GET ke endpoint POST-only | Response method not allowed | `Mobile::require_method` |

## Admin Web - Use Case Testing

| ID | Modul | Skenario | Precondition | Langkah Uji | Expected Result | Audit Trail |
|---|---|---|---|---|---|---|
| ADM-AUTH-001 | Login | Login admin berhasil | Akun role admin aktif | Login via `/login` | Redirect `/dashboard_admin` | `Login::do_login` |
| ADM-AUTH-002 | Role Guard | Customer akses admin | Login customer | Buka `/admin/products` | Redirect login atau ditolak | `verify_session('admin')`, `is_admin` |
| ADM-AUTH-003 | Menu Role | Menu admin sesuai role | Login tiap role | Bandingkan sidebar | Menu tampil sesuai role guard di header | `admin/views/header.php` |
| ADM-DASH-001 | Dashboard | Dashboard tampil | Admin login | Buka `/dashboard_admin` | Dashboard tampil tanpa error | `Dashboard::index` |
| ADM-PROD-001 | Produk | List produk | Role admin/adminonline/salesman/keuangan | Buka `/admin/products` | List produk, pagination, filter harga tampil | `Products::index` |
| ADM-PROD-002 | Produk | Search produk admin | Produk ada | Submit search | Hasil sesuai query dan query di-escape | `Products::search` |
| ADM-PROD-003 | Produk | Tambah produk valid | Kategori ada, gambar valid optional | Isi nama, harga, stok, satuan, berat, kategori | Row `products` dibuat, SKU dibuat | `Products::add_product`, `Product_model` |
| ADM-PROD-004 | Produk | Validasi tambah produk | Field wajib kosong | Submit tambah produk | Form gagal | `Products::add_product` |
| ADM-PROD-005 | Produk | Upload gambar produk invalid | File selain jpg/png/jpeg atau >2 MB | Submit gambar invalid | Upload ditolak | `Products::add_product` |
| ADM-PROD-006 | Produk | Edit produk valid | Produk ada | Ubah harga/stok/deskripsi/status | Row `products` terupdate | `Products::edit_product` |
| ADM-PROD-007 | Produk | Hapus gambar produk | Produk punya gambar | Trigger product API `delete_image` | File dan kolom picture dihapus | `Products::product_api` |
| ADM-PROD-008 | Produk | Hapus produk | Produk ada | Trigger product API `delete_product` | Row produk dihapus, file gambar ikut dihapus bila ada | `Products::product_api` |
| ADM-CAT-001 | Kategori | List kategori | Role admin/adminonline | Buka `/admin/categories` | Kategori tampil | `Products::category` |
| ADM-CAT-002 | Kategori | Tambah/edit/hapus kategori | Kategori test | Trigger `category_api` add/edit/delete | Data `product_category` berubah sesuai aksi | `Products::category_api` |
| ADM-COUP-001 | Kupon | List kupon | Role admin/salesman/adminonline | Buka `/admin/products/coupons` | Kupon tampil dengan status aktif/expired | `Products::coupons` |
| ADM-COUP-002 | Kupon | Tambah/edit/hapus kupon | Data kupon valid | Trigger `coupon_api` | Data `coupons` berubah | `Products::coupon_api` |
| ADM-PROMO-001 | Promo | List promo | Produk ada | Buka `/admin/products/promo` | Promo tampil | `Products::promo` |
| ADM-PROMO-002 | Promo | Tambah/edit/hapus promo | Produk valid | Trigger `promo_api` | Data `promo` berubah | `Products::promo_api` |
| ADM-BAN-001 | Banner Produk | CRUD banner | Gambar/banner valid | Tambah/edit/delete banner | Data `banner_product` berubah | `Banner_product` controller |
| ADM-CUST-001 | Customer | List customer | Role admin/salesman/adminonline/keuangan | Buka `/admin/customers` | List customer tampil | `Customers::index` |
| ADM-CUST-002 | Customer | Tambah customer admin | Data valid | Isi nama, NIK, NPWP, email, password, kota, level, limit, salesman | Row `users` dan `customers` dibuat | `Customers::add_customer` |
| ADM-CUST-003 | Customer | Validasi tambah customer | Field wajib kosong | Submit form | Form gagal | `Customers::add_customer` |
| ADM-CUST-004 | Customer | View customer | Customer ada | Buka `/admin/customers/view/{id}` | Profil, order, payment, VA, ringkasan point tampil | `Customers::view` |
| ADM-CUST-005 | Customer | Edit customer | Customer ada | Ubah data customer | Data `customers` terupdate | `Customers::api('edit')` |
| ADM-CUST-006 | Customer | Activate/deactivate | Customer ada | Trigger API activate/deactivate | `users.status` berubah | `Customers::api` |
| ADM-CUST-007 | Customer | Reset password | Customer ada | Trigger reset password | Password menjadi hash dari default `1234` | `Customers::api('reset_password')` |
| ADM-CUST-008 | Customer | Generate VA customer | Customer ada | Submit VA code | Kolom `va_code` terupdate | `Customers::generate_va` |
| ADM-ORD-001 | Order | List order sales/admin | Order ada | Buka `/admin/orders` | Order tampil dan status diformat | `Orders::index`, `Orders::api('order_all')` |
| ADM-ORD-002 | Order | List distribusi | Role distribusi/admin | Buka `/admin/orders/distribusi` | Order pengemasan/pengiriman tampil | `Orders::distribusi` |
| ADM-ORD-003 | Order | List kadep/rating | Role kadep/admin | Buka `/admin/orders/kadep` | Rating sales/order selesai tampil | `Orders::kadep` |
| ADM-ORD-004 | Order | View order | Order ada | Buka `/admin/orders/view/{id}` | Detail order, item, delivery, bank, resi, payment flash tampil | `Orders::view` |
| ADM-ORD-005 | Order | Update status order | Order ada | Submit status baru | `orders.order_status` berubah, flash sukses | `Orders::status` |
| ADM-ORD-006 | Order | Verify order | Order ada | Submit invoice, ekspedisi, TTB, shipping cost, insurance, status | Order terverifikasi sesuai model | `Orders::api('verify')`, `Order_model::verify` |
| ADM-ORD-007 | Order | Update harga allowed | Order status 1, 9, atau 11 | Ubah harga item | Harga item dan total order berubah dalam transaksi DB | `Orders::api('update_harga')` |
| ADM-ORD-008 | Order | Update harga blocked | Order bukan status 1/9/11 | Ubah harga item | Ditolak dengan flash status tidak dapat diperbarui | `Orders::api('update_harga')` |
| ADM-ORD-009 | Order | Update resi | Order punya `kd_faktur` | Submit resi customer | Resi tersimpan | `Orders::api('update_resi')` |
| ADM-ORD-010 | Order | Reset resi | Resi ada | Submit reset | Resi dikosongkan/reset | `Orders::api('reset_resi')` |
| ADM-ORD-011 | Order | Update pengemasan massal | List order dipilih | Submit ids, tgl pengiriman, no truk | Order menjadi status 4 | `Orders::api('update_pengemasan')` |
| ADM-BRIVA-001 | BRIVA | Inquiry status VA local unpaid/cancel | Order/VA ada | Trigger inquiry | Response sesuai mode local atau BRI | `Orders::inquiry_status_va` |
| ADM-BRIVA-002 | BRIVA | Cek VA status paid | `briva_api.status` 2 | Trigger cek VA | Payment dibuat, order status 3/payment method 2 | `Orders::cek_va_status` |
| ADM-BRIVA-003 | BRIVA Switch | Toggle BRIVA local/live | Role admin | Buka `/admin/briva-switch`, update | Konfigurasi BRIVA berubah | `Briva_switch::index/update` |
| ADM-PAY-001 | Payment | List pembayaran | Role admin/keuangan | Buka `/admin/payments` | Payment all/confirmed/not confirmed tampil | `Payments::index` |
| ADM-PAY-002 | Payment | View payment | Payment ada | Buka `/admin/payments/view/{id}` | Detail payment dan bank tampil | `Payments::view` |
| ADM-PAY-003 | Payment | Konfirmasi payment berhasil | Payment status 1 | Submit action 1 | Payment status 2, order status sesuai model | `Payments::verify`, `Payment_model` |
| ADM-PAY-004 | Payment | Payment gagal/kurang bayar | Payment status 1 | Submit action 2 | Payment status 3, order status gagal sesuai model | `Payments::verify` |
| ADM-PIU-001 | Piutang | List piutang | Role admin/keuangan | Buka `/admin/piutang` | Piutang tampil | `Piutang::index` |
| ADM-PIU-002 | Piutang | Verify piutang | Payment/order kredit ada | Submit verify | Status payment/order berubah sesuai model | `Piutang::verify` |
| ADM-KIRIM-001 | Pengiriman | List pengiriman | Role admin/distribusi | Buka `/admin/pengiriman` | Data pengiriman tampil | `Pengiriman::index` |
| ADM-KIRIM-002 | Pengiriman | View TTB | TTB valid | Buka view TTB | Detail pengiriman tampil | `Pengiriman::view` |
| ADM-SALES-001 | Salesman | List salesman | Role admin | Buka `/admin/salesman` | Data salesman tampil | `Salesman::index` |
| ADM-SALES-002 | Salesman | CRUD salesman | Data valid | Add/edit/delete salesman | Tabel `salesman` berubah | `Salesman::salesman_api` |
| ADM-MSG-001 | Chat Admin | List chat customer | Role admin/salesman/adminonline | Buka `/admin/messages` | Daftar chat tampil | `Messages::index` |
| ADM-MSG-002 | Chat Admin | Kirim/reply chat | Customer ada | Kirim pesan ke customer | Row `message` dibuat, unread customer berubah | `Messages::send/reply` |
| ADM-MSG-003 | Chat Admin | Fetch unread | Ada pesan unread | Trigger fetch/count | JSON pesan dan counter sesuai data | `Messages::fetch`, `get_unread` |
| ADM-CONT-001 | Contact | List contact | Contact ada | Buka `/admin/contacts` | Pesan contact tampil | `Contacts::index` |
| ADM-CONT-002 | Contact | View/reply contact | Contact ada | Buka view, reply | Contact status berubah menjadi dibaca/dibalas | `Contacts::view/reply` |
| ADM-REP-001 | Laporan | Laporan bulanan | Order ada | Buka `/admin/report` dan filter bulan/tahun | Tabel laporan tampil | `Report::index/tabel` |
| ADM-REP-002 | Laporan | Export Excel | Data bulan/tahun ada | Trigger `Report::excel` | File Excel response dibuat | `Report::excel` |
| ADM-RPEN-001 | R Penjualan | Report penjualan | Order ada | Buka modul r_penjualan | Data penjualan tampil | `R_penjualan` controller/model |
| ADM-RATE-001 | Rating | Rating sales | Order selesai dengan rating | Buka `/admin/rating` | Rating tampil | `Rating::index/tabel` |
| ADM-REV-001 | Reviews | Review pelanggan | Review ada | Buka `/admin/reviews` | Review tampil dan bisa view/delete | `Reviews` controller |
| ADM-SET-001 | Settings | Update setting toko | Role admin/adminonline | Buka `/admin/settings`, ubah data toko | Settings tersimpan | `Settings::update` |
| ADM-SET-002 | Settings | Update bank payment | Data bank valid | Tambah/update bank | `payment_banks` terupdate JSON | `Settings::add_bank/update` |
| ADM-SET-003 | Settings | Update profile admin | Admin login | Ubah nama/email/password/gambar | Data user admin berubah | `Settings::profile_update` |
| ADM-ONG-001 | Ongkir Admin | Province/city/district/cost | Credential tersedia | Hit endpoint `/Ongkir/...` | JSON ongkir tampil | `Ongkir` controller |

## Test Non-Fungsional dan Risiko

| ID | Area | Skenario | Expected Result | Audit Trail |
|---|---|---|---|---|
| SEC-001 | Auth | Endpoint admin tanpa login | Redirect login dengan `redir_to` | `verify_session` |
| SEC-002 | Auth | Endpoint customer tanpa login | Redirect login dengan `redir_to` | `verify_session` |
| SEC-003 | Data Ownership | Customer akses order/payment user lain | Ditolak/404 | `Order_model`, `Payment_model` |
| SEC-004 | Upload | Upload file ekstensi berbahaya | Ditolak oleh config upload | Product/Profile/Payment controllers |
| SEC-005 | Input | Search dengan HTML/script | Query di-escape | `Home::search`, `Products::search` |
| INT-001 | RajaOngkir | API timeout/error | UI/JSON tidak fatal, error state tampil | `Shop::cekongkir`, `Rajaongkir` |
| INT-002 | BRIVA | BRI API gagal | Error/response debug terkendali, order tidak corrupt | `Orders`, `Mobile`, `Brivaws` |
| DB-001 | Transaction | Update harga order gagal sebagian | Transaksi DB rollback atau pesan gagal | `Orders::api('update_harga')` |
| UX-001 | Mobile web | Halaman login, home, cart, checkout responsive | Tidak overlap dan CTA bisa diklik | Views customer/auth |

## Prioritas Eksekusi Regression

1. Auth dan role guard: `CUS-AUTH-*`, `ADM-AUTH-*`, `SEC-001` sampai `SEC-003`.
2. Customer hulu-hilir: register/login, home, search, product, cart, ongkir, checkout, payment, order history.
3. Admin order-payment hulu-hilir: order view, update status, update harga, verify order, verify payment, BRIVA check.
4. Master data admin: produk, kategori, kupon, promo, customer, settings bank.
5. Komunikasi dan support: chat customer/admin, contact form, review/rating.
6. API mobile: auth token, product/cart/checkout/payment/order/message.

## Definisi Done Testing

- Semua skenario prioritas 1 sampai 3 memiliki bukti eksekusi: tanggal, tester, environment, data test, screenshot atau response JSON.
- Setiap defect punya referensi route/controller/model dan status perbaikan.
- Tidak ada order/payment yang tertinggal pada status tidak konsisten setelah test BRIVA/transfer/kredit.
- Data upload test dibersihkan dari `assets/uploads/products`, `assets/uploads/users`, dan `assets/uploads/payments` bila tidak dibutuhkan.
- Tidak ada klaim KPI bisnis sampai baseline produksi tersedia.
