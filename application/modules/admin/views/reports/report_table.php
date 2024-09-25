
<?php if($data->num_rows() > 0) { ?>

<table class='table table-bordered'>
	<thead>
		<tr>
      <th>No</th>
      <th>ID</th>
      <th>PLU Product</th>
      <th>Nama Barang</th>
      <th>Kategori</th>
      <th>QTY</th>
      <th>Satuan</th>
      <th>Total Harga</th>
      <th>Tanggal</th>
      <th>Customer</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$no = 1;
		$total = 0;
		foreach($data->result() as $p)
		{
			echo "
				<tr>
					<td>".$no."</td>
					<td>".$p->order_number."</td>
					<td>".$p->sku."</td>
					<td>".$p->nama_product."</td>
					<td>".$p->nama_kategori."</td>
          <td>".$p->order_qty."</td>
					<td>".$p->satuan."</td>
					<td>Rp. ".str_replace(",", ".", number_format($p->total))."</td>
          <td>".$p->order_date."</td>
					<td>".$p->customer."</td>
				</tr>
			";

			$no++;
		}

//		echo "
//		<tr>
//				<td colspan='9'><b>Total</b></td>
//				<td><b>Rp. ".str_replace(",", ".", number_format($total))."</b></td>
//			</tr>
//		";
		?>
	</tbody>
</table>

<p>
	<?php
	//	$bulan 	= date('Y-m', strtotime($bulan));
	//	$tahun		= date('Y-m', strtotime($tahun));
	?>
	<br>
	 <a href="<?php echo site_url('admin/report/excel/'.$bulan.'/'.$tahun); ?>" target='blank' class='btn btn-primary bi bi-filetype-xls'> Export ke Excel</a>
	</p>
	<br />
	<?php } ?>

<?php if($data->num_rows() == 0) { ?>
<div class='alert alert-info'>
Data dari bulan <b><?php echo $bulan; ?></b> dan tahun <b><?php echo $tahun; ?></b> tidak ditemukan
</div>
<br />
<?php } ?>
