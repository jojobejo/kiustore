<?php
defined('BASEPATH') or exit('No direct script access allowed');

$store_address = trim((string) get_settings('store_address'));
$store_phone = trim((string) get_settings('store_phone_number'));
$store_email = trim((string) get_settings('store_email'));
$store_website = site_url();
$flash_message = isset($flash) ? trim((string) $flash) : '';
$user_email = (isset($user) && isset($user->email)) ? (string) $user->email : '';
?>

<style>
  .contact-mobile-card {
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 12px;
  }

  .contact-mobile-card .label {
    font-size: 13px;
    color: #6c757d;
    margin-bottom: 6px;
  }

  .contact-mobile-card .value {
    font-size: 14px;
    color: #212529;
    margin-bottom: 0;
    word-break: break-word;
  }

  .contact-mobile-form {
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
  }

  .contact-mobile-form .form-control {
    border-radius: 10px;
  }

  .contact-mobile-map iframe {
    border: 0;
    border-radius: 12px;
    min-height: 320px;
  }
</style>

<main class="main-wrap index-page mb-xxl pt-2">
  <section class="pt-0">
    <div class="top-content mb-2">
      <div>
        <h4 class="title-color">Hubungi Kami</h4>
        <p class="content-color">Silakan kirim pertanyaan atau masukan Anda.</p>
      </div>
    </div>

    <div class="contact-mobile-card">
      <p class="label">Alamat</p>
      <p class="value"><?php echo $store_address !== '' ? nl2br(html_escape($store_address)) : '-'; ?></p>
    </div>

    <div class="contact-mobile-card">
      <p class="label">No. HP</p>
      <p class="value"><?php echo $store_phone !== '' ? html_escape($store_phone) : '-'; ?></p>
    </div>

    <div class="contact-mobile-card">
      <p class="label">Email</p>
      <p class="value">
        <?php if ($store_email !== '') : ?>
          <a href="mailto:<?php echo html_escape($store_email); ?>"><?php echo html_escape($store_email); ?></a>
        <?php else : ?>
          -
        <?php endif; ?>
      </p>
    </div>

    <div class="contact-mobile-card">
      <p class="label">Website</p>
      <p class="value"><a href="<?php echo html_escape($store_website); ?>"><?php echo html_escape($store_website); ?></a></p>
    </div>
  </section>

  <section class="pt-0">
    <div class="contact-mobile-form">
      <?php if ($flash_message !== '') : ?>
        <div class="alert alert-success text-center mb-3"><?php echo html_escape($flash_message); ?></div>
      <?php endif; ?>

      <form action="<?php echo site_url('pages/send_message'); ?>" method="POST" novalidate>
        <div class="mb-2">
          <input type="text" name="name" class="form-control" value="<?php echo set_value('name', (is_login() ? get_user_name() : '')); ?>" placeholder="Nama" required>
          <?php echo form_error('name'); ?>
        </div>
        <div class="mb-2">
          <input type="email" name="email" class="form-control" value="<?php echo set_value('email', (is_login() ? $user_email : '')); ?>" placeholder="Email" required>
          <?php echo form_error('email'); ?>
        </div>
        <div class="mb-2">
          <input type="text" name="subject" class="form-control" value="<?php echo set_value('subject'); ?>" placeholder="Subjek pesan" required>
          <?php echo form_error('subject'); ?>
        </div>
        <div class="mb-3">
          <textarea name="message" rows="5" class="form-control" placeholder="Pesan" required><?php echo set_value('message'); ?></textarea>
          <?php echo form_error('message'); ?>
        </div>
        <button type="submit" class="btn-solid w-100">Kirim Pesan</button>
      </form>
    </div>
  </section>

  <section class="pt-0 pb-2">
    <div class="contact-mobile-map">
      <iframe
        width="100%"
        height="360"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="Lokasi Toko"
        src="https://www.google.com/maps?q=PT+Karisma+Indoagro+Universal&hl=en&z=15&output=embed">
      </iframe>
    </div>
  </section>
</main>
