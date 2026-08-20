# Development - Product Detail Action Buttons Customer

Tanggal: 2026-08-20

## Ringkasan

Perubahan dilakukan pada halaman detail produk customer agar tombol `Beli` dan `Chat Dengan Admin` tampil satu baris, rapi, dan lebih kuat sebagai CTA utama.

## File Aplikasi

- `application/modules/customer/views/shop/product_detail.php`

## Detail Implementasi

- Route customer detail produk tetap memakai `product/(:num)/(:any)` menuju `customer/Product::product()`.
- View aktif tetap `application/modules/customer/views/shop/product_detail.php`.
- Link chat tetap memakai route existing `message` dengan `prefill_message`.
- Customer yang belum login tetap diarahkan ke `login` dengan `redir_to` menuju chat.
- Customer yang sudah login melihat:
  - baris input jumlah dan satuan,
  - baris tombol `Beli` dan `Chat Dengan Admin`.
- Tombol memakai icon Feather `shopping-cart` dan `message-circle`.
- CSS dibatasi dengan class khusus `product-purchase-panel`, `product-option-row`, `product-action-row`, dan `product-action-btn` agar tidak mengubah tombol lain.

## Validasi

- Validasi syntax PHP dilakukan dengan `php -l` pada file view yang diubah.
- Tidak ada perubahan controller, model, route, atau kontrak API.

## Catatan Penggunaan

1. Buka halaman detail produk customer melalui URL `product/{id}/{sku}`.
2. Jika belum login, tombol `Chat Dengan Admin` mengarahkan ke login lalu kembali ke chat.
3. Jika sudah login, pilih qty dan satuan, lalu gunakan `Beli` atau `Chat Dengan Admin` pada baris aksi yang sama.
