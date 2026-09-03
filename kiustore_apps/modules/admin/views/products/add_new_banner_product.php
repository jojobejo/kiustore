<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$is_edit = isset($is_edit) && $is_edit;
$banner = isset($banner) ? $banner : NULL;
$form_action = $is_edit ? 'admin/banner_product/update_banner_product' : 'admin/banner_product/add_banner_product';
$page_title = $is_edit ? 'Ubah Banner Produk' : 'Tambah Banner Produk';
$submit_text = $is_edit ? 'Simpan Perubahan' : 'Tambah Banner Produk';
$current_type = set_value('redirect_type', $is_edit && ! empty($banner->redirect_type) ? $banner->redirect_type : 'product');
$current_title = set_value('banner_title', $is_edit && ! empty($banner->banner_title) ? $banner->banner_title : '');
$current_product = $is_edit ? (! empty($banner->redirect_product_id) ? $banner->redirect_product_id : $banner->product_id) : '';
$current_product = set_value('product_id', $current_product);
$current_category = set_value('redirect_category_id', $is_edit && ! empty($banner->redirect_category_id) ? $banner->redirect_category_id : '');
$current_url = set_value('redirect_url', $is_edit && ! empty($banner->redirect_url) ? $banner->redirect_url : '');
$current_order = set_value('display_order', $is_edit && ! empty($banner->display_order) ? $banner->display_order : '');
$active_banner_count = isset($active_banner_count) ? (int) $active_banner_count : 0;
$current_active = set_value('is_active', $is_edit ? (int) $banner->is_active : ($active_banner_count < 3 ? 1 : 0));
?>
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0"><?php echo $page_title; ?></h6>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="<?php echo site_url('admin'); ?>"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo site_url('admin/banner_product'); ?>">Banner Produk</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo $is_edit ? 'Ubah' : 'Tambah'; ?></li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--6">
      <?php echo form_open_multipart($form_action); ?>
      <?php if ($is_edit) : ?>
        <input type="hidden" name="id" value="<?php echo (int) $banner->banner_id; ?>">
      <?php endif; ?>

      <div class="row">
        <div class="col-md-12">
          <div class="card-wrapper">
            <div class="card">
              <div class="card-header">
                <h3 class="mb-0">Data Banner</h3>
                <?php if ($flash) : ?>
                <span class="float-right text-success font-weight-bold" style="margin-top: -30px">
                  <?php echo $flash; ?>
                </span>
                <?php endif; ?>
                <?php if ($error) : ?>
                <div class="alert alert-danger mt-3 mb-0">
                  <?php echo $error; ?>
                </div>
                <?php endif; ?>
              </div>

              <div class="card-body">
                <div class="form-group">
                  <label class="form-control-label" for="banner-title">Title Banner:</label>
                  <input type="text" name="banner_title" class="form-control" id="banner-title" value="<?php echo html_escape($current_title); ?>" maxlength="150" required>
                  <small class="text-muted">Title ditulis manual oleh admin dan dipakai sebagai label banner.</small>
                </div>

                <div class="form-group">
                  <label class="form-control-label" for="redirect-type">Redirect Banner:</label>
                  <select name="redirect_type" class="form-control" id="redirect-type" required>
                    <option value="product"<?php echo $current_type === 'product' ? ' selected' : ''; ?>>Produk</option>
                    <option value="category"<?php echo $current_type === 'category' ? ' selected' : ''; ?>>Kategori</option>
                    <option value="custom"<?php echo $current_type === 'custom' ? ' selected' : ''; ?>>URL Manual</option>
                  </select>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label" for="display-order">Urutan Tampil:</label>
                      <input type="number" name="display_order" class="form-control" id="display-order" value="<?php echo html_escape($current_order); ?>" min="1" step="1" placeholder="Otomatis jika kosong">
                      <small class="text-muted">Angka paling kecil tampil lebih dulu. Homepage mengambil maksimal 3 banner aktif.</small>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label d-block">Status Tampilan:</label>
                      <label class="custom-toggle">
                        <input type="checkbox" name="is_active" value="1"<?php echo (int) $current_active === 1 ? ' checked' : ''; ?>>
                        <span class="custom-toggle-slider rounded-circle"></span>
                      </label>
                      <small class="text-muted d-block mt-2">Aktifkan hanya banner yang ingin ditampilkan. Maksimal 3 banner aktif.</small>
                    </div>
                  </div>
                </div>

                <div class="form-group banner-target banner-target-product">
                  <label class="form-control-label" for="package">Produk Tujuan:</label>
                  <select name="product_id" class="form-control" id="package">
                    <option value="">Pilih Produk</option>
                    <?php if (count($products) > 0) : ?>
                      <?php foreach ($products as $product) : ?>
                        <option value="<?php echo $product->id; ?>"<?php echo (string) $current_product === (string) $product->id ? ' selected' : ''; ?>><?php echo $product->name; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                  <?php echo form_error('product_id'); ?>
                </div>

                <div class="form-group banner-target banner-target-category">
                  <label class="form-control-label" for="redirect-category">Kategori Tujuan:</label>
                  <select name="redirect_category_id" class="form-control" id="redirect-category">
                    <option value="">Pilih Kategori</option>
                    <?php if (count($categories) > 0) : ?>
                      <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category->id; ?>"<?php echo (string) $current_category === (string) $category->id ? ' selected' : ''; ?>><?php echo $category->name; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>

                <div class="form-group banner-target banner-target-custom">
                  <label class="form-control-label" for="redirect-url">URL Manual:</label>
                  <input type="text" name="redirect_url" class="form-control" id="redirect-url" value="<?php echo html_escape($current_url); ?>" maxlength="255" placeholder="promo atau https://domain.com/halaman">
                  <small class="text-muted">Isi route internal seperti promo, category, all_products, atau URL eksternal http/https.</small>
                </div>

                <div class="form-group">
                  <label class="form-control-label" for="pic">Foto:</label>
                  <?php if ($is_edit && ! empty($banner->banner_image)) : ?>
                    <div class="mb-3">
                      <img alt="<?php echo html_escape($current_title); ?>" class="img img-fluid rounded" src="<?php echo base_url('assets/uploads/banner_product/'. $banner->banner_image); ?>" style="max-width: 360px; max-height: 180px;">
                    </div>
                  <?php endif; ?>
                  <input type="file" name="picture" class="form-control" id="pic"<?php echo $is_edit ? '' : ' required'; ?>>
                  <small class="text-muted"><?php echo $is_edit ? 'Kosongkan jika tidak ingin mengganti gambar. ' : ''; ?>Pilih foto PNG atau JPG dengan ukuran maksimal 2MB.</small>
                </div>

                <div class="card-footer text-right">
                  <a href="<?php echo site_url('admin/banner_product'); ?>" class="btn btn-secondary">Kembali</a>
                  <input type="submit" value="<?php echo $submit_text; ?>" class="btn btn-primary">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php echo form_close(); ?>
    </div>

    <script>
      (function () {
        var type = document.getElementById('redirect-type');
        var product = document.getElementById('package');
        var category = document.getElementById('redirect-category');
        var url = document.getElementById('redirect-url');
        var groups = {
          product: document.querySelector('.banner-target-product'),
          category: document.querySelector('.banner-target-category'),
          custom: document.querySelector('.banner-target-custom')
        };

        function setRequired(input, state) {
          if (input) {
            input.required = state;
          }
        }

        function refreshTarget() {
          var selected = type ? type.value : 'product';

          Object.keys(groups).forEach(function (key) {
            if (groups[key]) {
              groups[key].style.display = key === selected ? '' : 'none';
            }
          });

          setRequired(product, selected === 'product');
          setRequired(category, selected === 'category');
          setRequired(url, selected === 'custom');
        }

        if (type) {
          type.addEventListener('change', refreshTarget);
          refreshTarget();
        }
      })();
    </script>
