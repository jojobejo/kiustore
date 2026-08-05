<?php
defined('BASEPATH') or exit('No direct script access allowed');

class addoncart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        verify_session('customer');
        $this->load->library('cart');
        $this->load->model(array(
            'product_model' => 'product',
            'customer_model' => 'customer',
            'profile_model' => 'profile'
        ));
    }

    public $api_key = "850366532701e5e36174b032cfd311e9";

    public function cart()
    {
        $data = $this->profile->get_profile();

        $cart['carts'] = $this->cart->contents();
        $cart['total_cart'] = $this->cart->total();
        $cart['user'] = $data;

        if (level_user() < 3) {
            $ongkir = $cart['ongkir'] = "0";
            $userdata = $this->session->userdata('user_id');

            $cart['total_price'] = $cart['total_cart'] + $ongkir;

            // $cart['weight'] = $this->product->getweight($userdata)->result();

            $this->load->view('header');
            $this->load->view('shop/cart', $cart);
            $this->load->view('footer');
        } else {
            $ongkir = $cart['ongkir'] = "0";

            $cart['total_price'] = $cart['total_cart'] + $ongkir;

            $this->load->view('header');
            $this->load->view('shop/cart', $cart);
            $this->load->view('footer');
        }
    }
}
