<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends CI_Controller
{
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
        $params['title'] = 'Kelola Produk ' . get_store_name();

        $config['base_url'] = site_url('admin/products/index');
        $config['total_rows'] = $this->product->count_all_products();
        $config['per_page'] = 16;
        $config['uri_segment'] = 4;
        $choice = $config['total_rows'] / $config['per_page'];
        $config['num_links'] = floor($choice);

        $config['first_link']       = '«';
        $config['last_link']        = '»';
        $config['next_link']        = '›';
        $config['prev_link']        = '‹';
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

        $products['all_products'] = $this->product->get_all_products($config['per_page'], $page);
        $products['price'] = $this->product->get_confirmed_price($config['per_page'], $page);
        $products['noprice'] = $this->product->get_not_confirmed_price($config['per_page'], $page);
        $products['pagination'] = $this->pagination->create_links();

        $this->load->view('header', $params);
        $this->load->view('products/products', $products);
        $this->load->view('footer');
    }

    public function dataProduk()
    {
        $products = $this->product->get_confirmed_price();
        $n = 0;
        foreach ($products as $product) {
            //          $products[$n]->order_status = get_order_status($order->order_status, $order->payment_method);
            //        $products[$n]->payment_method = get_payment_method($order->payment_method);
            //          $products[$n]->order_date = get_formatted_date($order->order_date);;
            $products[$n]->price = format_rupiah($product->price);
            $products[$n]->price_2 = format_rupiah($product->price_2);
            $products[$n]->price_3 = format_rupiah($product->price_3);

            $n++;
        }
        $products['data'] = $products;
        $response = $products;
    }

    public function search()
    {
        $query = $this->input->get('search_query');
        $query = html_escape($query);

        $params['title'] = 'Cari "' . $query . '"';
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

    public function add_new_product()
    {
        $params['title'] = 'Tambah Produk Baru';

        $product['flash'] = $this->session->flashdata('add_new_product_flash');
        $product['categories'] = $this->product->get_all_categories();

        $this->load->view('header', $params);
        $this->load->view('products/add_new_product', $product);
        $this->load->view('footer');
    }

    public function add_product()
    {
        $this->form_validation->set_error_delimiters('<div class="form-error text-danger font-weight-bold">', '</div>');

        $this->form_validation->set_rules('name', 'Nama produk', 'trim|required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('price', 'Harga produk', 'trim|required');
        $this->form_validation->set_rules('stock', 'Stok barang', 'required|numeric');
        $this->form_validation->set_rules('product_unit_1', 'Satuan barang', 'required');
        $this->form_validation->set_rules('description', 'Deskripsi produk');
        $this->form_validation->set_rules('weight_product', 'Berat Satuan Produk', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add_new_product();
        } else {
            $name = $this->input->post('name');
            $description = $this->input->post('description');
            $category_id = $this->input->post('category_id');
            $price = $this->input->post('price');
            $price_2 = $this->input->post('price_2');
            $price_3 = $this->input->post('price_3');
            $stock = $this->input->post('stock');
            $product_unit_1 = $this->input->post('product_unit_1');
            $product_unit_2 = $this->input->post('product_unit_2');
            $product_unit_value = $this->input->post('product_unit_value');
            $product_type = $this->input->post('product_type');
            $weight_product = $this->input->post('weight_product');
            $desc = $this->input->post('desc');
            $date = date('Y-m-d H:i:s');

            $config['upload_path'] = './assets/uploads/products/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if (isset($_FILES['picture']) && @$_FILES['picture']['error'] == '0') {
                if (!$this->upload->do_upload('picture')) {
                    $error = array('error' => $this->upload->display_errors());

                    show_error($error);
                } else {
                    $upload_data = $this->upload->data();
                    $file_name = $upload_data['file_name'];
                }
            }

            $category_data = $this->product->category_data($category_id);
            $category_name = $category_data->name;

            $sku = create_product_sku($name, $category_name, $price, $price_2, $price_3, $stock);

            $product['category_id'] = $category_id;
            $product['sku'] = $sku;
            $product['name'] = $name;
            $product['description'] = $description;
            $product['price'] = $price;
            $product['price_2'] = $price_2;
            $product['price_3'] = $price_3;
            $product['stock'] = $stock;
            $product['product_unit_1'] = $product_unit_1;
            $product['product_unit_2'] = $product_unit_2;
            $product['product_unit_value'] = $product_unit_value;
            $product['product_type'] = $product_type;
            $product['product_unit_weight'] = $weight_product;
            $product['picture_name'] = $file_name;
            $product['add_date'] = $date;

            $this->product->add_new_product($product);
            $this->session->set_flashdata('add_new_product_flash', 'Produk baru berhasil ditambahkan!');

            redirect('admin/products/add_new_product');
        }
    }

    public function edit($id = 0)
    {
        if ($this->product->is_product_exist($id)) {
            $data = $this->product->product_data($id);

            $params['title'] = 'Edit ' . $data->name;

            $product['flash'] = $this->session->flashdata('edit_product_flash');
            $product['product'] = $data;
            $product['categories'] = $this->product->get_all_categories();

            $this->load->view('header', $params);
            $this->load->view('products/edit_product', $product);
            $this->load->view('footer');
        } else {
            show_404();
        }
    }

    public function edit_product()
    {
        $this->form_validation->set_error_delimiters('<div class="form-error text-danger font-weight-bold">', '</div>');

        $this->form_validation->set_rules('name', 'Nama produk', 'trim|required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('price', 'Harga produk', 'trim|required');
        $this->form_validation->set_rules('stock', 'Stok barang', 'required|numeric');
        $this->form_validation->set_rules('description', 'Deskripsi produk');
        $this->form_validation->set_rules('weight_product', 'Berat Satuan Produk', 'required');

        if ($this->form_validation->run() == FALSE) {
            $id = $this->input->post('id');
            $this->edit($id);
        } else {
            $id = $this->input->post('id');
            $data = $this->product->product_data($id);
            $current_picture = $data->picture_name;

            $name = $this->input->post('name');
            $description = $this->input->post('description');
            $category_id = $this->input->post('category_id');
            $price = $this->input->post('price');
            $price_2 = $this->input->post('price_2');
            $price_3 = $this->input->post('price_3');
            $discount = $this->input->post('price_discount');
            $stock = $this->input->post('stock');
            $product_unit_1 = $this->input->post('product_unit_1');
            $product_unit_2 = $this->input->post('product_unit_2');
            $product_unit_value = $this->input->post('product_unit_value');
            $weight_product = $this->input->post('weight_product');
            $product_type = $this->input->post('product_type');
            $desc = $this->input->post('desc');
            $available = $this->input->post('is_available');
            $date = date('Y-m-d H:i:s');

            $config['upload_path'] = './assets/uploads/products/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if (isset($_FILES['picture']) && @$_FILES['picture']['error'] == '0') {
                if ($this->upload->do_upload('picture')) {
                    $upload_data = $this->upload->data();
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = './assets/uploads/products/' . $upload_data['file_name'];
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = FALSE;
                    $config['quality'] = '50%';
                    $config['width'] = 500;
                    $config['height'] = 500;
                    $new_file_name = $upload_data['file_name'];
                    $config['new_image'] = './assets/uploads/products/' . $new_file_name;
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();

                    if ($this->product->is_product_have_image($id)) {
                        $file = './assets/uploads/products/' . $current_picture;

                        $file_name = $new_file_name;
                        unlink($file);
                    } else {
                        $file_name = $new_file_name;
                    }
                } else {
                    show_error($this->upload->display_errors());
                }
            } else {
                $file_name = ($this->product->is_product_have_image($id)) ? $current_picture : NULL;
            }

            $product['category_id'] = $category_id;
            $product['name'] = $name;
            $product['description'] = $description;
            $product['price'] = $price;
            $product['price_2'] = $price_2;
            $product['price_3'] = $price_3;
            $product['current_discount'] = $discount;
            $product['stock'] = $stock;
            $product['product_unit_1'] = $product_unit_1;
            $product['product_unit_2'] = $product_unit_2;
            $product['product_unit_value'] = $product_unit_value;
            $product['product_unit_weight'] = $weight_product;
            $product['product_type'] = $product_type;
            $product['picture_name'] = $file_name;
            $product['is_available'] = $available;

            $this->product->edit_product($id, $product);
            $this->session->set_flashdata('edit_product_flash', 'Produk berhasil diperbarui!');

            redirect('admin/products/view/' . $id);
        }
    }

    public function product_api()
    {
        $action = $this->input->get('action');

        switch ($action) {
            case 'delete_image':
                $id = $this->input->post('id');
                $data = $this->product->product_data($id);
                $picture_name = $data->picture_name;
                $file = './assets/uploads/products/' . $picture_name;

                if (file_exists($file) && is_readable($file) && unlink($file)) {
                    $this->product->delete_product_image($id);
                    $response = array('code' => 204, 'message' => 'Gambar berhasil dihapus');
                } else {
                    $response = array('code' => 200, 'message' => 'Terjadi kesalahan sata menghapus gambar');
                }
                break;
            case 'delete_product':
                $id = $this->input->post('id');
                $data = $this->product->product_data($id);
                $picture = $data->picture_name;
                $file = './assets/uploads/products/' . $picture;

                $this->product->delete_product($id);

                if (file_exists($file) && is_readable($file)) {
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
        if ($this->product->is_product_exist($id)) {
            $data = $this->product->product_data($id);

            $params['title'] = $data->name . ' | SKU ' . $data->sku;

            $product['product'] = $data;
            $product['flash'] = $this->session->flashdata('product_flash');
            $product['orders'] = $this->order->product_ordered($id);

            $this->load->view('header', $params);
            $this->load->view('products/view', $product);
            $this->load->view('footer');
        } else {
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
            case 'list':
                $categories['data'] = $this->product->get_all_categories();
                $response = $categories;
                break;
            case 'view_data':
                $id = $this->input->get('id');

                $data['data'] = $this->product->category_data($id);
                $response = $data;
                break;
            case 'add_category':
                $name = $this->input->post('name');

                $this->product->add_category($name);
                $categories['data'] = $this->product->get_all_categories();
                $response = $categories;
                break;
            case 'delete_category':
                $id = $this->input->post('id');

                $this->product->delete_category($id);
                $response = array('code' => 204, 'message' => 'Kategori berhasil dihapus!');
                break;
            case 'edit_category':
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

        foreach ($coupons as $coupon) {
            $coupons[$n]->credit = 'Rp ' . format_rupiah($coupon->credit);
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
            case 'coupon_list':
                $coupons['data'] = $this->_get_coupon_list();

                $response = $coupons;
                break;
            case 'view_data':
                $id = $this->input->get('id');

                $data['data'] = $this->product->coupon_data($id);
                $response = $data;
                break;
            case 'add_coupon':
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
            case 'delete_coupon':
                $id = $this->input->post('id');

                $this->product->delete_coupon($id);
                $response = array('code' => 204, 'message' => 'Kupon berhasil dihapus!');
                break;
            case 'edit_coupon':
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

    public function api($action = '')
    {
        switch ($action) {
            case 'semua':
                $products = $this->product->get_all_products();
                $n = 0;
                foreach ($products as $product) {
                    $products[$n]->price = format_rupiah($product->price);
                    $products[$n]->price_2 = format_rupiah($product->price_2);
                    $products[$n]->price_3 = format_rupiah($product->price_3);

                    $n++;
                }
                $products['data'] = $products;
                $response = $products;
                break;

            case 'ada_harga':
                $products = $this->product->get_confirmed_price();
                $n = 0;
                foreach ($products as $product) {
                    $products[$n]->price = format_rupiah($product->price);
                    $products[$n]->price_2 = format_rupiah($product->price_2);
                    $products[$n]->price_3 = format_rupiah($product->price_3);

                    $n++;
                }
                $products['data'] = $products;
                $response = $products;
                break;

            case 'belumada_harga':
                $products = $this->product->get_not_confirmed_price();
                $n = 0;
                foreach ($products as $product) {
                    $products[$n]->price = format_rupiah($product->price);
                    $products[$n]->price_2 = format_rupiah($product->price_2);
                    $products[$n]->price_3 = format_rupiah($product->price_3);

                    $n++;
                }
                $products['data'] = $products;
                $response = $products;
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

        foreach ($promos as $promo) {
            $promos[$n]->credit = 'Rp ' . format_rupiah($promo->credit);
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
            case 'promo_list':
                $promos['data'] = $this->_get_promo_list();

                $response = $promos;
                break;
            case 'view_data':
                $id = $this->input->get('id');

                $data['data'] = $this->product->promo_data($id);
                $response = $data;
                break;
            case 'add_promo':
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
            case 'delete_promo':
                $id = $this->input->post('id');

                $this->product->delete_promo($id);
                $response = array('code' => 204, 'message' => 'Kupon berhasil dihapus!');
                break;
            case 'edit_promo':
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
