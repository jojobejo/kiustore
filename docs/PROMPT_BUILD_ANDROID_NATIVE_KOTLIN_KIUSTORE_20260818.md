# Prompt Build Android Native Kotlin KIU Store

Gunakan prompt ini untuk menjalankan build mobile native Android Kotlin berdasarkan hasil scanning project `C:\xampp\htdocs\kiustore`.

```text
Anda adalah Senior Android Engineer, Backend Integration Engineer, dan Product Engineer. Bangun aplikasi Android native Kotlin untuk KIU Store/Karisma Online berdasarkan backend CodeIgniter 3 yang sudah tersedia.

Konteks backend terverifikasi:
- Project backend: C:\xampp\htdocs\kiustore
- REST API prefix: /api/v1
- Health endpoint tervalidasi: GET https://localhost/kiustore/api/v1 menghasilkan HTTP 200 dengan success=true dan database_ready=true.
- Route API berada di application/config/routes.php.
- Controller API: application/modules/api/controllers/Mobile.php.
- Model API: application/modules/api/models/Mobile_api_model.php.
- Dokumentasi API existing: docs/MOBILE_API.md.
- Database aktif lokal: kiucoid_kiustore.
- API memakai Bearer token custom, bukan session web.
- Cart mobile memakai database table mobile_cart_items, bukan session web.
- Produk dapat dibuka guest. Cart/order/payment/profile/chat/delete-account wajib login.

Target build:
- Android native Kotlin.
- Minimum SDK 26.
- Target SDK versi stabil terbaru yang tersedia di environment build.
- UI menggunakan Jetpack Compose.
- Architecture: MVVM + Repository + UseCase ringan.
- Dependency injection: Hilt.
- Network: Retrofit + OkHttp + Kotlinx Serialization atau Moshi.
- Image loading: Coil.
- Local persistence: DataStore untuk setting/token, Room untuk cache katalog/order ringan jika diperlukan.
- Background: WorkManager untuk polling BRIVA/order status bila user ada di layar invoice.
- Secure storage: EncryptedSharedPreferences atau encrypted DataStore untuk Bearer token.
- Jangan pernah menyimpan credential RajaOngkir, BRIVA, private key, client secret, atau API key bank di aplikasi Android. Semua integrasi eksternal harus lewat backend /api/v1.

Base URL:
- Buat build config:
  - DEBUG_BASE_URL = https://10.0.2.2/kiustore/api/v1/ untuk emulator Android jika Apache host PC dapat diakses dari emulator.
  - LAN_BASE_URL = https://<IP-PC>/kiustore/api/v1/ untuk device fisik.
  - PROD_BASE_URL = isi nanti dengan domain production/staging bersertifikat valid.
- Karena local XAMPP HTTPS bisa memakai sertifikat tidak dipercaya, buat network_security_config hanya untuk debug. Jangan longgarkan SSL pada release.
- Semua endpoint harus mengirim Accept: application/json dan Content-Type: application/json kecuali upload multipart bukti transfer.

Response wrapper API:
Semua response mengikuti pola:
{
  "success": true|false,
  "message": "string",
  "data": object|array|null,
  "meta": object|null,
  "errors": object|null
}
Buat sealed class ApiResult<out T>: Success(data, meta, message), Error(httpCode, message, errors), NetworkError.

Endpoint yang wajib diimplementasikan:
1. Health
   GET /api/v1

2. Auth
   POST /auth/register
   Payload: name, email, password, phone_number, address, shop_name, shop_address, province_id, kota_id, subdistrict_id, alamat_kirim, device_name.
   POST /auth/login
   Payload: email, password, device_name.
   POST /auth/logout
   DELETE /account

3. Profile
   GET /profile
   PUT /profile
   Field update: name, phone_number, address, shop_name, shop_address, province_id, kota_id, subdistrict_id, alamat_kirim, nik, npwp.

4. Catalog
   GET /banners
   GET /categories
   GET /products?page=1&per_page=20&search=&category_id=&promo=
   GET /products/{id}
   Catatan: katalog harus bisa guest. Jika token tersedia, sertakan Bearer token agar harga mengikuti level customer.

5. Cart
   GET /cart
   POST /cart
   Payload: product_id, quantity, unit_type.
   PUT /cart/{cartItemId}
   Payload: quantity.
   DELETE /cart/{cartItemId}
   Edge case wajib: jika POST /cart mengembalikan HTTP 409, tampilkan dialog "Masih ada transaksi berjalan" dan arahkan ke Riwayat Order.

6. Shipping
   GET /shipping/provinces
   GET /shipping/cities?province_id=...
   GET /shipping/districts?city_id=...
   POST /shipping/quotes
   Payload: destination_id, courier.
   Quote berlaku 30 menit dan harus dipilih dari response options. Jika cart berubah, invalidasi quote lokal.

7. Payment Method
   GET /payment-methods
   GET /payments/banks
   Payment yang boleh tampil di mobile:
   - 2 = Virtual Account Karisma/BRIVA.
   - 3 = Transfer Bank.
   Jangan tampilkan kredit/payment_method=1 untuk versi pertama.

8. Orders
   GET /orders?page=1&per_page=20
   GET /orders/{id}
   POST /orders/checkout
   Payload: shipping_quote_id, shipping_service, note.
   POST /orders/{id}/payment-method
   Payload: payment_method.
   POST /orders/{id}/cancel
   POST /orders/{id}/complete
   Payload: rating, rating_description.

9. Transfer Bank
   POST /orders/{id}/payments/bank-transfer
   Dukung minimal multipart upload field picture jpg/jpeg/png maksimal 5MB.
   Dukung juga JSON base64 jika lebih mudah:
   source_bank, source_account_number, source_account_name, transfer_amount, transfer_to, picture_mime, picture_base64.
   Setelah sukses, order status menjadi 8.

10. BRIVA
   POST /orders/{id}/payments/briva
   GET /orders/{id}/payments/briva/status
   Hanya jalankan generate jika order_status=2 dan payment_method=2.
   Tampilkan VA code, nominal, expired_at/exp_date, tombol copy, countdown 15 menit, tombol cek status.
   Jika status paid, refresh detail order.
   Jika expired, tampilkan status batal/expired sesuai response.

11. Messages
   GET /messages?last_id=...
   POST /messages
   Payload: message.
   Buat layar chat sederhana dengan refresh/polling ringan.

Screen yang wajib dibuat:
1. Splash/Health Check
   - Cek token lokal dan health API.
   - Jika API down, tampilkan retry.

2. Home Guest
   - Banner.
   - Kategori.
   - Produk terbaru/promo.
   - Search.
   - Tombol login/register.

3. Auth
   - Login.
   - Register customer/toko.
   - Validasi email, password minimal 6 karakter, phone, address.

4. Product List
   - Pagination/infinite scroll.
   - Filter kategori.
   - Filter promo.
   - Search.

5. Product Detail
   - Gambar produk.
   - Nama, SKU, deskripsi, harga, promo.
   - Pilih unit_type 1/2 sesuai units dari API.
   - Quantity stepper.
   - Add to cart.

6. Cart
   - List item.
   - Update quantity.
   - Delete item.
   - Summary subtotal dan total weight.
   - CTA lanjut checkout.

7. Shipping & Checkout
   - Pilih provinsi, kota, kecamatan/district.
   - Ambil quote ongkir.
   - Pilih service ongkir dari response.
   - Tampilkan countdown quote.
   - Submit checkout.
   - Setelah checkout, arahkan ke detail order status 1 "Menunggu diproses".

8. Order History
   - List order paginated.
   - Badge status dengan label dari API: status_label.
   - Detail order.

9. Invoice / Payment
   - Jika order_status=1: tampilkan menunggu admin.
   - Jika order_status=2 dan payment_method null: tampilkan pilihan BRIVA atau Transfer Bank.
   - Jika payment_method=2: tampilkan generate/check BRIVA.
   - Jika payment_method=3: tampilkan form upload bukti transfer.
   - Jika order_status=8: tampilkan menunggu verifikasi admin.
   - Jika order_status=4: tampilkan tombol selesaikan dan rating.
   - Jika status selesai/batal: read-only.

10. Chat
   - List messages.
   - Send message.
   - Pull to refresh atau polling saat layar aktif.

11. Profile
   - View/update profile.
   - Logout.
   - Delete account dengan konfirmasi dua langkah.

Business rules wajib:
- Jangan izinkan add cart ketika API mengembalikan 409 active_order. Tampilkan order aktif dan tombol ke detail order.
- Jangan buka payment sebelum order_status=2.
- Jangan tampilkan kredit/payment_method=1.
- Jangan kalkulasi harga akhir dari client sebagai sumber kebenaran. Semua subtotal, ongkir, grand_total, stock, dan unit price harus mengikuti response backend.
- Jika quote expired atau cart berubah, user wajib membuat quote ulang.
- Untuk complete order, rating wajib 1 sampai 5 dan hanya boleh saat order_status=4.
- Delete account harus logout lokal setelah API success dan hapus token/cache.

Model data minimal:
- ApiEnvelope<T>
- AuthToken(token, token_type, expires_at, user)
- UserProfile
- Banner
- Category
- Product
- ProductUnit
- CartResponse, CartItem, CartSummary
- ShippingProvince, ShippingCity, ShippingDistrict, ShippingQuote, ShippingOption
- PaymentMethod
- PaymentBank
- OrderSummary
- OrderDetail
- OrderItem
- BrivaPayment
- BrivaStatus
- Message

Testing wajib:
- Unit test repository parsing success/error envelope.
- Unit test token interceptor.
- Unit test order status UI state mapping.
- Instrumentation smoke test: guest home -> product detail.
- MockWebServer tests:
  - login success stores token.
  - 401 clears token.
  - cart 409 shows active transaction state.
  - checkout 422 quote expired shows refresh quote action.
  - BRIVA paid response refreshes order.

Deliverable:
- Android Studio project siap dibuka.
- README setup berisi base URL debug/staging/prod, cara menjalankan emulator, dan catatan SSL debug.
- Jangan menambahkan credential real ke repo.
- Buat build variant debug dan release.
- Pastikan APK debug dapat mengakses backend lokal.
```

## Catatan Eksekusi Untuk Developer Android

Prioritas MVP:

1. Health, guest catalog, auth, product detail.
2. Cart dan checkout dengan ongkir.
3. Order history/detail.
4. Payment transfer dan BRIVA.
5. Chat, profile update, delete account.

Definition of Done:

- Semua endpoint utama dipanggil melalui repository layer.
- Token aman dan auto-attached.
- Tidak ada credential BRI/RajaOngkir di Android.
- Payment tidak terbuka sebelum status order valid.
- UI selalu memakai `status_label` dan `grand_total` dari API.

