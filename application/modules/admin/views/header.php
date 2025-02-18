<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title><?php echo $title; ?> | <?php echo get_store_name(); ?></title>
  <!-- Favicon -->
  <link rel="icon" href="<?php echo site_url('assets/images/favicon.png'); ?>" type="image/png">
  <!-- Fonts -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"> -->
  <!-- Icons -->
  <link rel="stylesheet" href="<?php echo get_theme_uri('js/plugins/nucleo/css/nucleo.css', 'argon'); ?>" type="text/css">
  <link rel="stylesheet" href="<?php echo get_theme_uri('js/plugins/@fortawesome/fontawesome-free/css/all.min.css', 'argon'); ?>" type="text/css">




  <!-- Argon CSS -->
  <link rel="stylesheet" href="<?php echo get_theme_uri('css/argon9f1e.css?v=1.1.0', 'argon'); ?>" type="text/css">
  <link rel="stylesheet" href="<?php echo site_url('assets/css/chat.css'); ?>" type="text/css">
  <!-- <link href="https://cdn.datatables.net/v/bs4/dt-1.13.6/b-2.4.1/b-html5-2.4.1/r-2.5.0/datatables.min.css" rel="stylesheet"> -->
  <!-- <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" type="text/css"> -->
  <style>
    .star-checked {
      color: orange;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <!-- <script src="<?php echo get_theme_uri('vendor/jquery/dist/jquery.min.js', 'argon'); ?>"></script> -->
  <script src="<?php echo get_theme_uri('vendor/bootstrap/dist/js/bootstrap.bundle.min.js', 'argon'); ?>"></script>

  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css" rel="stylesheet">

  <script src="<?php echo site_url('assets/js/helper.js'); ?>"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
  <!-- <script src="https://cdn.datatables.net/v/bs4/dt-1.13.6/b-2.4.1/b-html5-2.4.1/r-2.5.0/datatables.min.js"></script> -->

  <!-- <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-html5-1.5.1/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.16/b-1.5.1/b-html5-1.5.1/datatables.min.js"></script> -->

</head>

<body class="@yield('sidebar_type')">
  <!-- Sidenav -->
  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-sm navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="https://os.youngpreneur.co.id/dashboard_admin">
          <img src="<?php echo site_url('assets/images/logo.png'); ?>" class="navbar-brand-img" alt="Logo">
        </a>
        <div class="ml-auto">
          <!-- Sidenav toggler -->
          <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link <?= ($this->uri->segment(1) == 'dashboard_admin' ? 'active' : '') ?>" href="<?php echo site_url('dashboard_admin'); ?>">
                <i class="ni ni-tv-2 text-primary"></i>
                <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            <?php if (admin_role() == 'admin' || admin_role() == 'adminonline') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(3) == 'category' ? 'active' : '') ?>" href="<?php echo site_url('admin/payments/briva_payment'); ?>">
                  <i class="ni ni-bullet-list-67 text-info"></i>
                  <span class="nav-link-text">Kategori Produk</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'payments' ? 'active' : '') ?>" href="<?php echo site_url('admin/brivaws'); ?>">
                  <i class="ni ni-bullet-list-67 text-info"></i>
                  <span class="nav-link-text">BRIVA API</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" <?= ($this->uri->segment(2) == 'ongkir' ? 'active' : '') ?> href="<?php echo site_url('admin/ongkir'); ?>">
                  <i class="ni ni-bullet-list-67 text-info"></i>
                  <span class="nav-link-text">Data Ongkir</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'banner_product' ? 'active' : '') ?>" href="<?php echo site_url('admin/banner_product'); ?>">
                  <i class="fa fa-image text-success"></i>
                  <span class="nav-link-text">Banner Produk</span>
                </a>
              </li>
            <?php endif; ?>
            <?php if (admin_role() == 'admin' || admin_role() == 'salesman' || admin_role() == 'adminonline' || admin_role() == 'keuangan') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'products' && $this->uri->segment(3) == '' ? 'active' : '') ?>" href="<?php echo site_url('admin/products'); ?>">
                  <i class="fa fa-cubes text-danger"></i>
                  <span class="nav-link-text">Produk</span>
                </a>
              </li>
            <?php endif; ?>
            <?php if (admin_role() == 'admin' || admin_role() == 'salesman' || admin_role() == 'adminonline') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'orders' && $this->uri->segment(3) == '' ? 'active' : '') ?>" href="<?php echo site_url('admin/orders'); ?>">
                  <i class="fa fa-shopping-basket text-warning"></i>
                  <span class="nav-link-text">Pesanan</span> <span class="badge badge-success" id="order_total"><?php echo get_total_order(); ?></span>
                </a>
              </li>
            <?php endif; ?>
            <?php if (admin_role() == 'admin' || admin_role() == 'distribusi') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(3) == 'distribusi' ? 'active' : '') ?>" href="<?php echo site_url('admin/orders/distribusi'); ?>">
                  <i class="fa fa-hourglass-start text-primary"></i>
                  <span class="nav-link-text">Pesanan (Distribusi)</span> <span class="badge badge-success" id="packing_total"><?php echo get_total_packing(); ?></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'pengiriman' ? 'active' : '') ?>" href="<?php echo site_url('admin/pengiriman'); ?>">
                  <i class="fa fa-truck text-info"></i>
                  <span class="nav-link-text">Pengiriman</span> <span class="badge badge-success" id="packing_total"></span>
                </a>
              </li>
            <?php endif; ?>
            <?php if (admin_role() == 'admin' || admin_role() == 'kadep') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(3) == 'kadep' ? 'active' : '') ?>" href="<?php echo site_url('admin/orders/kadep'); ?>">
                  <i class="fa fa-star text-success"></i>
                  <span class="nav-link-text">Rating Sales</span></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'rating' ? 'active' : '') ?>" href="<?php echo site_url('admin/rating'); ?>">
                  <i class="fa fa-envelope text-danger"></i>
                  <span class="nav-link-text">Nilai Rata-rata</span></span>
                </a>
              </li>
            <?php endif; ?>
            <?php if (admin_role() == 'admin' || admin_role() == 'salesman' || admin_role() == 'adminonline') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(3) == 'promo' ? 'active' : '') ?>" href="<?php echo site_url('admin/products/promo'); ?>">
                  <i class="fa fa-tags text-warning"></i>
                  <span class="nav-link-text">Promo</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(3) == 'coupons' ? 'active' : '') ?>" href="<?php echo site_url('admin/products/coupons'); ?>">
                  <i class="fa fa-tag text-info"></i>
                  <span class="nav-link-text">Kupon</span>
                </a>
              </li>
            <?php endif; ?>

            <?php if (admin_role() == 'admin' || admin_role() == 'keuangan') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'payments' ? 'active' : '') ?>" href="<?php echo site_url('admin/payments'); ?>">
                  <i class="fa fa-money-bill-alt text-danger"></i>
                  <span class="nav-link-text">Pembayaran</span> <span class="badge badge-success" id="payment_total"><?php echo get_unconfirmed_payment(); ?></span>
                </a>
              </li>
            <?php endif; ?>

            <?php if (admin_role() == 'admin' || admin_role() == 'keuangan') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'piutang' ? 'active' : '') ?>" href="<?php echo site_url('admin/piutang'); ?>">
                  <i class="fa fa-book text-success"></i>
                  <span class="nav-link-text">Piutang</span>
                </a>
              </li>
            <?php endif; ?>
            <?php if (admin_role() == 'admin' || admin_role() == 'salesman' || admin_role() == 'adminonline' || admin_role() == 'keuangan') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'customers' ? 'active' : '') ?>" href="<?php echo site_url('admin/customers'); ?>">
                  <i class="fa fa-users text-primary"></i>
                  <span class="nav-link-text">Pelanggan</span>
                </a>
              </li>
            <?php endif; ?>
            <?php if (admin_role() == 'admin') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == '' ? 'active' : '') ?>" href="<?php echo site_url('admin'); ?>">
                  <i class="fa fa-users text-success"></i>
                  <span class="nav-link-text">Users</span>
                </a>
              </li>
            <?php endif; ?>

            <!-- <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('admin/reviews'); ?>">
                <i class="fa fa-edit text-info"></i>
                <span class="nav-link-text">Review Pelanggan</span>
              </a>
            </li> -->
            <?php if (admin_role() == 'admin' || admin_role() == 'keuangan') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'report' ? 'active' : '') ?>" href="<?php echo site_url('admin/report'); ?>">
                  <i class="fa fa-book text-info"></i>
                  <span class="nav-link-text">Laporan</span>
                </a>
              </li>
            <?php endif; ?>
            <?php if (admin_role() == 'admin' || admin_role() == 'salesman' || admin_role() == 'adminonline') : ?>
              <li class="nav-item">
                <a class="nav-link <?= ($this->uri->segment(2) == 'messages' ? 'active' : '') ?>" href="<?php echo site_url('admin/messages'); ?>">
                  <i class="fa fa-comments text-danger"></i>
                  <span class="nav-link-text">Chat Pelanggan</span>
                  <!-- <span class="badge badge-success ml-1" id=""><?php echo unread_messages(); ?></span> -->
                  <span class="badge badge-success ml-1" id="unread_message"><?php echo all_unread_messages(); ?></span>
                </a>
              </li>
            <?php endif; ?>
            <!-- <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('admin/contacts'); ?>">
                <i class="fa fa-phone text-info"></i>
                <span class="nav-link-text">Kontak</span>
              </a>
            </li> -->
          </ul>

        </div>
      </div>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center ml-md-auto">
            <li class="nav-item d-xl-none">
              <!-- Sidenav toggler -->
              <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </div>
            </li>
            <li class="nav-item d-sm-none">
              <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                <i class="ni ni-zoom-split-in"></i>
              </a>
            </li>

          </ul>
          <ul class="navbar-nav align-items-center ml-auto ml-md-0">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img src="<?php echo get_admin_image(); ?>">
                  </span>
                  <div class="media-body ml-2 d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold"><?php echo get_admin_name(); ?></span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome!</h6>
                </div>
                <a href="<?php echo site_url('admin/settings/profile'); ?>" class="dropdown-item">
                  <i class="ni ni-single-02"></i>
                  <span>Profil</span>
                </a>
                <?php if (admin_role() == 'admin' || admin_role() == 'adminonline') : ?>
                  <a href="<?php echo site_url('admin/settings'); ?>" class="dropdown-item">
                    <i class="ni ni-settings-gear-65"></i>
                    <span>Pengaturan</span>
                  </a>
                <?php endif; ?>
                <div class="dropdown-divider"></div>
                <a href="<?php echo site_url('auth/logout'); ?>" class="dropdown-item">
                  <i class="ni ni-user-run"></i>
                  <span>Logout</span>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>