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
            'customer_model' => 'customer'
        ));
    }

    public function cart()
    {
        $cart['carts'] = $this->cart->contents();
        $cart['total_cart'] = $this->cart->total();

        // print_r($cart);
        // exit;
        //nonaktifkan ongkir otomatis
        // $ongkir = ($cart['total_cart'] >= get_settings('min_shop_to_free_shipping_cost')) ? 0 : get_settings('shipping_cost');
        $ongkir = 0;
        $cart['total_price'] = $cart['total_cart'] + $ongkir;

        $this->load->view('header');
        $this->load->view('shop/cart', $cart);
        $this->load->view('footer');
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
                $items_multi = [];
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

                    $total_price_multi[$item['product_type']] +=  $item['price'];
                }

                $subtotal = $this->cart->total();
                $ongkir = (int) ($subtotal >= get_settings('min_shop_to_free_shipping_cost')) ? 0 : get_settings('shipping_cost');

                $params['customer'] = $this->customer->data();
                $params['subtotal'] = $subtotal;
                $params['ongkir'] = ($ongkir > 0) ? 'Rp' . format_rupiah($ongkir) : 'Gratis';
                $params['total'] = $subtotal + $ongkir - $discount;
                $params['discount'] = $disc;

                // print_r('<pre>');
                // print_r($items);
                // print_r($items_multi);
                // print_r($total_price_multi);
                // print_r('</pre>');
                // exit;

                $this->session->set_userdata('order_quantity', $items);
                $this->session->set_userdata('order_quantity_multi', $items_multi);
                $this->session->set_userdata('total_price', $params['total']);
                $this->session->set_userdata('total_price_multi', $total_price_multi);


                $this->load->view('header');
                $this->load->view('shop/checkout', $params);
                $this->load->view('footer');
                break;
            case 'order':
                $quantity = $this->session->userdata('order_quantity');

                $user_id = get_current_user_id();
                $coupon_id = $this->session->userdata('coupon_id');
                $order_date = date('Y-m-d H:i:s');
                $due_date = date('Y-m-d');
                $payment = $this->input->post('payment');
                $shipping = $this->input->post('shipping');

                $name = $this->input->post('name');
                $phone_number = $this->input->post('phone_number');
                $address = $this->input->post('address');
                $shop_name = $this->input->post('shop_name');
                $shop_address = $this->input->post('shop_address');
                $note = $this->input->post('note');

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

                    foreach ($total_price_multi as $type => $total) {
                        if ($total) {
                            $order_number = $this->_create_order_number($quantity_multi[$type], $user_id, $coupon_id);
                            $due_date = ($type == 1 ? date('Y-m-d') : ($type == 2 ? date('Y-m-d', strtotime(' + 1 months')) : ($type == 3 ? date('Y-m-d', strtotime(' + 2 months')) : "")));

                            $order = array(
                                'user_id' => $user_id,
                                'coupon_id' => $coupon_id,
                                'order_number' => $order_number,
                                'order_status' => 1,
                                'order_date' => $order_date,
                                'total_price' => $total,
                                'total_items' => count($quantity_multi[$type]),
                                'payment_method' => ($type == 1 ? 2 : 1),
                                'shipping_method' => $shipping,
                                'delivery_data' => $delivery_data,
                                'due_date' => $due_date
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
                    //  $this->product->add_credit_limit($total_price);

                } else { // untuk metode pembayaran cash order langsung dijadikan 1
                    $total_price = $this->session->userdata('total_price');
                    $order_number = $this->_create_order_number($quantity, $user_id, $coupon_id);
                    $order = array(
                        'user_id' => $user_id,
                        'coupon_id' => $coupon_id,
                        'order_number' => $order_number,
                        'order_status' => 1,
                        'order_date' => $order_date,
                        'total_price' => $total_price,
                        'total_items' => count($quantity),
                        'payment_method' => $payment,
                        'shipping_method' => $shipping,
                        'delivery_data' => $delivery_data,
                        'due_date' => $due_date
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

                    $this->product->create_order_items($items);
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
        $action = $this->input->get('action');

        switch ($action) {
            case 'add_item':
                $id = $this->input->post('id');
                $qty = $this->input->post('qty');
                $satuan = $this->input->post('satuan');
                $satuan_text = $this->input->post('satuan_text');
                $satuan_qty = $this->input->post('satuan_qty');
                $sku = $this->input->post('sku');
                $name = $this->input->post('name');
                $product_type = $this->input->post('product_type');

                if ($satuan == 1) {
                    $price = $this->input->post('price');
                    $qty_pcs = $qty;
                } else {
                    $price = $this->input->post('price') * $this->input->post('satuan_qty');
                    $qty_pcs = $qty * $satuan_qty;
                }

                $total_price_item = $qty * $price;
                $total_price_in_cart = $this->cart->total();
                $total_price = $total_price_item + $total_price_in_cart;

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
                        'product_type' => $product_type
                    );
                    $this->cart->insert($item);
                    $total_item = count($this->cart->contents());

                    // if ($_SESSION['user_level'] != 1) {
                    //     $max = get_user_limit_transaction();
                    // } else {
                    //     $max = 0;
                    // }

                    $response = array('code' => 200, 'message' => 'Item dimasukkan dalam keranjang', 'total_item' => $total_item);
                } else {
                    $response = array('code' => 202, 'message' => 'Gagal memasukkan dalam keranjang. Stok barang hanya ' . $satuan_qty . ' ' . $qty);
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
