<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rajaongkir extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Rajaongkir_model');
    }

    public function index()
    {

        $user['flash']      = $this->session->flashdata('profile');

        $this->load->view('profilebk_rajaongkir', $user);
    }

    public function get_provinces()
    {
        $data = $this->Rajaongkir_model->get_provinces();
        echo json_encode($data['rajaongkir']['results']);
    }

    public function get_cities()
    {
        $province_id = $this->input->get('province_id');
        $data = $this->Rajaongkir_model->get_cities($province_id);
        echo json_encode($data['rajaongkir']['results']);
    }

    public function get_subdistricts()
    {
        $city_id = $this->input->get('city_id');
        $data = $this->Rajaongkir_model->get_subdistricts($city_id);
        echo json_encode($data['rajaongkir']['results']);
    }

    public function get_shipping_cost()
    {
        $origin = $this->input->post('origin');
        $destination = $this->input->post('destination');
        $weight = $this->input->post('weight');
        $courier = $this->input->post('courier');

        $response = $this->Rajaongkir_model->get_shipping_cost($origin, $destination, $weight, $courier);
        echo json_encode($response['rajaongkir']['results']);
    }
}
