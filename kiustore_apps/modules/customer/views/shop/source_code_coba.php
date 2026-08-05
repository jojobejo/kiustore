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


        <div>
                          <?php $total_weight = array_sum(array_column($carts, 'total_weight')); ?>
                          <input type="text" name="kiu" value="160" hidden>
                          <input type="text" name="subdis" value="<?= $t->sub_id ?>" hidden>
                          <input type="text" name="berat" value="<?= $total_weight ?>" hidden>
                        </div>

                        <?php
                          $total_weight = 0;
                          foreach ($carts as $item) {
                            $total_weight += $item['product_weight'] * $item['qty'];
                          ?>
                          <?php } ?>
                          <input type="text" name="kiu" value="<?= $total_weight ?>">