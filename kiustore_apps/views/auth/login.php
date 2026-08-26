<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">
    <meta name="description" content="Masuk ke KIU Store untuk belanja produk pertanian resmi Karisma Online.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#117a43">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>KIU Store | Login</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900&amp;display=swap">
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.png')?>">
    <link rel="apple-touch-icon" href="<?php echo get_login_theme('img/icons/icon-96x96.png')?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_login_theme('img/icons/icon-152x152.png')?>">
    <link rel="apple-touch-icon" sizes="167x167" href="<?php echo get_login_theme('img/icons/icon-167x167.png')?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_login_theme('img/icons/icon-180x180.png')?>">
    <link rel="stylesheet" href="<?php echo get_login_theme('css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?php echo get_login_theme('css/animate.css')?>">
    <link rel="stylesheet" href="<?php echo get_login_theme('css/owl.carousel.min.css')?>">
    <link rel="stylesheet" href="<?php echo get_login_theme('css/font-awesome.min.css')?>">
    <link rel="stylesheet" href="<?php echo get_login_theme('css/default/lineicons.min.css')?>">
    <link rel="stylesheet" href="<?php echo get_login_theme('style.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/auth-kiu.css?v=20260825-2')?>">
    <link rel="manifest" href="<?php echo get_login_theme('manifest.json')?>">
  </head>
  <body class="auth-kiu-page">
    <div class="preloader" id="preloader">
      <div class="spinner-grow text-secondary" role="status">
        <div class="sr-only">Loading...</div>
      </div>
    </div>

    <div class="login-wrapper d-flex align-items-center justify-content-center">
      <div class="background-shape"></div>

      <main class="auth-shell" aria-label="Login KIU Store">
        <section class="auth-brand-panel">
          <div>
            <div class="auth-brand-kicker">PT. Karisma Indoagro Universal</div>
            <h1 class="auth-brand-title">Belanja produk pertanian resmi lebih mudah.</h1>
            <p class="auth-brand-text">
              KIU Store menghubungkan pelanggan dengan katalog Karisma Online untuk kebutuhan pupuk, benih, pestisida, dan produk pendukung pertanian.
            </p>
            <div class="auth-brand-tags">
              <span>Tradisi</span>
              <span>Reputasi</span>
              <span>Inovasi</span>
            </div>
            <div class="auth-mini-market" aria-hidden="true">
              <div class="auth-mini-card">
                <strong>Produk Resmi</strong>
                <span>Katalog terkurasi dari jaringan Karisma.</span>
              </div>
              <div class="auth-mini-card">
                <strong>Order Praktis</strong>
                <span>Checkout dan histori pesanan dalam satu akun.</span>
              </div>
              <div class="auth-mini-card">
                <strong>Dukungan Toko</strong>
                <span>Akses layanan pelanggan dan update transaksi.</span>
              </div>
            </div>
          </div>

          <p class="auth-footnote">Mitra terpercaya kesuksesan pertanian Indonesia.</p>
        </section>

        <section class="auth-card">
          <div class="auth-logo-lockup">
            <div class="auth-logo-orbit">
              <img class="auth-logo" src="<?php echo base_url('assets/images/logo.png')?>" alt="Karisma Online Shop">
            </div>
            <div class="auth-status-chip">Official App</div>
          </div>

          <h1>Masuk Akun</h1>
          <p class="auth-card-subtitle">Gunakan email dan password yang sudah terdaftar untuk melanjutkan belanja.</p>

          <div class="register-form auth-form">
            <?php echo form_open('auth/login/do_login'); ?>
              <div class="form-group text-start">
                <span>Email</span>
                <label for="email"><i class="lni lni-user"></i></label>
                <input class="form-control" id="email" name="email" value="<?php echo set_value('email', isset($old_email) ? $old_email : ''); ?>" type="email" placeholder="nama@email.com" required>
              </div>

              <div class="form-group text-start">
                <span>Password</span>
                <label for="password"><i class="lni lni-lock"></i></label>
                <input class="form-control" id="password" name="password" type="password" placeholder="Masukkan password" required>
              </div>

              <?php if (!empty($redirection)) : ?>
                <div class="flash-message mt-3 alert alert-danger auth-alert">
                  Silahkan login untuk melanjutkan...
                </div>
              <?php endif; ?>

              <?php if (!empty($flash_message)) : ?>
                <div class="flash-message mt-3 alert alert-danger auth-alert">
                  <?php echo $flash_message; ?>
                </div>
              <?php endif; ?>

              <button class="btn auth-primary-btn btn-lg w-100" type="submit">Masuk</button>
            <?php echo form_close(); ?>
          </div>

          <div class="login-meta-data">
            <p class="mb-0">Belum punya akun?<a class="ms-1" href="<?=base_url('register')?>">Registrasi sekarang</a></p>
          </div>

          <div class="view-as-guest">
            <a class="btn" href="<?=base_url()?>">Kembali ke Halaman Utama</a>
          </div>
        </section>
      </main>
    </div>

    <script src="<?php echo get_login_theme('js/bootstrap.bundle.min.js')?>"></script>
    <script src="<?php echo get_login_theme('js/jquery.min.js')?>"></script>
    <script src="<?php echo get_login_theme('js/waypoints.min.js')?>"></script>
    <script src="<?php echo get_login_theme('js/jquery.easing.min.js')?>"></script>
    <script src="<?php echo get_login_theme('js/owl.carousel.min.js')?>"></script>
    <script src="<?php echo get_login_theme('js/jquery.counterup.min.js')?>"></script>
    <script src="<?php echo get_login_theme('js/jquery.countdown.min.js')?>"></script>
    <script src="<?php echo get_login_theme('js/default/jquery.passwordstrength.js')?>"></script>
    <script src="<?php echo get_login_theme('js/default/dark-mode-switch.js')?>"></script>
    <script src="<?php echo get_login_theme('js/default/active.js')?>"></script>
    <script src="<?php echo get_login_theme('js/pwa.js')?>"></script>
  </body>
</html>
