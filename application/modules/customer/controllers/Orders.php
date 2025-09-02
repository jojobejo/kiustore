<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('customer');
        $this->load->library('Brivaws');
        $this->load->model(array(
            'order_model' => 'order'
        ));
    }

    public function index()
    {
        $params['title'] = 'Order Saya';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua';
        $orders['orders'] = $this->order->get_all_orders($filter);

        $orders['unpaid'] = 0;
        $orders['process'] = 0;
        $orders['deliver'] = 0;
        $orders['success'] = 0;
        $orders['cancel'] = 0;
        $orders['all']  = 0;
        // $orders['cancel'] = 0;
        foreach ($orders['orders'] as $dt) {
            if (($dt->payment_method == 2 &&  $dt->order_status == 2) || ($dt->payment_method == 1 && $dt->order_status == 2)) {
                $orders['unpaid']++;
            }
            if (($dt->payment_method == 2 &&  $dt->order_status == 1) || ($dt->payment_method == 1 && $dt->order_status == 9) || ($dt->payment_method == 2 &&  $dt->order_status == 3) || ($dt->payment_method == 2 &&  $dt->order_status == 8) || ($dt->payment_method == 1 &&  $dt->order_status == 1) || ($dt->payment_method == 1 &&  $dt->order_status == 3)) {
                $orders['process']++;
            }
            if (($dt->payment_method == 2 &&  $dt->order_status == 4) || ($dt->payment_method == 1 &&  $dt->order_status == 4)) {
                $orders['deliver']++;
            }
            if (($dt->payment_method == 2 &&  $dt->order_status == 6) || ($dt->payment_method == 2 &&  $dt->order_status == 5) || ($dt->payment_method == 1 &&  $dt->order_status == 6)) {
                $orders['success']++;
            }
            if (($dt->payment_method == 2 &&  $dt->order_status == 7) || ($dt->payment_method == 1 &&  $dt->order_status == 7)) {
                $orders['cancel']++;
            }
            if ($dt->payment_method > 0 &&  $dt->order_status > 0) {
                $orders['all']++;
            }
        }
        // print_r($unpaid);
        // exit;
        $this->load->view('header', $params);
        $this->load->view('orders/orders', $orders);
        $this->load->view('footer');
    }

    public function view($id = 0)
    {
        if ($this->order->is_order_exist($id)) {
            $data       = $this->order->order_data($id);
            $items      = $this->order->order_items($id);
            $banks      = json_decode(get_settings('payment_banks'));
            $banks      = (array) $banks;
            $cusid      = get_current_user_id();
            $now        = date('Y-m-d');
            $getongkir  = $this->order->getongkir_checkout($cusid, $now);

            $params['title'] = 'Order #' . $data->order_number;
            $order['data'] = $data;
            $order['items'] = $items;
            $order['banks'] = $banks;
            $order['delivery_data'] = json_decode($data->delivery_data);
            $order['customer'] = $this->order->get_data_customer($cusid);
            $order['is_ongkir'] = $this->order->is_ongkir_exist($data->kd_faktur);
            $order['briva'] = $this->order->data_va($cusid, $data->order_number);
            $order['getongkir'] = $getongkir;
            $order['kdfaktur'] = $data->order_number;

            $this->load->view('header', $params);
            $this->load->view('orders/view', $order);
            $this->load->view('footer');
        } else {
            show_404();
        }
    }

    public function check_payment_status($userno, $order_number)
    {
        $result = $this->brivaws->inquiryStatusVa($userno, $order_number);
        $response = json_decode($result, true);

        if (isset($response['additionalInfo']['paidStatus'])) {
            $status = $response['additionalInfo']['paidStatus'];
        } else {
            $status = 'N';
        }

        echo json_encode([
            'paidStatus' => $status,
            'response'   => $response
        ]);
    }


    public function get_time_left($order_number)
    {
        $cusid = get_current_user_id();
        $va = $this->order->data_va($cusid, $order_number);

        if ($va) {
            $create_at = $va[0]->create_at;
            $expired_at = date('Y-m-d H:i:s', strtotime($create_at . ' +15 minutes'));

            $now = date('Y-m-d H:i:s');
            $diff = strtotime($expired_at) - strtotime($now);

            if ($diff <= 0) {
                $time_left = "00:00:00";
            } else {
                $hours   = floor($diff / 3600);
                $minutes = floor(($diff % 3600) / 60);
                $seconds = $diff % 60;
                $time_left = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
            }

            echo json_encode([
                'expired_at' => $expired_at,
                'time_left'  => $time_left
            ]);
        } else {
            echo json_encode([
                'expired_at' => null,
                'time_left'  => "00:00:00"
            ]);
        }
    }


    public function test_status_va()
    {
        $response = $this->brivaws->inquiryVa("64054799", "WKB25825185860");
        $data['response'] = $response;
        $this->load->view('shop/test', $data);
    }

    public function order_api()
    {
        $action = $this->input->get('action');

        switch ($action) {
            case 'terima_order':
                $id = $this->input->post('id');
                $data = $this->order->order_data($id);

                if (($data->payment_method == 2 && $data->order_status == 4) || ($data->payment_method == 1 && $data->order_status == 4)) {
                    $this->order->terima_order($_POST);
                    $response = array('code' => 200, 'success' => TRUE, 'message' => 'Order diterima');
                } else {
                    $response = array('code' => 200, 'error' => TRUE, 'message' => 'Order tidak dapat diterima. payment method=' . $data->payment_method . ' order status=' . $data->order_status);
                }
                break;
            case 'cancel_order':
                $id = $this->input->post('id');
                $del_va = $this->input->post('del_va');
                $del_no = $this->input->post('del_no');
                $data = $this->order->order_data($id);

                if (($data->payment_method == 1 && $data->order_status == 1) ||
                    ($data->payment_method == 2 && $data->order_status == 1) ||
                    ($data->payment_method == 2 && $data->order_status == 2)
                ) {
                    $this->order->cancel_order($id);
                    $this->order->delete_va($del_va);
                    $this->brivaws->deleteVa($del_no, $del_va);
                    $response = array('code' => 200, 'success' => TRUE, 'message' => 'Order dibatalkan');
                } else {
                    $response = array('code' => 200, 'error' => TRUE, 'message' => 'Order tidak dapat dibatalkan. payment method=' . $data->payment_method . ' order status=' . $data->order_status);
                }
                break;

            case 'do_payment':
                $id =  $this->input->post('id');
                $userid = $this->input->post('user_id');
                $trxid = $this->input->post('order');
                $kdfaktur = $this->input->post('kdfaktur');
                $va_no = $this->input->post('va_no');
                $va_name = $this->input->post('va_name');
                $va_to_pay = $this->input->post('va_to_pay');
                $nocust = $this->input->post('nocust');
                $data = $this->order->order_data($id);

                $datava = array(
                    'order_number'      => $trxid,
                    'kd_faktur'         => $kdfaktur,
                    'user_id'           => $userid,
                    'name'              => $va_name,
                    'va_code'           => $va_no,
                    'userno'            => $nocust,
                    'total_price_topay' => $va_to_pay,
                    'exp_date'          => date('c', strtotime('+15 minutes')),
                    'status'            => '1'
                );

                if ($data->payment_method == 1 && $data->order_status == 2) {
                    $this->order->input_va($datava);
                    $this->brivaws->createVa($va_no, $va_name, $va_to_pay, $trxid);
                    $response = array('code' => 200, 'success' => TRUE, 'message' => 'Payment Telah terbuat');
                } else {
                    $response = array('code' => 200, 'error' => TRUE, 'message' => 'Payment tidak dapat di generate');
                }

                break;

            case 'delete_order':
                $id = $this->input->post('id');
                $data = $this->order->order_data($id);

                if (($data->payment_method == 1 && ($data->order_status == 5 || $data->order_status == 4)) || ($data->payment_method == 2 && ($data->order_status == 4 || $data->order_status == 3))) {
                    $this->order->delete_order($id);
                    $response = array('code' => 200, 'success' => TRUE, 'message' => 'Order dihapus');
                } else {
                    $response = array('code' => 200, 'error' => TRUE, 'message' => 'Order tidak dapat dihapus');
                }
                break;
        }

        $response = json_encode($response);
        $this->output->set_content_type('application/json')
            ->set_output($response);
    }
}
