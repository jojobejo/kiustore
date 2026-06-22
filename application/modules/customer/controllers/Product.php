<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'product_model' => 'product'
        ));
    }

    public function all_categories()
    {

        $params['title'] = 'Kategori ';
        $products['categories'] = $this->product->get_all_categories();

        $this->load->view('header', $params);
        $this->load->view('shop/category_all', $products);
        $this->load->view('footer');
    }

    public function all_products($offset = 0)
    {
        $params['title'] = 'Semua Produk';
        $per_page = 10;

        $config['base_url'] = site_url('all_products');
        $config['total_rows'] = $this->product->count_all_products();
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 2;
        $config['num_links'] = 2;
        $config['first_link'] = '&laquo;';
        $config['last_link'] = '&raquo;';
        $config['next_link'] = '&rsaquo;';
        $config['prev_link'] = '&lsaquo;';
        $config['full_tag_open'] = '<nav class="mt-4" aria-label="Navigasi halaman produk"><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active" aria-current="page"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->load->library('pagination', $config);

        $products['products'] = $this->product->get_all_products($per_page, (int) $offset);
        $products['pagination'] = $this->pagination->create_links();

        $this->load->view('header', $params);
        $this->load->view('shop/product_all', $products);
        $this->load->view('footer');
    }

    public function promo()
    {

        $params['title'] = 'Promo Produk';
        $products['products'] = $this->product->promo_products();
        $this->load->view('header', $params);
        $this->load->view('shop/product_promo', $products);
        $this->load->view('footer');
    }

    public function product($id = 0, $sku = '')
    {
        if ($id == 0 || empty($sku)) {
            show_error('Akses tidak sah!');
        } else {
            if ($this->product->is_product_exist($id, $sku)) {
                $data = $this->product->product_data($id);

                $product['product'] = $data;
                $product['related_products'] = $this->product->related_products($data->id, $data->category_id);

                // get_header($data->name .' | '. get_settings('store_tagline'));
                // get_template_part('shop/view_single_product', $product);
                // get_footer();

                $this->load->view('header');
                $this->load->view('shop/product_detail', $product);
                $this->load->view('footer');
            } else {
                show_404();
            }
        }
    }

    public function products_in_category($id, $name, $offset = 0)
    {
        $per_page = 8;
        $category_name = urldecode($name);

        $config['base_url'] = site_url('category/' . $id . '/' . rawurlencode($category_name));
        $config['total_rows'] = $this->product->count_products_in_category($id);
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 4;
        $config['num_links'] = 2;
        $config['first_link'] = '&laquo;';
        $config['last_link'] = '&raquo;';
        $config['next_link'] = '&rsaquo;';
        $config['prev_link'] = '&lsaquo;';
        $config['full_tag_open'] = '<nav class="mt-4" aria-label="Navigasi halaman produk"><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active" aria-current="page"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->load->library('pagination', $config);

        $products['category'] = urldecode($name);
        $products['products'] = $this->product->get_products_in_category($id, $per_page, (int) $offset);
        $products['pagination'] = $this->pagination->create_links();

        $this->load->view('header');
        $this->load->view('shop/category_detail', $products);
        $this->load->view('footer');
    }

    public function search()
    {
        $query = $this->input->get('search_query');
        $query = html_escape($query);

        $products['title'] = 'Cari "' . $query . '"';
        $products['query'] = $query;

        $products['products'] = $this->product->search_product($query);

        $this->load->view('header');
        $this->load->view('search', $products);
        $this->load->view('footer');
    }

    public function search2()
    {
        $query = $this->input->get('search_query');
        $query = html_escape($query);

        $products['title'] = 'Cari "' . $query . '"';
        $products['query'] = $query;

        $products['products'] = $this->product->search_product($query);

        $this->load->view('header');
        $this->load->view('search', $products);
        $this->load->view('footer');
    }
}
