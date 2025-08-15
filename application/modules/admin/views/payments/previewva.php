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
                    <h3>Preview Data Virtual Account</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th>Nomor Customer</th>
                            <td><?= $cust_no ?></td>
                        </tr>
                        <tr>
                            <th>Nama Customer</th>
                            <td><?= $custname ?></td>
                        </tr>
                        <tr>
                            <th>Total Harga</th>
                            <td><?= number_format($totprice, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th>Kode Faktur</th>
                            <td><?= $transaksi_all ?></td>
                        </tr>
                    </table>

                    <form action="<?= base_url('createva') ?>" method="post">
                        <input type="hidden" name="cust_no" value="<?= $cust_no ?>">
                        <input type="hidden" name="custname" value="<?= $custname ?>">
                        <input type="hidden" name="totprice" value="<?= $totprice ?>">
                        <input type="hidden" name="transaksi_all" value="<?= $transaksi_all ?>">
                        <button type="submit" class="btn btn-success">Konfirmasi & Create VA</button>
                        <a href="<?= base_url('createva/form') ?>" class="btn btn-secondary">Batal</a>
                    </form>

                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>