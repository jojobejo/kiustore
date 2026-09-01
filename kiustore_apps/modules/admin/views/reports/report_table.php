
<?php if($data->num_rows() > 0) { ?>

<?php
$export_query = http_build_query(array(
	'period_type' => $filters['period_type'],
	'start_date' => $filters['start_date'],
	'end_date' => $filters['end_date'],
	'start_month' => $filters['start_month'],
	'end_month' => $filters['end_month'],
	'year' => $filters['year']
));
?>

<div class="d-flex justify-content-between align-items-center mb-3">
	<div>
		<h3 class="mb-0">Laporan Pendapatan</h3>
		<small class="text-muted">Periode <?php echo html_escape($filter_summary); ?></small>
	</div>
	<a href="<?php echo site_url('admin/report/excel') . '?' . $export_query; ?>" target="_blank" class="btn btn-primary bi bi-filetype-xls"> Export ke Excel</a>
</div>

<table class='table table-bordered'>
	<thead>
		<tr>
      <th>No</th>
      <th>Kode Faktur</th>
      <th>Nama Kios</th>
      <th>Nama Pelanggan</th>
      <th>Tanggal Transaksi</th>
      <th>Metode Transaksi</th>
      <th>Status Order</th>
      <th class="text-right">Nominal Transaksi</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$no = 1;
		$total = 0;
		foreach($data->result() as $p)
		{
			$invoice_code = !empty($p->kd_faktur) ? $p->kd_faktur : $p->order_number;
			$shop_name = !empty($p->shop_name) ? $p->shop_name : '-';
			$payment_method = get_payment_method($p->payment_method);
			$payment_method = ($payment_method) ? $payment_method : 'Tidak diketahui';
			$order_status = get_order_status($p->order_status);
			$total = $total + $p->total_price;
			echo "
				<tr>
					<td>".$no."</td>
					<td>".html_escape($invoice_code)."</td>
					<td>".html_escape($shop_name)."</td>
					<td>".html_escape($p->customer)."</td>
          <td>".html_escape($p->order_date)."</td>
					<td>".html_escape($payment_method)."</td>
					<td>".$order_status."</td>
					<td class='text-right'>Rp. ".str_replace(",", ".", number_format($p->total_price))."</td>
				</tr>
			";

			$no++;
		}

		echo "
		<tr>
				<td colspan='7'><b>Total Keseluruhan</b></td>
				<td class='text-right'><b>Rp. ".str_replace(",", ".", number_format($total))."</b></td>
			</tr>
		";
		?>
	</tbody>
</table>
	<br />
	<?php } ?>

<?php if($data->num_rows() == 0) { ?>
<div class='alert alert-info'>
Data pendapatan periode <b><?php echo html_escape($filter_summary); ?></b> tidak ditemukan
</div>
<br />
<?php } ?>
