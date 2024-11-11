<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<main class="main-wrap cart-page mb-xxl">
    <div class="col">
        <h3 class="mb-2">Asal : <?= $ckongkir->rajaongkir->origin_details->city_name ?> , <?= $ckongkir->rajaongkir->origin_details->province ?> </h3>
        <h3 class="mb-2">Tujuan :<?= $ckongkir->rajaongkir->destination_details->city_name ?>,<?= $ckongkir->rajaongkir->destination_details->province ?> </h3>
        <h3 class="mb-2">Berat Total : <?= ($ckongkir->rajaongkir->query->weight / 1000) ?> Kg </h3>
        <h3 class="mb-2">Jasa yang digunakan : <?= $ckongkir->rajaongkir->results[0]->code ?> - <?= $ckongkir->rajaongkir->results[0]->name ?> </h3>
    </div>
    <div class="col">
        <h3 class="mt-2 mb-2">Pilih Pengiriman : </h3><br>
        <form action="<?php echo site_url('ongkir'); ?>" method="POST">
            <select name="jasaongkir" id="jasaongkir">
                <?php
                $now = date('Y-m-d');

                foreach ($ckongkir->rajaongkir->results[0]->costs as $c) : ?>
                    <option value="<?= $c->service . ';' . $c->cost[0]->etd . ';' . $c->cost[0]->value ?>"><?= $c->service ?> - (<?= $c->cost[0]->etd ?>) - Rp. <?= number_format($c->cost[0]->value) ?> </option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="action" id="action" value="addongkir" hidden>
            <input type="text" name="jasa" id="jasa" value="<?= $ckongkir->rajaongkir->results[0]->code ?>" hidden>
            <input type="text" name="customer" id="customer" value="<?= $this->session->userdata('user_id') ?>" hidden>
            <button class="btn btn-block btn-primary mt-2">save</button>
        </form>
    </div>
</main>