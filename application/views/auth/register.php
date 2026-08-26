<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Registrasi akun KIU Store untuk belanja produk pertanian resmi Karisma Online.">
  <meta name="theme-color" content="#117a43">
  <title>KIU Store | Register</title>

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900&amp;display=swap">
  <link rel="icon" href="<?php echo base_url('assets/images/favicon.png') ?>">
  <link rel="apple-touch-icon" href="<?php echo get_login_theme('img/icons/icon-96x96.png') ?>">
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_login_theme('img/icons/icon-152x152.png') ?>">
  <link rel="apple-touch-icon" sizes="167x167" href="<?php echo get_login_theme('img/icons/icon-167x167.png') ?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_login_theme('img/icons/icon-180x180.png') ?>">
  <link rel="stylesheet" href="<?php echo get_login_theme('css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?php echo get_login_theme('css/animate.css') ?>">
  <link rel="stylesheet" href="<?php echo get_login_theme('css/owl.carousel.min.css') ?>">
  <link rel="stylesheet" href="<?php echo get_login_theme('css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?php echo get_login_theme('css/default/lineicons.min.css') ?>">
  <link rel="stylesheet" href="<?php echo get_login_theme('style.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/auth-kiu.css?v=20260825-2') ?>">
  <link rel="manifest" href="<?php echo get_login_theme('manifest.json') ?>">
</head>

<body class="auth-kiu-page">
  <div class="preloader" id="preloader">
    <div class="spinner-grow text-secondary" role="status">
      <div class="sr-only">Loading...</div>
    </div>
  </div>

  <div class="login-wrapper d-flex align-items-center justify-content-center">
    <div class="background-shape"></div>

    <main class="auth-shell" aria-label="Registrasi KIU Store">
      <section class="auth-brand-panel">
        <div>
          <div class="auth-brand-kicker">Karisma Online</div>
          <h1 class="auth-brand-title">Daftar sekali, belanja kebutuhan pertanian lebih ringkas.</h1>
          <p class="auth-brand-text">
            Buat akun KIU Store untuk mengakses katalog, keranjang, checkout, dan riwayat pesanan produk pertanian Karisma.
          </p>
          <div class="auth-brand-tags">
            <span>Pupuk</span>
            <span>Benih</span>
            <span>Pestisida</span>
            <span>Lainnya</span>
          </div>
          <div class="auth-mini-market" aria-hidden="true">
            <div class="auth-mini-card">
              <strong>Isi Profil</strong>
              <span>Data kontak dipakai untuk kebutuhan transaksi.</span>
            </div>
            <div class="auth-mini-card">
              <strong>Mulai Belanja</strong>
              <span>Pilih produk dan lanjutkan ke keranjang.</span>
            </div>
            <div class="auth-mini-card">
              <strong>Pantau Pesanan</strong>
              <span>Cek histori pembelian dari akun pelanggan.</span>
            </div>
          </div>
        </div>

        <p class="auth-footnote">Tradisi, reputasi, dan inovasi dalam satu platform belanja.</p>
      </section>

      <section class="auth-card">
        <div class="auth-logo-lockup">
          <div class="auth-logo-orbit">
            <img class="auth-logo" src="<?php echo base_url('assets/images/logo.png') ?>" alt="Karisma Online Shop">
          </div>
          <div class="auth-status-chip">New Account</div>
        </div>

        <h1>Registrasi</h1>
        <p class="auth-card-subtitle">Lengkapi data berikut untuk membuat akun pelanggan KIU Store.</p>

        <div class="register-form auth-form">
          <?php echo form_open('auth/register/verify'); ?>
            <div class="form-group text-start">
              <span>Nama</span>
              <label for="nama"><i class="lni lni-user"></i></label>
              <input class="form-control" id="nama" name="nama" type="text" placeholder="Nama lengkap" value="<?php echo set_value('nama'); ?>">
              <?php echo form_error('nama'); ?>
            </div>

            <div class="form-group text-start">
              <span>Alamat</span>
              <label for="alamat"><i class="lni lni-map"></i></label>
              <input class="form-control" id="alamat" name="alamat" type="text" placeholder="Alamat pengiriman" value="<?php echo set_value('alamat'); ?>">
              <?php echo form_error('alamat'); ?>
            </div>

            <div class="form-group text-start">
              <span>Email</span>
              <label for="email"><i class="lni lni-envelope"></i></label>
              <input class="form-control" id="email" name="email" type="email" placeholder="nama@email.com" autocomplete="off" value="<?php echo set_value('email'); ?>">
              <?php echo form_error('email'); ?>
            </div>

            <div class="form-group text-start">
              <span>No Telp</span>
              <label for="no_telp"><i class="lni lni-phone"></i></label>
              <input class="form-control" id="no_telp" name="no_telp" type="text" placeholder="08xxxxxxxxxx" value="<?php echo set_value('no_telp'); ?>">
              <?php echo form_error('no_telp'); ?>
            </div>

            <div class="form-group text-start">
              <span>Password</span>
              <label for="password"><i class="lni lni-lock"></i></label>
              <input class="form-control" id="password" name="password" type="password" placeholder="Buat password" autocomplete="off">
              <?php echo form_error('password'); ?>
            </div>

            <button class="btn auth-primary-btn btn-lg w-100" type="submit">Registrasi</button>
          <?php echo form_close(); ?>
        </div>

        <div class="login-meta-data">
          <p class="mt-3 mb-0">Sudah punya akun?<a class="ms-1" href="<?= base_url('login') ?>">Masuk</a></p>
        </div>

        <div class="view-as-guest">
          <a class="btn" href="<?= base_url() ?>">Kembali ke Halaman Utama</a>
        </div>
      </section>
    </main>
  </div>

  <script src="<?php echo get_login_theme('js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?php echo get_login_theme('js/jquery.min.js') ?>"></script>
  <script src="<?php echo get_login_theme('js/waypoints.min.js') ?>"></script>
  <script src="<?php echo get_login_theme('js/jquery.easing.min.js') ?>"></script>
  <script src="<?php echo get_login_theme('js/owl.carousel.min.js') ?>"></script>
  <script src="<?php echo get_login_theme('js/jquery.counterup.min.js') ?>"></script>
  <script src="<?php echo get_login_theme('js/jquery.countdown.min.js') ?>"></script>
  <script src="<?php echo get_login_theme('js/default/jquery.passwordstrength.js') ?>"></script>
  <script src="<?php echo get_login_theme('js/default/dark-mode-switch.js') ?>"></script>
  <script src="<?php echo get_login_theme('js/default/active.js') ?>"></script>
  <script src="<?php echo get_login_theme('js/pwa.js') ?>"></script>
</body>

</html>
