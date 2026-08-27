<?php

/**
 * KIU Store source-contract regression test runner.
 *
 * This runner intentionally avoids external dependencies because the current
 * repository does not contain Composer/PHPUnit metadata. It validates the use
 * case document against actual CodeIgniter routes, controllers, models, and
 * helpers without touching the production database.
 */

date_default_timezone_set('Asia/Jakarta');

class KiuRegressionRunner
{
    private $root;
    private $routes = array();
    private $results = array();

    public function __construct($root)
    {
        $this->root = rtrim($root, DIRECTORY_SEPARATOR);
        $this->routes = $this->loadRoutes();
    }

    public function run()
    {
        $tests = $this->tests();

        foreach ($tests as $test) {
            $started = microtime(true);
            try {
                call_user_func($test['callback']);
                $this->results[] = array(
                    'status' => 'PASS',
                    'duration_ms' => (int) round((microtime(true) - $started) * 1000),
                    'error' => null,
                    'meta' => $test['meta']
                );
            } catch (Exception $e) {
                $this->results[] = array(
                    'status' => 'FAIL',
                    'duration_ms' => (int) round((microtime(true) - $started) * 1000),
                    'error' => $e->getMessage(),
                    'meta' => $test['meta']
                );
            }
        }

        return $this->results;
    }

    public function printConsole()
    {
        $pass = 0;
        $fail = 0;

        foreach ($this->results as $index => $result) {
            if ($result['status'] === 'PASS') {
                $pass++;
            } else {
                $fail++;
            }

            $line = sprintf(
                "%s %02d %s - %s (%d ms)",
                $result['status'] === 'PASS' ? 'PASS' : 'FAIL',
                $index + 1,
                $result['meta']['test_id'],
                $result['meta']['scenario'],
                $result['duration_ms']
            );
            echo $line . PHP_EOL;

            if ($result['error']) {
                echo '     ' . $result['error'] . PHP_EOL;
            }
        }

        echo PHP_EOL;
        echo sprintf('SUMMARY: %d passed, %d failed, %d total', $pass, $fail, count($this->results)) . PHP_EOL;

        return $fail === 0 ? 0 : 1;
    }

