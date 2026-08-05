<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rating extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        $this->load->model(array(
            'order_model' => 'order'
        ));
    }

    public function index()
    {
        $params['title'] = 'Kelola Order';

        $this->load->view('header', $params);
        $this->load->view('orders/orders_rating_form');
        $this->load->view('footer');
    }

    public function tabel($bulan, $tahun)
  	{
  		$dt['data'] 		= $this->order->tabel($bulan, $tahun);
  		$dt['bulan']		= $bulan;
  		$dt['tahun']		= $tahun;

  		$this->load->view('orders/orders_rating_table', $dt);
  	}

    public function view($id = 0)
    {
        if ($this->order->is_order_exist($id)) {
            $data = $this->order->order_data($id);
            $items = $this->order->order_items($id);
            $banks = json_decode(get_settings('payment_banks'));
            $banks = (array) $banks;

            $params['title'] = 'Order #' . $data->order_number;

            $order['data'] = $data;
            $order['order_id'] = $id;
            $order['items'] = $items;
            $order['delivery_data'] = json_decode($data->delivery_data);
            $order['banks'] = $banks;
            $order['order_flash'] = $this->session->flashdata('order_flash');
            $order['payment_flash'] = $this->session->flashdata('payment_flash');

            $this->load->view('header', $params);
            $this->load->view('orders/view', $order);
            $this->load->view('footer');
        } else {
            show_404();
        }
    }

    public function api($action = '')
    {
        switch ($action) {
            case 'order_success_kadep':
                $orders = $this->order->get_order_success_kadep();
                $n = 0;
                foreach ($orders as $order) {
                    $orders[$n]->finish_date = get_formatted_date($order->finish_date);

                    if ($orders[$n]->rating == 1) {
                        $orders[$n]->rating =
                            '<span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>';
                    } elseif ($orders[$n]->rating == 2) {
                        $orders[$n]->rating =
                            '<span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>';
                    } elseif ($orders[$n]->rating == 3) {
                        $orders[$n]->rating =
                            '<span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>';
                    } elseif ($orders[$n]->rating == 4) {
                        $orders[$n]->rating =
                            '<span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star"></span>';
                    } elseif ($orders[$n]->rating == 5) {
                        $orders[$n]->rating =
                            '<span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star star-checked"></span>
                            <span class="fa fa-star star-checked"></span>';
                    }

                    $n++;
                }
                $orders['data'] = $orders;
                $response = $orders;
                break;

                case 'order_average_sales':
                    $orders = $this->order->get_order_average_sales();
                    $n = 0;
                    foreach ($orders as $order) {
                      //  $orders[$n]->finish_date = get_formatted_date($order->finish_date);

                        if ($orders[$n]->rating == 1) {
                            $orders[$n]->rating =
                                '<span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>';
                        } elseif ($orders[$n]->rating == 2) {
                            $orders[$n]->rating =
                                '<span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>';
                        } elseif ($orders[$n]->rating == 3) {
                            $orders[$n]->rating =
                                '<span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>';
                        } elseif ($orders[$n]->rating == 4) {
                            $orders[$n]->rating =
                                '<span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star"></span>';
                        } elseif ($orders[$n]->rating == 5) {
                            $orders[$n]->rating =
                                '<span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star star-checked"></span>
                                <span class="fa fa-star star-checked"></span>';
                        }

                        $n++;
                    }
                    $orders['data'] = $orders;
                    $response = $orders;
                    break;
        }

        $response = json_encode($response);
        $this->output->set_content_type('application/json')
            ->set_output($response);
    }

    public function get_total_order()
    {
        echo get_total_order();
        // echo $this->db->where('order_status', 1)->get('orders')->num_rows();
    }
}
