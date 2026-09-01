<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('format_customer_price_text')) {
  function format_customer_price_text($price, $price_2 = null, $price_3 = null, $use_get_price = true)
  {
    $formatted_price = $use_get_price ? format_rupiah(get_price($price, $price_2, $price_3)) : format_rupiah($price);
    $normalized_price = preg_replace('/[^0-9]/', '', (string) $formatted_price);

    if ($normalized_price === '999') {
      return 'silahkan hubungi admin';
    }

    return 'Rp ' . $formatted_price;
  }
}
?>
<!-- Main Start -->
<main class="main-wrap index-page mb-xxl pt-2">
  <!-- Search Box Start -->



  <form id="navbar-search-main" action="<?php echo site_url('search'); ?>" required>
    <!-- <div class="form-group mb-0">
      <div class="input-group input-group-alternative input-group-merge">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
        <input class="form-control" value="<?php echo (isset($query) ? $query : ''); ?>" name="search_query" placeholder="Cari..." type="text" required>
      </div>
    </div>
    <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button> -->

    <div class="search-box mb-4" data-tour="search">
      <i class="iconly-Search icli search"></i>
      <input class="form-control" type="search" value="<?php echo (isset($query) ? $query : ''); ?>" name="search_query" placeholder="Cari disini..." required />
    </div>
  </form>



  <!-- Search Box End -->
  <?php if (!empty($_SESSION['__ACTIVE_SESSION_DATA'])) : ?>
    <?php
    $jam = date('G');
    $ucapan = '';
    if ($jam >= 0 && $jam <= 10) {
      $ucapan = "Selamat Pagi";
    } else if ($jam > 10 && $jam <= 14) {
      $ucapan = "Selamat Siang";
    } else if ($jam > 14 && $jam <= 18) {
      $ucapan = "Selamat Sore";
    } else if ($jam > 18) {
      $ucapan = "Selamat Malam";
    }
    ?>
    <section class="info pt-0 mt-3">
      <div class="row gy-sm-6 gy-2">
        <div class="col-12">
          <div class="info-wrap bg-shape bg-theme-1" style="background-color: #0c5fdb;">
            <h3 class="font-md"><?= $ucapan; ?>, <?php echo get_user_name(); ?>... </h3>
            <span class="font-sm"> </span>
          </div>
        </div>
      </div>
    </section>
    <section class="info pt-0 mt-1">
      <div class="row gy-sm-6 gy-2">
        <div class="col-6">
          <a href="<?= base_url('invoice'); ?>">
            <div class="info-wrap bg-shape bg-theme-cus" style="background-color: #088345;">
              <h3 class="font-md">Tagihan </h3>
              <span class="font-sm"><?php echo get_user_invoice(); ?></span>
            </div>
          </a>
        </div>
        <div class="col-6">
          <div class="info-wrap bg-shape bg-theme-cus" style="background-color: #088345;">
            <h3 class="font-md">Limit Kredit</h3>
            <span class="font-sm"><?php echo get_user_limit_credit(); ?></span>
          </div>
        </div>
      </div>
    </section>
    <?php foreach ($tagihan as $dt) : ?>
      <div class="alert alert-primary <?= $dt->due_date > date('Y-m-d') ? 'bg-theme-5 ' : 'bg-theme-2'; ?> d-flex align-items-center <?= $dt->due_date > date('Y-m-d') ? 'text-black ' : 'text-white'; ?>  alert-dismissible " role="alert">
        <?php if ($dt->due_date > date('Y-m-d')) : ?>
          <img src="<?= base_url('assets/images/'); ?>smiling-face.png" style="height: 24px;width: 24px;" class="flex-shrink-0 me-2">
        <?php else : ?>
          <img src="<?= base_url('assets/images/'); ?>sad-face.png" style="height: 24px;width: 24px;" class="flex-shrink-0 me-2">
        <?php endif; ?>
        <div>
          <?php if ($dt->due_date > date('Y-m-d')) : ?>
            Tagihan anda jatuh tempo pada tanggal <strong><?= get_formatted_date($dt->due_date); ?></strong>
          <?php else : ?>
            Tagihan anda telah melampaui jatuh tempo pada tanggal <strong><?= get_formatted_date($dt->due_date); ?></strong>
          <?php endif; ?>
          senilai
          <strong><?= format_rupiah($dt->total_price + (($dt->shipping_cost) ? $dt->shipping_cost : 0) + (($dt->insurance) ? $dt->insurance : 0)) ?></strong>. Cek
          <a href="<?= base_url('customer/orders/view/') . $dt->id . '#' . $dt->order_number; ?>">Disini</a>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <section class="menu-awal pt-0 mt-1" style="background-color: #0c5fdb;">
    <div class="row gy-sm-4 gy-2">

      <!-- <div class="col-3">
        <div class="menu-wrap">
          <div class="bg-shape bg-theme-menu"></div>
          <a href="<?= site_url('category') ?>"> <img class="menu-img img-fluid" src="<?php echo site_url('assets/icon/kategori.png'); ?>" alt="kategori" /> </a>
          <span class="font-white">Kategori</span>
        </div>
      </div> -->

      <div class="col-3">
        <div class="menu-wrap" data-tour="menu-category">
          <div class="bg-shape"></div>
          <a href="<?= site_url('category') ?>"> <img class="menu-img img-fluid" src="<?php echo site_url('assets/icon/kategori.png'); ?>" alt="kategori" /> </a>
          <span class="font-white">Kategori</span>
        </div>
      </div>
      <div class="col-3">
        <div class="menu-wrap" data-tour="menu-products">
          <div class="bg-shape"></div>
          <a href="<?= site_url('all_products') ?>"> <img class="menu-img img-fluid" src="<?php echo site_url('assets/icon/produk.png'); ?>" alt="produk" /> </a>
          <span class="font-white">Produk</span>
        </div>
      </div>
      <div class="col-3">
        <div class="menu-wrap" data-tour="menu-promo">
          <div class="bg-shape"></div>
          <a href="<?= site_url('promo') ?>"> <img class="menu-img img-fluid" src="<?php echo site_url('assets/icon/diskon.png'); ?>" alt="diskon" /> </a>
          <span class="font-white">Promo</span>
        </div>
      </div>
      <div class="col-3">
        <div class="menu-wrap" data-tour="menu-cart">
          <div class="bg-shape"></div>
          <a href="<?= site_url('cart') ?>"> <img class="menu-img img-fluid" src="<?php echo site_url('assets/icon/keranjang.png'); ?>" alt="cart" /> </a>
          <span class="font-white">cart</span>
        </div>
      </div>
      <div class="col-3">
        <div class="menu-wrap" data-tour="menu-invoice">
          <div class="bg-shape"></div>
          <a href="<?= site_url('invoice') ?>"> <img class="menu-img img-fluid" src="<?php echo site_url('assets/icon/kredit.png'); ?>" alt="kredit" /> </a>
          <span class="font-white">Tagihan</span>
        </div>
      </div>
      <div class="col-3">
        <div class="menu-wrap" data-tour="menu-history">
          <div class="bg-shape"></div>
          <a href="<?= site_url('order_history') ?>"> <img class="menu-img img-fluid" src="<?php echo site_url('assets/icon/histori.png'); ?>" alt="histori" /> </a>
          <span class="font-white">Riwayat</span>
        </div>
      </div>
      <div class="col-3">
        <div class="menu-wrap" data-tour="menu-chat">
          <div class="bg-shape"></div>
          <a href="<?= site_url('message') ?>"> <img class="menu-img img-fluid" src="<?php echo site_url('assets/icon/pesan.png'); ?>" alt="chat" /> </a>
          <span class="font-white">Chat</span>
        </div>
      </div>
      <div class="col-3">
        <div class="menu-wrap" data-tour="menu-setting">
          <div class="bg-shape"></div>
          <a href="<?= site_url('profile') ?>"> <img class="menu-img img-fluid" src="<?php echo site_url('assets/icon/user-setting.png'); ?>" alt="setting" /> </a>
          <span class="font-white">Setting</span>
        </div>
      </div>


    </div>
  </section>

  <!-- Banner Section Start -->
  <section class="banner-section ratio2_1">
    <div class="h-banner-slider">

      <?php if (count($banner_product) > 0) : ?>
        <?php foreach (array_slice($banner_product, 0, 3) as $banner) : ?>
          <div>
            <div class="banner-box">
              <a href="<?php echo site_url('product/' . $banner->id . '/' . $banner->sku . '/'); ?>">
                <img src="<?php echo base_url('assets/uploads/banner_product/' . $banner->banner_image); ?>" alt="banner" class="bg-img" />
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
      <?php endif; ?>

      <!-- <div>
        <div class="banner-box">
          <img src="<?php echo get_theme_uri('images/banner/bn1.jpg'); ?>" alt="banner" class="bg-img" />
         <div class="content-box">
            <h1 class="title-color font-md heading">aaaa</h1>
            <p class="content-color font-sm">Get instant delivery</p>
            <a href="shop.html" class="btn-solid font-sm">Shop Now</a>
          </div>
        </div>
      </div>

      <div>
        <div class="banner-box">
          <img src="<?php echo get_theme_uri('images/banner/bn2.jpg'); ?>" alt="banner" class="bg-img" />
         <div class="content-box">
            <h2 class="font-white font-md heading">Farm Fresh Veggies</h2>
            <p class="font-white font-sm">Get instant delivery</p>
            <a href="shop.html" class="btn-outline font-sm">Shop Now</a>
          </div>
        </div>
      </div>

      <div>
        <div class="banner-box">
          <img src="<?php echo get_theme_uri('images/banner/bn3.jpg'); ?>" alt="banner" class="bg-img" />
         <div class="content-box">
            <h2 class="font-white font-md heading">Farm Fresh Veggies</h2>
            <p class="font-white font-sm">Get instant delivery</p>
            <a href="shop.html" class="btn-outline font-sm">Shop Now</a>
          </div>
        </div>
      </div>
       -->
    </div>
  </section>
  <!-- Banner Section End -->

  <?php if (!empty($_SESSION['__ACTIVE_SESSION_DATA'])) : ?>
    <!-- <section class="recently pt-0">
    <div class="recently-wrap">
      <h3 class="font-md">Batas Sisa Kredit</h3>
      <img class="corner" src="<?php echo get_theme_uri('svg/corner.svg'); ?>" alt="corner" />
      <span class="font-sm"><?php echo get_user_limit_transaction(); ?></span>
    </div>
  </section> -->

    <!-- Buy from Recently Bought Start -->
    <section class="recently pt-0">
      <div class="recently-wrap">
        <h3 class="font-md">Produk terakhir dibeli</h3>
        <img class="corner" src="<?php echo get_theme_uri('svg/corner.svg'); ?>" alt="corner" />

        <div class="recently-list-slider recently-list">
          <?php if (count($last_order) > 0) : ?>
            <?php foreach ($last_order as $data) : ?>
              <div>
                <div class="item">
                  <a href="<?php echo site_url('product/' . $data->id . '/' . $data->sku . '/'); ?>">
                    <img src="<?php echo get_product_image_url($data->picture_name); ?>" alt="<?php echo $data->name; ?>" />
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <!-- Buy from Recently Bought End -->
  <?php endif; ?>

  <!-- Shop By Category Start -->
  <section class="category pt-0">
    <h3 class="font-md"><span>Kategori Produk </span><span class="line"></span></h3>
    <div class="row gy-sm-4 gy-2">

      <?php if (count($categories) > 0) : ?>
        <?php $i = 1;
        foreach ($categories as $category) : ?>
          <div class="col-2">
            <div class="category-wrap">
              <div class="bg-shape bg-theme-blue border-blue"></div>
              <a href="<?php echo site_url('category/' . $category->id . '/' . $category->name . '/'); ?>"><img src="<?php echo get_theme_uri('images/catagoeris/' . $category->id . '.png'); ?>" class="w-100 h-100 object-fit-cover" alt="offer" /></a>
              <span class="title-color"><?php echo $category->name; ?></span>
            </div>
          </div>
        <?php $i++;
        endforeach; ?>


        <div class="col-1">
          <div class="category-wrap">
            <div class="bg-shape bg-theme-blue border-blue"></div>
            <a href="<?php echo site_url('category'); ?>"> <img class="category img-fluid" src="<?php echo get_theme_uri('images/catagoeris/8.png'); ?>" alt="category" /> </a>
            <span class="title-color">Semua Kategori</span>
          </div>
        </div>

      <?php else : ?>
      <?php endif; ?>


    </div>
  </section>
  <!-- Shop By Category End -->

  <?php if (count($promo_products) > 0) : ?>
    <!-- Say hello to Offers! Start -->
    <section class="offer-section pt-0">
      <div class="offer">
        <div class="top-content">
          <div>
            <h4 class="title-color">Promo Spesial</h4>
            <p class="content-color">Dapatkan Spesial Promo untuk Anda</p>
          </div>
          <!--    <a href="offer.html" class="font-theme">Lihat Semua</a> -->
        </div>

        <div class="offer-wrap">

          <?php foreach (array_slice($promo_products, 0, 3) as $product) : ?>
            <div class="product-list media">
              <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>"><img src="<?php echo get_product_image_url($product->picture_name); ?>" class="img-fluid" alt="<?php echo $product->name; ?>" /></a>
              <div class="media-body">
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>" class="font-sm"> <?php echo $product->name; ?></a>
                <span class="title-color font-sm"><?php echo format_customer_price_text($product->promo_price, $product->promo_price_2, $product->promo_price_3); ?> <del><small> <?php echo format_customer_price_text($product->price, $product->price_2, $product->price_3); ?></small></del>
                  <!-- <span class="badges-round bg-theme-theme font-xs">50% off</span> --></span>
                <div class="plus-minus d-xs-none">
                  <!-- <a class="btn btn-success" href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>">Beli</a> -->
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
        </div>
      </div>
    </section>
    <!-- Say hello to Offers! End -->
  <?php endif; ?>

  <!-- Lowest Price Start -->
  <section class="low-price-section pt-0">
    <div class="top-content">
      <div>
        <h4 class="title-color">Produk Terlaris</h4>
        <p class="content-color">Produk yang paling banyak dibeli</p>
      </div>
      <!--  <a href="shop.html" class="font-theme">See all</a> -->
    </div>

    <div class="product-slider">
      <?php if (count($best_products) > 0) : ?>
        <?php foreach ($best_products as $product) : ?>
          <div>
            <div class="product-card">
              <div class="img-wrap">
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>"><img src="<?php echo get_product_image_url($product->picture_name); ?>" class="img-fluid" alt="<?php echo $product->name; ?>" /> </a>
              </div>
              <div class="content-wrap">
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>" class="font-sm title-color"><?php echo $product->name; ?> </a>
                <?php if ($product->promo == 1) : ?>
                  <span class="title-color font-sm"><?php echo format_customer_price_text($product->promo_price, $product->promo_price_2, $product->promo_price_3); ?> <del><small> <?php echo format_customer_price_text($product->price, $product->price_2, $product->price_3); ?></small></del>
                  <?php else : ?>
                    <span class="title-color font-sm plus-item"><?php echo ($product->promo == 1) ? format_customer_price_text($product->promo_price, $product->promo_price_2, $product->promo_price_3)  : format_customer_price_text($product->price, $product->price_2, $product->price_3); ?>
                    <?php endif; ?>
                    <a class="btn btn-success" href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>">Beli</a>
                    </span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
      <?php endif; ?>

    </div>
  </section>
  <!-- Lowest Price End -->

  <!-- Everyday Essentials Start -->
  <section class="low-price-section pt-0">
    <div class="top-content">
      <div>
        <h4 class="title-color">Semua Produk</h4>
        <!--  <p class="content-color">Produk yang paling banyak dibeli</p> -->
      </div>
      <a href="all_products" class="font-theme">Lihat Semua</a>
    </div>

    <div class="row gy-3">
      <?php if (count($products) > 0) : ?>
        <?php foreach (array_slice($products, 0, 8) as $product) : ?>
          <div class="col-6 col-md-4 col-sm-6">
            <div class="product-card">

              <div class="img-wrap">
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>">
                  <img src="<?php echo get_product_image_url($product->picture_name); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo $product->name; ?>" />
                </a>
              </div>

              <div class="content-wrap">
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>" class="font-sm title-color"><?php echo $product->name; ?> </a>
                <?php if ($product->promo == 1) : ?>
                  <span class="title-color font-sm"><?php echo format_customer_price_text($product->promo_price, $product->promo_price_2, $product->promo_price_3); ?> <del><small> <?php echo format_customer_price_text($product->price, $product->price_2, $product->price_3); ?></small></del>
                  <?php else : ?>
                    <span class="title-color font-sm plus-item"><?php echo format_customer_price_text($product->price, $product->price_2, $product->price_3); ?>
                    <?php endif; ?>
                    <!-- <a class="btn btn-success" href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>">Beli</a> -->
                    </span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
      <?php endif; ?>
    </div>
  </section>
  <!-- Everyday Essentials End -->