    public function writeMarkdownReport($path)
    {
        $pass = 0;
        $fail = 0;
        foreach ($this->results as $result) {
            if ($result['status'] === 'PASS') {
                $pass++;
            } else {
                $fail++;
            }
        }

        $lines = array();
        $lines[] = '# KIU Store Regression Test Report';
        $lines[] = '';
        $lines[] = 'Tanggal eksekusi: ' . date('Y-m-d H:i:s T');
        $lines[] = 'Environment: PHP CLI ' . PHP_VERSION . ', source-contract mode, database tidak disentuh.';
        $lines[] = '';
        $lines[] = '## Ringkasan Eksekutif';
        $lines[] = '';
        $lines[] = '| Metrik | Nilai |';
        $lines[] = '|---|---:|';
        $lines[] = '| Total test | ' . count($this->results) . ' |';
        $lines[] = '| Pass | ' . $pass . ' |';
        $lines[] = '| Fail | ' . $fail . ' |';
        $lines[] = '';
        $lines[] = '## Taksonomi Data';
        $lines[] = '';
        $lines[] = '- Verified System Facts: status test di bawah berasal dari file source aktual, route aktual, dan helper/controller/model aktual.';
        $lines[] = '- Milestone Completion %: belum dihitung sebagai kesiapan modul penuh karena test database, browser, dan API HTTP live belum dijalankan.';
        $lines[] = '- Business Outcome KPIs: UNVERIFIED. Tidak ada baseline data produksi berjalan pada eksekusi ini.';
        $lines[] = '';
        $lines[] = '## Detail Test Case';
        $lines[] = '';

        foreach ($this->results as $result) {
            $meta = $result['meta'];
            $lines[] = '### ' . $meta['test_id'] . ' - ' . $result['status'];
            $lines[] = '';
            $rows = array(
                'TEST MODULE' => $meta['module'],
                'SCENARIO' => $meta['scenario'],
                'PRECONDITION' => $meta['precondition'],
                'TEST STEPS' => $meta['steps'],
                'EXPECTED RESULT' => $meta['expected'],
                'AUDIT TRAIL' => $meta['audit_trail'],
                'ROUTE' => $meta['route'],
                'CONTROLLER' => $meta['controller'],
                'MODEL' => $meta['model'],
                'HELPER' => $meta['helper'],
                'LIBRARY' => $meta['library'],
                'DATABASE TABLE' => $meta['database_table'],
                'EXTERNAL DEPENDENCY' => $meta['external_dependency'],
                'AUTHENTICATION REQUIREMENT' => $meta['authentication_requirement'],
                'ROLE REQUIREMENT' => $meta['role_requirement'],
                'TEST TYPE' => $meta['test_type'],
                'RISK LEVEL' => $meta['risk_level'],
                'RESULT' => $result['status'],
                'BUG ANALYSIS' => $result['error'] ? $result['error'] : 'Tidak ada defect pada kontrak source yang diuji.'
            );

            $lines[] = '| Field | Nilai |';
            $lines[] = '|---|---|';
            foreach ($rows as $key => $value) {
                $lines[] = '| ' . $this->md($key) . ' | ' . $this->md($value) . ' |';
            }
            $lines[] = '';
        }

        $lines[] = '## Next Steps & Risk Mitigation';
        $lines[] = '';
        $lines[] = '| Akar Masalah | Dampak Bisnis | Strategi Mitigasi | Owner |';
        $lines[] = '|---|---|---|---|';
        $lines[] = '| Belum ada framework PHPUnit/Composer di repo | Test belum dapat mengeksekusi controller dengan database transaction otomatis | Tambahkan test bootstrap CodeIgniter atau PHPUnit pada environment test terisolasi | Engineering |';
        $lines[] = '| Kontrak dokumen dan controller API bisa bergeser | Mobile app dapat menerima HTTP method/response yang tidak sesuai ekspektasi | Jadikan source-contract test ini bagian dari regression gate | QA + Backend |';
        $lines[] = '| Database test belum dipisahkan eksplisit dari config default | Risiko test menulis ke database lokal/prod bila integration test dibuat tergesa-gesa | Tambahkan `DATABASE_TEST` dan cleanup prefix `TEST_*` sebelum integration test | DevOps + QA |';
        $lines[] = '';

        file_put_contents($path, implode(PHP_EOL, $lines) . PHP_EOL);
    }

