<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<?php if (count($cartaddons) > 0) : ?>
  <?php if (count($carts) > 0) : ?>
    <?php if ($member == '1') : ?>
      <main class="main-wrap cart-page mb-xxl">
        <?php if ($this->session->flashdata('limit')) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('limit'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        <div class="cart-item-wrap pt-0 mb-3">
          <?php foreach ($carts as $item) : ?>
            <div class="swipe-to-show cart-<?php echo $item['rowid']; ?>">
              <div class="product-list media">
                <a href="#"><img src="<?php echo get_product_image($item['id']); ?>" alt="offer" /></a>
                <div class="media-body">
                  <a href="#" class="font-sm"> <?php echo $item['name']; ?> </a>
                  <span class="content-color font-xs">Rp <?php echo format_rupiah($item['price']); ?> x <span class="qty-item-<?php echo $item['rowid']; ?>"><?php echo $item['qty']; ?> <?php echo $item['satuan_text']; ?></span></span>
                  <span class="title-color subtotal-item-<?php echo $item['rowid']; ?> font-sm">Rp <?php echo format_rupiah($item['subtotal']); ?></span>
                  <div class="plus-minus">
                    <i class="subs" data-feather="minus"></i>
                    <input class="cart-update" name="quantity[<?php echo $item['rowid']; ?>]" type="number" data-qty="<?php echo $item['qty']; ?>" data-rowid="<?php echo $item['rowid']; ?>" value="<?php echo $item['qty']; ?>" min="0" max="1000" />
                    <i class="adds" data-feather="plus"></i>
                  </div>
                </div>
              </div>
              <div class="delete-button" data-bs-toggle="offcanvas" data-bs-target="#confirmation" aria-controls="confirmation" data-rowid="<?php echo $item['rowid']; ?>" data-brid="<?php echo $item['id']; ?>">
                <i data-feather="trash"></i>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Cart Item Section End  -->
        <!-- Coupon Area-->

        <div class="card coupon-card mb-3">
          <div class="card-body">
            <div class="apply-coupon">
              <h6 class="mb-0">Apakah anda punya Kupon?</h6>
              <p class="mb-2">Masukkan kode kupon untuk mendapatkan potongan harga!</p>
              <div class="coupon-form">
                <input id="code" name="coupon_code" type="text" class="form-control" placeholder="">
              </div>
            </div>
          </div>
        </div>
        <!-- Cart Amount Area-->
        <div class="card cart-amount-area mb-3">
          <div class="card-body d-flex align-items-center justify-content-between">
            Subtotal <h5 class="total-price n-total mb-0">Rp <?php echo format_rupiah($total_price); ?></h5>
          </div>
        </div>

        <div class="card cart-amount-area mb-3">
          <div class="card-body d-flex align-items-center justify-content-between">
            Total <h5 class="total-price n-total mb-0">Rp <?php echo format_rupiah($total_price); ?></h5>
          </div>
        </div>

        <div hidden>
          <form action="<?= base_url('checkout'); ?>" method="POST">
            <input type="text" name="customer" value="<?= $this->session->userdata('user_id') ?>">
            <?php foreach ($carts as $item) : ?>
              <div class="swipe-to-show cart-<?php echo $item['rowid']; ?>">
                <div class="product-list media">
                  <a href="#"><img src="<?php echo get_product_image($item['id']); ?>" alt="offer" /></a>
                  <div class="media-body">
                    <a href="#" class="font-sm"> <?php echo $item['name']; ?> </a>
                    <span class="content-color font-xs">Rp <?php echo format_rupiah($item['price']); ?> x <span class="qty-item-<?php echo $item['rowid']; ?>"><?php echo $item['qty']; ?> <?php echo $item['satuan_text']; ?></span></span>
                    <span class="title-color subtotal-item-<?php echo $item['rowid']; ?> font-sm">Rp <?php echo format_rupiah($item['subtotal']); ?></span>
                    <div class="plus-minus">
                      <i class="subs" data-feather="minus"></i>
                      <input class="cart-update" name="quantity[<?php echo $item['rowid']; ?>]" type="number" data-qty="<?php echo $item['qty']; ?>" data-rowid="<?php echo $item['rowid']; ?>" value="<?php echo $item['qty']; ?>" min="0" max="1000" />
                      <i class="adds" data-feather="plus"></i>
                    </div>
                  </div>
                </div>
                <div class="delete-button" data-bs-toggle="offcanvas" data-bs-target="#confirmation" aria-controls="confirmation" data-rowid="<?php echo $item['rowid']; ?>">
                </div>
              </div>
            <?php endforeach; ?>
            <?php foreach ($itm_cart as $itm) : ?>
              <input type="text" value="<?= $itm->kdchart ?>" name="kdchart" id="kdchart">
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-success" style="width: 98%; margin-bottom: 10px; margin-left: 12px;">checkout</button>
        </form>
      </main>
    <?php elseif ($member == '0') : ?>
      <main class="main-wrap cart-page mb-xxl">
        <?php if ($this->session->flashdata('limit')) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('limit'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        <div class="cart-item-wrap pt-0 mb-3">
          <?php foreach ($carts as $item) : ?>
            <div class="swipe-to-show cart-<?php echo $item['rowid']; ?>">
              <div class="product-list media">
                <a href="#"><img src="<?php echo get_product_image($item['id']); ?>" alt="offer" /></a>
                <div class="media-body">
                  <a href="#" class="font-sm"> <?php echo $item['name']; ?> </a>
                  <span class="content-color font-xs">Rp <?php echo format_rupiah($item['price']); ?> x <span class="qty-item-<?php echo $item['rowid']; ?>"><?php echo $item['qty']; ?> <?php echo $item['satuan_text']; ?></span></span>
                  <span class="title-color subtotal-item-<?php echo $item['rowid']; ?> font-sm">Rp <?php echo format_rupiah($item['subtotal']); ?></span>
                  <div class="plus-minus">
                    <i class="subs" data-feather="minus"></i>
                    <input class="cart-update" name="quantity[<?php echo $item['rowid']; ?>]" type="number" data-qty="<?php echo $item['qty']; ?>" data-rowid="<?php echo $item['rowid']; ?>" value="<?php echo $item['qty']; ?>" min="0" max="1000" />
                    <i class="adds" data-feather="plus"></i>
                  </div>
                </div>
              </div>
              <div class="delete-button" data-bs-toggle="offcanvas" data-bs-target="#confirmation" aria-controls="confirmation" data-rowid="<?php echo $item['rowid']; ?>" data-brid="<?php echo $item['id']; ?>">
                <i data-feather="trash"></i>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Cart Item Section End  -->
        <!-- Coupon Area-->

        <div class="card coupon-card mb-3">
          <div class="card-body">
            <div class="apply-coupon">
              <h6 class="mb-0">Apakah anda punya Kupon?</h6>
              <p class="mb-2">Masukkan kode kupon untuk mendapatkan potongan harga!</p>
              <div class="coupon-form">
                <input id="code" name="coupon_code" type="text" class="form-control" placeholder="">
              </div>
            </div>
          </div>
        </div>

        <!-- Cart Amount Area-->
        <div class="card cart-amount-area mb-3">
          <div class="card-body d-flex align-items-center justify-content-between">
            Subtotal <h5 class="total-price n-total mb-0">Rp <?php echo format_rupiah($total_price); ?></h5>
          </div>
        </div>

        <?php foreach ($area as $a) :
          $subid = $a->subdistrict_id;
        ?>
          <?php if ($subid == '0') : ?>
            <a href="<?= base_url('profile') ?>" class="btn btn-warning" style="width: 98%; margin-bottom: 10px; margin-left: 12px;"> Verifikasi Alamat Terlebih dahulu</a>
            <!-- AREA TERCOVER -->
          <?php elseif ($subid == '2201' || $subid == '2211' || $subid == '2227' || $subid == '2218' || $subid == '2214' || $subid == '2215' || $subid == '2208' || $subid == '2216' || $subid == '2203' || $subid == '2204' || $subid == '2219' || $subid == '2202' || $subid == '2231' || $subid == '2229' || $subid == '2222' || $subid == '2220') : ?>
            <div hidden>
              <form action="<?= base_url('checkout'); ?>" method="POST">
                <input type="text" name="customer" value="<?= $this->session->userdata('user_id') ?>">
                <?php foreach ($carts as $item) : ?>
                  <div class="swipe-to-show cart-<?php echo $item['rowid']; ?>">
                    <div class="product-list media">
                      <a href="#"><img src="<?php echo get_product_image($item['id']); ?>" alt="offer" /></a>
                      <div class="media-body">
                        <a href="#" class="font-sm"> <?php echo $item['name']; ?> </a>
                        <span class="content-color font-xs">Rp <?php echo format_rupiah($item['price']); ?> x <span class="qty-item-<?php echo $item['rowid']; ?>"><?php echo $item['qty']; ?> <?php echo $item['satuan_text']; ?></span></span>
                        <span class="title-color subtotal-item-<?php echo $item['rowid']; ?> font-sm">Rp <?php echo format_rupiah($item['subtotal']); ?></span>
                        <div class="plus-minus">
                          <i class="subs" data-feather="minus"></i>
                          <input class="cart-update" name="quantity[<?php echo $item['rowid']; ?>]" type="number" data-qty="<?php echo $item['qty']; ?>" data-rowid="<?php echo $item['rowid']; ?>" value="<?php echo $item['qty']; ?>" min="0" max="1000" />
                          <i class="adds" data-feather="plus"></i>
                        </div>
                      </div>
                    </div>
                    <div class="delete-button" data-bs-toggle="offcanvas" data-bs-target="#confirmation" aria-controls="confirmation" data-rowid="<?php echo $item['rowid']; ?>">
                    </div>
                  </div>
                <?php endforeach; ?>
            </div>
            <?php foreach ($itm_cart as $itm) : ?>
              <input type="text" value="<?= $itm->kdchart ?>" name="kdchart" id="kdchart" hidden>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-success" style="width: 98%; margin-bottom: 10px; margin-left: 12px;">checkout</button>
            </form>
          <?php else : ?>
            <?php foreach ($sts_ongkir as $st) : ?>
              <?php if ($st->sts_ongkir == '0') : ?>
                <div class="card cart-amount-area mb-3">
                  <div class="card-body">
                    Pilih Ekpedisi
                    <form action="<?= base_url('cekongkir'); ?>" method="POST">
                      <?php foreach ($tmp_cart as $t) : ?>
                        <div>
                          <input type="text" name="kiu" value="160" hidden>
                          <input type="text" name="subdis" value="<?= $t->sub_id ?>" hidden>
                          <input type="text" name="berat" value="<?= $t->total_weights ?>" hidden>
                        </div>
                      <?php endforeach; ?>
                      <select name="kurir" id="kurir" class="form-control mt-2">
                        <option value="-" selected disabled>-- PILIH EKPEDISI --</option>
                        <option value="jne">JNE</option>
                        <option value="pos">POS INDONESIA</option>
                        <option value="tiki">TIKI</option>
                        <option value="j&t">J&T</option>
                        <option value="SICEPAT">SICEPAT</option>
                        <option value="ANTERAJA">ANTERAJA</option>
                        <option value="KARISMA">KARISMA</option>
                      </select>
                      <button class="btn btn-block btn-primary mt-2" style="width: 100%;">CEK ONGKIR</button>
                    </form>
                  </div>
                </div>
                <!-- stsongkir -->
              <?php elseif ($st->sts_ongkir == '1') : ?>
                <?php foreach ($ongkirs as $o) :
                  $now  = date('Y-m-d');
                  $jsongkir = explode(';', $o->jsongkir);
                  $expedisi = $o->sjasa;
                  if ($expedisi == "jne") {
                    $expedisi = 'JNE';
                  } elseif ($expedisi == "pos") {
                    $expedisi = 'POS INDONESIA';
                  } elseif ($expedisi == "tiki") {
                    $expedisi = 'TIKI';
                  }
                ?>
                  <div class="card cart-amount-area mb-2">
                    <div class="card-body  d-flex align-items-center justify-content-between">
                      Biaya Pengiriman
                      <h5 class="total-price  mb-0">Rp. <?= format_rupiah($jsongkir['2']); ?></h5>
                    </div>
                    <div class="card-body  d-flex align-items-center justify-content-between">
                      Ekpedisi
                      <input type="text" value="<?= $expedisi ?> - <?= $jsongkir['0'] ?>" hidden>
                      <input type="text" value="<?= $jsongkir['1'] ?>(Hari)" hidden>
                      <span class="badge bg-info" style="font-size: medium;"><?= $expedisi ?> (<?= $jsongkir['0'] ?>), Estimasi <?= $jsongkir['1'] ?> Hari</span>
                    </div>
                    <form action="<?= base_url('ongkir'); ?>" method="POST">
                      <input type="text" name="action" value="deleteongkir" hidden>
                      <input type="text" name="customer" value="<?= $this->session->userdata('user_id') ?>" hidden>
                      <button type="submit" class="btn btn-warning" style="width: 98%; margin-bottom: 10px; margin-left: 12px;">Pilih Expedisi Lain</button>
                    </form>
                  </div>
                  <div class="card cart-amount-area mb-3">
                    <div class="card-body d-flex align-items-center justify-content-between">
                      Total <h5 class="total-price n-total mb-0">Rp <?php echo format_rupiah($total_price + $jsongkir['2']); ?></h5>
                    </div>
                  </div>
                <?php endforeach; ?>
                <div hidden>
                  <form action="<?= base_url('checkout'); ?>" method="POST">
                    <input type="text" name="customer" value="<?= $this->session->userdata('user_id') ?>">
                    <?php foreach ($carts as $item) : ?>
                      <div class="swipe-to-show cart-<?php echo $item['rowid']; ?>">
                        <div class="product-list media">
                          <a href="#"><img src="<?php echo get_product_image($item['id']); ?>" alt="offer" /></a>
                          <div class="media-body">
                            <a href="#" class="font-sm"> <?php echo $item['name']; ?> </a>
                            <span class="content-color font-xs">Rp <?php echo format_rupiah($item['price']); ?> x <span class="qty-item-<?php echo $item['rowid']; ?>"><?php echo $item['qty']; ?> <?php echo $item['satuan_text']; ?></span></span>
                            <span class="title-color subtotal-item-<?php echo $item['rowid']; ?> font-sm">Rp <?php echo format_rupiah($item['subtotal']); ?></span>
                            <div class="plus-minus">
                              <i class="subs" data-feather="minus"></i>
                              <input class="cart-update" name="quantity[<?php echo $item['rowid']; ?>]" type="number" data-qty="<?php echo $item['qty']; ?>" data-rowid="<?php echo $item['rowid']; ?>" value="<?php echo $item['qty']; ?>" min="0" max="1000" />
                              <i class="adds" data-feather="plus"></i>
                            </div>
                          </div>
                        </div>
                        <div class="delete-button" data-bs-toggle="offcanvas" data-bs-target="#confirmation" aria-controls="confirmation" data-rowid="<?php echo $item['rowid']; ?>">
                        </div>
                      </div>
                    <?php endforeach; ?>
                </div>
                <?php foreach ($itm_cart as $itm) : ?>
                  <input type="text" value="<?= $itm->kdchart ?>" name="kdchart" id="kdchart" hidden>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-success" style="width: 98%; margin-bottom: 10px; margin-left: 12px;">checkout</button>
                </form>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        <?php endforeach; ?>

      </main>
    <?php endif; ?>

    <!-- Main End -->
  <?php else : ?>
    <main class="main-wrap empty-cart mb-xxl">
      <!-- Banner Start -->
      <div class="banner-box">
        <img src="<?php echo get_theme_uri('svg/empty-cart.svg'); ?>" class="img-fluid" alt="404" />
      </div>
      <!-- Banner End -->

      <!-- Error Section Start -->
      <section class="error mb-large">
        <h2 class="font-lg">Keranjang belanja anda kosong</h2>
        <!-- <p class="content-color font-md">Looks like you haven’t added anything to your cart yet. You will find a lot of interesting products on our “Shop” page</p> -->
        <a href="<?php echo site_url('category'); ?>" class="btn-solid">Mulai Belanja</a>
      </section>
      <!-- Error Section End -->
    </main>
  <?php endif; ?>
<?php else : ?>
  <main class="main-wrap empty-cart mb-xxl">
    <!-- Banner Start -->
    <div class="banner-box">
      <img src="<?php echo get_theme_uri('svg/empty-cart.svg'); ?>" class="img-fluid" alt="404" />
    </div>
    <!-- Banner End -->

    <!-- Error Section Start -->
    <section class="error mb-large">
      <h2 class="font-lg">Keranjang belanja anda kosong</h2>
      <!-- <p class="content-color font-md">Looks like you haven’t added anything to your cart yet. You will find a lot of interesting products on our “Shop” page</p> -->
      <a href="<?php echo site_url('category'); ?>" class="btn-solid">Mulai Belanja</a>
    </section>
    <!-- Error Section End -->
  </main>
<?php endif; ?>