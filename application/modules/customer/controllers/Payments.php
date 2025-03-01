<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('customer');

        $this->load->model(array(
            'order_model' => 'order',
            'payment_model' => 'payment'
        ));
        $this->load->library('form_validation');

        // Payment Status
        // 7   =  batal
        // 6   =  done
        // 5   =  barang diterima
        // 4   =  pengiriman
        // 3   =  pengemasan
        // 2   =  menunggu pembayaran
        // 1   =  menunggu sales

    }

    public function index()
    {
        $params['title'] = 'Pembayaran Saya';

        $config['base_url'] = site_url('customer/payments/index');
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

        $payments['payments'] = $this->payment->get_all_payments($config['per_page'], $page);
        $payments['pagination'] = $this->pagination->create_links();

        $this->load->view('header', $params);
        $this->load->view('payments/payments', $payments);
        $this->load->view('footer');
    }

    public function confirm()
    {

        $order_id = $this->input->get('order');
        $cusid  = $this->session->userdata('user_id');

        $params['title'] = 'Konfirmasi Pembayaran';

        $payments['orders'] = $this->order->order_with_bank_payments($order_id);
        $payments['customer'] = $this->order->get_data_customer($cusid);
        $payments['target_time'] = date('Y-m-d H:i:s', strtotime('+2 hour'));
        //  $payments['orders'] = $this->payment->order_data($id);
        $payments['banks'] = (array) json_decode(get_settings('payment_banks'));
        $payments['order_id'] = $order_id;
        $payments['flash'] = $this->session->flashdata('payment_flash');
        $payments['payments'] = $this->payment->payment_list();

        $payments['generateva'] = $this->payment->payment_list();
        
        // print_r($payments['orders']);exit;
        $this->load->view('header', $params);
        $this->load->view('payments/confirm', $payments);
        $this->load->view('footer');
    }

    public function do_confirm()
    {
        // print_r($_POST);
        // exit;
        $this->form_validation->set_error_delimiters('<div class="font-weight-bold text-danger">', '</div>');

        $this->form_validation->set_rules('order_id', 'Order', 'required|numeric');
        $this->form_validation->set_rules('bank_name', 'Nama bank', 'required');
        $this->form_validation->set_rules('name', 'Nama pengirim', 'required');
        $this->form_validation->set_rules('bank_number', 'No. Rekening', 'required');
        $this->form_validation->set_rules('transfer', 'Jumlah transfer', 'required');
        $this->form_validation->set_rules('bank', 'Bank transfer tujuan', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->confirm();
        } else {
            $order_id = $this->input->post('order_id');
            $bank_name = $this->input->post('bank_name');
            $bank_number = $this->input->post('bank_number');
            $transfer = $this->input->post('transfer');
            $name = $this->input->post('name');
            $bank = $this->input->post('bank');

            $config['upload_path'] = './assets/uploads/payments/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 5096;

            $this->load->library('upload', $config);

            // if ($this->upload->do_upload('picture')) {
            //     $data = $this->upload->data();
            //     $config['image_library'] = 'gd2';
            //     $config['source_image'] = './assets/uploads/payments/' . $data['file_name'];
            //     $config['create_thumb'] = FALSE;
            //     $config['maintain_ratio'] = FALSE;
            //     $config['quality'] = '50%';
            //     $config['width'] = 400;
            //     $config['height'] = 600;
            //     $new_file_name = $data['file_name'];
            //     $config['new_image'] = './assets/uploads/payments/' . $new_file_name;
            //     $this->load->library('image_lib', $config);
            //     $this->image_lib->resize();
            //     $file_name = $data['file_name'];

            //     $picture_name = $file_name;
            // } else {
            //     show_error($this->upload->display_errors());
            // }

            $picture_name = "-";

            $data = array(
                'transfer_to' => $bank,
                'source' => array(
                    'bank' => $bank_name,
                    'name' => $name,
                    'number' => $bank_number
                )
            );
            $data = json_encode($data);

            $payment = array(
                'order_id' => $order_id,
                'payment_price' => $transfer,
                'payment_date' => date('Y-m-d H:i:s'),
                'picture_name' => $picture_name,
                'payment_data' => $data
            );
            $this->payment->register_payment($order_id, $payment);
            $this->session->set_flashdata('payment_flash', 'Konfirmasi berhasil dilakukan. Admin akan memverifikasinya dalam waktu 1x24 jam');

            redirect('order_history');
        }
    }

    public function view($id = 0)
    {
        if ($this->payment->is_payment_exist($id)) {
            $data = $this->payment->payment_data($id);
            $banks = json_decode(get_settings('payment_banks'));
            $banks = (array) $banks;

            $params['title'] = 'Pembayaran Order #' . $data->order_number;

            $payment['data'] = $data;
            $payment['banks'] = $banks;

            $payment['payment'] = json_decode($data->payment_data);

            $this->load->view('header', $params);
            $this->load->view('payments/view', $payment);
            $this->load->view('footer');
        } else {
            show_404();
        }
    }
}
