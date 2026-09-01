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
        $filters = $this->build_revenue_filters();

        $report['filters'] = $filters;
        $report['data'] = $this->report->revenue_report($filters);
        $report['total_revenue'] = $this->report->revenue_report_total($filters);
        $report['filter_summary'] = $this->revenue_filter_summary($filters);

        $this->load->view('header', $params);
        $this->load->view('reports/report', $report);
        $this->load->view('footer');
    }

    public function tabel($bulan = null, $tahun = null)
    {
        $filters = $this->build_revenue_filters($bulan, $tahun);

        $dt['data'] = $this->report->revenue_report($filters);
        $dt['total_revenue'] = $this->report->revenue_report_total($filters);
        $dt['filters'] = $filters;
        $dt['filter_summary'] = $this->revenue_filter_summary($filters);

        $this->load->view('reports/report_table', $dt);
    }

    public function excel($bulan = null, $tahun = null)
    {
        $filters = $this->build_revenue_filters($bulan, $tahun);
        $data = $this->report->revenue_report($filters);
        if($data->num_rows() > 0)
        {
            $filename = 'Laporan_Pendapatan_' . date('Ymd_His');
            header("Content-type: application/x-msdownload");
            header("Content-Disposition: attachment; filename=".$filename.".xls");

            echo "
                <table border='1' width='100%'>
                    <thead>
                        <tr>
                  <th>No</th>
                  <th>Kode Faktur</th>
                  <th>Nama Kios</th>
                  <th>Nama Pelanggan</th>
                  <th>Tanggal Transaksi</th>
                  <th>Metode Transaksi</th>
                  <th>Status Order</th>
                  <th>Nominal Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
            ";

            $no = 1;
            $total = 0;
            foreach($data->result() as $p)
            {
                $payment_method = get_payment_method($p->payment_method);
                $payment_method = ($payment_method) ? $payment_method : 'Tidak diketahui';
                $order_status = strip_tags(get_order_status($p->order_status));
                $invoice_code = !empty($p->kd_faktur) ? $p->kd_faktur : $p->order_number;
                $shop_name = !empty($p->shop_name) ? $p->shop_name : '-';
                echo "
                    <tr>
                <td>".$no."</td>
                <td>".html_escape($invoice_code)."</td>
                <td>".html_escape($shop_name)."</td>
                <td>".html_escape($p->customer)."</td>
                <td>".html_escape($p->order_date)."</td>
                <td>".html_escape($payment_method)."</td>
                <td>".html_escape($order_status)."</td>
                <td>Rp. ".str_replace(",", ".", number_format($p->total_price))."</td>
                    </tr>
                ";

                $total = $total + $p->total_price;
                $no++;
            }

            echo "
                    <tr>
                        <td colspan='7'><b>Total Keseluruhan</b></td>
                        <td><b>Rp. ".str_replace(",", ".", number_format($total))."</b></td>
                    </tr>
                </tbody>
                </table>
            ";
        }
    }

    private function build_revenue_filters($bulan = null, $tahun = null)
    {
        if ($bulan !== null && $tahun !== null) {
            $month = sprintf('%04d-%02d', (int) $tahun, (int) $bulan);

            return array(
                'period_type' => 'month_range',
                'start_date' => $month . '-01',
                'end_date' => date('Y-m-t', strtotime($month . '-01')),
                'start_month' => $month,
                'end_month' => $month,
                'year' => (int) $tahun
            );
        }

        $period_type = $this->input->get('period_type', true);
        $period_type = in_array($period_type, array('date_range', 'month_range', 'yearly'), true) ? $period_type : 'month_range';

        if ($period_type === 'date_range') {
            $start_date = $this->valid_date($this->input->get('start_date', true), 'Y-m-d') ? $this->input->get('start_date', true) : date('Y-m-01');
            $end_date = $this->valid_date($this->input->get('end_date', true), 'Y-m-d') ? $this->input->get('end_date', true) : date('Y-m-d');

            if (strtotime($start_date) > strtotime($end_date)) {
                $temp = $start_date;
                $start_date = $end_date;
                $end_date = $temp;
            }

            return array(
                'period_type' => $period_type,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_month' => date('Y-m', strtotime($start_date)),
                'end_month' => date('Y-m', strtotime($end_date)),
                'year' => (int) date('Y', strtotime($start_date))
            );
        }

        if ($period_type === 'yearly') {
            $year = (int) $this->input->get('year', true);
            $year = ($year >= 2000 && $year <= 2100) ? $year : (int) date('Y');

            return array(
                'period_type' => $period_type,
                'start_date' => $year . '-01-01',
                'end_date' => $year . '-12-31',
                'start_month' => $year . '-01',
                'end_month' => $year . '-12',
                'year' => $year
            );
        }

        $start_month = $this->valid_date($this->input->get('start_month', true), 'Y-m') ? $this->input->get('start_month', true) : date('Y-m');
        $end_month = $this->valid_date($this->input->get('end_month', true), 'Y-m') ? $this->input->get('end_month', true) : $start_month;

        if (strtotime($start_month . '-01') > strtotime($end_month . '-01')) {
            $temp = $start_month;
            $start_month = $end_month;
            $end_month = $temp;
        }

        return array(
            'period_type' => $period_type,
            'start_date' => $start_month . '-01',
            'end_date' => date('Y-m-t', strtotime($end_month . '-01')),
            'start_month' => $start_month,
            'end_month' => $end_month,
            'year' => (int) date('Y', strtotime($start_month . '-01'))
        );
    }

    private function valid_date($value, $format)
    {
        if (empty($value)) {
            return false;
        }

        $date = DateTime::createFromFormat($format, $value);

        return $date && $date->format($format) === $value;
    }

    private function revenue_filter_summary($filters)
    {
        if ($filters['period_type'] === 'yearly') {
            return 'Tahun ' . $filters['year'];
        }

        return date('d M Y', strtotime($filters['start_date'])) . ' s/d ' . date('d M Y', strtotime($filters['end_date']));
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
