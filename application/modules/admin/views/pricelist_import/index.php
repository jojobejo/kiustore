<?php
defined('BASEPATH') or exit('No direct script access allowed');

function pricelist_import_price($value)
{
  return $value === NULL ? '-' : 'Rp ' . format_rupiah($value);
}

function pricelist_import_status_badge($status)
{
  $class = $status === 'UPDATED' ? 'badge-success' : ($status === 'SKIPPED' ? 'badge-danger' : 'badge-secondary');
  return '<span class="badge ' . $class . '">' . html_escape($status) . '</span>';
}

function pricelist_import_render_changed_rows($rows)
{
  foreach ($rows as $row) : ?>
    <tr>
      <td class="text-center">
        <?php if ($row->update_status === 'PENDING') : ?>
          <input type="checkbox" class="changed-check" data-item-id="<?php echo (int) $row->id; ?>" value="<?php echo (int) $row->id; ?>">
        <?php else : ?>
          <?php echo pricelist_import_status_badge($row->update_status); ?>
        <?php endif; ?>
      </td>
      <td>
        <strong><?php echo html_escape($row->product_name); ?></strong><br>
        <small class="text-muted"><?php echo html_escape($row->deskripsi_bersih); ?></small>
      </td>
      <td><?php echo pricelist_import_price($row->current_price); ?></td>
      <td><?php echo pricelist_import_price($row->new_price); ?></td>
      <td><?php echo pricelist_import_price($row->current_price_2); ?></td>
      <td><?php echo pricelist_import_price($row->new_price_2); ?></td>
      <td><?php echo pricelist_import_price($row->current_price_3); ?></td>
      <td><?php echo pricelist_import_price($row->new_price_3); ?></td>
      <td><?php echo html_escape($row->tgl_info); ?></td>
      <td><?php echo html_escape($row->keterangan_asal_info); ?></td>
    </tr>
  <?php endforeach;
}

function pricelist_import_render_pricelist_only_rows($rows)
{
  foreach ($rows as $row) : ?>
    <tr>
      <td><?php echo html_escape($row->kode_barang); ?></td>
      <td><?php echo html_escape($row->deskripsi_bersih); ?></td>
      <td><?php echo pricelist_import_price($row->new_price); ?></td>
      <td><?php echo pricelist_import_price($row->new_price_2); ?></td>
      <td><?php echo pricelist_import_price($row->new_price_3); ?></td>
      <td><?php echo html_escape($row->supplier); ?></td>
      <td><?php echo html_escape($row->tgl_info); ?></td>
      <td><?php echo html_escape($row->keterangan_asal_info); ?></td>
    </tr>
  <?php endforeach;
}

function pricelist_import_render_product_only_rows($rows)
{
  foreach ($rows as $row) : ?>
    <tr>
      <td><?php echo (int) $row->product_id; ?></td>
      <td><?php echo html_escape($row->product_name); ?></td>
      <td><?php echo pricelist_import_price($row->current_price); ?></td>
      <td><?php echo pricelist_import_price($row->current_price_2); ?></td>
      <td><?php echo pricelist_import_price($row->current_price_3); ?></td>
    </tr>
  <?php endforeach;
}

