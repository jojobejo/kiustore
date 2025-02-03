<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Main Start -->
<main class="main-wrap index-page mb-xxl">



    <!-- Everyday Essentials Start -->
    <section class="low-price-section pt-0 mt-2 mb-5">

        <div class="row gy-3">
            <?php echo form_open_multipart('customer/payments/do_confirm'); ?>
            <div class="mb-3">
                <div class="title mb-2"><span>Order</span></div>
                <input type="hidden" name="order_id" class="form-control" id="orders" value="<?= $orders->id; ?>">
                <input type="text" class="form-control" readonly value="<?= $orders->order_number . ' (Rp ' . format_rupiah($orders->final_price) . ')'; ?>">
            </div>
            <div class="mb-3">
                <div class="title mb-2"><span>Virtual Account - Payment</span></div>
                <input type="text" class="form-control" readonly value="<?= $customer->va_code ?>">
            </div>
            <div class="mb-3">
                <div class="title mb-2"><span>Cara Pembayaran</span></div>
            </div>
            <div>
                <div class="mb-3">
                    <div class="title mb-2"><span>Order</span></div>
                    <input type="hidden" name="order_id" class="form-control" id="orders" value="<?= $orders->id; ?>">
                    <input type="text" class="form-control" readonly value="<?= $orders->order_number . ' (Rp ' . format_rupiah($orders->final_price) . ')'; ?>">
                </div>
                <div class="mb-3">
                    <div class="title mb-2"><span>Nama Bank</span></div>
                    <!-- <input type="text" name="bank_name" value="<?php echo set_value('bank_name'); ?>" class="form-control" id="bank_name" required> -->
                    <input type="text" name="bank_name" value="VA-BRI" class="form-control" id="bank_name" required>
                    <?php echo form_error('bank_name'); ?>
                </div>
                <div class="mb-3">
                    <div class="title mb-2"><span>No Rekening</span></div>
                    <input type="text" name="bank_number" value="012345" class="form-control" id="bank_number" required>
                    <?php echo form_error('bank_number'); ?>
                </div>
                <div class="mb-3">
                    <div class="title mb-2"><span>Atas Nama</span></div>
                    <input type="text" name="name" value="<?= $customer->name ?>" class="form-control" id="an" required>
                    <?php echo form_error('name'); ?>
                </div>
                <div class="mb-3">
                    <div class="title mb-2"><span>Jumlah Transfer</span></div>
                    <input type="text" name="transfer" value="<?= '' . $orders->final_price . ''; ?>" class="form-control" id="price" required>
                    <?php echo form_error('transfer'); ?>
                </div>
                <div class="mb-3">
                    <div class="title mb-2"><span>Transfer ke</span></div>
                    <?php if (count($banks) > 0) : ?>
                        <select name="bank" class="form-control" id="orders">
                            <?php foreach ($banks as $bank => $data) : ?>
                                <option value="<?php echo $bank; ?>" <?php echo set_select('bank', $bank); ?>>
                                    <?php echo $data->bank; ?> a.n <?php echo $data->name; ?> (<?php echo $data->number; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else : ?>
                        <div class="text-danger font-weight-bold">Belum ada data bank.</div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <div class="title mb-2"><span>Bukti Pembayaran</span></div>
                    <input type="file" name="picture" class="form-control">
                    <?php echo form_error('picture'); ?>
                </div>
            </div>

            <button class="btn btn-success w-100 mb-5" type="submit">Konfirmasi</button>
            <?php if ($flash) : ?>
                <p class="text-center text-success"><?php echo $flash; ?></p>
            <?php endif; ?>
            </form>
        </div>
    </section>
    <!-- Everyday Essentials End -->
</main>
<!-- Main End -->