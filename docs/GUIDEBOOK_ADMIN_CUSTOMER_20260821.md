# Guidebook Admin dan Customer KIU Store

Tanggal dokumen: 2026-08-21

## Tujuan

Guidebook ini menjadi panduan operasional untuk admin dan customer dari awal akses sampai penyelesaian order. Isi dokumen disusun dari fitur yang terverifikasi di route, controller, view, helper, dan model repositori lokal.

## Audit Trail

| Area | Sumber Sistem |
|---|---|
| Route aplikasi | `application/config/routes.php` |
| Login/Register/Logout | `application/controllers/auth/Login.php`, `Register.php`, `Logout.php` |
| Role dan session | `application/helpers/session_helper.php` |
| Status order/payment | `application/helpers/global_helper.php` |
| Customer web | `application/modules/customer/controllers` |
| Admin web | `application/modules/admin/controllers` |
| API mobile | `application/modules/api/controllers/Mobile.php` |

## Akun dan Hak Akses

| Role | Akses Utama |
|---|---|
| `customer` | Home, katalog produk, cart, checkout, pembayaran, order history, invoice, chat, profil, privacy policy |
| `admin` | Seluruh menu admin yang dikawal helper admin |
| `adminonline` | Produk, kategori, banner, order, promo, kupon, customer, chat, settings tertentu |
| `keuangan` | Pembayaran, piutang, customer, laporan, produk sesuai menu |
| `salesman` | Produk, order, promo, kupon, customer, chat |
| `distribusi` | Pesanan distribusi dan pengiriman |
| `kadep` | Rating sales dan nilai rata-rata |

## Panduan Customer

### 1. Registrasi Akun

1. Buka `/register`.
2. Isi password, nama lengkap, alamat, nomor HP, dan email.
3. Submit formulir.
4. Sistem membuat akun `users` role `customer` dan data profil di `customers`.
5. Setelah berhasil, customer diarahkan ke halaman notifikasi registrasi.

Catatan validasi:

- Password wajib minimal 4 karakter.
- Nomor HP wajib unik di tabel `customers`.
- Email wajib unik di tabel `users`.

### 2. Login dan Logout

1. Buka `/login`.
2. Isi email dan password.
3. Klik `Masuk`.
4. Customer aktif diarahkan ke `/home`.
5. Untuk keluar, buka menu sidebar lalu pilih `Logout` atau akses `/logout`.

Jika customer mencoba membuka `/cart`, `/profile`, atau halaman customer private tanpa login, sistem akan mengarahkan ke login dengan parameter redirect.

### 3. Navigasi Home dan Katalog

Menu customer utama:

| Menu | Fungsi |
|---|---|
| Home | Melihat banner, kategori, promo, produk, invoice, dan tagihan |
| Kategori | Melihat daftar kategori produk |
| Cart | Melihat dan mengelola keranjang |
| Histori Order | Melihat daftar dan detail pesanan |
| Chat | Mengirim dan membaca pesan dengan admin |
| Settings/Profile | Mengelola profil, alamat, akun, dan guide book |
| Privacy & Policy | Membaca kebijakan privasi |

Alur katalog:

1. Buka `/home`, `/category`, `/all_products`, atau `/promo`.
2. Cari produk melalui fitur search.
3. Buka detail produk lewat `/product/{id}/{sku}`.
4. Pastikan harga, stok, satuan, berat, dan deskripsi sesuai kebutuhan.

### 4. Keranjang dan Ongkir

1. Tambahkan produk ke cart.
2. Buka `/cart`.
3. Periksa item, qty, satuan, subtotal, dan total.
4. Untuk customer yang wajib ongkir, pilih asal, tujuan, dan kurir.
5. Sistem menghitung ongkir memakai modul RajaOngkir/Komerce.
6. Pilih layanan ongkir, lalu simpan.

Kondisi yang perlu diperhatikan:

- Cart tidak dapat diproses bila data produk tidak lengkap.
- Checkout akan ditolak bila ada transaksi BRIVA berjalan yang belum selesai atau belum dibatalkan.
- Untuk customer level tertentu, ongkir dapat bernilai 0 sesuai logic sistem.

### 5. Checkout

1. Dari cart, masukkan kode kupon jika ada.
2. Perbarui quantity.
3. Lanjut ke checkout.
4. Sistem menghitung subtotal, discount, ongkir, dan total.
5. Periksa data pengiriman: nama, nomor HP, alamat, toko, alamat toko, dan catatan.
6. Pilih metode pembayaran.
7. Submit order.

Metode pembayaran yang terverifikasi di helper:

