<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
    /* Mengubah border input dan Select2 menjadi hitam */
    .form-control,
    .select2-container--bootstrap-5 .select2-selection {
        border: 2px solid black !important;
    }

    /* Tambahkan efek saat fokus */
    .form-control:focus,
    .select2-container--bootstrap-5 .select2-selection:focus {
        border-color: black !important;
        box-shadow: none !important;
    }

    /* Mengatur tinggi Select2 agar sesuai dengan Bootstrap */
    .select2-container--bootstrap-5 .select2-selection {
        height: 38px !important;
        display: flex;
        align-items: center;
    }
</style>

<!-- Main Start -->
<main class="main-wrap setting-page mb-xxl">
    <div class="user-panel">
        <div class="media">
            <div class="avatar-wrap">
                <a href="javascript:void(0)"> <img src="<?= get_user_image(); ?>" alt="<?php echo get_user_name(); ?>"></a>
            </div>
            <div class="media-body">
                <h2 class="title-color"><?php echo get_user_name(); ?></h2>
                <span class="content-color font-md">
                </span>
            </div>
        </div>
    </div>

    <?php if ($action == '1') : ?>
        <!-- <?php echo form_open_multipart('customer/profile/edit_name', 'class="custom-form"'); ?> -->
        <div class="mb-4 mt-2">
            <div class="input-box mb-2">
                <label class="form-label">Provinsi :</label>
                <select class="kiuselect form-control" name="province" id="province"></select>

            </div>
            <div class="input-box mb-2">
                <label class="form-label">Kota/Kabupaten :</label>
                <select class="kiuselect form-control" name="city" id="city"></select>

            </div>
            <div class="input-box mb-2">
                <label class="form-label">Kecamatan :</label>
                <select class="kiuselect form-control" name="subdistrict" id="subdistrict"></select>

            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="form-label">provinsi_id :</label>
                <input type="text" id="pro_id" name="pro_id" value="">
            </div>
            <div class="col">
                <label class="form-label">city_id :</label>
                <input type="text" id="kab_id" name="kab_id" value="">
            </div>
            <div class="col">
                <label class="form-label">subdistric_id</label>
                <input type="text" id="kec_id" name="kec_id" value="">
            </div>
        </div>

        <div class="row mt-2 mb-2">
            <div class="col">
                <a id="newButtonclose" class="btn btn-secondary w-100" href="<?= base_url('profile') ?>">Batal</a>
            </div>
            <div class="col">
                <a type="submit" id="newButton" class="btn btn-success w-100">Simpan Perubahan</a>
            </div>
        </div>

    <?php elseif ($action == '2') : ?>
    <?php endif; ?>

    <?php if ($flash) : ?>
        <p class="text-center text-success"><?php echo $flash; ?></p>
    <?php endif; ?>
    </form>
    <!-- Form Section End -->
</main>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2 dengan tema Bootstrap 5
        $('#select_option').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        $('.kiuselect').select2({
            allowClear: true
        });

        // Fungsi untuk load data provinsi
        function loadProvinces() {
            $.get("<?= base_url('rajaongkir/get_provinces') ?>", function(data) {
                $('#province').html('<option value="">Pilih Provinsi</option>');
                $.each(JSON.parse(data), function(i, prov) {
                    $('#province').append('<option value="' + prov.province_id + '">' + prov.province + '</option>');
                });

            });
        }

        // Load kota berdasarkan provinsi yang dipilih
        $('#province').change(function() {
            let province_id = $(this).val();

            $('#city, #subdistrict').html('<option value="">Pilih</option>');
            if (province_id) {
                $.get("<?= base_url('rajaongkir/get_cities') ?>", {
                    province_id
                }, function(data) {
                    $.each(JSON.parse(data), function(i, city) {
                        $('#city').append('<option value="' + city.city_id + '">' + city.type + ' ' + city.city_name + '</option>');
                        $('#pro_id').val(province_id);
                    });
                });
            }
        });

        $('#city').change(function() {
            let city_id = $(this).val();
            $('#subdistrict').html('<option value="">Pilih</option>');
            $('#subdistrict_id').val(''); // Reset input hidden

            if (city_id) {
                $.get("<?= base_url('rajaongkir/get_subdistricts') ?>", {
                    city_id
                }, function(data) {
                    $.each(JSON.parse(data), function(i, subdistrict) {
                        $('#subdistrict').append('<option value="' + subdistrict.subdistrict_id + '">' + subdistrict.subdistrict_name + '</option>');
                        $('#kab_id').val(city_id);
                    });
                });
            }
        });

        $('#subdistrict').change(function() {
            let subdistrict_id = $(this).val();
            $('#kec_id').val(subdistrict_id); // Set value input hidden
        });

        loadProvinces();

    });
</script>