<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_edit = !empty($edit_alias);
$form_action = $is_edit
  ? site_url('admin/zahir-stock-alias/update/' . (int) $edit_alias->id)
  : site_url('admin/zahir-stock-alias/store');
$selected_product_id = $is_edit ? (int) $edit_alias->product_id : 0;
?>
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-8 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Setting Alias Nama Barang Zahir</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin'); ?>"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin/zahir-stock'); ?>">Stock Zahir Digital</a></li>
              <li class="breadcrumb-item active" aria-current="page">Alias Nama Barang</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-4 col-5 text-right">
          <a href="<?php echo site_url('admin/zahir-stock'); ?>" class="btn btn-sm btn-neutral"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Total Alias</h5>
                  <span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['total']; ?></span>
                </div>
                <div class="col-auto"><div class="icon icon-shape bg-info text-white rounded-circle shadow"><i class="fa fa-tags"></i></div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Alias Aktif</h5>
                  <span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['active']; ?></span>
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
                  <h5 class="card-title text-uppercase text-muted mb-0">Nonaktif</h5>
                  <span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['inactive']; ?></span>
                </div>
                <div class="col-auto"><div class="icon icon-shape bg-warning text-white rounded-circle shadow"><i class="fa fa-pause"></i></div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Belum Resolve</h5>
                  <span class="h2 font-weight-bold mb-0"><?php echo (int) $summary['unresolved']; ?></span>
                </div>
                <div class="col-auto"><div class="icon icon-shape bg-danger text-white rounded-circle shadow"><i class="fa fa-exclamation"></i></div></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid mt--6">
  <?php if (!empty($flash)) : ?>
    <div class="alert alert-<?php echo html_escape($flash['type']); ?> alert-dismissible fade show" role="alert">
      <?php echo html_escape($flash['message']); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
  <?php endif; ?>

  <?php if (!$table_ready) : ?>
    <div class="alert alert-danger" role="alert">
      <strong>Tabel alias belum tersedia.</strong> Jalankan migration `20260831_zahir_stock_product_aliases.sql` terlebih dahulu.
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header border-0 d-flex align-items-center justify-content-between">
          <h3 class="mb-0"><?php echo $is_edit ? 'Edit Alias' : 'Tambah Alias'; ?></h3>
          <?php if ($is_edit) : ?>
            <a href="<?php echo site_url('admin/zahir-stock-alias'); ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> Baru</a>
          <?php endif; ?>
        </div>
        <div class="card-body">
          <form action="<?php echo $form_action; ?>" method="post">
            <div class="form-group">
              <label class="form-control-label">Nama Barang Zahir</label>
              <textarea name="zahir_name" class="form-control" rows="4" required><?php echo $is_edit ? html_escape($edit_alias->zahir_name) : ''; ?></textarea>
            </div>
            <div class="form-group">
              <label class="form-control-label">Produk Karisma Online</label>
              <select name="product_id" class="form-control alias-product-select" required>
                <option value="">Pilih Produk</option>
                <?php foreach ($products as $product) : ?>
                  <option value="<?php echo (int) $product->id; ?>" <?php echo (int) $product->id === $selected_product_id ? 'selected' : ''; ?>>
                    <?php echo html_escape($product->name); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-control-label">Catatan</label>
              <textarea name="notes" class="form-control" rows="3"><?php echo $is_edit ? html_escape((string) $edit_alias->notes) : ''; ?></textarea>
            </div>
            <div class="custom-control custom-checkbox mb-4">
              <input type="checkbox" class="custom-control-input" id="aliasActive" name="active" value="1" <?php echo (!$is_edit || (int) $edit_alias->active === 1) ? 'checked' : ''; ?>>
              <label class="custom-control-label" for="aliasActive">Alias aktif untuk proses integrasi</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block" <?php echo !$table_ready ? 'disabled' : ''; ?>>
              <i class="fa fa-save"></i> <?php echo $is_edit ? 'Simpan Perubahan' : 'Tambah Alias'; ?>
            </button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header border-0">
          <h3 class="mb-0">Daftar Alias Nama Barang Zahir</h3>
        </div>
        <div class="table-responsive p-3">
          <table class="table align-items-center table-flush" id="aliasTable" style="width:100%">
            <thead class="thead-light">
              <tr>
                <th>Nama Barang Zahir</th>
                <th>Produk Karisma Online</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($aliases as $alias) : ?>
                <tr>
                  <td><?php echo html_escape($alias->zahir_name); ?></td>
                  <td>
                    <?php echo html_escape($alias->resolved_product_name ? $alias->resolved_product_name : $alias->product_name); ?>
                    <?php if (empty($alias->resolved_product_name)) : ?>
                      <div><span class="badge badge-danger">Belum resolve</span></div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ((int) $alias->active === 1) : ?>
                      <span class="badge badge-success">Aktif</span>
                    <?php else : ?>
                      <span class="badge badge-secondary">Nonaktif</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo html_escape((string) $alias->notes); ?></td>
                  <td class="text-right">
                    <a href="<?php echo site_url('admin/zahir-stock-alias?edit_id=' . (int) $alias->id); ?>" class="btn btn-warning btn-sm" title="Edit alias">
                      <i class="fa fa-edit"></i>
                    </a>
                    <?php if ((int) $alias->active === 1) : ?>
                      <form action="<?php echo site_url('admin/zahir-stock-alias/delete/' . (int) $alias->id); ?>" method="post" class="d-inline" onsubmit="return confirm('Nonaktifkan alias ini dari proses integrasi?');">
                        <button type="submit" class="btn btn-danger btn-sm" title="Nonaktifkan alias">
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
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
  $(function() {
    $('.alias-product-select').select2({
      placeholder: 'Cari produk Karisma Online',
      allowClear: true,
      width: '100%'
    });

    $('#aliasTable').DataTable({
      pageLength: 10,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      columnDefs: [{ orderable: false, targets: 4 }],
      language: {
        search: 'Cari:',
        lengthMenu: 'Menampilkan _MENU_ data',
        info: 'Menampilkan _START_ sampai _END_ data dari _TOTAL_ data',
        infoEmpty: 'Tidak ada data yang ditampilkan',
        infoFiltered: '(dari total _MAX_ data)',
        zeroRecords: 'Tidak ada hasil pencarian ditemukan',
        paginate: {
          first: '&laquo;',
          last: '&raquo;',
          next: '&rsaquo;',
          previous: '&lsaquo;'
        }
      }
    });
  });
</script>
