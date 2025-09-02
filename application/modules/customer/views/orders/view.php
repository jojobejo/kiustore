<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<main class="main-wrap order-detail mb-xxl">
    <!-- Banner Start -->
    <section class="pt-0">
        <div class="banner-box">
            <div class="media">
                <img src="<?php echo get_theme_uri('icons/svg/box.svg'); ?>" alt="box" />
                <div class="media-body">
                    <span class="font-sm">Order ID: <?= $data->number_ordered; ?></span>
                    <h2><?php echo get_order_status($data->order_status, $data->payment_method); ?></h2>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner End -->

    <!-- Item Section Start -->
    <section class="item-section p-0">
        <h3 class="font-theme font-md">Items:</h3>

        <div class="item-wrap">
            <!-- Item Start -->
            <?php foreach ($items as $item) : ?>
                <a class="media">
                    <div class="count">
                        <span class="font-sm"><?php echo $item->order_qty . ' ' . $item->satuan_text; ?></span>
                        <i data-feather="x"></i>
                    </div>
                    <div class="media-body">
                        <h4 class="title-color font-sm"><?php echo $item->name; ?></h4>
                        <span class="content-color font-sm">@ Rp <?php echo format_rupiah($item->order_price); ?></span>
                    </div>
                    <span class="title-color font-md">Rp <?php echo format_rupiah($item->order_qty * $item->order_price); ?></span>
                </a>
            <?php endforeach; ?>

            <!-- Item End -->
        </div>
    </section>
    <!-- Item Section End -->

    <!-- Order Summary Section Start -->
    <section class="order-summary p-0">
        <h3 class="font-theme font-md">Data Order</h3>
        <!-- Product Summary Start -->
        <ul>
            <li>
                <span>No. Faktur</span>
                <span><?php echo $data->kd_faktur; ?></span>
            </li>
            <li>
                <span>No. TTB</span>
                <span><?php echo $data->order_number; ?></span>
            </li>
            <li>
                <span>Tanggal Order</span>
                <span><?php echo get_formatted_date($data->order_date); ?></span>
            </li>
            <li>
                <span>Jatuh Tempo Pembayaran</span>
                <span><?php echo get_formatted_date($data->due_date); ?></span>
            </li>
            <li>
                <span>Jumlah Barang</span>
                <span><?php echo $data->total_items; ?></span>
            </li>
            <?php if ($data->coupon_id) : ?>
                <li>
                    <span>Total Belanja</span>
                    <span>Rp <?php echo format_rupiah($data->total_belanja + $data->kupon); ?></span>
                </li>
                <li>
                    <span>Potongan Kupon</span>
                    <span>Rp <?php echo format_rupiah($data->kupon); ?></span>
                </li>
            <?php else : ?>
                <li>
                    <span>Total Belanja</span>
                    <span>Rp <?php echo format_rupiah($data->total_belanja); ?></span>
                </li>
            <?php endif; ?>

            <?php foreach ($is_ongkir as $o) :
                $jsongkir = explode(';', $o->ongkir_price); ?>
                <?php if ($o->ongkir_price == '0') : ?>
                    <li>
                        <span>Ekspedisi</span>
                        <span>Rp <?php echo format_rupiah($data->shipping_cost); ?></span>
                    </li>

                <?php else : ?>
                    <li>
                        <span>Ekspedisi</span>
                        <span>Rp <?php echo format_rupiah($jsongkir['4']); ?></span>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>

            <li>
                <span>Asuransi</span>
                <span>Rp <?php echo format_rupiah($data->insurance); ?></span>
            </li>
            <?php foreach ($is_ongkir as $o) :
                $jsongkir = explode(';', $o->ongkir_price);
                $final_price = 0;
                $ongkir_value = isset($jsongkir[4]) ? $jsongkir[4] : 0;
                $final_price = $data->insurance + $data->shipping_cost + $data->total_belanja + $ongkir_value;
            ?>

                <li>
                    <span>Total Keseluruhan</span>
                    <span class="font-theme">Rp <?php echo format_rupiah($final_price); ?></span>
                </li>
            <?php endforeach; ?>

            <li>
                <span>Pembayaran</span>
                <span>
                    <?php echo ($data->payment_method == 1) ? 'Kredit' : ''; ?>
                    <?php echo ($data->payment_method == 2) ? 'Virtual Account Karisma' : ''; ?>
                </span>
            </li>

            <!-- <?php if ($data->order_status != '7') : ?>
                <?php foreach ($briva as $b) : ?>
                    <li style="font-weight: bolder;">
                        <span>Virtual Account</span>
                        <span><?= $b->va_code ?> <a href="#" class="btn btn-sm btn-info ml-2"><i class="far fa-copy"></i></a></span>
                    </li>
                    <li>
                        <span></span>
                        <span>Rp. <?= format_rupiah($b->total_price_topay)  ?> <a href="#" class="btn btn-sm btn-info ml-2"><i class="far fa-copy"></i></a></span>
                    </li>
                <?php endforeach; ?>
            <?php else : ?>
            <?php endif; ?> -->

            <li>
                <span></span>
            </li>
        </ul>
        <!-- Product Summary End -->
    </section>

    <section hidden>
        <?php
        $dataorder = json_decode($data->delivery_data, true);
        $duedate = date("ym", strtotime($data->due_date));
        ?>
        <div class="row">
            <input type="text" value="<?= $data->order_id ?>" name="order_id" id="order_id">
            <input type="text" value="<?= $data->number_ordered ?>" name="trxid" id="trxid">
            <input type="text" value="<?= $data->kd_faktur ?>" name="kdfaktur" id="kdfaktur">
            <input type="text" value="<?= $dataorder['customer']['name'] ?>" name="va_name" id="va_name">
            <input type="text" value="<?= substr($dataorder['customer']['phone_number'], -8) . $duedate ?>" name="no_va" id="no_va">
            <input type="text" value="<?= substr($dataorder['customer']['phone_number'], -8) ?>" name="nocust" id="nocust">
            <input type="text" value="<?= $data->final_price ?>" name="total_topay" id="total_topay">
            <input type="text" value="<?= $data->user_id ?>" name="user_id" id="user_id">
        </div>
    </section>

    <!-- Order Summary Section End -->
    <div hidden>
        <section class="address-section p-0">
            <h3 class="font-theme font-md">Pengiriman</h3>
            <div class="address">
                <?php if ($data->shipping_method == 1) : ?>
                    <table class="table table-hover table-striped table-hover">
                        <tr>
                            <td>Expedisi</td>
                            <td><b>PT. KARISMA INDOAGRO UNIVERSAL</b></td>
                        </tr>
                    </table>
                <?php else : ?>
                    <table class="table table-hover table-striped table-hover">
                        <?php foreach ($getongkir as $gt) :
                            $jsongkir = explode(';', $gt->jsongkir);
                            $expedisi = $gt->sjasa;
                            if ($expedisi == "jne") {
                                $expedisi = 'JNE';
                            } elseif ($expedisi == "pos") {
                                $expedisi = 'POS INDONESIA';
                            } elseif ($expedisi == "tiki") {
                                $expedisi = 'TIKI';
                            }
                        ?>
                            <tr>
                                <td><b>Expedisi</b></td>
                                <td style="text-align: right;font-weight: bold;"><?= $expedisi ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Jasa</td>
                                <td style="text-align: right;font-weight: bold;"><?= $jsongkir['1'] . ' ' . '(' . $jsongkir['2'] . ')' ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Estimasi Pengiriman</td>
                                <td style="text-align: right;font-weight: bold;"><?= $jsongkir['3'] ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Biaya Pengiriman</td>
                                <td style="text-align: right;font-weight: bold;">Rp. <?= format_rupiah($jsongkir['4']) ?> </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </section>
        <!-- Address Section Start -->
        <section class="address-section p-0">
            <h3 class="font-theme font-md">Pembayaran</h3>
            <div class="address">
                <?php $final_price = $data->insurance + $data->shipping_cost + $data->total_belanja + $ongkir_value; ?>
                <table class="table table-hover table-striped table-hover">
                    <tr>
                        <td style="font-weight: bold;">Virual Account</td>
                        <td style="text-align: right;font-weight: bold;"><b>Rp <?php echo format_rupiah($final_price); ?></b></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Expired Token</td>
                        <td style="text-align: right;font-weight: bold;"><b><?php echo get_formatted_date($data->payment_date); ?></b></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Status</td>
                        <td style="text-align: right;font-weight: bold;"><b>
                                <?php if ($data->payment_status == 1) : ?>
                                    <span>Menunggu konfirmasi</span>
                                <?php elseif ($data->payment_method == 2) : ?>
                                    <span>Dikonfirmasi</span>
                                <?php elseif ($data->payment_method == 3) : ?>
                                    <span>Gagal</span>
                                <?php endif; ?>
                            </b>
                        </td>
                    </tr>
                </table>
            </div>
        </section>
        <!-- Address Section End -->
        <!-- <?= $data->payment_method; ?> <br>
        <?= $data->order_status; ?> -->
        <!-- Payment Method Section Start -->
    </div>

    <section class="payment-method p-0" hidden>
        <?php foreach ($briva as $b) : ?>
            <h5 id="userno"><?= $b->userno ?></h5>
            <h5 id="order_number"><?= $b->order_number ?></h5>

            <div id="payment_status">Menunggu pembayaran...</div>
        <?php endforeach; ?>
    </section>

    <section class="payment-method p-0" hidden>

        <?php foreach ($briva as $b) :
            $expired_time = strtotime($b->create_at . ' +15 minutes') * 1000;
        ?>
            <h3>Invoice : <?= $b->order_number ?></h3>
            <h3>nova : <?= $b->userno ?></h3>
            <h3>expired : <?= $b->create_at ?></h3>
            <h3>Time Left : <span class="countdown" data-expired="<?= $expired_time ?>">--:--:--</span></h3>
        <?php endforeach; ?>

    </section>

    <section class="payment-method p-0">
        <h3 class="font-theme font-md">Tindakan</h3>
        <div class="payment">
            <?php if ($data->payment_method == 1) : ?>
                <?php if ($data->order_status == 1) : ?>
                    <div class="alert alert-info m-2 w-100">Pesanan dalam proses sales</div>
                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">Batalkan</a>
                <?php elseif ($data->order_status == 2) : ?>
                    <div class="alert alert-info m-2 w-100">Menunggu Pembayaran</div>
                    <a href="#" id="" class="btn btn-success ml-3 w-100" data-bs-toggle="modal" data-bs-target="#do_payment_kredit">Lakukan Pembayaran Coba</a>
                    <!-- <a href="<?php echo site_url('customer/payments/confirm?order=' . $data->order_id); ?>" class="btn btn-success ml-3 w-100">Lakukan Pembayaran</a> -->
                <?php elseif ($data->order_status == 3) : ?>
                    <div class="alert alert-info m-2 w-100">Pesanan dalam pengemasan</div>
                <?php elseif ($data->order_status == 4) : ?>
                    <div class="alert alert-info m-2 w-100">Pesanan dalam pengiriman</div>
                    <a href="#" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#terimaModal"><i class="fa fa-thumbs-o-up"></i> Terima Barang</a>
                <?php elseif ($data->order_status == 5) : ?>
                    <?php if ($data->payment_price == NULL) : ?>
                        <div class="alert alert-info m-2 w-100">Pesanan sudah diterima dan menunggu pelunasan</div>
                        <a href="<?php echo site_url('customer/payments/confirm?order=' . $data->order_id); ?>" class="btn btn-success">Konfirmasi Pembayaran</a>
                    <?php else : ?>
                        <div class="alert alert-info m-2 w-100">Menunggu konfirmasi pembayaran</div>
                    <?php endif; ?>
                <?php elseif ($data->order_status == 6) : ?>
                    <div class="alert alert-info m-2 w-100">Pesanan selesai</div>
                <?php elseif ($data->order_status == 7) : ?>
                    <div class="alert alert-info m-2 w-100">Pesanan dibatalkan</div>
                    <!-- <a href="#" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#deleteModal"><iclass="fa fa-trash"></i> Hapus</a> -->
                <?php endif; ?>

            <?php elseif ($data->payment_method == 2) : ?>
                <?php if ($data->order_status == 1) : ?>
                    <div class="alert alert-info m-2 w-100">Pesanan dalam proses sales</div>
                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">Batalkan</a>
                <?php elseif ($data->order_status == 2) : ?>
                    <?php if ($data->payment_status == 3) : ?>
                        <div class="alert alert-info m-2 w-100">Pembayaran Gagal / Kurang Bayar</div>
                        <a href="<?php echo site_url('customer/payments/confirm?order=' . $data->order_id); ?>" class="btn btn-success">Konfirmasi Pembayaran</a>
                    <?php else : ?>
                        <div class="alert alert-info m-2 w-100">Menunggu konfirmasi pembayaran</div>
                        <!-- <a href="<?php echo site_url('customer/payments/confirm?order=' . $data->order_id); ?>" class="btn btn-success m-2 w-100">Lakukan Pembayaran</a> -->
                        <!-- <a href="#" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelModal">Batalkan Pesanan</a> -->
                        <a href="#" id="" class="btn btn-success ml-3 w-100" data-bs-toggle="modal" data-bs-target="#do_payment_kredit">Lakukan Pembayaran Coba</a>
                    <?php endif; ?>
                <?php elseif ($data->order_status == 3) : ?>
                    <div class="alert alert-info m-2 w-100">Pesanan dalam pengemasan</div>
                <?php elseif ($data->order_status == 4) : ?>
                    <div class="alert alert-info m-2 w-100">Pesanan dalam pengiriman</div>
                    <a href="#" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#terimaModal"><i class="fa fa-thumbs-o-up"></i> Terima Barang</a>
                <?php elseif ($data->order_status == 6) : ?>
                    <div class="alert alert-info m-2 w-100">Pesanan selesai</div>
                <?php elseif ($data->order_status == 7) : ?>
                    <div class="alert alert-info m-2 w-100">Pesanan dibatalkan</div>
                    <!-- <a href="#" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i> Hapus</a> -->
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
    <!-- Payment Method Section End -->