| Kode | Metode |
|---|---|
| 1 | Kredit |
| 2 | Virtual Account |
| 3 | Transfer Bank |

Catatan kredit:

- Order kredit dapat masuk status `Dalam Pengajuan Kredit`.
- Jika total kredit melebihi limit transaksi customer, sistem menolak checkout dan mengarahkan kembali ke cart.

### 6. Pembayaran

#### Virtual Account

1. Setelah order VA dibuat, ikuti instruksi pembayaran VA.
2. Cek status pembayaran melalui detail order bila tersedia.
3. Status pembayaran akan berubah sesuai respons BRIVA atau mode local development.

#### Transfer Bank

1. Buka halaman konfirmasi pembayaran dari order atau `/customer/payments/confirm?order={id}`.
2. Isi bank asal, nama pengirim, nomor rekening, nominal transfer, dan bank tujuan.
3. Upload bukti pembayaran jika tersedia.
4. Submit.
5. Sistem membuat data `payments` dengan status menunggu konfirmasi.
6. Admin/keuangan akan memverifikasi pembayaran.

### 7. Histori Order dan Status

1. Buka `/order_history`.
2. Pilih order untuk melihat detail.
3. Pantau status order.

Status order utama:

| Status | Arti Operasional |
|---|---|
| Proses oleh Sales | Order sedang diproses sales |
| Menunggu Pembayaran | Customer perlu melakukan pembayaran |
| Menunggu Konfirmasi Pembayaran | Bukti/payment menunggu verifikasi |
| Payment Verify | Pembayaran sudah tervalidasi awal |
| Pengemasan | Order sedang dikemas |
| Pengiriman | Order sedang dikirim |
| Barang Diterima | Customer sudah menerima barang |
| Selesai | Order selesai |
| Dibatalkan | Order dibatalkan |
| Dalam Pengajuan Kredit | Order kredit menunggu approval |

### 8. Profil dan Alamat

1. Buka `/profile`.
2. Untuk ubah profil, buka edit profile.
3. Update nama, nomor HP, alamat, nama toko, alamat toko, dan foto profil bila perlu.
4. Untuk alamat kirim, gunakan menu edit alamat.
5. Pilih provinsi, kabupaten/kota, dan kecamatan/subdistrict.
6. Simpan perubahan.

Catatan:

- Foto profil hanya menerima `jpg` dan `png` dengan batas upload sesuai controller.
- Password baru minimal 4 karakter.
- Email harus format valid dan panjang sesuai validasi controller.

### 9. Chat dan Contact

Chat:

1. Buka `/message`.
2. Ketik pesan.
3. Kirim ke admin.
4. Pantau balasan di halaman yang sama.

Contact:

1. Buka `/contact`.
2. Isi nama, email, subject, dan pesan.
3. Submit.
4. Sistem mencatat pesan ke `contacts`.

### 10. Review dan Rating

1. Setelah order selesai atau diterima, buka halaman review bila tersedia.
2. Isi rating dan deskripsi.
3. Submit.
4. Data rating digunakan pada modul review/rating admin.

## Panduan Admin

### 1. Login Admin

1. Buka `/login`.
2. Masukkan akun role admin yang aktif.
3. Sistem mengarahkan role admin, adminonline, keuangan, salesman, distribusi, dan kadep ke `/dashboard_admin`.
4. Menu sidebar tampil sesuai role.

### 2. Dashboard

1. Buka `/dashboard_admin`.
2. Gunakan dashboard sebagai pintu masuk monitoring.
3. Perhatikan badge jumlah order, pembayaran belum terkonfirmasi, dan pesan belum dibaca jika tampil.

### 3. Produk, Kategori, Kupon, Promo, dan Banner

#### Produk

1. Buka `/admin/products`.
2. Lihat daftar semua produk, produk dengan harga, dan produk belum ada harga.
3. Tambah produk melalui menu tambah produk.
4. Isi nama, kategori, harga level, stok, satuan, berat, tipe produk, deskripsi, dan gambar.
5. Simpan.
6. Untuk edit, buka detail/edit produk, ubah data, lalu simpan.
7. Untuk hapus gambar atau produk, gunakan aksi yang tersedia di halaman produk.

Validasi penting:

- Nama produk wajib 4 sampai 255 karakter.
- Harga utama wajib.
- Stok wajib numerik.
- Berat produk wajib.
- Upload gambar produk menerima `jpg`, `png`, `jpeg` dan batas ukuran sesuai controller.

#### Kategori

1. Buka `/admin/categories`.
2. Tambah kategori baru.
3. Edit nama kategori bila perlu.
4. Hapus kategori yang tidak digunakan setelah memastikan tidak mengganggu produk aktif.

