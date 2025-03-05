<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<main class="main-wrap cart-page mb-xxl">
    <!-- Checkout Wrapper-->
    <?php if (is_members() == '1') : ?>
        <form action="<?php echo site_url('checkout_submit'); ?>" method="POST">
            <div class="checkout-wrapper-area py-3">
                <!-- Billing Address-->
                <div class="billing-information-card mb-3">
                    <div class="card billing-information-title-card bg-danger">
                        <div class="card-body">
                            <h6 class="text-center mb-0 text-white">Alamat Pengiriman</h6>
                        </div>
                    </div>
                    <div class="card user-data-card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nama</label>
                                <input type="text" name="name" value="<?php echo $customer->name; ?>" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">No. Telepon</label>
                                <input type="text" name="phone_number" value="<?php echo $customer->phone_number; ?>" class="form-control" id="hp" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Alamat Customer</label>
                                <textarea name="address" class="form-control" id="address" required><?php echo $customer->address; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nama Toko</label>
                                <textarea name="shop_name" class="form-control" id="shop_name" required><?php echo $customer->shop_name; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Alamat Toko / Alamat Pengiriman</label>
                                <textarea name="shop_address" class="form-control" id="shop_address" required><?php echo $customer->shop_address; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Catatan</label>
                                <textarea name="note" class="form-control" id="note"></textarea>
                            </div>
                            <?php foreach ($kdchart as $kd) : ?>
                                <input type="text" value="<?= $kd->kdchart ?>" name="kdfaktur" id="kdfaktur" hidden>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php foreach ($is_ongkir as $isongkir) : ?>
                    <?php if ($isongkir->ongkir == 0) : ?>
                        <div class="shipping-method-choose mb-3">
                            <div class="alert alert-info m-2">Metode Pembayaran</div>
                            <div class="card shipping-method-choose-card">
                                <div class="card-body">
                                    <div class="payment-method-choose">
                                        <div class="row">
                                            <!-- Net Banking Option Start -->
                                            <div class="input-box col-6">
                                                <input type="radio" name="payment" id="transfer" value="2" checked />
                                                <label class="form-check-label" for="cod">VA - KARISMA </label>
                                            </div>
                                            <!-- Net Banking Option End -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- shipping method choose-->
                        <div class="shipping-method-choose mb-3">
                            <div class="alert alert-info m-2">Metode Pengiriman</div>
                            <div class="card shipping-method-choose-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="input-box col-6">
                                            <input type="text" value="KARISMA" name="jns_shipping" id="jns_shipping" hidden>
                                            <input id="karisma" name="shipping" type="radio" value="1" checked>
                                            <label for="karisma">PT. Karisma Indoagro Universal</label>
                                            <div class="check"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <?php foreach ($jasa_ongkir as $j) :
                            $now = date('Y-m-d');
                            $jsongkir = explode(';', $j->jsongkir);
                            $expedisi = $j->sjasa;
                            if ($expedisi == "jne") {
                                $expedisi = 'JNE';
                            } elseif ($expedisi == "pos") {
                                $expedisi = 'POS INDONESIA';
                            } elseif ($expedisi == "tiki") {
                                $expedisi = 'TIKI';
                            }
                        ?>
                            <div hidden>
                                <input type="text" value="<?= $jsongkir['0'] ?>" name="jns_shipping" id="jns_shipping">
                                <input type="text" value="<?= $jsongkir['1'] ?> Hari" name="estimasi" id="estimasi">
                                <input type="text" value="<?= $jsongkir['2'] ?>" name="ongkirprice" id="ongkirprice">
                                <input type="text" value="<?= $j->kd_faktur ?>" name="kdfaktur" id="kdfaktur">
                            </div>
                            <!-- Payment Method Choose-->
                            <div class="shipping-method-choose mb-3">
                                <div class="alert alert-info m-2">Metode Pembayaran</div>
                                <div class="card shipping-method-choose-card">
                                    <div class="card-body">
                                        <div class="payment-method-choose">
                                            <div class="row">
                                                <div class="input-box col-6">
                                                    <input type="radio" name="payment" id="transfer" value="2" checked />
                                                    <label class="form-check-label" for="transfer">VA - KARISMA </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- shipping method choose-->
                            <div class="shipping-method-choose mb-3">
                                <div class="alert alert-info m-2">Metode Pengiriman</div>
                                <div class="card shipping-method-choose-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="input-box col-6">
                                                <input id="expiedisi" name="shipping" type="radio" value="5" checked>
                                                <label for="karisma"><?= $expedisi ?> - <?= $jsongkir['0'] ?> - Estimasi - <?= $jsongkir['1'] ?> (Rp. <?= number_format($jsongkir['2'], 0) ?>)</label>
                                                <div class="check"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>


                <!-- Cart Amount Area-->
                <div class="card cart-amount-area mb-10">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <!--    <h5 class="total-price mb-0">Rp <?php echo format_rupiah($total); ?></h5> -->
                        <input type="text" name="usrid" id="usrid" class="btn btn-warning" value="<?= $this->session->userdata('user_id') ?>" hidden>
                        <input type="submit" class="btn btn-warning w-100" value="Buat Pesanan">
                    </div>
                </div>
            </div>
        </form>
    <?php else : ?>
        <form action="<?php echo site_url('checkout_submit'); ?>" method="POST">
            <div class="checkout-wrapper-area py-3">
                <!-- Billing Address-->
                <div class="billing-information-card mb-3">
                    <div class="card billing-information-title-card bg-danger">
                        <div class="card-body">
                            <h6 class="text-center mb-0 text-white">Alamat Pengiriman</h6>
                        </div>
                    </div>
                    <div class="card user-data-card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nama</label>
                                <input type="text" name="name" value="<?php echo $customer->name; ?>" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">No. Telepon</label>
                                <input type="text" name="phone_number" value="<?php echo $customer->phone_number; ?>" class="form-control" id="hp" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Alamat Customer</label>
                                <textarea name="address" class="form-control" id="address" required><?php echo $customer->address; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nama Toko</label>
                                <textarea name="shop_name" class="form-control" id="shop_name" required><?php echo $customer->shop_name; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Alamat Toko / Alamat Pengiriman</label>
                                <textarea name="shop_address" class="form-control" id="shop_address" required><?php echo $customer->shop_address; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Catatan</label>
                                <textarea name="note" class="form-control" id="note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Payment Method Choose-->
                <div class="shipping-method-choose mb-3">
                    <div class="alert alert-info m-2">Metode Pembayaran</div>
                    <div class="card shipping-method-choose-card">
                        <div class="card-body">
                            <div class="payment-method-choose">
                                <div class="row">
                                    <!-- Net Banking Option Start -->
                                    <div class="input-box col-6">
                                        <input type="radio" name="payment" id="kredit" value="1" checked />
                                        <label class="form-check-label" for="cod">Kredit </label>
                                    </div>
                                    <div class="input-box col-6">
                                        <input type="radio" name="payment" id="transfer" value="2" />
                                        <label class="form-check-label" for="cod">VA - KARISMA </label>
                                    </div>
                                    <!-- Net Banking Option End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- shipping method choose-->
                <div class="shipping-method-choose mb-3">
                    <div class="alert alert-info m-2">Metode Pengiriman</div>
                    <div class="card shipping-method-choose-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="input-box col-6">
                                    <input id="karisma" name="shipping" type="radio" value="1" checked>
                                    <label for="karisma">PT. Karisma Indoagro Universal</label>
                                    <div class="check"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Amount Area-->
                <div class="card cart-amount-area mb-10">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <!--    <h5 class="total-price mb-0">Rp <?php echo format_rupiah($total); ?></h5> -->
                        <?php foreach ($kdchart as $kd) : ?>
                            <input type="text" value="<?= $kd->kdchart ?>" name="kdfaktur" id="kdfaktur" hidden>
                        <?php endforeach; ?>
                        <input type="text" name="usrid" id="usrid" class="btn btn-warning" value="<?= $this->session->userdata('user_id') ?>" hidden>
                        <input type="submit" class="btn btn-warning" value="Buat Pesanan">
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>

    </div>