<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">

            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Users</h3>
                </div>

                <div class="card-body ">
                    <form id="form-alamat" onsubmit="return false;">

                        <div class="row">

                            <!-- PROVINSI -->
                            <div class="col-md-4 mb-3">
                                <label>Provinsi</label>
                                <select id="province" class="form-control kiuselect">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>

                            <!-- KOTA / KABUPATEN -->
                            <div class="col-md-4 mb-3">
                                <label>Kota / Kabupaten</label>
                                <select id="city" class="form-control kiuselect">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>

                            <!-- KECAMATAN -->
                            <div class="col-md-4 mb-3">
                                <label>Kecamatan</label>
                                <select id="district" class="form-control kiuselect">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="row mt-3">

                                <!-- EKSPEDISI -->
                                <div class="col-md-4 mb-3">
                                    <label>Ekspedisi</label>
                                    <select id="courier" class="form-control kiuselect">
                                        <option value="">Pilih Ekspedisi</option>
                                        <option value="jne">JNE</option>
                                        <option value="pos">POS Indonesia</option>
                                        <option value="tiki">TIKI</option>
                                    </select>
                                </div>

                                <!-- BERAT TOTAL -->
                                <div class="col-md-4 mb-3">
                                    <label>Total Berat (gram)</label>
                                    <input type="number" id="total_weight" class="form-control" placeholder="Masukkan berat (gram)" min="1">
                                </div>


                                <!-- BUTTON HITUNG ONGKIR -->
                                <div class="col-md-4 mb-3 d-flex align-items-end">
                                    <button type="button" id="btn-cek-ongkir" class="btn btn-success w-100">
                                        Hitung Ongkir
                                    </button>
                                </div>

                            </div>

                            <div class="mt-3" id="hasil-ongkir"></div>


                        </div>

                        <!-- HIDDEN FIELD (SINKRON DENGAN SCRIPT) -->
                        <input type="text" id="pro_id" name="pro_id">
                        <input type="text" id="pro_name" name="pro_name">

                        <input type="text" id="kab_id" name="kab_id">
                        <input type="text" id="kab_name" name="kab_name">

                        <input type="text" id="kec_id" name="kec_id">
                        <input type="text" id="kec_name" name="kec_name">

                        <div class="mt-3 text-right">
                            <button type="button" id="selesai" class="btn btn-primary">
                                Simpan Alamat
                            </button>
                        </div>

                    </form>


                    <div class="mt-3">
                        <button id="btn-cek" class="btn btn-primary btn-sm">Hitung Ongkir</button>
                    </div>

                    <div class="mt-4" id="hasil"></div>

                </div>
            </div>


        </div>
    </div>
</div>

<link href="<?php echo get_theme_uri('vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css', 'argon'); ?>" rel="stylesheet">

<script src="<?php echo get_theme_uri('vendor/datatables.net/js/jquery.dataTables.min.js', 'argon'); ?>"></script>
<script src="<?php echo get_theme_uri('vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js', 'argon'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables.lang.js'); ?>"></script>


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

        // Hitung Ongkir
        $('#btn-cek-ongkir').on('click', function() {

            let kec_id = $('#kec_id').val();
            let courier = $('#courier').val();
            let weight = $('#total_weight').val();


            if (kec_id === '' || courier === '' || weight === '' || weight <= 0) {
                alert('Alamat, ekspedisi, dan berat wajib diisi');
                return;
            }


            $('#hasil-ongkir').html('Menghitung ongkir...');

            $.ajax({
                url: "<?= base_url('rajaongkir/ajax_hitung_ongkir') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    kec_id: kec_id,
                    courier: courier,
                    weight: weight
                },

                success: function(res) {

                    if (res.status === 'empty') {
                        $('#hasil-ongkir').html(
                            '<div class="alert alert-warning">Kurir tersedia, tapi tidak ada layanan ke alamat ini</div>'
                        );
                        return;
                    }

                    if (res.status !== 'success') {
                        $('#hasil-ongkir').html(
                            '<div class="alert alert-warning">Ongkir tidak tersedia</div>'
                        );
                        return;
                    }

                    // render table
                },
                error: function() {
                    $('#hasil-ongkir').html(
                        '<div class="alert alert-danger">Koneksi ke server gagal</div>'
                    );
                }
            });
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