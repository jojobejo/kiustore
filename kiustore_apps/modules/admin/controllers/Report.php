<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        $this->load->model(array(
            'report_model' => 'report'
        ));
    }

    public function index()
    {
        $params['title'] = 'Laporan';

        $this->load->view('header', $params);
        $this->load->view('reports/report');
        $this->load->view('footer');
    }

    public function tabel($bulan, $tahun)
  	{
  		$dt['data'] 		= $this->report->tabel($bulan, $tahun);
  		$dt['bulan']		= $bulan;
  		$dt['tahun']		= $tahun;

  		$this->load->view('reports/report_table', $dt);
  	}

    public function excel($bulan, $tahun)
  	{
  		$this->load->model('report_model');
  		$data 	= $this->report_model->tabel($bulan, $tahun);
  		if($data->num_rows() > 0)
  		{
  			$filename = 'Report_'.$bulan.'_'.$tahun;
  			header("Content-type: application/x-msdownload");
  			header("Content-Disposition: attachment; filename=".$filename.".xls");

  			echo "
  				<table border='1' width='100%'>
  					<thead>
  						<tr>
                  <th>No</th>
                  <th>ID</th>
                  <th>PLU Product</th>
                  <th>Nama Barang</th>
                  <th>Kategori</th>
                  <th>Tanggal</th>
                  <th>Customer</th>
                  <th>QTY</th>
                  <th>Satuan</th>
                  <th>Total Harga</th>
  						</tr>
  					</thead>
  					<tbody>
  			";

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
                <td>".$p->order_date."</td>
                <td>".$p->customer."</td>
                <td>".$p->order_qty."</td>
                <td>".$p->satuan."</td>
                <td>Rp. ".str_replace(",", ".", number_format($p->total))."</td>
  					</tr>
  				";

  				$total = $total + $p->total;
  				$no++;
  			}

  			echo "
  				<tr>
  					<td colspan='9'><b>Total Keseluruhan</b></td>
  					<td><b>Rp. ".str_replace(",", ".", number_format($total))."</b></td>
  				</tr>
  			</tbody>
  			</table>
  			";
  		}
  	}

    public function view($id = 0)
    {
        if ( $this->order->is_order_exist($id))
        {
            $data = $this->order->order_data($id);
            $items = $this->order->order_items($id);
            $banks = json_decode(get_settings('payment_banks'));
            $banks = (Array) $banks;

            $params['title'] = 'Order #'. $data->order_number;

            $order['data'] = $data;
            $order['items'] = $items;
            $order['delivery_data'] = json_decode($data->delivery_data);
            $order['banks'] = $banks;
            $order['order_flash'] = $this->session->flashdata('order_flash');
            $order['payment_flash'] = $this->session->flashdata('payment_flash');

            $this->load->view('header', $params);
            $this->load->view('reports/view', $order);
            $this->load->view('footer');
        }
        else
        {
            show_404();
        }
    }

    public function status()
    {
        $status = $this->input->post('status');
        $order = $this->input->post('order');

        $this->order->set_status($status, $order);
        $this->session->set_flashdata('order_flash', 'Status berhasil diperbarui');

        redirect('admin/reports/view/'. $order);
    }

    public function get_total_order()
    {
        echo $this->db->where('order_status', 1)->get('orders')->num_rows();
    }
}
