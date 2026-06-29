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

$route['default_controller']                = 'customer/home';
$route['404_override']                      = 'errors/not_found';
$route['translate_uri_dashes']              = FALSE;

// Mobile API v1
$route['api/v1']                            = 'api/mobile/index';
$route['api/v1/auth/register']              = 'api/mobile/register';
$route['api/v1/auth/login']                 = 'api/mobile/login';
$route['api/v1/auth/logout']                = 'api/mobile/logout';
$route['api/v1/profile']                    = 'api/mobile/profile';
$route['api/v1/banners']                    = 'api/mobile/banners';
$route['api/v1/categories']                 = 'api/mobile/categories';
$route['api/v1/products']                   = 'api/mobile/products';
$route['api/v1/products/(:num)']            = 'api/mobile/product/$1';
$route['api/v1/cart']                       = 'api/mobile/cart';
$route['api/v1/cart/(:num)']                = 'api/mobile/cart_item/$1';
$route['api/v1/shipping/provinces']         = 'api/mobile/shipping_provinces';
$route['api/v1/shipping/cities']            = 'api/mobile/shipping_cities';
$route['api/v1/shipping/districts']         = 'api/mobile/shipping_districts';
$route['api/v1/shipping/quotes']            = 'api/mobile/shipping_quotes';
$route['api/v1/orders']                     = 'api/mobile/orders';
$route['api/v1/orders/checkout']            = 'api/mobile/checkout';
$route['api/v1/orders/(:num)']              = 'api/mobile/order/$1';
$route['api/v1/orders/(:num)/cancel']       = 'api/mobile/cancel_order/$1';
$route['api/v1/messages']                   = 'api/mobile/messages';

$route['error/no-internet']                 = 'errors/no_internet';
$route['error/timeout']                     = 'errors/timeout';
$route['error/app']                         = 'errors/app';

$route['dashboard_admin']                   = 'admin/dashboard';
$route['admin']                             = 'admin/admin';
$route['admin/categories']                  = 'admin/products/category';

$route['send_admin_message']                = 'admin/messages/send';
$route['admin/messages/fetch']              = 'admin/messages/fetch';
$route['generate_va']                       = 'admin/customers/generate_va';

$route['login']                             = 'auth/login';
$route['logout']                            = 'auth/logout';
$route['register']                          = 'auth/register';

$route['policys']                           = 'customer/terms/policy_privacy';
$route['privacy-policy']                    = 'customer/terms/policy_privacy';
$route['customer/terms/policy_privacy']     = 'customer/terms/policy_privacy';

$route['cartongkir']                        = 'customer/addons/addoncart/cart';

$route['customer']                          = 'customer/customer';
$route['profile']                           = 'customer/profile';
$route['cus_edit_customer/(:any)']          = 'customer/profile/cus_editdata/$1';
$route['edit_profile_cust/(:any)']          = 'customer/profile/cus_edit_profile/$1';
$route['sv_alamatcus']                      = 'customer/profile/';
$route['change_alamat_customer_profile']    = 'customer/profile/change_alamat_asal';
$route['change_password']                   = 'customer/profile/change_password';
$route['message']                           = 'customer/message';
$route['message/fetch']                     = 'customer/message/fetch';
$route['send_message']                      = 'customer/message/send';
$route['count_unread_messages']             = 'customer/message/count_unread';
$route['home']                              = 'customer/home';
$route['contact']                           = 'customer/home/contact';
$route['contact/send']                      = 'customer/home/send_contact';
$route['shop']                              = 'customer/shop';
$route['order_history']                     = 'customer/orders';
$route['invoice']                           = 'customer/invoice';
$route['order_view/(:num)']                 = 'customer/orders/view/$1';
$route['category']                          = 'customer/product/all_categories';
$route['category/(:num)/(:any)/(:num)']     = 'customer/product/products_in_category/$1/$2/$3';
$route['category/(:num)/(:any)']            = 'customer/product/products_in_category/$1/$2';
$route['all_products/(:num)']               = 'customer/product/all_products/$1';
$route['all_products']                      = 'customer/product/all_products';
$route['search']                            = 'customer/home/search';
$route['promo']                             = 'customer/product/promo';
$route['product/(:num)/(:any)']             = 'customer/product/product/$1/$2';
$route['cart']                              = 'customer/shop/cart';
$route['cekongkir']                         = 'customer/shop/cekongkir';
$route['cekongkir_new']                     = 'customer/shop/district_calculate_cost';
$route['ongkir']                            = 'customer/shop/ongkir';

$route['cart_api']                          = 'customer/shop/cart_api';
$route['checkout']                          = 'customer/shop/checkout';
$route['checkout_submit']                   = 'customer/shop/checkout/order';

// payment
$route['admin/apibriva']                    = 'admin/api_payment_briva';
$route['admin/brivaws']                     = 'admin/api_payment_briva';
$route['createva']                          = 'admin/api_payment_briva/createVa';
$route['createva/preview']                  = 'admin/api_payment_briva/preview_briva';

// COBA
$route['readonlychange']                    = 'customer/profile/toggle_readonly';
$route['inputlocation']                     = 'customer/profile/inputlocation';
$route['get_provinces']                     = 'customer/profile/get_provinces';

$route['test_api_payment']                  = 'customer/shop/test_api_payment';
$route['test_status_va']                    = 'customer/orders/test_status_va';

$route['get_time_left/(:any)']              = 'customer/orders/get_time_left/$1';
$route['check_payment_status/(:any)/(:any)'] = 'customer/orders/check_payment_status/$1/$2';

// RAJAONGKIR 
$route['rajaongkir']                        = 'customer/rajaongkir';
$route['rajaongkir/get_provinces']          = 'customer/rajaongkir/get_provinces';
$route['rajaongkir/get_cities']             = 'customer/rajaongkir/get_cities';
$route['rajaongkir/get_districts']          = 'customer/rajaongkir/get_districts';
$route['rajaongkir/get_subdistricts']       = 'customer/rajaongkir/get_subdistricts';
$route['rajaongkir/get_shipping_cost']      = 'customer/rajaongkir/get_shipping_cost';

$route['admin/ongkir']                      = 'admin/Ongkir';
$route['Ongkir/province']                   = 'admin/Ongkir/province';
$route['Ongkir/city/(:num)']                = 'admin/Ongkir/city/$1';
$route['Ongkir/district/(:num)']            = 'admin/Ongkir/district/$1';
$route['Ongkir/sub_district/(:num)']        = 'admin/Ongkir/sub_district/$1';
$route['Ongkir/cost']                       = 'admin/Ongkir/cost';

$route['ongkirdev']                         = 'admin/Admin/dev_ongkir';
$route['admin/ajax_provinces']              = 'admin/Admin/ajax_provinces';
$route['admin/ajax_cities']                 = 'admin/Admin/ajax_cities';
$route['admin/ajax_subdistricts']           = 'admin/Admin/ajax_subdistricts';
$route['rajaongkir/ajax_hitung_ongkir']     = 'admin/Admin/ajax_hitung_ongkir';
