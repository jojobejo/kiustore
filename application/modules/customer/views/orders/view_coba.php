<div>
    <h3><?= $data->kode_faktur ?></h3>

    <section class="order-summary p-0">
        <h3 class="font-theme font-md">Data Order</h3>
        <!-- Product Summary Start -->
        <ul>
            <li>
                <span>No. Faktur</span>
                <span><?php echo $data->invoice_number; ?></span>
            </li>
            <li>
                <span>No. TTB</span>
                <span><?php echo $data->ttb_number; ?></span>
            </li>
            <li>
                <span>Tanggal Order</span>
                <span><?php echo get_formatted_date($data->order_date); ?></span>
            </li>
            <li>
                <span>Jatuh Tempo Pembayaran</span>
                <span><?php echo get_formatted_date($data->due_date); ?></span>
            </li>
            <li>
                <span>Jumlah Barang</span>
                <span><?php echo $data->total_items; ?></span>
            </li>
            <li>
                <span>Total Belanja</span>
                <span>Rp <?php echo format_rupiah($data->total_belanja); ?></span>
            </li>
            <li>
                <span>Ekspedisi</span>
                <span>Rp <?php echo format_rupiah($data->shipping_cost); ?></span>
            </li>
            <?php
            $final_price = $data->shipping_cost + $data->total_belanja;
            ?>
            <li>
                <span>Total Keseluruhan</span>
                <span class="font-theme">Rp <?php echo format_rupiah($final_price); ?></span>
            </li>
            
        </ul>
    </section>

    <?php foreach ($items as $item) : ?>
        <a class="media">
            <div class="count">
                <span class="font-sm"><?php echo $item->order_qty . ' ' . $item->satuan_text; ?></span>
                <i data-feather="x"></i>
            </div>
            <div class="media-body">
                <h4 class="title-color font-sm"><?php echo $item->name; ?></h4>
                <span class="content-color font-sm">@ Rp <?php echo format_rupiah($item->order_price); ?></span>
            </div>
            <span class="title-color font-md">Rp <?php echo format_rupiah($item->order_qty * $item->order_price); ?></span>
        </a>

    <?php endforeach; ?>
</div>