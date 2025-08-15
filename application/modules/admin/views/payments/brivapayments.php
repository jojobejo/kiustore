<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Kelola Pembayaran</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
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
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3>BRIVA API</h3>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('createva/preview') ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nomor Customer</label>
                            <input type="number" name="cust_no" id="cust_no" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Customer</label>
                            <input type="text" name="custname" id="custname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Total Harga</label>
                            <input type="number" name="totprice" id="totprice" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kode Faktur</label>
                            <input type="text" name="transaksi_all" id="transaksi_all" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Preview</button>
                    </form>

                    <h3></h3>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>


    <script>

    </script>