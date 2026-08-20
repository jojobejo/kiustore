<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
    .profile-help-page {
        background: linear-gradient(180deg, #eef7ff 0%, #f7fbff 42%, #ffffff 100%);
        min-height: calc(100vh - 120px);
    }

    .help-hero {
        background: linear-gradient(135deg, #0c5fdb 0%, #0baf9a 100%);
        border-radius: 16px;
        color: #ffffff;
        margin: 12px 0 18px;
        overflow: hidden;
        padding: 20px;
        position: relative;
    }

    .help-hero:after {
        background: rgba(255, 255, 255, 0.16);
        border-radius: 999px;
        content: "";
        height: 130px;
        position: absolute;
        right: -48px;
        top: -48px;
        width: 130px;
    }

    .help-hero h1 {
        color: #ffffff;
        font-size: 24px;
        font-weight: 800;
        line-height: 1.2;
        margin: 8px 0;
    }

    .help-hero p {
        color: rgba(255, 255, 255, 0.88);
        font-size: 14px;
        line-height: 1.55;
        margin: 0;
    }

    .help-chip {
        align-items: center;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-radius: 999px;
        color: #ffffff;
        display: inline-flex;
        font-size: 12px;
        font-weight: 700;
        gap: 6px;
        padding: 6px 10px;
    }

    .help-section-title {
        color: #12345b;
        font-size: 17px;
        font-weight: 800;
        margin: 18px 0 10px;
    }

    .tutorial-step {
        background: #ffffff;
        border: 1px solid #dceafa;
        border-left: 5px solid #0c5fdb;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(12, 95, 219, 0.08);
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
        padding: 14px;
    }

    .tutorial-step.active {
        border-color: #0baf9a;
        border-left-color: #0baf9a;
        box-shadow: 0 12px 28px rgba(11, 175, 154, 0.16);
    }

    .tutorial-control-bar {
        align-items: center;
        background: #ffffff;
        border: 1px solid #dceafa;
        border-radius: 14px;
        display: grid;
        gap: 10px;
        grid-template-columns: 1fr auto 1fr;
        margin-bottom: 12px;
        padding: 10px;
    }

    .tutorial-progress {
        color: #0b2a4a;
        font-size: 12px;
        font-weight: 800;
        text-align: center;
        white-space: nowrap;
    }

    .step-number {
        align-items: center;
        background: #0c5fdb;
        border-radius: 12px;
        color: #ffffff;
        display: flex;
        flex: 0 0 34px;
        font-size: 14px;
        font-weight: 800;
        height: 34px;
        justify-content: center;
        width: 34px;
    }

    .tutorial-step.active .step-number {
        background: #0baf9a;
    }

    .step-content h2 {
        color: #0b2a4a;
        font-size: 15px;
        font-weight: 800;
        line-height: 1.3;
        margin: 0 0 6px;
    }

    .step-content p {
        color: #52677f;
        font-size: 13px;
        line-height: 1.55;
        margin: 0 0 8px;
    }

    .step-highlight {
        background: #fff7e6;
        border-radius: 10px;
        color: #7a4b00;
        display: block;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.45;
        padding: 8px 10px;
    }

    .module-grid {
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .module-card {
        background: #ffffff;
        border: 1px solid #dceafa;
        border-radius: 12px;
        padding: 12px;
    }

    .module-card i {
        color: #0c5fdb;
        font-size: 20px;
        margin-bottom: 8px;
    }

    .module-card h3 {
        color: #0b2a4a;
        font-size: 13px;
        font-weight: 800;
        margin: 0 0 4px;
    }

    .module-card p {
        color: #66788f;
        font-size: 12px;
        line-height: 1.45;
        margin: 0;
    }

    .help-action-bar {
        display: grid;
        gap: 10px;
        grid-template-columns: 1fr 1fr;
        margin: 18px 0 8px;
    }

    @media (max-width: 360px) {
        .module-grid,
        .help-action-bar {
            grid-template-columns: 1fr;
        }

        .tutorial-control-bar {
            grid-template-columns: 1fr;
        }
    }
</style>

<main class="main-wrap setting-page mb-xxl profile-help-page">
    <section class="help-hero">
        <span class="help-chip"><i class="fas fa-graduation-cap"></i> Tutorial Customer</span>
        <h1>Belanja lebih cepat, rapi, dan mudah dipantau.</h1>
        <p>Ikuti alur singkat ini untuk memahami modul utama yang bisa digunakan oleh customer, mulai dari mencari produk sampai memantau riwayat pesanan.</p>
    </section>

    <h2 class="help-section-title">Langkah Tutorial</h2>

    <div class="tutorial-control-bar">
        <button class="btn btn-outline-primary btn-sm w-100" type="button" id="tutorialPrev">Sebelumnya</button>
        <span class="tutorial-progress" id="tutorialProgress">Step 1 dari 5</span>
        <button class="btn btn-primary btn-sm w-100" type="button" id="tutorialNext">Selanjutnya</button>
    </div>

    <section class="tutorial-step active" data-step="1">
        <div class="step-number">1</div>
        <div class="step-content">
            <h2>Mulai dari Beranda</h2>
            <p>Buka menu Beranda untuk melihat akses cepat, produk pilihan, dan informasi promo yang sedang ditampilkan aplikasi.</p>
            <span class="step-highlight">Highlight: gunakan Beranda sebagai pintu masuk sebelum mencari produk atau mengecek informasi terbaru.</span>
        </div>
    </section>

    <section class="tutorial-step" data-step="2">
        <div class="step-number">2</div>
        <div class="step-content">
            <h2>Cari Produk Lewat Kategori</h2>
            <p>Masuk ke Kategori untuk melihat daftar produk berdasarkan kelompoknya, lalu pilih produk untuk membaca detail sebelum membeli.</p>
            <span class="step-highlight">Highlight: modul Kategori membantu customer menemukan produk dengan lebih terarah.</span>
        </div>
    </section>

    <section class="tutorial-step" data-step="3">
        <div class="step-number">3</div>
        <div class="step-content">
            <h2>Masukkan Produk ke Keranjang</h2>
            <p>Setelah memilih produk, tambahkan ke Keranjang. Periksa kembali item, jumlah, dan kesiapan data sebelum lanjut checkout.</p>
            <span class="step-highlight">Highlight: Keranjang menjadi tempat kontrol akhir sebelum pesanan dikirim ke sistem.</span>
        </div>
    </section>

    <section class="tutorial-step" data-step="4">
        <div class="step-number">4</div>
        <div class="step-content">
            <h2>Checkout dan Cek Ongkir</h2>
            <p>Lengkapi proses checkout dengan memastikan alamat pengiriman sudah benar. Gunakan fitur ongkir saat tersedia untuk memperkirakan biaya pengiriman.</p>
            <span class="step-highlight">Highlight: alamat yang tervalidasi di Profile membantu proses checkout berjalan lebih lancar.</span>
        </div>
    </section>

    <section class="tutorial-step" data-step="5">
        <div class="step-number">5</div>
        <div class="step-content">
            <h2>Pantau Pesanan dan Chat</h2>
            <p>Gunakan Riwayat Order untuk melihat pesanan yang pernah dibuat. Jika butuh bantuan, buka Chat untuk berkomunikasi dengan tim terkait.</p>
            <span class="step-highlight">Highlight: Riwayat dan Chat membantu customer memantau status pesanan tanpa kehilangan konteks.</span>
        </div>
    </section>

    <h2 class="help-section-title">Modul yang Dapat Diakses</h2>
    <section class="module-grid">
        <article class="module-card">
            <i class="iconly-Home icli"></i>
            <h3>Beranda</h3>
            <p>Akses awal untuk melihat informasi dan produk yang ditampilkan.</p>
        </article>
        <article class="module-card">
            <i class="iconly-Category icli"></i>
            <h3>Kategori</h3>
            <p>Menelusuri produk berdasarkan kelompok barang.</p>
        </article>
        <article class="module-card">
            <i class="iconly-Bag-2 icli"></i>
            <h3>Keranjang</h3>
            <p>Mengecek item sebelum masuk ke proses checkout.</p>
        </article>
        <article class="module-card">
            <i class="iconly-Paper icli"></i>
            <h3>Riwayat Order</h3>
            <p>Melihat daftar pesanan dan detail transaksi.</p>
        </article>
        <article class="module-card">
            <i class="iconly-Chat icli"></i>
            <h3>Chat</h3>
            <p>Mengirim dan membaca pesan bantuan terkait kebutuhan customer.</p>
        </article>
        <article class="module-card">
            <i class="iconly-Setting icli"></i>
            <h3>Profile</h3>
            <p>Mengelola identitas, password, foto profil, dan alamat customer.</p>
        </article>
    </section>

    <div class="help-action-bar">
        <a class="btn btn-secondary w-100" href="<?= base_url('profile') ?>">Kembali</a>
        <a class="btn btn-primary w-100" href="<?= base_url('profile/guide-book-customer') ?>">Buka Guide Book</a>
    </div>
</main>

<script>
    (function() {
        var steps = document.querySelectorAll('.tutorial-step');
        var progress = document.getElementById('tutorialProgress');
        var prev = document.getElementById('tutorialPrev');
        var next = document.getElementById('tutorialNext');
        var activeIndex = 0;

        function setActiveStep(index, shouldScroll) {
            activeIndex = Math.max(0, Math.min(index, steps.length - 1));

            steps.forEach(function(step, itemIndex) {
                step.classList.toggle('active', itemIndex === activeIndex);
            });

            if (progress) {
                progress.textContent = 'Step ' + (activeIndex + 1) + ' dari ' + steps.length;
            }

            if (prev) {
                prev.disabled = activeIndex === 0;
            }

            if (next) {
                next.textContent = activeIndex === steps.length - 1 ? 'Selesai' : 'Selanjutnya';
            }

            if (shouldScroll) {
                steps[activeIndex].scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        if (prev && next && steps.length) {
            prev.addEventListener('click', function() {
                setActiveStep(activeIndex - 1, true);
            });

            next.addEventListener('click', function() {
                setActiveStep(activeIndex + 1, true);
            });

            setActiveStep(0, false);
        }
    })();
</script>
