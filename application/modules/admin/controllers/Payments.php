<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        $this->load->model(array(
            'order_model' => 'order',
            'payment_model' => 'payment'
        ));
    }

    public function index()
    {
        $params['title'] = 'Kelola Pembayaran';

        $config['base_url'] = site_url('admin/payments/index');
        $config['total_rows'] = $this->payment->count_all_payments();
        $config['per_page'] = 10;
        $config['uri_segment'] = 4;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = floor($choice);

        $config['first_link']       = '«';
        $config['last_link']        = '»';
        $config['next_link']        = '›';
        $config['prev_link']        = '‹';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';

        $this->load->library('pagination', $config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $payments['all_payments'] = $this->payment->get_all_payments($config['per_page'], $page);
        $payments['confirmed'] = $this->payment->get_confirmed_payments($config['per_page'], $page);
        $payments['not_confirmed'] = $this->payment->get_not_confirmed_payments($config['per_page'], $page);
        $payments['pagination'] = $this->pagination->create_links();
        // print_r('<pre>');
        // print_r($payments['all_payments']);
        // print_r('<pre>');
        // exit;
        $this->load->view('header', $params);
        $this->load->view('payments/payments', $payments);
        $this->load->view('footer');
    }

    public function view($id = 0)
    {
        if ($this->payment->is_payment_exist($id)) {
            $data = $this->payment->payment_data($id);

            $banks = json_decode(get_settings('payment_banks'));
            $banks = (array) $banks;

            $params['title'] = 'Pembayaran Order #' . $data->order_number;

            $payments['banks'] = $banks;
            $payments['payment'] = $data;
            $payments['flash'] = $this->session->flashdata('payment_flash');

            $this->load->view('header', $params);
            $this->load->view('payments/view', $payments);
            $this->load->view('footer');
        } else {
            show_404();
        }
    }

    public function verify()
    {
        $id = $this->input->post('id');
        $order_id = $this->input->post('order');
        $action = $this->input->post('action');
        $payment_method = $this->input->post('payment_method');
        $redir = $this->input->post('redir');

        if ($action == 1) {
            $status = 2;
            $flash = 'Pembayaran berhasil dikonfirmasi';
            $this->payment->set_payment_status($id, $status, $order_id);
        } else if ($action == 2) {
            $status = 3;
            $flash = 'Pembayaran Gagal';
            $this->payment->set_payment_status_gagal($id, $status, $order_id);
            //  $this->payment->delete($id);
        } else {
            $flash = 'Tidak ada tindakan dilakukan';
        }



        $this->session->set_flashdata('payment_flash', $flash);

        if ($redir == 1)
            redirect('admin/payments/view/' . $id);

        redirect('admin/orders/view/' . $order_id . '#payment_flash');
    }

    public function get_total_payment()
    {
        echo $this->db->where('payment_status', 2)->get('payments')->num_rows();
    }
    public function api($action = '')
    {
        switch ($action) {
            case 'payment_all':
                $payment = $this->payment->get_all_payments();
                $n = 0;
                foreach ($payment as $order) {
                    $payment[$n]->payment_status = get_payment_status($payment->status);
                    $payment[$n]->payment_date = get_formatted_date($payment->payment_date);
                    $payment[$n]->jumlah = format_rupiah($payment->payment_price);

                    $n++;
                }
                $payment['data'] = $payment;
                $response = $payment;
                break;
            case 'payment_not_confirmed':
                $orders = $this->order->get_all_orders_sales();
                $n = 0;
                foreach ($orders as $order) {
                    $orders[$n]->order_status = get_order_status($order->order_status, $order->payment_method);
                    $orders[$n]->payment_method = get_payment_method($order->payment_method);
                    $orders[$n]->order_date = get_formatted_date($order->order_date);;
                    $orders[$n]->final_price = format_rupiah($order->final_price);

                    $n++;
                }
                $orders['data'] = $orders;
                $response = $orders;
                break;
            case 'payment_confirmed':
                $orders = $this->order->get_all_orders_distribusi();
                $n = 0;
                foreach ($orders as $order) {
                    $orders[$n]->order_status = get_order_status($order->order_status, $order->payment_method);
                    $orders[$n]->payment_method = get_payment_method($order->payment_method);
                    $orders[$n]->order_date = get_formatted_date($order->order_date);;
                    $orders[$n]->final_price = format_rupiah($order->final_price);

                    $n++;
                }
                $orders['data'] = $orders;
                $response = $orders;
                break;
        }
    }

    // private $url = 'https://sandbox.partner.api.bri.co.id';
    // private $privateKey = file_get_contents('private_key_rsa.pem');
    // private $client_secret = '5JwtO5v1EkO0yswO'; //Consumer Secret
    // private $client_id = 'AGZJwGBUqlThM2XBzuLdA31qKzvujGKV'; //Consumer Key
    // private $xPartnerId = 'ROMalang'; // di generate oleh bri
    // private $partnerServiceId = '   22001'; // di generate oleh bri
    // private $customerNo = '00218322'; // di generate oleh partner

    // public function briva_payment()
    // {

    //     $params['title'] = 'Payment VIA BRIVA-API';
    //     $data['briva']   = $this->payment->payment_bri();

    //     $this->load->view('header', $params);
    //     $this->load->view('payments/brivapayments', $data);
    //     $this->load->view('footer');
    // }
}
