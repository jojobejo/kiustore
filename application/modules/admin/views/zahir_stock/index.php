<?php
defined('BASEPATH') or exit('No direct script access allowed');

$matched_minus = array();
$matched_plus = array();
foreach ($matched as $row) {
  if ((int) $row['selisih'] < 0) {
    $matched_minus[] = $row;
  } elseif ((int) $row['selisih'] > 0) {
    $matched_plus[] = $row;
  }
}

function zahir_stock_render_match_rows($rows)
{
  foreach ($rows as $row) : ?>
    <tr>
      <td>
        <input type="checkbox" class="matched-check" data-product-id="<?php echo (int) $row['product_id']; ?>" value="<?php echo (int) $row['product_id']; ?>">
      </td>
      <td><?php echo html_escape($row['product_name']); ?></td>
      <td><?php echo html_escape($row['nama_barang']); ?></td>
      <td><?php echo (int) $row['product_stock']; ?></td>
      <td><?php echo (int) $row['zahir_qty']; ?></td>
      <td>
        <?php
          $selisih = (int) $row['selisih'];
          $badge = $selisih < 0 ? 'badge-danger' : ($selisih > 0 ? 'badge-success' : 'badge-secondary');
        ?>
        <span class="badge <?php echo $badge; ?>"><?php echo $selisih; ?></span>
      </td>
    </tr>
  <?php endforeach;
}

