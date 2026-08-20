<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mobile extends CI_Controller
{
    private $payload = array();
    private $user = null;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('api/Mobile_api_model', 'mobile_api');
        $this->payload = $this->read_payload();

        $this->output
            ->set_content_type('application/json')
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept')
            ->set_header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

        if (strtoupper($this->input->method(TRUE)) === 'OPTIONS') {
            $this->output->set_status_header(204)->set_output('');
            $this->output->_display();
            exit;
        }
    }

    public function index()
    {
        return $this->respond(array(
            'name' => 'KIU Store Mobile API',
            'version' => 'v1',
            'database_ready' => $this->mobile_api->mobile_tables_ready()
        ));
    }

    public function register()
    {
        if (!$this->require_method('POST') || !$this->require_mobile_tables()) {
            return;
        }

        if (!$this->require_fields(array('name', 'email', 'password', 'phone_number', 'address'))) {
            return;
        }

        $email = strtolower(trim((string) $this->value('email')));
        $password = (string) $this->value('password');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->error('Format email tidak valid.', 422);
        }

        if (strlen($password) < 6) {
            return $this->error('Password minimal 6 karakter.', 422);
        }

        if ($this->mobile_api->email_exists($email)) {
            return $this->error('Email sudah terdaftar.', 409);
        }

        $user_id = $this->mobile_api->register_customer(array(
            'name' => trim((string) $this->value('name')),
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'phone_number' => trim((string) $this->value('phone_number')),
            'address' => trim((string) $this->value('address')),
            'shop_name' => trim((string) $this->value('shop_name', '')),
            'shop_address' => trim((string) $this->value('shop_address', '')),
            'province_id' => (int) $this->value('province_id', 0),
            'kota_id' => (int) $this->value('kota_id', 0),
            'subdistrict_id' => (int) $this->value('subdistrict_id', 0),
            'alamat_kirim' => trim((string) $this->value('alamat_kirim', ''))
        ));

        if (!$user_id) {
            return $this->error('Pendaftaran gagal diproses.', 500);
        }

        $token = $this->mobile_api->issue_token($user_id, $this->value('device_name', 'mobile'));

        return $this->respond(array(
            'token' => $token['plain_token'],
            'token_type' => 'Bearer',
            'expires_at' => $token['expires_at'],
            'user' => $this->mobile_api->profile($user_id)
        ), 201, 'Pendaftaran berhasil.');
    }

    public function login()
    {
        if (!$this->require_method('POST') || !$this->require_mobile_tables()) {
            return;
        }

        if (!$this->require_fields(array('email', 'password'))) {
            return;
        }

        $user = $this->mobile_api->find_customer_account(strtolower(trim((string) $this->value('email'))));
        if (!$user || !password_verify((string) $this->value('password'), $user->password)) {
            return $this->error('Email atau password salah.', 401);
        }

        if ((int) $user->status !== 1) {
            return $this->error('Akun belum aktif.', 403);
        }

        $token = $this->mobile_api->issue_token($user->id, $this->value('device_name', 'mobile'));

        return $this->respond(array(
            'token' => $token['plain_token'],
            'token_type' => 'Bearer',
            'expires_at' => $token['expires_at'],
            'user' => $this->mobile_api->profile($user->id)
        ), 200, 'Login berhasil.');
    }

    public function logout()
    {
        if (!$this->require_method('POST') || !$this->authenticate()) {
            return;
        }

        $this->mobile_api->revoke_token($this->bearer_token());
        return $this->respond(null, 200, 'Logout berhasil.');
    }

    public function account()
    {
        if (!$this->require_method('DELETE') || !$this->authenticate()) {
            return;
        }

        if (!$this->mobile_api->delete_account($this->user->id)) {
            return $this->error('Akun gagal dihapus. Silakan coba lagi.', 500);
        }

        return $this->respond(array('deleted' => TRUE), 200, 'Akun berhasil dihapus.');
    }

    public function profile()
    {
        if (!$this->authenticate()) {
            return;
        }

        $method = strtoupper($this->input->method(TRUE));
        if ($method === 'GET') {
            return $this->respond($this->mobile_api->profile($this->user->id));
        }

        if (!in_array($method, array('PUT', 'PATCH'), TRUE)) {
            return $this->method_not_allowed(array('GET', 'PUT', 'PATCH'));
        }

        $fields = array(
            'name', 'phone_number', 'address', 'shop_name', 'shop_address',
            'province_id', 'kota_id', 'subdistrict_id', 'alamat_kirim',
            'nik', 'npwp'
        );
        $data = array();

        foreach ($fields as $field) {
            if ($this->has_value($field)) {
                $data[$field] = $this->value($field);
            }
        }

        if (empty($data)) {
            return $this->error('Tidak ada data profil yang dikirim.', 422);
        }

        $this->mobile_api->update_profile($this->user->id, $data);
        return $this->respond($this->mobile_api->profile($this->user->id), 200, 'Profil berhasil diperbarui.');
    }

    public function categories()
    {
        if (!$this->require_method('GET')) {
            return;
        }

        return $this->respond($this->mobile_api->categories());
    }

    public function banners()
    {
        if (!$this->require_method('GET')) {
            return;
        }

        return $this->respond($this->mobile_api->banners());
    }

    public function products()
    {
        if (!$this->require_method('GET')) {
            return;
        }

        $level = $this->optional_user_level();
        $page = max(1, (int) $this->input->get('page'));
        $per_page = min(100, max(1, (int) ($this->input->get('per_page') ?: 20)));
        $filters = array(
            'search' => trim((string) $this->input->get('search', TRUE)),
            'category_id' => (int) $this->input->get('category_id'),
            'promo' => $this->input->get('promo')
        );

        $result = $this->mobile_api->products($level, $filters, $page, $per_page);

        return $this->respond($result['items'], 200, 'OK', array(
            'page' => $page,
            'per_page' => $per_page,
            'total' => $result['total'],
            'total_pages' => (int) ceil($result['total'] / $per_page)
        ));
    }

    public function product($id)
    {
        if (!$this->require_method('GET')) {
            return;
        }

        $product = $this->mobile_api->product((int) $id, $this->optional_user_level());
        if (!$product) {
            return $this->error('Produk tidak ditemukan.', 404);
        }

        return $this->respond($product);
    }

    public function cart()
    {
        if (!$this->authenticate()) {
            return;
        }

        $method = strtoupper($this->input->method(TRUE));
        if ($method === 'GET') {
            return $this->respond($this->mobile_api->cart($this->user->id, $this->user->level));
        }

        if ($method !== 'POST') {
            return $this->method_not_allowed(array('GET', 'POST'));
        }

        if (!$this->require_fields(array('product_id', 'quantity'))) {
            return;
        }

        $product_id = (int) $this->value('product_id');
        $quantity = (int) $this->value('quantity');
        $unit_type = (int) $this->value('unit_type', 1);

        $active_order = $this->mobile_api->active_transaction_order($this->user->id);
        if ($active_order) {
            return $this->error(
                'Masih ada transaksi berjalan. Lanjutkan dari menu Riwayat, atau batalkan/selesaikan transaksi tersebut sebelum menambahkan item baru.',
                409,
                array('active_order' => $active_order)
            );
        }

        if ($quantity < 1 || !in_array($unit_type, array(1, 2), TRUE)) {
            return $this->error('Quantity atau unit_type tidak valid.', 422);
        }

        $check = $this->mobile_api->validate_cart_quantity($product_id, $quantity, $unit_type);
        if (!$check['valid']) {
            return $this->error($check['message'], 422);
        }

        $this->mobile_api->add_cart_item($this->user->id, $product_id, $quantity, $unit_type);
        return $this->respond($this->mobile_api->cart($this->user->id, $this->user->level), 201, 'Produk ditambahkan ke keranjang.');
    }

    public function cart_item($id)
    {
        if (!$this->authenticate()) {
            return;
        }

        $item = $this->mobile_api->cart_item((int) $id, $this->user->id);
        if (!$item) {
            return $this->error('Item keranjang tidak ditemukan.', 404);
        }

        $method = strtoupper($this->input->method(TRUE));
        if ($method === 'DELETE') {
            $this->mobile_api->delete_cart_item($item->id, $this->user->id);
            return $this->respond($this->mobile_api->cart($this->user->id, $this->user->level), 200, 'Item dihapus.');
        }

        if (!in_array($method, array('PUT', 'PATCH'), TRUE)) {
            return $this->method_not_allowed(array('PUT', 'PATCH', 'DELETE'));
        }

        $quantity = (int) $this->value('quantity');
        if ($quantity < 1) {
            return $this->error('Quantity minimal 1.', 422);
        }

        $check = $this->mobile_api->validate_cart_quantity($item->product_id, $quantity, $item->unit_type);
        if (!$check['valid']) {
            return $this->error($check['message'], 422);
        }

        $this->mobile_api->update_cart_item($item->id, $this->user->id, $quantity);
        return $this->respond($this->mobile_api->cart($this->user->id, $this->user->level), 200, 'Keranjang diperbarui.');
    }

    public function shipping_provinces()
    {
        if (!$this->require_method('GET')) {
            return;
        }

        return $this->shipping_response($this->mobile_api->shipping_request('destination/province'));
    }

    public function shipping_cities()
    {
        if (!$this->require_method('GET')) {
            return;
        }

        $province_id = (int) $this->input->get('province_id');
        if ($province_id < 1) {
            return $this->error('province_id wajib diisi.', 422);
        }

        return $this->shipping_response($this->mobile_api->shipping_request('destination/city/' . $province_id));
    }

    public function shipping_districts()
    {
        if (!$this->require_method('GET')) {
            return;
        }

        $city_id = (int) $this->input->get('city_id');
        if ($city_id < 1) {
            return $this->error('city_id wajib diisi.', 422);
        }

        return $this->shipping_response($this->mobile_api->shipping_request('destination/district/' . $city_id));
    }

    public function shipping_quotes()
    {
        if (!$this->require_method('POST') || !$this->authenticate()) {
            return;
        }

        if (!$this->require_fields(array('destination_id', 'courier'))) {
            return;
        }

        $cart = $this->mobile_api->cart($this->user->id, $this->user->level);
        if (empty($cart['items'])) {
            return $this->error('Keranjang masih kosong.', 422);
        }

        $result = $this->mobile_api->shipping_quote(
            $this->user->id,
            (int) $this->value('destination_id'),
            trim((string) $this->value('courier')),
            (int) $cart['summary']['total_weight']
        );

        return $this->shipping_response($result, 201);
    }

    public function payment_methods()
    {
        if (!$this->require_method('GET')) {
            return;
        }

        return $this->respond($this->mobile_api->payment_methods());
    }

    public function payment_banks()
    {
        if (!$this->require_method('GET')) {
            return;
        }

        return $this->respond($this->mobile_api->payment_banks());
    }

    public function orders()
    {
        if (!$this->require_method('GET') || !$this->authenticate()) {
            return;
        }

        $page = max(1, (int) $this->input->get('page'));
        $per_page = min(100, max(1, (int) ($this->input->get('per_page') ?: 20)));
        $result = $this->mobile_api->orders($this->user->id, $page, $per_page);

        return $this->respond($result['items'], 200, 'OK', array(
            'page' => $page,
            'per_page' => $per_page,
            'total' => $result['total'],
            'total_pages' => (int) ceil($result['total'] / $per_page)
        ));
    }

    public function order($id)
    {
        if (!$this->require_method('GET') || !$this->authenticate()) {
            return;
        }

        $order = $this->mobile_api->order((int) $id, $this->user->id);
        if (!$order) {
            return $this->error('Pesanan tidak ditemukan.', 404);
        }

        return $this->respond($order);
    }

    public function checkout()
    {
        if (!$this->require_method('POST') || !$this->authenticate()) {
            return;
        }

        if (!$this->require_fields(array('shipping_quote_id', 'shipping_service'))) {
            return;
        }

        $result = $this->mobile_api->checkout($this->user->id, $this->user->level, array(
            'shipping_quote_id' => (int) $this->value('shipping_quote_id'),
            'shipping_service' => trim((string) $this->value('shipping_service')),
            'note' => trim((string) $this->value('note', ''))
        ));

        if (!$result['success']) {
            return $this->error($result['message'], $result['status']);
        }

        return $this->respond($result['data'], 201, 'Pesanan berhasil dibuat.');
    }

    public function cancel_order($id)
    {
        if (!$this->require_method('POST') || !$this->authenticate()) {
            return;
        }

        if (!$this->mobile_api->cancel_order((int) $id, $this->user->id)) {
            return $this->error('Pesanan tidak dapat dibatalkan.', 422);
        }

        return $this->respond($this->mobile_api->order((int) $id, $this->user->id), 200, 'Pesanan dibatalkan.');
    }

    public function complete_order($id)
    {
        if (!$this->require_method('POST') || !$this->authenticate()) {
            return;
        }

        if (!$this->require_fields(array('rating'))) {
            return;
        }

        $result = $this->mobile_api->complete_order((int) $id, $this->user->id, array(
            'rating' => (int) $this->value('rating'),
            'rating_description' => trim((string) $this->value('rating_description', ''))
        ));

        if (!$result['success']) {
            return $this->error($result['message'], $result['status']);
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function confirm_bank_transfer($id)
    {
        if (!$this->require_method('POST') || !$this->authenticate()) {
            return;
        }

        if (!$this->require_fields(array('source_bank', 'source_account_number', 'source_account_name', 'transfer_amount', 'transfer_to'))) {
            return;
        }

        $picture_name = $this->payment_picture_name();
        if ($picture_name === FALSE) {
            return;
        }

        $result = $this->mobile_api->confirm_bank_transfer((int) $id, $this->user->id, array(
            'source_bank' => trim((string) $this->value('source_bank')),
            'source_account_number' => trim((string) $this->value('source_account_number')),
            'source_account_name' => trim((string) $this->value('source_account_name')),
            'transfer_amount' => (float) $this->value('transfer_amount'),
            'transfer_to' => trim((string) $this->value('transfer_to')),
            'picture_name' => $picture_name
        ));

        if (!$result['success']) {
            return $this->error($result['message'], $result['status']);
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function generate_briva_payment($id)
    {
        if (!$this->require_method('POST') || !$this->authenticate()) {
            return;
        }

        try {
            if (!is_briva_payment_local()) {
                $this->load->library('Brivaws');
            }
            $result = $this->mobile_api->generate_briva_payment(
                (int) $id,
                $this->user->id,
                is_briva_payment_local() ? null : $this->brivaws
            );
        } catch (Exception $e) {
            log_message('error', 'Generate BRIVA mobile gagal: ' . $e->getMessage());
            return $this->error('Koneksi BRIVA belum siap. Silakan coba lagi.', 500);
        }

        if (!$result['success']) {
            return $this->error($result['message'], $result['status'], isset($result['errors']) ? $result['errors'] : null);
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function briva_payment_status($id)
    {
        if (!$this->require_method('GET') || !$this->authenticate()) {
            return;
        }

        try {
            if (!is_briva_payment_local()) {
                $this->load->library('Brivaws');
            }
            $result = $this->mobile_api->briva_payment_status(
                (int) $id,
                $this->user->id,
                is_briva_payment_local() ? null : $this->brivaws
            );
        } catch (Exception $e) {
            log_message('error', 'Cek status BRIVA mobile gagal: ' . $e->getMessage());
            return $this->error('Koneksi BRIVA belum siap. Silakan coba lagi.', 500);
        }

        if (!$result['success']) {
            return $this->error($result['message'], $result['status'], isset($result['errors']) ? $result['errors'] : null);
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function select_payment_method($id)
    {
        if (!$this->require_method('POST') || !$this->authenticate()) {
            return;
        }

        if (!$this->require_fields(array('payment_method'))) {
            return;
        }

        $result = $this->mobile_api->select_payment_method(
            (int) $id,
            $this->user->id,
            (int) $this->value('payment_method')
        );

        if (!$result['success']) {
            return $this->error($result['message'], $result['status']);
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function messages()
    {
        if (!$this->authenticate()) {
            return;
        }

        $method = strtoupper($this->input->method(TRUE));
        if ($method === 'GET') {
            return $this->respond($this->mobile_api->messages($this->user->id, (int) $this->input->get('last_id')));
        }

        if ($method !== 'POST') {
            return $this->method_not_allowed(array('GET', 'POST'));
        }

        $message = trim((string) $this->value('message'));
        if ($message === '') {
            return $this->error('Pesan tidak boleh kosong.', 422);
        }

        return $this->respond($this->mobile_api->send_message($this->user->id, $message), 201, 'Pesan berhasil dikirim.');
    }

    private function authenticate()
    {
        if (!$this->require_mobile_tables()) {
            return FALSE;
        }

        $token = $this->bearer_token();
        if (!$token) {
            $this->error('Bearer token wajib dikirim.', 401);
            return FALSE;
        }

        $this->user = $this->mobile_api->user_from_token($token);
        if (!$this->user) {
            $this->error('Token tidak valid atau kedaluwarsa.', 401);
            return FALSE;
        }

        return TRUE;
    }

    private function optional_user_level()
    {
        $token = $this->bearer_token();
        if (!$token || !$this->mobile_api->mobile_tables_ready()) {
            return 1;
        }

        $user = $this->mobile_api->user_from_token($token, FALSE);
        return $user ? max(1, min(3, (int) $user->level)) : 1;
    }

    private function bearer_token()
    {
        $header = $this->input->get_request_header('Authorization', TRUE);
        if (!$header && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $header = $_SERVER['HTTP_AUTHORIZATION'];
        }

        if ($header && preg_match('/Bearer\s+(\S+)/i', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function read_payload()
    {
        $content_type = (string) $this->input->get_request_header('Content-Type', TRUE);
        if (stripos($content_type, 'application/json') !== FALSE) {
            $json = json_decode($this->input->raw_input_stream, TRUE);
            return is_array($json) ? $json : array();
        }

        $post = $this->input->post(NULL, TRUE);
        return is_array($post) ? $post : array();
    }

    private function value($key, $default = null)
    {
        return array_key_exists($key, $this->payload) ? $this->payload[$key] : $default;
    }

    private function has_value($key)
    {
        return array_key_exists($key, $this->payload);
    }

    private function require_fields($fields)
    {
        $missing = array();
        foreach ($fields as $field) {
            if (!$this->has_value($field) || $this->value($field) === '' || $this->value($field) === null) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            $this->error('Field wajib belum lengkap.', 422, array('missing_fields' => $missing));
            return FALSE;
        }

        return TRUE;
    }

    private function require_method($method)
    {
        if (strtoupper($this->input->method(TRUE)) !== strtoupper($method)) {
            $this->method_not_allowed(array($method));
            return FALSE;
        }

        return TRUE;
    }

    private function require_mobile_tables()
    {
        if (!$this->mobile_api->mobile_tables_ready()) {
            $this->error('Database API mobile belum dimigrasikan. Jalankan db/migrations/20260629_mobile_api.sql.', 503);
            return FALSE;
        }

        return TRUE;
    }

    private function shipping_response($result, $status = 200)
    {
        if (!$result['success']) {
            return $this->error($result['message'], 502, $result['data']);
        }

        return $this->respond($result['data'], $status);
    }

    private function payment_picture_name()
    {
        if (!empty($_FILES['picture']['name'])) {
            $config['upload_path'] = './assets/uploads/payments/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 5096;
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('picture')) {
                $this->error(strip_tags($this->upload->display_errors('', '')), 422);
                return FALSE;
            }

            $upload_data = $this->upload->data();
            return $upload_data['file_name'];
        }

        $base64 = trim((string) $this->value('picture_base64', ''));
        if ($base64 === '') {
            return '';
        }

        $mime = strtolower(trim((string) $this->value('picture_mime', '')));
        if (preg_match('/^data:(image\/(png|jpe?g));base64,(.+)$/i', $base64, $matches)) {
            $mime = strtolower($matches[1]);
            $base64 = $matches[3];
        }

        $extensions = array(
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png'
        );

        if (!isset($extensions[$mime])) {
            $this->error('Format bukti pembayaran harus jpg, jpeg, atau png.', 422);
            return FALSE;
        }

        $binary = base64_decode($base64, TRUE);
        if ($binary === FALSE) {
            $this->error('Bukti pembayaran base64 tidak valid.', 422);
            return FALSE;
        }

        if (strlen($binary) > 5096 * 1024) {
            $this->error('Ukuran bukti pembayaran maksimal 5MB.', 422);
            return FALSE;
        }

        $file_name = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
        $upload_dir = FCPATH . 'assets/uploads/payments/';
        if (!is_dir($upload_dir) && !@mkdir($upload_dir, 0775, TRUE)) {
            $this->error('Folder bukti pembayaran belum siap.', 500);
            return FALSE;
        }

        if (!is_writable($upload_dir)) {
            $this->error('Folder bukti pembayaran tidak dapat ditulis.', 500);
            return FALSE;
        }

        $path = $upload_dir . $file_name;
        if (@file_put_contents($path, $binary) === FALSE) {
            $this->error('Bukti pembayaran gagal disimpan.', 500);
            return FALSE;
        }

        return $file_name;
    }

    private function method_not_allowed($allowed)
    {
        $this->output->set_header('Allow: ' . implode(', ', $allowed));
        return $this->error('HTTP method tidak didukung.', 405);
    }

    private function error($message, $status, $errors = null)
    {
        return $this->respond(null, $status, $message, null, $errors);
    }

    private function respond($data, $status = 200, $message = 'OK', $meta = null, $errors = null)
    {
        $response = array(
            'success' => ($status >= 200 && $status < 300),
            'message' => $message,
            'data' => $data
        );

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return $this->output
            ->set_status_header($status)
            ->set_output(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
