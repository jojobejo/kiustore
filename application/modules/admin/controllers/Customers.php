<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        $this->load->model(array(
            'customer_model' => 'customer',
            'order_model' => 'order',
            'salesman_model' => 'salesman',
            'payment_model' => 'payment',
            'admin_model' => 'admin'
        ));
        $this->load->library('form_validation');
    }
    public $api_key = "850366532701e5e36174b032cfd311e9";

    public function index()
    {
        $params['title'] = 'Customer';

        $this->load->view('header', $params);
        $this->load->view('customers/customers');
        $this->load->view('footer');
    }

    public function add_new_customer()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key:" . $this->api_key
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $customer['kota'] = array('error' => true);
        } else {
            $customer['kota'] = json_decode($response);
        }

        $params['title'] = 'Tambah Pelanggan Baru';

        $customer['flash'] = $this->session->flashdata('add_new_customer_flash');
        //  $customer['salesman'] = $this->salesman->get_all_salesman();
        $customer['admin'] = $this->customer->get_all_sales();
        // print_r($customer);exit;
        $this->load->view('header', $params);
        $this->load->view('customers/add_new_customer', $customer);
        $this->load->view('footer');
    }

    public function add_customer()
    {
        $this->form_validation->set_error_delimiters('<div class="form-error text-danger font-weight-bold">', '</div>');

        $this->form_validation->set_rules('name', 'Nama Pelanggan', 'trim|required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('nik', 'Nomor Induk Kependudukan', 'trim|required');
        $this->form_validation->set_rules('npwp', 'NPWP', 'required|numeric');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('kota', 'kota', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // print_r(validation_errors());exit;
            $this->add_new_customer();
        } else {
            $password = $this->input->post('password');
            $name = $this->input->post('name');
            $nik = $this->input->post('nik');
            $npwp = $this->input->post('npwp');
            $kota = $this->input->post('kota');
            $address = $this->input->post('address');
            $shop_name = $this->input->post('shop_name');
            $shop_address = $this->input->post('shop_address');
            $no_telp = $this->input->post('no_telp');
            $email = $this->input->post('email');
            $level = $this->input->post('level');
            $max_credit = $this->input->post('max_credit');
            $salesman_id = $this->input->post('salesman_id');

            $password = password_hash($password, PASSWORD_BCRYPT);

            $user_data = array(
                'email' => $email,
                'password' => $password,
                'role' => 'customer',
                'register_date' => date('Y-m-d H:i:s'),
                'status' => '1'
            );

            $user = $this->customer->register_user($user_data);

            $customer_data = array(
                'user_id' => $user,
                'name' => $name,
                'nik' => $nik,
                'npwp' => $npwp,
                'phone_number' => $no_telp,
                'kota_id' => $kota,
                'address' => $address,
                'shop_name' => $shop_name,
                'shop_address' => $shop_address,
                'level' => $level,
                'max_credit' => $max_credit,
                'salesman_id' => $salesman_id,
            );

            $this->customer->register_customer($customer_data);

            $this->session->set_flashdata('add_new_customer_flash', 'Customer berhasil ditambahkan!');

            redirect('admin/customers');
        }
    }

    public function view($id = 0)
    {
        if ($this->customer->is_customer_exist($id)) {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/city",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key:" . $this->api_key
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                $customer['kota'] = array('error' => true);
            } else {
                $customer['kota'] = json_decode($response);
            }

            $data  = $this->customer->customer_data($id);
            $cusva = $this->customer->get_status_va($id);

            $customer['admin'] = $this->admin->get_all_admin();

            $params['title'] = $data->name;

            $customer['customer'] = $data;
            $customer['va']     = $cusva;
            $customer['flash'] = $this->session->flashdata('customer_flash');
            $customer['orders'] = $this->order->order_by($id);
            $customer['payments'] = $this->payment->payment_by($id);

            $this->load->view('header', $params);
            $this->load->view('customers/view', $customer);
            $this->load->view('footer');
        } else {
            show_404();
        }
    }

    public function api($action = '')
    {
        switch ($action) {
            case 'customers':
                $customers = $this->customer->get_all_customers();

                $n = 0;
                foreach ($customers as $customer) {
                    $customers[$n]->profile_picture = base_url('assets/uploads/users/' . $customer->profile_picture);

                    $n++;
                }

                $customers['data'] = $customers;

                $response = $customers;
                break;
            case 'delete':
                $id = $this->input->post('id');

                $this->customer->delete_customer($id);

                $response = array('code' => 204);
                break;
            case 'deactivate':
                $id = $this->input->post('id');

                $this->customer->deactivate_customer($id);

                $response = array('code' => 204);
                break;
            case 'activate':
                $id = $this->input->post('id');

                $this->customer->activate_customer($id);

                $response = array('code' => 204);
                break;
            case 'edit':
                $customer['user_id'] = $this->input->post('user_id');
                $customer['name'] = $this->input->post('names');
                $customer['kota'] = $this->input->post('kota');
                //    $customer['email'] = $this->input->post('email');
                $customer['phone_number'] = $this->input->post('phone_number');
                $customer['salesman_id'] = $this->input->post('salesman_id');
                $customer['address'] = $this->input->post('address');
                $customer['level'] = $this->input->post('level');
                $customer['max_credit'] = $this->input->post('max_credit');

                $this->customer->edit($customer);

                redirect('admin/customers/view/' . $customer['user_id']);
                break;
        }

        $response = json_encode($response);
        $this->output->set_content_type('application/json')
            ->set_output($response);
    }

    public function generate_va()
    {
        $idcus  = $this->input->post('idcus');
        $vacus  = $this->input->post('vacusno');

        $generate = array(
            'va_code'   => $vacus
        );

        $this->customer->updateva($idcus, $generate);
        redirect('admin/customers/view/' . $idcus);
    }
}