</main>
<!-- Main End -->

<?php if (!empty($start_customer_tutorial)) : ?>
  <style>
    body.kiu-tour-running {
      overflow: hidden;
    }

    .kiu-tour-overlay {
      animation: kiuTourFadeIn 0.22s ease forwards;
      background: transparent;
      inset: 0;
      opacity: 0;
      pointer-events: none;
      position: fixed;
      z-index: 10000;
    }

    .kiu-tour-spotlight {
      animation: kiuTourPulse 1.4s ease-in-out infinite;
      border: 3px solid #ffffff;
      border-radius: 22px;
      box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.58), 0 0 22px rgba(65, 120, 255, 0.9);
      pointer-events: none;
      position: fixed;
      transition: all 0.28s ease;
      z-index: 10002;
    }

    .kiu-tour-card {
      animation: kiuTourPop 0.25s ease forwards;
      background: #ffffff;
      border-radius: 22px;
      box-shadow: 0 22px 60px rgba(0, 0, 0, 0.22);
      left: 50%;
      max-width: calc(100vw - 36px);
      opacity: 0;
      padding: 20px;
      position: fixed;
      transform: translate(-50%, 16px);
      transition: top 0.28s ease;
      width: 550px;
      z-index: 10003;
    }

    .kiu-tour-head {
      align-items: center;
      display: flex;
      gap: 12px;
      justify-content: space-between;
      margin-bottom: 12px;
    }

    .kiu-tour-title {
      color: #111827;
      font-size: 20px;
      font-weight: 800;
      line-height: 1.25;
      margin: 0;
    }

    .kiu-tour-count {
      color: #8b8f99;
      flex: 0 0 auto;
      font-size: 14px;
      font-weight: 800;
    }

    .kiu-tour-desc {
      color: #7a7f8b;
      font-size: 17px;
      line-height: 1.45;
      margin: 0 0 18px;
    }

    .kiu-tour-actions {
      align-items: center;
      display: flex;
      justify-content: space-between;
    }

    .kiu-tour-skip {
      background: transparent;
      border: 0;
      color: #8b8f99;
      font-size: 16px;
      font-weight: 800;
      padding: 10px 12px;
    }

    .kiu-tour-next {
      align-items: center;
      background: #3468df;
      border: 0;
      border-radius: 14px;
      color: #ffffff;
      display: inline-flex;
      font-size: 16px;
      font-weight: 800;
      gap: 10px;
      padding: 12px 18px;
    }

    .kiu-tour-next i {
      font-size: 14px;
    }

    .kiu-onboarding-splash[hidden] {
      display: none;
    }

    .kiu-onboarding-splash {
      align-items: center;
      background: linear-gradient(180deg, #ffffff 0%, #f5f9ff 60%, #eef8f4 100%);
      display: flex;
      inset: 0;
      justify-content: center;
      min-height: 100vh;
      overflow: hidden;
      padding: 28px;
      position: fixed;
      text-align: center;
      z-index: 10010;
    }

    .kiu-onboarding-splash::before {
      display: none;
    }

    .kiu-onboarding-splash::after {
      display: none;
    }

    .kiu-splash-content {
      align-items: center;
      display: flex;
      flex-direction: column;
      max-width: 420px;
      position: relative;
      width: 100%;
      z-index: 1;
    }

    .kiu-splash-logo-card {
      align-items: center;
      background: #ffffff;
      border: 1px solid rgba(12, 95, 219, 0.08);
      border-radius: 22px;
      box-shadow: 0 22px 58px rgba(30, 64, 175, 0.12);
      display: flex;
      height: 150px;
      justify-content: center;
      margin-bottom: 34px;
      padding: 28px;
      width: min(100%, 360px);
    }

    .kiu-splash-logo-card img {
      display: block;
      max-height: 88px;
      max-width: 100%;
      object-fit: contain;
    }

    .kiu-splash-icon {
      display: none;
    }

    .kiu-splash-icon.icon-bag {
      left: 4%;
      top: 104px;
    }

    .kiu-splash-icon.icon-leaf {
      color: #0b8f70;
      right: 3%;
      top: 78px;
    }

    .kiu-splash-icon.icon-cart {
      bottom: 164px;
      left: 8%;
    }

    .kiu-splash-icon.icon-spark {
      bottom: 186px;
      color: #f59e0b;
      right: 8%;
    }

    .kiu-splash-title {
      color: #0f3f8f;
      font-size: 36px;
      font-weight: 900;
      letter-spacing: 0;
      line-height: 1.18;
      margin: 0 0 24px;
    }

    .kiu-splash-desc {
      color: #64748b;
      font-size: 18px;
      font-weight: 600;
      line-height: 1.42;
      margin: 0 auto 34px;
      max-width: 440px;
    }

    .kiu-splash-redirect {
      align-items: center;
      background: #0c5fdb;
      border: 0;
      border-radius: 999px;
      box-shadow: 0 14px 30px rgba(12, 95, 219, 0.18);
      color: #ffffff;
      display: inline-flex;
      font-size: 17px;
      font-weight: 900;
      gap: 12px;
      justify-content: center;
      padding: 13px 24px;
      text-decoration: none;
    }

    .kiu-splash-redirect:hover,
    .kiu-splash-redirect:focus {
      color: #ffffff;
      text-decoration: none;
    }

    @keyframes kiuTourFadeIn {
      to {
        opacity: 1;
      }
    }

    @keyframes kiuTourPop {
      to {
        opacity: 1;
        transform: translate(-50%, 0);
      }
    }

    @keyframes kiuSplashFloat {
      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-8px);
      }
    }

    @keyframes kiuSplashPop {
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    @keyframes kiuTourPulse {
      0%,
      100% {
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.58), 0 0 20px rgba(65, 120, 255, 0.75);
      }

      50% {
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.58), 0 0 34px rgba(65, 120, 255, 1);
      }
    }

    @media (max-width: 576px) {
      .kiu-tour-card {
        border-radius: 18px;
        padding: 18px;
        width: calc(100vw - 48px);
      }

      .kiu-tour-title {
        font-size: 17px;
      }

      .kiu-tour-desc {
        font-size: 15px;
      }

      .kiu-onboarding-splash {
        padding: 22px;
      }

      .kiu-splash-logo-card {
        height: 128px;
        margin-bottom: 28px;
        width: min(100%, 310px);
      }

      .kiu-splash-title {
        font-size: 32px;
      }

      .kiu-splash-desc {
        font-size: 16px;
      }

      .kiu-splash-redirect {
        font-size: 15px;
        padding: 12px 20px;
      }
    }
  </style>

  <div class="kiu-tour-overlay" id="kiuTourOverlay"></div>
  <div class="kiu-tour-spotlight" id="kiuTourSpotlight"></div>
  <div class="kiu-tour-card" id="kiuTourCard" role="dialog" aria-live="polite" aria-label="Tutorial Customer">
    <div class="kiu-tour-head">
      <h2 class="kiu-tour-title" id="kiuTourTitle"></h2>
      <span class="kiu-tour-count" id="kiuTourCount"></span>
    </div>
    <p class="kiu-tour-desc" id="kiuTourDesc"></p>
    <div class="kiu-tour-actions">
      <button class="kiu-tour-skip" type="button" id="kiuTourSkip">Skip</button>
      <button class="kiu-tour-next" type="button" id="kiuTourNext">Lanjut <i class="fas fa-arrow-right"></i></button>
    </div>
  </div>
  <div class="kiu-onboarding-splash" id="kiuOnboardingSplash" aria-live="polite" hidden>
    <div class="kiu-splash-content">
      <span class="kiu-splash-icon icon-bag"><i class="fas fa-shopping-bag"></i></span>
      <span class="kiu-splash-icon icon-leaf"><i class="fas fa-leaf"></i></span>
      <span class="kiu-splash-icon icon-cart"><i class="fas fa-shopping-cart"></i></span>
      <span class="kiu-splash-icon icon-spark"><i class="fas fa-magic"></i></span>
      <div class="kiu-splash-logo-card">
        <img src="<?= get_theme_uri('images/logo/logo.png') ?>" alt="Karisma Online Shop">
      </div>
      <h2 class="kiu-splash-title">Selamat<br>Berbelanja</h2>
      <p class="kiu-splash-desc">Tutorial selesai. Temukan produk terbaik Karisma dan mulai belanja dari dashboard.</p>
      <a class="kiu-splash-redirect" id="kiuSplashRedirect" href="<?= site_url('home') ?>">
        <i class="fas fa-home"></i>
        <span>Mengarahkan ke dashboard</span>
      </a>
    </div>
  </div>

  <script>
    (function() {
      var steps = [{
          target: '[data-tour="search"]',
          title: 'Cari Produk',
          desc: 'Gunakan kolom pencarian untuk menemukan produk berdasarkan nama atau SKU dengan lebih cepat.'
        },
        {
          target: '[data-tour="menu-category"]',
          title: 'Kategori',
          desc: 'Gunakan Kategori untuk melihat produk berdasarkan jenis kebutuhan pertanian.'
        },
        {
          target: '[data-tour="menu-products"]',
          title: 'Produk',
          desc: 'Buka Produk untuk melihat daftar barang yang tersedia dan memilih item yang dibutuhkan.'
        },
        {
          target: '[data-tour="menu-promo"]',
          title: 'Promo',
          desc: 'Pantau Promo untuk melihat produk dengan penawaran khusus yang sedang aktif.'
        },
        {
          target: '[data-tour="menu-cart"]',
          title: 'Cart',
          desc: 'Cart menyimpan produk yang sudah dipilih sebelum Anda melanjutkan checkout.'
        },
        {
          target: '[data-tour="menu-invoice"]',
          title: 'Tagihan',
          desc: 'Gunakan Tagihan untuk mengecek informasi invoice dan kewajiban pembayaran customer.'
        },
        {
          target: '[data-tour="menu-history"]',
          title: 'Riwayat',
          desc: 'Riwayat membantu Anda memantau pesanan yang sudah pernah dibuat.'
        },
        {
          target: '[data-tour="menu-chat"]',
          title: 'Chat',
          desc: 'Gunakan Chat untuk bertanya atau berkoordinasi dengan tim Karisma.'
        },
        {
          target: '[data-tour="menu-setting"]',
          title: 'Setting',
          desc: 'Setting membawa Anda ke halaman Profile untuk mengatur data akun, alamat, dan password.'
        },
        {
          target: '[data-tour="footer-home"]',
          title: 'Beranda',
          desc: 'Navigasi Beranda mengembalikan Anda ke halaman utama aplikasi.'
        },
        {
          target: '[data-tour="footer-category"]',
          title: 'Kategori Footer',
          desc: 'Akses cepat ke halaman kategori juga tersedia di navigasi bawah.'
        },
        {
          target: '[data-tour="footer-cart"]',
          title: 'Keranjang',
          desc: 'Keranjang di navigasi bawah memudahkan pengecekan pesanan kapan saja.'
        },
        {
          target: '[data-tour="footer-history"]',
          title: 'Riwayat Order',
          desc: 'Gunakan Riwayat untuk membuka daftar order dari navigasi bawah.'
        },
        {
          target: '[data-tour="footer-chat"]',
          title: 'Chat Customer',
          desc: 'Chat di navigasi bawah menjadi jalur cepat untuk komunikasi bantuan.'
        },
        {
          target: '[data-tour="profile-avatar"]',
          title: 'Profile Customer',
          desc: 'Tekan avatar untuk membuka Profile, mengubah data customer, melihat tutorial, atau membuka guide book.'
        }
      ];

      var current = 0;
      var overlay = document.getElementById('kiuTourOverlay');
      var spotlight = document.getElementById('kiuTourSpotlight');
      var card = document.getElementById('kiuTourCard');
      var title = document.getElementById('kiuTourTitle');
      var desc = document.getElementById('kiuTourDesc');
      var count = document.getElementById('kiuTourCount');
      var next = document.getElementById('kiuTourNext');
      var skip = document.getElementById('kiuTourSkip');
      var splash = document.getElementById('kiuOnboardingSplash');
      var splashRedirect = document.getElementById('kiuSplashRedirect');
      var dashboardUrl = '<?= site_url('home') ?>';
      var splashTimer = null;

      function getVisibleElement(selector) {
        var nodes = document.querySelectorAll(selector);
        for (var i = 0; i < nodes.length; i++) {
          var rect = nodes[i].getBoundingClientRect();
          if (rect.width > 0 && rect.height > 0) {
            return nodes[i];
          }
        }

        return null;
      }

      function placeCard(rect) {
        var gap = 18;
        var cardHeight = card.offsetHeight || 210;
        var top = rect.bottom + gap;

        if (top + cardHeight > window.innerHeight - 18) {
          top = rect.top - cardHeight - gap;
        }

        if (top < 18) {
          top = Math.max(18, (window.innerHeight - cardHeight) / 2);
        }

        card.style.top = top + 'px';
      }

      function showStep(index) {
        current = Math.max(0, Math.min(index, steps.length - 1));

        var step = steps[current];
        var target = getVisibleElement(step.target);

        if (!target) {
          if (current < steps.length - 1) {
            showStep(current + 1);
          } else {
            closeTour();
          }
          return;
        }

        target.scrollIntoView({
          behavior: 'smooth',
          block: 'center',
          inline: 'center'
        });

        window.setTimeout(function() {
          var rect = target.getBoundingClientRect();
          var padding = 8;

          title.textContent = step.title;
          desc.textContent = step.desc;
          count.textContent = (current + 1) + ' dari ' + steps.length;
          next.innerHTML = current === steps.length - 1 ? 'Selesai' : 'Lanjut <i class="fas fa-arrow-right"></i>';

          spotlight.style.left = Math.max(8, rect.left - padding) + 'px';
          spotlight.style.top = Math.max(8, rect.top - padding) + 'px';
          spotlight.style.width = Math.min(window.innerWidth - 16, rect.width + padding * 2) + 'px';
          spotlight.style.height = Math.min(window.innerHeight - 16, rect.height + padding * 2) + 'px';
          spotlight.style.borderRadius = Math.min(26, Math.max(14, rect.height / 4)) + 'px';
          placeCard(rect);
        }, 280);
      }

      function closeTour() {
        document.body.classList.remove('kiu-tour-running');

        [overlay, spotlight, card].forEach(function(element) {
          if (element) {
            element.remove();
          }
        });

        if (window.history.replaceState) {
          window.history.replaceState(null, '', '<?= site_url('home') ?>');
        }
      }

      function redirectToDashboard() {
        window.location.replace(dashboardUrl);
      }

      function showCompletionSplash() {
        document.body.classList.remove('kiu-tour-running');
        document.removeEventListener('click', lockTutorialClicks, true);

        [overlay, spotlight, card].forEach(function(element) {
          if (element) {
            element.remove();
          }
        });

        if (splash) {
          splash.hidden = false;
        }

        if (window.history.replaceState) {
          window.history.replaceState(null, '', dashboardUrl);
        }

        splashTimer = window.setTimeout(redirectToDashboard, 5000);
      }

      function lockTutorialClicks(event) {
        if (!card || card.contains(event.target)) {
          return;
        }

        event.preventDefault();
        event.stopPropagation();
      }

      document.body.classList.add('kiu-tour-running');
      document.addEventListener('click', lockTutorialClicks, true);

      next.addEventListener('click', function() {
        if (current >= steps.length - 1) {
          showCompletionSplash();
          return;
        }

        showStep(current + 1);
      });

      skip.addEventListener('click', function() {
        closeTour();
        document.removeEventListener('click', lockTutorialClicks, true);
      });

      window.addEventListener('resize', function() {
        showStep(current);
      });

      if (splashRedirect) {
        splashRedirect.addEventListener('click', function(event) {
          event.preventDefault();
          if (splashTimer) {
            window.clearTimeout(splashTimer);
          }
          redirectToDashboard();
        });
      }

      window.setTimeout(function() {
        showStep(0);
      }, 450);
    })();
  </script>
<?php endif; ?>
