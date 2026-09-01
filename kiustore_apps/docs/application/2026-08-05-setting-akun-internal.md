# Development Aplikasi - Setting Akun Internal

Tanggal: 2026-08-05
Route utama: `admin`

## Tujuan

Modul ini menambahkan kontrol admin untuk menandai akun sebagai akun internal. Akun internal dipakai untuk aktivitas internal, test, training, atau validasi proses tanpa ikut mempengaruhi ringkasan dan laporan bisnis utama.

## Perubahan Aplikasi

1. `application/modules/admin/controllers/Admin.php`
   - Route `admin` tetap memakai controller existing `admin/admin`.
   - Akses modul user dikunci untuk role `admin`.
   - API `admin/admin/api/users` menerima filter `role` dan `is_internal`.
   - API `admin/admin/api/toggle_internal` menandai atau melepas status akun internal.
   - Form tambah/edit user menyimpan flag `is_internal`.
   - Password pada edit user hanya diubah jika field password diisi.

2. `application/modules/admin/models/Admin_model.php`
   - List user sekarang dapat menampilkan semua role, termasuk `customer`, agar admin bisa review pengguna aplikasi secara penuh.
   - Menyediakan daftar role dinamis dari tabel `users`.
   - Menyimpan dan mengupdate `users.is_internal`.
   - Ada fallback jika kode sudah deploy tetapi migrasi belum dijalankan.

3. `application/modules/admin/views/admin/admin.php`
   - Menambahkan filter `Role`.
   - Menambahkan filter `Akun`: semua, internal, non internal.
   - Menambahkan kolom status akun internal.
   - Menambahkan tombol toggle akun internal.
   - Link akun `customer` diarahkan ke `admin/customers/view/{id}` supaya role customer tidak berubah lewat form admin internal.

4. `application/modules/admin/views/admin/add_new.php`
   - Menambahkan toggle `Akun Internal` pada form tambah user.

5. `application/modules/admin/views/admin/edit.php`
   - Menambahkan toggle `Akun Internal`.
   - Field password dibuat kosong opsional agar password lama tidak ter-hash ulang.

6. `application/modules/admin/views/customers/customers.php`
   - Menampilkan badge `Internal` / `Non Internal` pada daftar pelanggan.

## Query Bisnis Yang Disesuaikan

Query dashboard dan laporan utama berikut mengecualikan akun internal:

- `Customer_model::count_all_customers()`
- `Customer_model::latest_customers()`
- `Order_model::count_all_orders()`
- `Order_model::latest_orders()`
- `Order_model::order_overview()`
- `Order_model::income_overview()`
- `Payment_model::sum_success_payment()`
- `Payment_model::payment_overview()`
- `Report_model::count_all_orders()`
- `Report_model::tabel()`
- `Report_model::latest_orders()`
- `Report_model::order_overview()`
- `Report_model::income_overview()`
- `R_penjualan_model::count_all_orders()`
- `R_penjualan_model::laporan_penjualan()`
- `R_penjualan_model::get_all_orders()`
- `R_penjualan_model::latest_orders()`
- `R_penjualan_model::order_overview()`
- `R_penjualan_model::income_overview()`
- Badge helper `get_total_order()`, `get_unconfirmed_payment()`, dan `get_total_packing()`

## Catatan Teknis

- Data transaksi akun internal tidak dihapus.
- Flag internal menjadi kontrak query: modul baru yang menghitung KPI, omset, laporan penjualan, ranking, atau insentif harus menambahkan filter `COALESCE(users.is_internal, 0) = 0`.
- Daftar admin/user tetap dapat melihat akun internal untuk audit.
