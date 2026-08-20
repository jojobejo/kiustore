<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Briva_switch extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        if (admin_role() !== 'admin') {
            show_error('Akses BRIVA SWITCH hanya untuk role admin.', 403, 'Akses Ditolak');
        }

        $this->load->library('form_validation');
    }

    public function index()
    {
        $params['title'] = 'BRIVA SWITCH';
        $data['flash'] = $this->session->flashdata('briva_switch_flash');
        $data['mode'] = briva_payment_mode();

        $this->load->view('header', $params);
        $this->load->view('briva_switch/index', $data);
        $this->load->view('footer');
    }

    public function update()
    {
        $mode = strtolower(trim((string) $this->input->post('mode')));

        if (!in_array($mode, array('local', 'production'), TRUE)) {
            $this->session->set_flashdata('briva_switch_flash', 'Mode BRIVA tidak valid.');
            redirect('admin/briva-switch');
            return;
        }

        upsert_settings('briva_payment_mode', $mode);

        $message = $mode === 'local'
            ? 'BRIVA SWITCH aktif LOCAL. Payment development tidak memanggil API BRI production.'
            : 'BRIVA SWITCH aktif PRODUCTION. Payment kembali memakai flow BRIVA existing.';

        $this->session->set_flashdata('briva_switch_flash', $message);
        redirect('admin/briva-switch');
    }
}
