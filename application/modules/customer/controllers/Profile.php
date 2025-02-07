<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('customer');

        $this->load->model(array(
            'profile_model' => 'profile'
        ));
        $this->load->library('form_validation');
    }

    public $api_key = "197f7e1329685d3ed9d1468c54efc9dd";

    public function index()
    {
        $data = $this->profile->get_profile();
        $loc = $this->profile->detail_loc();

        $params['title']    = $data->name;
        $user['user']       = $data;
        $user['user_loc']   = $loc;
        $user['flash']      = $this->session->flashdata('profile');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key:" . $this->api_key,
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $user['kota'] = array('error' => true);
        } else {
            // DATA AWAL
            $data = $this->profile->get_profile();
            $params['title'] = $data->name;
            // JSON-RAJA-ONGKIR
            $user['kota'] = json_decode($response);
        }

        $this->load->view('header', $params);
        $this->load->view('profile', $user);
        $this->load->view('footer');
    }

    public function toggle_readonly()
    {
        $readonly = $this->input->post('readonly');
        echo json_encode(["status" => "success", "readonly" => $readonly]);
    }

    public function inputlocation()
    {
        $location = $this->input->post('location');
        echo json_encode(["status" => "success", "location" => $location]);
    }

    public function get_provinces()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . $this->api_key,
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            $data = json_decode($response, true);
            $provinces = [];
            if (isset($data['rajaongkir']['results'])) {
                foreach ($data['rajaongkir']['results'] as $prov) {
                    $provinces[] = [
                        "id" => $prov['province_id'],
                        "text" => $prov['province']
                    ];
                }
            }
            echo json_encode($provinces);
        } else {
            echo json_encode([]);
        }
    }


    public function change_alamat_asal()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key:" . $this->api_key,
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $user['kota'] = array('error' => true);
        } else {
            // DATA AWAL
            $data = $this->profile->get_profile();
            $params['title'] = $data->name;
            // JSON-RAJA-ONGKIR
            $user['kota'] = json_decode($response);
        }

        $this->load->view('header', $params);
        $this->load->view('profile_alamat', $user);
        $this->load->view('footer');
    }

    public function change_password()
    {
        $data = $this->profile->get_profile();

        $params['title'] = $data->name;
        $user['user'] = $data;
        $user['flash'] = $this->session->flashdata('profile');

        $this->load->view('header', $params);
        $this->load->view('change_password', $user);
        $this->load->view('footer');
    }

    public function edit_name()
    {
        $this->form_validation->set_rules('name', 'Nama lengkap', 'required|max_length[32]|min_length[4]');

        if ($this->form_validation->run() === FALSE) {
            $this->index();
        } else {
            $data = new stdClass();

            $data->name = $this->input->post('name');
            $data->phone_number = $this->input->post('phone_number');
            $data->address = $this->input->post('address');
            $data->shop_name = $this->input->post('shop_name');
            $data->shop_address = $this->input->post('shop_address');

            $profile = $this->profile->get_profile();
            $old_profile = $profile->profile_picture;

            if (isset($_FILES) && @$_FILES['file']['error'] == '0') {
                $config['upload_path'] = './assets/uploads/users/';
                $config['allowed_types'] = 'jpg|png';
                $config['max_size'] = 2048;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('file')) {
                    if ($old_profile) {
                        unlink('./assets/uploads/users/' . $old_profile);
                    }

                    $file_data = $this->upload->data();
                    $data->profile_picture = $file_data['file_name'];
                } else {
                    $errors = $this->upload->display_errors();
                    $errors .= '<p>';
                    $errors .= anchor('profile', '&laquo; Kembali');
                    $errors .= '</p>';

                    show_error($errors);
                }
            }

            $flash_message = ($this->profile->update($data)) ? 'Profil berhasil diperbarui!' : 'Terjadi kesalahan';

            $this->session->set_flashdata('profile', $flash_message);
            redirect('customer/profile');
        }
    }

    public function edit_account()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|max_length[16]|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'min_length[4]');

        if ($this->form_validation->run() === FALSE) {
            $this->index();
        } else {
            $data = new stdClass();
            $profile = $this->profile->get_profile();

            $get_password = $this->input->post('password');

            if (empty($get_password)) {
                $password = $profile->password;
            } else {
                $password = password_hash($get_password, PASSWORD_BCRYPT);
            }

            $data->username = $this->input->post('username');
            $data->password = $password;

            $flash_message = ($this->profile->update_account($data)) ? 'Akun berhasil diperbarui' : 'Terjadi kesalahan';

            $this->session->set_flashdata('profile', $flash_message);

            redirect('customer/profile/change_password');
        }
    }

    public function edit_email()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[32]|min_length[10]');

        if ($this->form_validation->run() === FALSE) {
            $this->index();
        } else {
            $data = new stdClass();

            $data->email = $this->input->post('email');

            $flash_message = ($this->profile->update_account($data)) ? 'Email berhasil diperbarui' : 'Terjadi kesalahan';

            $this->session->set_flashdata('profile', $flash_message);
            $this->session->set_flashdata('show_tab', 'email');

            redirect('customer/profile');
        }
    }
}
