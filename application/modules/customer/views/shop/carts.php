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
                        <input type="text" value="<?php echo $customer->shop_address; ?>" class="form-control" readonly>
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
                                    <option value="<?= $c['service'] . ';' . $c['etd'] . ';' . $c['cost'] ?>" data-code="<?= $c['code']; ?>" data-cost="<?= $c['cost']; ?>" data-etd="<?= $c['etd']; ?>">
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
    document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById("jasaongkir");
        const inputCode = document.getElementById("jasa");

        select.addEventListener("change", function() {
            let selectedOption = this.options[this.selectedIndex];
            let code = selectedOption.getAttribute("data-code");
            let etd = selectedOption.getAttribute("data-etd");
            let cost = selectedOption.getAttribute("data-cost");
            inputCode.value = code + ";" + etd + ";" + cost;
        });
    });
</script>