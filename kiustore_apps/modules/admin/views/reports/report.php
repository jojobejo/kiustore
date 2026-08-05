<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Laporan</h6>
        </div>
        <?php if (admin_role() == 'admin') : ?>
          <div class="col-lg-6 col-5 text-right">
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
              <a class="nav-link active" id="success-tab" data-toggle="pill" href="#success" role="tab" aria-controls="success" aria-selected="false">Laporan
                <span class="badge badge-warning" id="info-success"> </span>
              </a>
            </li>
            <!--
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="average-tab" data-toggle="pill" href="#average" role="tab" aria-controls="average" aria-selected="false">Average Sales Perform
                <span class="badge badge-warning" id="info-average"> </span>
              </a>
            </li>
          -->
          </ul>
        </div>

        <div class="card-body p-0">

          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="success" role="tabpanel" aria-labelledby="success-tab">
              <div class="table-responsive">
                <?php //echo form_open('admin/report/tabel', array('id' => 'FormLaporan'));
                ?>
                <form action="<?= base_url('admin/report/tabel') ?>" method="POST" id="FormLaporan1">
                  <div class="row">
                    <div class="col-sm-5">
                      <div class="form-horizontal">
                        <div class="form-group">
                          <label class="col-sm-4 control-label">Bulan</label>
                          <div class="col-sm-8">
                            <input type='month' name='from' class='form-control' id='bulan' value="<?php echo date('m-Y'); ?>" data-toggle="datetimepicker" data-target="#tanggal_dari" autocomplete="false">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class='row'>
                    <div class="col-sm-5">
                      <div class="form-horizontal">
                        <div class="form-group">
                          <div class="col-sm-4"></div>
                          <div class="col-sm-8">
                            <button type="submit" class="btn btn-primary" style='margin-left: 0px;'>Tampilkan</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php echo form_close(); ?>

                  <br />
                  <div id='result'></div>
              </div>
            </div>

            <!--
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
          -->
          </div>
        </div>


      </div>

    </div>
  </div>
</div>



<script>
  $(document).ready(function() {
    $('#bulan').datetimepicker({
      timepicker: false,
      format: 'm-Y'
    });
  });

</script>
