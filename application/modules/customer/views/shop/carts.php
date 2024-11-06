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
        <h3 class="mt-2 mb-2">Biaya : </h3><br>
        <?php foreach ($ckongkir->rajaongkir->results[0]->costs as $c) : ?>
            <h3 class="mt-2 mb-2"><?= $c->service ?> - (<?= $c->description ?>)</h3>
            <h3 class="mb-2"> Rp. <?= number_format($c->cost[0]->value) ?> (<?= $c->cost[0]->etd ?>) Hari</h3>
        <?php endforeach; ?>
    </div>
</main>