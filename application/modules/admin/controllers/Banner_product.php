<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_product extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        $this->load->model(array(
            'product_model' => 'product',
            'order_model' => 'order'
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        $params['title'] = 'Kelola Banner Produk '. get_store_name();

        $products['banners'] = $this->product->get_all_banner();
        $products['flash'] = $this->session->flashdata('banner_product_flash');
        $products['error'] = $this->session->flashdata('banner_product_error');

        $this->load->view('header', $params);
        $this->load->view('products/banner_product', $products);
        $this->load->view('footer');
    }

    public function search()
    {
        $query = $this->input->get('search_query');
        $query = html_escape($query);

        $params['title'] = 'Cari "'. $query .'"';
        $params['query'] = $query;

        $config['base_url'] = site_url('admin/products/search');
        $config['total_rows'] = $this->product->count_all_products();
        $config['per_page'] = 16;
        $config['uri_segment'] = 4;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = floor($choice);

        $config['first_link']       = '«';
        $config['last_link']        = '»';
        $config['next_link']        = '›';
        $config['prev_link']        = '‹';
        $config['reuse_query_string'] = TRUE;
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';

        $this->load->library('pagination', $config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $products['products'] = $this->product->search_products($query, $config['per_page'], $page);
        $products['pagination'] = $this->pagination->create_links();
        $products['count'] = $this->product->count_search($query);

        $this->load->view('header', $params);
        $this->load->view('products/search', $products);
        $this->load->view('footer');
    }

    public function add_new_banner_product()
    {
        $params['title'] = 'Tambah Banner Produk Baru';

        $product['flash'] = $this->session->flashdata('add_new_product_flash');
        $product['error'] = $this->session->flashdata('add_new_product_error');
        $product['products'] = $this->product->get_list_products();
        $product['categories'] = $this->product->get_all_categories();
        $product['active_banner_count'] = $this->product->count_active_banner_products();
        $product['is_edit'] = FALSE;
        $product['banner'] = NULL;

        $this->load->view('header', $params);
        $this->load->view('products/add_new_banner_product', $product);
        $this->load->view('footer');
    }

    public function add_banner_product()
    {
        if ( ! $this->product->is_banner_product_flexible_ready())
        {
            $this->session->set_flashdata('add_new_product_error', 'Struktur database banner belum diperbarui. Jalankan SQL alter table terlebih dahulu.');
            redirect('admin/banner_product/add_new_banner_product');
        }

        $error = '';
        $banner = $this->_banner_payload_from_post($error);

        if ($banner === FALSE)
        {
            $this->session->set_flashdata('add_new_product_error', $error);
            redirect('admin/banner_product/add_new_banner_product');
        }

        $config = $this->_banner_upload_config();

        $this->load->library('upload', $config);

        if ( ! isset($_FILES['picture']) || $_FILES['picture']['error'] != UPLOAD_ERR_OK)
        {
            $this->session->set_flashdata('add_new_product_error', $this->_upload_error_message(isset($_FILES['picture']['error']) ? $_FILES['picture']['error'] : NULL));
            redirect('admin/banner_product/add_new_banner_product');
        }

        if ( ! $this->upload->do_upload('picture'))
        {
            $this->session->set_flashdata('add_new_product_error', strip_tags($this->upload->display_errors()));
            redirect('admin/banner_product/add_new_banner_product');
        }

        $upload_data = $this->upload->data();
        $file_name = $upload_data['file_name'];

        $banner['banner_image'] = $file_name;

        if ( ! $this->product->add_new_banner_product($banner))
        {
            $file = $config['upload_path'] . $file_name;

            if (file_exists($file) && is_readable($file))
            {
                unlink($file);
            }

            $this->session->set_flashdata('add_new_product_error', 'Banner gagal disimpan ke database.');
            redirect('admin/banner_product/add_new_banner_product');
        }

        $this->session->set_flashdata('add_new_product_flash', 'Banner produk baru berhasil ditambahkan!');

        redirect('admin/banner_product/add_new_banner_product');
    }

    public function edit_banner_product($id = 0)
    {
        $banner = $this->product->banner_data($id);

        if ( ! $banner)
        {
            show_404();
        }

        $params['title'] = 'Ubah Banner Produk';

        $product['flash'] = $this->session->flashdata('edit_banner_product_flash');
        $product['error'] = $this->session->flashdata('edit_banner_product_error');
        $product['products'] = $this->product->get_list_products();
        $product['categories'] = $this->product->get_all_categories();
        $product['active_banner_count'] = $this->product->count_active_banner_products($id);
        $product['is_edit'] = TRUE;
        $product['banner'] = $banner;

        $this->load->view('header', $params);
        $this->load->view('products/add_new_banner_product', $product);
        $this->load->view('footer');
    }

    public function update_banner_product()
    {
        $id = $this->input->post('id');
        $current = $this->product->banner_data($id);

        if ( ! $current)
        {
            show_404();
        }

        if ( ! $this->product->is_banner_product_flexible_ready())
        {
            $this->session->set_flashdata('edit_banner_product_error', 'Struktur database banner belum diperbarui. Jalankan SQL alter table terlebih dahulu.');
            redirect('admin/banner_product/edit_banner_product/'. $id);
        }

        $error = '';
        $banner = $this->_banner_payload_from_post($error);

        if ($banner === FALSE)
        {
            $this->session->set_flashdata('edit_banner_product_error', $error);
            redirect('admin/banner_product/edit_banner_product/'. $id);
        }

        $new_file = NULL;

        if (isset($_FILES['picture']) && $_FILES['picture']['error'] != UPLOAD_ERR_NO_FILE)
        {
            $config = $this->_banner_upload_config();
            $this->load->library('upload', $config);

            if ($_FILES['picture']['error'] != UPLOAD_ERR_OK)
            {
                $this->session->set_flashdata('edit_banner_product_error', $this->_upload_error_message($_FILES['picture']['error']));
                redirect('admin/banner_product/edit_banner_product/'. $id);
            }

            if ( ! $this->upload->do_upload('picture'))
            {
                $this->session->set_flashdata('edit_banner_product_error', strip_tags($this->upload->display_errors()));
                redirect('admin/banner_product/edit_banner_product/'. $id);
            }

            $upload_data = $this->upload->data();
            $new_file = $upload_data['file_name'];
            $banner['banner_image'] = $new_file;
        }

        if ( ! $this->product->edit_banner_product($id, $banner))
        {
            if ($new_file)
            {
                $this->_delete_banner_file($new_file);
            }

            $this->session->set_flashdata('edit_banner_product_error', 'Banner gagal diperbarui.');
            redirect('admin/banner_product/edit_banner_product/'. $id);
        }

        if ($new_file && ! empty($current->banner_image) && $current->banner_image !== $new_file)
        {
            $this->_delete_banner_file($current->banner_image);
        }

        $this->session->set_flashdata('banner_product_flash', 'Banner produk berhasil diperbarui!');
        redirect('admin/banner_product');
    }

    public function update_display_settings()
    {
        if ( ! $this->product->is_banner_product_flexible_ready())
        {
            $this->session->set_flashdata('banner_product_error', 'Struktur database banner belum diperbarui. Jalankan SQL alter table terlebih dahulu.');
            redirect('admin/banner_product');
        }

        $orders = $this->input->post('display_order');
        $active_ids = $this->input->post('is_active');

        if ( ! is_array($orders))
        {
            $this->session->set_flashdata('banner_product_error', 'Tidak ada setting urutan banner yang dikirim.');
            redirect('admin/banner_product');
        }

        $active_ids = is_array($active_ids) ? array_map('intval', $active_ids) : array();
        $active_ids = array_values(array_unique($active_ids));

        if (count($active_ids) > 3)
        {
            $this->session->set_flashdata('banner_product_error', 'Maksimal hanya 3 banner yang boleh aktif tampil.');
            redirect('admin/banner_product');
        }

        $settings = array();

        foreach ($orders as $id => $order)
        {
            $id = (int) $id;

            if ($id < 1 || ! $this->product->banner_data($id))
            {
                continue;
            }

            $settings[$id] = array(
                'display_order' => max(1, (int) $order),
                'is_active' => in_array($id, $active_ids, TRUE) ? 1 : 0
            );
        }

        if (empty($settings) || ! $this->product->update_banner_display_settings($settings))
        {
            $this->session->set_flashdata('banner_product_error', 'Setting tampilan banner gagal disimpan.');
            redirect('admin/banner_product');
        }

        $this->session->set_flashdata('banner_product_flash', 'Setting urutan dan banner aktif berhasil disimpan.');
        redirect('admin/banner_product');
    }

    private function _banner_upload_config()
    {
        $config['upload_path'] = './assets/uploads/banner_product/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        if ( ! is_dir($config['upload_path']))
        {
            mkdir($config['upload_path'], 0755, TRUE);
        }

        if ( ! is_dir($config['upload_path']) || ! is_writable($config['upload_path']))
        {
            $this->session->set_flashdata('add_new_product_error', 'Folder upload banner tidak dapat ditulis.');
            redirect('admin/banner_product/add_new_banner_product');
        }

        return $config;
    }

    private function _banner_payload_from_post(&$error)
    {
        $title = trim((string) $this->input->post('banner_title', TRUE));
        $redirect_type = trim((string) $this->input->post('redirect_type', TRUE));
        $product_id = trim((string) $this->input->post('product_id', TRUE));
        $category_id = trim((string) $this->input->post('redirect_category_id', TRUE));
        $redirect_url = trim((string) $this->input->post('redirect_url', TRUE));
        $display_order = trim((string) $this->input->post('display_order', TRUE));
        $is_active = $this->input->post('is_active') ? 1 : 0;
        $id = $this->input->post('id');
        $id = $id !== NULL && is_numeric($id) ? (int) $id : NULL;

        if ($title === '')
        {
            $error = 'Title banner wajib diisi manual.';
            return FALSE;
        }

        if ( ! in_array($redirect_type, array('product', 'category', 'custom'), TRUE))
        {
            $error = 'Tipe redirect banner tidak valid.';
            return FALSE;
        }

        $banner = array(
            'banner_title' => $title,
            'redirect_type' => $redirect_type,
            'product_id' => 0,
            'redirect_product_id' => NULL,
            'redirect_category_id' => NULL,
            'redirect_url' => NULL,
            'display_order' => $display_order !== '' && is_numeric($display_order) ? max(1, (int) $display_order) : $this->product->get_next_banner_display_order(),
            'is_active' => $is_active
        );

        if ($is_active && $this->product->count_active_banner_products($id) >= 3)
        {
            $error = 'Maksimal hanya 3 banner yang boleh aktif tampil. Nonaktifkan salah satu banner lain terlebih dahulu.';
            return FALSE;
        }

        if ($redirect_type === 'product')
        {
            if ($product_id === '' || ! is_numeric($product_id) || ! $this->product->is_product_exist($product_id))
            {
                $error = 'Pilih produk yang valid untuk redirect banner.';
                return FALSE;
            }

            $banner['product_id'] = (int) $product_id;
            $banner['redirect_product_id'] = (int) $product_id;
            return $banner;
        }

        if ($redirect_type === 'category')
        {
            if ($category_id === '' || ! is_numeric($category_id) || ! $this->product->is_category_exist($category_id))
            {
                $error = 'Pilih kategori yang valid untuk redirect banner.';
                return FALSE;
            }

            $banner['redirect_category_id'] = (int) $category_id;
            return $banner;
        }

        if ($redirect_url === '' || strlen($redirect_url) > 255 || preg_match('/[\x00-\x1F\x7F]/', $redirect_url))
        {
            $error = 'URL redirect wajib diisi dan maksimal 255 karakter.';
            return FALSE;
        }

        if (preg_match('/^(javascript|data|vbscript):/i', $redirect_url) || preg_match('/^\/\//', $redirect_url))
        {
            $error = 'URL redirect tidak aman.';
            return FALSE;
        }

        if (preg_match('/^[a-z][a-z0-9+\-.]*:\/\//i', $redirect_url))
        {
            $scheme = strtolower(parse_url($redirect_url, PHP_URL_SCHEME));

            if ( ! in_array($scheme, array('http', 'https'), TRUE) || ! filter_var($redirect_url, FILTER_VALIDATE_URL))
            {
                $error = 'URL eksternal harus memakai format http atau https yang valid.';
                return FALSE;
            }
        }

        $banner['redirect_url'] = $redirect_url;
        return $banner;
    }

    private function _delete_banner_file($file_name)
    {
        $file = './assets/uploads/banner_product/' . basename($file_name);

        if (is_file($file) && is_readable($file))
        {
            unlink($file);
        }
    }

    private function _upload_error_message($error_code)
    {
        switch ($error_code)
        {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'Ukuran gambar terlalu besar. Maksimal 2MB.';
            case UPLOAD_ERR_PARTIAL:
                return 'Upload gambar tidak lengkap. Silahkan coba lagi.';
            case UPLOAD_ERR_NO_FILE:
            case NULL:
                return 'Pilih gambar banner terlebih dahulu.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Folder sementara upload tidak tersedia di server.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Server gagal menulis file upload.';
            case UPLOAD_ERR_EXTENSION:
                return 'Upload dihentikan oleh konfigurasi server.';
            default:
                return 'Upload gambar gagal. Silahkan coba lagi.';
        }
    }

    public function delete($id) 
    {    
        $this->product->delete_banner_product($id);
        $this->session->set_flashdata('banner_product_flash', 'Banner produk berhasil dihapus.');
        
        redirect('admin/banner_product');
    }

    public function edit($id = 0)
    {
        if ( $this->product->is_product_exist($id))
        {
            $data = $this->product->product_data($id);

            $params['title'] = 'Edit '. $data->name;

            $product['flash'] = $this->session->flashdata('edit_product_flash');
            $product['product'] = $data;
            $product['categories'] = $this->product->get_all_categories();

            $this->load->view('header', $params);
            $this->load->view('products/edit_product', $product);
            $this->load->view('footer');
        }
        else
        {
            show_404();
        }
    }

    public function edit_product()
    {
        $this->form_validation->set_error_delimiters('<div class="form-error text-danger font-weight-bold">', '</div>');

        $this->form_validation->set_rules('name', 'Nama produk', 'trim|required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('price', 'Harga produk', 'trim|required');
        $this->form_validation->set_rules('stock', 'Stok barang', 'required|numeric');
        $this->form_validation->set_rules('unit', 'Satuan barang', 'required');
        $this->form_validation->set_rules('description', 'Deskripsi produk', 'max_length[512]');

        if ($this->form_validation->run() == FALSE)
        {
            $id = $this->input->post('id');
            $this->edit($id);
        }
        else
        {
            $id = $this->input->post('id');
            $data = $this->product->product_data($id);
            $current_picture = $data->picture_name;

            $name = $this->input->post('name');
            $category_id = $this->input->post('category_id');
            $price = $this->input->post('price');
            $price_2 = $this->input->post('price_2');
            $price_3 = $this->input->post('price_3');
            $discount = $this->input->post('price_discount');
            $stock = $this->input->post('stock');
            $unit = $this->input->post('unit');
            $desc = $this->input->post('desc');
            $available = $this->input->post('is_available');
            $date = date('Y-m-d H:i:s');

            $config['upload_path'] = './assets/uploads/products/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if ( isset($_FILES['picture']) && @$_FILES['picture']['error'] == '0')
            {
                if ( $this->upload->do_upload('picture'))
                {
                    $upload_data = $this->upload->data();
                    $new_file_name = $upload_data['file_name'];

                    if ( $this->product->is_product_have_image($id))
                    {
                        $file = './assets/uploads/products/'. $current_picture;

                        $file_name = $new_file_name;
                        unlink($file);
                    }
                    else
                    {
                        $file_name = $new_file_name;
                    }
                }
                else
                {
                    show_error($this->upload->display_errors());
                }
            }
            else
            {
                $file_name = ($this->product->is_product_have_image($id)) ? $current_picture : NULL;
            }

            $product['category_id'] = $category_id;
            $product['name'] = $name;
            $product['description'] = $desc;
            $product['price'] = $price;
            $product['price_2'] = $price_2;
            $product['price_3'] = $price_3;
            $product['current_discount'] = $discount;
            $product['stock'] = $stock;
            $product['product_unit'] = $unit;
            $product['picture_name'] = $file_name;
            $product['is_available'] = $available;

            $this->product->edit_product($id, $product);
            $this->session->set_flashdata('edit_product_flash', 'Produk berhasil diperbarui!');

            redirect('admin/products/view/'. $id);
        }
    }

    public function product_api()
    {
        $action = $this->input->get('action');

        switch ($action)
        {
            case 'delete_image' :
                $id = $this->input->post('id');
                $data = $this->product->product_data($id);
                $picture_name = $data->picture_name;
                $file = './assets/uploads/products/'. $picture_name;

                if ( file_exists($file) && is_readable($file) && unlink($file))
                {
                    $this->product->delete_product_image($id);
                    $response = array('code' => 204, 'message' => 'Gambar berhasil dihapus');
                }
                else
                {
                    $response = array('code' => 200, 'message' => 'Terjadi kesalahan sata menghapus gambar');
                }
            break;
            case 'delete_product' :
                $id = $this->input->post('id');
                $data = $this->product->product_data($id);
                $picture = $data->picture_name;
                $file = './assets/uploads/products/'. $picture;

                $this->product->delete_product($id);

                if ( file_exists($file) && is_readable($file))
                {
                    unlink($file);
                }

                $response = array('code' => 204);
            break;
        }

        $response = json_encode($response);
        $this->output->set_content_type('application/json')
            ->set_output($response);
    }

    public function view($id = 0)
    {
        if ( $this->product->is_product_exist($id))
        {
            $data = $this->product->product_data($id);

            $params['title'] = $data->name .' | SKU '. $data->sku;

            $product['product'] = $data;
            $product['flash'] = $this->session->flashdata('product_flash');
            $product['orders'] = $this->order->product_ordered($id);

            $this->load->view('header', $params);
            $this->load->view('products/view', $product);
            $this->load->view('footer');
        }
        else
        {
            show_404();
        }
    }

    public function category()
    {
        $params['title'] = 'Kelola Kategori Produk';

        $categories['categories'] = $this->product->get_all_categories();

        $this->load->view('header', $params);
        $this->load->view('products/category', $categories);
        $this->load->view('footer');
    }

    public function category_api()
    {
        $action = $this->input->get('action');

        switch ($action) {
            case 'list' :
                $categories['data'] = $this->product->get_all_categories();
                $response = $categories;
            break;
            case 'view_data' :
                $id = $this->input->get('id');

                $data['data'] = $this->product->category_data($id);
                $response = $data;
            break;
            case 'add_category' :
                $name = $this->input->post('name');

                $this->product->add_category($name);
                $categories['data'] = $this->product->get_all_categories();
                $response = $categories;
            break;
            case 'delete_category' :
                $id = $this->input->post('id');

                $this->product->delete_category($id);
                $response = array('code' => 204, 'message' => 'Kategori berhasil dihapus!');
            break;
            case 'edit_category' :
                $id = $this->input->post('id');
                $name = $this->input->post('name');

                $this->product->edit_category($id, $name);
                $response = array('code' => 201, 'message' => 'Kategori berhasil diperbarui');
            break;
        }

        $response = json_encode($response);
        $this->output->set_content_type('application/json')
            ->set_output($response);
    }

    public function coupons()
    {
        $params['title'] = 'Kelola Kupon Belanja';

        $this->load->view('header', $params);
        $this->load->view('products/coupons');
        $this->load->view('footer');
    }

    public function _get_coupon_list()
    {
        $coupons = $this->product->get_all_coupons();
        $n = 0;

        foreach ($coupons as $coupon)
        {
            $coupons[$n]->credit = 'Rp '. format_rupiah($coupon->credit);
            $coupons[$n]->start_date = get_formatted_date($coupon->start_date);
            $coupons[$n]->is_active = ($coupon->is_active == 1) ? ((strtotime($coupon->expired_date) < time()) ? 'Sudah kadaluarsa' : 'Masih berlaku') : 'Tidak aktif';
            $coupons[$n]->expired_date = get_formatted_date($coupon->expired_date);

            $n++;
        }

        return $coupons;
    }

    public function coupon_api()
    {
        $action = $this->input->get('action');

        switch ($action) {
            case 'coupon_list' :
                $coupons['data'] = $this->_get_coupon_list();

                $response = $coupons;
            break;
            case 'view_data' :
                $id = $this->input->get('id');

                $data['data'] = $this->product->coupon_data($id);
                $response = $data;
            break;
            case 'add_coupon' :
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $credit = $this->input->post('credit');
                $start = $this->input->post('start_date');
                $end = $this->input->post('expired_date');

                $coupon = array(
                    'name' => $name,
                    'code' => $code,
                    'credit' => $credit,
                    'start_date' => date('Y-m-d', strtotime($start)),
                    'expired_date' => date('Y-m-d', strtotime($end))
                );

                $this->product->add_coupon($coupon);
                $coupons['data'] = $this->_get_coupon_list();

                $response = $coupons;
            break;
            case 'delete_coupon' :
                $id = $this->input->post('id');

                $this->product->delete_coupon($id);
                $response = array('code' => 204, 'message' => 'Kupon berhasil dihapus!');
            break;
            case 'edit_coupon' :
                $id = $this->input->post('id');
                $name = $this->input->post('name');
                $code = $this->input->post('code');
                $credit = $this->input->post('credit');
                $start = $this->input->post('start_date');
                $end = $this->input->post('expired_date');
                $active = $this->input->post('is_active');

                $coupon = array(
                    'name' => $name,
                    'code' => $code,
                    'credit' => $credit,
                    'start_date' => date('Y-m-d', strtotime($start)),
                    'expired_date' => date('Y-m-d', strtotime($end)),
                    'is_active' => $active
                );

                $this->product->edit_coupon($id, $coupon);
                $response = array('code' => 201, 'message' => 'Kupon berhasil diperbarui');
            break;
        }

        $response = json_encode($response);
        $this->output->set_content_type('application/json')
            ->set_output($response);
    }

    public function promo()
    {
        $params['products'] = $this->product->get_list_products();
        $params['title'] = 'Kelola Kupon Belanja';

        $this->load->view('header', $params);
        $this->load->view('products/promo');
        $this->load->view('footer');
    }

    public function _get_promo_list()
    {
        $promos = $this->product->get_all_promo();
        $n = 0;

        foreach ($promos as $promo)
        {
            $promos[$n]->credit = 'Rp '. format_rupiah($promo->credit);
            $promos[$n]->product_name = $promo->product_name;
            $promos[$n]->start_date = get_formatted_date($promo->start_date);
            $promos[$n]->is_active = ($promo->is_active == 1) ? ((strtotime($promo->expired_date) < time()) ? 'Sudah kadaluarsa' : 'Masih berlaku') : 'Tidak aktif';
            $promos[$n]->expired_date = get_formatted_date($promo->expired_date);

            $n++;
        }

        return $promos;
    }

    public function promo_api()
    {
        $action = $this->input->get('action');

        switch ($action) {
            case 'promo_list' :
                $promos['data'] = $this->_get_promo_list();

                $response = $promos;
            break;
            case 'view_data' :
                $id = $this->input->get('id');

                $data['data'] = $this->product->promo_data($id);
                $response = $data;
            break;
            case 'add_promo' :
                $product_id = $this->input->post('product_id');
                $credit = $this->input->post('credit');
                $start = $this->input->post('start_date');
                $end = $this->input->post('expired_date');

                $promo = array(
                    'product_id' => $product_id,
                    'credit' => $credit,
                    'start_date' => date('Y-m-d', strtotime($start)),
                    'expired_date' => date('Y-m-d', strtotime($end))
                );

                $this->product->add_promo($promo);
                $promos['data'] = $this->_get_promo_list();

                $response = $promos;
            break;
            case 'delete_promo' :
                $id = $this->input->post('id');

                $this->product->delete_promo($id);
                $response = array('code' => 204, 'message' => 'Kupon berhasil dihapus!');
            break;
            case 'edit_promo' :
                $id = $this->input->post('id');
                $product_id = $this->input->post('product_id');
                $credit = $this->input->post('credit');
                $start = $this->input->post('start_date');
                $end = $this->input->post('expired_date');
                $active = $this->input->post('is_active');

                $promo = array(
                    'product_id' => $product_id,
                    'credit' => $credit,
                    'start_date' => date('Y-m-d', strtotime($start)),
                    'expired_date' => date('Y-m-d', strtotime($end)),
                    'is_active' => $active
                );

                $this->product->edit_promo($id, $promo);
                $response = array('code' => 201, 'message' => 'Promo berhasil diperbarui');
            break;
        }

        $response = json_encode($response);
        $this->output->set_content_type('application/json')
            ->set_output($response);
    }
}
