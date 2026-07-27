<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Main Start -->
<main class="main-wrap setting-page mb-xxl">
    <div class="user-panel">
        <div class="media">
            <div class="avatar-wrap">
                <a href="javascript:void(0)"> <img src="<?= get_user_image(); ?>" alt="<?php echo get_user_name(); ?>"></a>
            </div>
            <div class="media-body">
                <h2 class="title-color"><?php echo get_user_name(); ?></h2>
                <!--  <span class="content-color font-md">Batas Kredit : <?php echo get_user_max_credit(); ?></span> -->
            </div>
        </div>
    </div>
    <!-- <?= print_r($kota) ?> -->
    <!-- Form Section Start -->
    <div class="input-box mb-2">
        <select name="city" id="city" class="form-control provinsi_customer_edit input-lg">
            <?php foreach ($kota->rajaongkir->results as $k) : ?>
                <option value="<?= $k->province_id ?>"><?= $k->province ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="input-box mb-2">
        <select name="city" id="city" class="form-control provinsi_customer input-lg">
            <?php foreach ($kota->rajaongkir->results as $k) : ?>
                <option value="<?= $k->province_id ?>"><?= $k->province ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="input-box mb-2">
        <select name="city" id="city" class="form-control provinsi input-lg">
            <?php foreach ($kota->rajaongkir->results as $k) : ?>
                <option value="<?= $k->province_id ?>"><?= $k->province ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- Form Section End -->
</main>
<!-- Main End -->