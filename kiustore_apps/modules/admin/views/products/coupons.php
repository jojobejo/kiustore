<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Kelola Kupon Belanja</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin'); ?>"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin/products'); ?>">Produk</a></li>
              <li class="breadcrumb-item active" aria-current="page">Kupon</li>
            </ol>
          </nav>
        </div>
        <?php if (admin_role() == 'admin' || admin_role() == 'adminonline') : ?>
          <div class="col-lg-6 col-5 text-right">
            <a href="#" data-target="#addModal" data-toggle="modal" class="btn btn-sm btn-neutral">Tambah</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col">
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0 d-flex justify-content-between align-items-center">
          <h3 class="mb-0">Kategori Kupon</h3>
          <?php if (admin_role() == 'admin' || admin_role() == 'adminonline') : ?>
            <button type="button" class="btn btn-sm btn-danger btnBulkDelete" disabled>
              <i class="fa fa-trash"></i> Hapus Terpilih
            </button>
          <?php endif; ?>
        </div>

        <div class="packageContainer">
          <!-- Light table -->
          <div class="table-responsive">
            <table class="table align-items-center table-flush" id="packageList" style="width: 100%">
              <thead class="thead-light">
                <tr>
                  <th scope="col" style="width: 48px;">
                    <?php if (admin_role() == 'admin' || admin_role() == 'adminonline') : ?>
                      <input type="checkbox" id="checkAllCoupons" aria-label="Pilih semua kupon">
                    <?php endif; ?>
                  </th>
                  <th scope="col">#</th>
                  <th scope="col">Nama</th>
                  <th schope="col">Kode</th>
                  <th schope="col">Potongan</th>
                  <th schope="col">Tanggal Mulai</th>
                  <th schope="col">Tanggal Selesai</th>
                  <th schope="col">Status</th>
                  <th scope="col"></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>


  <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-body p-0">
          <div class="card bg-secondary border-0 mb-0">
            <div class="card-header bg-transparent">
              <h3 class="card-heading text-center mt-2">Tambah Kupon</h3>
            </div>
            <div class="card-body px-lg-5 py-lg-5">
              <form role="form" action="#" method="POST" id="addCouponForm">

                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-box-2"></i></span>
                    </div>
                    <input name="name" class="form-control" placeholder="Nama kupon" type="text" maxlength="255" required>
                  </div>
                  <div class="text-danger err name-error"></div>
                </div>

                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-cog"></i></span>
                    </div>
                    <input name="code" class="form-control" placeholder="Kode kupon " type="text" maxlength="255" required>
                  </div>
                  <div class="text-danger err code-error"></div>
                </div>

                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Rp</span>
                    </div>
                    <input name="credit" class="form-control" placeholder="Potongan harga " type="text" required>
                  </div>
                  <div class="text-danger err credit-error"></div>
                </div>

                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                    </div>
                    <input name="start_date" class="form-control" placeholder="Tanggal mulai" type="date" required>
                  </div>
                  <div class="text-danger err start-date-error"></div>
                </div>

                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                    </div>
                    <input name="expired_date" class="form-control" placeholder="Tanggal kadaluarsa" type="date" required>
                  </div>
                  <div class="text-danger err expired-date-error"></div>
                </div>

                <div class="text-left">
                  <button type="button" class="btn btn-secondary my-4" data-dismiss="modal">Batal</button>
                </div>
                <div class="float-right" style="margin-top: -90px">
                  <button type="submit" class="btn btn-primary my-4 addPackageBtn">Tambah</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modal-title-default">Hapus Kupon</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="#" id="deleteCoupon" method="POST">

          <input type="hidden" name="id" value="" class="deleteID">

          <div class="modal-body">
            <p>Yakin ingin menghapus? Tindakan ini tidak dapat dibatalkan.</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger btn-delete">Hapus</button>
            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="bulk-delete-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="bulk-delete-modal-title">Hapus Kupon Terpilih</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="#" id="bulkDeleteCoupon" method="POST">
          <div class="modal-body">
            <p>Yakin ingin menghapus <strong><span class="bulk-delete-count">0</span> kupon</strong>? Tindakan ini tidak dapat dibatalkan.</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger btn-bulk-delete">Hapus Terpilih</button>
            <button type="button" class="btn btn-link ml-auto" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-body p-0">
          <div class="card bg-secondary border-0 mb-0">
            <div class="card-header bg-transparent">
              <h3 class="card-heading text-center mt-2">Edit Kupon</h3>
            </div>
            <div class="card-body px-lg-5 py-lg-5">
              <form role="form" action="#" method="POST" id="editCouponForm">

                <input type="hidden" name="id" value="" class="edit-id">

                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-box-2"></i></span>
                    </div>
                    <input name="name" class="form-control edit-name" placeholder="Nama kupon" type="text" maxlength="255" required>
                  </div>
                  <div class="text-danger err name-error"></div>
                </div>

                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-cog"></i></span>
                    </div>
                    <input name="code" class="form-control edit-code" placeholder="Kode kupon " type="text" maxlength="255" required>
                  </div>
                  <div class="text-danger err code-error"></div>
                </div>

                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Rp</span>
                    </div>
                    <input name="credit" class="form-control edit-credit" placeholder="Potongan harga " type="text" required>
                  </div>
                  <div class="text-danger err credit-error"></div>
                </div>

                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                    </div>
                    <input name="start_date" class="form-control edit-start" placeholder="Tanggal mulai" type="date" required>
                  </div>
                  <div class="text-danger err start-date-error"></div>
                </div>

                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                    </div>
                    <input name="expired_date" class="form-control edit-expired" placeholder="Tanggal kadaluarsa" type="date" required>
                  </div>
                  <div class="text-danger err expired-date-error"></div>
                </div>

                <div class="form-group mb-3">
                  <label for="av" class="form-control-label">
                    <input type="checkbox" name="is_active" value="1" id="av"> Kupon ini masih tersedia
                  </label>
                </div>

                <div class="text-left">
                  <button type="button" class="btn btn-secondary my-4" data-dismiss="modal">Batal</button>
                </div>
                <div class="float-right" style="margin-top: -90px">
                  <button type="submit" class="btn btn-primary my-4 editPackageBtn">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <link href="<?php echo get_theme_uri('vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css', 'argon'); ?>" rel="stylesheet">

  <script src="<?php echo get_theme_uri('vendor/datatables.net/js/jquery.dataTables.min.js', 'argon'); ?>"></script>
  <script src="<?php echo get_theme_uri('vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js', 'argon'); ?>"></script>
  <script src="<?php echo base_url('assets/plugins/datatables.lang.js'); ?>"></script>
  <script>
    $(document).ready(function() {
      var selectedCouponIds = {};
      var role = '<?= admin_role(); ?>';

      function getSelectedCouponIds() {
        return Object.keys(selectedCouponIds);
      }

      function updateBulkDeleteState() {
        var selectedCount = getSelectedCouponIds().length;
        $('.bulk-delete-count').text(selectedCount);
        $('.btnBulkDelete').prop('disabled', selectedCount === 0);
      }

      function syncCurrentPageCheckboxes(api) {
        var selectableRows = 0;
        var selectedRows = 0;

        api.rows({
          page: 'current'
        }).every(function() {
          var row = this.data();
          var isSelected = !!selectedCouponIds[row.id];
          var checkbox = $(this.node()).find('.coupon-check');

          checkbox.prop('checked', isSelected);

          if (checkbox.length) {
            selectableRows++;
          }

          if (isSelected) {
            selectedRows++;
          }
        });

        $('#checkAllCoupons')
          .prop('checked', selectableRows > 0 && selectableRows === selectedRows)
          .prop('indeterminate', selectedRows > 0 && selectedRows < selectableRows);
        updateBulkDeleteState();
      }

      $(document).on('click', '.btnDelete', function() {
        var id = $(this).data('id');

        $('.deleteID').val(id);
        $('#deleteModal').modal('show');
      });

      $(document).on('click', '.btnEdit', function() {
        var id = $(this).data('id');

        $.ajax({
          method: 'GET',
          url: '<?php echo site_url('admin/products/coupon_api?action=view_data'); ?>',
          data: {
            id: id
          },
          success: function(res) {
            if (res.data) {
              var d = res.data;

              $('.edit-id').val(d.id);
              $('.edit-name').val(d.name);
              $('.edit-code').val(d.code);
              $('.edit-credit').val(d.credit);
              $('.edit-start').val(d.start_date);
              $('.edit-expired').val(d.expired_date);

              if (d.is_active == 1) {
                $('#av').attr('checked', true);
              }

              $('#editModal').modal('show');
            }
          }
        });
      });

      $('#editCouponForm').submit(function(e) {
        e.preventDefault();

        var btn = $('.editPackageBtn');
        var id = $('.edit-id').val();
        var data = $(this).serialize();
        btn.html('<i class="fa fa-spin fa-spinner"></i> Menyimpan...').attr('disabled', true);

        $.ajax({
          method: 'POST',
          url: '<?php echo site_url('admin/products/coupon_api?action=edit_coupon'); ?>',
          data: data,
          success: function(res) {
            if (res.code == 201) {
              btn.html('<i class="fa fa-check"></i> Berhasil').removeAttr('disabled');

              setTimeout(() => {
                $('#editModal').modal('hide');
                table.ajax.reload();
                btn.html('Simpan');
              }, 1500);
            }
          }
        })
      });

      $(document).on('change', '.coupon-check', function() {
        var id = $(this).val();

        if ($(this).is(':checked')) {
          selectedCouponIds[id] = true;
        } else {
          delete selectedCouponIds[id];
        }

        syncCurrentPageCheckboxes(table);
      });

      $('#checkAllCoupons').change(function() {
        var isChecked = $(this).is(':checked');

        table.rows({
          page: 'current'
        }).every(function() {
          var row = this.data();

          if (isChecked) {
            selectedCouponIds[row.id] = true;
          } else {
            delete selectedCouponIds[row.id];
          }
        });

        syncCurrentPageCheckboxes(table);
      });

      $('.btnBulkDelete').click(function() {
        var selectedCount = getSelectedCouponIds().length;

        if (selectedCount === 0) {
          return;
        }

        $('.bulk-delete-count').text(selectedCount);
        $('#bulkDeleteModal').modal('show');
      });

      $('#bulkDeleteCoupon').submit(function(e) {
        e.preventDefault();

        var ids = getSelectedCouponIds();
        var btn = $('.btn-bulk-delete');

        if (ids.length === 0) {
          return;
        }

        btn.html('<i class="fa fa-spin fa-spinner"></i> Menghapus...').attr('disabled', true);

        $.ajax({
          method: 'POST',
          url: '<?php echo site_url('admin/products/coupon_api?action=delete_coupons'); ?>',
          data: {
            ids: ids
          },
          success: function(res) {
            if (res.code == 204) {
              selectedCouponIds = {};
              btn.html('<i class="fa fa-check"></i> Terhapus!');

              setTimeout(() => {
                $('#bulkDeleteModal').modal('hide');
                table.ajax.reload();
                $('#checkAllCoupons').prop('checked', false);
                updateBulkDeleteState();
                btn.html('Hapus Terpilih').removeAttr('disabled');
              }, 1500);
            } else {
              btn.html('Hapus Terpilih').removeAttr('disabled');
            }
          },
          error: function() {
            btn.html('Hapus Terpilih').removeAttr('disabled');
          }
        });
      });

      $('#deleteCoupon').submit(function(e) {
        e.preventDefault();

        var id = $('.deleteID').val();
        var btn = $('.btn-delete');

        btn.html('<i class="fa fa-spin fa-spinner"></i> Menghapus...');

        $.ajax({
          method: 'POST',
          url: '<?php echo site_url('admin/products/coupon_api?action=delete_coupon'); ?>',
          data: {
            id: id
          },
          success: function(res) {
            if (res.code == 204) {
              btn.html('<i class="fa fa-check"></i> Terhapus!');

              setTimeout(() => {
                $('#deleteModal').modal('hide');
                delete selectedCouponIds[id];
                table.ajax.reload();
                updateBulkDeleteState();
                btn.html('Hapus');
              }, 1500);
            }
          }
        })
      });

      var table = $('#packageList').DataTable({
        "ajax": "<?php echo site_url('admin/products/coupon_api?action=coupon_list'); ?>",
        "columns": [{
            "data": null,
            "orderable": false,
            "searchable": false,
            "mRender": function(data, type, row) {
              if (role == 'admin' || role == 'adminonline') {
                return '<input type="checkbox" class="coupon-check" value="' + row.id + '" aria-label="Pilih kupon">';
              } else {
                return '';
              }
            }
          },
          {
            "data": "id"
          },
          {
            "data": "name"
          },
          {
            "data": "code"
          },
          {
            "data": "credit"
          },
          {
            "data": "start_date"
          },
          {
            "data": "expired_date"
          },
          {
            "data": "is_active"
          },
          {
            "mRender": function(data, type, row) {
              if (role == 'admin' || role == 'adminonline') {
                return '<div class="text-right"><a href="#" data-id="' + row.id + '" class="btn btn-warning btn-sm btnEdit"><i class="fa fa-edit"></i></a><a href="#" data-id="' + row.id + '" class="btn btn-danger btn-sm btnDelete"><i class="fa fa-trash"></i></a></div>';
              } else {
                return '';
              }
            }
          }
        ],
        "language": {
          "search": "Cari:",
          "lengthMenu": "Menampilkan _MENU_ data",
          "info": "Menampilkan _START_ sampai _END_ data dari _TOTAL_ data",
          "infoEmpty": "Tidak ada data yang ditampilkan",
          "infoFiltered": "(dari total _MAX_ data)",
          "zeroRecords": "Tidak ada hasil pencarian ditemukan",
          "paginate": {
            "first": "&laquo;",
            "last": "&raquo;",
            "next": "&rsaquo;",
            "previous": "&lsaquo;"
          }
        },
        "drawCallback": function() {
          syncCurrentPageCheckboxes(this.api());
        }
      });


      $('#addCouponForm').submit(function(e) {
        e.preventDefault();

        var data = $(this).serialize();
        var btn = $('.addPackageBtn');

        btn.html('<i class="fa fa-spin fa-spinner"></i> Menambah...');
        $('.err').empty();

        $.ajax({
          method: 'POST',
          url: '<?php echo site_url('admin/products/coupon_api?action=add_coupon'); ?>',
          data: data,
          context: this,
          success: function(res) {
            if (res.data) {
              btn.html('<i class="fa fa-check"></i> Berhasil!');

              setTimeout(function() {
                $('#addCouponForm .form-control').val(null);
                btn.html('Tambah');
              }, 2000);
              setTimeout(() => {
                $('#addModal').modal('hide');
              }, 2222);

              table.ajax.reload();
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            btn.html('Tambah');

            var errors = xhr.responseJSON.errors;

            $.each(errors, function(keys, val) {
              $.each(val, function(key, val) {
                $('.' + keys + '-error').text(val);
              });
            });
          }
        })
      })
    });
  </script>
