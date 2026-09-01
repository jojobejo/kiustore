<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Page content -->
<div class="container-fluid mt--6">
    <section class="low-price-section pt-0">

        <form id="navbar-search-main" action="<?php echo site_url('search'); ?>" required>
            <div class="search-box mb-4">
                <i class="iconly-Search icli search"></i>
                <input class="form-control" type="search" value="<?php echo (isset($query) ? $query : ''); ?>" name="search_query" placeholder="Cari disini..." required />
            </div>
        </form>
        <div class="top-content">
            <div>
                <h4 class="title-color">Cari Produk</h4>
                <p class="content-color">Menampilkan <?php echo $count; ?> hasil pencarian dengan kata kunci "<b><?php echo $query; ?></b>"</p>
            </div>
        </div>

        <?php
        if (!function_exists('format_search_price_text')) {
            function format_search_price_text($price, $price_2, $price_3)
            {
                $formatted_price = format_rupiah(get_price($price, $price_2, $price_3));
                $normalized_price = preg_replace('/[^0-9]/', '', (string) $formatted_price);

                if ($normalized_price === '999') {
                    return 'silahkan hubungi admin';
                }

                return 'Rp ' . $formatted_price;
            }
        }
        ?>

        <div class="row gy-3">

            <?php if (count($products) > 0) : ?>
                <?php foreach (array_slice($products, 0, 8) as $product) : ?>
                    <div class="col-6">
                        <div class="product-card">
                            <div class="img-wrap">
                                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>"><img src="<?php echo get_product_image_url($product->picture_name); ?>" class="img-fluid" alt="<?php echo $product->name; ?>" /> </a>
                            </div>
                            <div class="content-wrap">
                                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>" class="font-sm title-color"><?php echo $product->name; ?> </a>
                                <?php if ($product->promo == 1) : ?>
                                    <span class="title-color font-sm"><?php echo format_search_price_text($product->promo_price, $product->promo_price_2, $product->promo_price_3); ?> <del><small> <?php echo format_search_price_text($product->price, $product->price_2, $product->price_3); ?></small></del>
                                    <?php else : ?>
                                        <span class="title-color font-sm plus-item"><?php echo format_search_price_text($product->price, $product->price_2, $product->price_3); ?>
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