#### Kupon

1. Buka `/admin/products/coupons`.
2. Tambahkan nama, kode, nilai credit, tanggal mulai, tanggal expired.
3. Aktifkan/nonaktifkan sesuai periode promosi.

#### Promo

1. Buka `/admin/products/promo`.
2. Pilih produk, nilai promo, tanggal mulai, tanggal expired.
3. Simpan dan pantau status aktif/expired.

#### Banner Produk

1. Buka menu Banner Produk.
2. Tambahkan banner sesuai kebutuhan promosi.
3. Edit atau hapus banner yang tidak lagi aktif.

### 4. Customer

1. Buka `/admin/customers`.
2. Lihat daftar customer.
3. Tambah customer baru bila pendaftaran dilakukan oleh internal.
4. Isi nama, NIK, NPWP, email, password, kota, alamat, toko, level, limit kredit, dan salesman.
5. Buka detail customer untuk melihat profil, order, payment, VA, dan riwayat terkait.
6. Gunakan aksi activate/deactivate untuk mengatur status akun.
7. Gunakan reset password bila customer membutuhkan reset awal.
8. Gunakan generate/update VA customer bila diperlukan.

Catatan:

- Reset password admin untuk customer memakai default `1234` pada controller.
- Setelah reset password, customer harus diminta mengganti password dari profil.

### 5. Order Management

1. Buka `/admin/orders` untuk order umum.
2. Role distribusi/admin membuka `/admin/orders/distribusi` untuk proses pengemasan/pengiriman.
3. Role kadep/admin membuka `/admin/orders/kadep` untuk rating sales.
4. Buka detail order untuk melihat item, delivery data, bank, resi, pembayaran, dan flash status.

Aksi order umum:

| Aksi | Kapan Digunakan | Dampak Sistem |
|---|---|---|
| Update status | Perubahan tahap order | `orders.order_status` berubah |
| Verify order | Validasi invoice, ekspedisi, TTB, shipping cost, insurance | Order diverifikasi sesuai model |
| Update harga | Hanya status 1, 9, atau 11 | Harga item dan total order berubah |
| Update resi | Setelah resi tersedia | Resi tersimpan ke order/faktur |
| Reset resi | Resi salah atau batal | Resi direset |
| Update pengemasan | Distribusi memproses batch | Order menjadi status pengiriman |

Kontrol penting:

- Update harga ditolak bila order bukan status 1, 9, atau 11.
- Setiap perubahan status harus sesuai kondisi fisik order dan bukti operasional.
- Untuk BRIVA, jangan ubah manual sebelum memastikan status pembayaran.

### 6. Pembayaran dan Piutang

#### Pembayaran

1. Buka `/admin/payments`.
2. Filter semua, confirmed, dan not confirmed.
3. Buka detail payment.
4. Cocokkan nominal, order, bank asal, bank tujuan, dan bukti bayar.
5. Pilih konfirmasi berhasil bila valid.
6. Pilih gagal/kurang bayar bila pembayaran tidak ditemukan atau nominal tidak sesuai.

Dampak:

- Konfirmasi berhasil mengubah payment status menjadi berhasil dikonfirmasi.
- Gagal/kurang bayar mengubah payment status menjadi gagal dan order mengikuti model payment.

#### Piutang

1. Buka `/admin/piutang`.
2. Lihat daftar piutang.
3. Verifikasi sesuai bukti pembayaran/settlement.
4. Pastikan status order dan payment konsisten setelah verifikasi.

### 7. BRIVA

1. Admin dapat membuka `/admin/briva-switch` untuk mengatur mode BRIVA bila role mengizinkan.
2. Pada detail order, gunakan inquiry atau cek VA status untuk validasi pembayaran VA.
3. Dalam mode local development, respons dapat disimulasikan oleh controller.
4. Dalam mode live, controller menggunakan library BRIVA sesuai konfigurasi.

Kontrol risiko:

- Jangan memproses order dibatalkan sebagai paid.
- Pastikan `order_number`, `customerNo/cusno`, dan nominal sesuai sebelum membuat payment.

### 8. Pengiriman

1. Buka `/admin/pengiriman`.
2. Lihat data pengiriman.
3. Buka detail berdasarkan TTB bila tersedia.
4. Update informasi pengiriman sesuai resi/truk/tanggal.

### 9. Salesman, Rating, dan Review

Salesman:

1. Buka menu salesman.
2. Tambah, edit, atau hapus salesman sesuai data internal.
3. Pastikan customer memiliki salesman yang valid bila dipakai dalam proses order.

Rating:

