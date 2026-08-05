<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Kelola Users</h6>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <a href="<?php echo site_url('admin/admin/add_new_admin'); ?>" class="btn btn-neutral">Tambah Users</a>
            </div>
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
              <h3 class="mb-0">Users</h3>
            </div>

            <div class="card-body border-bottom">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-md-0">
                    <label class="form-control-label" for="filterRole">Filter Role</label>
                    <select id="filterRole" class="form-control form-control-sm">
                      <option value="">Semua Role</option>
                      <?php foreach (($roles ?? array()) as $role) : ?>
                        <option value="<?php echo html_escape($role->role); ?>"><?php echo html_escape($role->role); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-md-0">
                    <label class="form-control-label" for="filterInternal">Filter Akun</label>
                    <select id="filterInternal" class="form-control form-control-sm">
                      <option value="">Semua Akun</option>
                      <option value="1">Internal</option>
                      <option value="0">Non Internal</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="adminList" style="width: 100%">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Akun</th>
                                <th scope="col">Tanggal Terdaftar</th>
                                <th scope="col">Status</th>
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
            <h6 class="modal-title" id="modal-title-default">Hapus ?</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <form action="#" id="deleteUser" method="POST">

          <input type="hidden" name="id" value="" class="deleteID">

        <div class="modal-body">
            <p>Apakah anda yakin ? Semua data seperti data profil, order dan pembayaran juga akan dihapus.</p>
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
            <h6 class="modal-title" id="modal-title-default">Nonaktifkan ?</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <form action="#" id="deactivateUser" method="POST">

          <input type="hidden" name="id" value="" class="deactivateID">

        <div class="modal-body">
            <p>Apakah anda yakin menonaktifkan akun ini?</p>
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
            <h6 class="modal-title" id="modal-title-default">Mengaktifkan user ?</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <form action="#" id="activateUser" method="POST">

          <input type="hidden" name="id" value="" class="activateID">

        <div class="modal-body">
            <p>Apakah anda yakin menngaktifkan akun ini?</p>
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
    function escapeHtml(value) {
      if (value === null || value === undefined) {
        return '';
      }

      return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    $(document).on('click', '.btnDelete', function() {
      var id  = $(this).data('id');
      var btn = $('.btn-delete');

      btn.html('Hapus');

      $('.deleteID').val(id);
      $('#deleteModal').modal('show');
    });

    $(document).on('click', '.btnDeactivate', function() {
      var id  = $(this).data('id');
      var btn = $('.btn-deactivate');

      btn.html('Nonaktifkan');

      $('.deactivateID').val(id);
      $('#deactivateModal').modal('show');
    });

    $(document).on('click', '.btnActivate', function() {
      var id  = $(this).data('id');
      var btn = $('.btn-activate');

      btn.html('Aktifkan');

      $('.activateID').val(id);
      $('#activateModal').modal('show');
    });

    $(document).on('click', '.btnInternal', function(e) {
      e.preventDefault();

      var btn = $(this);
      var id = btn.data('id');
      var nextStatus = btn.data('next');
      var oldHtml = btn.html();

      btn.html('<i class="fa fa-spin fa-spinner"></i>');

      $.ajax({
        method: 'POST',
        url: '<?php echo site_url('admin/admin/api/toggle_internal'); ?>',
        data: {
          id: id,
          is_internal: nextStatus
        },
        success: function(res) {
          if (res.code == 200) {
            table.ajax.reload(null, false);
          } else {
            btn.html(oldHtml);
            alert('Gagal memperbarui status akun internal. Pastikan migrasi database sudah dijalankan.');
          }
        },
        error: function() {
          btn.html(oldHtml);
          alert('Gagal memperbarui status akun internal.');
        }
      });
    });

    $('#deleteUser').submit(function(e) {
      e.preventDefault();

      var id = $('.deleteID').val();
      var btn = $('.btn-delete');

      btn.html('<i class="fa fa-spin fa-spinner"></i> Menghapus...');

      $.ajax({
        method: 'POST',
        url: '<?php echo site_url('admin/admin/api/delete'); ?>',
        data: {
            id: id
        },
        success: function (res) {
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

    $('#deactivateUser').submit(function(e) {
      e.preventDefault();

      var id = $('.deactivateID').val();
      var btn = $('.btn-deactivate');

      btn.html('<i class="fa fa-spin fa-spinner"></i> Menonaktifkan...');

      $.ajax({
        method: 'POST',
        url: '<?php echo site_url('admin/admin/api/deactivate'); ?>',
        data: {
            id: id
        },
        success: function (res) {
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

    $('#activateUser').submit(function(e) {
      e.preventDefault();

      var id = $('.activateID').val();
      var btn = $('.btn-activate');

      btn.html('<i class="fa fa-spin fa-spinner"></i> Mengaktifkan...');

      $.ajax({
        method: 'POST',
        url: '<?php echo site_url('admin/admin/api/activate'); ?>',
        data: {
            id: id
        },
        success: function (res) {
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

    var table = $('#adminList').DataTable({
      "ajax" : {
        "url": "<?php echo site_url('admin/admin/api/users'); ?>",
        "data": function (d) {
          d.role = $('#filterRole').val();
          d.is_internal = $('#filterInternal').val();
        }
      },
      "columns" : [
        {"data": "id"},
        {"data": function (data, type, row) {
            var baseUrl = '<?php echo site_url(); ?>';
            var url = data.role == 'customer' ? baseUrl + 'admin/customers/view/' + data.id : baseUrl + 'admin/admin/edit/' + data.id;
            var name = escapeHtml(data.display_name || data.name || '-');
            var shopName = data.shop_name ? '<br><small class="text-muted">' + escapeHtml(data.shop_name) + '</small>' : '';

            return '<a href="'+ url +'">'+ name +'</a>' + shopName;
        }
        },
        {"data": "email"},
        {"data": "role"},
        {"data": function (data, type, row) {
            if (data.is_internal == 1) {
              return '<span class="badge badge-warning">Internal</span>';
            }

            return '<span class="badge badge-light">Non Internal</span>';
          }
        },
        {"data": function (data, type, row) {
            return data.register_date;
          }
        },
        {"data": function (data, type, row) {
            var status = 'aktiv';
            if(data.status == 1){
              status = '<span class="badge badge-success">aktif</span>';
            } else {
              status = '<span class="badge badge-danger">non aktif</span>';
            }
            return status;
          }
        },
        {"mRender": function (data, type, row) {
            var url = window.location.href.split('?')[0].replace('#', '');
            url = url + '/edit/'+ row.id;
            var action = '';
            var internalNext = row.is_internal == 1 ? 0 : 1;
            var internalTitle = row.is_internal == 1 ? 'Tandai non internal' : 'Tandai internal';
            var internalIcon = row.is_internal == 1 ? 'fa-user-check' : 'fa-user-shield';
            var internalButton = '<a href="#" title="'+internalTitle+'" data-id="'+row.id+'" data-next="'+internalNext+'" class="btn btn-warning btn-sm btnInternal"><i class="fa '+internalIcon+'"></i></a> ';
            var customerUrl = '<?php echo site_url('admin/customers/view/'); ?>' + row.id;

            if (row.role == 'customer') {
              return '<div class="text-right">'+internalButton+'<a href="'+customerUrl+'" title="Lihat profil customer" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a></div>';
            }

            if(row.status == 1){
              action = '<div class="text-right">'+internalButton+'<a href="#" data-id="'+row.id+'" class="btn btn-primary btn-sm btnDeactivate"><i class="fa fa-lock"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-danger btn-sm btnDelete"><i class="fa fa-trash"></i></a></div>' ;
            } else {
              action = '<div class="text-right">'+internalButton+'<a href="#" data-id="'+row.id+'" class="btn btn-primary btn-sm btnActivate"><i class="fa fa-unlock"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-danger btn-sm btnDelete"><i class="fa fa-trash"></i></a></div>' ;
            }
            return action;
          }
        },
      ],
      "language" : {
        "search" : "Cari:",
        "lengthMenu" : "Menampilkan _MENU_ data",
        "info" : "Menampilkan _START_ sampai _END_ data dari _TOTAL_ data",
        "infoEmpty" : "Tidak ada data yang ditampilkan",
        "infoFiltered" : "(dari total _MAX_ data)",
        "zeroRecords" : "Tidak ada hasil pencarian ditemukan",
        "paginate": {
          "first":"&laquo;",
          "last":"&raquo;",
          "next":       "&rsaquo;",
          "previous":   "&lsaquo;"
        },
      }
    });

    $('#filterRole, #filterInternal').on('change', function() {
      table.ajax.reload();
    });
});
</script>
