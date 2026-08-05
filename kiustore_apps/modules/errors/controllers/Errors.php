<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Errors extends CI_Controller
{
    private function wants_json()
    {
        $accept = (string) $this->input->server('HTTP_ACCEPT');
        $format = strtolower((string) $this->input->get('format', TRUE));

        if ($format === 'json') {
            return TRUE;
        }

        if (strpos($accept, 'application/json') !== FALSE) {
            return TRUE;
        }

        return $this->input->is_ajax_request();
    }

    private function render_error($status_code, $payload)
    {
        set_status_header($status_code);

        if ($this->wants_json()) {
            $response = array(
                'code' => $status_code,
                'status' => $payload['status'],
                'title' => $payload['title'],
                'message' => $payload['message'],
                'action_text' => $payload['action_text'],
                'action_url' => $payload['action_url']
            );

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }

        $this->load->view('errors/error_page', $payload);
    }

    public function timeout()
    {
        return $this->no_internet();
    }

    public function no_internet()
    {
        $payload = array(
            'status' => 408,
            'title' => 'No Internet Access',
            'heading' => 'Koneksi internet terputus',
            'message' => 'Permintaan melebihi batas waktu atau jaringan sedang tidak tersedia. Periksa koneksi Anda lalu coba lagi.',
            'action_text' => 'Coba Lagi',
            'action_url' => site_url('home')
        );

        return $this->render_error(408, $payload);
    }

    public function app()
    {
        $payload = array(
            'status' => 500,
            'title' => 'Terjadi Kesalahan',
            'heading' => 'Aplikasi sedang mengalami kendala',
            'message' => 'Terjadi error saat memproses permintaan Anda. Silakan kembali ke halaman utama atau muat ulang aplikasi.',
            'action_text' => 'Kembali ke Beranda',
            'action_url' => site_url('home')
        );

        return $this->render_error(500, $payload);
    }

    public function not_found()
    {
        $payload = array(
            'status' => 404,
            'title' => 'Halaman Tidak Ditemukan',
            'heading' => 'Halaman tidak ditemukan',
            'message' => 'URL yang Anda buka tidak tersedia atau sudah dipindahkan.',
            'action_text' => 'Buka Beranda',
            'action_url' => site_url('home')
        );

        return $this->render_error(404, $payload);
    }
}
