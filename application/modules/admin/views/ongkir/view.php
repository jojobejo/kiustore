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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>id_city</th>
                                    <th>Nama Provinsi</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($province) : ?>
                                    <?php foreach ($province->rajaongkir->results as $r) : ?>
                                        <tr>
                                            <td><?= $r->province_id ?></td>
                                            <td><?= $r->province ?></td>
                                            <td></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>