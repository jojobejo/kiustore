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
    <!-- KETERANGAN ACTION 
    #1  = inputan awal alamat 
    #2  = edit profile all kecuali alamat 
    #3  = simpan inputan alamat
    #4  = edit inputan alamat 
    -->

    <?php if ($action == '1') : ?>

        <div class="mb-4 mt-2">
            <div class="input-box mb-2">
                <label class="form-label">Provinsi :</label>
                <select class="kiuselect form-control w-100" name="province" id="province"></select>
            </div>

            <div class="input-box mb-2">
                <label class="form-label">Kota/Kabupaten :</label>
                <select class="kiuselect form-control w-100" name="city" id="city"></select>
            </div>
            <div class="input-box mb-2">
                <label class="form-label">Kecamatan :</label>
                <select class="kiuselect form-control w-100" name="district" id="district"></select>
            </div>
            <!-- <div class="input-box mb-2">
                <label class="form-label">Kelurahan :</label>
                <select class="kiuselect form-control w-100" name="village" id="village"></select>
            </div> -->
            <div class="input-box mb-2">
                <label class="form-label">USER ID</label>
                <input type="text" class="form-control" name="idcust" id="idcust" value="<?= $user->id ?>">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label class="form-label w-100">provinsi_id :</label>
                <input type="text" id="pro_id" name="pro_id" value="">
            </div>
            <div class="col">
                <label class="form-label w-100">city_id :</label>
                <input type="text" id="kab_id" name="kab_id" value="">
            </div>
            <div class="col">
                <label class="form-label w-100">distric_id</label>
                <input type="text" id="kec_id" name="kec_id" value="">
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

    <?php elseif ($action == '2') : ?>
        
    <?php endif; ?>

    <?php if ($flash) : ?>
        <p class="text-center text-success"><?php echo $flash; ?></p>
    <?php endif; ?>
    </form>
</main>

<script>
    $(document).ready(function() {
        $('.kiuselect').select2({
            allowClear: true
        });

        // Load Provinsi
        function loadProvinces() {
            $.get("<?= base_url('rajaongkir/get_provinces') ?>", function(res) {
                $('#province').html('<option value="">Pilih Provinsi</option>');
                $.each(res.data, function(i, prov) {
                    $('#province').append('<option value="' + prov.id + '">' + prov.name + '</option>');
                });
            }, 'json');
        }

        // Jika Provinsi berubah → load Kota/Kabupaten
        $('#province').change(function() {
            let province = $(this).val();

            $('#pro_id').val(province);

            $('#city').html('<option value="">Pilih Kota/Kabupaten</option>');
            $('#district').html('<option value="">Pilih Kecamatan</option>');
            $('#kab_id').val('');
            $('#kec_id').val('');


            if (province) {
                $.get("<?= base_url('rajaongkir/get_cities') ?>", {
                    province_id: province
                }, function(res) {
                    $.each(res.data, function(i, city) {
                        $('#city').append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                }, 'json');
            }
        });

        // Jika Kota berubah → load Kecamatan
        $('#city').change(function() {
            let city_id = $(this).val();

            $('#kab_id').val(city_id);
            $('#district').html('<option value="">Pilih Kecamatan</option>');
            $('#kec_id').val('');

            if (city_id) {
                $.get("<?= base_url('rajaongkir/get_districts') ?>", {
                    city_id: city_id
                }, function(res) {
                    $.each(res.data, function(i, district) {
                        $('#district').append('<option value="' + district.id + '">' + district.name + '</option>');
                    });
                }, 'json');
            }
        });

        // Jika Kota berubah → load kecamatan (jika ada endpoint)
        $('#district').change(function() {
            let subdistrict_id = $(this).val();
            $('#kec_id').val(subdistrict_id);

            if (subdistrict_id) {
                $.get("<?= base_url('rajaongkir/') ?>", {
                    subdistrict_id: subdistrict_id
                }, function(res) {
                    $.each(res.data, function(i, village) {
                        $('#village').append('<option value="' + village.id + '">' + village.name + '</option>');
                    });
                }, 'json');
            }
        });

        // Jika Kecamatan berubah → load Kelurahan (jika ada endpoint)
        $('#subdistrict').change(function() {
            let subdistrict_id = $(this).val();
            $('#village').html('<option value="">Pilih Kelurahan</option>');

            if (subdistrict_id) {
                $.get("<?= base_url('rajaongkir/get_villages') ?>", {
                    subdistrict_id: subdistrict_id
                }, function(res) {
                    $.each(res.data, function(i, village) {
                        $('#village').append('<option value="' + village.id + '">' + village.name + '</option>');
                    });
                }, 'json');
            }
        });


        $("#selesai").on('click', function() {
            var pro_id = $("#pro_id").val().trim();
            var kab_id = $("#kab_id").val().trim();
            var kec_id = $("#kec_id").val().trim();

            if (pro_id === "" || kab_id === "" || kec_id === "") {
                alert("Harap isi semua field sebelum menyimpan.");
                return;
            }

            $.ajax({
                url: "<?= base_url('cus_edit_customer/3') ?>",
                type: "POST",
                data: {
                    pro_id: pro_id,
                    kab_id: kab_id,
                    kec_id: kec_id
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