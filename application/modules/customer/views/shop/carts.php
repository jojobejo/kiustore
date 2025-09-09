<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<main class="main-wrap cart-page mb-xxl">
    <form action="<?php echo site_url('ongkir'); ?>" method="POST">

        <div class="checkout-wrapper-area py-3">
            <!-- Billing Address-->
            <div class="billing-information-card mb-3">

                <div class="card billing-information-title-card bg-danger">
                    <div class="card-body">
                        <h6 class="text-center mb-0 text-white">Cek Ongkir</h6>
                    </div>
                </div>

                <div class="card user-data-card">
                    <!-- Alamat -->

                    <div class="card-body">
                        <label class="form-label">Alamat Lengkap</label>
                        <input type="text" id="" name="" value="<?php echo $customer->shop_address; ?>" class="form-control" readonly>
                    </div>

                    <!-- Berat -->
                    <div class="card-body">
                        <label class="form-label">Berat Total (Kg)</label>
                        <input type="text" value="<?= ($weight / 1000) ?>" class="form-control" readonly>
                    </div>
                    <!-- Pilihan Ongkir -->
                    <?php if (!empty($ongkir)) : ?>
                        <div class="card-body">
                            <label class="form-label">Pilih Jasa Ekspedisi</label>
                            <select name="jasaongkir" id="jasaongkir" class="form-control" required>
                                <option value="">-- Pilih Jasa --</option>
                                <?php foreach ($ongkir as $c) : ?>
                                    <option value="<?= $c['name'] . ';' . $c['service'] . ';' . $c['description'] . ';' . $c['etd'] . ';' . $c['cost'] ?>" data-code="<?= $c['code']; ?>" data-cost="<?= $c['cost']; ?>" data-etd="<?= $c['etd']; ?>">
                                        <?= $c['name']; ?> - <?= $c['service']; ?> (<?= $c['description']; ?>) - Rp <?= number_format($c['cost'], 0, ',', '.'); ?> / Estimasi: <?= $c['etd']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <!-- hidden input -->
                            <input type="hidden" name="jasa" id="jasa" value="">
                            <input type="hidden" name="action" value="addongkir">
                            <input type="hidden" name="customer" value="<?= $this->session->userdata('user_id') ?>">
                            <?php foreach ($itm_cart as $itm) : ?>
                                <input type="hidden" name="kdfaktur" value="<?= $itm->kdchart ?>">
                            <?php endforeach; ?>
                        </div>

                    <?php else : ?>
                        <div class="card-body">
                            <div class="alert alert-warning">Jasa pengiriman tidak tersedia</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tombol Submit -->
            <?php if (!empty($ongkir)) : ?>
                <div class="card cart-amount-area mb-10">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <a href="<?= base_url('cart') ?>" class="btn btn-block btn-warning mt-2 w-100">Ekspedisi Lainya</a>
                        <a href="" class="ml-2"></a>
                        <button class="btn btn-block btn-primary mt-2 w-100" type="submit">Confirm Ekspedisi</button>
                    </div>
                </div>
            <?php else : ?>
                <div class="card cart-amount-area mb-10">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <a href="<?= base_url('cart') ?>" class="btn btn-block btn-warning mt-2 w-100">Jasa Tidak Tersedia</a>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </form>
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
                    $('#province').append('<option value="' + prov.id + '">' + prov.name + '</option>');
                });
            }, 'json');
        }

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById("jasaongkir");
        const inputCode = document.getElementById("jasa");

        select.addEventListener("change", function() {
            let selectedOption = this.options[this.selectedIndex];
            let code = selectedOption.getAttribute("data-code");
            let etd = selectedOption.getAttribute("data-etd");
            let cost = selectedOption.getAttribute("data-cost");
            inputCode.value = code;
        });
    });
</script>