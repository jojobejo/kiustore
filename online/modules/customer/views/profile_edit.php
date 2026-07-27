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
                <a href="javascript:void(0)">
                    <img src="<?= get_user_image(); ?>" alt="<?php echo get_user_name(); ?>">
                </a>
            </div>
            <div class="media-body">
                <h2 class="title-color"><?php echo get_user_name(); ?></h2>
            </div>
        </div>
    </div>

    <?php if ($action == '1') : ?>

        <div class="mb-4 mt-2">
            <div class="input-box mb-2">
                <label class="form-label">Provinsi :</label>
                <select class="kiuselect form-control w-100" name="province" id="province"></select>
                <input type="text" class="form-control mt-2" id="pro_name" name="pro_name" placeholder="Nama Provinsi" readonly hidden>
                <input type="hidden" id="pro_id" name="pro_id">
            </div>

            <div class="input-box mb-2">
                <label class="form-label">Kota/Kabupaten :</label>
                <select class="kiuselect form-control w-100" name="city" id="city"></select>
                <input type="text" class="form-control mt-2" id="kab_name" name="kab_name" placeholder="Nama Kota/Kabupaten" readonly hidden>
                <input type="hidden" id="kab_id" name="kab_id">
            </div>

            <div class="input-box mb-2">
                <label class="form-label">Kecamatan :</label>
                <select class="kiuselect form-control w-100" name="district" id="district"></select>
                <input type="text" class="form-control mt-2" id="kec_name" name="kec_name" placeholder="Nama Kecamatan" readonly hidden>
                <input type="hidden" id="kec_id" name="kec_id">
            </div>

            <div class="input-box mb-2" hidden>
                <label class="form-label">USER ID</label>
                <input type="text" class="form-control" name="idcust" id="idcust" value="<?= $user->id ?>">
            </div>
        </div>

        <div class="row mt-2 mb-2">
            <div class="col">
                <a id="newButtonclose" class="btn btn-secondary w-100" href="<?= base_url('profile') ?>">Batal</a>
            </div>
            <div class="col">
                <button type="button" id="selesai" class="btn btn-success w-100">Simpan Perubahan</button>
            </div>
        </div>

    <?php endif; ?>

    <?php if ($flash) : ?>
        <p class="text-center text-success"><?php echo $flash; ?></p>
    <?php endif; ?>
</main>

<script>
    $(document).ready(function() {
        $('.kiuselect').select2({
            allowClear: true
        });

        function loadProvinces() {
            $.get("<?= base_url('rajaongkir/get_provinces') ?>", function(res) {
                $('#province').html('<option value="">Pilih Provinsi</option>');
                $.each(res.data, function(i, prov) {
                    $('#province').append('<option value="' + prov.id + '" data-name="' + prov.name + '">' + prov.name + '</option>');
                });
            }, 'json');
        }

        $('#province').change(function() {
            let province = $(this).val();
            let province_name = $('#province option:selected').data('name') || '';

            $('#pro_id').val(province);
            $('#pro_name').val(province_name);

            $('#city').html('<option value="">Pilih Kota/Kabupaten</option>');
            $('#district').html('<option value="">Pilih Kecamatan</option>');
            $('#kab_id, #kab_name, #kec_id, #kec_name').val('');

            if (province) {
                $.get("<?= base_url('rajaongkir/get_cities') ?>", {
                    province_id: province
                }, function(res) {
                    $.each(res.data, function(i, city) {
                        $('#city').append('<option value="' + city.id + '" data-name="' + city.name + '">' + city.name + '</option>');
                    });
                }, 'json');
            }
        });

        $('#city').change(function() {
            let city_id = $(this).val();
            let city_name = $('#city option:selected').data('name') || '';

            $('#kab_id').val(city_id);
            $('#kab_name').val(city_name);

            $('#district').html('<option value="">Pilih Kecamatan</option>');
            $('#kec_id, #kec_name').val('');

            if (city_id) {
                $.get("<?= base_url('rajaongkir/get_districts') ?>", {
                    city_id: city_id
                }, function(res) {
                    $.each(res.data, function(i, district) {
                        $('#district').append('<option value="' + district.id + '" data-name="' + district.name + '">' + district.name + '</option>');
                    });
                }, 'json');
            }
        });

        $('#district').change(function() {
            let subdistrict_id = $(this).val();
            let subdistrict_name = $('#district option:selected').data('name') || '';

            $('#kec_id').val(subdistrict_id);
            $('#kec_name').val(subdistrict_name);
        });

        $("#selesai").on('click', function() {
            var pro_id = $("#pro_id").val().trim();
            var pro_name = $("#pro_name").val().trim();
            var kab_id = $("#kab_id").val().trim();
            var kab_name = $("#kab_name").val().trim();
            var kec_id = $("#kec_id").val().trim();
            var kec_name = $("#kec_name").val().trim();

            if (pro_id === "" || kab_id === "" || kec_id === "") {
                alert("Harap isi semua field sebelum menyimpan.");
                return;
            }

            $.ajax({
                url: "<?= base_url('cus_edit_customer/3') ?>",
                type: "POST",
                data: {
                    pro_id: pro_id,
                    pro_name: pro_name,
                    kab_id: kab_id,
                    kab_name: kab_name,
                    kec_id: kec_id,
                    kec_name: kec_name
                },
                dataType: "JSON",
                cache: false,
                success: function(response) {
                    if (response.status === "success") {
                        alert("Data berhasil diperbarui!");
                        window.location.href = "<?= base_url('profile') ?>";
                    } else {
                        alert("Gagal menyimpan data. Silakan coba lagi.");
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan. Silakan coba lagi.");
                }
            });
        });

        loadProvinces();
    });
</script>