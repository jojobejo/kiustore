# BRIVA Production Config

## Latar Belakang

Endpoint pembayaran customer `customer/orders/update_briva_status` memakai library `Brivaws` melalui `$this->load->library('Brivaws')`.

Di CodeIgniter 3, library config otomatis dicari dari file `application/config/brivaws.php`. Sebelumnya hanya tersedia `application/config/briva.php`, sehingga `Brivaws` jatuh ke nilai default hardcode di constructor.

## Perubahan Aplikasi

1. URL BRIVA legacy config `application/config/briva.php` diarahkan ke production:

```text
https://partner.api.bri.co.id
```

2. Ditambahkan `application/config/brivaws.php` sebagai config eksplisit untuk library `Brivaws`.

3. Key config dibuat sesuai nama yang dibaca oleh constructor `Brivaws`:

```text
url
partnerServiceId
client_id
secret_key
expartnerid
private_key_path
```

## Verifikasi

Verifikasi minimal dilakukan dengan request token SNAP BRI ke:

```text
https://partner.api.bri.co.id/snap/v1.0/access-token/b2b
```

Test ini hanya memvalidasi koneksi/auth dasar dan tidak melakukan create/update/delete VA.

## Hasil Test 2026-07-27

Endpoint production dapat dijangkau dari mesin lokal, tetapi token belum berhasil dibuat.

Response BRI:

```text
HTTP 401
responseCode: 4017300
responseMessage: Unauthorized. stringToSign
```

Test tambahan dengan beberapa format timestamp (`UTC .000Z`, `UTC Z`, dan ISO-8601 Asia/Jakarta) menghasilkan response yang sama.

Pemeriksaan key lokal:

```text
private_key_loads: true
public_key_loads: true
signature_created: true
public_private_pair_valid: false
```

Artinya private key lokal dapat dipakai untuk membuat signature, tetapi tidak cocok dengan file `key/public.pem` lokal. Jika public key yang terdaftar di portal BRI juga tidak cocok dengan private key production yang dipakai aplikasi, request token akan ditolak sebagai `Unauthorized. stringToSign`.
