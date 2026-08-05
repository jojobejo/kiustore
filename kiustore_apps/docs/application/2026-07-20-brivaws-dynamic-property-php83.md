# Brivaws Dynamic Property PHP 8.3

## Latar Belakang

Pada PHP 8.2+ termasuk PHP 8.3, pembuatan properti objek secara dinamis memunculkan deprecation warning. Error yang dilaporkan:

```text
Creation of dynamic property Brivaws::$CI is deprecated
```

Error tersebut mengarah ke `application/libraries/Brivaws.php` saat constructor mengisi `$this->CI = &get_instance();`.

## Status Kode Lokal

Checkout lokal saat ini sudah memiliki deklarasi properti eksplisit:

```php
private $CI;
```

Deklarasi ini berada di class `Brivaws` sebelum constructor, sehingga PHP 8.3 tidak lagi menganggap `$CI` sebagai dynamic property.

## Catatan Deployment

Jika error masih muncul di server online, kemungkinan file production belum sama dengan checkout lokal atau OPcache masih menyimpan versi lama.

Langkah yang perlu dilakukan di server:

1. Pastikan `application/libraries/Brivaws.php` di server memiliki `private $CI;` di dalam class `Brivaws`.
2. Upload/sinkronkan file `application/libraries/Brivaws.php` dari checkout lokal.
3. Restart PHP-FPM/Apache atau clear OPcache agar server memakai file terbaru.
4. Uji ulang halaman atau proses yang memanggil BRIVA.

## Verifikasi Lokal

Syntax check lokal:

```text
C:\xampp\php\php.exe -l application\libraries\Brivaws.php
```

Hasil: tidak ada syntax error.

Catatan: PHP CLI lokal adalah PHP 7.4.33, sehingga lint ini memvalidasi syntax, tetapi bukan bukti runtime PHP 8.3.
