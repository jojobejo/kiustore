<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('format_customer_price_text')) {
  function format_customer_price_text($price, $price_2 = null, $price_3 = null, $use_get_price = true)
  {
    $formatted_price = $use_get_price ? get_price($price, $price_2, $price_3) : format_rupiah($price);
    $normalized_price = preg_replace('/[^0-9]/', '', (string) $formatted_price);

    if ($normalized_price === '999') {
      return 'silahkan hubungi admin';
    }

    return 'Rp ' . $formatted_price;
  }
}
?>

<!-- Main Start -->
<main class="main-wrap product-page mb-xxl">
  <!-- Banner Section Start -->
  <div class="banner-box product-banner">
    <div class="banner">
      <img src="<?php echo base_url('assets/uploads/products/' . $product->picture_name); ?>" alt="veg" />
    </div>
    <!-- <div class="banner">
          <img src="<?php echo base_url('assets/uploads/products/' . $product->picture_name); ?>" alt="veg" />
        </div>
        <div class="banner">
          <img src="<?php echo base_url('assets/uploads/products/' . $product->picture_name); ?>" alt="veg" />
        </div>
        <div class="banner">
          <img src="<?php echo base_url('assets/uploads/products/' . $product->picture_name); ?>" alt="veg" />
        </div> -->
  </div>
  <!-- Banner Section End -->

  <!-- Product Section Section Start -->
  <section class="product-section">

    <h1 class="font-md"><?php echo $product->name; ?></h1>

    <div class="price">
      <?php if ($product->promo == 1) : ?>
        <div class="price"><span><?php echo format_customer_price_text($product->promo_price, $product->promo_price_2, $product->promo_price_3); ?></span><del><small><?php echo format_customer_price_text($product->price, $product->price_2, $product->price_3); ?></small></del></div>
      <?php else : ?>
        <span><?php echo format_customer_price_text($product->price, $product->price_2, $product->price_3); ?></span>
      <?php endif; ?>

    </div>
    <?php if (!is_login()) : ?>
    <?php else : ?>
      <div class="select-group row">
        <input class="form-control qty-pd col-6" id="qty" type="number" value="1" />
        <div class="input-box satuan col-6">
          <div class="select-box">
            <select class="form-control satuan-pd" id="satuan">
              <option value="1"><?php echo $product->product_unit_1; ?></option>
              <?php if ($product->product_unit_2 && $product->product_unit_value != 0) : ?>
                <option value="2"><?php echo $product->product_unit_2; ?></option>
              <?php endif; ?>
            </select>
            <span><i data-feather="chevron-right"></i></span>
          </div>
        </div>
        <?php if (!is_login()) : ?>
          <!--<a class="btn btn-success btn-lg add-to-chart add-cart atc-pd col-12" href="<?= base_url('login') ?>">Beli</a>-->
        <?php else : ?>
          <a class="btn btn-success btn-lg add-to-chart add-cart atc-pd col-12" id="atc" href="#" data-sku="<?php echo $product->sku; ?>" data-name="<?php echo $product->name; ?>" data-price="<?php echo ($product->promo == 1) ?
                                                                                                                                                                                                  get_v_price($product->promo_price, $product->promo_price_2, $product->promo_price_3) :
                                                                                                                                                                                                  get_v_price($product->price, $product->price_2, $product->price_3); ?>" data-id="<?php echo $product->id; ?>" data-satuan-qty="<?php echo $product->product_unit_value; ?>" data-product-type="<?php echo $product->product_type; ?>" data-satuan="1" data-satuan-text="<?php echo $product->product_unit_1; ?>" data-qty="1" data-product-weight="<?php echo $product->product_unit_weight ?>">Beli</a>
        <?php endif; ?>
      <?php endif; ?>

      </div>


      <!-- Product Detail Start -->
      <div class="product-detail section-p-t">
        <div class="product-detail-box">
          <h2 class="title-color">Detail Produk</h2>
          <p class="content-color font-base"><?php echo nl2br($product->description); ?>
        </div>

      </div>
      <!-- Product Detail End -->
  </section>


  <!-- Lowest Price 2 Start -->
  <section class="recently-viewed">
    <div class="top-content">
      <div>
        <h4 class="title-color">Produk Lainnya</h4>
        <!-- <p class="font-xs content-color">Pay less, Get More</p> -->
      </div>
      <!-- <a href="#" class="font-xs font-theme">Lihat Semua</a> -->
    </div>
    <div class="product-slider">

      <?php if (count($related_products) > 0) : ?>
        <?php foreach (array_slice($related_products, 0, 3) as $product) : ?>
          <div>
            <div class="product-card">
              <div class="img-wrap">
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>"><img src="<?php echo base_url('assets/uploads/products/' . $product->picture_name); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo $product->name; ?>" /> </a>
              </div>
              <div class="content-wrap">
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>" class="font-sm title-color"><?php echo $product->name; ?> </a>

                <span class="title-color font-sm plus-item">
                  <?php if ($product->promo == 1) : ?>
                    <div class="price"><span><?php echo format_customer_price_text($product->promo_price, $product->promo_price_2, $product->promo_price_3); ?></span><del><small><?php echo format_customer_price_text($product->price, $product->price_2, $product->price_3); ?></small></del></div>
                  <?php else : ?>
                    <span><?php echo format_customer_price_text($product->price, $product->price_2, $product->price_3); ?></span>
                  <?php endif; ?>
                  <!-- <a class="btn btn-success btn-sm" href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>">Beli</a> -->

              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
      <?php endif; ?>

    </div>
  </section>
  <!-- Lowest Price 2 End -->
</main>
<!-- Main End -->
