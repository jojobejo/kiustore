<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Data Ongkir</h6>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-md">
            <div class="card-wrapper">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <h2>Cek Ongkir</h2>
                        <form method="post" action="<?= base_url('Ongkir/cost') ?>">
                            <label>Origin District ID:</label>
                            <input type="text" name="origin_district_id" required><br><br>

                            <label>Destination District ID:</label>
                            <input type="text" name="destination_district_id" required><br><br>

                            <label>Berat (gram):</label>
                            <input type="number" name="weight" required><br><br>

                            <label>Kurir (jne, tiki, pos, jnt, sicepat, dll):</label>
                            <input type="text" name="courier" required><br><br>

                            <button type="submit">Cek Ongkir</button>
                        </form>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>