</main>

<?php if (($data->payment_method == 2 && $data->order_status == 4) || ($data->payment_method == 1 && $data->order_status == 4)) : ?>
    <div class="modal fade" id="terimaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="terimaModalLabel">Terima Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="mt-2">Rating Sales</h4>
                    <div class="mb-3 rating">
                        <label class="rating-label">
                            <input name="rating" class="rating" max="5" oninput="this.style.setProperty('--value', `${this.valueAsNumber}`)" step="1" style="--stars:5;--value:0" type="range" value="0">
                        </label>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Keterangan</label>
                        <textarea name="rating-desc" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success terima-btn">Terima</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.terima-btn').click(function(e) {
            e.preventDefault();

            // $(this).html('<i class="fa fa-spin fa-spinner"></i> Proses...');
            let rating = $('input[name="rating"]').val();
            let rating_desc = $('textarea[name="rating-desc"]').val();
            $.ajax({
                method: 'POST',
                url: '<?php echo site_url('customer/orders/order_api?action=terima_order'); ?>',
                data: {
                    id: <?php echo $data->order_id; ?>,
                    rating: rating,
                    rating_desc: rating_desc
                },
                context: this,
                success: function(res) {
                    if (res.code == 200) {
                        // $(this).html('Terima');

                        if (res.success) {
                            $('.statusField').text('Diterima');
                            $('.actionRow').html('Order Diterima');
                        } else if (res.error) {
                            $('.actionRow').html(res.message);
                        }

                        setTimeout(() => {
                            $('#terimaModal').modal('hide');
                            location.reload();
                        }, 1000);
                    }
                }
            })
        })
    </script>