1. Buka menu Rating Sales atau Nilai Rata-rata.
2. Pantau rating order selesai.
3. Gunakan data ini sebagai bahan evaluasi layanan, bukan klaim KPI sebelum baseline produksi ditetapkan.

Review:

1. Buka menu review pelanggan.
2. Lihat review.
3. Hapus review hanya bila melanggar kebijakan internal.

### 10. Chat dan Contact

Chat pelanggan:

1. Buka `/admin/messages`.
2. Pilih customer.
3. Baca pesan masuk.
4. Balas pesan.
5. Pantau unread counter.

Contact:

1. Buka `/admin/contacts`.
2. Lihat pesan contact form.
3. Reply jika modul reply dipakai.
4. Pastikan status contact berubah dibaca/dibalas.

### 11. Settings Toko dan Profil Admin

Settings toko:

1. Buka `/admin/settings`.
2. Update nama toko, nomor telepon, email, tagline, deskripsi, alamat, minimum free shipping, dan shipping cost.
3. Update bank pembayaran dalam format form bank yang tersedia.
4. Simpan.

Profil admin:

1. Buka `/admin/settings/profile`.
2. Update nama, email, password, dan foto profil.
3. Simpan.

Catatan upload:

- Foto admin menerima `jpg`, `png`, `jpeg` dan batas ukuran sesuai controller.

## SOP Hulu ke Hilir Order

### Alur Customer ke Admin

1. Customer register atau dibuatkan admin.
2. Customer login.
3. Customer memilih produk.
4. Customer memasukkan produk ke cart.
5. Customer memilih ongkir bila diperlukan.
6. Customer checkout dengan kupon jika ada.
7. Customer memilih pembayaran kredit, VA, atau transfer bank.
8. Sistem membuat order dan item order.
9. Customer membayar atau menunggu proses kredit.
10. Admin/sales/keuangan memverifikasi order dan payment.
11. Distribusi memproses pengemasan dan pengiriman.
12. Customer menerima barang.
13. Order diselesaikan.
14. Customer memberi review/rating bila tersedia.
15. Laporan dan rating digunakan untuk monitoring internal.

### Kontrol Mutu Operasional

| Tahap | Kontrol |
|---|---|
| Registrasi | Email dan phone unik, status akun aktif |
| Produk | Harga, stok, berat, satuan, gambar valid |
| Cart | Item sesuai produk aktif dan stok |
| Ongkir | Origin, destination, courier, weight valid |
| Checkout | Total, discount, ongkir, delivery data valid |
| Payment | Nominal, order id, bank, bukti bayar valid |
| BRIVA | Status VA, order number, customer number, nominal valid |
| Order Status | Perubahan status sesuai bukti fisik/order |
| Pengiriman | Resi/TTB/tanggal/no truk terisi bila diperlukan |
| Closing | Order selesai setelah barang diterima |

## Checklist Harian Admin

| Aktivitas | Frekuensi | Owner |
|---|---|---|
| Cek order baru | Harian dan saat jam operasional | Sales/Admin Online |
| Cek payment belum konfirmasi | Harian | Keuangan |
| Cek order BRIVA berjalan | Harian | Keuangan/Admin |
| Cek order pengemasan/pengiriman | Harian | Distribusi |
| Cek chat pelanggan | Harian | Admin Online/Sales |
| Cek produk stok/harga kosong | Harian/Mingguan | Admin Produk |
| Cek laporan dan rating | Mingguan/Bulanan | Manajemen/Kadep |

## Troubleshooting Singkat

| Masalah | Pemeriksaan Awal | Tindakan |
|---|---|---|
| Customer tidak bisa login | Status user, email, password | Aktifkan user atau reset password |
| Customer tidak bisa checkout | Cart, transaksi BRIVA aktif, data produk, limit kredit | Selesaikan/batalkan transaksi aktif, koreksi produk, cek limit |
| Ongkir tidak tampil | Origin/destination/courier/weight, API key | Validasi alamat dan credential |
| Payment transfer belum masuk admin | Row `payments`, order_id, payment_status | Minta customer submit ulang bila data tidak ada |
| VA tidak berubah paid | `briva_api`, mode local/live, response BRI | Jalankan inquiry/cek status dan validasi credential |
| Order tidak bisa update harga | Status order | Pastikan status 1, 9, atau 11 |
| Menu admin tidak tampil | Role user | Sesuaikan role dengan kebutuhan akses |

## Batasan Dokumen

- Dokumen ini tidak menyatakan persentase kesiapan modul.
- Dokumen ini tidak mengklaim KPI bisnis lapangan.
- Hasil akhir operasional tetap harus dibuktikan melalui test evidence, database snapshot, screenshot, atau response JSON.
