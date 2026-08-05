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
            </div>

        </div>
    </div>

    <?php echo form_open_multipart('customer/profile/edit_name', 'class="custom-form"'); ?>
    <div class="input-box">
        <i data-feather="at-sign"></i>
        <input class="form-control" type="text" id="inputName" name="name" value="<?php echo set_value('name', $user->name); ?>">
    </div>
    <div class="input-box">
        <i class="iconly-Call icli"></i>
        <input class="form-control" type="text" id="inputHP" name="phone_number" value="<?php echo set_value('name', $user->phone_number); ?>">
    </div>
    <div class="input-box">
        <i data-feather="at-sign"></i>
        <input class="form-control" type="email" id="inputEmail" value="<?php echo set_value('name', $user->email); ?>">
    </div>
    <div class="input-box">
        <div class="title mb-2"><i class="lni lni-map-marker"></i><span class="badge bg-danger">Nama Toko</span></div>
        <input class="form-control" type="text" id="inputAddr" name="shop_name" value="<?php echo set_value('name', $user->shop_name); ?>">
    </div>
    <div class="input-box">
        <div class="title mb-2"><i class="lni lni-map-marker"></i><span class="badge bg-danger">Alamat Toko/Kios</span></div>
        <input class="form-control" type="text" id="inputAddr" name="shop_address" value="<?php echo set_value('name', $user->shop_address); ?>">
    </div>
    <div class="row">
        <div class="col">
            <a id="newButtonclose" class="btn btn-secondary mt-3 w-100" href="<?= base_url('profile') ?>">Batal</a>
        </div>
        <div class="col">
            <button type="submit" id="newButton" class="btn btn-success w-100">
                Simpan Perubahan
            </button>

        </div>
    </div>

    <?php if ($flash) : ?>
        <p class="text-center text-success"><?php echo $flash; ?></p>
    <?php endif; ?>

    </form>
    <!-- Form Section End -->
</main>
<!-- Main End -->