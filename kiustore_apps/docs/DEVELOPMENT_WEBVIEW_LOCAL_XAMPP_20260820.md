# Development Aplikasi - Akses WebView Lokal XAMPP

Tanggal: 2026-08-20

## Ringkasan

Project KIU Store lokal di `C:\xampp\htdocs\kiustore` disiapkan agar bisa disajikan ke Android WebView melalui Android emulator.

URL Android emulator:

`http://10.0.2.2/kiustore/`

URL browser komputer:

`http://localhost/kiustore/`

## File yang Diubah

- `application/config/config.php`
- `.htaccess`
- Project Android: `app/src/main/java/com/karisma/karismaonline/MainActivity.java`

## Detail Implementasi

- `base_url` CodeIgniter mengikuti protokol request aktual.
- Host lokal `localhost`, `127.0.0.1`, dan `10.0.2.2` tidak dipaksa redirect ke HTTPS.
- Forced HTTPS tetap dipertahankan untuk host non-lokal.
- Android WebView diarahkan ke `http://10.0.2.2/kiustore/`.

## Tata Cara Penggunaan

1. Jalankan Apache dan database di XAMPP.
2. Buka `http://localhost/kiustore/` dari browser komputer untuk memastikan project aktif.
3. Jalankan APK debug di Android emulator.
4. WebView akan memuat KIU Store dari folder local development.

## Validasi

- `http://localhost/kiustore/` mengembalikan `HTTP 200`.
- `application/config/config.php` lolos PHP lint.
- APK debug Android berhasil dibuild menggunakan Java 11.

