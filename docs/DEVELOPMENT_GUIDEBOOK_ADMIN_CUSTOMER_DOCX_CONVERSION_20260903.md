# Development Note - Konversi Guidebook Admin Customer ke Word

Tanggal: 2026-09-03

## Ringkasan

Dokumen `docs/GUIDEBOOK_ADMIN_CUSTOMER_20260821.md` dikonversi menjadi file Word `docs/GUIDEBOOK_ADMIN_CUSTOMER_20260821.docx`.

## Hasil

- File Word berhasil dibuat.
- Tabel Markdown yang sebelumnya memakai karakter pemisah `|` sudah dikonversi menjadi tabel Word asli.
- Header tabel dipertahankan sesuai isi dokumen sumber.
- Dibuat script konversi ulang di `docs/convert_guidebook_to_docx.py` agar proses dapat dijalankan kembali jika dokumen Markdown berubah.

## Validasi

Validasi struktur dokumen Word menunjukkan 9 tabel berhasil terbaca sebagai tabel Word:

- Prinsip / Penjelasan.
- Jenis Pengguna / Akses Utama.
- Menu / Fungsi.
- Metode / Penjelasan.
- Status / Arti.
- Aksi / Kapan Digunakan / Dampak.
- Tahap / Kontrol.
- Aktivitas / Frekuensi / Penanggung Jawab.
- Kendala / Pemeriksaan Awal / Tindakan.

## Catatan QA

Render visual otomatis belum dapat dijalankan karena tool render tidak menemukan LibreOffice atau `soffice` pada environment saat ini. Validasi yang berhasil dilakukan adalah validasi struktur file Word menggunakan pembacaan dokumen `.docx`.
