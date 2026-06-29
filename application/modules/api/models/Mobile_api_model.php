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
            'nik' => '',
            'npwp' => '',
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
            'province_id' => 0,
            'kota_id' => 0,
            'subdistrict_id' => 0,
            'address' => $data['address'],
            'shop_name' => $data['shop_name'],
            'shop_address' => $data['shop_address'] !== '' ? $data['shop_address'] : $data['address'],
            'max_credit' => 0,
            'level' => 1,
            'profile_picture' => null,
            'salesman_id' => 79,
            'kode_customer' => '',
            'va_code' => 0
        );

        if ($this->db->field_exists('alamat_kirim', 'customers')) {
            $customer['alamat_kirim'] = $data['address'];
        }

        $this->db->insert('customers', $customer);

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

    public function user_from_token($plain_token, $touch = TRUE)
    {
        $user = $this->db
            ->select('t.id AS token_id, u.id, u.email, u.status, c.name, c.level, c.salesman_id')
            ->from('mobile_api_tokens t')
            ->join('users u', 'u.id = t.user_id')
            ->join('customers c', 'c.user_id = u.id')
            ->where('t.token_hash', hash('sha256', $plain_token))
            ->where('t.revoked_at IS NULL', null, FALSE)
            ->where('t.expires_at >', date('Y-m-d H:i:s'))
            ->where(array('u.status' => 1, 'u.role' => 'customer'))
            ->get()
            ->row();

        if ($user && $touch) {
            $this->db->where('id', $user->token_id)->update('mobile_api_tokens', array(
                'last_used_at' => date('Y-m-d H:i:s')
            ));
        }

        return $user;
    }

    public function revoke_token($plain_token)
    {
        return $this->db
            ->where('token_hash', hash('sha256', $plain_token))
            ->update('mobile_api_tokens', array('revoked_at' => date('Y-m-d H:i:s')));
    }

    public function profile($user_id)
    {
        $fields = array(
            'u.id', 'u.email', 'u.status', 'c.name', 'c.phone_number', 'c.address',
            'c.shop_name', 'c.shop_address', 'c.province_id', 'c.kota_id',
            'c.subdistrict_id', 'c.level', 'c.max_credit', 'c.profile_picture',
            'c.salesman_id', 'c.kode_customer'
        );

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

        $profile['id'] = (int) $profile['id'];
        $profile['status'] = (int) $profile['status'];
        $profile['province_id'] = (int) $profile['province_id'];
        $profile['kota_id'] = (int) $profile['kota_id'];
        $profile['subdistrict_id'] = (int) $profile['subdistrict_id'];
        $profile['level'] = (int) $profile['level'];
        $profile['max_credit'] = (int) $profile['max_credit'];
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
                $allowed[$field] = $value;
            }
        }

        if (empty($allowed)) {
            return FALSE;
        }

        return $this->db->where('user_id', (int) $user_id)->update('customers', $allowed);
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
        $this->db->from('v_products v')->where('v.is_available', 1);
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
            'image_url' => base_url('assets/uploads/products/' . ($row['picture_name'] ? $row['picture_name'] : 'default.jpg')),
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

    public function checkout($user_id, $level, $data)
    {
        if ((int) $data['payment_method'] !== 2) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'API v1 baru mendukung payment_method 2.');
        }

        $quote = $this->db
            ->where(array(
                'id' => (int) $data['shipping_quote_id'],
                'user_id' => (int) $user_id,
                'used_at' => null
            ))
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->get('mobile_shipping_quotes')
            ->row();

        if (!$quote) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Quote ongkir tidak valid atau kedaluwarsa.');
        }

        $cart = $this->cart($user_id, $level);
        if (empty($cart['items'])) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Keranjang masih kosong.');
        }

        if ((int) $quote->weight !== (int) $cart['summary']['total_weight']) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Berat keranjang berubah. Buat quote ongkir baru.');
        }

        $selected = null;
        foreach ((array) json_decode($quote->options_json, TRUE) as $option) {
            if (strcasecmp($option['service'], $data['shipping_service']) === 0) {
                $selected = $option;
                break;
            }
        }

        if (!$selected) {
            return array('success' => FALSE, 'status' => 422, 'message' => 'Layanan ongkir tidak ditemukan.');
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
        $shipping_cost = (float) $selected['cost'];
        $order_number = $this->generate_order_number($user_id);

        $order = array(
            'user_id' => (int) $user_id,
            'coupon_id' => null,
            'order_number' => $order_number,
            'kd_faktur' => 'MOB-' . $order_number,
            'invoice_number' => '',
            'order_status' => 2,
            'order_date' => date('Y-m-d H:i:s'),
            'total_price' => $cart['summary']['subtotal'],
            'total_items' => count($cart['items']),
            'payment_method' => 2,
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
            'jenis_pengiriman' => $selected['courier'] . '-' . $selected['service'],
            'estimasi_kirim' => $selected['etd'] ? $selected['etd'] : '0',
            'shipping_cost' => $shipping_cost,
            'insurance' => 0
        );

        if ($this->db->field_exists('nama_ekspedisi', 'orders')) {
            $order['nama_ekspedisi'] = $selected['courier'];
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
        $this->db->where('id', (int) $quote->id)->update('mobile_shipping_quotes', array(
            'used_at' => date('Y-m-d H:i:s')
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('success' => FALSE, 'status' => 500, 'message' => 'Pesanan gagal disimpan.');
        }

        $this->db->trans_commit();

        return array(
            'success' => TRUE,
            'status' => 201,
            'data' => $this->order($order_id, $user_id)
        );
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
            $item['quantity'] = (int) $item['order_qty'];
            $item['price'] = (float) $item['order_price'];
            $item['subtotal'] = $item['quantity'] * $item['price'];
            $item['image_url'] = base_url('assets/uploads/products/' . ($item['picture_name'] ? $item['picture_name'] : 'default.jpg'));
            unset($item['order_qty'], $item['order_price'], $item['picture_name']);
        }

        $order['items'] = $items;
        return $order;
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

    private function format_order($row)
    {
        $status = (int) $row['order_status'];
        $labels = array(
            1 => 'Menunggu diproses',
            2 => 'Menunggu pembayaran',
            3 => 'Dibayar',
            4 => 'Dikirim',
            5 => 'Selesai',
            6 => 'Selesai',
            7 => 'Dibatalkan',
            8 => 'Diproses',
            9 => 'Menunggu persetujuan'
        );

        $row['id'] = (int) $row['id'];
        $row['order_status'] = $status;
        $row['status_label'] = isset($labels[$status]) ? $labels[$status] : 'Status ' . $status;
        $row['total_price'] = (float) $row['total_price'];
        $row['shipping_cost'] = (float) $row['shipping_cost'];
        $row['insurance'] = (float) $row['insurance'];
        $row['total_items'] = (int) $row['total_items'];
        $row['payment_method'] = (int) $row['payment_method'];
        $row['grand_total'] = $row['total_price'] + $row['shipping_cost'] + $row['insurance'];
        $row['delivery_data'] = json_decode($row['delivery_data'], TRUE);

        return $row;
    }

    private function generate_order_number($user_id)
    {
        do {
            $number = 'MOB' . date('ymd') . substr(str_pad((string) $user_id, 4, '0', STR_PAD_LEFT), -4) . random_int(100, 999);
        } while ($this->db->where('order_number', $number)->count_all_results('orders') > 0);

        return $number;
    }
}