function pricelist_import_render_invalid_rows($rows)
{
  foreach ($rows as $row) : ?>
    <tr>
      <td><?php echo $row->row_number ? (int) $row->row_number : '-'; ?></td>
      <td><?php echo html_escape($row->kode_barang); ?></td>
      <td><?php echo html_escape($row->deskripsi_raw ?: $row->deskripsi_bersih); ?></td>
      <td><span class="badge badge-danger"><?php echo html_escape($row->change_status); ?></span></td>
      <td><?php echo html_escape($row->validation_message); ?></td>
      <td><?php echo html_escape($row->tgl_info); ?></td>
      <td><?php echo html_escape($row->keterangan_asal_info); ?></td>
    </tr>
  <?php endforeach;
}
?>
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-8 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Import Pricelist Harga</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin'); ?>"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Pricelist Harga</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-4 col-5 text-right">
          <a href="<?php echo site_url('admin/pricelist-import'); ?>" class="btn btn-sm btn-neutral"><i class="fa fa-sync"></i> Refresh</a>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-2 col-md-4">
          <div class="card card-stats"><div class="card-body"><div class="row"><div class="col"><h5 class="card-title text-uppercase text-muted mb-0">Data Olahan</h5><span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['processed_rows']; ?></span></div><div class="col-auto"><div class="icon icon-shape bg-info text-white rounded-circle shadow"><i class="fa fa-table"></i></div></div></div></div></div>
        </div>
        <div class="col-xl-2 col-md-4">
          <div class="card card-stats"><div class="card-body"><div class="row"><div class="col"><h5 class="card-title text-uppercase text-muted mb-0">Harga Berubah</h5><span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['changed_rows']; ?></span></div><div class="col-auto"><div class="icon icon-shape bg-warning text-white rounded-circle shadow"><i class="fa fa-exchange-alt"></i></div></div></div></div></div>
        </div>
        <div class="col-xl-2 col-md-4">
          <div class="card card-stats"><div class="card-body"><div class="row"><div class="col"><h5 class="card-title text-uppercase text-muted mb-0">Pricelist Only</h5><span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['pricelist_only_rows']; ?></span></div><div class="col-auto"><div class="icon icon-shape bg-danger text-white rounded-circle shadow"><i class="fa fa-file-excel"></i></div></div></div></div></div>
        </div>
        <div class="col-xl-2 col-md-4">
          <div class="card card-stats"><div class="card-body"><div class="row"><div class="col"><h5 class="card-title text-uppercase text-muted mb-0">Karisma Only</h5><span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['product_only_rows']; ?></span></div><div class="col-auto"><div class="icon icon-shape bg-secondary text-white rounded-circle shadow"><i class="fa fa-cubes"></i></div></div></div></div></div>
        </div>
        <div class="col-xl-2 col-md-4">
          <div class="card card-stats"><div class="card-body"><div class="row"><div class="col"><h5 class="card-title text-uppercase text-muted mb-0">Invalid</h5><span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['invalid_rows']; ?></span></div><div class="col-auto"><div class="icon icon-shape bg-danger text-white rounded-circle shadow"><i class="fa fa-ban"></i></div></div></div></div></div>
        </div>
        <div class="col-xl-2 col-md-4">
          <div class="card card-stats"><div class="card-body"><div class="row"><div class="col"><h5 class="card-title text-uppercase text-muted mb-0">Duplikat</h5><span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['duplicate_rows']; ?></span></div><div class="col-auto"><div class="icon icon-shape bg-success text-white rounded-circle shadow"><i class="fa fa-layer-group"></i></div></div></div></div></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid mt--6">
  <style>
    .pricelist-table {
      width: 100% !important;
      table-layout: fixed;
    }
    .pricelist-table th,
    .pricelist-table td {
      vertical-align: middle !important;
      white-space: normal;
      overflow-wrap: anywhere;
      font-size: 12px;
    }
    .pricelist-table .text-center {
      text-align: center;
    }
    .pricelist-export-actions {
      display: flex;
      flex-wrap: wrap;
      justify-content: flex-end;
      gap: 6px;
    }
    .pricelist-export-actions .btn {
      color: #000 !important;
      white-space: normal;
      text-align: left;
      line-height: 1.25;
    }
  </style>

  <?php if (!empty($flash)) : ?>
    <div class="alert alert-<?php echo html_escape($flash['type']); ?> alert-dismissible fade show" role="alert">
      <?php echo html_escape($flash['message']); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header border-0 d-flex align-items-center justify-content-between">
      <div>
        <h3 class="mb-0">Upload Excel Pricelist</h3>
        <small class="text-muted">
          Sistem menyimpan file sebagai staging, membersihkan deskripsi, group by deskripsi bersih, validasi duplikat/konflik, lalu admin approve sebelum update harga.
        </small>
      </div>
      <?php if (!empty($latest_batch)) : ?>
        <a href="<?php echo site_url('admin/pricelist-import?batch_id=' . (int) $latest_batch->id); ?>" class="btn btn-sm btn-outline-primary">Import Terakhir</a>
      <?php endif; ?>
    </div>
    <div class="card-body">
      <form action="<?php echo site_url('admin/pricelist-import/import'); ?>" method="post" enctype="multipart/form-data" class="row align-items-end">
        <div class="col-md-8">
          <label class="form-control-label">File Excel Pricelist</label>
          <input type="file" name="pricelist_file" class="form-control" accept=".xlsx,.xls,.csv,.txt,.tsv" required>
          <small class="text-muted">Kolom audit seperti Tgl Info dan Keterangan Asal Info Perubahan Harga disimpan ke item import, bukan ke products.</small>
        </div>
        <div class="col-md-4 mt-3 mt-md-0">
          <button type="submit" class="btn btn-success btn-block">
            <i class="fa fa-upload"></i> Import dan Preview
          </button>
        </div>
      </form>

      <?php if (!empty($batch)) : ?>
        <div class="alert alert-info mt-3 mb-0">
          Batch aktif #<?php echo (int) $batch->id; ?>:
          <?php echo html_escape($batch->source_file_name); ?>,
          raw <?php echo (int) $batch->raw_rows; ?>,
          olahan <?php echo (int) $batch->processed_rows; ?>,
          match <?php echo (int) $batch->matched_rows; ?>,
          berubah <?php echo (int) $batch->changed_rows; ?>.
          <span class="badge badge-secondary ml-2">Pending <?php echo (int) $update_summary['PENDING']; ?></span>
          <span class="badge badge-success ml-1">Updated <?php echo (int) $update_summary['UPDATED']; ?></span>
          <span class="badge badge-danger ml-1">Skipped <?php echo (int) $update_summary['SKIPPED']; ?></span>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if (empty($batch)) : ?>
    <div class="alert alert-primary">
      Belum ada batch pricelist. Upload file terlebih dahulu untuk menampilkan preview dan tombol approve.
    </div>
  <?php else : ?>
    <form id="approveSelectedForm" action="<?php echo site_url('admin/pricelist-import/approve'); ?>" method="post" onsubmit="return prepareApproveSelected();">
      <input type="hidden" name="batch_id" value="<?php echo (int) $batch->id; ?>">
      <div id="selectedItemInputs"></div>
      <div class="card">
        <div class="card-header border-0 d-flex align-items-center justify-content-between">
          <h3 class="mb-0">Preview Perubahan Harga</h3>
          <div>
            <button type="submit" id="approveSelectedButton" class="btn btn-secondary btn-sm mr-2" disabled>
              <i class="fa fa-check"></i> Approve Terpilih <span class="badge badge-light ml-1" id="selectedBadge">0</span>
            </button>
            <button type="submit" form="approveAllForm" class="btn btn-danger btn-sm" <?php echo empty($changed) ? 'disabled' : ''; ?>>
              <i class="fa fa-bolt"></i> Approve Semua Berubah <span class="badge badge-light ml-1"><?php echo count($changed); ?></span>
            </button>
          </div>
        </div>
        <div class="table-responsive p-3">
          <table class="table align-items-center table-flush pricelist-table" id="tableChanged">
            <thead class="thead-light">
              <tr>
                <th>Pilih</th>
                <th>Produk / Deskripsi Bersih</th>
                <th>Harga Lama</th>
                <th>Harga Baru</th>
                <th>R1 Lama</th>
                <th>R1 Baru</th>
                <th>R2 Lama</th>
                <th>R2 Baru</th>
                <th>Tgl Info</th>
                <th>Asal Info</th>
              </tr>
            </thead>
            <tbody><?php pricelist_import_render_changed_rows($changed); ?></tbody>
          </table>
        </div>
      </div>
    </form>
    <form id="approveAllForm" action="<?php echo site_url('admin/pricelist-import/approve'); ?>" method="post" onsubmit="return confirm('Approve semua item harga berubah pada batch ini dan update products.price, price_2, price_3. Lanjutkan?');">
      <input type="hidden" name="approve_all" value="1">
      <input type="hidden" name="batch_id" value="<?php echo (int) $batch->id; ?>">
    </form>

    <div class="card">
      <div class="card-header border-0 d-flex align-items-center justify-content-between">
        <h3 class="mb-0">Sajian Data Hasil Olah Pricelist</h3>
        <div class="pricelist-export-actions">
          <a href="<?php echo site_url('admin/pricelist-import/export-pricelist-only-excel?batch_id=' . (int) $batch->id); ?>" class="btn btn-success btn-sm">
            <i class="fa fa-file-excel"></i> Export Data barang ada di PL tidak ada pada karisma online
          </a>
          <a href="<?php echo site_url('admin/pricelist-import/export-product-only-excel?batch_id=' . (int) $batch->id); ?>" class="btn btn-info btn-sm">
            <i class="fa fa-file-excel"></i> Export Data Barang ada di karisma online tidak ada pada PL
          </a>
        </div>
      </div>
      <div class="card-body pt-0">
        <ul class="nav nav-tabs" id="pricelistTabs" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-pricelist-only" role="tab">Ada di Pricelist <span class="badge badge-danger ml-1"><?php echo count($pricelist_only); ?></span></a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-product-only" role="tab">Ada di Karisma <span class="badge badge-secondary ml-1"><?php echo count($product_only); ?></span></a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-invalid" role="tab">Data Invalid <span class="badge badge-danger ml-1"><?php echo count($invalid); ?></span></a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-unchanged" role="tab">Harga Sama <span class="badge badge-success ml-1"><?php echo count($unchanged); ?></span></a></li>
        </ul>
        <div class="tab-content pt-3">
          <div class="tab-pane fade show active" id="tab-pricelist-only" role="tabpanel">
            <div class="table-responsive">
              <table class="table align-items-center table-flush pricelist-table" id="tablePricelistOnly">
                <thead class="thead-light"><tr><th>Kode</th><th>Deskripsi Bersih</th><th>Harga</th><th>R1</th><th>R2</th><th>Supplier</th><th>Tgl Info</th><th>Asal Info</th></tr></thead>
                <tbody><?php pricelist_import_render_pricelist_only_rows($pricelist_only); ?></tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="tab-product-only" role="tabpanel">
            <div class="table-responsive">
              <table class="table align-items-center table-flush pricelist-table" id="tableProductOnly">
                <thead class="thead-light"><tr><th>ID</th><th>Produk Karisma Online</th><th>Harga</th><th>R1</th><th>R2</th></tr></thead>
                <tbody><?php pricelist_import_render_product_only_rows($product_only); ?></tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="tab-invalid" role="tabpanel">
            <div class="table-responsive">
              <table class="table align-items-center table-flush pricelist-table" id="tableInvalid">
                <thead class="thead-light"><tr><th>Row</th><th>Kode</th><th>Deskripsi Raw</th><th>Status</th><th>Validasi</th><th>Tgl Info</th><th>Asal Info</th></tr></thead>
                <tbody><?php pricelist_import_render_invalid_rows($invalid); ?></tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="tab-unchanged" role="tabpanel">
            <div class="table-responsive">
              <table class="table align-items-center table-flush pricelist-table" id="tableUnchanged">
                <thead class="thead-light"><tr><th>Produk</th><th>Harga</th><th>R1</th><th>R2</th><th>Tgl Info</th><th>Asal Info</th></tr></thead>
                <tbody>
                  <?php foreach ($unchanged as $row) : ?>
                    <tr>
                      <td><?php echo html_escape($row->product_name); ?></td>
                      <td><?php echo pricelist_import_price($row->current_price); ?></td>
                      <td><?php echo pricelist_import_price($row->current_price_2); ?></td>
                      <td><?php echo pricelist_import_price($row->current_price_3); ?></td>
                      <td><?php echo html_escape($row->tgl_info); ?></td>
                      <td><?php echo html_escape($row->keterangan_asal_info); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if (!empty($recent_batches)) : ?>
    <div class="card">
      <div class="card-header border-0"><h3 class="mb-0">Riwayat Batch Import Pricelist</h3></div>
      <div class="table-responsive p-3">
        <table class="table align-items-center table-flush" id="tableBatches">
          <thead class="thead-light">
            <tr>
              <th>Batch</th>
              <th>File</th>
              <th>Status</th>
              <th>Imported At</th>
              <th>Approved At</th>
              <th>Berubah</th>
              <th>Invalid</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_batches as $history) : ?>
              <tr>
                <td>#<?php echo (int) $history->id; ?></td>
                <td><?php echo html_escape($history->source_file_name); ?></td>
                <td><span class="badge badge-<?php echo $history->status === 'APPROVED' ? 'success' : 'secondary'; ?>"><?php echo html_escape($history->status); ?></span></td>
                <td><?php echo html_escape($history->imported_at); ?></td>
                <td><?php echo html_escape($history->approved_at); ?></td>
                <td><?php echo (int) $history->changed_rows; ?></td>
                <td><?php echo (int) $history->invalid_rows; ?></td>
                <td><a href="<?php echo site_url('admin/pricelist-import?batch_id=' . (int) $history->id); ?>" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
  $(function() {
    $('#tableChanged, #tablePricelistOnly, #tableProductOnly, #tableInvalid, #tableUnchanged, #tableBatches').DataTable({
      pageLength: 10,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      autoWidth: false
    });

    var selectedItems = {};

    function syncVisibleChecks() {
      $('.changed-check').each(function() {
        var id = $(this).data('item-id').toString();
        $(this).prop('checked', selectedItems[id] === true);
      });
    }

    function renderSelectedInputs() {
      var container = $('#selectedItemInputs');
      container.empty();
      $.each(selectedItems, function(id, selected) {
        if (selected) {
          $('<input>', { type: 'hidden', name: 'item_ids[]', value: id }).appendTo(container);
        }
      });
    }

    function updateApproveButton() {
      var selectedCount = Object.keys(selectedItems).filter(function(id) { return selectedItems[id]; }).length;
      var button = $('#approveSelectedButton');
      $('#selectedBadge').text(selectedCount);
      button.prop('disabled', selectedCount === 0);
      button.toggleClass('btn-success', selectedCount > 0);
      button.toggleClass('btn-secondary', selectedCount === 0);
      button.toggleClass('shadow', selectedCount > 0);
    }

    $('#tableChanged').on('change', '.changed-check', function() {
      var id = $(this).data('item-id').toString();
      selectedItems[id] = $(this).is(':checked');
      syncVisibleChecks();
      updateApproveButton();
    });

    $('#tableChanged').on('draw.dt', function() {
      syncVisibleChecks();
    });

    window.prepareApproveSelected = function() {
      renderSelectedInputs();
      var selectedCount = $('#selectedItemInputs input[name="item_ids[]"]').length;
      if (selectedCount === 0) {
        return false;
      }
      return confirm('Approve akan update ' + selectedCount + ' item harga terpilih ke products.price, price_2, price_3. Lanjutkan?');
    };
  });
</script>
