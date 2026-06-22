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
<main class="main-wrap index-page mb-xxl">
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

    <div class="search-box mb-4">
      <i class="iconly-Search icli search"></i>
      <input class="form-control" type="search" value="<?php echo (isset($query) ? $query : ''); ?>" name="search_query" placeholder="Cari disini..." required autocomplete="off" />
    </div>
  </form>


  <!-- Everyday Essentials Start -->
  <section class="low-price-section pt-0 mt-3">
    <div class="top-content">
      <div>
        <h4 class="title-color">Kategori Produk</h4>
        <p class="content-color">Semua produk yang ada di kategori <?= $category; ?></p>
      </div>
    </div>

    <div class="row gy-3">

      <?php if (count($products) > 0) : ?>
        <?php foreach ($products as $product) : ?>
          <div class="col-6">
            <div class="product-card">
              <div class="img-wrap">
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>"><img src="<?php echo base_url('assets/uploads/products/' . $product->picture_name); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo $product->name; ?>" /> </a>
              </div>
              <div class="content-wrap">
                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>" class="font-sm title-color"><?php echo $product->name; ?> </a>
                <span class="title-color font-sm plus-item"><?php echo format_customer_price_text($product->price, null, null, false); ?>
                  <!-- <a class="btn btn-success btn-sm add-to-chart add-cart w-10" href="#" data-sku="<?php echo $product->sku; ?>" data-name="<?php echo $product->name; ?>" data-price="<?php echo ($product->current_discount > 0) ? ($product->price - $product->current_discount) : $product->price; ?>" data-id="<?php echo $product->id; ?>">Beli</a> -->
                </span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
      <?php endif; ?>
    </div>

    <?php if (!empty($pagination)) : ?>
      <?php echo $pagination; ?>
    <?php endif; ?>
  </section>
  <!-- Everyday Essentials End -->
</main>
<!-- Main End -->
