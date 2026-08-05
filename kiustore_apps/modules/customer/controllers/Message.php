<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        verify_session('customer');

        $this->load->model(array(
            'message_model' => 'message',
        ));
        $this->user_id = get_current_user_id();
    }

    public function index()
    {
        // $params['title'] = get_settings('store_tagline');

        // $home['total_order'] = $this->order->count_all_orders();
        // $home['total_payment'] = $this->payment->count_all_payments();
        // $home['total_process_order'] = $this->order->count_process_order();
        // $home['total_review'] = $this->review->count_all_reviews();

        // $home['flash'] = $this->session->flashdata('store_flash');

        $data['message'] = $this->message->load_message();
        $this->message->read_all_messages();
        $this->load->view('header_single');
        $this->load->view('message', $data);
        $this->load->view('footer_single');
    }

    public function send()
    {        
        $salesman_id = $this->user_id = get_current_salesman_id();
        $customer_id = $this->user_id = get_current_user_id();;
        $message = trim((string) $this->input->post('message'));

        if ($message === '') {
            $response = array('code' => 422, 'error' => TRUE, 'message' => 'Pesan tidak boleh kosong');
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        $created_at = date('Y-m-d H:i:s');

        $data = array(
            'salesman_id' => $salesman_id,
            'customer_id' => $customer_id,
            'message' => $message,
            'created_at' => $created_at,
            'chat_from' => 2,
            'status' => 2
        );

        $send = $this->message->send($data);
        if ($send) {
            $response = array(
                'code' => 200,
                'error' => FALSE,
                'message' => 'Pesan dikirim',
                'data' => array(
                    'id' => (int) $send,
                    'message' => $message,
                    'chat_from' => 2,
                    'created_at' => $created_at,
                    'created_at_formatted' => date('d-m-Y H:i', strtotime($created_at))
                )
            );
        } else {
            $response = array('code' => 200, 'error' => TRUE, 'message' => 'Pesan gagal dikirim');
        }
        
        $response = json_encode($response);
        $this->output->set_content_type('application/json')->set_output($response);
    }

    public function count_unread()
    {
        
        $data = $this->message->count_unread();
        $response = json_encode($data);
        $this->output->set_content_type('application/json')
            ->set_output($response);
    }

    public function fetch()
    {
        $last_id = (int) $this->input->get('last_id');
        $messages = $this->message->load_message_after($last_id);

        if (!empty($messages)) {
            $this->message->read_all_messages();
        }

        $response = array(
            'code' => 200,
            'error' => FALSE,
            'data' => array_map(function ($message) {
                return array(
                    'id' => (int) $message->id,
                    'message' => $message->message,
                    'chat_from' => (int) $message->chat_from,
                    'created_at' => $message->created_at,
                    'created_at_formatted' => date('d-m-Y H:i', strtotime($message->created_at))
                );
            }, $messages)
        );

        $this->output->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