<?php endif; ?>


<?php if ($data->payment_method == 2 && $data->order_status == 2) : ?>
    <div class="modal fade" id="do_payment_kredit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="terimaModalLabel">Lakukan Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <?php
                        $dataorder = json_decode($data->delivery_data, true);
                        $duedate = date("ym", strtotime($data->due_date));
                        ?>
                        <div class="row">
                            <input type="text" value="<?= $data->order_id ?>" name="order_id" id="order_id">
                            <input type="text" value="<?= $data->number_ordered ?>" name="trxid" id="trxid">
                            <input type="text" value="<?= $data->kd_faktur ?>" name="kdfaktur" id="kdfaktur">
                            <input type="text" value="<?= $dataorder['customer']['name'] ?>" name="va_name" id="va_name">
                            <input type="text" value="<?= substr($dataorder['customer']['phone_number'], -8) . $duedate ?>" name="no_va" id="no_va">
                            <input type="text" value="<?= substr($dataorder['customer']['phone_number'], -8) ?>" name="nocust" id="nocust">
                            <input type="text" value="<?= $data->final_price ?>" name="total_topay" id="total_topay">
                            <input type="text" value="<?= $data->user_id ?>" name="user_id" id="user_id">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success payment-btn">Lakukan Pembayaran</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($data->payment_method == 1 && $data->order_status == 2) : ?>
    <div class="modal fade" id="do_payment_kredit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="terimaModalLabel">Lakukan Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="row">
                            <?php
                            $dataorder = json_decode($data->delivery_data, true);
                            $duedate = date("ym", strtotime($data->due_date));
                            ?>
                            <input type="text" value="<?= $data->order_id ?>" name="order_id" id="order_id">
                            <input type="text" value="<?= $data->number_ordered ?>" name="trxid" id="trxid">
                            <input type="text" value="<?= $dataorder['customer']['name'] ?>" name="va_name" id="va_name">
                            <input type="text" value="<?= substr($dataorder['customer']['phone_number'], -8) . $duedate ?>" name="no_va" id="no_va">
                            <input type="text" value="<?= $data->final_price ?>" name="total_topay" id="total_topay">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success payment-btn">Lakukan Pembayaran</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.payment-btn').click(function(e) {
            e.preventDefault();

            let id = $('#order_id').val();
            let order = $('#trxid').val();
            let va_no = $('#no_va').val();
            let va_name = $('#va_name').val();
            let va_to_pay = $('#total_topay').val();

            $(this).html('<i class="fa fa-spin fa-spinner"></i> Generate Payment ...');

            $.ajax({
                method: 'POST',
                url: '<?php echo site_url('customer/orders/order_api?action=do_payment'); ?>',
                data: {
                    id: id,
                    order: order,
                    va_no: va_no,
                    va_name: va_name,
                    va_to_pay: va_to_pay
                },
                context: this,
                success: function(res) {
                    if (res.code == 200) {
                        console.log("Response dari server:", res);
                        $(this).html('Batalkan');

                        if (res.success) {
                            $('.statusField').text('Prosess');
                        } else if (res.error) {
                            $('.actionRow').html(res.message);
                        }
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                }
            })
        })
    </script>

