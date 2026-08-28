# Audit Perbandingan kiustore_apps vs application

Tanggal audit: 2026-08-28  
Basis perbandingan: path relatif file aktual di disk  
Sumber utama:
- `/Applications/XAMPP/xamppfiles/htdocs/kiustore/kiustore_apps`
- `/Applications/XAMPP/xamppfiles/htdocs/kiustore/application`

## Ringkasan Eksekutif

| Kategori | Jumlah | Makna Audit |
|---|---:|---|
| Total file di `kiustore_apps` | 260 | File aktual pada folder pembanding |
| Total file di `application` | 304 | File aktual pada main project |
| File dengan path sama | 256 | Ada di kedua folder |
| File sama persis | 47 | Path sama dan checksum sama |
| File path sama tetapi isi berbeda | 209 | Perlu review sebelum sinkronisasi |
| File hanya ada di `kiustore_apps` | 4 | Kandidat file baru dari folder pembanding |
| File hanya ada di `application` | 48 | Tidak tersedia di `kiustore_apps` |

## Distribusi File Berbeda Isi

| Area | Jumlah |
|---|---:|
| `modules/admin` | 110 |
| `modules/customer` | 66 |
| `controllers` | 8 |
| `helpers` | 7 |
| `modules/api` | 4 |
| `models` | 4 |
| `views` | 3 |
| `modules/errors` | 2 |
| `config` | 2 |
| `libraries` | 1 |
| `.DS_Store` | 1 |
| `modules/.DS_Store` | 1 |

## A. File Baru di `kiustore_apps`

File berikut ada di `kiustore_apps`, tetapi tidak ada pada path yang sama di `application`.

```text
config/.DS_Store
controllers/.DS_Store
modules/admin/views/ongkir/zahir_stock/index.php
modules/admin/views/report/zahir_stock/index.php
```

## B. File yang Tidak Ada di `kiustore_apps`

File berikut ada di `application`, tetapi tidak ada pada path yang sama di `kiustore_apps`.

```text
.htaccess
WORK-TO-DO
brivacoba.php
build_helper.php
config/autoload.php
config/briva.php
config/constants.php
config/database.php
config/doctypes.php
config/foreign_chars.php
config/hooks.php
config/memcached.php
config/migration.php
config/mimes.php
config/profiler.php
config/rajaongkir.php
config/smileys.php
config/user_agents.php
config/zahirdigital.php
core/MY_Loader.php
core/MY_Router.php
core/MY_URI.php
docs/application/2026-07-09-products-view-null-guard.md
docs/application/2026-07-20-brivaws-dynamic-property-php83.md
docs/application/2026-07-27-briva-production-config.md
docs/application/2026-08-05-setting-akun-internal.md
docs/database/2026-07-09-products-view-null-guard.md
docs/database/2026-07-20-brivaws-dynamic-property-php83.md
docs/database/2026-07-27-briva-production-config.md
docs/database/2026-08-05-migrasi-setting-akun-internal.md
docs/database/2026-08-05-setting-akun-internal.md
docs/usage/2026-08-05-setting-akun-internal.md
libraries/index
logs/.gitkeep
modules/admin/views/admin/vbriva.php
modules/admin/views/brivaws/index
modules/api/.DS_Store
modules/api/api.zip
modules/customer/controllers/addons/index.php
modules/customer/views/orders/view_coba.php
third_party/MX/Base.php
third_party/MX/Ci.php
third_party/MX/Config.php
third_party/MX/Controller.php
third_party/MX/Lang.php
third_party/MX/Loader.php
third_party/MX/Modules.php
third_party/MX/Router.php
```

## C. File Path Sama Tetapi Isi Berbeda

File berikut ada di kedua folder, namun checksum SHA-256 berbeda.

