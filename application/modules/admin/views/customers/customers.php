<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Kelola Pelanggan</h6>
        </div>
        <?php if (admin_role() == 'admin') : ?>
          <div class="col-lg-6 col-5 text-right">
            <a href="<?php echo site_url('admin/customers/add_new_customer'); ?>" class="btn btn-neutral">Tambah Pelanggan</a>
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
        <div class="card-header">
          <h3 class="mb-0">Pelanggan</h3>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-items-center table-flush" id="customerList" style="width: 100%">
              <thead class="thead-light">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Nama / Nama Toko</th>
                  <th scope="col">Email</th>
                  <th scope="col">No. HP</th>
                  <th scope="col">Alamat</th>
                  <th scope="col">Level</th>
                  <th scope="col">Sales</th>
                  <th scope="col">Status</th>
                  <th scope="col">Tanggal Terdaftar</th>
                  <th scope="col"></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>

      </div>
    </div>
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
            <p>Yakin ingin pelanggan ini? Semua data seperti data profil, order dan pembayaran juga akan dihapus.</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger btn-delete">Hapus</button>
            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modal-title-default">Nonaktifkan Pelanggan?</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="#" id="deactivateCustomer" method="POST">

          <input type="hidden" name="id" value="" class="deactivateID">

          <div class="modal-body">
            <p>Apakah anda yakin menonaktifkan akun customer ini?</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger btn-deactivate">Nonaktifkan</button>
            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="activateModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modal-title-default">Mengaktifkan Pelanggan?</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="#" id="activateCustomer" method="POST">

          <input type="hidden" name="id" value="" class="activateID">

          <div class="modal-body">
            <p>Apakah anda yakin menngaktifkan akun customer ini?</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger btn-activate">Aktifkan</button>
            <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <link href="<?php echo get_theme_uri('vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css', 'argon'); ?>" rel="stylesheet">

  <script src="<?php echo get_theme_uri('vendor/datatables.net/js/jquery.dataTables.min.js', 'argon'); ?>"></script>
  <script src="<?php echo get_theme_uri('vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js', 'argon'); ?>"></script>
  <script src="<?php echo base_url('assets/plugins/datatables.lang.js'); ?>"></script>

  <script>
    $(document).ready(function() {
      $(document).on('click', '.btnDelete', function() {
        var id = $(this).data('id');
        var btn = $('.btn-delete');

        btn.html('Hapus');

        $('.deleteID').val(id);
        $('#deleteModal').modal('show');
      });

      $(document).on('click', '.btnDeactivate', function() {
        var id = $(this).data('id');
        var btn = $('.btn-deactivate');

        btn.html('Nonaktifkan');

        $('.deactivateID').val(id);
        $('#deactivateModal').modal('show');
      });

      $(document).on('click', '.btnActivate', function() {
        var id = $(this).data('id');
        var btn = $('.btn-activate');

        btn.html('Aktifkan');

        $('.activateID').val(id);
        $('#activateModal').modal('show');
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
                $('#deleteModal').modal('hide');
                table.ajax.reload();
                btn.html('Hapus');
              }, 1500);
            }
          }
        })
      });

      $('#deactivateCustomer').submit(function(e) {
        e.preventDefault();

        var id = $('.deactivateID').val();
        var btn = $('.btn-deactivate');

        btn.html('<i class="fa fa-spin fa-spinner"></i> Menonaktifkan...');

        $.ajax({
          method: 'POST',
          url: '<?php echo site_url('admin/customers/api/deactivate'); ?>',
          data: {
            id: id
          },
          success: function(res) {
            if (res.code == 204) {
              btn.html('<i class="fa fa-check"></i> Berhasil menonaktifkan Pelanggan!');

              setTimeout(() => {
                $('#deactivateModal').modal('hide');
                table.ajax.reload();
                btn.html('Hapus');
              }, 1500);
            }
          }
        })
      });

      $('#activateCustomer').submit(function(e) {
        e.preventDefault();

        var id = $('.activateID').val();
        var btn = $('.btn-activate');

        btn.html('<i class="fa fa-spin fa-spinner"></i> Mengaktifkan...');

        $.ajax({
          method: 'POST',
          url: '<?php echo site_url('admin/customers/api/activate'); ?>',
          data: {
            id: id
          },
          success: function(res) {
            if (res.code == 204) {
              btn.html('<i class="fa fa-check"></i> Berhasil mengaktifkan Pelanggan!');

              setTimeout(() => {
                $('#activateModal').modal('hide');
                table.ajax.reload();
                btn.html('Hapus');
              }, 1500);
            }
          }
        })
      });

      var role = '<?= admin_role(); ?>';
      var table = $('#customerList').DataTable({
        "ajax": "<?php echo site_url('admin/customers/api/customers'); ?>",
        "columns": [{
            "data": "id"
          },
          {
            "data": function(data, type, row) {
              var url = window.location.href.split('?')[0].replace('#', '');
              url = url + '/view/' + data.id;

              return '<a href="' + url + '">' + data.name + '</a> <br> <a href="' + url + '">' + data.shop_name + ' <br/></a>';
            }
          },
          {
            "data": "email"
          },
          {
            "data": "phone_number"
          },
          {
            "data": "address"
          },
          {
            "data": function(data, type, row) {
              var level = '';
              if (data.level == 1) {
                level = '<span class="badge badge-info">UMUM</span>';
              } else if (data.level == 2) {
                level = '<span class="badge badge-info">R1</span>';
              } else if (data.level == 3) {
                level = '<span class="badge badge-info">R2</span>';
              }
              return level;
            }
          },
          {
            "data": function(data, type, row) {
              return data.sales_name;
            }
          },
          {
            "data": function(data, type, row) {
              var status = 'aktiv';
              if (data.status == 1) {
                status = '<span class="badge badge-success">aktif</span>';
              } else {
                status = '<span class="badge badge-danger">non aktif</span>';
              }
              return status;
            }
          },
          {
            "data": function(data, type, row) {
              return data.register_date;
            }
          },
          {
            "mRender": function(data, type, row) {
              var url = window.location.href.split('?')[0].replace('#', '');
              url = url + '/edit/' + row.id;
              var action = '';

              if (row.status == 1) {
                action = '<div class="text-right"><a href="#" data-id="' + row.id + '" class="btn btn-primary btn-sm btnDeactivate"><i class="fa fa-lock"></i></a> <a href="#" data-id="' + row.id + '" class="btn btn-danger btn-sm btnDelete"><i class="fa fa-trash"></i></a></div>';
              } else {
                action = '<div class="text-right"><a href="#" data-id="' + row.id + '" class="btn btn-primary btn-sm btnActivate"><i class="fa fa-unlock"></i></a> <a href="#" data-id="' + row.id + '" class="btn btn-danger btn-sm btnDelete"><i class="fa fa-trash"></i></a></div>';
              }
              if (role == 'admin' || role == 'adminonline') {
                return action;
              } else {
                return '';
              }
            }
          },
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
        }
      });
    });
  </script>
