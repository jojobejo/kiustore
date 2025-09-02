-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Sep 2025 pada 03.22
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kiucoid_kiustore`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `banner_product`
--

CREATE TABLE `banner_product` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `banner_image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `banner_product`
--

INSERT INTO `banner_product` (`id`, `product_id`, `banner_image`, `created_at`) VALUES
(2, 2, 'bn1.jpg', '2022-06-18 02:23:10'),
(3, 8, 'bn2.jpg', '2022-06-18 02:24:15'),
(4, 11, 'bn3.jpg', '2022-06-18 02:24:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `briva_api`
--

CREATE TABLE `briva_api` (
  `id` int(11) NOT NULL,
  `order_number` varchar(16) NOT NULL,
  `kd_faktur` varchar(50) NOT NULL,
  `user_id` int(3) NOT NULL,
  `name` text NOT NULL,
  `va_code` varchar(50) NOT NULL,
  `userno` text NOT NULL,
  `total_price_topay` text NOT NULL,
  `exp_date` text NOT NULL,
  `status` int(2) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `briva_api`
--

INSERT INTO `briva_api` (`id`, `order_number`, `kd_faktur`, `user_id`, `name`, `va_code`, `userno`, `total_price_topay`, `exp_date`, `status`, `create_at`) VALUES
(7, 'LLB1925185380', 'KIU850109250008', 85, 'maulana malik', '9111864054784', '64054784', '333000', '2025-09-01T15:39:55+07:00', 1, '2025-09-01 08:24:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('g676oin6j0acqqulktfafenjsivkeupp', '127.0.0.1', 1756775582, ''),
('e7a8qe0t499csin6os2hbh9pvsuv8pac', '127.0.0.1', 1756776179, 0x7265646972656374696f6e7c733a37363a226148523063484d364c79397362324e68624768766333517661326c3163335276636d5576595752746157347662334a6b5a584a7a4c32646c644639306233526862463976636d526c63673d3d223b);

-- --------------------------------------------------------

--
-- Struktur dari tabel `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `name` varchar(32) NOT NULL,
  `subject` varchar(128) DEFAULT NULL,
  `email` varchar(64) NOT NULL,
  `message` mediumtext NOT NULL,
  `contact_date` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `reply_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `contacts`
--

INSERT INTO `contacts` (`id`, `parent_id`, `name`, `subject`, `email`, `message`, `contact_date`, `status`, `reply_at`) VALUES
(1, NULL, 'Agung Tri Saputra', 'Pengiriman kok lama?', 'martinms.za@gmail.com', 'pengiriman pesanan saya kok lama ya', '2020-03-29 07:40:13', 2, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(32) NOT NULL,
  `credit` decimal(8,2) NOT NULL,
  `start_date` date NOT NULL,
  `expired_date` date NOT NULL,
  `is_active` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `coupons`
--

INSERT INTO `coupons` (`id`, `name`, `code`, `credit`, `start_date`, `expired_date`, `is_active`) VALUES
(4, 'Berbagi Ramadhan', 'RAMADHAN2021', '5000.00', '2021-05-02', '2021-05-09', NULL),
(5, 'WELCOME MAY', 'MAY22', '4000.00', '2022-05-01', '2022-05-31', 1),
(6, 'test1', 'kupon', '100000.00', '2022-06-22', '2022-06-30', 1),
(7, 'Tes Kupon', 'TEST', '999999.99', '2022-10-29', '2022-11-09', 1),
(8, 'kupon baru', 'KUPONTAHUNBARU', '20000.00', '2022-11-03', '2022-11-30', 1),
(9, 'KUPON', 'TAHUNBARU', '12000.00', '2022-11-13', '2022-11-30', 1),
(10, 'HUT RI', '45', '45000.00', '2022-11-17', '2022-11-17', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nik` varchar(20) NOT NULL,
  `npwp` varchar(20) NOT NULL,
  `name` varchar(191) NOT NULL,
  `phone_number` varchar(32) DEFAULT NULL,
  `province_id` int(11) NOT NULL,
  `kota_id` int(11) NOT NULL,
  `subdistrict_id` int(11) NOT NULL,
  `address` varchar(191) NOT NULL,
  `shop_name` varchar(100) NOT NULL,
  `shop_address` varchar(200) DEFAULT NULL,
  `max_credit` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `profile_picture` varchar(191) DEFAULT NULL,
  `salesman_id` int(11) NOT NULL,
  `kode_customer` varchar(10) NOT NULL,
  `va_code` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `nik`, `npwp`, `name`, `phone_number`, `province_id`, `kota_id`, `subdistrict_id`, `address`, `shop_name`, `shop_address`, `max_credit`, `level`, `profile_picture`, `salesman_id`, `kode_customer`, `va_code`) VALUES
(1, 56, 'nikcwahyu', '123123123123', 'cwahyu', '081122334455', 18, 0, 5874, 'alamatcwahyu7', 'tokowahyu7', 'jatimulyo gang ampel no 167', 25000000, 2, NULL, 54, '', 0),
(15, 81, '123456123456', '132456132456', 'custemer trial', '01230123', 0, 0, 0, 'JL,Toko baru', 'Toko Trial Baru 123', 'JL,Toko baru', 17000000, 3, NULL, 79, '', 0),
(16, 85, '12345678945613654321', '12345678945613654321', 'maulana malik', '082264054784', 18, 256, 2504, 'Jl.Semangka 31 A', 'trial umum ', 'Jl.Semangka 31 A', 0, 1, NULL, 59, '', 2147483647),
(17, 86, '123456', '123456', 'diana', '085200000000000', 0, 160, 0, 'patrang', 'dianatoko', 'jember', 1000000, 2, NULL, 59, '', 0),
(18, 87, '123123', '123123', 'cumum1', '681234567489', 0, 1, 0, 'Jember', '-', '-', 0, 1, NULL, 59, '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers_bk`
--

CREATE TABLE `customers_bk` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nik` varchar(20) NOT NULL,
  `npwp` varchar(20) NOT NULL,
  `name` varchar(191) NOT NULL,
  `phone_number` varchar(32) DEFAULT NULL,
  `address` varchar(191) NOT NULL,
  `shop_name` varchar(100) NOT NULL,
  `credit` int(11) NOT NULL,
  `max_credit` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `profile_picture` varchar(191) DEFAULT NULL,
  `salesman_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer_location`
--

CREATE TABLE `customer_location` (
  `id` int(11) NOT NULL,
  `user_id` varchar(7) NOT NULL,
  `provinsi` int(5) NOT NULL,
  `kota` int(5) NOT NULL,
  `sub_kota` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `generate_kdchart`
--

CREATE TABLE `generate_kdchart` (
  `id` int(11) NOT NULL,
  `kdchart` varchar(255) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `generate_kdchart`
--

INSERT INTO `generate_kdchart` (`id`, `kdchart`, `create_at`) VALUES
(1, 'KIU560807250001', '2025-07-08 04:16:06'),
(2, 'KIU850109250001', '2025-09-01 04:30:34'),
(3, 'KIU850109250002', '2025-09-01 04:35:33'),
(4, 'KIU850109250003', '2025-09-01 05:00:41'),
(5, 'KIU850109250004', '2025-09-01 05:13:54'),
(6, 'KIU850109250005', '2025-09-01 08:08:09'),
(7, 'KIU850109250006', '2025-09-01 08:13:51'),
(8, 'KIU850109250007', '2025-09-01 08:23:18'),
(9, 'KIU850109250008', '2025-09-01 08:24:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `message`
--

CREATE TABLE `message` (
  `id` int(10) NOT NULL,
  `salesman_id` int(10) DEFAULT NULL,
  `customer_id` int(10) NOT NULL,
  `message` mediumtext NOT NULL,
  `chat_from` int(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `reply_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coupon_id` bigint(20) DEFAULT NULL,
  `order_number` varchar(16) NOT NULL,
  `kd_faktur` varchar(35) NOT NULL,
  `invoice_number` text NOT NULL,
  `ttb_number` text DEFAULT NULL,
  `order_status` enum('1','2','3','4','5','6','7','8','9') DEFAULT '1',
  `order_date` datetime NOT NULL,
  `total_price` decimal(12,2) DEFAULT NULL,
  `total_items` int(10) DEFAULT NULL,
  `payment_method` int(11) DEFAULT 1,
  `shipping_method` int(1) NOT NULL,
  `delivery_data` text DEFAULT NULL,
  `delivered_date` datetime DEFAULT NULL,
  `deliver_by` varchar(15) DEFAULT NULL,
  `finish_date` datetime DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `jenis_pengiriman` text NOT NULL,
  `estimasi_kirim` text NOT NULL,
  `shipping_cost` text DEFAULT '0',
  `insurance` text DEFAULT '0',
  `rating` int(11) DEFAULT NULL,
  `rating_desc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `coupon_id`, `order_number`, `kd_faktur`, `invoice_number`, `ttb_number`, `order_status`, `order_date`, `total_price`, `total_items`, `payment_method`, `shipping_method`, `delivery_data`, `delivered_date`, `deliver_by`, `finish_date`, `due_date`, `jenis_pengiriman`, `estimasi_kirim`, `shipping_cost`, `insurance`, `rating`, `rating_desc`) VALUES
(1, 85, NULL, 'KRT1925185326', 'KIU850109250002', '', NULL, '7', '2025-09-01 11:35:33', '16500.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-01', 'jnt', '0', '0', '0', NULL, NULL),
(2, 85, NULL, 'HAL1925185495', 'KIU850109250003', '', NULL, '7', '2025-09-01 12:00:41', '99000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-01', 'jne', '0', '0', '0', NULL, NULL),
(3, 85, NULL, 'ZOQ1925185402', 'KIU850109250004', '', NULL, '7', '2025-09-01 12:13:54', '20000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-01', 'pos', '0', '0', '0', NULL, NULL),
(4, 56, NULL, 'GVS1925156683', 'KIU560109250005', 'GVS192515', NULL, '2', '2025-09-01 12:46:22', '190000.00', 1, 1, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"081122334455\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2025-09-01 00:00:00', 'k12', '2025-09-01 12:51:53', '2025-10-01', '89', '0', '0', '0', 5, ''),
(5, 85, NULL, 'HZW1925185128', 'KIU850109250005', '', NULL, '7', '2025-09-01 15:08:09', '25000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-01', 'jne', '0', '0', '0', NULL, NULL),
(6, 85, NULL, 'EBL1925185470', 'KIU850109250006', '', NULL, '7', '2025-09-01 15:13:51', '99000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-01', 'jne', '0', '0', '0', NULL, NULL),
(7, 56, NULL, 'BOS1925156108', 'KIU560109250007', '', NULL, '9', '2025-09-01 15:21:59', '19000.00', 1, 1, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"081122334455\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-10-01', '89', '0', '0', '0', NULL, NULL),
(8, 85, NULL, 'NCL1925185602', 'KIU850109250007', '', NULL, '7', '2025-09-01 15:23:18', '20000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-01', 'jne', '0', '0', '0', NULL, NULL),
(9, 85, NULL, 'LLB1925185380', 'KIU850109250008', '', NULL, '2', '2025-09-01 15:24:55', '250000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-01', 'jne', '0', '0', '0', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders_bk`
--

CREATE TABLE `orders_bk` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coupon_id` bigint(20) DEFAULT NULL,
  `order_number` varchar(16) NOT NULL,
  `invoice_number` text NOT NULL,
  `ttb_number` text DEFAULT NULL,
  `order_status` enum('1','2','3','4','5','6','7','8') DEFAULT '1',
  `order_date` datetime NOT NULL,
  `total_price` decimal(12,2) DEFAULT NULL,
  `total_items` int(10) DEFAULT NULL,
  `payment_method` int(11) DEFAULT 1,
  `shipping_method` int(1) NOT NULL,
  `delivery_data` text DEFAULT NULL,
  `delivered_date` datetime DEFAULT NULL,
  `deliver_by` varchar(15) DEFAULT NULL,
  `finish_date` datetime DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `shipping_cost` text DEFAULT '0',
  `insurance` text DEFAULT '0',
  `rating` int(11) DEFAULT NULL,
  `rating_desc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `order_qty` int(10) NOT NULL,
  `order_price` decimal(10,2) DEFAULT NULL,
  `satuan` int(11) NOT NULL,
  `satuan_text` text NOT NULL,
  `satuan_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `order_qty`, `order_price`, `satuan`, `satuan_text`, `satuan_qty`) VALUES
(1, 1, 18, 1, '16500.00', 1, 'Pcs', 10),
(2, 2, 1, 1, '99000.00', 1, 'Pcs', 10),
(3, 3, 8, 1, '20000.00', 1, 'Pcs', 10),
(4, 4, 8, 1, '190000.00', 2, 'Box', 10),
(5, 5, 9, 1, '25000.00', 1, 'Pcs', 10),
(6, 6, 1, 1, '99000.00', 1, 'Pcs', 10),
(7, 7, 8, 1, '19000.00', 1, 'Pcs', 10),
(8, 8, 8, 1, '20000.00', 1, 'Pcs', 10),
(9, 9, 9, 1, '250000.00', 2, 'Box', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items_bk`
--

CREATE TABLE `order_items_bk` (
  `id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `order_qty` int(10) NOT NULL,
  `order_price` decimal(8,2) DEFAULT NULL,
  `satuan` int(11) NOT NULL,
  `satuan_text` text NOT NULL,
  `satuan_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `payment_price` decimal(11,2) DEFAULT NULL,
  `payment_date` datetime NOT NULL,
  `picture_name` varchar(191) DEFAULT NULL,
  `payment_status` enum('1','2','3') DEFAULT '1',
  `confirmed_date` datetime DEFAULT NULL,
  `payment_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_price`, `payment_date`, `picture_name`, `payment_status`, `confirmed_date`, `payment_data`) VALUES
(1, 4, '190000.00', '2025-09-01 12:57:11', '-', '1', NULL, '{\"transfer_to\":\"bank-bca\",\"source\":{\"bank\":\"VA-BRI\",\"name\":\"cwahyu\",\"number\":\"012345\"}}');

-- --------------------------------------------------------

--
-- Struktur dari tabel `piutang`
--

CREATE TABLE `piutang` (
  `id` int(11) NOT NULL,
  `no_faktur` text NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `payment_price` int(11) NOT NULL,
  `pay` int(11) NOT NULL DEFAULT 0,
  `payment_date` date NOT NULL,
  `payment_status` int(11) NOT NULL,
  `confirm_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) NOT NULL,
  `category_id` int(10) DEFAULT NULL,
  `sku` varchar(32) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `picture_name` varchar(191) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `price_2` int(11) NOT NULL,
  `price_3` int(11) NOT NULL,
  `stock` int(10) NOT NULL,
  `current_discount` double NOT NULL,
  `product_unit` varchar(32) DEFAULT NULL,
  `product_unit_1` varchar(25) NOT NULL,
  `product_unit_2` varchar(25) NOT NULL,
  `product_unit_value` text NOT NULL,
  `product_type` varchar(25) NOT NULL,
  `product_unit_weight` int(25) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `add_date` datetime DEFAULT NULL,
  `user_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `description`, `picture_name`, `price`, `price_2`, `price_3`, `stock`, `current_discount`, `product_unit`, `product_unit_1`, `product_unit_2`, `product_unit_value`, `product_type`, `product_unit_weight`, `is_available`, `add_date`, `user_level`) VALUES
(1, 2, 'SB750372', 'Ace-One', 'NULL', 'ace_one_99000.jpg', 99000, 98000, 97000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '3', 1000, 1, '0000-00-00 00:00:00', 0),
(2, 2, 'BS350420', 'Akalis 500 SC', 'NULL', 'akalis_500sc_268500.jpg', 0, 268500, 228500, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '2', 1000, 1, '0000-00-00 00:00:00', 0),
(4, 2, 'TS120790', 'Sinergy 300 EC', 'NULL', 'sinergy300ec_155000.jpg', 0, 120000, 155000, 1000, 0, 'Botol', 'Pcs', 'Box', '10', '2', 1000, 1, '0000-00-00 00:00:00', 0),
(5, 2, 'WS120811', 'Cornbelt 336 SC', 'NULL', 'cornbelt_336_sc_290000.jpg', 0, 0, 290000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(8, 2, 'PS220885', 'Baltiko', 'NULL', 'ace_one_99000.jpg', 20000, 19000, 17500, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '2', 1000, 1, '0000-00-00 00:00:00', 0),
(9, 2, 'AB450163', 'Topida 25 WP', 'NULL', 'ace_one_99000.jpg', 25000, 0, 0, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '3', 1000, 1, '0000-00-00 00:00:00', 0),
(10, 2, 'BMS120283', 'Paskal 50 WP', 'NULL', 'paskal_50wp_23000.jpg', 23000, 0, 0, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(11, 2, 'URS13301', 'Biosoft 500ml', 'NULL', 'Biosoft_-_500ml_101500.jpg', 0, 0, 101500, 1000, 0, 'Botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(12, 2, 'BPS15347', 'Nostro 440 EC - 500ml (FUNGISIDA)', 'NULL', 'Nostro_440_EC_-_500ml_(FUNGISIDA)_100000.jpg', 0, 110000, 100000, 1000, 0, 'Botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(13, 2, 'KPS223370', 'Hexacar 100 SC - 250ml (FUNGISIDA)', 'NULL', 'Hexacar_100_SC_-_250ml_(FUNGISIDA)_50000.jpg', 0, 55000, 50000, 1000, 0, 'Botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(14, 2, 'CMS410424', 'BM Ematez 19 EC - 100ml (INSEKTISIDA)', 'NULL', 'BM_Ematez_19_EC_-_100ml_(INSEKTISIDA)_45000.jpg', 0, 0, 45000, 1000, 0, 'Botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(15, 2, 'B5MP8100547', 'Blast 500 ML', 'NULL', '1820703_e3c79fa6-7398-41d6-a8ce-ee27b694d3e9.jpg', 0, 0, 80000, 1000, 0, 'botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(18, 1, 'QMK35GP1805', 'Qiuvita merah K 32 500 gram', '', 'NULL', 16500, 0, 0, 1000, 0, 'pack', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(19, 1, 'FF111000791', 'FUNGISIDA', 'NULL', 'gambar_otak.png', 13000, 11000, 10000, 1000, 0, 'BOX', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(20, 9, 'OII112500872', 'OBAT ISEKTIDA', 'NULL', 'abstrak.jpeg', 15000, 12500, 11000, 1000, 0, 'BOX', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_category`
--

CREATE TABLE `product_category` (
  `id` int(10) NOT NULL,
  `name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `product_category`
--

INSERT INTO `product_category` (`id`, `name`) VALUES
(1, 'Fungisida'),
(2, 'Herbisida'),
(9, 'Insektisida'),
(10, 'Obat-obat'),
(12, 'Sarana Pertanian'),
(13, 'Lain Lain'),
(14, 'benih'),
(15, 'ALAT PERTANIAN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `promo`
--

CREATE TABLE `promo` (
  `id` bigint(20) NOT NULL,
  `product_id` int(11) NOT NULL,
  `credit` decimal(8,2) NOT NULL,
  `start_date` date NOT NULL,
  `expired_date` date NOT NULL,
  `is_active` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `promo`
--

INSERT INTO `promo` (`id`, `product_id`, `credit`, `start_date`, `expired_date`, `is_active`) VALUES
(1, 1, '9000.00', '2022-10-04', '2022-11-25', 1),
(2, 2, '8500.00', '2022-10-04', '2022-12-16', 1),
(3, 4, '8000.00', '2022-11-09', '2022-11-16', 1),
(4, 10, '2500.00', '2022-11-10', '2022-11-24', 1),
(5, 10, '2500.00', '2022-11-10', '2022-11-24', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `title` varchar(191) DEFAULT NULL,
  `review_text` mediumtext NOT NULL,
  `review_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` int(10) NOT NULL,
  `key` varchar(32) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `key`, `content`) VALUES
(1, 'current_theme_name', 'fastkart'),
(2, 'store_name', 'KIU STORE'),
(3, 'store_phone_number', '08111111111111'),
(4, 'store_email', 'kiu@gmail.com'),
(5, 'store_tagline', 'Official Store PT. Karisma Indoagro Universal'),
(6, 'store_logo', 'Logo.png'),
(7, 'max_product_image_size', '20000'),
(8, 'store_description', 'Belanja mudah hanya di KIU STORE'),
(9, 'store_address', 'Jl. Semeru 89 Ajung – Jember 68175'),
(10, 'min_shop_to_free_shipping_cost', '20000'),
(11, 'shipping_cost', '3000'),
(12, 'payment_banks', '{\"bank-bca\":{\"bank\":\"BANK BCA\",\"number\":\"20348483\",\"name\":\"PT. KARISMA INDOAGRO UNIVERSAL\"},\"bank-mandiri\":{\"bank\":\"BANK MANDIRI\",\"number\":\"10034453\",\"name\":\"PT. KARISMA INDOAGRO UNIVERSAL\"},\"bank-bri\":{\"bank\":\"BANK BRI\",\"number\":\"310337234005700\",\"name\":\"PT. KARISMA INDOAGRO UNIVERSAL\"}}');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbtestongkir`
--

CREATE TABLE `tbtestongkir` (
  `id` int(11) NOT NULL,
  `jsongkir` varchar(255) NOT NULL,
  `kd_faktur` varchar(25) NOT NULL,
  `sjasa` varchar(255) NOT NULL,
  `idcustomer` varchar(255) NOT NULL,
  `status` int(2) NOT NULL,
  `create_at` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbtestongkir`
--

INSERT INTO `tbtestongkir` (`id`, `jsongkir`, `kd_faktur`, `sjasa`, `idcustomer`, `status`, `create_at`) VALUES
(1, 'J&T Express;EZ;Reguler;;66000', 'KIU850109250002', 'jnt', '85', 3, '2025-09-01'),
(2, 'Jalur Nugraha Ekakurir (JNE);REG;Layanan Reguler;6 day;83000', 'KIU850109250003', 'jne', '85', 3, '2025-09-01'),
(3, 'POS Indonesia (POS);Pos Reguler;240;11 day;77500', 'KIU850109250004', 'pos', '85', 3, '2025-09-01'),
(4, 'Jalur Nugraha Ekakurir (JNE);REG;Layanan Reguler;6 day;83000', 'KIU850109250005', 'jne', '85', 3, '2025-09-01'),
(5, 'Jalur Nugraha Ekakurir (JNE);REG;Layanan Reguler;6 day;83000', 'KIU850109250006', 'jne', '85', 3, '2025-09-01'),
(6, 'Jalur Nugraha Ekakurir (JNE);REG;Layanan Reguler;6 day;83000', 'KIU850109250007', 'jne', '85', 3, '2025-09-01'),
(7, 'Jalur Nugraha Ekakurir (JNE);REG;Layanan Reguler;6 day;83000', 'KIU850109250008', 'jne', '85', 3, '2025-09-01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tmp_cart`
--

CREATE TABLE `tmp_cart` (
  `id` int(11) NOT NULL,
  `kdchart` varchar(255) NOT NULL,
  `idbarang` int(11) NOT NULL,
  `idcustomer` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `satuan` int(11) NOT NULL,
  `satuan_text` varchar(191) NOT NULL,
  `satuan_qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `name` text NOT NULL,
  `product_type` varchar(25) NOT NULL,
  `product_weight` int(11) NOT NULL,
  `total_weight` int(25) NOT NULL,
  `sts_ongkir` int(2) NOT NULL,
  `create_at` text NOT NULL,
  `last_action` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '1 = admin, 2 = customer',
  `register_date` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `profile_picture`, `role`, `register_date`, `status`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$10$Brm3RNWFKvZ1e0ej1vBz9.QbFMW21q0l/iDSt5aDOoGj9zlLFuxh6', 'agung1.png', 'admin', NULL, 1),
(54, 'swahyu', 'swahyu@gmail.com', NULL, '$2y$10$Yl0H.az2oYdN8JsXgUagCeQBqxf1WIUphyd9y8AE/R6HzsNJfPuf2', NULL, 'salesman', '2022-09-22 17:35:28', 1),
(55, 'dwahyu', 'dwahyu@gmail.com', NULL, '$2y$10$64mC/JHkEyBfhymqU4b5b.oJyh3H46Xdsr6gPlKfUWLKs1s1cFU.q', NULL, 'distribusi', '2022-09-22 17:35:57', 1),
(56, NULL, 'cwahyu@gmail.com', NULL, '$2y$10$NW0poORtQS34wLh.I2knS.bbbWzSVlk71BzcCByVEfuTJoiPp5PBa', NULL, 'customer', '2022-09-22 17:37:38', 1),
(59, 'Admin Penjualan', 'penjualan@gmail.com', NULL, '$2y$10$oXf2VHVBbngML9Pl4WevyORyeMQNKzQ.zLBfk/NfzU.mwpSh6MWZ6', NULL, 'salesman', '2022-09-23 04:29:41', 1),
(62, 'Admin Online', 'adminonline@gmail.com', NULL, '$2y$10$Piw9jb8Sd.SVUh2SL4PRJuFD5aJ3bSDOHxy78I/dAPUeJ9GBtR6QW', NULL, 'adminonline', '2022-10-26 20:29:19', 1),
(63, 'Keuangan', 'keuangan@gmail.com', NULL, '$2y$10$YhoKD4bwf8eW9fSKKtmluujAswZtex5M/xS07wIw4W/t2WdglqMtW', NULL, 'keuangan', '2022-10-26 22:19:19', 1),
(72, 'Kadep', 'kadep@gmail.com', NULL, '$2y$10$Brm3RNWFKvZ1e0ej1vBz9.QbFMW21q0l/iDSt5aDOoGj9zlLFuxh6', NULL, 'kadep', '2022-11-02 11:56:19', 1),
(73, 'Kadep 1', 'kadep1@gmail.com', NULL, '$2y$10$8OhbMGn21ZMLHqj1ilLjKeQzMBsvciI7qNXFftJHtf4e8RSwIk99.', NULL, 'kadep', '2022-11-03 09:37:40', 1),
(74, NULL, 'faisal@gmail.com', NULL, '$2y$10$y9dKpR6lsu6FbyX5/sJY9.SB2Al9a7Pi9LpnN5jq18K.uxRskevi.', NULL, 'customer', '2022-11-03 10:02:40', 0),
(79, 'Sales Trial', 'sales@gmail.com', NULL, '$2y$10$IRZLM8vlw9yvDnBO8R96We1pswHQCnTRATn/i1yFSBDY7wP82ipVu', NULL, 'salesman', '2022-11-30 13:18:01', 1),
(80, NULL, 'custrial@gmail.com', NULL, '$2y$10$o2rPkdmMxXRzjEmhddDun.zGKicsVNmVEl9cjX3u1kI57B47v8ZBS', NULL, 'customer', '2022-11-30 13:20:23', 1),
(81, NULL, 'customer2@gmail.com', NULL, '$2y$10$xXQXksNLB8SERgEnjDHnQur9gXHaijVS4usUdgKPW2MkNHr4DwxRW', NULL, 'customer', '2022-11-30 13:36:45', 1),
(85, NULL, 'cumum@gmail.com', NULL, '$2y$10$JdC3psY/Y1wrRdRedKykP.Y1FYMYcoQ66ZCOw2fYl8RhI2NTfm2hy', NULL, 'customer', '2024-10-07 21:46:12', 1),
(86, NULL, 'diana_w@gmail.com', NULL, '$2y$10$Rqr2B94rDR/e61fg8Uq45eS85sp7DJjRla43uiSqkLSJDQoTYfYma', NULL, 'customer', '2025-02-28 13:04:24', 1),
(87, NULL, 'cumum1@gmail.com', NULL, '$2y$10$YdLJCYIlC5wFJX6zhBT5yO7OdiWGUFYkHCoaxR003w61K4MRIlXD2', NULL, 'customer', '2025-02-28 14:51:17', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_bk`
--

CREATE TABLE `users_bk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `username` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '1 = admin, 2 = customer',
  `register_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users_bk`
--

INSERT INTO `users_bk` (`id`, `name`, `email`, `email_verified_at`, `username`, `password`, `profile_picture`, `role`, `register_date`) VALUES
(1, 'Admiistrator', 'admin@local.test', NULL, 'admin', '$2y$10$Brm3RNWFKvZ1e0ej1vBz9.QbFMW21q0l/iDSt5aDOoGj9zlLFuxh6', 'agung1.png', 'admin', NULL),
(7, 'Customer ', 'customer@local.test', NULL, 'customer', '$2y$10$6C/A5Yy1gt4yhStWDWN1M.isBaznzDc.MZJdIj7UddW3.qIX5vDvK', NULL, 'customer', '2020-03-29 08:14:30'),
(8, NULL, 'martinms.za@gmail.com', NULL, 'test', '$2y$10$gj4QxFnTj0dlpwJvT4aJiOM5UW6uCt7MdafC6VrnqsKDi0/JKmsLS', NULL, 'customer', '2021-05-07 10:25:08'),
(1, 'Admiistrator', 'admin@local.test', NULL, 'admin', '$2y$10$Brm3RNWFKvZ1e0ej1vBz9.QbFMW21q0l/iDSt5aDOoGj9zlLFuxh6', 'agung1.png', 'admin', NULL),
(7, 'Customer ', 'customer@local.test', NULL, 'customer', '$2y$10$6C/A5Yy1gt4yhStWDWN1M.isBaznzDc.MZJdIj7UddW3.qIX5vDvK', NULL, 'customer', '2020-03-29 08:14:30'),
(8, NULL, 'martinms.za@gmail.com', NULL, 'test', '$2y$10$gj4QxFnTj0dlpwJvT4aJiOM5UW6uCt7MdafC6VrnqsKDi0/JKmsLS', NULL, 'customer', '2021-05-07 10:25:08');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `banner_product`
--
ALTER TABLE `banner_product`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `briva_api`
--
ALTER TABLE `briva_api`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indeks untuk tabel `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_contacts_contacts` (`parent_id`);

--
-- Indeks untuk tabel `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_customers_users` (`user_id`);

--
-- Indeks untuk tabel `customer_location`
--
ALTER TABLE `customer_location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_userid` (`user_id`);

--
-- Indeks untuk tabel `generate_kdchart`
--
ALTER TABLE `generate_kdchart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_chartkd` (`kdchart`);

--
-- Indeks untuk tabel `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_contacts_contacts` (`salesman_id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_orders_users` (`user_id`),
  ADD KEY `FK_orders_coupons` (`coupon_id`);

--
-- Indeks untuk tabel `orders_bk`
--
ALTER TABLE `orders_bk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_orders_users` (`user_id`),
  ADD KEY `FK_orders_coupons` (`coupon_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `order_items_bk`
--
ALTER TABLE `order_items_bk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indeks untuk tabel `piutang`
--
ALTER TABLE `piutang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_products_product_category` (`category_id`);

--
-- Indeks untuk tabel `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_reviews_users` (`user_id`),
  ADD KEY `FK_reviews_orders` (`order_id`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `tbtestongkir`
--
ALTER TABLE `tbtestongkir`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tmp_cart`
--
ALTER TABLE `tmp_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `users_email_unique` (`email`) USING BTREE;

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `banner_product`
--
ALTER TABLE `banner_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `briva_api`
--
ALTER TABLE `briva_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `customer_location`
--
ALTER TABLE `customer_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `generate_kdchart`
--
ALTER TABLE `generate_kdchart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `message`
--
ALTER TABLE `message`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `orders_bk`
--
ALTER TABLE `orders_bk`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `order_items_bk`
--
ALTER TABLE `order_items_bk`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `piutang`
--
ALTER TABLE `piutang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `product_category`
--
ALTER TABLE `product_category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `promo`
--
ALTER TABLE `promo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `tbtestongkir`
--
ALTER TABLE `tbtestongkir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tmp_cart`
--
ALTER TABLE `tmp_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `FK_contacts_contacts` FOREIGN KEY (`parent_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ketidakleluasaan untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `FK_customers_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_orders_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_orders_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
