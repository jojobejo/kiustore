<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
        <div class="price"><span>Rp <?php echo get_price($product->promo_price, $product->promo_price_2, $product->promo_price_3); ?></span><del><small>Rp <?php echo get_price($product->price, $product->price_2, $product->price_3); ?></small></del></div>
      <?php else : ?>
        <span>Rp <?php echo get_price($product->price, $product->price_2, $product->price_3); ?></span>
      <?php endif; ?>

    </div>
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

      <a class="btn btn-success btn-lg add-to-chart add-cart atc-pd col-12" id="atc" href="#" data-sku="<?php echo $product->sku; ?>" data-name="<?php echo $product->name; ?>" data-price="<?php echo ($product->promo == 1) ? get_v_price($product->promo_price, $product->promo_price_2, $product->promo_price_3) : get_v_price($product->price, $product->price_2, $product->price_3); ?>" data-id="<?php echo $product->id; ?>" data-satuan-qty="<?php echo $product->product_unit_value; ?>" data-product-type="<?php echo $product->product_type; ?>" data-satuan="1" data-satuan-text="<?php echo $product->product_unit_1; ?>" data-qty="1" data-product-weight="<?php echo $product->product_unit_weight ?>">Beli</a>

    </div>


    <!-- Product Detail Start -->
    <div class="product-detail section-p-t">
      <div class="product-detail-box">
        <h2 class="title-color">Detail Produk</h2>
        <p class="content-color font-base"><?php echo $product->description; ?>
      </div>

    </div>
    <!-- Product Detail End -->
  </section>
  <!-- Product Section Section End -->

  <!-- Product Review Section Start -->
  <!--  <section class="product-review pb-0">
        <div class="top-content">
          <h3 class="title-color">Ulasan Produk(15)</h3>
          <a href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#all-review" class="font-xs">Lihat Semua</a>
        </div>
        <div class="review-wrap">
          <div class="review-box">
            <div class="media">
              <img src="<?php echo get_theme_uri('images/avatar/avatar.jpg'); ?>" alt="avatar" />
              <div class="media-body">
                <h4 class="font-sm title-color">Andrea Joanne</h4>
                <div class="rating">
                  <i data-feather="star"></i>
                  <i data-feather="star"></i>
                  <i data-feather="star"></i>
                  <i data-feather="star"></i>
                  <i data-feather="star"></i>
                </div>
              </div>
            </div>
            <p class="font-sm content-color">Produk Bagus, pengiriman cepat</p>
          </div>
          <div class="review-box">
            <div class="media">
              <img src="<?php echo get_theme_uri('images/avatar/avatar.jpg'); ?>" alt="avatar" />
              <div class="media-body">
                <h4 class="font-sm title-color">Andrea Joanne</h4>
                <div class="rating">
                  <i data-feather="star"></i>
                  <i data-feather="star"></i>
                  <i data-feather="star"></i>
                  <i data-feather="star"></i>
                  <i data-feather="star"></i>
                </div>
              </div>
            </div>
            <p class="font-sm content-color">Toppppppp....</p>
          </div>
        </div>
      </section>
      <!-- Product Review Section End -->

  <!-- <section class="check-delivery-section">
        <div class="title-section">
          <h4>Check Delivery</h4>
          <p class="font-xs content-color">Enter Pincode to check delivery date / pickup option</p>
        </div>
        <div class="custom-form">
          <input class="form-control" type="number" placeholder="Pin code" />
          <a href="javascript:void(0)">Check</a>
        </div>
        <div class="service-section">
          <ul>
            <li class="font-sm content-color"><img src="<?php echo get_theme_uri('icons/svg/delivery.svg'); ?>" class="img-fluid" alt="" />Free Delivery on order above $200.00</li>
            <li class="font-sm content-color"><img src="<?php echo get_theme_uri('icons/svg/payment.svg'); ?>" class="img-fluid" alt="" />Cash On delivery Available</li>
            <li class="font-sm content-color"><img src="<?php echo get_theme_uri('icons/svg/refund.svg'); ?>" class="img-fluid" alt="" />Easy 21 days returns and exchanges</li>
          </ul>
        </div>
      </section> -->

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
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>"><img src="<?php echo base_url('assets/uploads/products/' . $product->picture_name); ?>" class="img-fluid" alt="<?php echo $product->name; ?>" /> </a>
              </div>
              <div class="content-wrap">
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>" class="font-sm title-color"><?php echo $product->name; ?> </a>

                <span class="title-color font-sm plus-item">
                  <?php if ($product->promo == 1) : ?>
                    <div class="price"><span>Rp <?php echo get_price($product->promo_price, $product->promo_price_2, $product->promo_price_3); ?></span><del><small>Rp <?php echo get_price($product->price, $product->price_2, $product->price_3); ?></small></del></div>
                  <?php else : ?>
                    <span>Rp <?php echo get_price($product->price, $product->price_2, $product->price_3); ?></span>
                  <?php endif; ?>
                  <a class="btn btn-success btn-sm" href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>">Beli</a>

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