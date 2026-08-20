# Development - Tombol Tanya Admin Stock Produk

Tanggal: 2026-08-20

## Ringkasan

Menambahkan tombol `tanya admin untuk ketersedian stock` pada halaman detail produk customer.

## Verified System Facts

- Route detail produk tetap memakai `product/(:num)/(:any)` menuju `customer/product/product/$1/$2`.
- View aktif detail produk berada di `application/modules/customer/views/shop/product_detail.php`.
- Route chat customer tetap memakai `message` menuju `customer/message`.
- Tombol baru mengarahkan customer ke halaman chat dengan parameter `prefill_message`.
- Format pesan otomatis: `Halo Admin ! Info stock untuk [nama produk]`.
- Jika customer belum login, tombol mengarahkan ke login dengan `redir_to` menuju halaman chat beserta pesan otomatis.

## Perubahan Aplikasi

- `application/modules/customer/views/shop/product_detail.php`
  - Membentuk pesan otomatis berdasarkan nama produk.
  - Menambahkan CTA chat stock pada area aksi produk.
  - Menjaga tombol `Beli` tetap berjalan untuk customer yang sudah login.
- `application/modules/customer/views/message.php`
  - Membaca parameter `prefill_message` dari URL dan mengisikannya ke kolom ketik pesan.
  - Menjaga kompatibilitas parameter lama `auto_text=1` untuk pesan ongkir dari halaman lain.

## Catatan UAT

1. Buka halaman detail produk.
2. Klik `tanya admin untuk ketersedian stock`.
3. Pastikan halaman chat terbuka.
4. Pastikan kolom ketik pesan berisi `Halo Admin ! Info stock untuk [nama produk aktual]`.
5. Klik kirim dan pastikan pesan masuk ke percakapan customer-admin.