function zahir_stock_render_zahir_only_rows($rows)
{
  foreach ($rows as $row) : ?>
    <tr>
      <td><?php echo html_escape($row['nama_barang']); ?></td>
      <td><?php echo (int) $row['qty']; ?></td>
      <td class="text-center">
        <button type="button" class="btn btn-success btn-sm btn-insert-zahir-product" data-name="<?php echo html_escape($row['nama_barang']); ?>" title="Insert ke products">
          <i class="fa fa-check"></i>
        </button>
      </td>
    </tr>
  <?php endforeach;
}
?>
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-8 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Integrasi Stock Zahir Digital</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin'); ?>"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Stock Zahir Digital</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-4 col-5 text-right">
          <a href="<?php echo site_url('admin/zahir-stock'); ?>" class="btn btn-sm btn-neutral"><i class="fa fa-sync"></i> Refresh</a>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Data Diolah</h5>
                  <span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['processed_rows']; ?></span>
                </div>
                <div class="col-auto"><div class="icon icon-shape bg-info text-white rounded-circle shadow"><i class="fa fa-table"></i></div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Match Produk</h5>
                  <span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['matched_rows']; ?></span>
                </div>
                <div class="col-auto"><div class="icon icon-shape bg-success text-white rounded-circle shadow"><i class="fa fa-check"></i></div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Zahir Tidak Ada</h5>
                  <span class="h2 font-weight-bold mb-0" id="zahirOnlyTotal"><?php echo (int) $summary['zahir_only_rows']; ?></span>
                </div>
                <div class="col-auto"><div class="icon icon-shape bg-warning text-white rounded-circle shadow"><i class="fa fa-exclamation"></i></div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Produk Tidak Ada</h5>
                  <span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['product_only_rows']; ?></span>
                </div>
                <div class="col-auto"><div class="icon icon-shape bg-danger text-white rounded-circle shadow"><i class="fa fa-times"></i></div></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid mt--6">
  <style>
    .zahir-match-table {
      width: 100% !important;
      table-layout: fixed;
    }

    .zahir-match-table th,
    .zahir-match-table td {
      vertical-align: middle !important;
      white-space: normal;
      overflow-wrap: anywhere;
    }

    .zahir-match-table th:nth-child(1),
    .zahir-match-table td:nth-child(1) {
      width: 7% !important;
      text-align: center;
    }

    .zahir-match-table th:nth-child(2),
    .zahir-match-table td:nth-child(2) {
      width: 28% !important;
    }

    .zahir-match-table th:nth-child(3),
    .zahir-match-table td:nth-child(3) {
      width: 28% !important;
    }

    .zahir-match-table th:nth-child(4),
    .zahir-match-table td:nth-child(4),
    .zahir-match-table th:nth-child(5),
    .zahir-match-table td:nth-child(5),
    .zahir-match-table th:nth-child(6),
    .zahir-match-table td:nth-child(6) {
      width: 12.33% !important;
      text-align: right;
    }

    .zahir-match-panel .dataTables_wrapper {
      width: 100%;
    }
  </style>

  <?php if (!empty($flash)) : ?>
    <div class="alert alert-<?php echo html_escape($flash['type']); ?> alert-dismissible fade show" role="alert">
      <?php echo html_escape($flash['message']); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
  <?php endif; ?>

  <?php if (!$success) : ?>
    <div class="alert alert-danger" role="alert">
      <strong>Sumber data belum siap.</strong>
      <?php echo html_escape($error_message); ?>
      URL: <?php echo html_escape($source_url); ?>
    </div>
  <?php endif; ?>

  <div class="card d-none">
    <div class="card-header border-0">
      <h3 class="mb-0">Rules Olah Data Zahir Digital</h3>
    </div>
    <div class="card-body py-3">
      <ol class="mb-0">
        <li>Karakter <strong>*</strong> dihilangkan dari kolom Nama Barang.</li>
        <li>Nama Barang dinormalisasi dengan trim dan penghapusan spasi berlebih.</li>
        <li>Data digroup ulang menjadi list Nama Barang bersih yang unik, kemudian Qty dijumlahkan berdasarkan Nama Barang tersebut.</li>
        <li>Data olahan disajikan dalam format Excel dengan header <strong>Nama Barang</strong> dan <strong>Qty</strong>.</li>
      </ol>
    </div>
  </div>

  <div class="card d-none">
    <div class="card-header border-0">
      <h3 class="mb-0">Data Zahir Digital Yang Telah Diolah</h3>
    </div>
    <div class="table-responsive p-3">
      <table class="table align-items-center table-flush" id="tableProcessed">
        <thead class="thead-light">
          <tr>
            <th>Nama Barang</th>
            <th>Qty</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($processed as $row) : ?>
            <tr>
              <td><?php echo html_escape($row['nama_barang']); ?></td>
              <td><?php echo (int) $row['qty']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <form id="approveSelectedForm" action="<?php echo site_url('admin/zahir-stock/approve'); ?>" method="post" onsubmit="return prepareApproveSelected();">
    <div id="selectedProductInputs"></div>
    <div class="card">
      <div class="card-header border-0 d-flex align-items-center justify-content-between">
        <h3 class="mb-0">Compare Match dan Approve Update Stock</h3>
        <div class="d-flex align-items-center">
          <button type="submit" id="approveSelectedButton" class="btn btn-secondary btn-sm mr-2" disabled>
            <i class="fa fa-check"></i> Approve Terpilih <span class="badge badge-light ml-1" id="selectedBadge">0</span>
          </button>
          <button type="submit" form="approveAllForm" class="btn btn-danger btn-sm" <?php echo empty($matched) ? 'disabled' : ''; ?>>
            <i class="fa fa-bolt"></i> Bulk All Update Data Semua <span class="badge badge-light ml-1"><?php echo count($matched); ?></span>
          </button>
        </div>
      </div>
      <div class="card-body pt-0">
        <ul class="nav nav-tabs" id="matchedStockTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="tab-minus-link" data-toggle="tab" href="#tab-minus" role="tab" aria-controls="tab-minus" aria-selected="true">
              Selisih <span class="badge badge-danger ml-1"><?php echo count($matched_minus); ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tab-plus-link" data-toggle="tab" href="#tab-plus" role="tab" aria-controls="tab-plus" aria-selected="false">
              Plus <span class="badge badge-success ml-1"><?php echo count($matched_plus); ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tab-all-link" data-toggle="tab" href="#tab-all" role="tab" aria-controls="tab-all" aria-selected="false">
              Semua <span class="badge badge-info ml-1"><?php echo count($matched); ?></span>
            </a>
          </li>
        </ul>
        <div class="tab-content pt-3" id="matchedStockTabContent">
          <div class="tab-pane fade show active" id="tab-minus" role="tabpanel" aria-labelledby="tab-minus-link">
            <div class="table-responsive">
              <table class="table align-items-center table-flush table-matched zahir-match-table" id="tableMatchedMinus">
                <thead class="thead-light">
                  <tr>
                    <th>Pilih</th>
                    <th>Produk Karisma Online</th>
                    <th>Nama Barang Zahir</th>
                    <th>Stock Karisma</th>
                    <th>Qty Zahir</th>
                    <th>Selisih</th>
                  </tr>
                </thead>
                <tbody><?php zahir_stock_render_match_rows($matched_minus); ?></tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="tab-plus" role="tabpanel" aria-labelledby="tab-plus-link">
            <div class="table-responsive">
              <table class="table align-items-center table-flush table-matched zahir-match-table" id="tableMatchedPlus">
                <thead class="thead-light">
                  <tr>
                    <th>Pilih</th>
                    <th>Produk Karisma Online</th>
                    <th>Nama Barang Zahir</th>
                    <th>Stock Karisma</th>
                    <th>Qty Zahir</th>
                    <th>Selisih</th>
                  </tr>
                </thead>
                <tbody><?php zahir_stock_render_match_rows($matched_plus); ?></tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="tab-all" role="tabpanel" aria-labelledby="tab-all-link">
            <div class="table-responsive">
              <table class="table align-items-center table-flush table-matched zahir-match-table" id="tableMatchedAll">
                <thead class="thead-light">
                  <tr>
                    <th>Pilih</th>
                    <th>Produk Karisma Online</th>
                    <th>Nama Barang Zahir</th>
                    <th>Stock Karisma</th>
                    <th>Qty Zahir</th>
                    <th>Selisih</th>
                  </tr>
                </thead>
                <tbody><?php zahir_stock_render_match_rows($matched); ?></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <form id="approveAllForm" action="<?php echo site_url('admin/zahir-stock/approve'); ?>" method="post" onsubmit="return confirm('Bulk all akan update seluruh produk match sesuai Qty Zahir Digital terbaru. Lanjutkan?');">
    <input type="hidden" name="approve_all" value="1">
  </form>

  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header border-0 d-flex align-items-center justify-content-between">
          <h3 class="mb-0">Barang Zahir Tidak Ada di Produk Karisma Online</h3>
          <a href="<?php echo site_url('admin/zahir-stock/export-stock-excel'); ?>" class="btn btn-success btn-sm">
            <i class="fa fa-file-excel"></i> Export Data Stock
          </a>
        </div>
        <div class="table-responsive p-3">
          <table class="table align-items-center table-flush" id="tableZahirOnly">
            <thead class="thead-light">
              <tr>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody><?php zahir_stock_render_zahir_only_rows($zahir_only); ?></tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header border-0">
          <h3 class="mb-0">Produk Karisma Online Tidak Ada di Zahir Digital</h3>
        </div>
        <div class="table-responsive p-3">
          <table class="table align-items-center table-flush" id="tableProductOnly">
            <thead class="thead-light">
              <tr>
                <th>Nama Barang</th>
                <th>Stock</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($product_only as $row) : ?>
                <tr>
                  <td><?php echo html_escape($row['nama_barang']); ?></td>
                  <td><?php echo (int) $row['stock']; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    $('#tableProcessed').DataTable();
    var matchedTables = $('.table-matched').DataTable({
      pageLength: 10,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      autoWidth: false,
      scrollX: false,
      columnDefs: [
        { orderable: false, targets: 0 },
        { width: '7%', targets: 0 },
        { width: '28%', targets: 1 },
        { width: '28%', targets: 2 },
        { width: '12.33%', targets: 3 },
        { width: '12.33%', targets: 4 },
        { width: '12.33%', targets: 5 }
      ]
    });
    var zahirOnlyTable = $('#tableZahirOnly').DataTable({
      pageLength: 10,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      columnDefs: [{ orderable: false, targets: 2 }]
    });
    $('#tableProductOnly').DataTable();

    $('#tableZahirOnly').on('click', '.btn-insert-zahir-product', function() {
      var button = $(this);
      var row = button.closest('tr');
      var namaBarang = button.data('name');

      if (!confirm('Insert "' + namaBarang + '" ke tabel products Karisma Online?')) {
        return;
      }

      button.prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
      button.html('<i class="fa fa-spinner fa-spin"></i>');

      $.ajax({
        url: '<?php echo site_url('admin/zahir-stock/insert-product'); ?>',
        type: 'POST',
        dataType: 'json',
        data: {
          nama_barang: namaBarang
        },
        success: function(response) {
          if (!response || !response.success) {
            alert(response && response.message ? response.message : 'Insert produk gagal.');
            button.prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
            button.html('<i class="fa fa-check"></i>');
            return;
          }

          zahirOnlyTable.row(row).remove().draw(false);
          var currentTotal = parseInt($('#zahirOnlyTotal').text(), 10);
          if (!isNaN(currentTotal) && currentTotal > 0) {
            $('#zahirOnlyTotal').text(currentTotal - 1);
          }
        },
        error: function() {
          alert('Insert produk gagal karena request AJAX bermasalah.');
          button.prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
          button.html('<i class="fa fa-check"></i>');
        }
      });
    });

    var selectedProducts = {};

    function syncVisibleChecks() {
      $('.matched-check').each(function() {
        var id = $(this).data('product-id').toString();
        $(this).prop('checked', selectedProducts[id] === true);
      });
    }

    function renderSelectedInputs() {
      var container = $('#selectedProductInputs');
      container.empty();
      $.each(selectedProducts, function(id, selected) {
        if (selected) {
          $('<input>', {
            type: 'hidden',
            name: 'product_ids[]',
            value: id
          }).appendTo(container);
        }
      });
    }

    function updateApproveButton() {
      var selectedCount = Object.keys(selectedProducts).filter(function(id) {
        return selectedProducts[id];
      }).length;
      var button = $('#approveSelectedButton');

      $('#selectedBadge').text(selectedCount);
      button.prop('disabled', selectedCount === 0);
      button.toggleClass('btn-success', selectedCount > 0);
      button.toggleClass('btn-secondary', selectedCount === 0);
      button.toggleClass('shadow', selectedCount > 0);
    }

    $('.table-matched').on('change', '.matched-check', function() {
      var id = $(this).data('product-id').toString();
      selectedProducts[id] = $(this).is(':checked');
      syncVisibleChecks();
      updateApproveButton();
    });

    $('.table-matched').on('draw.dt', function() {
      syncVisibleChecks();
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function() {
      matchedTables.columns.adjust();
      $('.table-matched').DataTable().columns.adjust().draw(false);
      syncVisibleChecks();
    });

    window.setTimeout(function() {
      matchedTables.columns.adjust().draw(false);
    }, 250);

    window.prepareApproveSelected = function() {
      renderSelectedInputs();
      var selectedCount = $('#selectedProductInputs input[name="product_ids[]"]').length;
      if (selectedCount === 0) {
        return false;
      }

      return confirm('Approve akan update ' + selectedCount + ' produk terpilih sesuai Qty Zahir Digital terbaru. Lanjutkan?');
    };
  });
</script>