<?php endif; ?>

<?php if ($data->order_status == 2) : ?>
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Batalkan Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin membatalkan pesanan? </p>
                    <input type="text" name="del_va" id="del_va" value="<?= $data->order_number ?>" hidden>
                    <input type="text" name="del_no" id="del_no" value="<?= $data->userno ?>" hidden>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger cancel-btn">Batalkan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.cancel-btn').click(function(e) {
            e.preventDefault();

            let orderId = <?= $data->order_id ?>;
            let delVa = $('#del_va').val();
            let delNo = $('#del_no').val();

            $(this).html('<i class="fa fa-spin fa-spinner"></i> Membatalkan...');

            $.ajax({
                method: 'POST',
                url: '<?php echo site_url('customer/orders/order_api?action=cancel_order'); ?>',
                data: {
                    id: orderId,
                    del_va: delVa,
                    del_no: delNo
                },
                context: this,
                success: function(res) {
                    if (res.code == 200) {
                        console.log("Response dari server:", res);
                        $(this).html('Batalkan');

                        if (res.success) {
                            $('.statusField').text('Dibatalkan');
                            $('.actionRow').html('Order dibatalkan');
                        } else if (res.error) {
                            $('.actionRow').html(res.message);
                        }

                        setTimeout(() => {
                            $('#cancelModal').modal('hide');
                            location.reload();
                        }, 1000);
                    }
                }
            })
        })
    </script>
