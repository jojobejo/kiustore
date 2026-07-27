<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Rating Sales</h6>
        </div>
        <?php if (admin_role() == 'admin') : ?>
          <div class="col-lg-6 col-5 text-right">
            <!--    <a href="<?php echo site_url('admin/customers/add_new_customer'); ?>" class="btn btn-neutral">Tambah Pelanggan</a> -->
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
          <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

            <li class="nav-item" role="presentation">
              <a class="nav-link active" id="success-tab" data-toggle="pill" href="#success" role="tab" aria-controls="success" aria-selected="false">Rating
                <span class="badge badge-warning" id="info-success"> </span>
              </a>
            </li>

            <li class="nav-item" role="presentation">
              <a class="nav-link" id="average-tab" data-toggle="pill" href="#average" role="tab" aria-controls="average" aria-selected="false">Average Sales Perform
                <span class="badge badge-warning" id="info-average"> </span>
              </a>
            </li>
          </ul>
        </div>

        <div class="card-body p-0">

          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="success" role="tabpanel" aria-labelledby="success-tab">
              <div class="table-responsive">
                <table class="table align-items-center table-flush" id="orderList5" style="width: 100%">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">NO</th>
                      <th scope="col">Order ID</th>
                      <th scope="col">Customer</th>
                      <th scope="col">Sales</th>
                      <th scope="col">Rating</th>
                      <th scope="col">Keterangan</th>
                      <th scope="col">Tanggal</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

            <div class="tab-pane fade" id="average" role="tabpanel" aria-labelledby="average-tab">
              <div class="table-responsive">
                <table class="table align-items-center table-flush" id="orderList12" style="width: 100%">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">Sales</th>
                      <th scope="col">Pesanan</th>
                      <th scope="col">Rating</th>
                      <th scope="col">Nilai Rata-rata</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

          </div>
        </div>


      </div>

    </div>
  </div>
</div>


<!-- <link href="<?php echo get_theme_uri('vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css', 'argon'); ?>" rel="stylesheet">

<script src="<?php echo get_theme_uri('vendor/datatables.net/js/jquery.dataTables.min.js', 'argon'); ?>"></script>
<script src="<?php echo get_theme_uri('vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js', 'argon'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables.lang.js'); ?>"></script> -->

<script>
  $(document).ready(function() {
    var ids = $('#ids');
    $('#orderList tfoot th').each(function() {
      var title = $(this).text();
      $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });

    var role = '<?= admin_role(); ?>';


    var table = $('#orderList5').DataTable({
      "ajax": "<?php echo site_url('admin/orders/api/order_success_kadep'); ?>",
      "columns": [

        {
          "data": "id",
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          "data": function(data, type, row) {
            var url = "<?= base_url('admin/orders') ?>" + '/view/' + data.id;

            return '<a href="' + url + '"> #' + data.order_number + '</a>';
          }
        },
        {
          "data": "customer"
        },
        {
          "data": "sales"
        },
        {
          "data": "rating"
        },
        {
          "data": "rating_desc"
        },
        {
          "data": "finish_date"
        },
      ],
      "dom": '<"float-left mb-2"B><"float-right search1"f>rt<"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
      buttons: [
        'print',
        'copyHtml5',
        'excelHtml5',
        'pdfHtml5'
      ],
      initComplete: function() {
        // Apply the search
        this.api()
          .columns()
          .every(function() {
            var that = this;

            $('input', this.footer()).on('keyup change clear', function() {
              if (that.search() !== this.value) {
                that.search(this.value).draw();
              }
            });
          });
        $('#info-success').text(this.api().data().length);
      },
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

    var table = $('#orderList12').DataTable({
      "ajax": "<?php echo site_url('admin/orders/api/order_average_sales'); ?>",
      "columns": [{
          "data": "sales"
        },
        {
          "data": "pesanan"
        },
        {
          "data": "rating"
        },
        {
          "data": "average"
        },
      ],
      "dom": '<"float-left mb-2"B><"float-right search1"f>rt<"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
      buttons: [
        'print',
        'copyHtml5',
        'excelHtml5',
        'pdfHtml5'
      ],
      initComplete: function() {
        // Apply the search
        this.api()
          .columns()
          .every(function() {
            var that = this;

            $('input', this.footer()).on('keyup change clear', function() {
              if (that.search() !== this.value) {
                that.search(this.value).draw();
              }
            });
          });
        $('#info-average').text(this.api().data().length);
      },
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