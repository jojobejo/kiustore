
<?php if($data->num_rows() > 0) { ?>

<table class='table table-bordered'>
	<thead>
		<tr>
      <th>No</th>
			<th>Sales</th>
			<th>Total Order</th>
			<th>Rating</th>
			<th>Nilai Rata-rata</th>
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
					<td>".$p->sales."</td>
					<td>".$p->pesanan."</td>
					<td>".$p->rating."</td>
					<td>".$p->average."</td>
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
	<a href="<?php echo site_url('rating/excel/'.$bulan.'/'.$tahun); ?>" target='blank' class='btn btn-primary'><img src="<?php echo config_item('images'); ?>xls.png"> Export ke Excel</a>
</p>
<br />
<?php } ?>

<?php if($data->num_rows() == 0) { ?>
<div class='alert alert-info'>
Data dari bulan <b><?php echo $bulan; ?></b> dan tahun <b><?php echo $tahun; ?></b> tidak ditemukan
</div>
<br />
<?php } ?>
