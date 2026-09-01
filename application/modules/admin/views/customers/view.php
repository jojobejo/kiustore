<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
  .customer-view-page .card {
    overflow: hidden;
  }

  .customer-view-page .profile-form-table td:first-child {
    width: 220px;
    color: #525f7f;
    font-weight: 600;
    vertical-align: middle;
  }

  .customer-view-page .profile-form-table td:last-child {
    vertical-align: middle;
  }

  .customer-view-page .customer-table-toolbar {
    align-items: center;
    display: flex;
    gap: 1rem;
    justify-content: space-between;
    margin-bottom: 1rem;
  }

  .customer-view-page .customer-tabs {
    flex-wrap: wrap;
    gap: .5rem;
  }

  .customer-view-page .customer-tabs .nav-link {
    border: 1px solid #e9ecef;
    border-radius: .375rem;
    color: #525f7f;
    font-size: .8125rem;
    font-weight: 700;
    padding: .5rem .875rem;
  }

  .customer-view-page .customer-tabs .nav-link.active {
    background: #5e72e4;
    border-color: #5e72e4;
    color: #fff;
    box-shadow: 0 4px 10px rgba(94, 114, 228, .2);
  }

  .customer-view-page .customer-search {
    min-width: 260px;
    position: relative;
  }

  .customer-view-page .customer-search i {
    color: #adb5bd;
    left: .75rem;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
  }

  .customer-view-page .customer-search input {
    padding-left: 2.25rem;
  }

  .customer-view-page .customer-data-table {
    margin-bottom: 0;
    min-width: 720px;
  }

  .customer-view-page #section_extravaganza_point .customer-data-table,
  .customer-view-page #section_extravaganza_point table {
    min-width: 980px;
    white-space: nowrap;
  }

  .customer-view-page .customer-table-footer {
    align-items: center;
    display: flex;
    justify-content: space-between;
    padding-top: 1rem;
  }

  @media (max-width: 767.98px) {
    .customer-view-page .customer-table-toolbar,
    .customer-view-page .customer-table-footer {
      align-items: stretch;
      flex-direction: column;
    }

    .customer-view-page .customer-search {
      min-width: 100%;
    }

    .customer-view-page .profile-form-table td:first-child {
      width: 150px;
    }
  }
