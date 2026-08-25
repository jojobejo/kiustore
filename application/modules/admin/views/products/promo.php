<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Kelola Promo Belanja</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin'); ?>"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin/products'); ?>">Produk</a></li>
              <li class="breadcrumb-item active" aria-current="page">Promo</li>
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
        <div class="card-header border-0">
          <div class="row align-items-center">
            <div class="col">
              <h3 class="mb-0">Kategori Promo</h3>
            </div>
            <?php if (admin_role() == 'admin' || admin_role() == 'adminonline') : ?>
              <div class="col text-right">
                <button type="button" class="btn btn-danger btn-sm bulkDeleteBtn" disabled>
                  <i class="fa fa-trash"></i> Hapus Terpilih
                </button>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="packageContainer">
          <!-- Light table -->
          <div class="table-responsive">
            <table class="table align-items-center table-flush" id="promoList" style="width: 100%">
              <thead class="thead-light">
                <tr>
                  <th scope="col" class="text-center">
                    <?php if (admin_role() == 'admin' || admin_role() == 'adminonline') : ?>
                      <input type="checkbox" id="checkAllPromo">
                    <?php endif; ?>
                  </th>
                  <th scope="col">#</th>
                  <th scope="col">Nama Produk</th>
                  <th schope="col">Potongan Harga</th>
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
              <h3 class="card-heading text-center mt-2">Tambah Promo</h3>
            </div>
            <div class="card-body px-lg-5 py-lg-5">
              <form role="form" action="#" method="POST" id="addPromoForm">

                <div class="form-group">
                  <label class="form-control-label" for="add_product_id">Nama Produk:</label>
                  <select name="product_id" class="form-control promo-product-select" id="add_product_id" required>
                    <option value="">Pilih Produk</option>
                    <?php if (count($products) > 0) : ?>
                      <?php foreach ($products as $product) : ?>
                        <option value="<?php echo $product->id; ?>"><?php echo $product->name; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                  <div class="text-danger err name-error"></div>
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
                  <button type="submit" class="btn btn-primary my-4 addPromoBtn">Tambah</button>
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
          <h6 class="modal-title" id="modal-title-default">Hapus Promo</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="#" id="deletePromo" method="POST">

          <input type="hidden" name="id" value="" class="deleteID">
          <input type="hidden" name="ids" value="" class="deleteIDs">

          <div class="modal-body">
            <p class="deleteMessage">Yakin ingin menghapus? Tindakan ini tidak dapat dibatalkan.</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger btn-delete">Hapus</button>
            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Batal</button>
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
              <h3 class="card-heading text-center mt-2">Edit Promo</h3>
            </div>
            <div class="card-body px-lg-5 py-lg-5">
              <form role="form" action="#" method="POST" id="editPromoForm" onsubmit="return false">

                <input type="hidden" name="id" value="" class="edit-id">


                <div class="form-group">
                  <label class="form-control-label" for="edit_product_id">Nama Produk:</label>
                  <select name="product_id" class="form-control promo-product-select edit-product-id" id="edit_product_id" required>
                    <option value="">Pilih Produk</option>
                    <?php if (count($products) > 0) : ?>
                      <?php foreach ($products as $product) : ?>
                        <option value="<?php echo $product->id; ?>" <?php echo set_select('product_id', $product->id); ?>><?php echo $product->name; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                  <div class="text-danger err name-error"></div>
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
                    <input type="checkbox" name="is_active" value="1" id="av"> Promo ini masih tersedia
                  </label>
                </div>

                <div class="text-left">
                  <button type="button" class="btn btn-secondary my-4" data-dismiss="modal">Batal</button>
                </div>
                <div class="float-right" style="margin-top: -90px">
                  <button type="button" class="btn btn-primary my-4 editPromoBtn">Simpan</button>
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
  <style>
    .select2-container {
      width: 100% !important;
    }

    .select2-container .select2-selection--single {
      height: calc(2.75rem + 2px);
      border: 1px solid #cad1d7;
      border-radius: .375rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: calc(2.75rem + 2px);
      padding-left: .75rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: calc(2.75rem + 2px);
    }
  </style>
  <script>
    $(document).ready(function() {
      $('#add_product_id').select2({
        placeholder: 'Cari nama produk',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#addModal')
      });

      $('#edit_product_id').select2({
        placeholder: 'Cari nama produk',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#editModal')
      });

      $(document).on('click', '.btnDelete', function() {
        var id = $(this).data('id');

        $('.deleteID').val(id);
        $('.deleteIDs').val('');
        $('.deleteMessage').text('Yakin ingin menghapus promo ini? Tindakan ini tidak dapat dibatalkan.');
        $('#deleteModal').modal('show');
      });

      function getSelectedPromoIds() {
        return $('.promo-check:checked').map(function() {
          return $(this).val();
        }).get();
      }

      function updateBulkDeleteButton() {
        var selectedCount = getSelectedPromoIds().length;
        $('.bulkDeleteBtn').prop('disabled', selectedCount === 0);
      }

      $(document).on('change', '.promo-check', function() {
        var total = $('.promo-check').length;
        var selectedCount = getSelectedPromoIds().length;

        $('#checkAllPromo').prop('checked', total > 0 && total === selectedCount);
        updateBulkDeleteButton();
      });

      $(document).on('change', '#checkAllPromo', function() {
        $('.promo-check').prop('checked', $(this).prop('checked'));
        updateBulkDeleteButton();
      });

      $(document).on('click', '.bulkDeleteBtn', function() {
        var ids = getSelectedPromoIds();

        if (ids.length === 0) {
          return;
        }

        $('.deleteID').val('');
        $('.deleteIDs').val(ids.join(','));
        $('.deleteMessage').text('Yakin ingin menghapus ' + ids.length + ' promo terpilih? Tindakan ini tidak dapat dibatalkan.');
        $('#deleteModal').modal('show');
      });

      $(document).on('click', '.btnEdit', function() {
        var id = $(this).data('id');

        $.ajax({
          method: 'GET',
          url: '<?php echo site_url('admin/products/promo_api?action=view_data'); ?>',
          data: {
            id: id
          },
          success: function(res) {
            if (res.data) {
              var d = res.data;

              $('.edit-id').val(d.id);
              $('.edit-product-id').val(d.product_id).trigger('change');
              $('.edit-credit').val(d.credit);
              $('.edit-start').val(d.start_date);
              $('.edit-expired').val(d.expired_date);

              $('#av').prop('checked', d.is_active == 1);

              $('#editModal').modal('show');
            }
          }
        });
      });

      $(document).on('click', '.editPromoBtn', function(e) {
        e.preventDefault();

        var btn = $('.editPromoBtn');
        var id = $('.edit-id').val();
        var data = $('#editPromoForm').serialize();
        btn.html('<i class="fa fa-spin fa-spinner"></i> Menyimpan...').attr('disabled', true);

        $.ajax({
          method: 'POST',
          url: '<?php echo site_url('admin/products/promo_api?action=edit_promo'); ?>',
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

      $('#deletePromo').submit(function(e) {
        e.preventDefault();

        var id = $('.deleteID').val();
        var ids = $('.deleteIDs').val();
        var btn = $('.btn-delete');

        btn.html('<i class="fa fa-spin fa-spinner"></i> Menghapus...').attr('disabled', true);

        $.ajax({
          method: 'POST',
          url: '<?php echo site_url('admin/products/promo_api?action=delete_promo'); ?>',
          data: {
            id: id,
            ids: ids
          },
          success: function(res) {
            if (res.code == 204) {
              btn.html('<i class="fa fa-check"></i> Terhapus!');

              setTimeout(() => {
                $('#deleteModal').modal('hide');
                table.ajax.reload();
                $('#checkAllPromo').prop('checked', false);
                updateBulkDeleteButton();
                btn.html('Hapus').removeAttr('disabled');
              }, 1500);
            }
          },
          error: function() {
            btn.html('Hapus').removeAttr('disabled');
          }
        })
      });
      var role = '<?= admin_role(); ?>';
      var table = $('#promoList').DataTable({
        "ajax": "<?php echo site_url('admin/products/promo_api?action=promo_list'); ?>",
        "columns": [{
            "data": "id",
            "orderable": false,
            "searchable": false,
            "className": "text-center",
            render: function(data) {
              if (role == 'admin' || role == 'adminonline') {
                return '<input type="checkbox" class="promo-check" value="' + data + '">';
              }

              return '';
            }
          },
          {
            "data": "id",
            "orderable": false,
            "className": "text-center",
            render: function(data, type, row, meta) {
              return meta.row + meta.settings._iDisplayStart + 1;
            }
          },
          {
            "data": "product_name"
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
          },
        },
        "drawCallback": function() {
          $('#checkAllPromo').prop('checked', false);
          updateBulkDeleteButton();
        }
      });


      $('#addPromoForm').submit(function(e) {
        e.preventDefault();

        var data = $(this).serialize();
        var btn = $('.addPromoBtn');

        btn.html('<i class="fa fa-spin fa-spinner"></i> Menambah...');
        $('.err').empty();

        $.ajax({
          method: 'POST',
          url: '<?php echo site_url('admin/products/promo_api?action=add_promo'); ?>',
          data: data,
          context: this,
          success: function(res) {
            if (res.data) {
              btn.html('<i class="fa fa-check"></i> Berhasil!');

              setTimeout(function() {
                $('#addPromoForm')[0].reset();
                $('#add_product_id').val(null).trigger('change');
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
