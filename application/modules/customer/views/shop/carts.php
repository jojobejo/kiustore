<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<main class="main-wrap cart-page mb-xxl">
    <!-- Checkout Wrapper-->
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

                    <div class="card-body">
                        <div class="">
                            <label for="exampleFormControlInput1" class="form-label">Alamat Lengkap</label>
                            <input type="text" name="name" value="<?php echo $customer->shop_address; ?> , <?= $ckongkir->rajaongkir->origin_details->city_name ?>,<?= $ckongkir->rajaongkir->destination_details->province ?>" class="form-control" id="name" required readonly>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="">
                            <label for="exampleFormControlInput1" class="form-label">Berat Total</label>
                            <input type="text" name="name" value="<?= ($weight / 1000) ?>" class="form-control" id="name" required readonly>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="">
                            <label for="exampleFormControlInput1" class="form-label">Jasa Yang Digunakan</label>
                            <input type="text" name="name" value="<?= $ckongkir->rajaongkir->results[0]->code ?> - <?= $ckongkir->rajaongkir->results[0]->name ?>" class="form-control" id="name" required readonly>
                        </div>
                    </div>

                    <?php if (!empty($ckongkir->rajaongkir->results[0]->costs)) : ?>
                        <div class="card-body">
                            <div class="">
                                <label for="exampleFormControlInput1" class="form-label">Jasa Yang Digunakan</label>
                                <select name="jasaongkir" id="jasaongkir" class="form-control">
                                    <?php
                                    $now = date('Y-m-d');
                                    foreach ($ckongkir->rajaongkir->results[0]->costs as $c) : ?>
                                        <option value="<?= $c->service . ';' . $c->cost[0]->etd . ';' . $c->cost[0]->value ?>"><?= $c->service ?> - (<?= $c->cost[0]->etd ?>) - Rp. <?= number_format($c->cost[0]->value) ?> </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="action" id="action" value="addongkir" hidden>
                                <input type="text" name="jasa" id="jasa" value="<?= $ckongkir->rajaongkir->results[0]->code ?>" hidden>
                                <input type="text" name="customer" id="customer" value="<?= $this->session->userdata('user_id') ?>" hidden>
                                <?php foreach ($itm_cart as $itm) : ?>
                                    <input type="text" name="kdfaktur" id="kdfaktur" value="<?= $itm->kdchart ?>" hidden>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else : ?>
                    <?php endif; ?>

                </div>
            </div>
            <!-- Cart Amount Area-->
            <?php if (!empty($ckongkir->rajaongkir->results[0]->costs)) : ?>
                <div class="card cart-amount-area mb-10">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <button class="btn btn-block btn-primary mt-2" style="width: 100%;" type="submit">Confirm Ekspedisi</button>
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

</main>