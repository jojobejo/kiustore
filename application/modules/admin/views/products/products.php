<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-3 col-33">
          <h6 class="h2 text-white d-inline-block mb-0">Kelola Produk</h6>
        </div>

        <div class="col-lg-3 col-3">
          <!-- Search form -->
          <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main" action="<?php echo site_url('admin/products/search'); ?>" required>
            <div class="form-group mb-0">
              <div class="input-group input-group-alternative input-group-merge">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input class="form-control" value="<?php echo (isset($query) ? $query : ''); ?>" name="search_query" placeholder="Cari..." type="text" required>
              </div>
            </div>
            <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </form>
        </div>

        <?php if (admin_role() == 'admin' || admin_role() == 'adminonline') : ?>
          <div class="col-lg-6 col-5 text-right">
            <a href="<?php echo site_url('admin/products/add_new_product'); ?>" class="btn btn-neutral">Tambah</a>
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
              <a class="nav-link " id="all-tab" data-toggle="pill" href="#all" role="tab" aria-controls="all" aria-selected="true">Semua
                <span class="badge badge-warning" id="info-all"> </span>
              </a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link active" id="price-tab" data-toggle="pill" href="#price" role="tab" aria-controls="price" aria-selected="false">Tercantum Harga
                <span class="badge badge-warning" id="info-process"> </span>
              </a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="noprice-tab" data-toggle="pill" href="#noprice" role="tab" aria-controls="noprice" aria-selected="false">Belum Tercantum Harga
                <span class="badge badge-warning" id="info-noprice"> </span>
              </a>
            </li>
          </ul>
        </div>
        <div class="card-body p-0">
          <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade " id="all" role="tabpanel" aria-labelledby="all-tab">
              <div class="table-responsive">
                <table class="table align-items-center table-flush" id="semua" style="width: 100%">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">No</th>
                      <th scope="col">Nama Barang</th>
                      <th scope="col">SKU</th>
                      <th scope="col">Stok</th>
                      <th scope="col">Satuan</th>
                      <th scope="col">Harga Umum/Eceran</th>
                      <th scope="col">Harga R1</th>
                      <th scope="col">Harga R2</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

            <div class="tab-pane fade show active" id="price" role="tabpanel" aria-labelledby="price-tab">
              <div class="table-responsive">
                <table class="table align-items-center table-flush" id="ada_harga" style="width: 100%">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">No</th>
                      <th scope="col">Nama Barang</th>
                      <th scope="col">Kategori</th>
                      <th scope="col">Stok</th>
                      <th scope="col">Satuan</th>
                      <th scope="col">Harga Umum/Eceran</th>
                      <th scope="col">Harga R1</th>
                      <th scope="col">Harga R2</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

            <div class="tab-pane fade show" id="noprice" role="tabpanel" aria-labelledby="noprice-tab">
              <div class="table-responsive">
                <table class="table align-items-center table-flush" id="belumada_harga" style="width: 100%">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">No</th>
                      <th scope="col">Nama Barang</th>
                      <th scope="col">Kategori</th>
                      <th scope="col">Stok</th>
                      <th scope="col">Satuan</th>
                      <th scope="col">Harga Umum/Eceran</th>
                      <th scope="col">Harga R1</th>
                      <th scope="col">Harga R2</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

          </div>

        </div>

        <div class="card-footer">
          <?php echo $pagination; ?>
        </div>

      </div>
    </div>
  </div>

  <script>

  var table = $('#semua').DataTable({
    "ajax": "<?php echo site_url('admin/products/api/semua'); ?>",
    "columns": [

      {
        "data": "id",
        render: function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {
        "data": function(data, type, row) {
          var url = window.location.href.split('?')[0].replace('#', '');
          url = url + '/view/' + data.id;

          return '<a href="' + url + '"> ' + data.name + '</a>';
        }
      },
      {
        "data": "sku"
      },
      {
        "data": "stock"
      },
      {
        "data": "product_unit_1"
      },
      {
        "data": "price"
      },
      {
        "data": "price_2"
      },
      {
        "data": "price_3"
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

          $('input.search', this.footer()).on('keyup change clear', function() {
            if (that.search() !== this.value) {
              that.search(this.value).draw();
            }
          });
        });
      $('#info-payment').text(this.api().data().length);
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

  var table = $('#ada_harga').DataTable({
    "ajax": "<?php echo site_url('admin/products/api/ada_harga'); ?>",
    "columns": [

      {
        "data": "id",
        render: function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {
        "data": function(data, type, row) {
          var url = window.location.href.split('?')[0].replace('#', '');
          url = url + '/view/' + data.id;

          return '<a href="' + url + '"> ' + data.name + '</a>';
        }
      },
      {
        "data": "sku"
      },
      {
        "data": "stock"
      },
      {
        "data": "product_unit_1"
      },
      {
        "data": "price"
      },
      {
        "data": "price_2"
      },
      {
        "data": "price_3"
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

          $('input.search1', this.footer()).on('keyup change clear', function() {
            if (that.search() !== this.value) {
              that.search(this.value).draw();
            }
          });
        });
      $('#info-payment').text(this.api().data().length);
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

  var table = $('#tidakada_harga').DataTable({
    "ajax": "<?php echo site_url('admin/products/api/belumada_harga'); ?>",
    "columns": [

      {
        "data": "id",
        render: function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {
        "data": function(data, type, row) {
          var url = window.location.href.split('?')[0].replace('#', '');
          url = url + '/view/' + data.id;

          return '<a href="' + url + '"> ' + data.name + '</a>';
        }
      },
      {
        "data": "sku"
      },
      {
        "data": "stock"
      },
      {
        "data": "product_unit_1"
      },
      {
        "data": "price"
      },
      {
        "data": "price_2"
      },
      {
        "data": "price_3"
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

          $('input.search2', this.footer()).on('keyup change clear', function() {
            if (that.search() !== this.value) {
              that.search(this.value).draw();
            }
          });
        });
      $('#info-payment').text(this.api().data().length);
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

  </script>
