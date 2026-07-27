<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0"><?php echo $customer->name; ?></h6>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><?php echo anchor('admin/customers', 'Pelanggan'); ?></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo $customer->name; ?></li>
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
    <div class="col-md-5">
      <div class="card-wrapper">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">Data Pelanggan</h3>
            <?php if ($flash) : ?>
              <span class="float-right text-success font-weight-bold" style="margin-top: -30px;"><?php echo $flash; ?></span>
            <?php endif; ?>
          </div>
          <div class="card-body p-0">
            <form action="<?php echo site_url('admin/customers/api/edit'); ?>" method="POST">
              <table class="table align-items-center table-flush table-hover">
                <tr>
                  <td>Nama Pelanggan</td>
                  <td>
                    <input type="hidden" value="<?php echo $customer->id; ?>" name="user_id">
                    <input type="text" value="<?php echo $customer->name; ?>" class="form-control form-control-sm" name="names">
                  </td>
                </tr>
                <tr>
                  <!--        <td>Email</td>
                        <td>
                            <input type="text" value="<?php echo $customer->email; ?>" class="form-control form-control-sm" name="email">
                        </td>
                    </tr> -->
                <tr>
                  <td>No. HP</td>
                  <td>
                    <input type="text" value="<?php echo $customer->phone_number; ?>" class="form-control form-control-sm" name="phone_number">
                  </td>
                </tr>
                <tr>
                  <td>Alamat Pelanggan</td>
                  <td>
                    <input type="text" value="<?php echo $customer->address; ?>" class="form-control form-control-sm" name="address">
                  </td>
                </tr>
                <tr>
                  <td>Nama Toko</td>
                  <td>
                    <input type="text" value="<?php echo $customer->shop_name; ?>" class="form-control form-control-sm" name="shop_name">
                  </td>
                </tr>
                <tr>
                  <td>Alamat Toko</td>
                  <td>
                    <div class="row">
                      <div class="col-8">
                        <input type="text" value="<?php echo $customer->shop_address; ?>" class="form-control form-control-sm" name="shop_address" readonly>
                      </div>
                      <div class="col-2">
                        <a href="<?= base_url('') ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit text-warning"></i></a>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Terdaftar pada</td>
                  <td><b><?php echo get_formatted_date($customer->register_date); ?></b></td>
                </tr>
                <?php if (admin_role() == 'admin' || admin_role() == 'keuangan') : ?>
                  <tr>
                    <td>Nama Sales</td>
                    <td>
                      <select name="salesman_id" class="form-control" id="salesman_id">
                        <option value="">Pilih Salesman</option>
                        <?php if (count($admin) > 0) : ?>
                          <?php foreach ($admin as $sales) : ?>
                            <option value="<?php echo $sales->id; ?>" <?php echo set_select('salesman_id', $sales->id, ($customer->salesman_id == $sales->id) ? TRUE : FALSE); ?>>› <?php echo $sales->name; ?></option>

                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </td>
                  </tr>
                <?php endif; ?>
                <tr>
                  <td>Level</td>
                  <td>
                    <select name="level" class="form-control" id="level">
                      <option value="<?php echo $customer->level; ?>">
                        <?php if ($customer->level == 1) {
                          echo 'Umum';
                        } elseif ($customer->level == 2) {
                          echo 'R1';
                        } elseif ($customer->level == 3) {
                          echo 'R2';
                        }
                        ?>
                      </option>
                      <option value="">Pilih Level</option>
                      <option value="1"> Basic</option>
                      <option value="2"> R1</option>
                      <option value="3"> R2</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Maks. Kredit</td>
                  <td>
                    <?php if (admin_role() == 'adminonline' || admin_role() == 'salesman') : ?>
                      <input type="text" placeholder="<?php echo $customer->max_credit; ?>" class="form-control form-control-sm" name="max_credit" disabled>
                    <?php endif; ?>
                    <?php if (admin_role() == 'admin' || admin_role() == 'keuangan') : ?>
                      <input type="text" value="<?php echo $customer->max_credit; ?>" class="form-control form-control-sm" name="max_credit">
                    <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td>
                    <div class="col-md-2 text-left">
                      <input type="submit" class="btn btn-primary" value="UBAH">
                    </div>
                  </td>
                </tr>
              </table>
            </form>
          </div>
          <div class="card-footer">
            <a href="#" data-id="<?php echo $customer->id; ?>" class="btn btn-danger btn-sm btnDelete"><i class="fa fa-trash"></i></a>
          </div>

        </div>

      </div>

    </div>
    <div class="col-md-7">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="mb-0">Riwayat Order</h3>
        </div>
        <div class="card-body <?php echo (count($orders) > 0) ? 'p-0' : ''; ?>">
          <?php if (count($orders) > 0) : ?>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nomor</th>
                    <th scope="col">Jumlah Harga</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($orders as $order) : ?>
                    <tr>
                      <th scope="col">
                        <?php echo $order->id; ?>
                      </th>
                      <td>
                        <?php echo anchor('admin/orders/view/' . $order->id, '#' . $order->order_number); ?>
                      </td>
                      <td>
                        Rp <?php echo format_rupiah($order->total_price); ?>
                      </td>
                      <td><?php echo get_order_status($order->order_status, '#' . $order->payment_method); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else : ?>
            <div class="alert alert-info">Belum ada data pembayarn.</div>
          <?php endif; ?>
        </div>
      </div>


      <div class="card card-primary">
        <div class="card-header">
          <h3 class="mb-0">Riwayat Pembayaran</h3>
        </div>
        <div class="card-body <?php echo (count($payments) > 0) ? 'p-0' : ''; ?>">
          <?php if (count($payments) > 0) : ?>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Order</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($payments as $payment) : ?>
                    <tr>
                      <th scope="col">
                        <?php echo $payment->id; ?>
                      </th>
                      <td>
                        <?php echo anchor('admin/paymeny/view/' . $payment->id, $payment->order_number); ?>
                      </td>
                      <td>
                        Rp <?php echo format_rupiah($payment->payment_price); ?>
                      </td>
                      <td><?php echo get_payment_status($payment->payment_status); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else : ?>
            <div class="alert alert-info">Belum ada data order.</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card card-primary" id="section_extravaganza_point">
        <div class="card-header">
          <h3 class="mb-0">Point Extravaganza</h3>
        </div>
        <div class="card-body">
          <?php
          $has_abc = count($extravaganza_summary_abc) > 0;
          $has_fastmoving = count($extravaganza_summary_fastmoving) > 0;
          $total_silver = 0;
          $total_gold = 0;
          $total_platinum = 0;
          $konv_silver = 0;
          $konv_gold = 0;
          $konv_platinum = 0;

          if ($has_abc) {
            foreach ($extravaganza_summary_abc as $summary) {
              $total_silver += (int)$summary->total_silver;
              $total_gold += (int)$summary->total_gold;
              $total_platinum += (int)$summary->total_platinum;
            }
          }
          if ($has_fastmoving) {
            foreach ($extravaganza_summary_fastmoving as $summary) {
              $total_silver += (int)$summary->total_silver;
              $total_gold += (int)$summary->total_gold;
              $total_platinum += (int)$summary->total_platinum;
            }
          }

          if ($total_silver > 0) {
            $konv_platinum = (int)floor($total_silver / 100);
            $sisa_after_platinum = $total_silver - ($konv_platinum * 100);
            $konv_gold = (int)floor($sisa_after_platinum / 50);
            $konv_silver = (int)($sisa_after_platinum - ($konv_gold * 50));
          }
          ?>
          <?php if ($has_abc || $has_fastmoving) : ?>
            <div class="table-responsive mt-3">
              <table class="table align-items-center table-flush table-striped table-hover">
                <thead class="thead-light bg-primary text-white">
                  <tr>
                    <th scope="col">Kategori</th>
                    <th scope="col">Nominal</th>
                    <th scope="col">POINT</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($has_abc) : ?>
                    <?php foreach ($extravaganza_summary_abc as $summary) : ?>
                      <tr>
                        <td><span class="badge badge-primary">ABC</span></td>
                        <td>Rp <?= number_format($summary->total_nominal) ?></td>
                        <td><?= number_format($summary->total_silver) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                  <?php if ($has_fastmoving) : ?>
                    <?php foreach ($extravaganza_summary_fastmoving as $summary) : ?>
                      <tr>
                        <td><span class="badge badge-success">fastmoving</span></td>
                        <td>Rp <?= number_format($summary->total_nominal) ?></td>
                        <td><?= number_format($summary->total_silver) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
              <table class="table align-items-center table-flush table-striped table-hover">
                <thead class="thead-light bg-primary text-white">
                  <tr>
                    <th scope="col">TOT</th>
                    <th scope="col">SILVER</th>
                    <th scope="col">GOLD</th>
                    <th scope="col">PLATINUM</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="font-weight-bold">
                    <td>Total</td>
                    <td><?= number_format($total_silver) ?></td>
                    <td><?= number_format($total_gold) ?></td>
                    <td><?= number_format($total_platinum) ?></td>
                  </tr>
                </tbody>
              </table>

              <!-- <table class="table align-items-center table-flush table-striped table-hover" id="tbkonversi">
                <thead class="thead-light bg-primary text-white">
                  <tr>
                    <th scope="col">TOT</th>
                    <th scope="col">SILVER</th>
                    <th scope="col">GOLD</th>
                    <th scope="col">PLATINUM</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="font-weight-bold">
                    <td>Total</td>
                    <td><?= number_format($konv_silver) ?></td>
                    <td><?= number_format($konv_gold) ?></td>
                    <td><?= number_format($konv_platinum) ?></td>
                  </tr>
                </tbody>
              </table> -->

            </div>
          <?php else : ?>
            <div class="alert alert-info">Belum ada data point.</div>
          <?php endif; ?>

          <ul class="nav nav-tabs mt-4" role="tablist" id="extravaganza-tabs">
            <li class="nav-item">
              <a class="nav-link active" href="#" data-extravaganza-filter="all">Semua</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-extravaganza-filter="ABC">ABC</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-extravaganza-filter="fastmoving">Fastmoving</a>
            </li>
          </ul>

          <div class="table-responsive mt-3">
            <table class="table align-items-center table-flush table-striped table-hover">
              <thead class="thead-light bg-primary text-white">
                <tr>
                  <th scope="col">Order</th>
                  <th scope="col">Status</th>
                  <th scope="col">Kategori</th>
                  <th scope="col">Qty</th>
                  <th scope="col">Harga</th>
                  <th scope="col">Nominal</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($extravaganza_history) > 0) : ?>
                  <?php foreach ($extravaganza_history as $row) : ?>
                    <tr data-category="<?= $row->product_category ?>">
                      <td><?= $row->order_number ?></td>
                      <td><?= $row->order_status ?></td>
                      <td>
                        <?php if ($row->product_category == 'ABC') : ?>
                          <span class="badge badge-primary">ABC</span>
                        <?php elseif ($row->product_category == 'fastmoving') : ?>
                          <span class="badge badge-success">fastmoving</span>
                        <?php else : ?>
                          <span class="badge badge-secondary"><?= $row->product_category ?></span>
                        <?php endif; ?>
                      </td>
                      <td><?= number_format($row->order_qty) ?></td>
                      <td>Rp <?= format_rupiah($row->order_price) ?></td>
                      <td>Rp <?= format_rupiah($row->nominal_belanja) ?></td>
                    </tr>
                  <?php endforeach; ?>
                  <tr class="extravaganza-empty" style="display:none;">
                    <td colspan="9">Tidak ada data untuk kategori ini.</td>
                  </tr>
                <?php else : ?>
                  <tr>
                    <td colspan="9">Belum ada data riwayat invoice.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <script>
            (function() {
              var tabContainer = document.getElementById('extravaganza-tabs');
              if (!tabContainer) return;

              var rows = document.querySelectorAll('#section_extravaganza_point tbody tr[data-category]');
              var emptyRow = document.querySelector('#section_extravaganza_point tbody tr.extravaganza-empty');

              function applyFilter(filter) {
                var visible = 0;
                rows.forEach(function(row) {
                  var match = (filter === 'all' || row.getAttribute('data-category') === filter);
                  row.style.display = match ? '' : 'none';
                  if (match) visible++;
                });
                if (emptyRow) {
                  emptyRow.style.display = (visible === 0 && rows.length > 0) ? '' : 'none';
                }
              }

              tabContainer.querySelectorAll('[data-extravaganza-filter]').forEach(function(tab) {
                tab.addEventListener('click', function(e) {
                  e.preventDefault();
                  tabContainer.querySelectorAll('.nav-link').forEach(function(t) {
                    t.classList.remove('active');
                  });
                  tab.classList.add('active');
                  applyFilter(tab.getAttribute('data-extravaganza-filter'));
                });
              });

              applyFilter('all');
            })();
          </script>
        </div>
      </div>

      <!-- VIRTUAL ACCOUNT -->
      <!-- <div class="card card-primary" hidden>
        <div class="card-header">
          <h3 class="mb-0">Virtual Account Payment</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if ($va->va == 'yes') : ?>
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">VA CODE</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?= $customer->va_code ?></td>
                    <td>ACTIVE</td>
                  </tr>
                </tbody>
              </table>
            <?php else : ?>
              <?php
              $vacus  =  '62' . substr($customer->phone_number, 2);
              $idcus  = $customer->id;
              ?>
              <div class="alert alert-info">VA Belum Terbuat</div>
              <form action="<?= base_url('generate_va'); ?>" method="POST">
                <input type="text" name="idcus" id="idcus" value="<?= $idcus ?>">
                <input type="text" name="vacusno" id="vacusno" value="<?= $vacus ?>">
                <button type="submit" class="btn btn-warning">GENERATE VA</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div> -->


    </div> <!-- col -->
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modal-title-default">Hapus Pelanggan?</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="#" id="deleteCustomer" method="POST">

          <input type="hidden" name="id" value="" class="deleteID">

          <div class="modal-body">
            <p class="deleteText">Yakin ingin pelanggan ini? Semua data seperti data profil, order dan pembayaran juga akan dihapus.</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger btn-delete">Hapus</button>
            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $(document).on('click', '.btnDelete', function() {
        var id = $(this).data('id');

        $('.deleteID').val(id);
        $('#deleteModal').modal('show');
      });

      $('#deleteCustomer').submit(function(e) {
        e.preventDefault();

        var id = $('.deleteID').val();
        var btn = $('.btn-delete');

        btn.html('<i class="fa fa-spin fa-spinner"></i> Menghapus...');

        $.ajax({
          method: 'POST',
          url: '<?php echo site_url('admin/customers/api/delete'); ?>',
          data: {
            id: id
          },
          success: function(res) {
            if (res.code == 204) {
              btn.html('<i class="fa fa-check"></i> Terhapus!');

              setTimeout(() => {
                $('.deleteText').html('Data berhasil dihapus');
              }, 1000);
              setTimeout(() => {
                $('.deleteText').html('<i class="fa fa-spin fa-spinner"></i> Mengalihkan...');
              }, 2500);
              setTimeout(() => {
                window.location = '<?php echo site_url('admin/customers'); ?>';
              }, 4000);
            }
          }
        })
      });
    });
  </script>