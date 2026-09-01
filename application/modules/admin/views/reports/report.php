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
              <form action="<?php echo site_url('admin/report'); ?>" method="GET" id="revenueReportForm" class="px-4 pt-4">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-control-label">Jenis Periode</label>
                      <select name="period_type" id="periodType" class="form-control">
                        <option value="date_range" <?php echo ($filters['period_type'] == 'date_range') ? 'selected' : ''; ?>>Rentang Tanggal</option>
                        <option value="month_range" <?php echo ($filters['period_type'] == 'month_range') ? 'selected' : ''; ?>>Bulan ke Bulan</option>
                        <option value="yearly" <?php echo ($filters['period_type'] == 'yearly') ? 'selected' : ''; ?>>Tahunan</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 period-field" data-period="date_range">
                    <div class="form-group">
                      <label class="form-control-label">Tanggal Mulai</label>
                      <input type="date" name="start_date" class="form-control" value="<?php echo html_escape($filters['start_date']); ?>">
                    </div>
                  </div>
                  <div class="col-md-3 period-field" data-period="date_range">
                    <div class="form-group">
                      <label class="form-control-label">Tanggal Akhir</label>
                      <input type="date" name="end_date" class="form-control" value="<?php echo html_escape($filters['end_date']); ?>">
                    </div>
                  </div>
                  <div class="col-md-3 period-field" data-period="month_range">
                    <div class="form-group">
                      <label class="form-control-label">Bulan Mulai</label>
                      <input type="month" name="start_month" class="form-control" value="<?php echo html_escape($filters['start_month']); ?>">
                    </div>
                  </div>
                  <div class="col-md-3 period-field" data-period="month_range">
                    <div class="form-group">
                      <label class="form-control-label">Bulan Akhir</label>
                      <input type="month" name="end_month" class="form-control" value="<?php echo html_escape($filters['end_month']); ?>">
                    </div>
                  </div>
                  <div class="col-md-3 period-field" data-period="yearly">
                    <div class="form-group">
                      <label class="form-control-label">Tahun</label>
                      <input type="number" name="year" min="2000" max="2100" class="form-control" value="<?php echo html_escape($filters['year']); ?>">
                    </div>
                  </div>
                  <div class="col-md-3 d-flex align-items-end">
                    <div class="form-group w-100">
                      <button type="submit" class="btn btn-primary btn-block">Tampilkan</button>
                    </div>
                  </div>
                </div>
              </form>

              <div class="px-4 pb-4">
                <div class="row">
                  <div class="col-md-4">
                    <div class="bg-gradient-info rounded p-4 mb-3">
                      <h5 class="text-uppercase text-white mb-1">Total Pendapatan</h5>
                      <span class="h2 font-weight-bold text-white mb-0">Rp <?php echo format_rupiah($total_revenue); ?></span>
                      <p class="mt-2 mb-0 text-sm text-white">Periode <?php echo html_escape($filter_summary); ?></p>
                    </div>
                  </div>
                </div>
                <div id="result">
                  <?php $this->load->view('reports/report_table', array(
                    'data' => $data,
                    'total_revenue' => $total_revenue,
                    'filters' => $filters,
                    'filter_summary' => $filter_summary
                  )); ?>
                </div>
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
    function togglePeriodFields() {
      var periodType = $('#periodType').val();
      $('.period-field').hide();
      $('.period-field[data-period="' + periodType + '"]').show();
    }

    $('#periodType').on('change', togglePeriodFields);
    togglePeriodFields();
  });

</script>