<?php endif; ?>

<?php if ($data->order_status == 5) : ?>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletelModalLabel">Hapus Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="deleteText">Anda yakin ingin menghapus pesanan? Semua data yang terkait juga akan dihapus</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning delete-btn">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.delete-btn').click(function(e) {
            e.preventDefault();

            $(this).html('<i class="fa fa-spin fa-spinner"></i> Menghapus...');
            var del = $('.deleteText');

            $.ajax({
                method: 'POST',
                url: '<?php echo site_url('customer/orders/order_api?action=delete_order'); ?>',
                data: {
                    id: <?php echo $data->order_id; ?>
                },
                context: this,
                success: function(res) {
                    if (res.code == 200) {
                        $(this).html('Hapus');

                        if (res.success) {
                            del.html('Order dan semua datanya berhasil dihapus');

                            setTimeout(() => {
                                del.html('<i class="fa fa-spin fa-spinner"></i> Mengalihkan...');
                            }, 3000);
                            setTimeout(() => {
                                window.location = '<?php echo site_url('customer/orders'); ?>';
                            }, 5000);
                        } else if (res.error) {
                            $('.actionRow').html(res.message);

                            setTimeout(() => {
                                $('#deleteModal').modal('hide');
                            }, 2000);
                        }
                    }
                }
            })
        })
    </script>

<?php endif; ?>