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
                <span class="content-color font-md">
                    <button type="button" id="toggleReadonly" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></button>
                </span>
            </div>
        </div>
    </div>

    <?php echo form_open_multipart('customer/profile/edit_name', 'class="custom-form"'); ?>
    <div class="input-box">
        <i data-feather="at-sign"></i>
        <input class="form-control" type="text" id="inputName" name="name" value="<?php echo set_value('name', $user->name); ?>" readonly>
    </div>
    <div class="input-box">
        <i class="iconly-Call icli"></i>
        <input class="form-control" type="text" id="inputHP" name="phone_number" value="<?php echo set_value('name', $user->phone_number); ?>" readonly>
    </div>
    <div class="input-box">
        <i data-feather="at-sign"></i>
        <input class="form-control" type="email" id="inputEmail" value="<?php echo set_value('name', $user->email); ?>" disabled readonly>
    </div>

    <?php if ($user_loc->loc_sts == '0') : ?>

        <div class="input-box mb-2">
            <select name="city" id="city" class="form-control provinsi input-lg">
                <?php foreach ($kota->rajaongkir->results as $k) : ?>
                    <option value="<?= $k->province_id ?>"><?= $k->province ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- <a href="<?= base_url('change_alamat_customer_profile') ?>" class="btn btn-md btn-primary"></a> -->

        <div id="formlocation" style="display: none;">
            <div class="input-box">
                <div class="title mb-2"><i class="lni lni-map-marker"></i><span class="badge bg-danger">Alamat Rumah</span></div>
                <input class="form-control" type="text" id="inputAddr" name="address" value="<?php echo set_value('name', $user->address); ?>">
            </div>
            <button class="btn btn-primary mt-2" id="submitBtn" style="display: none;">Submit</button>
        </div>


    <?php else : ?>
        <div class="input-box">
            <div class="title mb-2"><i class="lni lni-map-marker"></i><span class="badge bg-danger">Alamat Rumah</span></div>
            <input class="form-control" type="text" id="inputAddr" name="address" value="<?php echo set_value('name', $user->address); ?>">
        </div>
        <div class="input-box">
            <div class="title mb-2"><i class="lni lni-map-marker"></i><span class="badge bg-danger">Nama Toko</span></div>
            <input class="form-control" type="text" id="inputAddr" name="shop_name" value="<?php echo set_value('name', $user->shop_name); ?>">
        </div>
        <div class="input-box">
            <div class="title mb-2"><i class="lni lni-map-marker"></i><span class="badge bg-danger">Alamat Toko / Alamat pengiriman</span></div>
            <input class="form-control" type="text" id="inputAddr" name="shop_address" value="<?php echo set_value('name', $user->shop_address); ?>">
        </div>
        <div class="input-box">
            <div class="title mb-2"><i class="lni lni-user"></i><span>Foto Profil</span></div>
            <input type="file" class="form-control" id="inputFoto" name="file">
        </div>
    <?php endif; ?>

    <a type="submit" id="newButton" class="btn btn-success w-100" style="display: none;">Simpan Perubahan</a>

    <?php if ($flash) : ?>
        <p class="text-center text-success"><?php echo $flash; ?></p>
    <?php endif; ?>
    </form>
    <!-- Form Section End -->
</main>
<!-- Main End -->

<script>
    $(document).ready(function() {
        $("#toggleReadonly").click(function() {
            var isReadonly = $("#inputName").prop("readonly") ? 0 : 1;

            $.ajax({
                url: "<?= site_url('readonlychange') ?>",
                type: "POST",
                data: {
                    readonly: isReadonly
                },
                success: function(response) {
                    // Toggle readonly pada semua input text dan email
                    $("input[type='text'], input[type='email']").each(function() {
                        $(this).prop("readonly", isReadonly);
                    });

                    // Jika readonly dimatikan, sembunyikan tombol toggle dan tampilkan tombol baru
                    if (isReadonly === 0) {
                        $("#toggleReadonly").hide();
                        $("#newButton").show();
                    }
                }
            });
        });
        
        $('#provinsi').select2({
            placeholder: "Pilih Provinsi",
        });

        $('#inptalamat').click(function() {
            $('#formlocation').toggle();
        });

        $('#inputAddr').on('input', function() {
            if ($(this).val().trim() !== '') {
                $('#submitBtn').show();
                $('#inptalamat').hide();
            } else if ($(this).val().trim() == '') {
                $('#inptalamat').show();
                $('#submitBtn').hide();
            } else {
                $('#submitBtn').hide();
            }

        });

        $('#submitBtn').click(function() {
            var address = $('#inputAddr').val();
            $.ajax({
                url: "<?php echo base_url('controller/method'); ?>", // Ganti dengan URL controller yang sesuai
                type: "POST",
                data: {
                    address: address
                },
                success: function(response) {
                    alert("Alamat berhasil disimpan!");
                },
                error: function() {
                    alert("Terjadi kesalahan, coba lagi.");
                }
            });
        });

    });
</script>