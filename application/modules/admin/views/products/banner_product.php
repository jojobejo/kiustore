<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Kelola Banner Produk</h6>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <a href="<?php echo site_url('admin/banner_product/add_new_banner_product'); ?>" class="btn btn-neutral">Tambah</a>
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
              <h3 class="mb-0">Kelola Banner Produk</h3>
              <?php if ($flash) : ?>
                <div class="alert alert-success mt-3 mb-0">
                  <?php echo $flash; ?>
                </div>
              <?php endif; ?>
              <?php if ($error) : ?>
                <div class="alert alert-danger mt-3 mb-0">
                  <?php echo $error; ?>
                </div>
              <?php endif; ?>
            </div>

            <?php if ( count($banners) > 0) : ?>
            <div class="card-body">
                <?php echo form_open('admin/banner_product/update_display_settings'); ?>
                <div class="alert alert-info">
                    Pilih maksimal 3 banner aktif. Urutan paling kecil akan tampil lebih dulu di homepage.
                </div>
                <div class="row">
                <?php foreach ($banners as $banner) : ?>
                    <div class="col-md-3">
                        <div class="card card-primary">
                            <div class="card-header">
                                <?php
                                  $banner_title = ! empty($banner->banner_title) ? $banner->banner_title : (! empty($banner->name) ? $banner->name : 'Banner Produk');
                                  $redirect_type = ! empty($banner->redirect_type) ? $banner->redirect_type : 'product';
                                  $redirect_label = 'Produk: '. (! empty($banner->name) ? $banner->name : '-');

                                  if ($redirect_type === 'category')
                                  {
                                      $redirect_label = 'Kategori: '. (! empty($banner->redirect_category_name) ? $banner->redirect_category_name : '-');
                                  }
                                  elseif ($redirect_type === 'custom')
                                  {
                                      $redirect_label = 'URL: '. (! empty($banner->redirect_url) ? $banner->redirect_url : '-');
                                  }
                                ?>
                                <h3 class="card-heading"><?php echo html_escape($banner_title); ?></h3>
                                <small class="text-muted"><?php echo html_escape($redirect_label); ?></small>
                                <div class="mt-2">
                                  <span class="badge badge-<?php echo (int) $banner->is_active === 1 ? 'success' : 'secondary'; ?>">
                                    <?php echo (int) $banner->is_active === 1 ? 'Tampil' : 'Disembunyikan'; ?>
                                  </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <img alt="<?php echo html_escape($banner_title); ?>" class="img img-fluid rounded" src="<?php echo base_url('assets/uploads/banner_product/'. $banner->banner_image); ?>" style="width: 1000px; max-height: 800px">

                                </div>

                                <div class="row mt-3">
                                  <div class="col-7">
                                    <label class="form-control-label" for="display-order-<?php echo (int) $banner->banner_id; ?>">Urutan</label>
                                    <input type="number" name="display_order[<?php echo (int) $banner->banner_id; ?>]" id="display-order-<?php echo (int) $banner->banner_id; ?>" class="form-control form-control-sm" min="1" step="1" value="<?php echo (int) $banner->display_order; ?>">
                                  </div>
                                  <div class="col-5">
                                    <label class="form-control-label d-block">Tampil</label>
                                    <label class="custom-toggle">
                                      <input type="checkbox" name="is_active[]" value="<?php echo (int) $banner->banner_id; ?>"<?php echo (int) $banner->is_active === 1 ? ' checked' : ''; ?>>
                                      <span class="custom-toggle-slider rounded-circle"></span>
                                    </label>
                                  </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <a href="<?php echo site_url('admin/banner_product/edit_banner_product/'. $banner->banner_id); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                <a href="<?php echo site_url('admin/banner_product/delete/'. $banner->banner_id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin akan menghapus banner produk ini?');"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="text-right">
                  <button type="submit" class="btn btn-primary">Simpan Setting Tampilan</button>
                </div>
                <?php echo form_close(); ?>
            </div>
            <?php else : ?>
             <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="alert alert-primary">
                            Belum ada data banner produk yang ditambahkan. Silahkan menambahkan baru.
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
