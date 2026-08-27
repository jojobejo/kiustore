# Development Note: Guard Integrasi Zahir Digital Pada Server Online

Tanggal: 2026-08-27

## Latar Belakang

Production log mencatat error:

```text
Sumber data belum siap. Connection timed out after 30002 milliseconds
URL: https://10.10.10.12/zahirdigital/SALES/GLOBAL/stockready_api.php
```

Endpoint `10.10.10.12` adalah alamat jaringan lokal/internal. Server online tidak dapat menjangkau alamat tersebut kecuali server berada pada jaringan kantor yang sama, memakai VPN/site-to-site, atau memakai gateway/proxy yang memang dibuka secara aman.

## Perubahan Aplikasi

File yang diubah:

- `application/config/zahirdigital.php`
- `application/modules/admin/controllers/Zahir_stock.php`

Perubahan utama:

1. Menambahkan config `zahir_stockready_enabled`.
2. Default integrasi dibuat nonaktif pada checkout ini:

```php
$config['zahir_stockready_enabled'] = FALSE;
```

3. Timeout dikurangi dari 30 detik menjadi 5 detik untuk menekan efek lambat saat koneksi jaringan bermasalah.
4. Controller berhenti lebih awal jika integrasi dinonaktifkan, sehingga halaman admin tidak menggantung menunggu request ke IP lokal.
5. Error koneksi ke host private IP diberi pesan operasional yang lebih jelas: server online membutuhkan LAN/VPN/reverse proxy/sinkronisasi terjadwal.

## Cara Mengaktifkan Di Server Yang Memiliki Akses LAN/VPN

Aktifkan hanya jika server aplikasi benar-benar bisa mengakses host Zahir Digital:

```php
$config['zahir_stockready_enabled'] = TRUE;
$config['zahir_stockready_url'] = 'https://10.10.10.12/zahirdigital/SALES/GLOBAL/stockready_api.php';
$config['zahir_stockready_timeout'] = 5;
```

Validasi dari server aplikasi:

```bash
curl -k --connect-timeout 5 --max-time 5 -H "X-Karisma-Stock-Token: karisma-zahir-stock-20260827" "https://10.10.10.12/zahirdigital/SALES/GLOBAL/stockready_api.php"
```

Jika command tersebut gagal dari server online, aplikasi memang tidak boleh mencoba update stock langsung dari endpoint lokal.

## Opsi Penyelesaian Arsitektur

### Opsi 1: Disable Integrasi Di Server Online

Ini adalah mitigasi cepat dan paling aman jika server online tidak punya jalur ke jaringan kantor.

Dampak:

- Halaman admin tetap bisa dibuka.
- Approve/update stock dari Zahir dibatalkan otomatis.
- Tidak ada risiko stock Karisma Online berubah dari data sumber yang gagal diambil.

### Opsi 2: VPN Atau Site-to-Site Tunnel

Buat jalur private dari server online ke jaringan kantor agar host `10.10.10.12` reachable.

Kontrol wajib:

- Firewall hanya membuka akses dari IP server aplikasi.
- Endpoint tetap memakai token integrasi.
- Timeout tetap pendek.
- Logging koneksi dipantau.

### Opsi 3: Reverse Proxy Internal Dengan Domain Terbatas

Expose endpoint Zahir melalui gateway yang aman, misalnya domain internal khusus yang hanya menerima IP server aplikasi.

Kontrol wajib:

- TLS valid.
- IP allowlist.
- Token/Basic Auth.
- Rate limit.
- Tidak mengekspos seluruh aplikasi Zahir Digital, hanya endpoint `stockready_api.php`.

### Opsi 4: Scheduled Sync / Staging Table

Jalankan proses sinkronisasi dari jaringan kantor yang bisa membaca Zahir, lalu kirim hasil ke server online melalui endpoint import/API yang aman.

Ini opsi paling sehat untuk jangka panjang karena server online tidak bergantung langsung pada host LAN.

## Root Cause Dan Mitigasi

| Akar Masalah | Dampak Bisnis | Mitigasi | Owner |
|---|---|---|---|
| Server online mencoba hit IP LAN `10.10.10.12` | Halaman admin lambat, log timeout, approve stock gagal | Matikan integrasi pada server yang tidak punya akses LAN/VPN | Developer/Infra |
| Tidak ada jalur jaringan valid dari online ke Zahir internal | Data stock tidak dapat ditarik real-time | Pilih VPN, reverse proxy terbatas, atau scheduled sync | Infra/Management |
| Timeout terlalu panjang | User menunggu sampai 30 detik setiap request gagal | Turunkan timeout ke 5 detik | Developer |

## Status Verifikasi

- PHP lint wajib dijalankan setelah perubahan.
- Browser UAT admin tetap diperlukan untuk memastikan pesan operasional tampil sesuai harapan pada halaman `admin/zahir-stock`.
- Approve transaksi real tidak boleh diuji pada server online selama `zahir_stockready_enabled = FALSE`.
