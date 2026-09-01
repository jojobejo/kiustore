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



    <!-- Everyday Essentials Start -->
    <section class="low-price-section pt-0 mt-3">
        <div class="top-content">
            <div>
                <h4 class="title-color">Promo Produk</h4>
                <!-- <p class="content-color">Semua produk yang ada di kategori</p> -->
            </div>
        </div>

        <div class="row gy-3">
            <?php if (count($products) > 0) : ?>
                <?php foreach (array_slice($products, 0, 8) as $product) : ?>
                    <div class="col-6">
                        <div class="product-card">
                            <div class="img-wrap">
                                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>"><img src="<?php echo get_product_image_url($product->picture_name); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo $product->name; ?>" /> </a>
                            </div>
                            <div class="content-wrap">
                                <a href="<?php echo site_url('product/' . $product->id . '/' . $product->sku . '/'); ?>" class="font-sm title-color"><?php echo $product->name; ?> </a>
                                <?php if ($product->promo == 1) : ?>
                                    <span class="title-color font-sm"><?php echo format_customer_price_text($product->promo_price, $product->promo_price_2, $product->promo_price_3); ?> <del><small> <?php echo format_customer_price_text($product->price, $product->price_2, $product->price_3); ?></small></del>
                                    <?php else : ?>
                                        <span class="title-color font-sm plus-item"><?php echo ($product->promo == 1) ? format_customer_price_text($product->promo_price, $product->promo_price_2, $product->promo_price_3)  : format_customer_price_text($product->price, $product->price_2, $product->price_3); ?>
                                        <?php endif; ?>

                                        <!-- <a class="btn btn-success btn-sm add-to-chart add-cart" href="#" data-sku="<?php echo $product->sku; ?>" data-name="<?php echo $product->name; ?>" data-price="<?php echo ($product->current_discount > 0) ? ($product->price - $product->current_discount) : $product->price; ?>" data-id="<?php echo $product->id; ?>">Beli</a> -->
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
