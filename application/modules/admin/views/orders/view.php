<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Order #<?php echo $data->order_number; ?></h6>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Order</a></li>
              <li class="breadcrumb-item active" aria-current="page">#<?php echo $data->order_number; ?></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Page content -->
<div class="container-fluid mt--6">

  <div class="row">
    <div class="col-md-8">
      <div class="card-wrapper">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-auto">
                <h3 class="mb-0">Data Pengiriman</h3>
              </div>
              <div class="col-auto">
                <b class="statusField"><?php echo get_order_status($data->order_status, $data->payment_method); ?></b>
              </div>
              <div class="col-auto">
                <form action="<?php echo  base_url('admin/orders/status'); ?>" method="POST">
                  <div class="col-auto" hidden>
                    <input type="text" name="status" id="status" value="7">
                    <input type="text" name="order" id="order" value="<?= $data->id ?>">
                  </div>
                  <input type="submit" class="btn btn-danger btn-sm" value="Batalkan Pesanan">
                </form>
              </div>
            </div>
            <?php if ($order_flash) : ?>
              <span class="float-right text-success font-weight-bold" style="margin-top: -30px;"><?php echo $order_flash; ?></span>
            <?php endif; ?>
          </div>

          <div class="card-body p-0">
            <table class="table align-items-center table-flush table-striped">
              <tr>
                <td>Nomor Order</td>
                <td><b>#<?php echo $data->order_number; ?></b></td>
              </tr>
              <tr>
                <td>Tanggal Order</td>
                <td><b><?php echo get_formatted_date($data->order_date); ?></b></td>
              </tr>
              <tr>
                <td>Nama Customer</td>
                <td><b><?php echo $delivery_data->customer->name; ?></b></td>
              </tr>
              <tr>
                <td>Nama Toko</td>
                <td><b><?= (isset($delivery_data->customer->shop_name) ? $delivery_data->customer->shop_name : '-'); ?></b></td>
              </tr>
              <tr>
                <td>Alamat Toko / Pengiriman</td>
                <td>
                  <div style="white-space: initial;"><b><?= (isset($delivery_data->customer->shop_address) ? $delivery_data->customer->shop_address : '-'); ?></b></div>
                </td>
              </tr>
              <tr>
                <td>Item</td>
                <td><b><?php echo $data->total_items; ?></b></td>
              </tr>
              <tr>
                <td>Metode pembayaran</td>
                <td><b>
                    <?php echo ($data->payment_method == 1) ? 'Kredit' : ''; ?>
                    <?php echo ($data->payment_method == 2) ? 'Transfer Bank' : ''; ?>
                  </b></td>
              </tr>
              <tr>
                <td>Metode pengiriman</td>
                <td><b>
                    <?php echo ($data->shipping_method == 1) ? 'PT. Karisma Indoagro Universal' : ''; ?>
                  </b></td>
              </tr>
              <tr>
                <td>Catatan</td>
                <td><b><?php echo $delivery_data->note; ?></b></td>
              </tr>
              <tr>
                <td>Status</td>
                <td><b class="statusField"><?php echo get_order_status($data->order_status, $data->payment_method); ?></b></td>
              </tr>
              <?php if ($data->payment_method == 5) : ?>
                <!--    <tr>
                          <td>Tanggal Jatuh Tempo</td>
                          <td><b class="statusField"><?php echo get_formatted_date($data->due_date); ?></b></td>
                      </tr> -->
              <?php endif; ?>
            </table>
          </div>

        </div>

        <div class="card card-primary">
          <div class="card-header">
            <h3 class="mb-0">Barang dalam pesanan</h3>
          </div>
          <div class="card-body p-0">

            <form action="<?php echo site_url('admin/orders/api/update_harga'); ?>" method="POST">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col"></th>
                    <th scope="col">Produk</th>
                    <th scope="col">Jumlah beli</th>
                    <th scope="col">Harga satuan</th>
                  </tr>
                </thead>
                <tbody>
                  <input type="hidden" value="<?php echo $order_id; ?>" name="order_id">
                  <?php foreach ($items as $item) : ?>
                    <tr>
                      <td>
                        <img class="img img-fluid rounded" style="width: 60px; height: 60px;" alt="<?php echo $item->name; ?>" src="<?php echo base_url('assets/uploads/products/' . $item->picture_name); ?>">
                      </td>
                      <td>
                        <h5 class="mb-0"><?php echo $item->name; ?></h5>
                      </td>
                      <td><?php echo $item->order_qty; ?> <?php echo $item->satuan_text; ?></td>
                      <td>
                        <input type="hidden" value="<?php echo $item->id; ?>" name="id[]">
                        <input type="hidden" value="<?php echo $item->order_qty; ?>" name="qty[]">
                        <?php if ($data->order_status == 9) : ?>
                          <input type="text" value="<?= floatval($item->order_price); ?>" class="form-control form-control-sm" name="order_price[]">
                        <?php elseif ($data->order_status == 1) : ?>
                          <input type="text" value="<?= floatval($item->order_price); ?>" class="form-control form-control-sm" name="order_price[]">
                        <?php else : ?>
                          <input type="text" value="<?= floatval($item->order_price); ?>" class="form-control form-control-sm" name="order_price[]" readonly>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>

                  <tr>
                    <td colspan="4">
                      <div class="col-md-3 text-right">
                        <?php if ($data->order_status == 1) : ?>
                          <input type="submit" class="btn btn-primary" value="Update Harga">
                        <?php elseif ($data->order_status == 9) : ?>
                          <input type="submit" class="btn btn-primary" value="Update Harga">
                        <?php endif; ?>

                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>


            </form>
          </div>
        </div>

      </div>

    </div>

    <?php if (admin_role() != 'kadep') : ?>
      <div class="col-md-4">

        <div class="card card-primary">
          <div class="card-header">
            <h3 class="mb-0">Ongkir dan Asuransi</h3>
          </div>
          <div class="card-body p-0">
            <form action="<?php echo site_url('admin/orders/api/verify'); ?>" method="POST">
              <table class="table align-items-center table-flush table-hover">

                <tr>
                  <td>No. Faktur</td>
                  <td>
                    <input type="hidden" value="<?php echo $data->id; ?>" name="id">
                    <input type="hidden" value="<?php echo $data->payment_method; ?>" name="payment_method">
                    <input type="hidden" value="<?php echo $data->order_status; ?>" name="order_status">
                    <?php if ($data->order_status == '1' || $data->order_status == '9') : ?>
                      <input type="text" value="<?php echo $data->invoice_number; ?>" class="form-control form-control-sm" name="invoice_number" required>
                    <?php else : ?>
                      <input type="text" value="<?php echo $data->invoice_number; ?>" class="form-control form-control-sm" name="invoice_number" readonly>
                    <?php endif; ?>
                  </td>
                </tr>

                <!--  <tr>
                          <td>No. TTB</td>
                          <td>
                              <input type="text" value="<?php echo $data->ttb_number; ?>" class="form-control form-control-sm" name="ttb_number">
                          </td>
                      </tr> -->
                <?php if (admin_role() != 'distribusi') { ?>
                  <!--
                  <tr>
                    <td>Tgl Jatuh Tempo</td>
                    <td>
                      <input type="date" value="<?php echo $data->due_date; ?>" class="form-control form-control-sm" name="due_date">
                    </td>
                  </tr> -->

                  <?php if ($data->order_status == '1' || $data->order_status == '9') : ?>
                    <tr>
                      <td>Ongkir</td>
                      <td>
                        <input type="text" value="<?php echo $data->shipping_cost; ?>" class="form-control form-control-sm" name="shipping_cost" required>
                      </td>
                    </tr>
                    <tr>
                      <td>Asuransi</td>
                      <td>
                        <input type="text" value="<?php echo $data->insurance; ?>" class="form-control form-control-sm" name="insurance" required>
                      </td>
                    </tr>
                  <?php else : ?>
                    <tr>
                      <td>Ongkir</td>
                      <td>
                        <input type="text" value="<?php echo $data->shipping_cost; ?>" class="form-control form-control-sm" name="shipping_cost" readonly>
                      </td>
                    </tr>
                    <tr>
                      <td>Asuransi</td>
                      <td>
                        <input type="text" value="<?php echo $data->insurance; ?>" class="form-control form-control-sm" name="insurance" readonly>
                      </td>
                    </tr>
                  <?php endif; ?>
                  <tr>
                    <td></td>
                    <td>
                      <?php if ($data->order_status == '1') : ?>
                        <div class="col-md-3 text-right">
                          <input type="submit" class="btn btn-primary" value="OK">
                        </div>
                      <?php elseif ($data->order_status == '9') : ?>

                        <?php if (!empty($kredit)) : ?>
                          <?php
                          $selisih = $kredit->max_credit - $kredit->tagihan;
                          ?>
                          <?php if ($selisih < 0) : ?>
                            <button class="btn btn-danger btn-block mt-2" disabled>Plafon Kredit Tidak Mencukupi</button>
                          <?php else : ?>
                            <input type="submit" class="btn btn-primary" value="OK" hidden>
                            <button class="btn btn-primary btn-block mt-2">Order Dapat Dilanjutkan</button>
                          <?php endif; ?>
                        <?php else : ?>
                          0
                        <?php endif; ?>

                      <?php else : ?>
                        <div class="col-md-3 text-right">
                        </div>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php } ?>
              </table>
            </form>
          </div>
        </div>
        <!--      <div class="card card-primary">
                <div class="card-header">
                    <h3 class="mb-0">Data Penerima</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table align-items-center table-flush table-hover">
                        <tr>
                            <td>Nama</td>
                            <td><b><?php echo $delivery_data->customer->name; ?></b></td>
                        </tr>
                        <tr>
                            <td>No. HP</td>
                            <td><b><?php echo $delivery_data->customer->phone_number; ?></b></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td><div style="white-space: initial;"><b><?php echo $delivery_data->customer->address; ?></b></div></td>
                        </tr>
                        <tr>
                            <td>Catatan</td>
                            <td><b><?php echo $delivery_data->note; ?></b></td>
                        </tr>
                    </table>
                </div>
            </div> -->
        <?php if (admin_role() != 'distribusi') { ?>
          <div class="card card-primary" id="#payments">
            <?php if ($data->order_status == '9') : ?>
            <?php else : ?>
              <div class="card-header">
                <div class="row">
                  <div class="col-auto">
                    <h3 class="mb-0">Pembayaran</h3>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <div class="card-body <?php echo ($data->payment_method == 1) ? 'p-0' : ''; ?>">
              <?php if ($data->payment_method == 1) : ?>

              <?php else : ?>
              <?php endif; ?>
              <?php if ($data->payment_method == 2) : ?>
                <?php if ($data->payment_price == NULL) : ?>
                  <?php if ($data->order_status == '2') : ?>
                    <div class="col-auto" hidden>
                      <input type="text" class="form-control" name="invoice" id="invoice" value="<?= $data->order_number ?>" readonly>
                      <input type="text" class="form-control" name="cusno" id="cusno" value="<?= substr($delivery_data->customer->phone_number, -8) ?>" readonly>
                      <input type="text" class="form-control" name="orderid" id="orderid" value="<?= $data->id ?>" readonly>
                      <input type="text" class="form-control" name="pricepay" id="pricepay" value="<?= $data->total_price ?>" readonly>
                      <input type="text" class="form-control" name="orderdate" id="orderdate" value="<?= $data->order_date ?>" readonly>
                    </div>
                    <div id="payment-result" class="alert alert-info m-2">
                      <h3>Belum Ada Data</h3>
                    </div>
                    <button class="btn btn-primary btn-lg cek_va w-100">Cek Pembayaran</button>
                  <?php elseif ($data->order_status == '9') : ?>
                  <?php else : ?>
                    <div>
                      <input type="hidden" id="invoice" value="<?= $data->order_number ?>">
                      <input type="hidden" id="cusno" value="<?= $delivery_data->customer->phone_number ?>">
                      <input type="hidden" id="orderid" value="<?= $data->id ?>">
                      <input type="hidden" id="pricepay" value="<?= $data->total_price ?>">
                      <input type="hidden" id="orderdate" value="<?= $data->order_date ?>">
                    </div>
                    <tr>
                      <td>Cek Pembayaran</td>
                      <td>
                        <button type="button" class="btn btn-warning btn-sm" id="btnCekVA">
                          Cek Pembayaran (VA)
                        </button>
                      </td>
                    </tr>

                  <?php endif; ?>

                <?php else : ?>
                  <?php if ($payment_flash) : ?>
                    <br>
                    <div class="alert alert-info" id="payment_flash"><?php echo $payment_flash; ?></div>
                  <?php endif; ?>

                  <table class="table align-items-center table-flush table-hover">
                    <tr>
                      <td>Transfer</td>
                      <td><b>Rp <?php echo format_rupiah($data->payment_price); ?></b></td>
                    </tr>
                    <tr>
                      <td>Tanggal</td>
                      <td><b><?php echo get_formatted_date($data->payment_date); ?></b></td>
                    </tr>
                    <tr>
                      <td>Status</td>
                      <td><b>
                          <?php if ($data->payment_status == 1) : ?>
                            <span class="badge badge-info">Menunggu konfirmasi</span>
                          <?php elseif ($data->payment_status == 2) : ?>
                            <span class="badge badge-success">Dikonfirmasi</span>
                          <?php elseif ($data->payment_status == 3) : ?>
                            <span class="badge badge-danger">Gagal</span>
                          <?php endif; ?>
                        </b></td>
                    </tr>
                    <!-- <tr>
                      <td>Transfer ke</td>
                      <td>
                        <div style="white-space: initial;"><b>
                            <?php
                            $bank_data = json_decode($data->payment_data);
                            $bank_data  = (array) $bank_data;
                            $transfer_to = $bank_data['transfer_to'];

                            $transfer_to = $banks[$transfer_to];
                            $transfer_from = $bank_data['source'];
                            ?>
                            <?php echo $transfer_to->bank; ?> a.n <?php echo $transfer_to->name; ?> (<?php echo $transfer_to->number; ?>)
                          </b></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Transfer dari</td>
                      <td>
                        <div style="white-space: initial;">
                          <b><?php echo $transfer_from->bank; ?> a.n <?php echo $transfer_from->name; ?> (<?php echo $transfer_from->number; ?>)</b>
                        </div>
                      </td>
                    </tr> -->
                  </table>
                <?php endif; ?>
              <?php elseif ($data->payment_method == 1) : ?>
                <?php if ($data->payment_price == NULL) : ?>
                  <div class="alert alert-info m-2">Tidak ada data pembayaran.</div>
                <?php else : ?>
                  <div>
                    <img class="img img-fluid" src="<?php echo base_url('assets/uploads/payments/' . $data->picture_name); ?>">
                  </div>
                  <?php if ($payment_flash) : ?>
                    <br>
                    <div class="alert alert-info" id="payment_flash"><?php echo $payment_flash; ?></div>
                  <?php endif; ?>

                  <table class="table align-items-center table-flush table-hover">
                    <tr>
                      <td>Transfer</td>
                      <td><b>Rp <?php echo format_rupiah($data->payment_price); ?></b></td>
                    </tr>
                    <tr>
                      <td>Tanggal</td>
                      <td><b><?php echo get_formatted_date($data->payment_date); ?></b></td>
                    </tr>
                    <tr>
                      <td>Status</td>
                      <td><b>
                          <?php if ($data->payment_status == 1) : ?>
                            <span class="badge badge-info">Menunggu konfirmasi</span>
                          <?php elseif ($data->payment_status == 2) : ?>
                            <span class="badge badge-success">Dikonfirmasi</span>
                          <?php elseif ($data->payment_status == 3) : ?>
                            <span class="badge badge-danger">Gagal</span>
                          <?php endif; ?>
                        </b></td>
                    </tr>
                    <tr>
                      <td>Transfer ke</td>
                      <td>
                        <div style="white-space: initial;"><b>
                            <?php
                            $bank_data = json_decode($data->payment_data);
                            $bank_data  = (array) $bank_data;
                            $transfer_to = $bank_data['transfer_to'];

                            $transfer_to = $banks[$transfer_to];
                            $transfer_from = $bank_data['source'];
                            ?>
                            <?php echo $transfer_to->bank; ?> a.n <?php echo $transfer_to->name; ?> (<?php echo $transfer_to->number; ?>)
                          </b></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Transfer dari</td>
                      <td>
                        <div style="white-space: initial;">
                          <b><?php echo $transfer_from->bank; ?> a.n <?php echo $transfer_from->name; ?> (<?php echo $transfer_from->number; ?>)</b>
                        </div>
                      </td>
                    </tr>
                  </table>
                <?php endif; ?>
              <?php endif; ?>
            </div>

            <?php if ($data->payment_price != NULL) : ?>
              <div class="card-footer">
                <form action="<?php echo site_url('admin/payments/verify'); ?>" method="POST">
                  <div class="row">
                    <input type="hidden" name="id" value="<?php echo $data->payment_id; ?>">
                    <input type="hidden" name="payment_method" value="<?php echo $data->payment_method; ?>">
                    <input type="hidden" name="order" value="<?php echo $data->id; ?>">

                    <!-- FORM VERIFY PAYMENTS  -->
                    <!-- <div class="col-md-9">
                        <select class="form-control" name="action">
                          <?php if ($data->payment_status == 1) : ?>
                            <option value="1">Konfirmasi Pembayaran</option>
                            <option value="2">Pembayaran Tidak Ada</option>
                          <?php else : ?>
                            <option value="4" readonly>Tidak ada pilihan</option>
                          <?php endif; ?>
                        </select>
                      </div>
                      <div class="col-md-3 text-right">
                        <input type="submit" class="btn btn-primary" value="OK">
                      </div> -->

                  </div>
                </form>
              </div>
            <?php endif; ?>
          </div>

          <!-- <div class="card card-primary" id="#briva">
            <div class="card-header">
              <h3 class="mb-0">Virtual Account BRI</h3>
            </div>
            <div class="card-body">
              <div class="alert alert-info m-2">Data VA Tidak Ada</div>
              <form action="" method="POST">
              </form>
            </div>
          </div> -->

        <?php } ?>
      </div>
    <?php endif; ?>
  </div>

  <script>
    $(document).on("click", ".cek_va", function(e) {
      e.preventDefault();

      let invoice = $("#invoice").val();
      let cusno = $("#cusno").val();

      $.ajax({
        url: "<?= site_url('admin/orders/inquiry_status_va') ?>",
        type: "POST",
        dataType: "json",
        data: {
          invoice: invoice,
          cusno: cusno
        },
        success: function(res) {
          console.log("RAW RESPONSE: ", res);

          if (res.paid_status === "Y") {
            $("#payment-result").html(
              '<div class="alert alert-success m-2">' + res.message + '</div>'
            );
          } else {
            $("#payment-result").html(
              '<div class="alert alert-info m-2">' + res.message + '</div>'
            );
          }
        },
        error: function() {
          $("#payment-result").html(
            '<div class="alert alert-danger m-2">Terjadi kesalahan server.</div>'
          );
        }
      });
    });
  </script>
  <script>
    document.getElementById('btnCekVA').addEventListener('click', function() {

      Swal.fire({
        title: "Memeriksa...",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });

      $.ajax({
        url: "<?= base_url('admin/orders/cek_va_status') ?>",
        type: "POST",
        dataType: "json",
        data: {
          invoice: $('#invoice').val(),
          cusno: $('#cusno').val(),
          orderid: $('#orderid').val(),
          pricepay: $('#pricepay').val(),
          orderdate: $('#orderdate').val()
        },

        success: function(res) {
          Swal.close();

          if (res.status === "UNPAID") {
            Swal.fire("Info", res.message, "warning");
          }

          if (res.status === "UPDATED") {
            Swal.fire("Sukses", res.message, "success").then(() => {
              location.reload();
            });
          }

          if (res.status === "ERROR") {
            Swal.fire("Error", res.message, "error");
          }
        },

        error: function(xhr) {
          Swal.close();
          console.log("XHR ERROR:", xhr.responseText);
          Swal.fire("Gagal", "Server bermasalah", "error");
        }
      });

    });
  </script>