```text
.DS_Store
config/config.php
config/routes.php
controllers/Login.php
controllers/Pages.php
controllers/Welcome.php
controllers/auth/Login.php
controllers/auth/Logout.php
controllers/auth/Register.php
controllers/auth/User_agreement.php
controllers/briva/briva_list_function.php
helpers/briva_helper.php
helpers/brivaws_lib.php
helpers/f.php
helpers/global_helper.php
helpers/session_helper.php
helpers/snap_helper.php
helpers/themes_helper.php
libraries/Brivaws.php
models/Contact_model.php
models/Customer_model.php
models/auth/Login_model.php
models/auth/Register_model.php
modules/.DS_Store
modules/admin/controllers/12123.php
modules/admin/controllers/Admin.php
modules/admin/controllers/Api_payment_briva.php
modules/admin/controllers/BKrivawsapicopy.php
modules/admin/controllers/Banner_product.php
modules/admin/controllers/Briva_switch.php
modules/admin/controllers/Brivaws.json
modules/admin/controllers/Brivawsapi.php
modules/admin/controllers/Contacts.php
modules/admin/controllers/Customers.php
modules/admin/controllers/Dashboard.php
modules/admin/controllers/Messages.php
modules/admin/controllers/Ongkir.php
modules/admin/controllers/Orders.php
modules/admin/controllers/Payments.php
modules/admin/controllers/Pengiriman.php
modules/admin/controllers/Piutang.php
modules/admin/controllers/Products.php
modules/admin/controllers/R_penjualan.php
modules/admin/controllers/Rating.php
modules/admin/controllers/Report.php
modules/admin/controllers/Reviews.php
modules/admin/controllers/Salesman.php
modules/admin/controllers/Settings.php
modules/admin/controllers/Zahir_stock.php
modules/admin/controllers/test_debuged20022025.php
modules/admin/models/Admin_model.php
modules/admin/models/BRI_model.php
modules/admin/models/Contact_model.php
modules/admin/models/Customer_model.php
modules/admin/models/Message_model.php
modules/admin/models/Ongkir_model.php
modules/admin/models/Order_model.php
modules/admin/models/Payment_model.php
modules/admin/models/Piutang_model.php
modules/admin/models/Product_model.php
modules/admin/models/R_penjualan_model.php
modules/admin/models/Rajaongkir_model.php
modules/admin/models/Report_model.php
modules/admin/models/Review_model.php
modules/admin/models/Salesman_model.php
modules/admin/models/Setting_model.php
modules/admin/models/Zahir_stock_model.php
modules/admin/views/admin/add_new.php
modules/admin/views/admin/admin.php
modules/admin/views/admin/dev_ongkir.php
modules/admin/views/admin/edit.php
modules/admin/views/admin/view.php
modules/admin/views/banner_product/add_new_product.php
modules/admin/views/banner_product/category.php
modules/admin/views/banner_product/coupons.php
modules/admin/views/banner_product/edit_product.php
modules/admin/views/banner_product/products.php
modules/admin/views/banner_product/promo.php
modules/admin/views/banner_product/search.php
modules/admin/views/banner_product/view.php
modules/admin/views/briva_switch/index.php
modules/admin/views/brivaws/brivapayments.php
modules/admin/views/contacts/contacts.php
modules/admin/views/contacts/view.php
modules/admin/views/customers/add_new_customer.php
modules/admin/views/customers/customers.php
modules/admin/views/customers/edit_customer.php
modules/admin/views/customers/view.php
modules/admin/views/dashboard.php
modules/admin/views/footer.php
modules/admin/views/header.php
modules/admin/views/messages/messages.php
modules/admin/views/ongkir/script.php
modules/admin/views/ongkir/view.php
modules/admin/views/orders/orders.php
modules/admin/views/orders/orders_distribusi.php
modules/admin/views/orders/orders_kadep.php
modules/admin/views/orders/orders_rating_form.php
modules/admin/views/orders/orders_rating_table.php
modules/admin/views/orders/view.php
modules/admin/views/overview.php
modules/admin/views/payments/brivapayments.php
modules/admin/views/payments/payments.php
modules/admin/views/payments/paymentsbackup.php
modules/admin/views/payments/previewva.php
modules/admin/views/payments/view.php
modules/admin/views/pengiriman/pengiriman.php
modules/admin/views/pengiriman/view.php
modules/admin/views/piutang/piutang.php
modules/admin/views/piutang/view.php
modules/admin/views/products/add_new_banner_product.php
modules/admin/views/products/add_new_product.php
modules/admin/views/products/banner_product.php
modules/admin/views/products/category.php
modules/admin/views/products/coupons.php
modules/admin/views/products/edit_product.php
modules/admin/views/products/products.php
modules/admin/views/products/products_bc.php
modules/admin/views/products/promo.php
modules/admin/views/products/search.php
modules/admin/views/products/view.php
modules/admin/views/r_penjualan/report.php
modules/admin/views/r_penjualan/view.php
modules/admin/views/report/report.php
modules/admin/views/reports/report.php
modules/admin/views/reports/report_table.php
modules/admin/views/reports/view.php
modules/admin/views/reviews/reviews.php
modules/admin/views/reviews/view.php
modules/admin/views/salesman/edit_sales.php
modules/admin/views/salesman/salesman.php
modules/admin/views/settings/profile.php
modules/admin/views/settings/settings.php
modules/api/controllers.4612/Mobile.php
modules/api/controllers/Mobile.php
modules/api/models.5789/Mobile_api_model.php
modules/api/models/Mobile_api_model.php
modules/customer/controllers/APIongkir.php
modules/customer/controllers/Api_payment_briva.php
modules/customer/controllers/Customer.php
modules/customer/controllers/Home.php
modules/customer/controllers/Invoice.php
modules/customer/controllers/Message.php
modules/customer/controllers/Orders.php
modules/customer/controllers/Payments.php
modules/customer/controllers/Product.php
modules/customer/controllers/Profile.php
modules/customer/controllers/Rajaongkir.php
modules/customer/controllers/Reviews.php
modules/customer/controllers/Shop.php
modules/customer/controllers/ShopBK-asli.php
modules/customer/controllers/Terms.php
modules/customer/controllers/addons/addoncart.php
modules/customer/controllers/tr_transaksi.json
modules/customer/models/Invoice_model.php
modules/customer/models/Message_model.php
modules/customer/models/Order_model.php
modules/customer/models/Payment_model.php
modules/customer/models/Product_model.php
modules/customer/models/Profile_model.php
modules/customer/models/Rajaongkir_model.php
modules/customer/models/Review_model.php
modules/customer/views/change_password.php
modules/customer/views/change_profile_customer.php
modules/customer/views/contact.php
modules/customer/views/footer.php
modules/customer/views/footer_single.php
modules/customer/views/header.php
modules/customer/views/header_single.php
modules/customer/views/headerselect.php
modules/customer/views/home.php
modules/customer/views/invoice/invoice.php
modules/customer/views/message.php
modules/customer/views/orders/orders.php
modules/customer/views/orders/view.php
modules/customer/views/payments/confirm.php
modules/customer/views/payments/payments.php
modules/customer/views/payments/view.php
modules/customer/views/policy_privacy.php
modules/customer/views/profile.php
modules/customer/views/profile_alamat.php
modules/customer/views/profile_edit.php
modules/customer/views/profile_guide_book.php
modules/customer/views/profile_tutorial.php
modules/customer/views/profilebk_rajaongkir.php
modules/customer/views/reviews/reviews.php
modules/customer/views/reviews/view.php
modules/customer/views/reviews/write.php
modules/customer/views/search.php
modules/customer/views/shop/APIongkirtest.php
modules/customer/views/shop/cart.php
modules/customer/views/shop/cart_cust_offline.php
modules/customer/views/shop/cartbk.php
modules/customer/views/shop/carts.php
modules/customer/views/shop/category_all.php
modules/customer/views/shop/category_detail.php
modules/customer/views/shop/checkout.php
modules/customer/views/shop/product_all.php
modules/customer/views/shop/product_promo.php
modules/customer/views/shop/source_code_coba.php
modules/customer/views/shop/test.php
modules/customer/views/shop/testing.php
modules/customer/views/shop/view_all_products.php
modules/errors/controllers/Errors.php
modules/errors/views/error_page.php
views/auth/login.php
views/auth/notif_register.php
views/auth/register.php
```

## Catatan Audit

- Status ini adalah audit struktur dan checksum file, bukan validasi perilaku runtime.
- File `.DS_Store` muncul sebagai perbedaan teknis macOS; secara fungsional biasanya tidak relevan untuk aplikasi.
- Area risiko sinkronisasi terbesar berada pada `modules/admin` dan `modules/customer`, karena mayoritas file dengan path sama memiliki isi berbeda.
- Sebelum melakukan merge/copy, perlu review diff per file untuk menghindari overwrite logic produksi pada `application`.
