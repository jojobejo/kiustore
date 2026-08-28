# Development Rules: Sinkronisasi application ke kiustore_apps

Tanggal dibuat: 2026-08-28  
Scope: `/Applications/XAMPP/xamppfiles/htdocs/kiustore/application` ke `/Applications/XAMPP/xamppfiles/htdocs/kiustore/kiustore_apps`

## Verified System Facts

| Fakta | Status |
|---|---|
| `application/` digunakan sebagai main project | Terverifikasi dari struktur workspace |
| `kiustore_apps/` sudah disamakan dengan `application/` | Terverifikasi checksum setelah sinkronisasi |
| Jumlah file setelah sinkronisasi | 304 file di masing-masing folder |
| File hanya ada di salah satu folder setelah sinkronisasi | 0 |
| File path sama tetapi isi berbeda setelah sinkronisasi | 0 |

## Prinsip Utama

`application/` adalah single source of truth. Semua perubahan source code, konfigurasi aplikasi, helper, library, model, controller, view, dan modul HMVC dilakukan terlebih dahulu di `application/`.

`kiustore_apps/` berfungsi sebagai mirror. Folder ini harus diperbarui dari `application/`, bukan menjadi tempat perubahan utama.

## Workflow Development Wajib

1. Lakukan perubahan hanya di `application/`.
2. Cari file yang berubah:
   ```bash
   git status --short application/
   ```
3. Preview sinkronisasi:
   ```bash
   ./scripts/sync_application_to_kiustore_apps.sh --dry-run
   ```
4. Jalankan sinkronisasi:
   ```bash
   ./scripts/sync_application_to_kiustore_apps.sh
   ```
5. Verifikasi mirror:
   ```bash
   ./scripts/sync_application_to_kiustore_apps.sh --verify
   ```
6. Review status repository:
   ```bash
   git status --short application/ kiustore_apps/
   ```

## Aturan Sinkronisasi

| Kondisi | Tindakan |
|---|---|
| File berubah di `application/` dan ada di `kiustore_apps/` | Update file yang sama di `kiustore_apps/` |
| File baru di `application/` | Tambahkan ke path yang sama di `kiustore_apps/` |
| File dihapus dari `application/` | Hapus dari path yang sama di `kiustore_apps/` |
| File hanya ada di `kiustore_apps/` | Hapus saat full mirror, kecuali ada pengecualian tertulis |
| File konfigurasi sensitif berubah | Review manual sebelum sync dan commit |

## Command Standar

Preview perubahan tanpa menulis file:
```bash
./scripts/sync_application_to_kiustore_apps.sh --dry-run
```

Sinkronisasi aktual:
```bash
./scripts/sync_application_to_kiustore_apps.sh
```

Validasi akhir:
```bash
./scripts/sync_application_to_kiustore_apps.sh --verify
```

## Audit Trail

Setiap perubahan sinkronisasi harus dapat ditelusuri dari:

- Output `git status --short application/ kiustore_apps/`
- Output dry-run script sinkronisasi
- Hasil verifikasi `--verify`
- Commit atau catatan pekerjaan yang menjelaskan alasan perubahan

## Risk Mitigation

| Akar Masalah | Dampak Bisnis | Strategi Mitigasi | Owner |
|---|---|---|---|
| Perubahan langsung di `kiustore_apps/` | Source distribusi berbeda dari main project | Terapkan rule single source of truth `application/` | Developer |
| File baru tidak ikut disalin | Modul distribusi tidak lengkap | Jalankan sync script setelah setiap perubahan | Developer |
| File lama tertinggal di `kiustore_apps/` | Behavior runtime tidak konsisten | Gunakan full mirror dengan `--delete` melalui script standar | Developer |
| Konfigurasi sensitif ikut berubah | Risiko koneksi/API produksi terganggu | Review manual pada file `config/*` sebelum commit | Developer Lead |
