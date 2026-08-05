<select id="province" class="form-control"></select>
<select id="city" class="form-control"></select>
<select id="district" class="form-control"></select>
<select id="sub_district" class="form-control"></select>

<script>
    $(document).ready(function() {
        // Load provinces
        $.getJSON("<?= base_url('admin/provinces') ?>", function(res) {
            $('#province').append('<option value="">Pilih Provinsi</option>');
            res.data.forEach(p => $('#province').append(`<option value="${p.province_id}">${p.province}</option>`));
        });

        $('#province').change(function() {
            let prov_id = $(this).val();
            $('#city').empty();
            $('#district').empty();
            $('#sub_district').empty();
            if (!prov_id) return;
            $.getJSON("<?= base_url('ongkir/cities/') ?>" + prov_id, function(res) {
                $('#city').append('<option value="">Pilih Kota</option>');
                res.data.forEach(c => $('#city').append(`<option value="${c.city_id}">${c.city_name}</option>`));
            });
        });

        $('#city').change(function() {
            let city_id = $(this).val();
            $('#district').empty();
            $('#sub_district').empty();
            if (!city_id) return;
            $.getJSON("<?= base_url('ongkir/districts/') ?>" + city_id, function(res) {
                $('#district').append('<option value="">Pilih Kecamatan</option>');
                res.data.forEach(d => $('#district').append(`<option value="${d.district_id}">${d.district_name}</option>`));
            });
        });

        $('#district').change(function() {
            let district_id = $(this).val();
            $('#sub_district').empty();
            if (!district_id) return;
            $.getJSON("<?= base_url('ongkir/sub_districts/') ?>" + district_id, function(res) {
                $('#sub_district').append('<option value="">Pilih Kelurahan</option>');
                res.data.forEach(s => $('#sub_district').append(`<option value="${s.subdistrict_id}">${s.subdistrict_name}</option>`));
            });
        });
    });

    $.post("<?= base_url('ongkir/calculate_cost') ?>", {
        origin_subdistrict: "501", // contoh subdistrict origin
        destination_subdistrict: "502", // contoh subdistrict destination
        weight: 1000, // berat dalam gram
        courier: "jne"
    }, function(res) {
        console.log(res);
    }, 'json');
</script>