</style>
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
<div class="container-fluid mt--6 customer-view-page">
  <div class="row">
    <div class="col-12">
      <div class="card-wrapper">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">Data Pelanggan</h3>
            <?php if ($flash) : ?>
              <span class="float-right text-success font-weight-bold" style="margin-top: -30px;"><?php echo $flash; ?></span>
            <?php endif; ?>
          </div>
          <form action="<?php echo site_url('admin/customers/api/edit'); ?>" method="POST">
            <div class="card-body p-0">
              <table class="table align-items-center table-flush table-hover profile-form-table">
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
              </table>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <input type="submit" class="btn btn-primary" value="UBAH">
              <a href="#" data-id="<?php echo $customer->id; ?>" class="btn btn-danger btn-sm btnDelete"><i class="fa fa-trash"></i></a>
            </div>
          </form>

        </div>

      </div>

    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="mb-0">Riwayat Order</h3>
        </div>
        <div class="card-body customer-async-panel" id="customer-orders-panel">
          <div class="text-center text-muted py-4">
            <i class="fa fa-spin fa-spinner"></i> Memuat riwayat order...
          </div>
        </div>
      </div>


      <div class="card card-primary">
        <div class="card-header">
          <h3 class="mb-0">Riwayat Pembayaran</h3>
        </div>
        <div class="card-body customer-async-panel" id="customer-payments-panel">
          <div class="text-center text-muted py-4">
            <i class="fa fa-spin fa-spinner"></i> Memuat riwayat pembayaran...
          </div>
        </div>
      </div>

      <div class="card card-primary" id="section_extravaganza_point">
        <div class="card-header">
          <h3 class="mb-0">Point Extravaganza</h3>
        </div>
        <div class="card-body customer-async-panel" id="customer-extravaganza-panel">
          <div class="text-center text-muted py-4">
            <i class="fa fa-spin fa-spinner"></i> Memuat point extravaganza...
          </div>
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
      var customerId = '<?php echo (int) $customer->id; ?>';
      var detailUrl = '<?php echo site_url('admin/customers/api/customer_detail'); ?>';

      function setPanelContent(selector, html) {
        var panel = $(selector);
        panel.html(html);
        panel.removeClass('p-0');
      }

      function setPanelError(selector) {
        $(selector)
          .removeClass('p-0')
          .html('<div class="alert alert-danger mb-0">Data belum berhasil dimuat. Silakan refresh halaman.</div>');
      }

      function bindExtravaganzaFilter() {
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
      }

      function initCustomerDataTable(widget) {
        var currentFilter = $(widget).find('.customer-tabs .nav-link.active').data('filter') || '';
        var currentPage = 1;
        var pageSize = parseInt($(widget).data('page-size'), 10) || 10;
        var rows = $(widget).find('.customer-data-table tbody tr[data-filter]');
        var search = $(widget).find('.customer-table-search');
        var info = $(widget).find('.customer-table-info');
        var empty = $(widget).find('.customer-table-empty');
        var prev = $(widget).find('.customer-page-prev');
        var next = $(widget).find('.customer-page-next');

        function matchedRows() {
          var keyword = (search.val() || '').toLowerCase().trim();

          return rows.filter(function() {
            var row = $(this);
            var filterMatch = !currentFilter || row.data('filter') === currentFilter;
            var searchText = (row.data('search') || row.text()).toString().toLowerCase();
            var searchMatch = !keyword || searchText.indexOf(keyword) !== -1;

            return filterMatch && searchMatch;
          });
        }

        function render() {
          var matches = matchedRows();
          var total = matches.length;
          var totalPages = Math.max(Math.ceil(total / pageSize), 1);

          if (currentPage > totalPages) {
            currentPage = totalPages;
          }

          var start = (currentPage - 1) * pageSize;
          var end = start + pageSize;

          rows.hide();
          matches.slice(start, end).show();

          empty.toggle(total === 0);
          info.text(total === 0 ? '0 data' : 'Menampilkan ' + (start + 1) + '-' + Math.min(end, total) + ' dari ' + total + ' data');
          prev.prop('disabled', currentPage <= 1 || total === 0);
          next.prop('disabled', currentPage >= totalPages || total === 0);
        }

        $(widget).find('.customer-tabs .nav-link').on('click', function(e) {
          e.preventDefault();
          $(widget).find('.customer-tabs .nav-link').removeClass('active');
          $(this).addClass('active');
          currentFilter = $(this).data('filter') || '';
          currentPage = 1;
          render();
        });

        search.on('input', function() {
          currentPage = 1;
          render();
        });

        prev.on('click', function() {
          if (currentPage > 1) {
            currentPage--;
            render();
          }
        });

        next.on('click', function() {
          currentPage++;
          render();
        });

        render();
      }

      function initCustomerDataTables() {
        $('.customer-table-widget').each(function() {
          initCustomerDataTable(this);
        });
      }

      $.ajax({
        method: 'GET',
        url: detailUrl,
        data: {
          id: customerId
        },
        dataType: 'json',
        cache: false,
        success: function(res) {
          if (res.code == 200) {
            setPanelContent('#customer-orders-panel', res.orders_html);
            setPanelContent('#customer-payments-panel', res.payments_html);
            setPanelContent('#customer-extravaganza-panel', res.extravaganza_html);
            initCustomerDataTables();
            bindExtravaganzaFilter();
          } else {
            setPanelError('#customer-orders-panel');
            setPanelError('#customer-payments-panel');
            setPanelError('#customer-extravaganza-panel');
          }
        },
        error: function() {
          setPanelError('#customer-orders-panel');
          setPanelError('#customer-payments-panel');
          setPanelError('#customer-extravaganza-panel');
        }
      });

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
