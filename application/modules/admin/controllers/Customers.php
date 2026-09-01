<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        verify_session('admin');

        $this->load->model(array(
            'customer_model' => 'customer',
            'order_model' => 'order',
            'salesman_model' => 'salesman',
            'payment_model' => 'payment',
            'admin_model' => 'admin'
        ));
        $this->load->library('form_validation');
    }
    public $api_key = "850366532701e5e36174b032cfd311e9";

    public function index()
    {
        $params['title'] = 'Customer';

        $this->load->view('header', $params);
        $this->load->view('customers/customers');
        $this->load->view('footer');
    }

    public function add_new_customer()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key:" . $this->api_key
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $customer['kota'] = array('error' => true);
        } else {
            $customer['kota'] = json_decode($response);
        }

        $params['title'] = 'Tambah Pelanggan Baru';

        $customer['flash'] = $this->session->flashdata('add_new_customer_flash');
        //  $customer['salesman'] = $this->salesman->get_all_salesman();
        $customer['admin'] = $this->customer->get_all_sales();
        // print_r($customer);exit;
        $this->load->view('header', $params);
        $this->load->view('customers/add_new_customer', $customer);
        $this->load->view('footer');
    }

    public function add_customer()
    {
        $this->form_validation->set_error_delimiters('<div class="form-error text-danger font-weight-bold">', '</div>');

        $this->form_validation->set_rules('name', 'Nama Pelanggan', 'trim|required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('nik', 'Nomor Induk Kependudukan', 'trim|required');
        $this->form_validation->set_rules('npwp', 'NPWP', 'required|numeric');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('kota', 'kota', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // print_r(validation_errors());exit;
            $this->add_new_customer();
        } else {
            $password = $this->input->post('password');
            $name = $this->input->post('name');
            $nik = $this->input->post('nik');
            $npwp = $this->input->post('npwp');
            $kota = $this->input->post('kota');
            $address = $this->input->post('address');
            $shop_name = $this->input->post('shop_name');
            $shop_address = $this->input->post('shop_address');
            $no_telp = $this->input->post('no_telp');
            $email = $this->input->post('email');
            $level = $this->input->post('level');
            $max_credit = $this->input->post('max_credit');
            $salesman_id = $this->input->post('salesman_id');

            $password = password_hash($password, PASSWORD_BCRYPT);

            $user_data = array(
                'email' => $email,
                'password' => $password,
                'role' => 'customer',
                'register_date' => date('Y-m-d H:i:s'),
                'status' => '1'
            );

            $user = $this->customer->register_user($user_data);

            $customer_data = array(
                'user_id' => $user,
                'name' => $name,
                'nik' => $nik,
                'npwp' => $npwp,
                'phone_number' => $no_telp,
                'kota_id' => $kota,
                'address' => $address,
                'shop_name' => $shop_name,
                'shop_address' => $shop_address,
                'level' => $level,
                'max_credit' => $max_credit,
                'salesman_id' => $salesman_id,
            );

            $this->customer->register_customer($customer_data);

            $this->session->set_flashdata('add_new_customer_flash', 'Customer berhasil ditambahkan!');

            redirect('admin/customers');
        }
    }

    public function view($id = 0)
    {
        if ($this->customer->is_customer_exist($id)) {
            $data  = $this->customer->customer_data($id);
            $cusva = $this->customer->get_status_va($id);

            $customer['admin'] = $this->admin->get_all_admin();

            $params['title'] = $data->name;

            $customer['customer'] = $data;
            $customer['va']     = $cusva;
            $customer['flash'] = $this->session->flashdata('customer_flash');

            $this->load->view('header', $params);
            $this->load->view('customers/view', $customer);
            $this->load->view('footer');
        } else {
            show_404();
        }
    }

    public function api($action = '')
    {
        switch ($action) {
            case 'customers':
                $customers = $this->customer->get_all_customers();

                $n = 0;
                foreach ($customers as $customer) {
                    $customers[$n]->profile_picture = base_url('assets/uploads/users/' . $customer->profile_picture);

                    $n++;
                }

                $customers['data'] = $customers;

                $response = $customers;
                break;
            case 'customer_detail':
                $id = (int) $this->input->get('id');

                if (!$this->customer->is_customer_exist($id)) {
                    $this->output->set_status_header(404);
                    $response = array(
                        'code' => 404,
                        'message' => 'Customer tidak ditemukan.'
                    );
                    break;
                }

                $orders = $this->order->order_by($id);
                $payments = $this->payment->payment_by($id);
                $extravaganza_summary_abc = $this->payment->konversi_point_extravaganza_ABC($id);
                $extravaganza_summary_fastmoving = $this->payment->konversi_point_extravaganza_fastmoving($id);
                $extravaganza_history = $this->payment->riwayat_invoice($id);

                $response = array(
                    'code' => 200,
                    'orders_html' => $this->render_orders_table($orders),
                    'payments_html' => $this->render_payments_table($payments),
                    'extravaganza_html' => $this->render_extravaganza_panel(
                        $extravaganza_summary_abc,
                        $extravaganza_summary_fastmoving,
                        $extravaganza_history
                    ),
                    'meta' => array(
                        'orders_count' => count($orders),
                        'payments_count' => count($payments),
                        'extravaganza_history_count' => count($extravaganza_history)
                    )
                );
                break;
            case 'delete':
                $id = $this->input->post('id');

                $this->customer->delete_customer($id);

                $response = array('code' => 204);
                break;
            case 'deactivate':
                $id = $this->input->post('id');

                $this->customer->deactivate_customer($id);

                $response = array('code' => 204);
                break;
            case 'activate':
                $id = $this->input->post('id');

                $this->customer->activate_customer($id);

                $response = array('code' => 204);
                break;
            case 'reset_password':
                $id = $this->input->post('id');

                $this->customer->reset_customer_password($id, '1234');

                $response = array('code' => 200);
                break;
            case 'edit':
                $customer['user_id'] = $this->input->post('user_id');
                $customer['name'] = $this->input->post('names');
                $customer['kota'] = $this->input->post('kota');
                //    $customer['email'] = $this->input->post('email');
                $customer['phone_number'] = $this->input->post('phone_number');
                $customer['salesman_id'] = $this->input->post('salesman_id');
                $customer['address'] = $this->input->post('address');
                $customer['level'] = $this->input->post('level');
                $customer['max_credit'] = $this->input->post('max_credit');

                $this->customer->edit($customer);

                redirect('admin/customers/view/' . $customer['user_id']);
                break;
        }

        $response = json_encode($response);
        $this->output->set_content_type('application/json')
            ->set_output($response);
    }

    private function render_orders_table($orders)
    {
        ob_start();
        ?>
        <?php if (count($orders) > 0) : ?>
            <div class="customer-table-widget" data-page-size="10">
                <div class="customer-table-toolbar">
                    <ul class="nav nav-pills customer-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" href="#" data-filter="done">Selesai</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-filter="shipping">Pengiriman / Dikemas</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-filter="waiting">Menunggu Pembayaran</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-filter="cancelled">Batal</a></li>
                    </ul>
                    <div class="customer-search">
                        <i class="fa fa-search"></i>
                        <input type="search" class="form-control form-control-sm customer-table-search" placeholder="Cari order...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush customer-data-table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nomor</th>
                                <th scope="col">Jumlah Harga</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) : ?>
                                <?php
                                $status_group = $this->get_order_status_group($order->order_status);
                                $search_text = implode(' ', array(
                                    $order->id,
                                    $order->order_number,
                                    $order->total_price,
                                    strip_tags(get_order_status($order->order_status))
                                ));
                                ?>
                                <tr data-filter="<?php echo $status_group; ?>" data-search="<?php echo html_escape(strtolower($search_text)); ?>">
                                    <th scope="col"><?php echo html_escape($order->id); ?></th>
                                    <td><?php echo anchor('admin/orders/view/' . $order->id, '#' . html_escape($order->order_number)); ?></td>
                                    <td>Rp <?php echo format_rupiah($order->total_price); ?></td>
                                    <td><?php echo get_order_status($order->order_status); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="customer-table-footer">
                    <span class="customer-table-info text-muted"></span>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary customer-page-prev">Sebelumnya</button>
                        <button type="button" class="btn btn-outline-primary customer-page-next">Berikutnya</button>
                    </div>
                </div>
                <div class="alert alert-info customer-table-empty mb-0" style="display:none;">Data tidak ditemukan untuk filter ini.</div>
            </div>
        <?php else : ?>
            <div class="alert alert-info mb-0">Belum ada data order.</div>
        <?php endif; ?>
        <?php
        return trim(ob_get_clean());
    }

    private function render_payments_table($payments)
    {
        ob_start();
        ?>
        <?php if (count($payments) > 0) : ?>
            <div class="customer-table-widget" data-page-size="10">
                <div class="customer-table-toolbar">
                    <ul class="nav nav-pills customer-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" href="#" data-filter="transfer">Transfer</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-filter="briva">BRIVA</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-filter="credit">Kredit</a></li>
                    </ul>
                    <div class="customer-search">
                        <i class="fa fa-search"></i>
                        <input type="search" class="form-control form-control-sm customer-table-search" placeholder="Cari pembayaran...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush customer-data-table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Order</th>
                                <th scope="col">Metode</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment) : ?>
                                <?php
                                $method_group = $this->get_payment_method_group($payment->payment_method);
                                $method_label = get_payment_method($payment->payment_method);
                                $search_text = implode(' ', array(
                                    $payment->id,
                                    $payment->order_number,
                                    $method_label,
                                    $payment->payment_price,
                                    strip_tags(get_payment_status($payment->payment_status))
                                ));
                                ?>
                                <tr data-filter="<?php echo $method_group; ?>" data-search="<?php echo html_escape(strtolower($search_text)); ?>">
                                    <th scope="col"><?php echo html_escape($payment->id); ?></th>
                                    <td><?php echo anchor('admin/paymeny/view/' . $payment->id, html_escape($payment->order_number)); ?></td>
                                    <td><?php echo html_escape($method_label); ?></td>
                                    <td>Rp <?php echo format_rupiah($payment->payment_price); ?></td>
                                    <td><?php echo get_payment_status($payment->payment_status); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="customer-table-footer">
                    <span class="customer-table-info text-muted"></span>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary customer-page-prev">Sebelumnya</button>
                        <button type="button" class="btn btn-outline-primary customer-page-next">Berikutnya</button>
                    </div>
                </div>
                <div class="alert alert-info customer-table-empty mb-0" style="display:none;">Data tidak ditemukan untuk filter ini.</div>
            </div>
        <?php else : ?>
            <div class="alert alert-info mb-0">Belum ada data pembayaran.</div>
        <?php endif; ?>
        <?php
        return trim(ob_get_clean());
    }

    private function get_order_status_group($status)
    {
        $status = (int) $status;

        if ($status === 6) {
            return 'done';
        }

        if (in_array($status, array(3, 4, 5), true)) {
            return 'shipping';
        }

        if ($status === 7) {
            return 'cancelled';
        }

        return 'waiting';
    }

    private function get_payment_method_group($payment_method)
    {
        $payment_method = (int) $payment_method;

        if ($payment_method === 2) {
            return 'briva';
        }

        if ($payment_method === 1) {
            return 'credit';
        }

        return 'transfer';
    }

    private function render_extravaganza_panel($summary_abc, $summary_fastmoving, $history)
    {
        $has_abc = count($summary_abc) > 0;
        $has_fastmoving = count($summary_fastmoving) > 0;
        $total_silver = 0;
        $total_gold = 0;
        $total_platinum = 0;

        if ($has_abc) {
            foreach ($summary_abc as $summary) {
                $total_silver += (int) $summary->total_silver;
                $total_gold += (int) $summary->total_gold;
                $total_platinum += (int) $summary->total_platinum;
            }
        }

        if ($has_fastmoving) {
            foreach ($summary_fastmoving as $summary) {
                $total_silver += (int) $summary->total_silver;
                $total_gold += (int) $summary->total_gold;
                $total_platinum += (int) $summary->total_platinum;
            }
        }

        ob_start();
        ?>
        <?php if ($has_abc || $has_fastmoving) : ?>
            <div class="table-responsive mt-3">
                <table class="table align-items-center table-flush table-striped table-hover">
                    <thead class="thead-light bg-primary text-white">
                        <tr>
                            <th scope="col">Kategori</th>
                            <th scope="col">Nominal</th>
                            <th scope="col">POINT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($has_abc) : ?>
                            <?php foreach ($summary_abc as $summary) : ?>
                                <tr>
                                    <td><span class="badge badge-primary">ABC</span></td>
                                    <td>Rp <?php echo number_format($summary->total_nominal); ?></td>
                                    <td><?php echo number_format($summary->total_silver); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($has_fastmoving) : ?>
                            <?php foreach ($summary_fastmoving as $summary) : ?>
                                <tr>
                                    <td><span class="badge badge-success">fastmoving</span></td>
                                    <td>Rp <?php echo number_format($summary->total_nominal); ?></td>
                                    <td><?php echo number_format($summary->total_silver); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <table class="table align-items-center table-flush table-striped table-hover">
                    <thead class="thead-light bg-primary text-white">
                        <tr>
                            <th scope="col">TOT</th>
                            <th scope="col">SILVER</th>
                            <th scope="col">GOLD</th>
                            <th scope="col">PLATINUM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="font-weight-bold">
                            <td>Total</td>
                            <td><?php echo number_format($total_silver); ?></td>
                            <td><?php echo number_format($total_gold); ?></td>
                            <td><?php echo number_format($total_platinum); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="alert alert-info mb-0">Belum ada data point.</div>
        <?php endif; ?>

        <ul class="nav nav-tabs mt-4" role="tablist" id="extravaganza-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#" data-extravaganza-filter="all">Semua</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-extravaganza-filter="ABC">ABC</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-extravaganza-filter="fastmoving">Fastmoving</a>
            </li>
        </ul>

        <div class="table-responsive mt-3">
            <table class="table align-items-center table-flush table-striped table-hover">
                <thead class="thead-light bg-primary text-white">
                    <tr>
                        <th scope="col">Order</th>
                        <th scope="col">Status</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($history) > 0) : ?>
                        <?php foreach ($history as $row) : ?>
                            <tr data-category="<?php echo html_escape($row->product_category); ?>">
                                <td><?php echo html_escape($row->order_number); ?></td>
                                <td><?php echo html_escape($row->order_status); ?></td>
                                <td>
                                    <?php if ($row->product_category == 'ABC') : ?>
                                        <span class="badge badge-primary">ABC</span>
                                    <?php elseif ($row->product_category == 'fastmoving') : ?>
                                        <span class="badge badge-success">fastmoving</span>
                                    <?php else : ?>
                                        <span class="badge badge-secondary"><?php echo html_escape($row->product_category); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo number_format($row->order_qty); ?></td>
                                <td>Rp <?php echo format_rupiah($row->order_price); ?></td>
                                <td>Rp <?php echo format_rupiah($row->nominal_belanja); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="extravaganza-empty" style="display:none;">
                            <td colspan="6">Tidak ada data untuk kategori ini.</td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">Belum ada data riwayat invoice.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
        return trim(ob_get_clean());
    }

    public function generate_va()
    {
        $idcus  = $this->input->post('idcus');
        $vacus  = $this->input->post('vacusno');

        $generate = array(
            'va_code'   => $vacus
        );

        $this->customer->updateva($idcus, $generate);
        redirect('admin/customers/view/' . $idcus);
    }
}
