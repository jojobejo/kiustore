<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- Main Start -->
<main class="main-wrap product-page mb-xxl">
    <div class="card user-data-card">
        <div class="card-body">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Nama</label>
                <select name="kota" id="kota" class="form-control">
                    <option name="provinsi" value="0" disabled selected>--pilih provinsi--</option>
                    <?php if ($provinsi) : ?>
                        <?php foreach ($provinsi->rajaongkir->results as $prv) : ?>
                            <option name="provinsi" value="<?= $prv->province_id ?>"><?= $prv->province ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
</main>
<!-- Main End -->