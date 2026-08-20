<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
    .guide-page {
        background: #f5f8fc;
        min-height: calc(100vh - 120px);
    }

    .guide-header {
        background: #0b2a4a;
        border-radius: 16px;
        color: #ffffff;
        margin: 12px 0 16px;
        padding: 18px;
    }

    .guide-header span {
        color: #75e6d8;
        display: block;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .guide-header h1 {
        color: #ffffff;
        font-size: 23px;
        font-weight: 800;
        line-height: 1.2;
        margin: 0 0 8px;
    }

    .guide-header p {
        color: rgba(255, 255, 255, 0.82);
        font-size: 13px;
        line-height: 1.55;
        margin: 0;
    }

    .guide-list {
        display: grid;
        gap: 12px;
    }

    .guide-item {
        background: #ffffff;
        border: 1px solid #dceafa;
        border-radius: 14px;
        box-shadow: 0 8px 22px rgba(11, 42, 74, 0.06);
        padding: 14px;
    }

    .guide-item-head {
        align-items: center;
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .guide-icon {
        align-items: center;
        background: #eaf4ff;
        border-radius: 12px;
        color: #0c5fdb;
        display: flex;
        flex: 0 0 38px;
        font-size: 20px;
        height: 38px;
        justify-content: center;
        width: 38px;
    }

    .guide-item h2 {
        color: #0b2a4a;
        font-size: 15px;
        font-weight: 800;
        line-height: 1.3;
        margin: 0;
    }

    .guide-item p,
    .guide-item li {
        color: #536981;
        font-size: 13px;
        line-height: 1.55;
    }

    .guide-item p {
        margin: 0 0 8px;
    }

    .guide-item ol {
        margin: 0;
        padding-left: 18px;
    }

    .guide-note {
        background: #eefaf8;
        border-radius: 10px;
        color: #076b5f;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.45;
        margin-top: 10px;
        padding: 9px 10px;
    }

    .guide-action-bar {
        display: grid;
        gap: 10px;
        grid-template-columns: 1fr 1fr;
        margin: 18px 0 8px;
    }

    @media (max-width: 360px) {
        .guide-action-bar {
            grid-template-columns: 1fr;
        }
    }
</style>

<main class="main-wrap setting-page mb-xxl guide-page">
    <section class="guide-header">
        <span>Guide Book Customer</span>
        <h1>Panduan penggunaan modul customer.</h1>
        <p>Gunakan panduan ini sebagai referensi cepat untuk menjalankan fitur utama aplikasi KiuStore dari sisi pengguna kios/customer.</p>
    </section>

    <section class="guide-list">
        <article class="guide-item">
            <div class="guide-item-head">
                <div class="guide-icon"><i class="iconly-Home icli"></i></div>
                <h2>Beranda</h2>
            </div>
            <p>Beranda adalah halaman awal untuk melihat informasi toko dan akses cepat ke produk.</p>
            <ol>
                <li>Buka menu Beranda dari navigasi bawah.</li>
                <li>Lihat produk atau informasi yang tampil.</li>
                <li>Pilih produk atau lanjut ke Kategori jika ingin pencarian lebih spesifik.</li>
            </ol>
            <div class="guide-note">Tips: mulai dari Beranda saat ingin melihat informasi terbaru secara cepat.</div>
        </article>

        <article class="guide-item">
            <div class="guide-item-head">
                <div class="guide-icon"><i class="iconly-Category icli"></i></div>
                <h2>Kategori dan Produk</h2>
            </div>
            <p>Modul ini digunakan untuk menelusuri daftar produk berdasarkan kategori yang tersedia.</p>
            <ol>
                <li>Buka menu Kategori.</li>
                <li>Pilih kategori produk yang sesuai kebutuhan.</li>
                <li>Buka detail produk untuk melihat informasi sebelum menambahkan ke keranjang.</li>
            </ol>
            <div class="guide-note">Tips: gunakan kategori agar pencarian produk lebih fokus.</div>
        </article>

        <article class="guide-item">
            <div class="guide-item-head">
                <div class="guide-icon"><i class="iconly-Bag-2 icli"></i></div>
                <h2>Keranjang dan Checkout</h2>
            </div>
            <p>Keranjang dipakai untuk menampung produk yang akan dipesan sebelum customer menyelesaikan checkout.</p>
            <ol>
                <li>Tambahkan produk dari halaman detail produk.</li>
                <li>Buka Keranjang untuk memeriksa item pesanan.</li>
                <li>Lanjutkan checkout setelah data produk dan alamat sudah benar.</li>
            </ol>
            <div class="guide-note">Tips: periksa ulang jumlah item sebelum checkout untuk mengurangi kesalahan pesanan.</div>
        </article>

        <article class="guide-item">
            <div class="guide-item-head">
                <div class="guide-icon"><i class="iconly-Paper icli"></i></div>
                <h2>Riwayat Order dan Invoice</h2>
            </div>
            <p>Riwayat Order membantu customer melihat pesanan yang sudah pernah dibuat dan membuka detail transaksi.</p>
            <ol>
                <li>Buka menu Riwayat.</li>
                <li>Pilih pesanan yang ingin dilihat.</li>
                <li>Gunakan detail pesanan atau invoice sebagai rujukan transaksi.</li>
            </ol>
            <div class="guide-note">Tips: gunakan riwayat saat ingin mengecek ulang pesanan sebelumnya.</div>
        </article>

        <article class="guide-item">
            <div class="guide-item-head">
                <div class="guide-icon"><i class="iconly-Chat icli"></i></div>
                <h2>Chat Customer</h2>
            </div>
            <p>Chat digunakan untuk komunikasi bantuan, pertanyaan pesanan, atau kebutuhan koordinasi customer.</p>
            <ol>
                <li>Buka menu Chat.</li>
                <li>Baca pesan yang masuk.</li>
                <li>Kirim pesan dengan informasi yang jelas agar tim mudah menindaklanjuti.</li>
            </ol>
            <div class="guide-note">Tips: sertakan nomor pesanan saat bertanya tentang transaksi tertentu.</div>
        </article>

        <article class="guide-item">
            <div class="guide-item-head">
                <div class="guide-icon"><i class="iconly-Setting icli"></i></div>
                <h2>Profile, Alamat, dan Password</h2>
            </div>
            <p>Profile digunakan untuk mengelola identitas customer, alamat pengiriman, foto profil, dan password akun.</p>
            <ol>
                <li>Buka Profile melalui avatar atau menu Settings.</li>
                <li>Gunakan Edit Profile untuk memperbarui data dasar.</li>
                <li>Gunakan Verifikasi/Reset Data Alamat bila alamat perlu diperbarui.</li>
                <li>Gunakan Ganti Password untuk mengganti keamanan akun.</li>
            </ol>
            <div class="guide-note">Tips: pastikan alamat pengiriman sudah valid sebelum membuat pesanan.</div>
        </article>
    </section>

    <div class="guide-action-bar">
        <a class="btn btn-secondary w-100" href="<?= base_url('profile') ?>">Kembali</a>
        <a class="btn btn-info w-100" href="<?= base_url('profile/tutorial') ?>">Lihat Tutorial</a>
    </div>
</main>
