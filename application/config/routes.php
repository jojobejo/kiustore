<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'customer/home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['dashboard_admin'] = 'admin/dashboard';
$route['admin'] = 'admin/admin';

$route['send_admin_message'] = 'admin/messages/send';
$route['generate_va'] = 'admin/customers/generate_va';

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['register'] = 'auth/register';
$route['policy'] = 'pages/policy';

$route['cartongkir'] = 'customer/addons/addoncart/cart';

$route['customer'] = 'customer/customer';
$route['profile'] = 'customer/profile';
$route['cus_edit_customer/(:any)'] = 'customer/profile/cus_editdata/$1';
$route['sv_alamatcus'] = 'customer/profile/';
$route['change_alamat_customer_profile'] = 'customer/profile/change_alamat_asal';
$route['message'] = 'customer/message';
$route['send_message'] = 'customer/message/send';
$route['count_unread_messages'] = 'customer/message/count_unread';
$route['home'] = 'customer/home';
$route['shop'] = 'customer/shop';
$route['order_history'] = 'customer/orders';
$route['invoice'] = 'customer/invoice';
$route['order_view/(:num)'] = 'customer/orders/view/$1';
$route['category'] = 'customer/product/all_categories';
$route['category/(:num)/(:any)'] = 'customer/product/products_in_category/$1/$2';
$route['all_products'] = 'customer/product/all_products';
$route['search'] = 'customer/home/search';
$route['promo'] = 'customer/product/promo';
$route['product/(:num)/(:any)'] = 'customer/product/product/$1/$2';
$route['cart'] = 'customer/shop/cart';
$route['cekongkir'] = 'customer/shop/cekongkir';
$route['ongkir'] = 'customer/shop/ongkir';

$route['cart_api'] = 'customer/shop/cart_api';
$route['checkout'] = 'customer/shop/checkout';
$route['checkout_submit'] = 'customer/shop/checkout/order';

$route['admin/ongkir'] = 'admin/ongkir';
$route['admin/apibriva'] = 'admin/api_payment_briva';

// COBA
$route['readonlychange'] = 'customer/profile/toggle_readonly';
$route['inputlocation'] = 'customer/profile/inputlocation';
$route['get_provinces'] = 'customer/profile/get_provinces';

// RAJAONGKIR 
$route['rajaongkir'] = 'customer/rajaongkir';
$route['rajaongkir/get_provinces'] = 'customer/rajaongkir/get_provinces';
$route['rajaongkir/get_cities'] = 'customer/rajaongkir/get_cities';
$route['rajaongkir/get_subdistricts'] = 'customer/rajaongkir/get_subdistricts';
$route['rajaongkir/get_shipping_cost'] = 'customer/rajaongkir/get_shipping_cost';

// BRI VA
$route['admin/brivaws'] = 'admin/brivawsapi';

$route['get_token'] = 'admin/Brivawsapi/get_token';
$route['create_va'] = 'admin/Brivawsapi/create_va';
