# Perbaikan View Produk Admin

Tanggal: 2026-07-09

## Ringkasan
- Menambahkan guard pada `admin/products/view/{id}` agar detail produk tidak memunculkan notice ketika data produk atau relasi kategori tidak lengkap.
- Controller sekarang menghentikan request dengan `show_404()` jika data produk tidak berhasil dimuat.
- Judul halaman memakai nilai fallback agar tidak ikut memicu notice.

## Dampak Perilaku
- Halaman detail produk tetap tampil normal untuk data valid.
- Jika data produk tidak ditemukan atau hasil query kosong, sistem berhenti lebih aman tanpa error notice PHP.

## Cara Pakai / Validasi
- Buka halaman `admin/products/view/{id}` pada produk yang valid.
- Coba produk yang relasi kategorinya rusak atau datanya tidak lengkap.
- Pastikan halaman tidak lagi menampilkan notice `Trying to get property ... of non-object`.

