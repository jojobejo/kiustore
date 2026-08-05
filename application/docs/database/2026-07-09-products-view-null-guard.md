# Catatan Database Produk

Tanggal: 2026-07-09

## Ringkasan
- Tidak ada perubahan skema database.
- Query `product_data()` diubah dari `INNER JOIN` ke `LEFT JOIN` agar produk tetap terbaca meskipun data kategori tidak tersedia.

## Dampak Database
- Tidak perlu migrasi tabel.
- Data produk lama tetap kompatibel.
- Jika relasi kategori hilang, detail produk masih bisa dibaca tanpa memicu notice PHP.

## Verifikasi
- Tidak ada `ALTER TABLE` atau perubahan struktur.
- Cukup pastikan record pada tabel `products` tetap valid untuk `id` yang dibuka di halaman admin.

