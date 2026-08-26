<?php
defined('BASEPATH') or exit('No direct script access allowed');

$render_order_badges = function ($order) {
  $badges = [];

  $badges[] = get_order_status($order->order_status, $order->payment_method);

  if ($order->payment_method == 2 && $order->payment_status == 3) {
    $badges[] = get_payment_status($order->payment_status);
  }

  $discount_amount = isset($order->discount_amount) ? (float) $order->discount_amount : 0;

  if (!empty($order->has_coupon_discount)) {
    $coupon_label = !empty($order->coupon) ? html_escape($order->coupon) : 'Promo';
    $badges[] = '<span class="badge bg-success">Menggunakan Kupon/Promo: ' . $coupon_label . '</span>';
    $badges[] = '<span class="badge bg-info">Diskon Rp ' . format_rupiah($discount_amount) . '</span>';
  } else {
    $badges[] = '<span class="badge bg-secondary">Tanpa Kupon/Promo</span>';
  }

  return '<div class="order-status-badges mt-1">' . implode(' ', $badges) . '</div>';
};
?>
<style>
  .order-history .order-box {
    cursor: pointer;
  }

</style>
<!-- Main Start -->
<main class="main-wrap order-history mb-xxl">
  <ul class="nav nav-tab nav-pills custom-scroll-hidden" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="catagories7-tab" data-bs-toggle="pill" data-bs-target="#catagories7" type="button" role="tab" aria-controls="catagories7" aria-selected="true"> Semua <span class="badge rounded-pill bg-danger" id=""><?= $all ?> </span></button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="catagories1-tab" data-bs-toggle="pill" data-bs-target="#catagories1" type="button" role="tab" aria-controls="catagories1" aria-selected="true"> Belum Bayar<span class="badge rounded-pill bg-danger" id=""><?= $unpaid; ?> </span></button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="catagories2-tab" data-bs-toggle="pill" data-bs-target="#catagories2" type="button" role="tab" aria-controls="catagories2" aria-selected="false"> Diproses<span class="badge rounded-pill bg-danger" id=""><?= $process; ?> </span></button>
    </li>

    <li class="nav-item" role="presentation">
      <button class="nav-link" id="catagories3-tab" data-bs-toggle="pill" data-bs-target="#catagories3" type="button" role="tab" aria-controls="catagories3" aria-selected="false">Dikirim<span class="badge rounded-pill bg-danger" id=""><?= $deliver; ?> </span></button>
    </li>

    <li class="nav-item" role="presentation">
      <button class="nav-link" id="catagories4-tab" data-bs-toggle="pill" data-bs-target="#catagories4" type="button" role="tab" aria-controls="catagories4" aria-selected="false">Selesai<span class="badge rounded-pill bg-danger" id=""><?= $success; ?> </span></button>
    </li>

    <li class="nav-item" role="presentation">
      <button class="nav-link" id="catagories5-tab" data-bs-toggle="pill" data-bs-target="#catagories5" type="button" role="tab" aria-controls="catagories5" aria-selected="false">Dibatalkan<span class="badge rounded-pill bg-danger" id=""><?= $cancel; ?> </span></button>
    </li>

    <li class="nav-item" role="presentation">
      <button class="nav-link" id="catagories6-tab" data-bs-toggle="pill" data-bs-target="#catagories6" type="button" role="tab" aria-controls="catagories6" aria-selected="false">Pelunasan</button>
    </li>
  </ul>

  <!-- Search Box Start -->
  <div class="search-box">
    <div>
      <!-- <i class="iconly-Search icli search"></i>
        <input class="form-control" type="search" placeholder="Search here..." /> -->
    </div>
    <!-- <button class="filter font-md" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter" aria-controls="filter">Filter</button> -->


    <button class="btn btn-primary filter font-md mb-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter" aria-controls="filter">Filter</button>
  </div>
  <!-- Search Box End -->

  <!-- Tab Content Start -->
  <section class="tab-content ratio2_1" id="pills-tabContent">

    <div class="tab-pane fade show active" id="catagories1" role="tabpanel" aria-labelledby="catagories1-tab">
      <?php foreach ($orders as $order) : ?>
        <?php if (($order->payment_method == 2 &&  $order->order_status == 2) || ($order->payment_method == 1 && $order->order_status == 2)) : ?>
          <div class="order-box">
            <div class="media">
              <a href="<?= base_url() . 'customer/orders/view/' . $order->id, '#' . $order->order_number; ?>" class="content-box">
                <h2 class="font-sm title-color">ID: #<?= $order->order_number; ?></h2>
                <p class="font-xs content-color"><?php echo get_formatted_date($order->order_date); ?></p>
                <span class="content-color font-xs">Total: <span class="font-theme"><?php echo format_rupiah($order->final_price); ?></span></span>
                <span class="content-color font-xs">Jumlah Barang: <span class="font-theme"><?php echo $order->total_items; ?></span></span>
                <?= $render_order_badges($order); ?>
              </a>
              <!--    <div class="media-body">
                      <img src="<?php echo get_theme_uri('images/map/map.jpg'); ?>" alt="map" />
                    </div> -->
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="tab-pane fade" id="catagories2" role="tabpanel" aria-labelledby="catagories2-tab">
      <?php foreach ($orders as $order) : ?>
        <?php if (($order->payment_method == 2 &&  $order->order_status == 1) || ($order->payment_method == 1 && $order->order_status == 9) || ($order->payment_method == 2 &&  $order->order_status == 3) || ($order->payment_method == 2 &&  $order->order_status == 8) || ($order->payment_method == 1 &&  $order->order_status == 1) || ($order->payment_method == 1 &&  $order->order_status == 3) || ($order->payment_method == 3 && $order->order_status == 11) ) : ?>
          <div class="order-box">
            <div class="media">
              <a href="<?= base_url() . 'customer/orders/view/' . $order->id, '#' . $order->order_number; ?>" class="content-box">
                <h2 class="font-sm title-color">ID: #<?= $order->order_number; ?></h2>
                <p class="font-xs content-color"><?php echo get_formatted_date($order->order_date); ?></p>
                <span class="content-color font-xs">Total: <span class="font-theme"><?php echo format_rupiah($order->final_price); ?></span></span>
                <span class="content-color font-xs">Jumlah Barang: <span class="font-theme"><?php echo $order->total_items; ?></span></span>
                <?= $render_order_badges($order); ?>
              </a>
              <!--    <div class="media-body">
                      <img src="<?php echo get_theme_uri('images/map/map.jpg'); ?>" alt="map" />
                    </div> -->
            </div>
          </div>
        <?php endif; ?>

      <?php endforeach; ?>
    </div>

    <div class="tab-pane fade" id="catagories3" role="tabpanel" aria-labelledby="catagories3-tab">

      <?php foreach ($orders as $order) : ?>
        <?php if (($order->payment_method == 2 &&  $order->order_status == 4) || ($order->payment_method == 1 &&  $order->order_status == 4)) : ?>
          <div class="order-box">
            <div class="media">
              <a href="<?= base_url() . 'customer/orders/view/' . $order->id, '#' . $order->order_number; ?>" class="content-box">
                <h2 class="font-sm title-color">ID: #<?= $order->order_number; ?></h2>
                <p class="font-xs content-color"><?php echo get_formatted_date($order->order_date); ?></p>

                <span class="content-color font-xs">Total: <span class="font-theme"><?php echo format_rupiah($order->final_price); ?></span></span>
                <span class="content-color font-xs">Jumlah Barang: <span class="font-theme"><?php echo $order->total_items; ?></span></span>
                <?= $render_order_badges($order); ?>

              </a>
              <!--    <div class="media-body">
                      <img src="<?php echo get_theme_uri('images/map/map.jpg'); ?>" alt="map" />
                    </div> -->
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="tab-pane fade" id="catagories4" role="tabpanel" aria-labelledby="catagories4-tab">

      <?php foreach ($orders as $order) : ?>
        <?php if (($order->payment_method == 2 &&  $order->order_status == 6) || ($order->payment_method == 2 &&  $order->order_status == 5) || ($order->payment_method == 1 &&  $order->order_status == 6)) : ?>
          <div class="order-box">
            <div class="media">
              <a href="<?= base_url() . 'customer/orders/view/' . $order->id, '#' . $order->order_number; ?>" class="content-box">
                <h2 class="font-sm title-color">ID: #<?= $order->order_number; ?></h2>
                <p class="font-xs content-color"><?php echo get_formatted_date($order->order_date); ?></p>
                <span class="content-color font-xs">Total: <span class="font-theme"><?php echo format_rupiah($order->final_price); ?></span></span>
                <span class="content-color font-xs">Jumlah Barang: <span class="font-theme"><?php echo $order->total_items; ?></span></span>
                <?= $render_order_badges($order); ?>

              </a>
              <!--    <div class="media-body">
                      <img src="<?php echo get_theme_uri('images/map/map.jpg'); ?>" alt="map" />
                    </div> -->
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="tab-pane fade" id="catagories5" role="tabpanel" aria-labelledby="catagories5-tab">
      <?php foreach ($orders as $order) : ?>
        <?php if (($order->payment_method == 2 &&  $order->order_status == 7) || ($order->payment_method == 1 &&  $order->order_status == 7) || ($order->payment_method == 3 &&  $order->order_status == 7)) : ?>
          <div class="order-box">
            <div class="media">
              <a href="<?= base_url() . 'customer/orders/view/' . $order->id, '#' . $order->order_number; ?>" class="content-box">
                <h2 class="font-sm title-color">ID: #<?= $order->order_number; ?></h2>
                <p class="font-xs content-color"><?php echo get_formatted_date($order->order_date); ?></p>
                <span class="content-color font-xs">Total: <span class="font-theme"><?php echo format_rupiah($order->final_price); ?></span></span>
                <span class="content-color font-xs">Jumlah Barang: <span class="font-theme"><?php echo $order->total_items; ?></span></span>
                <?= $render_order_badges($order); ?>

              </a>
              <!--    <div class="media-body">
                      <img src="<?php echo get_theme_uri('images/map/map.jpg'); ?>" alt="map" />
                    </div> -->
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="tab-pane fade" id="catagories6" role="tabpanel" aria-labelledby="catagories6-tab">
      <?php foreach ($orders as $order) : ?>
        <?php if ($order->payment_method == 1 &&  $order->order_status == 5) : ?>
          <div class="order-box">
            <div class="media">
              <a href="<?= base_url() . 'customer/orders/view/' . $order->id, '#' . $order->order_number; ?>" class="content-box">
                <h2 class="font-sm title-color">ID: #<?= $order->order_number; ?></h2>
                <p class="font-xs content-color"><?php echo get_formatted_date($order->order_date); ?></p>
                <span class="content-color font-xs">Total: <span class="font-theme"><?php echo format_rupiah($order->final_price); ?></span></span>
                <span class="content-color font-xs">Jumlah Barang: <span class="font-theme"><?php echo $order->total_items; ?></span></span>
                <?= $render_order_badges($order); ?>
                <p class="font-xs content-color">Jatuh Tempo: <?php echo get_formatted_date($order->due_date); ?></p>

              </a>
              <!--    <div class="media-body">
                      <img src="<?php echo get_theme_uri('images/map/map.jpg'); ?>" alt="map" />
                    </div> -->
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="tab-pane fade" id="catagories7" role="tabpanel" aria-labelledby="catagories7-tab">
      <?php foreach ($orders as $order) : ?>
        <?php if ($order->payment_method > 0 &&  $order->order_status > 0) : ?>
          <div class="order-box">
            <div class="media">
              <a href="<?= base_url() . 'customer/orders/view/' . $order->id, '#' . $order->order_number; ?>" class="content-box">
                <h2 class="font-sm title-color">ID: #<?= $order->order_number; ?></h2>
                <p class="font-xs content-color"><?php echo get_formatted_date($order->order_date); ?></p>
                <span class="content-color font-xs">Total: <span class="font-theme"><?php echo format_rupiah($order->final_price); ?></span></span>
                <span class="content-color font-xs">Jumlah Barang: <span class="font-theme"><?php echo $order->total_items; ?></span></span>
                <?= $render_order_badges($order); ?>
                <p class="font-xs content-color">Jatuh Tempo: <?php echo get_formatted_date($order->due_date); ?></p>

              </a>
              <!--    <div class="media-body">
                      <img src="<?php echo get_theme_uri('images/map/map.jpg'); ?>" alt="map" />
                    </div> -->
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

  </section>
  <!-- Tab Content End -->
