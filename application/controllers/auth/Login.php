<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        verify_login();
        $this->load->library(['form_validation', 'encryption']);
        $this->load->model('auth/Login_model', 'login');
    }

    public function index()
    {
        $params['flash_message'] = $this->session->flashdata('login_flash');
        $params['old_email'] = $this->session->flashdata('old_email');

        $params['redirection'] = $this->input->get('redir_to');
        $this->session->set_userdata('redirection', $params['redirection']);

        $this->load->view('auth/login', $params);
    }

    public function do_login()
    {
        $this->form_validation->set_error_delimiters('<div class="text-error">', '</div>');

        $this->form_validation->set_rules('email', 'Email', 'required', [
            'required' => 'Silahkan masukkan Email untuk login'
        ]);
        // $this->form_validation->set_rules('email', 'Email', 'required|min_length[4]|max_length[16]', [
        //     'min_length' => 'Username minimal 4 karakter',
        //     'max_length' => 'Username maksimal 16 karakter',
        //     'required' => 'Silahkan masukkan Username untuk login'
        // ]);
        $this->form_validation->set_rules('password', 'Password', 'required', [
            'required' => 'Silahkan masukkan Password akun'
        ]);

        if ($this->form_validation->run() === FALSE) {
            $this->index();
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $remember_me = $this->input->post('remember_me');

            $this->login->login($email, $password);

            if ($this->login->is_user_exist()) {
                if (!$this->login->is_user_active()) {
                    $this->session->set_flashdata('login_flash', 'User dengan email <b>' . $email . '</b> tidak aktif / belum diverifikasi admin');
                    redirect('/auth/login');
                }

                $user_password = $this->login->get_password();

                if (password_verify($password, $user_password)) {
                    $role = $this->login->get_role();
                    if ($role == 'customer') {
                        $login_data = [
                            'is_login' => TRUE,
                            'is_customer' => TRUE,
                            'user_id' => $this->login->logged_user_id(),
                            'salesman_id' => $this->login->logged_salesman_id(),
                            'user_level' => $this->login->logged_user_level(),
                            'login_at' => time(),
                            'remember_me' => ($remember_me == 1) ? TRUE : FALSE
                        ];
                        $login_data_set = array(
                            'user_id' => $this->login->logged_user_id(),
                            'salesman_id' => $this->login->logged_salesman_id(),
                            'user_level' => $this->login->logged_user_level(),
                        );
                        $this->session->set_userdata($login_data_set);
                    } else {
                        $login_data = [
                            'user_id' => $this->login->logged_user_id(),
                            'is_login' => TRUE,
                            'is_admin' => TRUE,
                            'login_at' => time(),
                            'remember_me' => ($remember_me == 1) ? TRUE : FALSE
                        ];
                    }

                    $login_data_set     = json_encode($login_data_set);
                    $login_data         = json_encode($login_data);
                    $login_session      = $this->encryption->encrypt($login_data);
                    $login_data_set     = $this->encryption->encrypt($login_data_set);
                    $redirection        = $this->session->userdata('redirection');

                    if ($redirection) {
                        $redir_to = base64_decode($redirection);

                        $this->session->unset_userdata('redirection');
                    } else {
                        $redir_to = ($role == 'admin' || $role == 'salesman' || $role == 'distribusi' || $role == 'adminonline' || $role == 'keuangan' || $role == 'kadep') ? 'dashboard_admin' : 'home';

                        // print_r($redir_to);
                        // exit;
                    }

                    if ($remember_me == 1) {
                        $this->input->set_cookie('__ACTIVE_SESSION_DATA', $login_session, 604800); //48 jam
                        $this->input->set_userdata('__ACTIVE_SESSION_DATA', $login_data_set); //48 jam
                    } else {
                        $this->session->set_userdata('__ACTIVE_SESSION_DATA', $login_session, $login_data_set);
                    }

                    redirect($redir_to);
                } else {
                    $this->session->set_flashdata('login_flash', 'Password salah!');
                    $this->session->set_flashdata('old_email', $email);

                    redirect('/auth/login');
                }
            } else {
                $this->session->set_flashdata('login_flash', 'User dengan email <b>' . $email . '</b> tidak terdaftar');

                redirect('/auth/login');
            }
        }
    }
}
