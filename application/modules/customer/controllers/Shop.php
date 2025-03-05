<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shop extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        verify_session('customer');
        $this->load->library('cart');
        $this->load->model(array(
            'product_model' => 'product',
            'customer_model' => 'customer',
            'profile_model' => 'profile',
            'Payment_model' => 'payment'
        ));
    }

    public $api_key = "197f7e1329685d3ed9d1468c54efc9dd";

    public function cart()
    {
        $data = $this->profile->get_profile();
        $cart['carts']      = $this->cart->contents();
        $cart['total_cart'] = $this->cart->total();
        $cart['user']       = $data;
        //ADD-ONS
        $cusids             = $this->session->userdata('user_id');
        $now                = date('Y-m-d');

        $cart['area']       = $this->payment->areacust($cusids);

        $cart['cartaddons'] = $this->product->count_tmp_cart($cusids, $now)->result();
        $cart['itm_cart']   = $this->product->get_tmp_cart($cusids, $now)->result();

        if (level_user() >= 2) {

            $ongkir = $cart['ongkir'] = "0";
            $cart['member']          = is_member();
            $cart['itm_cart']        = $this->product->get_tmp_cart($cusids, $now)->result();
            $cart['total_price']     = $cart['total_cart'];
            $cart['tmp_cart']        = $this->product->gettmpshop($cusids, $now)->result();
            $cart['ongkirs']         = $this->product->getongkirs($cusids, $now)->result();
            $cart['profilecustomer'] = $this->product->getcustomer($cusids)->result();
            $cart['sts_ongkir']      = $this->product->getstatusongkir($cusids, $now)->result();

            $this->load->view('header');
            $this->load->view('shop/cart', $cart);
            $this->load->view('footer');
        } elseif (level_user() == 1) {
            $ongkir = $cart['ongkir'] = "0";
            $cart['member']          = 0;
            $cart['itm_cart']        = $this->product->get_tmp_cart($cusids, $now)->result();
            $cart['total_price']     = $cart['total_cart'];
            $cart['tmp_cart']        = $this->product->gettmpshop($cusids, $now)->result();
            $cart['ongkirs']         = $this->product->getongkirs($cusids, $now)->result();
            $cart['profilecustomer'] = $this->product->getcustomer($cusids)->result();
            $cart['sts_ongkir']      = $this->product->getstatusongkir($cusids, $now)->result();

            $this->load->view('header');
            $this->load->view('shop/cart', $cart);
            $this->load->view('footer');
        }
    }

    public function cekongkir()
    {
        $kiu            = $this->input->post('kiu');
        $kec            = $this->input->post('subdis');
        $expedisi       = $this->input->post('kurir');
        $cusids         = $this->session->userdata('user_id');
        $now            = date('Y-m-d');
        $cart           = $this->cart->contents();
        $total_weight   = 0;

        foreach ($cart as $item) {
            $total_weight += $item['product_weight'] * $item['qty'];
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=" . $kiu . "&originType=city&destination=" . $kec . "&destinationType=subdistrict&weight=" . $total_weight . "&courier=" . $expedisi,

            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key:" . $this->api_key,
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $data['ckongkir'] = array('error' => true);
        } else {

            $data['ckongkir']   = json_decode($response);
            $data['customer']   = $this->customer->data();
            $data['weight']     = $total_weight;
            $data['itm_cart']   = $this->product->get_tmp_cart($cusids, $now)->result();
        }

        $this->load->view('header');
        $this->load->view('shop/carts', $data);
        $this->load->view('footer');
    }

    public function ongkir()
    {
        $action = $this->input->post('action');
        switch ($action) {
            case 'addongkir':
                $datajasa     =  $this->input->post('jasaongkir');
                $selectjasa   =  $this->input->post('jasa');
                $customer     =  $this->input->post('customer');
                $kdfaktur     =  $this->input->post('kdfaktur');
                $datenow      =  date('Y-m-d');

                $insrtdata = array(
                    'jsongkir'   => $datajasa,
                    'kd_faktur'   => $kdfaktur,
                    'sjasa'      => $selectjasa,
                    'idcustomer' => $customer,
                    'status'     => '1',
                    'create_at'  => $datenow
                );

                $updatests = array(
                    'sts_ongkir' => '1'
                );

                $this->product->addongkir($insrtdata);
                $this->product->updatests($customer, $datenow, $updatests);
                redirect('cart');
                break;

            case 'deleteongkir':
                $customer     =  $this->input->post('customer');
                $datenow      =  date('Y-m-d');

                $updatests = array(
                    'sts_ongkir' => '0'
                );

                $updatestss = array(
                    'status' => '3'
                );

                $this->product->updatests($customer, $datenow, $updatests);
                $this->product->stsongkir($customer, $datenow, $updatestss);
                redirect('cart');
                break;
        }
    }

    public function checkout($action = '')
    {
        if (!is_login()) {
            $coupon = $this->input->post('coupon_code');
            $quantity = $this->input->post('quantity');

            $this->session->set_userdata('_temp_coupon', $coupon);
            $this->session->set_userdata('_temp_quantity', $quantity);

            verify_session('customer');
        }

        if (empty($this->input->post())) {
            redirect('home', 'refresh');
        }

        switch ($action) {
            default:

                $iduser     = $this->session->userdata('user_id');
                // $kdchart    = $this->product->kdnonkomersial($iduser);
                $coupon = $this->input->post('coupon_code') ? $this->input->post('coupon_code') : $this->session->userdata('_temp_coupon');
                $quantity = $this->input->post('quantity') ? $this->input->post('quantity') : $this->session->userdata('_temp_quantity');

                if ($this->session->userdata('_temp_quantity') || $this->session->userdata('_temp_coupon')) {
                    $this->session->unset_userdata('_temp_coupon');
                    $this->session->unset_userdata('_temp_quantity');
                }

                $items = [];

                foreach ($quantity as $rowid => $qty) {
                    $items['rowid'] = $rowid;
                    $items['qty'] = $qty;
                }

                $this->cart->update($items);

                if (empty($coupon)) {
                    $discount = 0;
                    $disc = 'Tidak menggunkan kupon';
                } else {
                    if ($this->customer->is_coupon_exist($coupon)) {
                        if ($this->customer->is_coupon_active($coupon)) {
                            if ($this->customer->is_coupon_expired($coupon)) {
                                $discount = 0;
                                $disc = 'Kupon kadaluarsa';
                            } else {
                                $coupon_id = $this->customer->get_coupon_id($coupon);
                                $this->session->set_userdata('coupon_id', $coupon_id);

                                $credit = $this->customer->get_coupon_credit($coupon);
                                $discount = $credit;
                                $disc = '<span class="badge badge-success">' . $coupon . '</span> Rp ' . format_rupiah($credit);
                            }
                        } else {
                            $discount = 0;
                            $disc = 'Kupon sudah tidak aktif';
                        }
                    } else {
                        $discount = 0;
                        $disc = 'Kupon tidak terdaftar';
                    }
                }

                $items = [];
                $items_multi    = [];
                $total_price_multi[1] = 0;
                $total_price_multi[2] = 0;
                $total_price_multi[3] = 0;

                foreach ($this->cart->contents() as $item) {
                    $items[$item['id']]['qty'] = $item['qty'];
                    $items[$item['id']]['satuan'] = $item['satuan'];
                    $items[$item['id']]['satuan_text'] = $item['satuan_text'];
                    $items[$item['id']]['satuan_qty'] = $item['satuan_qty'];
                    $items[$item['id']]['price'] = $item['price'];

                    $items_multi[$item['product_type']][$item['id']]['qty'] = $item['qty'];
                    $items_multi[$item['product_type']][$item['id']]['satuan'] = $item['satuan'];
                    $items_multi[$item['product_type']][$item['id']]['satuan_text'] = $item['satuan_text'];
                    $items_multi[$item['product_type']][$item['id']]['satuan_qty'] = $item['satuan_qty'];
                    $items_multi[$item['product_type']][$item['id']]['price'] = $item['price'];
                    // $total_price_multi[$item['product_type']] +=  $item['price'];
                    $total_price_multi[$item['product_type']] += ($item['price'] * $item['qty']);
                }

                $akseslv        = is_members();

                if ($akseslv > 1) {

                    $now            = date('Y-m-d');
                    $subtotal       = $this->cart->total();
                    $ongkir         = 0;
                    $user_id        = $this->session->userdata('user_id');

                    $params['customer'] = $this->customer->data();
                    $params['kdchart'] = $this->product->getkdchart($iduser);
                    $params['subtotal'] = $subtotal;
                    $params['total']    = $subtotal + $ongkir - $discount;
                    $params['discount'] = $disc;

                    $this->session->set_userdata('order_quantity', $items);
                    $this->session->set_userdata('order_quantity_multi', $items_multi);
                    $this->session->set_userdata('total_price', $params['total']);
                    $this->session->set_userdata('total_price_multi', $total_price_multi);

                    $this->load->view('header');
                    $this->load->view('shop/checkout', $params);
                    $this->load->view('footer');
                } else {
                    $now            = date('Y-m-d');
                    $subtotal       = $this->cart->total();
                    $ongkir         = 0;
                    $user_id        = $this->session->userdata('user_id');
                    $jasa_ongkir    = $this->product->getongkir_checkout($iduser, $now);
                    $is_ongkir      = $this->product->is_ongkir($iduser);

                    $params['customer'] = $this->customer->data();
                    $params['subtotal'] = $subtotal;
                    $params['jasa_ongkir'] = $jasa_ongkir;
                    $params['is_ongkir'] = $is_ongkir;
                    $params['total']    = $subtotal + $ongkir - $discount;
                    $params['discount'] = $disc;
                    $params['kdchart'] = $this->product->getkdchart($iduser);

                    $this->session->set_userdata('order_quantity', $items);
                    $this->session->set_userdata('order_quantity_multi', $items_multi);
                    $this->session->set_userdata('total_price', $params['total']);
                    $this->session->set_userdata('total_price_multi', $total_price_multi);

                    $this->load->view('header');
                    $this->load->view('shop/checkout', $params);
                    $this->load->view('footer');
                }
                break;

            case 'order':

                $quantity   = $this->session->userdata('order_quantity');
                $user_id    = get_current_user_id();
                $coupon_id  = $this->session->userdata('coupon_id');
                $order_date = date('Y-m-d H:i:s');
                $due_date   = date('Y-m-d');
                $payment    = $this->input->post('payment');
                $shipping   = $this->input->post('shipping');
                $ongkirprice   = $this->input->post('ongkirprice');
                $kdfaktur   = $this->input->post('kdfaktur');
                $estimasi   = $this->input->post('estimasi');
                $jnkirim    = $this->input->post('jns_shipping');

                $name       = $this->input->post('name');
                $phone_number = $this->input->post('phone_number');
                $address    = $this->input->post('address');
                $shop_name  = $this->input->post('shop_name');
                $shop_address = $this->input->post('shop_address');
                $note       = $this->input->post('note');

                $delivery_data = array(
                    'customer' => array(
                        'name' => $name,
                        'phone_number' => $phone_number,
                        'address' => $address,
                        'shop_name' => $shop_name,
                        'shop_address' => $shop_address
                    ),
                    'note' => $note
                );

                $delivery_data = json_encode($delivery_data);

                // if credit payment
                if ($payment == 1) {
                    $quantity_multi = $this->session->userdata('order_quantity_multi');
                    $total_price_multi = $this->session->userdata('total_price_multi');
                    $total_credit = $total_price_multi[2] + $total_price_multi[3];

                    $limit_transaction = get_user_limit_transaction();
                    if ($total_credit > $limit_transaction) {
                        $this->session->set_flashdata('limit', 'Total belanjaan anda melebihi batas kredit. Sisa limit kredit anda <strong>Rp.' . format_rupiah($limit_transaction) . '</strong>');
                        redirect('cart');
                    }
                    if (is_members() == '0') {
                        $kdfakturs   = $this->input->post('kdfaktur');
                        foreach ($total_price_multi as $type => $total) {
                            if ($total) {
                                $order_number = $this->_create_order_number($quantity_multi[$type], $user_id, $coupon_id);
                                $due_date = ($type == 1 ? date('Y-m-d') : ($type == 2 ? date('Y-m-d', strtotime(' + 1 months')) : ($type == 3 ? date('Y-m-d', strtotime(' + 2 months')) : "")));

                                $order = array(
                                    'user_id' => $user_id,
                                    'coupon_id' => $coupon_id,
                                    'order_number' => $order_number,
                                    'kd_faktur'    => $kdfakturs,
                                    'order_status' => 9,
                                    'order_date' => $order_date,
                                    'total_price' => $total,
                                    'total_items' => count($quantity_multi[$type]),
                                    'payment_method' => ($type == 1 ? 2 : 1),
                                    'shipping_method' => $shipping,
                                    'delivery_data' => $delivery_data,
                                    'due_date' => $due_date,
                                    'jenis_pengiriman' => '89',
                                    'estimasi_kirim' => 0,
                                    'shipping_cost' => 0
                                );
                                $order = $this->product->create_order($order);
                                $n = 0;
                                foreach ($quantity_multi[$type] as $id => $data) {
                                    $items[$n]['order_id'] = $order;
                                    $items[$n]['product_id'] = $id;
                                    $items[$n]['order_qty'] = $data['qty'];
                                    $items[$n]['satuan'] = $data['satuan'];
                                    $items[$n]['satuan_text'] = $data['satuan_text'];
                                    $items[$n]['satuan_qty'] = $data['satuan_qty'];
                                    $items[$n]['order_price'] = $data['price'];
                                    $n++;
                                }
                                $this->product->create_order_items($items);
                                $this->product->removechartall($kdfakturs);
                            }
                        }
                    } else {
                        foreach ($total_price_multi as $type => $total) {
                            $jnkirim    = $this->input->post('jns_shipping');
                            if ($total) {
                                $order_number = $this->_create_order_number($quantity_multi[$type], $user_id, $coupon_id);
                                $due_date = ($type == 1 ? date('Y-m-d') : ($type == 2 ? date('Y-m-d', strtotime(' + 1 months')) : ($type == 3 ? date('Y-m-d', strtotime(' + 2 months')) : "")));

                                $order = array(
                                    'user_id' => $user_id,
                                    'coupon_id' => $coupon_id,
                                    'order_number' => $order_number,
                                    'kd_faktur'    => $kdfaktur,
                                    'order_status' => 9,
                                    'order_date' => $order_date,
                                    'total_price' => $total,
                                    'total_items' => count($quantity_multi[$type]),
                                    'payment_method' => ($type == 1 ? 2 : 1),
                                    'shipping_method' => $shipping,
                                    'delivery_data' => $delivery_data,
                                    'due_date' => $due_date,
                                    'jenis_pengiriman' => '89',
                                    'estimasi_kirim' => '0',
                                    'shipping_cost' => $ongkirprice
                                );
                                $order = $this->product->create_order($order);
                                $n = 0;
                                foreach ($quantity_multi[$type] as $id => $data) {
                                    $items[$n]['order_id'] = $order;
                                    $items[$n]['product_id'] = $id;
                                    $items[$n]['order_qty'] = $data['qty'];
                                    $items[$n]['satuan'] = $data['satuan'];
                                    $items[$n]['satuan_text'] = $data['satuan_text'];
                                    $items[$n]['satuan_qty'] = $data['satuan_qty'];
                                    $items[$n]['order_price'] = $data['price'];
                                    $n++;
                                }
                                $this->product->create_order_items($items);
                            }
                        }
                    }
                } else {
                    $user_id    = get_current_user_id();
                    // untuk metode pembayaran cash order langsung dijadikan 1
                    if (is_member() == '1') {
                        $total_price = $this->session->userdata('total_price');
                        $order_number = $this->_create_order_number($quantity, $user_id, $coupon_id);
                        $order = array(
                            'user_id' => $user_id,
                            'coupon_id' => $coupon_id,
                            'order_number' => $order_number,
                            'kd_faktur' => $kdfaktur,
                            'order_status' => 1,
                            'order_date' => $order_date,
                            'total_price' => $total_price,
                            'total_items' => count($quantity),
                            'payment_method' => $payment,
                            'shipping_method' => $shipping,
                            'delivery_data' => $delivery_data,
                            'due_date' => $due_date,
                            'jenis_pengiriman' => 89,
                            'estimasi_kirim' => 0,
                            'shipping_cost' => 0
                        );

                        $order = $this->product->create_order($order);
                        $n = 0;

                        foreach ($quantity as $id => $data) {
                            $items[$n]['order_id'] = $order;
                            $items[$n]['product_id'] = $id;
                            $items[$n]['order_qty'] = $data['qty'];
                            $items[$n]['satuan'] = $data['satuan'];
                            $items[$n]['satuan_text'] = $data['satuan_text'];
                            $items[$n]['satuan_qty'] = $data['satuan_qty'];
                            $items[$n]['order_price'] = $data['price'];
                            $n++;
                        }

                        // GENERATE KDCHART
                        $vacode = $this->payment->get_va_code($user_id);
                        $createva = "2123" . $vacode->id . $vacode->custno;

                        $generatechart = array(
                            'kdchart'   => $kdfaktur
                        );

                        $datava = array(
                            'order_number'  => $order_number,
                            'user_id'       => $user_id,
                            'va_code'       => $createva,
                            'status'        => '1'
                        );

                        $this->product->create_order_items($items);
                        $this->payment->input_va($datava);
                        $this->product->removechartall($kdfaktur);
                        $this->product->insertgenerate($generatechart);
                    } elseif (is_member() == '0') {
                        $total_price = $this->session->userdata('total_price');
                        $order_number = $this->_create_order_number($quantity, $user_id, $coupon_id);
                        $order = array(
                            'user_id' => $user_id,
                            'coupon_id' => $coupon_id,
                            'order_number' => $order_number,
                            'kd_faktur' => $kdfaktur,
                            'order_status' => 2,
                            'order_date' => $order_date,
                            'total_price' => $total_price,
                            'total_items' => count($quantity),
                            'payment_method' => $payment,
                            'shipping_method' => $shipping,
                            'delivery_data' => $delivery_data,
                            'due_date' => $due_date,
                            'jenis_pengiriman' => $jnkirim,
                            'estimasi_kirim' => 0,
                            'shipping_cost' => 0
                        );

                        $order = $this->product->create_order($order);
                        $n = 0;

                        foreach ($quantity as $id => $data) {
                            $items[$n]['order_id'] = $order;
                            $items[$n]['product_id'] = $id;
                            $items[$n]['order_qty'] = $data['qty'];
                            $items[$n]['satuan'] = $data['satuan'];
                            $items[$n]['satuan_text'] = $data['satuan_text'];
                            $items[$n]['satuan_qty'] = $data['satuan_qty'];
                            $items[$n]['order_price'] = $data['price'];
                            $n++;
                        }

                        // GENERATE KDCHART
                        $vacode = $this->payment->get_va_code($user_id);
                        $createva = "2123" . $vacode->id . $vacode->custno;

                        $generatechart = array(
                            'kdchart'   => $kdfaktur
                        );

                        $datava = array(
                            'order_number'  => $order_number,
                            'user_id'       => $user_id,
                            'va_code'       => $createva,
                            'status'        => '1'
                        );

                        $updatesongkir = array(
                            'status'    => '3'
                        );
                        $now = date('Y-m-d');
                        $this->product->stsongkir($user_id, $now, $updatesongkir);
                        $this->product->create_order_items($items);
                        $this->payment->input_va($datava);
                        $this->product->removechartall($kdfaktur);
                        $this->product->insertgenerate($generatechart);
                    } else {
                        $user_id    = get_current_user_id();
                        $total_price = $this->session->userdata('total_price');
                        $order_number = $this->_create_order_number($quantity, $user_id, $coupon_id);
                        $order = array(
                            'user_id' => $user_id,
                            'coupon_id' => $coupon_id,
                            'order_number' => $order_number,
                            'kd_faktur' => $kdfaktur,
                            'order_status' => 1,
                            'order_date' => $order_date,
                            'total_price' => $total_price,
                            'total_items' => count($quantity),
                            'payment_method' => $payment,
                            'shipping_method' => $shipping,
                            'delivery_data' => $delivery_data,
                            'due_date' => $due_date,
                            'jenis_pengiriman' => $jnkirim,
                            'estimasi_kirim' => $estimasi,
                            'shipping_cost' => $ongkirprice
                        );

                        $order = $this->product->create_order($order);
                        $n = 0;

                        foreach ($quantity as $id => $data) {
                            $items[$n]['order_id'] = $order;
                            $items[$n]['product_id'] = $id;
                            $items[$n]['order_qty'] = $data['qty'];
                            $items[$n]['satuan'] = $data['satuan'];
                            $items[$n]['satuan_text'] = $data['satuan_text'];
                            $items[$n]['satuan_qty'] = $data['satuan_qty'];
                            $items[$n]['order_price'] = $data['price'];
                            $n++;
                        }

                        // GENERATE KDCHART
                        $vacode = $this->payment->get_va_code($user_id);
                        $createva = "2123" . $vacode->id . $vacode->custno;

                        $generatechart = array(
                            'kdchart'   => $kdfaktur
                        );
                        $datava = array(
                            'order_number'  => $order_number,
                            'user_id'       => $user_id,
                            'va_code'       => $createva,
                            'status'        => '1'
                        );
                        $updatesongkir = array(
                            'status'    => '3'
                        );

                        $now            = date('Y-m-d');
                        $this->product->stsongkir($user_id, $now, $updatesongkir);
                        $this->product->create_order_items($items);
                        $this->payment->input_va($datava);
                        $this->product->removechartall($kdfaktur);
                        $this->product->insertgenerate($generatechart);
                    }
                }

                $this->cart->destroy();
                $this->session->unset_userdata('order_quantity');
                $this->session->unset_userdata('total_price');
                $this->session->unset_userdata('total_price_muulti');
                $this->session->unset_userdata('coupon_id');

                $this->session->set_flashdata('order_flash', 'Order berhasil ditambahkan');
                if ($payment == 1) {
                    redirect('customer/orders/');
                } else {
                    redirect('customer/orders/view/' . $order);
                }
                break;
        }
    }

    public function cart_api()
    {
        $action     = $this->input->get('action');
        $iduser     = $this->session->userdata('user_id');
        $kdchart    = $this->product->kdnonkomersial($iduser);

        switch ($action) {
            case 'add_item':
                $id     = $this->input->post('id');
                $idcus  = $this->session->userdata('user_id');
                $qty = $this->input->post('qty');
                $satuan = $this->input->post('satuan');
                $satuan_text = $this->input->post('satuan_text');
                $satuan_qty = $this->input->post('satuan_qty');
                // $sku = $this->input->post('sku');
                $name = $this->input->post('name');
                $product_type = $this->input->post('product_type');
                $product_weight = $this->input->post('product_weight');
                $now    = date('Y-m-d');

                if ($satuan == 1) {
                    $price      = $this->input->post('price');
                    $qty_pcs    = $qty;
                    $weight     = $product_weight * $qty_pcs;
                } else {
                    $price      = $this->input->post('price') * $this->input->post('satuan_qty');
                    $qty_pcs    = $qty * $satuan_qty;
                    $weight     = $product_weight * $qty_pcs;
                }

                // TOTAL PRICE
                $total_price_item = $qty * $price;
                $total_price_in_cart = $this->cart->total();
                $total_price = $total_price_item + $total_price_in_cart;

                // TOTAL WEIGTH
                $total_weight_item = $weight;

                $stock = $this->product->get_stock($id);
                // $response = array('code' => 200, 'message' => 'stok ' . $stock, 'total_item' => 0);

                if ($qty_pcs <= $stock) {

                    $item = array(
                        'id' => $id,
                        'qty' => $qty,
                        'satuan' => $satuan,
                        'satuan_text' => $satuan_text,
                        'satuan_qty' => $satuan_qty,
                        'price' => $price,
                        'name' => $name,
                        'product_type' => $product_type,
                        'product_weight' => $product_weight,
                        'total_weight'  => $total_weight_item,
                        'kdchart'  => $kdchart
                    );

                    $items = array(
                        'kdchart'  => $kdchart,
                        'idbarang'  => $id,
                        'idcustomer' => $idcus,
                        'qty'   => $qty,
                        'satuan' => $satuan,
                        'satuan_text' => $satuan_text,
                        'satuan_qty' => $satuan_qty,
                        'price' => $price,
                        'name' => $name,
                        'product_type' => $product_type,
                        'product_weight' => $product_weight,
                        'total_weight'  => $total_weight_item,
                        'create_at' => $now
                    );

                    $this->cart->insert($item);
                    $this->product->tmp_cart_customer($items);
                    $total_item = count($this->cart->contents());

                    // if ($_SESSION['user_level'] != 1) {
                    //     $max = get_user_limit_transaction();
                    // } else {
                    //     $max = 0;
                    // }

                    $response = array('code' => 200, 'message' => 'Item dimasukkan dalam keranjang', 'total_item' => $total_item);
                } else {
                    $response = array('code' => 202, 'message' => 'Gagal memasukkan dalam keranjang. Stok Tidak Tersedia , Hubungi Admin Sales Anda');
                }

                break;
            case 'display_cart':
                $carts = [];
                foreach ($this->cart->contents() as $items) {

                    $carts[$items['rowid']]['id'] = $items['id'];
                    $carts[$items['rowid']]['name'] = $items['name'];
                    $carts[$items['rowid']]['qty'] = $items['qty'];
                    $carts[$items['rowid']]['price'] = $items['price'];
                    $carts[$items['rowid']]['subtotal'] = $items['subtotal'];
                    $carts[$items['rowid']]['product_weight'] = $items['product_weight'];
                    $carts[$items['rowid']]['total_weight'] = $items['total_weight'];
                    $carts[$items['rowid']]['kdchart'] = $items['kdchart'];
                }
                $response = array('code' => 200, 'carts' => $carts);
                break;
            case 'cart_info':

                $total_price = $this->cart->total();
                $total_item = count($this->cart->contents());

                $data['total_price'] = $total_price;
                $data['total_item'] = $total_item;

                $response['data'] = $data;
                break;

            case 'remove_item':

                $rowid = $this->input->post('rowid');
                $carts = $this->input->post('brid');

                $this->product->removechart($kdchart, $carts);
                $this->cart->remove($rowid);
                $total_price = $this->cart->total();
                $total_item = $this->cart->total_items();
                // $ongkir = (int) ($total_price >= get_settings('min_shop_to_free_shipping_cost')) ? 0 : get_settings('shipping_cost');
                $ongkir = 0;
                $data['code'] = 204;
                $data['message'] = 'Item dihapus dari keranjang';
                $data['total']['subtotal'] = 'Rp ' . format_rupiah($total_price);
                $data['total']['total_item'] = $total_item;
                $data['total']['ongkir'] = ($ongkir > 0) ? 'Rp ' . format_rupiah($ongkir) : '-';
                $data['total']['total'] = 'Rp ' . format_rupiah($total_price + $ongkir);

                $response = $data;
                break;

            case 'update_item':

                $updateItem = array(
                    'rowid' => $this->input->post('rowid'),
                    'qty'   => $this->input->post('qty')
                );

                $detail_item = $this->cart->get_item($this->input->post('rowid'));
                if ($this->cart->update($updateItem)) {
                    $data['error'] = 0;
                    $total_price = $this->cart->total();
                    // $ongkir = (int) ($total_price >= get_settings('min_shop_to_free_shipping_cost')) ? 0 : get_settings('shipping_cost');
                    $ongkir = 0;
                    $data['code'] = 204;
                    $data['message'] = 'Item qty updated';
                    $data['item']['subtotal'] =  'Rp ' . format_rupiah($this->input->post('qty') * $detail_item['price']);
                    $data['item']['total_weight'] =  $this->input->post('qty') * $detail_item['product_weight'];
                    $data['total']['subtotal'] = 'Rp ' . format_rupiah($total_price);
                    $data['total']['ongkir'] = ($ongkir > 0) ? 'Rp ' . format_rupiah($ongkir) : '-';
                    $data['total']['total'] = 'Rp ' . format_rupiah($total_price + $ongkir);
                } else {
                    $data['error'] = 1;
                }
                $response = $data;
                break;
        }

        $response = json_encode($response);
        $this->output->set_content_type('application/json')
            ->set_output($response);
    }

    public function _create_order_number($quantity, $user_id, $coupon_id)
    {
        $this->load->helper('string');

        $alpha = strtoupper(random_string('alpha', 3));
        $num = random_string('numeric', 3);
        $count_qty = count($quantity);


        $number = $alpha . date('j') . date('n') . date('y') . $count_qty . $user_id . $coupon_id . $num;
        //Random 3 letter . Date . Month . Year . Quantity . User ID . Coupon Used . Numeric

        return $number;
    }
}
