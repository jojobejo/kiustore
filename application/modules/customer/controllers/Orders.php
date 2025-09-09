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

            $params['title']            = 'Order #' . $data->order_number;
            $order['data']              = $data;
            $order['items']             = $items;
            $order['banks']             = $banks;
            $order['delivery_data']     = json_decode($data->delivery_data);
            $order['customer']          = $this->order->get_data_customer($cusid);
            $order['is_ongkir']         = $this->order->is_ongkir_exist($data->kd_faktur);
            $order['briva']             = $this->order->data_va($cusid, $data->order_number);
            $order['is_briva']          = $this->order->is_va_exist($data->order_number);
            $order['getongkir']         = $getongkir;
            $order['kdfaktur']          = $data->order_number;

            $this->load->view('header', $params);
            $this->load->view('orders/view', $order);
            $this->load->view('footer');
        } else {
            show_404();
        }
    }

    public function cek_va_status($order_number)
    {

        $cusid          = $this->session->userdata('user_id');
        header('Content-Type: application/json');

        try {

            $briva      = $this->order->data_va($cusid, $order_number);
            $data_order = $this->order->get_data_order($order_number, $cusid);

            $dtorder    = !empty($data_order) ? $data_order[0] : null;
            $brivas     = !empty($briva) ? $briva[0] : null;

            $createResponse = $this->brivaws->createVa(substr($dtorder->phone_number, -8), $dtorder->name, $dtorder->total_price, $dtorder->order_number);

            $createJson = json_decode($createResponse);

            log_message('debug', "[BRIVA CREATE] Order: {$order_number}, Response: " . print_r($createJson, true));

            if ($createJson->responseMessage === "Success") {
                if (!$brivas) {

                    $datava = array(
                        'order_number'      => $order_number,
                        'kd_faktur'         => $dtorder->kd_faktur,
                        'user_id'           => $dtorder->user_id,
                        'name'              => $dtorder->name,
                        'va_code'           => '91118' . substr($dtorder->phone_number, -8),
                        'userno'            => substr($dtorder->phone_number, -8),
                        'total_price_topay' => $dtorder->total_price,
                        'exp_date'          => date('c', strtotime('+15 minutes')),
                        'status'            => '1'
                    );
                    $this->order->input_va($datava);
                }
                echo json_encode(['status' => 'Created', 'data' => $createJson]);
                return;
            }

            // Step 3: Jika VA sudah ada di BRIVA
            if ($createJson->responseMessage === "Invalid Bill/Virtual Account already exist") {
                if ($brivas) {
                    // Update VA
                    $updateResponse = $this->brivaws->updateVa(substr($dtorder->phone_number, -8), $dtorder->name, $dtorder->order_number, $dtorder->total_price);
                    $updateJson = json_decode($updateResponse);

                    log_message('debug', "[BRIVA UPDATE] Order: {$order_number}, Response: " . print_r($updateJson, true));

                    echo json_encode(['status' => 'Updated', 'data' => $updateJson]);
                    return;
                } else {
                    $retryResponse =  $this->brivaws->inquiryVa(substr($dtorder->phone_number, -8), $order_number);
                    $retryJson = json_decode($retryResponse);

                    log_message('error', "[BRIVA RETRY CREATE] Order: {$order_number}, Response: " . print_r($retryJson, true));

                    echo json_encode(['status' => 'Retry Created', 'data' => $retryJson]);
                    return;
                }
            }

            // Step 4: Fallback Inquiry jika tidak jelas
            $inquiryResponse = $this->brivaws->inquiryVa(substr($dtorder->phone_number, -8), $order_number);
            $inquiryJson = json_decode($inquiryResponse);

            log_message('debug', "[BRIVA INQUIRY] Order: {$order_number}, Response: " . print_r($inquiryJson, true));
            echo json_encode(['status' => $inquiryJson->responseMessage ?? 'Unknown', 'data' => $inquiryJson]);
        } catch (Exception $e) {
            log_message('error', "[BRIVA ERROR] " . $e->getMessage());
            echo json_encode(['status' => 'Error', 'message' => $e->getMessage()]);
        }

        // $cusid = $this->session->userdata('user_id');
        // $briva = $this->order->data_va($cusid, $order_number);

        // header('Content-Type: application/json');

        // if (!empty($briva)) {
        //     $brivas = $briva[0];
        //     $result = $this->brivaws->inquiryVa($brivas->userno, $brivas->order_number);

        //     log_message('debug', 'BRIVA Inquiry Response: ' . print_r($result, true));

        //     $json = json_decode($result);

        //     log_message('debug', 'Decoded JSON: ' . print_r($json, true));

        //     echo json_encode([
        //         'status'  => $json->responseMessage ?? 'briva tidak ditemukan',
        //         'data'    => $json
        //     ]);
        // } else {
        //     echo json_encode([
        //         'status' => 'Data Tidak Ditemukan',
        //         'data'   => null
        //     ]);
        // }
    }

    public function update_expired($order_number)
    {
        $this->db->where('order_number', $order_number)
            ->update('briva_api', ['status' => 3]);

        $this->db->where('order_number', $order_number)
            ->update('orders', ['order_status' => 7]);

        echo json_encode(['success' => true, 'message' => 'Expired status updated']);
    }

    public function update_briva_status()
    {
        $id         = $this->input->post('id');
        $userid     = $this->input->post('userid');
        $trxid      = $this->input->post('order');
        $kdfaktur   = $this->input->post('kdfaktur');
        $va_name    = $this->input->post('va_name');
        $va_to_pay  = $this->input->post('va_to_pay');
        $nocust     = $this->input->post('nocust');
        $data       = $this->order->order_data($id);

        if (!$data) {
            $response = ['code' => 404, 'error' => TRUE, 'message' => 'Order tidak ditemukan'];
        } else {
            if ($data->order_status == 2) {
                $datava = [
                    'order_number'      => $trxid,
                    'kd_faktur'         => $kdfaktur,
                    'user_id'           => $userid,
                    'name'              => $va_name,
                    'va_code'           => '91118' . $nocust,
                    'userno'            => $nocust,
                    'total_price_topay' => $va_to_pay,
                    'exp_date'          => date('c', strtotime('+15 minutes')),
                    'status'            => '1'
                ];

                $apiResponse = $this->brivaws->updateVa($nocust, $va_name, $trxid, $va_to_pay);
                $apiResponse = json_decode($apiResponse, true);

                if (isset($apiResponse['responseCode']) && $apiResponse['responseCode'] === "2002800") {
                    $response = [
                        'code'    => 200,
                        'success' => TRUE,
                        'message' => 'Payment Telah terbuat',
                        'api'     => $apiResponse
                    ];
                    $this->order->input_va($datava);
                } else {
                    $response = [
                        'code'    => 500,
                        'error'   => TRUE,
                        'message' => 'Gagal update ke BRIVA',
                        'api'     => $apiResponse
                    ];
                }
            } else {
                $response = ['code' => 400, 'error' => TRUE, 'message' => 'Payment tidak dapat di generate'];
            }
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
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

            case 'create_payment':
                $id         = $this->input->post('id');
                $userid     = $this->input->post('userid');
                $trxid      = $this->input->post('order');
                $kdfaktur   = $this->input->post('kdfaktur');
                $va_no      = $this->input->post('nocust');
                $va_name    = $this->input->post('va_name');
                $va_to_pay  = $this->input->post('va_to_pay');
                $nocust     = $this->input->post('nocust');
                $data       = $this->order->order_data($id);

                $datava = array(
                    'order_number'      => $trxid,
                    'kd_faktur'         => $kdfaktur,
                    'user_id'           => $userid,
                    'name'              => $va_name,
                    'va_code'           => '91118' . $nocust,
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
