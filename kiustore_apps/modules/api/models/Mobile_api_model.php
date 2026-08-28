<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mobile_api_model extends CI_Model
{
    public function mobile_tables_ready()
    {
        return $this->db->table_exists('mobile_api_tokens')
            && $this->db->table_exists('mobile_cart_items')
            && $this->db->table_exists('mobile_shipping_quotes');
    }

    public function email_exists($email)
    {
        return $this->db->where('email', $email)->count_all_results('users') > 0;
    }

    public function register_customer($data)
    {
        $this->db->trans_begin();

        $user = array(
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'customer',
            'register_date' => date('Y-m-d H:i:s'),
            'status' => 1
        );

        if ($this->db->field_exists('name', 'users')) {
            $user['name'] = $data['name'];
        }

        $this->db->insert('users', $user);
        $user_id = (int) $this->db->insert_id();

        $customer = array(
            'user_id' => $user_id,
            'nik' => isset($data['nik']) ? (string) $data['nik'] : '',
            'npwp' => isset($data['npwp']) ? (string) $data['npwp'] : '',
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
            'province_id' => isset($data['province_id']) ? (int) $data['province_id'] : 0,
            'kota_id' => isset($data['kota_id']) ? (int) $data['kota_id'] : 0,
            'subdistrict_id' => isset($data['subdistrict_id']) ? (int) $data['subdistrict_id'] : 0,
            'address' => $data['address'],
            'shop_name' => $data['shop_name'],
            'shop_address' => (isset($data['shop_address']) && $data['shop_address'] !== '') ? $data['shop_address'] : $data['address'],
            'max_credit' => 0,
            'level' => 1,
            'profile_picture' => null,
            'salesman_id' => 79,
            'kode_customer' => '',
            'va_code' => 0
        );

        if ($this->db->field_exists('alamat_kirim', 'customers')) {
            $customer['alamat_kirim'] = (isset($data['alamat_kirim']) && $data['alamat_kirim'] !== '') ? $data['alamat_kirim'] : $data['address'];
        }

        $this->db->insert('customers', $customer);

        if ($this->db->table_exists('mobile_onboarding_flags')) {
            $this->db->insert('mobile_onboarding_flags', array(
                'user_id' => $user_id,
                'is_new_user' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ));
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->trans_commit();
        return $user_id;
    }

    public function find_customer_account($email)
    {
        return $this->db
            ->select('id, email, password, status')
            ->where(array('email' => $email, 'role' => 'customer'))
            ->get('users')
            ->row();
    }

    public function issue_token($user_id, $device_name)
    {
        $plain = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+30 days'));

        $this->db->insert('mobile_api_tokens', array(
            'user_id' => (int) $user_id,
            'token_hash' => hash('sha256', $plain),
            'device_name' => substr(trim((string) $device_name), 0, 100),
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s')
        ));

        return array(
            'plain_token' => $plain,
            'expires_at' => $expires_at
        );
    }

    public function onboarding_state($user_id, $default_is_new_user = FALSE)
    {
        $state = array(
            'is_new_user' => (bool) $default_is_new_user,
            'show_tutorial' => (bool) $default_is_new_user,
            'show_completion_splash' => (bool) $default_is_new_user,
            'completion_splash_duration_seconds' => 5,
            'redirect_after_tutorial_url' => site_url('home'),
            'home_route' => 'home'
        );

        if (!$this->db->table_exists('mobile_onboarding_flags')) {
            return $state;
        }

        $row = $this->db
            ->where('user_id', (int) $user_id)
            ->get('mobile_onboarding_flags')
            ->row();

        if (!$row) {
            return $state;
        }

        $is_new_user = ((int) $row->is_new_user === 1 && empty($row->tutorial_completed_at));

        $state['is_new_user'] = $is_new_user;
        $state['show_tutorial'] = $is_new_user;
        $state['show_completion_splash'] = $is_new_user;

        return $state;
    }

    public function complete_onboarding($user_id)
    {
        if (!$this->db->table_exists('mobile_onboarding_flags')) {
            return TRUE;
        }

        $now = date('Y-m-d H:i:s');
        $data = array(
            'is_new_user' => 0,
            'tutorial_completed_at' => $now,
            'completion_splash_shown_at' => $now,
            'updated_at' => $now
        );

        $exists = $this->db
            ->where('user_id', (int) $user_id)
            ->count_all_results('mobile_onboarding_flags') > 0;

        if ($exists) {
            return $this->db
                ->where('user_id', (int) $user_id)
                ->update('mobile_onboarding_flags', $data);
        }

        $data['user_id'] = (int) $user_id;
        $data['created_at'] = $now;

        return $this->db->insert('mobile_onboarding_flags', $data);
    }

    public function user_from_token($plain_token, $touch = TRUE)
    {
        $token_hash = hash('sha256', $plain_token);
        $token = $this->db
            ->where('token_hash', $token_hash)
            ->where('revoked_at IS NULL', null, false)
            ->get('mobile_api_tokens')
            ->row();

        if (!$token) {
            return null;
        }

        if (strtotime($token->expires_at) < time()) {
            return null;
        }

        if ($touch) {
            $this->db->where('id', $token->id)->update('mobile_api_tokens', array(
                'last_used_at' => date('Y-m-d H:i:s')
            ));
        }

        $user = $this->db
            ->select('u.id, u.email, u.status, c.name, c.level, c.salesman_id, c.subdistrict_id')
            ->from('users u')
            ->join('customers c', 'c.user_id = u.id')
            ->where('u.id', (int) $token->user_id)
            ->get()
            ->row();

        if (!$user) {
            return null;
        }

        $user->id = (int) $user->id;
        $user->status = (int) $user->status;
        $user->level = (int) $user->level;
        $user->salesman_id = (int) $user->salesman_id;
        $user->subdistrict_id = (int) $user->subdistrict_id;
        return $user;
    }

    public function revoke_token($plain_token)
    {
        $token_hash = hash('sha256', $plain_token);
        return $this->db
            ->where('token_hash', $token_hash)
            ->update('mobile_api_tokens', array('revoked_at' => date('Y-m-d H:i:s')));
    }

    public function delete_account($user_id)
    {
        $user = $this->find_user_by_id($user_id);
        if (!$user) {
            return FALSE;
        }

        $now = date('Y-m-d H:i:s');
        $deleted_email = $this->build_anonymized_email($user_id);

        $this->db->trans_begin();

        $this->insert_account_deletion_audit($user, $now);
        $this->revoke_all_user_tokens($user_id, $now);
        $this->delete_mobile_session_data($user_id);
        $this->anonymize_customer_profile($user_id);
        $this->detach_user_history($user_id);
        $this->anonymize_user_account($user_id, $deleted_email, $now);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->trans_commit();
        return TRUE;
    }

    public function profile($user_id)
    {
        $fields = array(
            'u.id', 'u.email', 'u.status', 'c.name', 'c.phone_number', 'c.address',
            'c.shop_name', 'c.shop_address', 'c.province_id', 'c.kota_id',
            'c.subdistrict_id', 'c.level', 'c.max_credit', 'c.profile_picture',
            'c.salesman_id', 'c.kode_customer'
        );

        if ($this->db->field_exists('nik', 'customers')) {
            $fields[] = 'c.nik';
        }

        if ($this->db->field_exists('npwp', 'customers')) {
            $fields[] = 'c.npwp';
        }

        if ($this->db->field_exists('alamat_kirim', 'customers')) {
            $fields[] = 'c.alamat_kirim';
        }

        $profile = $this->db
            ->select(implode(', ', $fields))
            ->from('users u')
            ->join('customers c', 'c.user_id = u.id')
            ->where('u.id', (int) $user_id)
            ->get()
            ->row_array();

        if (!$profile) {
            return null;
        }

        foreach (array('id', 'status', 'province_id', 'kota_id', 'subdistrict_id', 'level', 'max_credit', 'salesman_id') as $field) {
            $profile[$field] = isset($profile[$field]) ? (int) $profile[$field] : 0;
        }

        foreach (array('email', 'name', 'phone_number', 'address', 'shop_name', 'shop_address', 'nik', 'npwp', 'alamat_kirim', 'kode_customer') as $field) {
            $profile[$field] = isset($profile[$field]) ? (string) $profile[$field] : '';
        }

        $profile['profile_picture_url'] = $profile['profile_picture']
            ? base_url('assets/uploads/users/' . $profile['profile_picture'])
            : null;

        return $profile;
    }

    public function update_profile($user_id, $data)
    {
        $allowed = array();
        foreach ($data as $field => $value) {
            if ($this->db->field_exists($field, 'customers')) {
                if (in_array($field, array('province_id', 'kota_id', 'subdistrict_id', 'level', 'max_credit'), TRUE)) {
                    $allowed[$field] = (int) $value;
                } else {
                    $allowed[$field] = $value;
                }
            }
        }

        if (empty($allowed)) {
            return FALSE;
        }

        if (isset($allowed['name']) && $this->db->field_exists('name', 'users')) {
            $this->db->where('id', (int) $user_id)->update('users', array('name' => $allowed['name']));
        }

        $res = $this->db->where('user_id', (int) $user_id)->update('customers', $allowed);

        if ($this->db->table_exists('customer_location') && (isset($allowed['province_id']) || isset($allowed['kota_id']) || isset($allowed['subdistrict_id']))) {
            $loc_exists = $this->db->where('user_id', (string) $user_id)->count_all_results('customer_location') > 0;
            $loc_data = array(
                'provinsi' => isset($allowed['province_id']) ? (int) $allowed['province_id'] : 0,
                'kota' => isset($allowed['kota_id']) ? (int) $allowed['kota_id'] : 0,
                'sub_kota' => isset($allowed['subdistrict_id']) ? (int) $allowed['subdistrict_id'] : 0
            );
            if ($loc_exists) {
                $this->db->where('user_id', (string) $user_id)->update('customer_location', $loc_data);
            } else {
                $loc_data['user_id'] = (string) $user_id;
                $this->db->insert('customer_location', $loc_data);
            }
        }

        return $res;
    }

    public function categories()
    {
        $rows = $this->db->order_by('name', 'ASC')->get('product_category')->result_array();
        foreach ($rows as &$row) {
            $row['id'] = (int) $row['id'];
        }

        return $rows;
    }

    public function banners()
    {
        if (!$this->db->table_exists('banner_product')) {
            return $this->fallback_banners();
        }

        $rows = $this->db->query("
            SELECT
                a.id AS banner_id,
                a.product_id,
                a.banner_image,
                b.id,
                b.sku,
                b.name
            FROM banner_product a
            LEFT JOIN products b ON b.id = a.product_id
            WHERE a.banner_image IS NOT NULL
                AND a.banner_image != ''
            ORDER BY a.id DESC
        ")->result_array();

        if (empty($rows)) {
            return $this->fallback_banners();
        }

        $banners = array();
        foreach ($rows as $row) {
            $title = isset($row['name']) && $row['name'] !== '' ? $row['name'] : 'Promo Produk';
            $banners[] = array(
                'id' => (int) $row['banner_id'],
                'product_id' => isset($row['product_id']) ? (int) $row['product_id'] : null,
                'title' => $title,
                'subtitle' => 'Lihat promo terbaru',
                'image_name' => null,
                'image_url' => base_url('assets/uploads/banner_product/' . $row['banner_image']),
                'color_hex' => '#2F65D4'
            );
        }

        return $banners;
    }

    public function products($level, $filters, $page, $per_page)
    {
        $this->apply_product_filters($level, $filters);
        $total = $this->db->count_all_results();

        $this->apply_product_filters($level, $filters);
        $rows = $this->db
            ->select('v.*, pc.name AS category_name')
            ->join('product_category pc', 'pc.id = v.category_id', 'left')
            ->order_by('v.name', 'ASC')
            ->limit($per_page, ($page - 1) * $per_page)
            ->get()
            ->result_array();

        foreach ($rows as &$row) {
            $row = $this->format_product($row, $level);
        }

        return array(
            'items' => $rows,
            'total' => (int) $total
        );
    }

    public function product($id, $level)
    {
        $row = $this->db
            ->select('v.*, pc.name AS category_name')
            ->from('v_products v')
            ->join('product_category pc', 'pc.id = v.category_id', 'left')
            ->where(array('v.id' => (int) $id, 'v.is_available' => 1))
            ->like('v.level_product', (string) $level)
            ->get()
            ->row_array();

        return $row ? $this->format_product($row, $level) : null;
    }

    private function apply_product_filters($level, $filters)
    {
        $this->db->from('v_products v')
            ->where('v.is_available', 1)
            ->where('v.stock >', 0);
        $this->db->like('v.level_product', (string) $level);

        if (!empty($filters['search'])) {
            $this->db->group_start()
                ->like('v.name', $filters['search'])
                ->or_like('v.sku', $filters['search'])
                ->group_end();
        }

        if (!empty($filters['category_id'])) {
            $this->db->where('v.category_id', (int) $filters['category_id']);
        }

        if ($filters['promo'] !== null && $filters['promo'] !== '') {
            $this->db->where('v.promo', ((int) $filters['promo']) ? 1 : 0);
        }
    }

    private function format_product($row, $level)
    {
        $price_key = $level === 2 ? 'price_2' : ($level === 3 ? 'price_3' : 'price');
        $promo_key = $level === 2 ? 'promo_price_2' : ($level === 3 ? 'promo_price_3' : 'promo_price');
        $discount_key = $level === 2 ? 'discount_2' : ($level === 3 ? 'discount_3' : 'discount');
        $base_price = (float) $row[$price_key];
        $selling_price = ((int) $row['promo'] === 1) ? (float) $row[$promo_key] : $base_price;
        $unit_value = max(1, (int) $row['product_unit_value']);

        return array(
            'id' => (int) $row['id'],
            'category_id' => (int) $row['category_id'],
            'category_name' => isset($row['category_name']) ? $row['category_name'] : null,
            'sku' => $row['sku'],
            'name' => $row['name'],
            'description' => $row['description'],
            'image_url' => $this->product_image_url(isset($row['picture_name']) ? $row['picture_name'] : null),
            'stock' => (int) $row['stock'],
            'is_available' => (bool) $row['is_available'],
            'product_type' => (int) $row['product_type'],
            'weight_per_piece' => (float) $row['product_unit_weight'],
            'price' => $selling_price,
            'base_price' => $base_price,
            'promo' => (bool) $row['promo'],
            'discount_percent' => (int) $row[$discount_key],
            'units' => array(
                array(
                    'type' => 1,
                    'name' => $row['product_unit_1'],
                    'multiplier' => 1,
                    'price' => $selling_price
                ),
                array(
                    'type' => 2,
                    'name' => $row['product_unit_2'],
                    'multiplier' => $unit_value,
                    'price' => $selling_price * $unit_value
                )
            )
        );
    }

    private function product_image_url($picture_name)
    {
        $file_name = $picture_name ? $picture_name : 'default.jpg';
        $path = 'assets/uploads/products/' . $file_name;
        $version = @filemtime(FCPATH . $path);
        $url = base_url($path);

        return $version ? $url . '?v=' . $version : $url;
    }

    private function fallback_banners()
    {
        return array(
            array(
                'id' => 1,
                'product_id' => 3,
                'title' => 'Amistar Top',
                'subtitle' => 'Fungisida pilihan Karisma',
                'image_name' => 'banner_amistar',
                'image_url' => null,
                'color_hex' => '#2F65D4'
            ),
            array(
                'id' => 2,
                'product_id' => 2,
                'title' => 'NK 7328 Sumo',
                'subtitle' => 'Benih unggul untuk hasil optimal',
                'image_name' => 'banner_nk7328',
                'image_url' => null,
                'color_hex' => '#2F65D4'
            ),
            array(
                'id' => 3,
                'product_id' => 1,
                'title' => 'Agus 500 SC',
                'subtitle' => 'Perlindungan tanaman terpercaya',
                'image_name' => 'banner_agus',
                'image_url' => null,
                'color_hex' => '#2F65D4'
            )
        );
    }

    public function validate_cart_quantity($product_id, $quantity, $unit_type)
    {
        $product = $this->db
            ->select('id, stock, is_available, product_unit_value')
            ->where('id', (int) $product_id)
            ->get('products')
            ->row();

        if (!$product || (int) $product->is_available !== 1) {
            return array('valid' => FALSE, 'message' => 'Produk tidak tersedia.');
        }

        $multiplier = ((int) $unit_type === 2) ? max(1, (int) $product->product_unit_value) : 1;
        if (((int) $quantity * $multiplier) > (int) $product->stock) {
            return array('valid' => FALSE, 'message' => 'Quantity melebihi stok produk.');
        }

        return array('valid' => TRUE, 'message' => 'OK');
    }

    public function add_cart_item($user_id, $product_id, $quantity, $unit_type)
    {
        $existing = $this->db
            ->where(array(
                'user_id' => (int) $user_id,
                'product_id' => (int) $product_id,
                'unit_type' => (int) $unit_type
            ))
            ->get('mobile_cart_items')
            ->row();

        if ($existing) {
            return $this->db->where('id', $existing->id)->update('mobile_cart_items', array(
                'quantity' => (int) $quantity,
                'updated_at' => date('Y-m-d H:i:s')
            ));
        }

        return $this->db->insert('mobile_cart_items', array(
            'user_id' => (int) $user_id,
            'product_id' => (int) $product_id,
            'quantity' => (int) $quantity,
            'unit_type' => (int) $unit_type,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }

    public function active_transaction_order($user_id)
    {
        $row = $this->db
            ->where('user_id', (int) $user_id)
            ->where_not_in('order_status', array(3, 4, 5, 6, 7))
            ->order_by('order_date', 'DESC')
            ->get('orders')
            ->row_array();

        return $row ? $this->format_order($row) : null;
    }

    public function cart_item($id, $user_id)
    {
        return $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->get('mobile_cart_items')
            ->row();
    }

    public function update_cart_item($id, $user_id, $quantity)
    {
        return $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->update('mobile_cart_items', array(
                'quantity' => (int) $quantity,
                'updated_at' => date('Y-m-d H:i:s')
            ));
    }

    public function delete_cart_item($id, $user_id)
    {
        return $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->delete('mobile_cart_items');
    }

    public function cart($user_id, $level)
    {
        $rows = $this->db
            ->select('c.id AS cart_item_id, c.quantity, c.unit_type, v.*, pc.name AS category_name')
            ->from('mobile_cart_items c')
            ->join('v_products v', 'v.id = c.product_id')
            ->join('product_category pc', 'pc.id = v.category_id', 'left')
            ->where('c.user_id', (int) $user_id)
            ->order_by('c.id', 'ASC')
            ->get()
            ->result_array();

        $items = array();
        $subtotal = 0;
        $total_weight = 0;
        $total_quantity = 0;

        foreach ($rows as $row) {
            $product = $this->format_product($row, (int) $level);
            $unit = ((int) $row['unit_type'] === 2) ? $product['units'][1] : $product['units'][0];
            $quantity = (int) $row['quantity'];
            $item_subtotal = $unit['price'] * $quantity;
            $item_weight = $product['weight_per_piece'] * $unit['multiplier'] * $quantity;

            $items[] = array(
                'id' => (int) $row['cart_item_id'],
                'quantity' => $quantity,
                'unit' => $unit,
                'subtotal' => $item_subtotal,
                'total_weight' => $item_weight,
                'product' => $product
            );

            $subtotal += $item_subtotal;
            $total_weight += $item_weight;
            $total_quantity += $quantity;
        }

        return array(
            'items' => $items,
            'summary' => array(
                'item_count' => count($items),
                'total_quantity' => $total_quantity,
                'subtotal' => $subtotal,
                'total_weight' => $total_weight
            )
        );
    }

    public function shipping_request($endpoint, $method = 'GET', $params = array())
    {
        $this->config->load('rajaongkir', TRUE);
        $config = $this->config->item('rajaongkir');
        $api_key = isset($config['rajaongkir_api_key']) ? $config['rajaongkir_api_key'] : '';
        $base_url = isset($config['rajaongkir_base_url']) ? $config['rajaongkir_base_url'] : '';

        if ($api_key === '' || $base_url === '') {
            return array('success' => FALSE, 'message' => 'Konfigurasi ongkir tidak tersedia.', 'data' => null);
        }

        $url = rtrim($base_url, '/') . '/' . ltrim($endpoint, '/');
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'key: ' . $api_key,
                'Content-Type: application/x-www-form-urlencoded'
            ));
        } else {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('key: ' . $api_key));
        }

        $raw = curl_exec($curl);
        $error = curl_error($curl);
        $http_code = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $decoded = json_decode($raw, TRUE);
        if ($error || !is_array($decoded) || $http_code >= 400) {
            return array(
                'success' => FALSE,
                'message' => $error ? $error : 'Provider ongkir mengembalikan respons tidak valid.',
                'data' => $decoded
            );
        }

        return array('success' => TRUE, 'message' => 'OK', 'data' => $decoded);
    }

    public function shipping_quote($user_id, $destination_id, $courier, $weight)
    {
        $this->config->load('rajaongkir', TRUE);
        $config = $this->config->item('rajaongkir');
        $origin_id = isset($config['mobile_shipping_origin_id']) ? (int) $config['mobile_shipping_origin_id'] : 2528;

        $response = $this->shipping_request('calculate/domestic-cost', 'POST', array(
            'origin' => $origin_id,
            'destination' => (int) $destination_id,
            'weight' => max(1, (int) $weight),
            'courier' => $courier,
            'price' => 'lowest'
        ));

        if (!$response['success']) {
            return $response;
        }

        $raw_options = isset($response['data']['data']) ? $response['data']['data'] : array();
        $options = array();

        foreach ((array) $raw_options as $option) {
            if (!is_array($option)) {
                continue;
            }

            $cost = isset($option['cost']) ? $option['cost'] : (isset($option['price']) ? $option['price'] : 0);
            if (is_array($cost) && isset($cost[0]['value'])) {
                $cost = $cost[0]['value'];
            }

            $options[] = array(
                'courier' => isset($option['code']) ? $option['code'] : $courier,
                'service' => isset($option['service']) ? $option['service'] : (isset($option['name']) ? $option['name'] : 'service'),
                'description' => isset($option['description']) ? $option['description'] : '',
                'cost' => (float) $cost,
                'etd' => isset($option['etd']) ? $option['etd'] : null
            );
        }

        if (empty($options)) {
            return array('success' => FALSE, 'message' => 'Pilihan ongkir tidak ditemukan.', 'data' => $response['data']);
        }

        $expires_at = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $this->db->insert('mobile_shipping_quotes', array(
            'user_id' => (int) $user_id,
            'origin_id' => $origin_id,
            'destination_id' => (int) $destination_id,
            'weight' => (int) $weight,
            'courier' => $courier,
            'options_json' => json_encode($options),
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s')
        ));

        return array(
            'success' => TRUE,
            'message' => 'OK',
            'data' => array(
                'quote_id' => (int) $this->db->insert_id(),
                'expires_at' => $expires_at,
                'options' => $options
            )
        );
    }

    public function payment_methods()
    {
        return array(
            array(
                'id' => 2,
                'name' => 'Virtual Account Karisma',
                'detail' => 'Pembayaran melalui virtual account',
                'icon' => 'building.columns.fill'
            ),
            array(
                'id' => 3,
                'name' => 'Transfer Bank',
                'detail' => 'Transfer manual ke rekening Karisma',
                'icon' => 'banknote.fill'
            )
        );
    }

    public function payment_banks()
    {
        $banks = json_decode(get_settings('payment_banks'), TRUE);
        $result = array();

        foreach ((array) $banks as $code => $bank) {
            if (!is_array($bank)) {
                continue;
            }

            $result[] = array(
                'id' => (string) $code,
                'bank' => isset($bank['bank']) ? $bank['bank'] : '',
                'number' => isset($bank['number']) ? $bank['number'] : '',
                'name' => isset($bank['name']) ? $bank['name'] : ''
            );
        }

        return $result;
    }

    public function validate_coupon($code, $subtotal)
    {
        $code = strtoupper(trim((string) $code));
        $subtotal = max(0, (float) $subtotal);

        if ($code === '' || $subtotal <= 0) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Kode kupon tidak valid.');
        }

        $today = date('Y-m-d');
        $coupon = $this->db
            ->where('UPPER(code) = ' . $this->db->escape($code), null, false)
            ->where('is_active', 1)
            ->where('start_date <=', $today)
            ->where('expired_date >=', $today)
            ->get('coupons')
            ->row_array();

        if (!$coupon) {
            return array('success' => FALSE, 'status' => 404, 'message' => 'Kode kupon tidak tersedia atau sudah kedaluwarsa.');
        }

        $discount = min($subtotal, (float) $coupon['credit']);
        if ($discount <= 0) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Kupon tidak dapat digunakan untuk transaksi ini.');
        }

        return array(
            'success' => TRUE,
            'status' => 200,
            'data' => array(
                'id' => (int) $coupon['id'],
                'name' => $coupon['name'],
                'code' => strtoupper($coupon['code']),
                'credit' => $discount,
                'discount_amount' => $discount
            )
        );
    }

    public function checkout($user_id, $level, $data)
    {
        $cart = $this->cart($user_id, $level);
        if (empty($cart['items'])) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Keranjang masih kosong.');
        }

        $active_order = $this->active_transaction_order($user_id);
        if ($active_order) {
            return array(
                'success' => FALSE,
                'status' => 409,
                'message' => 'Masih ada transaksi berjalan. Lanjutkan dari menu Riwayat sebelum membuat pesanan baru.',
                'errors' => array('active_order' => $active_order)
            );
        }

        foreach ($cart['items'] as $item) {
            $check = $this->validate_cart_quantity(
                $item['product']['id'],
                $item['quantity'],
                $item['unit']['type']
            );
            if (!$check['valid']) {
                return array('success' => FALSE, 'status' => 422, 'message' => $item['product']['name'] . ': ' . $check['message']);
            }
        }

        $profile = $this->profile($user_id);
        $order_number = $this->generate_order_number($user_id);
        $coupon = null;
        $discount = 0;
        $voucher_code = isset($data['voucher_code']) ? trim((string) $data['voucher_code']) : '';
        if ($voucher_code !== '') {
            $coupon_result = $this->validate_coupon($voucher_code, $cart['summary']['subtotal']);
            if (!$coupon_result['success']) {
                return $coupon_result;
            }
            $coupon = $coupon_result['data'];
            $discount = (float) $coupon['discount_amount'];
        }

        $order = array(
            'user_id' => (int) $user_id,
            'coupon_id' => $coupon ? (int) $coupon['id'] : null,
            'order_number' => $order_number,
            'kd_faktur' => 'MOB-' . $order_number,
            'invoice_number' => '',
            'order_status' => 1,
            'order_date' => date('Y-m-d H:i:s'),
            'total_price' => max(0, (float) $cart['summary']['subtotal'] - $discount),
            'total_items' => count($cart['items']),
            'payment_method' => null,
            'shipping_method' => 5,
            'delivery_data' => json_encode(array(
                'customer' => array(
                    'name' => $profile['name'],
                    'phone_number' => $profile['phone_number'],
                    'address' => $profile['address'],
                    'shop_name' => $profile['shop_name'],
                    'shop_address' => $profile['shop_address']
                ),
                'note' => $data['note']
            )),
            'due_date' => date('Y-m-d'),
            'jenis_pengiriman' => '',
            'estimasi_kirim' => '',
            'shipping_cost' => 0,
            'insurance' => 0
        );

        if ($this->db->field_exists('nama_ekspedisi', 'orders')) {
            $order['nama_ekspedisi'] = '';
        }

        $this->db->trans_begin();

        $this->db->insert('orders', $order);
        $order_id = (int) $this->db->insert_id();

        $order_items = array();
        foreach ($cart['items'] as $item) {
            $order_items[] = array(
                'order_id' => $order_id,
                'product_id' => $item['product']['id'],
                'order_qty' => $item['quantity'],
                'order_price' => $item['unit']['price'],
                'satuan' => $item['unit']['type'],
                'satuan_text' => $item['unit']['name'],
                'satuan_qty' => $item['unit']['multiplier']
            );
        }

        $this->db->insert_batch('order_items', $order_items);
        $this->db->where('user_id', (int) $user_id)->delete('mobile_cart_items');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('success' => FALSE, 'status' => 500, 'message' => 'Pesanan gagal disimpan.');
        }

        $this->db->trans_commit();

        return array(
            'success' => TRUE,
            'status' => 201,
            'data' => $this->order_summary($order_id, $user_id)
        );
    }

    public function confirm_bank_transfer($order_id, $user_id, $data)
    {
        $order = $this->db
            ->where(array('id' => (int) $order_id, 'user_id' => (int) $user_id))
            ->get('orders')
            ->row_array();

        if (!$order) {
            return array('success' => FALSE, 'status' => 404, 'message' => 'Pesanan tidak ditemukan.');
        }

        if ((int) $order['payment_method'] !== 3) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Pesanan ini bukan pembayaran transfer bank.');
        }

        if (!in_array((int) $order['order_status'], array(2, 8), TRUE)) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Status pesanan tidak dapat menerima konfirmasi pembayaran.');
        }

        $banks = array();
        foreach ($this->payment_banks() as $bank) {
            $banks[$bank['id']] = $bank;
        }

        if (!isset($banks[$data['transfer_to']])) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Bank tujuan transfer tidak valid.');
        }

        $amount = (float) $data['transfer_amount'];
        if ($amount <= 0) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Jumlah transfer tidak valid.');
        }

        $payment_data = array(
            'order_id' => (int) $order_id,
            'bank_name' => $data['source_bank'],
            'bank_number' => $data['source_account_number'],
            'transfer' => $amount,
            'bank' => $data['transfer_to'],
            'transfer_to' => $data['transfer_to'],
            'name' => $data['source_account_name'],
            'name_duplicate' => $data['source_account_name'],
            'source' => array(
                'bank' => $data['source_bank'],
                'number' => $data['source_account_number'],
                'name' => $data['source_account_name']
            )
        );

        $payment = array(
            'order_id' => (int) $order_id,
            'payment_price' => $amount,
            'payment_date' => date('Y-m-d H:i:s'),
            'picture_name' => $data['picture_name'] !== '' ? $data['picture_name'] : '-',
            'payment_status' => '1',
            'payment_data' => json_encode($payment_data)
        );

        $this->db->trans_begin();
        $this->db->where('id', (int) $order_id)->update('orders', array('order_status' => 8));
        $this->db->insert('payments', $payment);
        $payment_id = (int) $this->db->insert_id();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('success' => FALSE, 'status' => 500, 'message' => 'Konfirmasi pembayaran gagal disimpan.');
        }

        $this->db->trans_commit();

        $payment['id'] = $payment_id;
        $payment['payment_price'] = (float) $payment['payment_price'];
        $payment['payment_status'] = (int) $payment['payment_status'];
        $payment['picture_url'] = $payment['picture_name'] !== '-'
            ? base_url('assets/uploads/payments/' . $payment['picture_name'])
            : null;
        $payment['payment_data'] = $payment_data;

        return array('success' => TRUE, 'status' => 201, 'message' => 'Konfirmasi pembayaran berhasil dikirim.', 'data' => $payment);
    }

    public function select_payment_method($order_id, $user_id, $payment_method)
    {
        $payment_method = (int) $payment_method;
        if (!in_array($payment_method, array(2, 3), TRUE)) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Metode pembayaran tidak didukung. Gunakan payment_method 2 atau 3.');
        }

        $order = $this->db
            ->where(array('id' => (int) $order_id, 'user_id' => (int) $user_id))
            ->get('orders')
            ->row_array();

        if (!$order) {
            return array('success' => FALSE, 'status' => 404, 'message' => 'Pesanan tidak ditemukan.');
        }

        if ((int) $order['order_status'] !== 2) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Metode pembayaran baru dapat dipilih setelah pesanan dikonfirmasi admin.');
        }

        $this->db
            ->where(array('id' => (int) $order_id, 'user_id' => (int) $user_id))
            ->update('orders', array('payment_method' => $payment_method));

        return array(
            'success' => TRUE,
            'status' => 200,
            'message' => 'Metode pembayaran berhasil dipilih.',
            'data' => $this->order_summary($order_id, $user_id)
        );
    }

    public function generate_briva_payment($order_id, $user_id, $brivaws)
    {
        $order = $this->db
            ->select('o.*, c.name AS customer_name, c.phone_number, c.va_code AS customer_va_code')
            ->from('orders o')
            ->join('customers c', 'c.user_id = o.user_id', 'left')
            ->where(array('o.id' => (int) $order_id, 'o.user_id' => (int) $user_id))
            ->get()
            ->row_array();

        if (!$order) {
            return array('success' => FALSE, 'status' => 404, 'message' => 'Pesanan tidak ditemukan.');
        }

        if ((int) $order['order_status'] !== 2) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'BRIVA baru dapat dibuat saat invoice menunggu pembayaran.');
        }

        if ((int) $order['payment_method'] !== 2) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'BRIVA hanya tersedia untuk metode pembayaran Virtual Account.');
        }

        if (is_briva_payment_local()) {
            return $this->generate_local_briva_payment($order, $user_id);
        }

        $existing = $this->briva_payment_by_order_number($order['order_number']);
        if ($existing && strtotime((string) $existing['exp_date']) > time()) {
            return array(
                'success' => TRUE,
                'status' => 200,
                'message' => 'Payment BRIVA sudah tersedia.',
                'data' => $this->format_briva_payment($existing)
            );
        }

        $customer_no = $this->briva_customer_no($order);
        if ($customer_no === '') {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Nomor customer untuk BRIVA belum valid.');
        }

        $va_name = $this->briva_customer_name($order);
        $trxid = $order['order_number'];
        $total_to_pay = (float) $order['total_price'] + (float) $order['shipping_cost'] + (float) $order['insurance'];
        $total_to_pay_formatted = number_format($total_to_pay, 2, '.', '');
        $expires_at = date('c', strtotime('+15 minutes'));

        $api_response = $this->decode_briva_response(
            $brivaws->updateVa(
                $customer_no,
                $va_name,
                $trxid,
                $total_to_pay_formatted
            )
        );

        $response_code = $this->briva_response_code($api_response);
        $success_message = 'Payment Telah terbuat';

        if ($response_code === '4042812') {
            $create_response = $this->decode_briva_response(
                $brivaws->createVa(
                    $customer_no,
                    $va_name,
                    $total_to_pay_formatted,
                    $trxid
                )
            );

            if (!$this->is_successful_briva_response($create_response)) {
                return array(
                    'success' => FALSE,
                    'status' => 502,
                    'message' => 'Gagal create VA ke BRIVA.',
                    'errors' => array(
                        'briva_update' => $api_response,
                        'briva_create' => $create_response
                    )
                );
            }

            $api_response = $create_response;
            $success_message = 'Payment VA Telah terbuat';
        } elseif ($response_code !== '2002800') {
            return array(
                'success' => FALSE,
                'status' => 502,
                'message' => 'Gagal update ke BRIVA.',
                'errors' => array('briva' => $api_response)
            );
        }

        if (!$this->is_successful_briva_response($api_response)) {
            return array(
                'success' => FALSE,
                'status' => 502,
                'message' => 'Gagal generate payment BRIVA.',
                'errors' => array('briva' => $api_response)
            );
        }

        $data = array(
            'order_number' => $trxid,
            'kd_faktur' => $order['kd_faktur'],
            'user_id' => (int) $user_id,
            'name' => $va_name,
            'va_code' => '91118' . $customer_no,
            'userno' => $customer_no,
            'total_price_topay' => $total_to_pay_formatted,
            'exp_date' => $expires_at,
            'status' => '1'
        );

        $this->save_briva_payment($trxid, $data);
        $saved = $this->briva_payment_by_order_number($trxid);

        return array(
            'success' => TRUE,
            'status' => 201,
            'message' => $success_message,
            'data' => $this->format_briva_payment($saved ? $saved : $data)
        );
    }

    public function briva_payment_status($order_id, $user_id, $brivaws)
    {
        $order = $this->db
            ->where(array('id' => (int) $order_id, 'user_id' => (int) $user_id))
            ->get('orders')
            ->row_array();

        if (!$order) {
            return array('success' => FALSE, 'status' => 404, 'message' => 'Pesanan tidak ditemukan.');
        }

        if ((int) $order['payment_method'] !== 2) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Pesanan ini bukan pembayaran Virtual Account.');
        }

        $briva = $this->briva_payment_by_order_number($order['order_number']);
        if (!$briva) {
            return array('success' => FALSE, 'status' => 404, 'message' => 'Payment BRIVA belum tersedia.');
        }

        if ((int) $briva['status'] === 2 || in_array((int) $order['order_status'], array(3, 10), TRUE)) {
            $this->complete_briva_payment($order['order_number']);
            $briva = $this->briva_payment_by_order_number($order['order_number']);
            $order = $this->db
                ->where(array('id' => (int) $order_id, 'user_id' => (int) $user_id))
                ->get('orders')
                ->row_array();

            return array(
                'success' => TRUE,
                'status' => 200,
                'message' => 'Pembayaran BRIVA sudah dikonfirmasi dan order masuk pengemasan.',
                'data' => $this->format_briva_status_response($order, $user_id, $briva, 'Y', FALSE, 'Pembayaran BRIVA sudah dikonfirmasi dan order masuk pengemasan.')
            );
        }

        if ((int) $order['order_status'] === 7) {
            return array(
                'success' => TRUE,
                'status' => 200,
                'message' => 'Order dibatalkan.',
                'data' => $this->format_briva_status_response($order, $user_id, $briva, 'N', TRUE, 'Order dibatalkan.')
            );
        }

        if (is_briva_payment_local()) {
            return $this->local_briva_payment_status($order, $user_id, $briva);
        }

        $status_response = $this->decode_briva_response(
            $brivaws->inquiryStatusVa($briva['userno'], $briva['order_number'])
        );
        $va_response = $this->decode_briva_response(
            $brivaws->inquiryVa($briva['userno'], $briva['order_number'])
        );

        if ($this->briva_response_code($status_response) === '') {
            return array(
                'success' => FALSE,
                'status' => 502,
                'message' => 'Response status BRIVA tidak valid.',
                'errors' => array('briva_status' => $status_response)
            );
        }

        $paid_status = isset($status_response['additionalInfo']['paidStatus'])
            ? (string) $status_response['additionalInfo']['paidStatus']
            : 'N';
        $expired_date = isset($va_response['virtualAccountData']['expiredDate'])
            ? (string) $va_response['virtualAccountData']['expiredDate']
            : (isset($briva['exp_date']) ? (string) $briva['exp_date'] : '');
        $is_expired = $expired_date !== '' && strtotime($expired_date) !== FALSE && strtotime($expired_date) <= time();
        $status_text = isset($status_response['responseMessage'])
            ? (string) $status_response['responseMessage']
            : 'Menunggu pembayaran';

        if ($paid_status === 'Y') {
            $brivaws->updateStatusVa($briva['userno'], $briva['order_number']);
            $this->complete_briva_payment($order['order_number']);
            $status_text = 'Pembayaran BRIVA berhasil diterima dan order masuk pengemasan.';
            $is_expired = FALSE;
        } elseif ($is_expired) {
            $brivaws->updateStatusVa($briva['userno'], $briva['order_number']);
            $this->db->where('order_number', $order['order_number'])->update('briva_api', array('status' => 3));
            $this->db->where('order_number', $order['order_number'])->update('orders', array('order_status' => 7));
            $status_text = 'VA expired & transaksi dibatalkan';
        }

        $briva = $this->briva_payment_by_order_number($order['order_number']);
        $order = $this->db
            ->where(array('id' => (int) $order_id, 'user_id' => (int) $user_id))
            ->get('orders')
            ->row_array();

        $data = $this->format_briva_status_response(
            $order,
            $user_id,
            $briva,
            $paid_status,
            $is_expired,
            $status_text,
            $expired_date,
            isset($va_response['virtualAccountData']) ? $va_response['virtualAccountData'] : null
        );

        return array(
            'success' => TRUE,
            'status' => 200,
            'message' => $status_text,
            'data' => $data
        );
    }

    public function order_summary($id, $user_id)
    {
        $row = $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->get('orders')
            ->row_array();

        return $row ? $this->format_order($row) : null;
    }

    public function orders($user_id, $page, $per_page)
    {
        $total = $this->db->where('user_id', (int) $user_id)->count_all_results('orders');
        $rows = $this->db
            ->where('user_id', (int) $user_id)
            ->order_by('order_date', 'DESC')
            ->limit($per_page, ($page - 1) * $per_page)
            ->get('orders')
            ->result_array();

        foreach ($rows as &$row) {
            $row = $this->format_order($row);
        }

        return array(
            'items' => $rows,
            'total' => (int) $total
        );
    }

    public function order($id, $user_id)
    {
        $row = $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->get('orders')
            ->row_array();

        if (!$row) {
            return null;
        }

        $order = $this->format_order($row);
        $items = $this->db
            ->select('oi.*, p.sku, p.name, p.picture_name')
            ->from('order_items oi')
            ->join('products p', 'p.id = oi.product_id', 'left')
            ->where('oi.order_id', (int) $id)
            ->get()
            ->result_array();

        foreach ($items as &$item) {
            $item['id'] = (int) $item['id'];
            $item['product_id'] = (int) $item['product_id'];
            $item['product_name'] = isset($item['name']) && $item['name'] !== '' ? $item['name'] : 'Produk';
            $item['quantity'] = (int) $item['order_qty'];
            $item['unit_name'] = isset($item['satuan_text']) && $item['satuan_text'] !== '' ? $item['satuan_text'] : 'pcs';
            $item['unit_price'] = (float) $item['order_price'];
            $item['subtotal'] = $item['quantity'] * $item['unit_price'];
            $item['image_url'] = $this->product_image_url(isset($item['picture_name']) ? $item['picture_name'] : null);
            unset($item['order_qty'], $item['order_price'], $item['picture_name'], $item['name'], $item['satuan_text']);
        }

        $items_subtotal = 0;
        foreach ($items as $item) {
            $items_subtotal += (float) $item['subtotal'];
        }
        $coupon = $this->order_coupon(isset($order['coupon_id']) ? $order['coupon_id'] : null);
        $coupon_discount = $coupon ? max(0, $items_subtotal - (float) $order['total_price']) : 0;
        if ($coupon && $coupon_discount <= 0) {
            $coupon_discount = min($items_subtotal, (float) $coupon['discount_amount']);
        }

        $delivery_data = is_array($order['delivery_data']) ? $order['delivery_data'] : array();
        $customer = isset($delivery_data['customer']) && is_array($delivery_data['customer'])
            ? $delivery_data['customer']
            : array();

        return array(
            'order' => $order,
            'items' => $items,
            'shipping_address' => isset($customer['address']) ? $customer['address'] : '',
            'shipping_service' => $this->order_shipping_service($order),
            'shipping_cost' => isset($order['shipping_cost']) ? (float) $order['shipping_cost'] : 0,
            'insurance' => isset($order['insurance']) ? (float) $order['insurance'] : 0,
            'coupon' => $coupon,
            'coupon_discount' => $coupon_discount,
            'briva_payment' => $this->format_briva_payment(
                $this->briva_payment_by_order_number($order['order_number'])
            )
        );
    }

    public function apply_order_coupon($id, $user_id, $code)
    {
        $order = $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->get('orders')
            ->row_array();

        if (!$order) {
            return array('success' => FALSE, 'status' => 404, 'message' => 'Pesanan tidak ditemukan.');
        }

        if (!empty($order['coupon_id'])) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Pesanan sudah menggunakan kupon.');
        }

        if (!in_array((int) $order['order_status'], array(1, 2, 8, 9), TRUE)) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Kupon tidak dapat diterapkan pada status pesanan ini.');
        }

        $items_subtotal = $this->order_items_subtotal((int) $id);
        $coupon_result = $this->validate_coupon($code, $items_subtotal);
        if (!$coupon_result['success']) {
            return $coupon_result;
        }

        $coupon = $coupon_result['data'];
        $discount = (float) $coupon['discount_amount'];
        $updated = $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->update('orders', array(
                'coupon_id' => (int) $coupon['id'],
                'total_price' => max(0, $items_subtotal - $discount)
            ));

        if (!$updated) {
            return array('success' => FALSE, 'status' => 500, 'message' => 'Kupon gagal diterapkan.');
        }

        return array(
            'success' => TRUE,
            'status' => 200,
            'data' => $this->order((int) $id, (int) $user_id)
        );
    }

    public function cancel_order($id, $user_id)
    {
        $order = $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->get('orders')
            ->row();

        if (!$order || !in_array((int) $order->order_status, array(1, 2, 9), TRUE)) {
            return FALSE;
        }

        return $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->update('orders', array('order_status' => 7));
    }

    public function complete_order($id, $user_id, $data)
    {
        $rating = (int) $data['rating'];
        if ($rating < 1 || $rating > 5) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Rating pelayanan sales wajib diisi.');
        }

        $order = $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->get('orders')
            ->row();

        if (!$order) {
            return array('success' => FALSE, 'status' => 404, 'message' => 'Pesanan tidak ditemukan.');
        }

        if ((int) $order->order_status !== 4) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Pesanan belum dalam status dikirim.');
        }

        $updated = $this->db
            ->where(array('id' => (int) $id, 'user_id' => (int) $user_id))
            ->update('orders', array(
                'order_status' => 5,
                'finish_date' => date('Y-m-d H:i:s'),
                'rating' => $rating,
                'rating_desc' => isset($data['rating_description']) ? $data['rating_description'] : ''
            ));

        if (!$updated) {
            return array('success' => FALSE, 'status' => 500, 'message' => 'Pesanan gagal diselesaikan.');
        }

        return array(
            'success' => TRUE,
            'status' => 200,
            'message' => 'Pesanan selesai. Terima kasih atas rating pelayanan sales.',
            'data' => $this->order((int) $id, (int) $user_id)
        );
    }

    public function messages($user_id, $last_id)
    {
        $this->db->where('customer_id', (int) $user_id);
        if ($last_id > 0) {
            $this->db->where('id >', (int) $last_id);
        }

        $rows = $this->db->order_by('id', 'ASC')->limit(100)->get('message')->result_array();
        foreach ($rows as &$row) {
            $row['id'] = (int) $row['id'];
            $row['chat_from'] = (int) $row['chat_from'];
        }

        $this->db
            ->where(array('customer_id' => (int) $user_id, 'chat_from' => 1))
            ->update('message', array('status' => 2));

        return $rows;
    }

    public function send_message($user_id, $message)
    {
        $customer = $this->db
            ->select('salesman_id')
            ->where('user_id', (int) $user_id)
            ->get('customers')
            ->row();

        $data = array(
            'salesman_id' => $customer ? (int) $customer->salesman_id : null,
            'customer_id' => (int) $user_id,
            'message' => $message,
            'chat_from' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 2
        );

        $this->db->insert('message', $data);
        $data['id'] = (int) $this->db->insert_id();

        return $data;
    }

    private function briva_payment_by_order_number($order_number)
    {
        return $this->db
            ->where('order_number', (string) $order_number)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('briva_api')
            ->row_array();
    }

    private function save_briva_payment($order_number, $data)
    {
        if ($this->briva_payment_by_order_number($order_number)) {
            return $this->db
                ->where('order_number', (string) $order_number)
                ->update('briva_api', $data);
        }

        return $this->db->insert('briva_api', $data);
    }

    private function generate_local_briva_payment($order, $user_id)
    {
        $existing = $this->briva_payment_by_order_number($order['order_number']);

        if ($existing) {
            $this->complete_briva_payment($order['order_number']);

            $saved = $this->briva_payment_by_order_number($order['order_number']);
            $data = $this->format_briva_payment($saved ? $saved : $existing);
            $data['payment_mode'] = 'local';

            return array(
                'success' => TRUE,
                'status' => 200,
                'message' => 'Payment BRIVA lokal sudah tersedia dan disimulasikan lunas.',
                'data' => $data
            );
        }

        $customer_no = $this->briva_customer_no($order);
        if ($customer_no === '') {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Nomor customer untuk BRIVA lokal belum valid.');
        }

        $total_to_pay = (float) $order['total_price'] + (float) $order['shipping_cost'] + (float) $order['insurance'];
        $data = array(
            'order_number' => $order['order_number'],
            'kd_faktur' => $order['kd_faktur'],
            'user_id' => (int) $user_id,
            'name' => $this->briva_customer_name($order),
            'va_code' => '91118' . $customer_no,
            'userno' => $customer_no,
            'total_price_topay' => number_format($total_to_pay, 2, '.', ''),
            'exp_date' => date('c', strtotime('+1 day')),
            'status' => '2'
        );

        $this->db->trans_start();
        $this->save_briva_payment($order['order_number'], $data);
        $this->complete_briva_payment($order['order_number']);
        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            return array('success' => FALSE, 'status' => 500, 'message' => 'Payment BRIVA lokal gagal disimpan.');
        }

        $saved = $this->briva_payment_by_order_number($order['order_number']);
        $response_data = $this->format_briva_payment($saved ? $saved : $data);
        $response_data['payment_mode'] = 'local';

        return array(
            'success' => TRUE,
            'status' => 201,
            'message' => 'Payment BRIVA lokal berhasil dibuat tanpa koneksi API BRI production.',
            'data' => $response_data
        );
    }

    private function local_briva_payment_status($order, $user_id, $briva)
    {
        if ((int) $briva['status'] !== 2 || (int) $order['order_status'] !== 3) {
            $this->complete_briva_payment($order['order_number']);

            $briva = $this->briva_payment_by_order_number($order['order_number']);
            $order = $this->db
                ->where(array('id' => (int) $order['id'], 'user_id' => (int) $user_id))
                ->get('orders')
                ->row_array();
        }

        $data = $this->format_briva_status_response(
            $order,
            $user_id,
            $briva,
            'Y',
            FALSE,
            'Pembayaran BRIVA lokal berhasil diterima.',
            isset($briva['exp_date']) ? (string) $briva['exp_date'] : '',
            array(
                'paymentMode' => 'local',
                'virtualAccountNo' => isset($briva['va_code']) ? (string) $briva['va_code'] : '',
                'totalAmount' => array(
                    'value' => isset($briva['total_price_topay']) ? (string) $briva['total_price_topay'] : '0.00',
                    'currency' => 'IDR'
                )
            )
        );
        $data['payment_mode'] = 'local';

        return array(
            'success' => TRUE,
            'status' => 200,
            'message' => 'Pembayaran BRIVA lokal berhasil diterima.',
            'data' => $data
        );
    }

    private function complete_briva_payment($order_number)
    {
        $this->db
            ->where('order_number', (string) $order_number)
            ->update('briva_api', array('status' => 2));

        return $this->db
            ->where('order_number', (string) $order_number)
            ->update('orders', array('order_status' => 3));
    }

    private function briva_customer_no($order)
    {
        $digits = preg_replace('/\D+/', '', isset($order['phone_number']) ? (string) $order['phone_number'] : '');
        if (strlen($digits) >= 8) {
            return substr($digits, -8);
        }

        $va_code = preg_replace('/\D+/', '', isset($order['customer_va_code']) ? (string) $order['customer_va_code'] : '');
        if (strlen($va_code) > 5) {
            return substr($va_code, -8);
        }

        return '';
    }

    private function briva_customer_name($order)
    {
        $delivery_data = json_decode(isset($order['delivery_data']) ? $order['delivery_data'] : '', TRUE);
        if (isset($delivery_data['customer']['name']) && trim((string) $delivery_data['customer']['name']) !== '') {
            return trim((string) $delivery_data['customer']['name']);
        }

        if (isset($order['customer_name']) && trim((string) $order['customer_name']) !== '') {
            return trim((string) $order['customer_name']);
        }

        return 'Karisma Customer';
    }

    private function decode_briva_response($response)
    {
        if (is_array($response)) {
            return $response;
        }

        $decoded = json_decode((string) $response, TRUE);
        return is_array($decoded) ? $decoded : array('raw' => $response);
    }

    private function briva_response_code($response)
    {
        if (!is_array($response)) {
            return '';
        }

        return isset($response['responseCode']) ? (string) $response['responseCode'] : '';
    }

    private function is_successful_briva_response($response)
    {
        $code = $this->briva_response_code($response);
        return strpos($code, '200') === 0;
    }

    private function format_briva_payment($row)
    {
        if (!$row) {
            return null;
        }

        $expires_at = isset($row['exp_date']) ? (string) $row['exp_date'] : '';

        return array(
            'id' => isset($row['id']) ? (int) $row['id'] : null,
            'order_number' => isset($row['order_number']) ? (string) $row['order_number'] : '',
            'kd_faktur' => isset($row['kd_faktur']) ? (string) $row['kd_faktur'] : '',
            'name' => isset($row['name']) ? (string) $row['name'] : '',
            'va_code' => isset($row['va_code']) ? (string) $row['va_code'] : '',
            'userno' => isset($row['userno']) ? (string) $row['userno'] : '',
            'total_price_topay' => isset($row['total_price_topay']) ? (float) $row['total_price_topay'] : 0,
            'exp_date' => $expires_at,
            'expires_at' => $expires_at,
            'expired_payment' => $expires_at,
            'status' => isset($row['status']) ? (int) $row['status'] : 0
        );
    }

    private function format_briva_status_response($order, $user_id, $briva, $paid_status, $is_expired, $status_text, $expired_date = '', $va_data = null)
    {
        if ($expired_date === '' && $briva && isset($briva['exp_date'])) {
            $expired_date = (string) $briva['exp_date'];
        }

        return array(
            'paid_status' => (string) $paid_status,
            'is_paid' => $paid_status === 'Y',
            'is_expired' => (bool) $is_expired,
            'expired_date' => (string) $expired_date,
            'status_text' => (string) $status_text,
            'va_data' => $va_data,
            'briva_payment' => $this->format_briva_payment($briva),
            'order_detail' => $order ? $this->order((int) $order['id'], (int) $user_id) : null
        );
    }

    private function order_shipping_service($order)
    {
        if (isset($order['nama_ekspedisi']) && trim((string) $order['nama_ekspedisi']) !== '') {
            return trim((string) $order['nama_ekspedisi']);
        }

        if (isset($order['jenis_pengiriman']) && trim((string) $order['jenis_pengiriman']) !== '') {
            return trim((string) $order['jenis_pengiriman']);
        }

        return '';
    }

    private function format_order($row)
    {
        $status = (int) $row['order_status'];
        $labels = array(
            1 => 'Menunggu diproses',
            2 => 'Menunggu pembayaran',
            3 => 'Dikemas',
            4 => 'Dikirim',
            5 => 'Selesai',
            6 => 'Selesai',
            7 => 'Dibatalkan',
            8 => 'Sedang ditinjau oleh admin',
            9 => 'Menunggu persetujuan',
            10 => 'Payment Verify',
            11 => 'Tentukan metode pengiriman'
        );

        $row['id'] = (int) $row['id'];
        $row['coupon_id'] = empty($row['coupon_id']) ? null : (int) $row['coupon_id'];
        $row['order_status'] = $status;
        $row['status_id'] = $status;
        $row['status_label'] = isset($labels[$status]) ? $labels[$status] : 'Status ' . $status;
        $row['total_price'] = (float) $row['total_price'];
        $row['shipping_cost'] = (float) $row['shipping_cost'];
        $row['insurance'] = (float) $row['insurance'];
        $row['total_items'] = (int) $row['total_items'];
        $row['payment_method'] = $row['payment_method'] === null ? null : (int) $row['payment_method'];
        $row['grand_total'] = $row['total_price'] + $row['shipping_cost'] + $row['insurance'];
        $row['delivery_data'] = json_decode($row['delivery_data'], TRUE);

        return $row;
    }

    private function order_coupon($coupon_id)
    {
        if (!$coupon_id) {
            return null;
        }

        $coupon = $this->db
            ->where('id', (int) $coupon_id)
            ->get('coupons')
            ->row_array();

        if (!$coupon) {
            return null;
        }

        return array(
            'id' => (int) $coupon['id'],
            'name' => $coupon['name'],
            'code' => strtoupper($coupon['code']),
            'credit' => (float) $coupon['credit'],
            'discount_amount' => (float) $coupon['credit']
        );
    }

    private function order_items_subtotal($order_id)
    {
        $row = $this->db
            ->select('COALESCE(SUM(order_qty * order_price), 0) AS subtotal', FALSE)
            ->where('order_id', (int) $order_id)
            ->get('order_items')
            ->row_array();

        return $row ? (float) $row['subtotal'] : 0;
    }

    private function generate_order_number($user_id)
    {
        do {
            $number = 'MOB' . date('ymd') . substr(str_pad((string) $user_id, 4, '0', STR_PAD_LEFT), -4) . random_int(100, 999);
        } while ($this->db->where('order_number', $number)->count_all_results('orders') > 0);

        return $number;
    }

    private function insert_account_deletion_audit(array $user, $deleted_at)
    {
        if (!$this->db->table_exists('mobile_account_deletions')) {
            return;
        }

        $this->db->insert('mobile_account_deletions', array(
            'user_id' => (int) $user['id'],
            'email_hash' => hash('sha256', strtolower((string) $user['email'])),
            'deleted_at' => $deleted_at,
            'created_at' => $deleted_at
        ));
    }

    private function revoke_all_user_tokens($user_id, $revoked_at)
    {
        if (!$this->db->table_exists('mobile_api_tokens')) {
            return;
        }

        $this->db
            ->where('user_id', (int) $user_id)
            ->where('revoked_at IS NULL', null, FALSE)
            ->update('mobile_api_tokens', array('revoked_at' => $revoked_at));
    }

    private function delete_mobile_session_data($user_id)
    {
        foreach (array('mobile_cart_items', 'mobile_shipping_quotes') as $table) {
            if ($this->db->table_exists($table)) {
                $this->db->where('user_id', (int) $user_id)->delete($table);
            }
        }
    }

    private function anonymize_customer_profile($user_id)
    {
        if (!$this->db->table_exists('customers')) {
            return;
        }

        $data = array();
        $this->set_existing_field($data, 'customers', 'nik', '');
        $this->set_existing_field($data, 'customers', 'npwp', '');
        $this->set_existing_field($data, 'customers', 'name', 'Deleted User');
        $this->set_existing_field($data, 'customers', 'phone_number', null);
        $this->set_existing_field($data, 'customers', 'address', '');
        $this->set_existing_field($data, 'customers', 'shop_name', '');
        $this->set_existing_field($data, 'customers', 'shop_address', null);
        $this->set_existing_field($data, 'customers', 'alamat_kirim', '');
        $this->set_existing_field($data, 'customers', 'profile_picture', null);
        $this->set_existing_field($data, 'customers', 'kode_customer', '');

        if ($this->db->field_exists('user_id', 'customers')) {
            $data['user_id'] = null;
        }

        if (!empty($data)) {
            $this->db->where('user_id', (int) $user_id)->update('customers', $data);
        }
    }

    private function detach_user_history($user_id)
    {
        foreach (array('orders', 'reviews') as $table) {
            if ($this->db->table_exists($table) && $this->db->field_exists('user_id', $table)) {
                $this->db->where('user_id', (int) $user_id)->update($table, array('user_id' => null));
            }
        }

        if ($this->db->table_exists('message') && $this->db->field_exists('customer_id', 'message')) {
            $this->db->where('customer_id', (int) $user_id)->update('message', array('customer_id' => 0));
        }
    }

    private function anonymize_user_account($user_id, $deleted_email, $deleted_at)
    {
        $data = array(
            'email' => $deleted_email,
            'password' => password_hash(bin2hex(random_bytes(24)), PASSWORD_BCRYPT)
        );

        $this->set_existing_field($data, 'users', 'name', 'Deleted User');
        $this->set_existing_field($data, 'users', 'profile_picture', null);
        $this->set_existing_field($data, 'users', 'status', 0);
        $this->set_existing_field($data, 'users', 'email_verified_at', null);
        $this->set_existing_field($data, 'users', 'register_date', $deleted_at);

        $this->db->where('id', (int) $user_id)->update('users', $data);
    }

    private function set_existing_field(array &$data, $table, $field, $value)
    {
        if ($this->db->field_exists($field, $table)) {
            $data[$field] = $value;
        }
    }
}