</main>
<!-- Main End -->


<!-- <main class="main-wrap no-order mb-xxl">
    <div class="banner-box">
      <img src="<?php echo get_theme_uri('svg/no-order.svg'); ?>" class="img-fluid" alt="404" />
    </div>

    <section class="error mb-large">
      <h2 class="font-lg">Histori order kosong</h2>
      <a href="<?= site_url('category') ?>" class="btn-solid">Mulai Belanja</a>
    </section>
  </main> -->
<!-- Filter Offcanvas Start -->
<div class="shop-fillter order-history-filter offcanvas offcanvas-bottom" tabindex="-1" id="filter" aria-labelledby="filter">
  <div class="offcanvas-header">
    <h5 class="title-color font-md">Filter</h5>
  </div>
  <form method="get">
    <div class="offcanvas-body small">
      <div class="pack-size mt-0">
        <div class="row g-3">

          <label class="btn btn-secondary size">
            <input type="radio" name="filter" value="semua" id="hatchback" autocomplete="off" checked> <span class="font-sm">Semua</span>
          </label>

          <label class="btn btn-secondary size">
            <input type="radio" name="filter" value="1day" id="hatchback" autocomplete="off" checked> <span class="font-sm">Hari Ini</span>
          </label>

          <label class="btn btn-secondary size">
            <input type="radio" name="filter" value="1week" id="hatchback" autocomplete="off" checked> <span class="font-sm">Minggu Ini</span>
          </label>

          <label class="btn btn-secondary size">
            <input type="radio" name="filter" value="1month" id="hatchback" autocomplete="off" checked> <span class="font-sm">Bulan Ini</span>
          </label>

          <label class="btn btn-secondary size">
            <input type="radio" name="filter" value="6month" id="hatchback" autocomplete="off" checked> <span class="font-sm">6 Bulan</span>
          </label>

          <label class="btn btn-secondary size">
            <input type="radio" name="filter" value="1year" id="hatchback" autocomplete="off" checked> <span class="font-sm">1 Tahun</span>
          </label>

          <!-- <div class="col-6">
        <label class="btn btn-secondary size">
          <input type="radio" name="options" id="hatchback" autocomplete="off" checked> <span class="font-sm">Minggu Ini</span>
        </label>
      </div>

      <div class="col-6">
        <label class="btn btn-secondary size">
          <input type="radio" name="options" id="hatchback" autocomplete="off" checked> <span class="font-sm">Bulan Ini</span>
        </label>
      </div>

      <div class="col-6">
        <label class="btn btn-secondary size">
          <input type="radio" name="options" id="hatchback" autocomplete="off" checked> <span class="font-sm">6 Bulan</span>
        </label>
      </div>

      <div class="col-6">
        <label class="btn btn-secondary size">
          <input type="radio" name="options" id="hatchback" autocomplete="off" checked> <span class="font-sm">1 Tahun</span>
        </label>
      </div> -->

        </div>
      </div>

      <!-- <div class="pack-size">
      <h5 class="title-color font-md">Time Filter</h5>
      <div class="row g-3">
        <div class="col-6">
          <div class="size">
            <span class="font-sm">Last 30days</span>
          </div>
        </div>

        <div class="col-6">
          <div class="size">
            <span class="font-sm">Last 6 Month</span>
          </div>
        </div>

        <div class="col-6">
          <div class="size">
            <span class="font-sm">2021</span>
          </div>
        </div>

        <div class="col-6">
          <div class="size">
            <span class="font-sm">2020</span>
          </div>
        </div>
      </div>
    </div> -->
    </div>
    <div class="offcanvas-footer">
      <div class="btn-box">
        <button class="btn-outline font-md" data-bs-dismiss="offcanvas" aria-label="Close">Close</button>
        <button class="btn-solid font-md" data-bs-dismiss="offcanvas" aria-label="Close">Apply</button>
      </div>
    </div>
  </form>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.order-history .order-box').forEach(function(card) {
      var target = card.querySelector('.content-box');

      if (!target || !target.href) {
        return;
      }

      card.setAttribute('role', 'link');
      card.setAttribute('tabindex', '0');

      card.addEventListener('click', function(event) {
        if (event.target.closest('button, input, label, select, textarea')) {
          return;
        }

        window.location.href = target.href;
      });

      card.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          window.location.href = target.href;
        }
      });
    });
  });
</script>
<!-- Filter Offcanvas End -->
