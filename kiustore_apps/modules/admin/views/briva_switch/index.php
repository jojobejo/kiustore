<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_local = $mode === 'local';
?>
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">BRIVA SWITCH</h6>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard_admin'); ?>"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">BRIVA SWITCH</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h3 class="mb-0">Mode Payment BRIVA</h3>
          <?php if ($flash) : ?>
            <span class="float-right text-success font-weight-bold" style="margin-top: -30px">
              <?php echo $flash; ?>
            </span>
          <?php endif; ?>
        </div>
        <div class="card-body">
          <div class="alert <?php echo $is_local ? 'alert-warning' : 'alert-success'; ?>">
            <strong>Mode aktif:</strong>
            <?php echo $is_local ? 'LOCAL DEVELOPMENT' : 'PRODUCTION BRIVA'; ?>
          </div>

          <?php echo form_open('admin/briva-switch/update'); ?>
            <div class="custom-control custom-radio mb-3">
              <input name="mode" class="custom-control-input" id="mode_production" type="radio" value="production" <?php echo !$is_local ? 'checked' : ''; ?>>
              <label class="custom-control-label" for="mode_production">
                <strong>Production</strong><br>
                <span class="text-muted">Menggunakan flow BRIVA existing melalui library Brivaws dan API BRI.</span>
              </label>
            </div>

            <div class="custom-control custom-radio mb-4">
              <input name="mode" class="custom-control-input" id="mode_local" type="radio" value="local" <?php echo $is_local ? 'checked' : ''; ?>>
              <label class="custom-control-label" for="mode_local">
                <strong>Local Development</strong><br>
                <span class="text-muted">Membuat VA simulasi lokal, tidak melakukan request token, inquiry, update, create, atau delete ke API BRI production.</span>
              </label>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Simpan Mode
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <h3 class="mb-0">Audit Trail</h3>
        </div>
        <div class="card-body">
          <p class="text-sm mb-2"><strong>Key setting:</strong> briva_payment_mode</p>
          <p class="text-sm mb-2"><strong>Nilai valid:</strong> local, production</p>
          <p class="text-sm mb-0"><strong>Tabel payment:</strong> briva_api</p>
        </div>
      </div>
    </div>
  </div>
</div>