    private function tests()
    {
        $self = $this;

        return array(
            array(
                'meta' => $this->meta(
                    'CUS-AUTH-001',
                    'Register',
                    'Registrasi customer berhasil',
                    'Email dan nomor HP belum ada.',
                    'Trace route `/register`, controller `Register::verify`, model register, validasi unik, insert database, redirect notifikasi.',
                    'User role customer dan row customers dibuat, lalu redirect ke notifikasi register.',
                    'application/config/routes.php; application/controllers/auth/Register.php; application/models/auth/Register_model.php',
                    '/register',
                    'application/controllers/auth/Register.php',
                    'application/models/auth/Register_model.php',
                    'form/session helper via autoload',
                    'form_validation, encryption',
                    'users, customers',
                    'Tidak ada',
                    'Public',
                    'customer setelah registrasi',
                    'Source Contract / Integration Candidate',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $self->assertRoute('register', 'auth/register');
                    $source = $self->read('application/controllers/auth/Register.php');
                    $verify = $self->functionBody($source, 'verify');
                    $self->assertContains("is_unique[customers.phone_number]", $verify, 'Validasi nomor HP unik harus mengarah ke tabel customers.phone_number.');
                    $self->assertContains("is_unique[users.email]", $verify, 'Validasi email unik harus mengarah ke tabel users.email.');
                    $self->assertContains("'role' => 'customer'", $verify, 'Registrasi harus membuat role customer.');
                    $self->assertContains('register_user', $verify, 'Register::verify harus memanggil register_user.');
                    $self->assertContains('register_customer', $verify, 'Register::verify harus memanggil register_customer.');
                    $self->assertContains("redirect('auth/register/notif')", $verify, 'Registrasi berhasil harus redirect ke notifikasi register.');
                    $model = $self->read('application/models/auth/Register_model.php');
                    $self->assertContains("insert('users'", $model, 'Register_model harus insert users.');
                    $self->assertContains("insert('customers'", $model, 'Register_model harus insert customers.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'CUS-AUTH-004',
                    'Login',
                    'Login customer berhasil',
                    'Akun customer aktif dan password valid.',
                    'Trace `/login`, `Login::do_login`, `Login_model`, session encrypted, redirect customer.',
                    'Session customer dibuat dan redirect ke `/home` atau tutorial home untuk customer baru.',
                    'application/controllers/auth/Login.php; application/models/auth/Login_model.php; application/helpers/session_helper.php',
                    '/login',
                    'application/controllers/auth/Login.php',
                    'application/models/auth/Login_model.php',
                    'application/helpers/session_helper.php',
                    'form_validation, encryption, session',
                    'users, customers',
                    'Tidak ada',
                    'Public login',
                    'customer',
                    'Source Contract / Integration Candidate',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $self->assertRoute('login', 'auth/login');
                    $source = $self->read('application/controllers/auth/Login.php');
                    $body = $self->functionBody($source, 'do_login');
                    $self->assertContains('password_verify', $body, 'Login harus memakai password_verify.');
                    $self->assertContains("'is_customer' => TRUE", $body, 'Login customer harus membuat flag is_customer.');
                    $self->assertContains('__ACTIVE_SESSION_DATA', $body, 'Login harus menyimpan encrypted active session.');
                    $self->assertContains("'home'", $body, 'Login customer harus memiliki redirect default home.');
                    $model = $self->read('application/models/auth/Login_model.php');
                    $self->assertContains("get('users')", $model, 'Login_model harus membaca tabel users.');
                    $self->assertContains('JOIN customers', $model, 'Login_model harus join customers untuk data level/salesman customer.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'CUS-AUTH-005',
                    'Login',
                    'Password salah',
                    'Akun aktif, password tidak cocok.',
                    'Trace branch password gagal pada `Login::do_login`.',
                    'Redirect login dengan flash `Password salah!` dan old email tersimpan.',
                    'application/controllers/auth/Login.php',
                    '/login',
                    'application/controllers/auth/Login.php',
                    'application/models/auth/Login_model.php',
                    'session_helper',
                    'form_validation, encryption',
                    'users',
                    'Tidak ada',
                    'Public login',
                    'customer/admin sesuai akun',
                    'Source Contract',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $body = $self->functionBody($self->read('application/controllers/auth/Login.php'), 'do_login');
                    $self->assertContains("Password salah!", $body, 'Flash password salah harus ada.');
                    $self->assertContains("set_flashdata('old_email'", $body, 'Old email harus disimpan saat password salah.');
                    $self->assertContains("redirect('/auth/login')", $body, 'Password salah harus redirect ke auth/login.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'CUS-AUTH-006',
                    'Login',
                    'User tidak aktif',
                    'users.status bukan aktif.',
                    'Trace `Login_model::is_user_active` dan branch controller.',
                    'Login ditolak dengan pesan akun tidak aktif/belum diverifikasi admin.',
                    'application/controllers/auth/Login.php; application/models/auth/Login_model.php',
                    '/login',
                    'application/controllers/auth/Login.php',
                    'application/models/auth/Login_model.php',
                    'session_helper',
                    'form_validation, encryption',
                    'users',
                    'Tidak ada',
                    'Public login',
                    'customer/admin sesuai akun',
                    'Source Contract',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $controller = $self->functionBody($self->read('application/controllers/auth/Login.php'), 'do_login');
                    $model = $self->functionBody($self->read('application/models/auth/Login_model.php'), 'is_user_active');
                    $self->assertContains('is_user_active', $controller, 'Controller harus memeriksa user aktif.');
                    $self->assertContains('tidak aktif / belum diverifikasi admin', $controller, 'Pesan user tidak aktif harus ada.');
                    $self->assertContains("where('status', 1)", $model, 'Model harus memfilter users.status = 1.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'CUS-AUTH-007 / SEC-002',
                    'Customer Access Guard',
                    'Akses halaman customer tanpa session',
                    'Belum login.',
                    'Trace route `/cart`, `/profile`, constructor controller, dan helper `verify_session`.',
                    'Protected customer route redirect ke `auth/login?redir_to=...`.',
                    'application/config/routes.php; application/modules/customer/controllers/Shop.php; application/modules/customer/controllers/Profile.php; application/helpers/session_helper.php',
                    '/cart, /profile',
                    'application/modules/customer/controllers/Shop.php; application/modules/customer/controllers/Profile.php',
                    'application/modules/customer/models/Profile_model.php; Product_model.php',
                    'application/helpers/session_helper.php',
                    'session, encryption',
                    'users, customers',
                    'Tidak ada',
                    'Wajib login',
                    'customer',
                    'Source Contract / Security Regression',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $self->assertRoute('cart', 'customer/shop/cart');
                    $self->assertRoute('profile', 'customer/profile');
                    $self->assertContains("verify_session('customer')", $self->read('application/modules/customer/controllers/Shop.php'), 'Shop harus dilindungi customer session.');
                    $self->assertContains("verify_session('customer')", $self->read('application/modules/customer/controllers/Profile.php'), 'Profile harus dilindungi customer session.');
                    $helper = $self->functionBody($self->read('application/helpers/session_helper.php'), 'verify_session');
                    $self->assertContains("redirect('auth/login?redir_to='", $helper, 'verify_session harus redirect ke login dengan redir_to.');
                    $self->assertContains("is_customer()", $helper, 'verify_session customer harus memanggil is_customer.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'CUS-AUTH-008',
                    'Logout',
                    'Logout customer',
                    'Customer login via cookie/session.',
                    'Trace `/logout` dan `Logout::index`.',
                    'Cart dihancurkan, active session/cookie dihapus, redirect login.',
                    'application/config/routes.php; application/controllers/auth/Logout.php',
                    '/logout',
                    'application/controllers/auth/Logout.php',
                    'UNVERIFIED',
                    'cookie, session_helper',
                    'cart, encryption',
                    'Session store',
                    'Tidak ada',
                    'Session aktif direkomendasikan',
                    'customer/admin',
                    'Source Contract',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $self->assertRoute('logout', 'auth/logout');
                    $body = $self->functionBody($self->read('application/controllers/auth/Logout.php'), 'index');
                    $self->assertContains('$this->cart->destroy()', $body, 'Logout harus membersihkan cart.');
                    $self->assertContains("delete_cookie('__ACTIVE_SESSION_DATA')", $body, 'Logout harus menghapus cookie active session.');
                    $self->assertContains("unset_userdata('__ACTIVE_SESSION_DATA')", $body, 'Logout harus menghapus session active data.');
                    $self->assertContains("redirect('/auth/login')", $body, 'Logout harus redirect ke auth/login.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'ADM-AUTH-001 / SEC-001',
                    'Admin Access Guard',
                    'Admin dashboard protected route',
                    'Admin belum/ sudah login sesuai skenario.',
                    'Trace route dashboard, constructor Dashboard, dan role helper.',
                    'Dashboard admin dilindungi `verify_session(admin)` dan role helper mengenali semua role admin operasional.',
                    'application/config/routes.php; application/modules/admin/controllers/Dashboard.php; application/helpers/session_helper.php',
                    '/dashboard_admin',
                    'application/modules/admin/controllers/Dashboard.php',
                    'Admin_model, Product_model, Customer_model, Order_model, Payment_model',
                    'application/helpers/session_helper.php',
                    'session, encryption',
                    'users',
                    'Tidak ada',
                    'Wajib login',
                    'admin/adminonline/keuangan/salesman/distribusi/kadep',
                    'Source Contract / Security Regression',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $self->assertRoute('dashboard_admin', 'admin/dashboard');
                    $self->assertContains("verify_session('admin')", $self->read('application/modules/admin/controllers/Dashboard.php'), 'Dashboard harus dilindungi admin session.');
                    $isAdmin = $self->functionBody($self->read('application/helpers/session_helper.php'), 'is_admin');
                    foreach (array('admin', 'adminonline', 'keuangan', 'salesman', 'distribusi', 'kadep') as $role) {
                        $self->assertContains("'$role'", $isAdmin, 'is_admin harus mengenali role ' . $role . '.');
                    }
                }
            ),
            array(
                'meta' => $this->meta(
                    'ADM-AUTH-REGRESSION-001',
                    'Login Redirect Role Guard',
                    'User admin aktif mengakses login kembali',
                    'Session login aktif dengan role admin operasional.',
                    'Bandingkan role `is_admin()` dengan role redirect di `verify_login()`.',
                    'Semua role admin operasional diarahkan ke dashboard_admin saat sudah login.',
                    'application/helpers/session_helper.php',
                    '/login',
                    'application/controllers/auth/Login.php',
                    'application/models/auth/Login_model.php',
                    'application/helpers/session_helper.php',
                    'session, encryption',
                    'users',
                    'Tidak ada',
                    'Session aktif',
                    'admin/adminonline/keuangan/salesman/distribusi/kadep',
                    'Source Contract / Security Regression',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $helper = $self->read('application/helpers/session_helper.php');
                    $verifyLogin = $self->functionBody($helper, 'verify_login');
                    foreach (array('admin', 'adminonline', 'keuangan', 'salesman', 'distribusi', 'kadep') as $role) {
                        $self->assertContains("'$role'", $verifyLogin, 'verify_login belum mengarahkan role admin operasional `' . $role . '` ke dashboard_admin.');
                    }
                    $self->assertContains("redirect('dashboard_admin')", $verifyLogin, 'verify_login harus redirect admin ke dashboard_admin.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'API-ROUTES-001',
                    'Mobile API Routing',
                    'Endpoint API mobile v1 terdaftar',
                    'Route config tersedia.',
                    'Trace route `/api/v1/*` ke `api/mobile/*` dan method controller.',
                    'Endpoint utama API mobile terdaftar dan method controller ada.',
                    'application/config/routes.php; application/modules/api/controllers/Mobile.php',
                    '/api/v1/*',
                    'application/modules/api/controllers/Mobile.php',
                    'application/modules/api/models/Mobile_api_model.php',
                    'UNVERIFIED',
                    'output, upload',
                    'mobile_api_tokens, mobile_cart_items, mobile_shipping_quotes, users, customers',
                    'RajaOngkir/Komerce, BRIVA untuk subset endpoint',
                    'Bearer token untuk endpoint private',
                    'customer',
                    'Source Contract / API Regression',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $expected = array(
                        'api/v1/status' => 'api/mobile/index',
                        'api/v1/auth/register' => 'api/mobile/register',
                        'api/v1/auth/login' => 'api/mobile/login',
                        'api/v1/auth/logout' => 'api/mobile/logout',
                        'api/v1/onboarding/complete' => 'api/mobile/onboarding_complete',
                        'api/v1/account' => 'api/mobile/account',
                        'api/v1/profile' => 'api/mobile/profile',
                        'api/v1/cart' => 'api/mobile/cart',
                        'api/v1/orders/checkout' => 'api/mobile/checkout',
                        'api/v1/messages' => 'api/mobile/messages'
                    );
                    foreach ($expected as $route => $target) {
                        $self->assertRoute($route, $target);
                        $parts = explode('/', $target);
                        $method = end($parts);
                        $self->assertFunctionExists($self->read('application/modules/api/controllers/Mobile.php'), $method, 'Mobile controller harus memiliki method ' . $method . '.');
                    }
                }
            ),
            array(
                'meta' => $this->meta(
                    'API-CART-001',
                    'Mobile API Cart',
                    'Order dikemas/dikirim tidak memblokir tambah cart',
                    'Customer memiliki order lama dengan status 3 atau 4.',
                    'Trace `Mobile_api_model::active_transaction_order` dan query status order.',
                    'Status Dikemas, Dikirim, Selesai, dan Dibatalkan tidak dihitung sebagai transaksi aktif pemblokir cart.',
                    'application/modules/api/models/Mobile_api_model.php',
                    '/api/v1/cart',
                    'application/modules/api/controllers/Mobile.php',
                    'application/modules/api/models/Mobile_api_model.php',
                    'UNVERIFIED',
                    'UNVERIFIED',
                    'orders, mobile_cart_items',
                    'Tidak ada',
                    'Bearer token',
                    'customer',
                    'Source Contract / API Regression',
                    'HIGH'
                ),
                'callback' => function () use ($self) {
                    $model = $self->read('application/modules/api/models/Mobile_api_model.php');
                    $body = $self->functionBody($model, 'active_transaction_order');
                    $self->assertContains("where_not_in('order_status', array(3, 4, 5, 6, 7))", $body, 'Order status 3 dan 4 harus boleh menambahkan item baru ke cart.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'API-CHECKOUT-001',
                    'Mobile API Checkout',
                    'Checkout menunggu ekspedisi admin',
                    'Customer membuat pesanan dari keranjang.',
                    'Trace `Mobile::checkout` dan `Mobile_api_model::checkout`.',
                    'Checkout mobile tidak mewajibkan quote ongkir, tidak mengisi ekspedisi otomatis, dan ongkir awal bernilai 0.',
                    'application/modules/api/controllers/Mobile.php; application/modules/api/models/Mobile_api_model.php',
                    '/api/v1/orders/checkout',
                    'application/modules/api/controllers/Mobile.php',
                    'application/modules/api/models/Mobile_api_model.php',
                    'UNVERIFIED',
                    'UNVERIFIED',
                    'orders, order_items, mobile_cart_items',
                    'Tidak ada untuk checkout awal; admin mengisi ekspedisi/ongkir',
                    'Bearer token',
                    'customer',
                    'Source Contract / API Regression',
                    'HIGH'
                ),
                'callback' => function () use ($self) {
                    $controller = $self->functionBody($self->read('application/modules/api/controllers/Mobile.php'), 'checkout');
                    $model = $self->functionBody($self->read('application/modules/api/models/Mobile_api_model.php'), 'checkout');
                    $self->assertContains("\$this->value('shipping_quote_id', 0)", $controller, 'Checkout harus menerima request tanpa shipping_quote_id.');
                    $self->assertContains("\$this->value('shipping_service', '')", $controller, 'Checkout harus menerima request tanpa shipping_service.');
                    $self->assertContains("'jenis_pengiriman' => ''", $model, 'Jenis pengiriman awal harus kosong sampai diisi admin.');
                    $self->assertContains("'shipping_cost' => 0", $model, 'Ongkir awal harus 0 sampai diisi admin.');
                    $self->assertContains("\$order['nama_ekspedisi'] = ''", $model, 'Nama ekspedisi awal harus kosong sampai diisi admin.');
                    $self->assertContains("'active_order' => \$active_order", $model, 'Checkout harus mengembalikan active_order saat ada transaksi berjalan.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'API-006',
                    'Mobile API Account',
                    'Account detail mobile',
                    'Bearer token valid.',
                    'Trace route `/api/v1/account` dan method `Mobile::account`.',
                    'Sesuai dokumen use case: `GET /api/v1/account` mengembalikan detail account/customer.',
                    'docs/USECASE_LIST_TESTING_CUSTOMER_ADMIN_20260821.md; application/modules/api/controllers/Mobile.php',
                    'GET /api/v1/account',
                    'application/modules/api/controllers/Mobile.php',
                    'application/modules/api/models/Mobile_api_model.php',
                    'UNVERIFIED',
                    'output',
                    'users, customers, mobile_api_tokens',
                    'Tidak ada',
                    'Bearer token valid',
                    'customer',
                    'Source Contract / API Regression',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $body = $self->functionBody($self->read('application/modules/api/controllers/Mobile.php'), 'account');
                    $self->assertContains("\$method === 'GET'", $body, 'Kontrak API-006 meminta GET /api/v1/account, tetapi source tidak memiliki branch GET.');
                    $self->assertContains('profile($this->user->id)', $body, 'GET account harus mengembalikan profil/account customer.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'API-029',
                    'Mobile API Authentication',
                    'Token invalid/revoked',
                    'Endpoint private diakses tanpa/ dengan token invalid.',
                    'Trace `Mobile::authenticate`, bearer parser, dan model token.',
                    'Response unauthorized 401 untuk token kosong atau invalid.',
                    'application/modules/api/controllers/Mobile.php; application/modules/api/models/Mobile_api_model.php',
                    '/api/v1/orders, /api/v1/cart, private endpoints',
                    'application/modules/api/controllers/Mobile.php',
                    'application/modules/api/models/Mobile_api_model.php',
                    'UNVERIFIED',
                    'output',
                    'mobile_api_tokens, users, customers',
                    'Tidak ada',
                    'Bearer token',
                    'customer',
                    'Source Contract / API Security Regression',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $controller = $self->read('application/modules/api/controllers/Mobile.php');
                    $auth = $self->functionBody($controller, 'authenticate');
                    $bearer = $self->functionBody($controller, 'bearer_token');
                    $model = $self->functionBody($self->read('application/modules/api/models/Mobile_api_model.php'), 'user_from_token');
                    $self->assertContains('Bearer token wajib dikirim.', $auth, 'Token kosong harus menghasilkan pesan unauthorized.');
                    $self->assertContains('Token tidak valid atau kedaluwarsa.', $auth, 'Token invalid/revoked harus ditolak.');
                    $self->assertContains(', 401', $auth, 'Unauthorized harus memakai HTTP 401.');
                    $self->assertContains('HTTP_AUTHORIZATION', $bearer, 'Bearer token harus dibaca dari Authorization header.');
                    $self->assertContains("where('revoked_at IS NULL'", $model, 'Model token harus menolak token revoked.');
                    $self->assertContains('expires_at', $model, 'Model token harus memeriksa expiry.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'API-030',
                    'Mobile API Method Guard',
                    'HTTP method salah',
                    'Endpoint POST-only dipanggil dengan method lain.',
                    'Trace `require_method`, `method_not_allowed`, dan response builder.',
                    'Response 405 dengan header Allow.',
                    'application/modules/api/controllers/Mobile.php',
                    '/api/v1/*',
                    'application/modules/api/controllers/Mobile.php',
                    'UNVERIFIED',
                    'UNVERIFIED',
                    'output',
                    'UNVERIFIED',
                    'Tidak ada',
                    'Bervariasi per endpoint',
                    'customer untuk endpoint private',
                    'Source Contract / API Regression',
                    'CRITICAL'
                ),
                'callback' => function () use ($self) {
                    $controller = $self->read('application/modules/api/controllers/Mobile.php');
                    $require = $self->functionBody($controller, 'require_method');
                    $methodNotAllowed = $self->functionBody($controller, 'method_not_allowed');
                    $respond = $self->functionBody($controller, 'respond');
                    $self->assertContains('method_not_allowed', $require, 'require_method harus memanggil method_not_allowed saat method salah.');
                    $self->assertContains("set_header('Allow: '", $methodNotAllowed, '405 harus mengirim header Allow.');
                    $self->assertContains(', 405', $methodNotAllowed, 'Method salah harus menghasilkan HTTP 405.');
                    $self->assertContains('set_status_header($status)', $respond, 'Response API harus meneruskan HTTP status.');
                }
            ),
            array(
                'meta' => $this->meta(
                    'SEC-005',
                    'Input Sanitization',
                    'Search dengan HTML/script',
                    'Query search dikirim dari URL.',
                    'Trace customer search dan admin product search.',
                    'Query di-escape sebelum masuk title/parameter pencarian.',
                    'application/modules/customer/controllers/Home.php; application/modules/admin/controllers/Products.php',
                    '/search, /admin/products/search',
                    'Home.php; Products.php',
                    'Product_model.php',
                    'global/url/form helper',
                    'pagination',
                    'products, product_category',
                    'Tidak ada',
                    'Public customer search; admin search tergantung controller',
                    'customer/admin sesuai modul',
                    'Source Contract / Security Regression',
                    'HIGH'
                ),
                'callback' => function () use ($self) {
                    $homeSearch = $self->functionBody($self->read('application/modules/customer/controllers/Home.php'), 'search');
                    $adminSearch = $self->functionBody($self->read('application/modules/admin/controllers/Products.php'), 'search');
                    $self->assertContains("\$this->input->get('search_query')", $homeSearch, 'Customer search harus membaca search_query.');
                    $self->assertContains('html_escape($query)', $homeSearch, 'Customer search harus escape query.');
                    $self->assertContains("\$this->input->get('search_query')", $adminSearch, 'Admin search harus membaca search_query.');
                    $self->assertContains('html_escape($query)', $adminSearch, 'Admin search harus escape query.');
                }
            )
        );
    }

    private function meta($testId, $module, $scenario, $precondition, $steps, $expected, $auditTrail, $route, $controller, $model, $helper, $library, $databaseTable, $externalDependency, $authRequirement, $roleRequirement, $testType, $riskLevel)
    {
        return array(
            'test_id' => $testId,
            'module' => $module,
            'scenario' => $scenario,
            'precondition' => $precondition,
            'steps' => $steps,
            'expected' => $expected,
            'audit_trail' => $auditTrail,
            'route' => $route,
            'controller' => $controller,
            'model' => $model,
            'helper' => $helper,
            'library' => $library,
            'database_table' => $databaseTable,
            'external_dependency' => $externalDependency,
            'authentication_requirement' => $authRequirement,
            'role_requirement' => $roleRequirement,
            'test_type' => $testType,
            'risk_level' => $riskLevel
        );
    }

    private function loadRoutes()
    {
        if (!defined('BASEPATH')) {
            define('BASEPATH', $this->root . '/system/');
        }

        $route = array();
        require $this->path('application/config/routes.php');
        return $route;
    }

    public function assertRoute($routeKey, $expectedTarget)
    {
        if (!array_key_exists($routeKey, $this->routes)) {
            throw new Exception('Route `' . $routeKey . '` tidak ditemukan di application/config/routes.php.');
        }

        if ($this->routes[$routeKey] !== $expectedTarget) {
            throw new Exception('Route `' . $routeKey . '` mengarah ke `' . $this->routes[$routeKey] . '`, expected `' . $expectedTarget . '`.');
        }
    }

    public function assertFunctionExists($source, $name, $message)
    {
        if (!preg_match('/function\s+' . preg_quote($name, '/') . '\s*\(/', $source)) {
            throw new Exception($message);
        }
    }

    public function assertContains($needle, $haystack, $message)
    {
        if (strpos($haystack, $needle) === false) {
            throw new Exception($message . ' Missing fragment: ' . $needle);
        }
    }

    public function read($relativePath)
    {
        $path = $this->path($relativePath);
        if (!is_file($path)) {
            throw new Exception('File tidak ditemukan: ' . $relativePath);
        }

        return file_get_contents($path);
    }

    public function functionBody($source, $functionName)
    {
        if (!preg_match('/function\s+' . preg_quote($functionName, '/') . '\s*\(/', $source, $match, PREG_OFFSET_CAPTURE)) {
            throw new Exception('Function `' . $functionName . '` tidak ditemukan.');
        }

        $start = strpos($source, '{', $match[0][1]);
        if ($start === false) {
            throw new Exception('Body function `' . $functionName . '` tidak dapat dibaca.');
        }

        $depth = 0;
        $length = strlen($source);
        for ($i = $start; $i < $length; $i++) {
            $char = $source[$i];
            if ($char === '{') {
                $depth++;
            } elseif ($char === '}') {
                $depth--;
                if ($depth === 0) {
                    return substr($source, $start, $i - $start + 1);
                }
            }
        }

        throw new Exception('Body function `' . $functionName . '` tidak lengkap.');
    }

    private function path($relativePath)
    {
        return $this->root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    }

    private function md($value)
    {
        $value = str_replace(array("\r", "\n"), ' ', (string) $value);
        return str_replace('|', '\\|', $value);
    }
}

$root = realpath(__DIR__ . '/..');
$runner = new KiuRegressionRunner($root);
$runner->run();

$reportPath = $root . '/docs/test-reports/latest-regression-report.md';
$runner->writeMarkdownReport($reportPath);

exit($runner->printConsole());
