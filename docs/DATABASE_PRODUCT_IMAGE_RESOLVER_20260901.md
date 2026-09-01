# Database Note - Product Image Resolver

Tanggal: 2026-09-01

## Status Perubahan Database

Tidak ada perubahan schema database.

Tidak ada migration baru.

Tidak ada update massal ke tabel `products`.

## Data Audit Lokal

Database aktif lokal:

- `kiucoid_kiustore`

Tabel terkait:

- `products`
- Field gambar: `products.picture_name`

Hasil audit lokal:

- Total produk: 951
- Produk dengan `picture_name` kosong atau `NULL`: 152
- Produk dengan `picture_name`: 799
- Cocok persis dengan file fisik: 16
- Cocok melalui normalisasi nama file: 123
- Tetap tidak memiliki pasangan file fisik valid: 660
- Total file gambar fisik di `assets/uploads/products/`: 159

## Kesimpulan

Masalah gambar produk bukan masalah struktur database. Penyebab utama adalah ketidaksesuaian isi `products.picture_name` terhadap nama file aktual di folder upload dan absennya `default.jpg`.

Perbaikan dilakukan di layer aplikasi melalui resolver gambar produk. Untuk penyelesaian data jangka panjang, perlu audit terpisah sebelum melakukan update massal `products.picture_name`, karena perubahan data gambar bersifat bisnis dan harus divalidasi per produk.

