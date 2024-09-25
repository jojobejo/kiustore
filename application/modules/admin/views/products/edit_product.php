<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Edit Produk</h6>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin'); ?>"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin/products'); ?>">Produk</a></li>
              <li class="breadcrumb-item"><a href="<?php echo site_url('admin/products/view/' . $product->id); ?>"><?php echo $product->name; ?></a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
  <?php echo form_open_multipart('admin/products/edit_product'); ?>
  <input type="hidden" name="id" value="<?php echo $product->id; ?>">

  <div class="row">
    <div class="col-md-8">
      <div class="card-wrapper">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">Data Produk</h3>
            <?php if ($flash) : ?>
              <span class="float-right text-success font-weight-bold" style="margin-top: -30px">
                <?php echo $flash; ?>
              </span>
            <?php endif; ?>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="form-control-label" for="pakcage">Kategori:</label>
                  <select name="category_id" class="form-control" id="package">
                    <option>Pilih kategori</option>
                    <?php if (count($categories) > 0) : ?>
                      <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category->id; ?>" <?php echo set_select('category_id', $category->id, ($product->category_id == $category->id) ? TRUE : FALSE); ?>>› <?php echo $category->name; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                  <?php echo form_error('category_id'); ?>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="form-control-label" for="name">Nama produk:</label>
              <input type="text" name="name" value="<?php echo set_value('name', $product->name); ?>" class="form-control" id="name">
              <?php echo form_error('name'); ?>
            </div>
            <?php if (admin_role() == 'admin' || admin_role() == 'keuangan') : ?>
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <label class="form-control-label" for="price">Harga Umum:</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" name="price" value="<?php echo set_value('price', $product->price); ?>" class="form-control" id="price">
                    </div>
                    <?php echo form_error('price'); ?>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label class="form-control-label" for="price_2">Harga R1:</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" name="price_2" value="<?php echo set_value('price_2', $product->price_2); ?>" class="form-control" id="price_2">
                    </div>
                    <?php echo form_error('price_2'); ?>
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label class="form-control-label" for="price_3">Harga R2:</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" name="price_3" value="<?php echo set_value('price_3', $product->price_3); ?>" class="form-control" id="price_3">
                    </div>
                    <?php echo form_error('price_3'); ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label class="form-control-label" for="stock">Stok :</label>
                  <input type="text" onkeyup="cek()" onkeydown="cek()" onchange="cek()" name="stock" value="<?php echo set_value('stock', $product->stock); ?>" class="form-control" id="stock">
                  <?php echo form_error('stock'); ?>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label class="form-control-label" for="product_unit_1">Satuan Kecil:</label>
                  <select name="product_unit_1" class="form-control" id="product_unit_1">
                    <option value="<?php echo  $product->product_unit_1; ?>"> <?php echo  $product->product_unit_1; ?></option>
                    <option value="<?php echo  $product->product_unit_1; ?>">Pilih satuan produk</option>
                    <option value="Pcs">Pcs</option>
                    <option value="Botol">Botol</option>
                    <option value="Kilo">Kilo</option>
                  </select>
                  <?php echo form_error('product_unit_1'); ?>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <label class="form-control-label" for="product_unit_value">Isi per BOX/KARUNG </label>
                  <input type="text" onkeyup="cek()" onkeydown="cek()" onchange="cek()" name="product_unit_value" value="<?php echo set_value('product_unit_value', $product->product_unit_value); ?>" class="form-control" id="product_unit_value">
                  <?php echo form_error('product_unit_value'); ?>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label class="form-control-label" for="stock">Satuan Besar:</label>
                  <select name="product_unit_2" class="form-control" id="product_unit_2">
                    <option value="<?php echo  $product->product_unit_2; ?>"><?php echo  $product->product_unit_2; ?></option>
                    <option value="<?php echo  $product->product_unit_2; ?>">Pilih satuan produk</option>
                    <option value="Box">Box</option>
                    <option value="Sak">Sak/karung</option>
                  </select>
                  <?php echo form_error('unit'); ?>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label class="form-control-label" for="desc">Cek Stok</label>
                  <input type="button"class="btn btn-primary" onclick="cek()" value="Cek Stok">
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label class="form-control-label" for="desc">Real Stok (BOX):</label>
                  <input type="text" name="result" value="" class="form-control" id="result" readonly>
                  <?php echo form_error('result'); ?>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label class="form-control-label" for="desc">Real Stok (PCS):</label>
                  <input type="text" name="result2" value="" class="form-control" id="result2" readonly>
                  <?php echo form_error('result2'); ?>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label" for="stock">Tipe Produk (Pembayaran yang disarankan untuk masing-masing produk)</label>
                  <select name="product_type" class="form-control" id="product_type" value="<?php echo set_value('product_type'); ?>">
                    <option value="<?php echo set_value('product_type'); ?>"><?php echo product_type($product->product_type, 'text'); ?></option>
                    <option value="1">0 Hari</option>
                    <option value="2">30 Hari</option>
                    <option value="3">60 Hari</option>
                  </select>
                  <?php echo form_error('product_type'); ?>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="form-control-label" for="desc">Deskripsi:</label>
              <textarea name="description" class="form-control" id="description"><?php echo set_value('description', $product->description); ?></textarea>
              <?php echo form_error('description'); ?>
            </div>

            <div class="form-group">
              <label for="av" class="form-control-label">
                <input type="checkbox" id="av" name="is_available" value="1" <?php echo set_checkbox('is_available', $product->is_available, ($product->is_available == 1) ? TRUE : FALSE); ?>> Apakah produk ini tersedia?
              </label>
            </div>

          </div>

        </div>

      </div>

    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-4">
              <h3 class="mb-0">Foto</h3>
            </div>
            <?php if ($product->picture_name) : ?>
              <div class="col-8">
                <ul class="nav nav-pills mb-3 float-right" id="pills-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link p-1 active" id="pills-current-tab" data-toggle="pill" href="#pills-current" role="tab" aria-controls="pills-home" aria-selected="true">Current</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link p-1" id="pills-edit-tab" data-toggle="pill" href="#pills-edit" role="tab" aria-controls="pills-profile" aria-selected="false">Ganti</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link p-1" id="pills-delete-tab" data-toggle="pill" href="#pills-delete" role="tab" aria-controls="pills-contact" aria-selected="false">Hapus</a>
                  </li>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="card-body">
          <?php if ($product->picture_name != NULL) : ?>
            <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade show active" id="pills-current" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="text-center">
                  <img alt="<?php echo $product->name; ?>" src="<?php echo base_url('assets/uploads/products/' . $product->picture_name); ?>" class="img img-fluid rounded">
                </div>
              </div>
              <div class="tab-pane fade" id="pills-edit" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="form-group">
                  <label class="form-control-label" for="pic">Foto:</label>
                  <input type="file" name="picture" class="form-control" id="pic">
                  <small class="text-muted">Pilih foto PNG atau JPG dengan ukuran maksimal 2MB</small>
                  <small class="newUploadText">Unggah file baru untuk mengganti foto saat ini.</small>
                </div>
              </div>
              <div class="tab-pane fade" id="pills-delete" role="tabpanel" aria-labelledby="pills-contact-tab">
                <p class="deleteText">Klik link dibawah untuk menghapus foto. Tindakan ini tidak dapat dibatalkan.</p>
                <div class="text-right">
                  <a href="#" class="deletePictureBtn btn btn-danger">Hapus</a>
                </div>
              </div>
            </div>
          <?php else : ?>
            <div class="form-group">
              <label class="form-control-label" for="pic">Foto:</label>
              <input type="file" name="picture" class="form-control" id="pic">
              <small class="text-muted">Pilih foto PNG atau JPG dengan ukuran maksimal 2MB</small>
            </div>
          <?php endif; ?>
        </div>
        <div class="card-footer text-right">
          <input type="submit" value="Simpan" class="btn btn-primary">
        </div>
      </div>
    </div>
  </div>

  </form>


  <script>
    $('.deletePictureBtn').click(function(e) {
      e.preventDefault();

      $(this).html('<i class="fa fa-spin fa-spinner"></i> Menghapus...');

      $.ajax({
        method: 'POST',
        url: '<?php echo site_url('admin/products/product_api?action=delete_image'); ?>',
        data: {
          id: <?php echo $product->id; ?>
        },
        context: this,
        success: function(res) {
          if (res.code == 204) {
            $('.deleteText').text('Gambar berhasil dihapus. Produk ini akan menggunakan gambar default jika tidak ada gambar baru yang diunggah');
            $(this).html('<i class="fa fa-check"></i> Terhapus!');

            setTimeout(function() {
              $('.newUploadText').text('Pilih gambar baru untuk mengganti gambar yang dihapus');
              $('#pills-delete, #pills-delete-tab, #pills-current, #pills-current-tab').hide('fade');
              $('#pills-edit').tab('show');
              $('#pills-edit-tab').addClass('active').text('Upload baru');
            }, 3000);
          } else {
            console.log('Terdapat kesalahan');
          }
        }
      })
    });

    function cek()
    {
      var  stock = document.getElementById("stock").value;
      var  product_unit_value = document.getElementById("product_unit_value").value;
      var  result = document.getElementById("result").value;
      var  result2 = document.getElementById("result2").value;

      result = (parseInt(stock) / parseInt(product_unit_value));
      document.getElementById("result").value = Math.floor(result);
      result2 = (parseInt(stock) - (parseInt(product_unit_value) * parseInt(result)));
      document.getElementById("result2").value = Math.floor(result2);
    }

  </script>
