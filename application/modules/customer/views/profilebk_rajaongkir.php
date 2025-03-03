<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biaya Pengiriman - RajaOngkir</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5.min.css" rel="stylesheet" />

    <!-- jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Cek Biaya Pengiriman</h2>

        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Provinsi Tujuan:</label>
                    <select id="province" class="form-select select2"></select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kota/Kabupaten Tujuan:</label>
                    <select id="city" class="form-select select2"></select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kecamatan Tujuan:</label>
                    <select id="subdistrict" class="form-select select2"></select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Berat Barang (gram):</label>
                    <input type="number" id="weight" class="form-control" min="1" value="1000">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kurir:</label>
                    <select id="courier" class="form-select">
                        <option value="jne">JNE</option>
                        <option value="pos">POS</option>
                        <option value="tiki">TIKI</option>
                    </select>
                </div>

                <button id="check_shipping" class="btn btn-primary w-100">Cek Ongkir</button>

                <h3 class="mt-4 text-center">Hasil Biaya Pengiriman</h3>
                <div id="shipping_result" class="alert alert-info mt-2 text-center"></div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 dengan tema Bootstrap 5
        $('.select2').select2({
            theme: 'bootstrap-4',
            placeholder: "Pilih opsi",
            allowClear: true
        });

        // Fungsi untuk load data provinsi
        function loadProvinces() {
            $.get("<?= base_url('rajaongkir/get_provinces') ?>", function(data) {
                $('#province').html('<option value="">Pilih Provinsi</option>');
                $.each(JSON.parse(data), function(i, prov) {
                    $('#province').append('<option value="'+ prov.province_id +'">'+ prov.province +'</option>');
                });
            });
        }

        // Load kota berdasarkan provinsi yang dipilih
        $('#province').change(function() {
            let province_id = $(this).val();
            $('#city, #subdistrict').html('<option value="">Pilih</option>');
            if (province_id) {
                $.get("<?= base_url('rajaongkir/get_cities') ?>", {province_id}, function(data) {
                    $.each(JSON.parse(data), function(i, city) {
                        $('#city').append('<option value="'+ city.city_id +'">'+ city.type + ' ' + city.city_name +'</option>');
                    });
                });
            }
        });

        // Load kecamatan berdasarkan kota yang dipilih
        $('#city').change(function() {
            let city_id = $(this).val();
            $('#subdistrict').html('<option value="">Pilih</option>');
            if (city_id) {
                $.get("<?= base_url('rajaongkir/get_subdistricts') ?>", {city_id}, function(data) {
                    $.each(JSON.parse(data), function(i, subdistrict) {
                        $('#subdistrict').append('<option value="'+ subdistrict.subdistrict_id +'">'+ subdistrict.subdistrict_name +'</option>');
                    });
                });
            }
        });

        // Cek biaya pengiriman
        $('#check_shipping').click(function() {
            let origin = 501; // ID Kecamatan Pengirim (sesuaikan)
            let destination = $('#subdistrict').val();
            let weight = $('#weight').val();
            let courier = $('#courier').val();

            $.post("<?= base_url('rajaongkir/get_shipping_cost') ?>", {origin, destination, weight, courier}, function(data) {
                let results = JSON.parse(data);
                $('#shipping_result').html('');
                $.each(results, function(i, result) {
                    $.each(result.costs, function(j, cost) {
                        $('#shipping_result').append('<p><strong>' + cost.service + '</strong>: Rp ' + cost.cost[0].value + ' (' + cost.cost[0].etd + ' hari)</p>');
                    });
                });
            });
        });

        loadProvinces();
    });
</script>
