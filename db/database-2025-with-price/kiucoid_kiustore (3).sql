-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Okt 2025 pada 10.37
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `banner_product`
--

INSERT INTO `banner_product` (`id`, `product_id`, `banner_image`, `created_at`) VALUES
(7, 2, 'BANNER_AMISTARTOP1.jpg', '2025-09-23 14:14:05'),
(8, 7, 'BANNER_AGUS_500SC.jpg', '2025-09-23 14:14:23'),
(9, 35, 'BANNER_NK7328_SUMO.jpg', '2025-09-23 14:14:35');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `briva_api`
--

INSERT INTO `briva_api` (`id`, `order_number`, `kd_faktur`, `user_id`, `name`, `va_code`, `userno`, `total_price_topay`, `exp_date`, `status`, `create_at`) VALUES
(7, 'LLB1925185380', 'KIU850109250008', 85, 'maulana malik', '9111864054784', '64054784', '333000', '2025-09-01T15:39:55+07:00', 3, '2025-09-04 08:40:10'),
(11, 'GCJ3925185205', 'KIU850309250001', 85, 'maulana malik', '9111864054784', '64054784', '1115000.00', '2025-09-03T14:41:56+07:00', 3, '2025-09-04 07:32:28'),
(13, 'SKC4925285390', 'KIU850409250001', 85, 'maulana malik', '9111864054784', '64054784', '2711500.00', '2025-09-04T16:02:16+07:00', 1, '2025-09-04 08:47:17'),
(14, 'WUX10925187536', 'KIU871009250001', 87, 'cumum1', '9111834567489', '34567489', '2880000.00', '2025-09-10T09:22:57+07:00', 3, '2025-09-10 02:27:21'),
(15, 'QAE10925187697', 'KIU871009250003', 87, 'cumum1', '9111834567489', '34567489', '2650000.00', '2025-09-10T13:09:25+07:00', 3, '2025-09-10 06:11:31'),
(17, 'IPA10925187751', 'KIU871009250005', 87, 'cumum1', '9111834567489', '34567489', '4400000.00', '2025-09-10T15:11:44+07:00', 3, '2025-09-10 08:11:55'),
(18, 'DPL11925187460', 'KIU871109250001', 87, 'cumum1', '9111834567489', '34567489', '2650000.00', '2025-09-11T09:20:15+07:00', 3, '2025-09-11 02:20:18'),
(19, 'FHK11925287746', 'KIU871109250002', 87, 'cumum1', '9111834567489', '34567489', '4850000.00', '2025-09-11T09:37:27+07:00', 3, '2025-09-11 02:46:48'),
(21, 'LSI29925185807', 'KIU852909250001', 85, 'maulana malik', '9111864054784', '64054784', '2650000.00', '2025-09-29T11:12:54+07:00', 2, '2025-09-29 04:06:04'),
(22, 'HFO29925185516', 'KIU852909250002', 85, 'maulana malik', '9111864054784', '64054784', '144000.00', '2025-09-29T11:33:28+07:00', 1, '2025-09-29 04:18:28'),
(23, 'HKR29925187605', 'KIU872909250003', 87, 'cumum1', '9111834567489', '34567489', '220000.00', '2025-09-29T12:18:30+07:00', 3, '2025-09-29 05:18:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('49jus7l4nj80cdie60bbddimsej9lbgh', '::1', 1759378740, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393337383734303b),
('hfu5poruv8bopeegtdhuete13a008u10', '::1', 1759379057, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393337393035373b7265646972656374696f6e7c4e3b5f5f4143544956455f53455353494f4e5f444154417c733a3238303a2263363065646631356235653937353764643964326439343834366336656661323331616536323630623239636434363538666638393536343633363533383136653261636162653734643231633062303063316236623134393362396233333238383666666163656464636130326336303539326664373736363532363532306d2f524f68396d38566a2f344a5064416242743947564f373359495947426a557574726a506a39696c5832387248527161524d3835686336334549556d495450315459434a70697774643771664a715647454770652b6a35684556794842346379303845613664382f2b43526a2b4571504543457670646d6a63785049366d79473056477a2b6c58304d676c34434133314e666e6c673d3d223b),
('duuu241kk7u1lvc545e6selef66j8sej', '127.0.0.1', 1759378917, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393337383931303b),
('0nogp83l1ng11r52mifskm7ovi9949m9', '::1', 1759379447, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393337393434373b7265646972656374696f6e7c4e3b5f5f4143544956455f53455353494f4e5f444154417c733a3238303a2263363065646631356235653937353764643964326439343834366336656661323331616536323630623239636434363538666638393536343633363533383136653261636162653734643231633062303063316236623134393362396233333238383666666163656464636130326336303539326664373736363532363532306d2f524f68396d38566a2f344a5064416242743947564f373359495947426a557574726a506a39696c5832387248527161524d3835686336334549556d495450315459434a70697774643771664a715647454770652b6a35684556794842346379303845613664382f2b43526a2b4571504543457670646d6a63785049366d79473056477a2b6c58304d676c34434133314e666e6c673d3d223b),
('qc3di427oni7goi1m4cki2ps70pduaom', '::1', 1759382003, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393338323030333b7265646972656374696f6e7c4e3b5f5f4143544956455f53455353494f4e5f444154417c733a3238303a2263363065646631356235653937353764643964326439343834366336656661323331616536323630623239636434363538666638393536343633363533383136653261636162653734643231633062303063316236623134393362396233333238383666666163656464636130326336303539326664373736363532363532306d2f524f68396d38566a2f344a5064416242743947564f373359495947426a557574726a506a39696c5832387248527161524d3835686336334549556d495450315459434a70697774643771664a715647454770652b6a35684556794842346379303845613664382f2b43526a2b4571504543457670646d6a63785049366d79473056477a2b6c58304d676c34434133314e666e6c673d3d223b),
('qmtaprjmckj9em70um6g7bb5n6jgohjc', '::1', 1759382012, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393338323030333b7265646972656374696f6e7c4e3b6c6f67696e5f666c6173687c733a31363a22426572686173696c206c6f676f757421223b5f5f63695f766172737c613a313a7b733a31313a226c6f67696e5f666c617368223b733a333a226f6c64223b7d),
('i6de73ac5k0gmed1nrvh2nbc5369foi1', '::1', 1759382111, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393338323032303b),
('9163jib2mvo71udv4e73mubebivfqarj', '::1', 1759386527, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393338363532373b7265646972656374696f6e7c4e3b757365725f69647c733a323a223835223b73616c65736d616e5f69647c733a323a223539223b757365725f6c6576656c7c733a313a2231223b6c6f67696e5f666c6173687c733a31363a22426572686173696c206c6f676f757421223b5f5f63695f766172737c613a313a7b733a31313a226c6f67696e5f666c617368223b733a333a226f6c64223b7d),
('d4b6u3vkaciptk9le18sb44qr6vvuc5k', '::1', 1759386870, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393338363837303b7265646972656374696f6e7c4e3b757365725f69647c733a323a223835223b73616c65736d616e5f69647c733a323a223539223b757365725f6c6576656c7c733a313a2231223b5f5f4143544956455f53455353494f4e5f444154417c733a3334343a2265376665326534316465656231376166663536376433613261356434306232303962386535613830666238363164636536376663613632393362373333613762613735376361653564366433666361333836366135303237376331633764363438333064616165326230346535323464323738653864636439343164396338376571736f7a7778315a49717252364b4251705247332f526262722b754668754752492f4f5643383963545a2b7265494c5a555559486b546830737a466f752f64793169426575516966594342515654546b6a6d6f644e644c6b776e7432796d66325945617445542f316e44456d6f6f372f66696172576c4b4f4d423261336c4b3756364e6a31692f5752467275686d3470676946644d5330797a42396e34614e6c777655484a633477635838754454465a686f6f616d7435724e4b6e63436d6863712f385845627544686738412f6a455236546a4e673d3d223b),
('3t22tbov9u2dorsi9l05sk9ktqns7lsq', '::1', 1759394197, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393339343139373b7265646972656374696f6e7c4e3b757365725f69647c733a323a223835223b73616c65736d616e5f69647c733a323a223539223b757365725f6c6576656c7c733a313a2231223b5f5f4143544956455f53455353494f4e5f444154417c733a3334343a2265376665326534316465656231376166663536376433613261356434306232303962386535613830666238363164636536376663613632393362373333613762613735376361653564366433666361333836366135303237376331633764363438333064616165326230346535323464323738653864636439343164396338376571736f7a7778315a49717252364b4251705247332f526262722b754668754752492f4f5643383963545a2b7265494c5a555559486b546830737a466f752f64793169426575516966594342515654546b6a6d6f644e644c6b776e7432796d66325945617445542f316e44456d6f6f372f66696172576c4b4f4d423261336c4b3756364e6a31692f5752467275686d3470676946644d5330797a42396e34614e6c777655484a633477635838754454465a686f6f616d7435724e4b6e63436d6863712f385845627544686738412f6a455236546a4e673d3d223b),
('vcaialpc2e5ktrf0s8julgn3ag0un5kc', '::1', 1759394202, 0x5f5f63695f6c6173745f726567656e65726174657c693a313735393339343139373b7265646972656374696f6e7c4e3b757365725f69647c733a323a223835223b73616c65736d616e5f69647c733a323a223539223b757365725f6c6576656c7c733a313a2231223b5f5f4143544956455f53455353494f4e5f444154417c733a3334343a2265376665326534316465656231376166663536376433613261356434306232303962386535613830666238363164636536376663613632393362373333613762613735376361653564366433666361333836366135303237376331633764363438333064616165326230346535323464323738653864636439343164396338376571736f7a7778315a49717252364b4251705247332f526262722b754668754752492f4f5643383963545a2b7265494c5a555559486b546830737a466f752f64793169426575516966594342515654546b6a6d6f644e644c6b776e7432796d66325945617445542f316e44456d6f6f372f66696172576c4b4f4d423261336c4b3756364e6a31692f5752467275686d3470676946644d5330797a42396e34614e6c777655484a633477635838754454465a686f6f616d7435724e4b6e63436d6863712f385845627544686738412f6a455236546a4e673d3d223b);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `nik`, `npwp`, `name`, `phone_number`, `province_id`, `kota_id`, `subdistrict_id`, `address`, `shop_name`, `shop_address`, `max_credit`, `level`, `profile_picture`, `salesman_id`, `kode_customer`, `va_code`) VALUES
(1, 56, 'nikcwahyu', '123123123123', 'cwahyu', '081122334455', 18, 0, 5874, 'alamatcwahyu7', 'tokowahyu7', 'jatimulyo gang ampel no 167', 25000000, 2, NULL, 54, '', 0),
(15, 81, '123456123456', '132456132456', 'custemer trial', '01230123', 0, 0, 0, 'JL,Toko baru', 'Toko Trial Baru 123', 'JL,Toko baru', 17000000, 3, NULL, 79, '', 0),
(16, 85, '12345678945613654321', '12345678945613654321', 'maulana malik', '082264054784', 18, 391, 3944, 'Jl.Semangka 31 A', 'trial umum ', 'Jl.Semangka 31 A', 0, 1, NULL, 59, '', 2147483647),
(17, 86, '123456', '123456', 'diana', '085655909900', 0, 160, 0, 'patrang', 'dianatoko', 'jember', 1000000, 2, NULL, 59, '', 0),
(18, 87, '123123', '123123', 'cumum1', '681234567489', 18, 258, 2558, 'Jember', '-', '-', 0, 1, NULL, 59, '', 0),
(25, 94, '', '', 'customer-coba-lagi1', '082296054781', 18, 577, 5874, 'Jl.Surabaya , Jawa Timur , Surabaya , Benowo', '', NULL, 0, 1, NULL, 59, '', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `generate_kdchart`
--

CREATE TABLE `generate_kdchart` (
  `id` int(11) NOT NULL,
  `kdchart` varchar(255) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(9, 'KIU850109250008', '2025-09-01 08:24:55'),
(10, 'KIU850309250001', '2025-09-03 02:23:40'),
(11, 'KIU850309250002', '2025-09-03 08:02:17'),
(12, 'KIU850409250001', '2025-09-04 05:05:43'),
(13, 'KIU940409250002', '2025-09-04 08:58:22'),
(14, 'KIU870509250001', '2025-09-05 09:07:15'),
(15, 'KIU870609250001', '2025-09-06 06:07:50'),
(16, 'KIU870909250001', '2025-09-09 05:48:09'),
(17, 'KIU870909250002', '2025-09-09 05:51:55'),
(18, 'KIU870909250002', '2025-09-09 05:51:55'),
(19, 'KIU870909250003', '2025-09-09 06:02:56'),
(20, 'KIU870909250004', '2025-09-09 07:44:37'),
(21, 'KIU871009250001', '2025-09-10 01:34:47'),
(22, 'KIU871009250002', '2025-09-10 02:41:07'),
(23, 'KIU871009250003', '2025-09-10 05:42:28'),
(24, 'KIU871009250004', '2025-09-10 06:57:04'),
(25, 'KIU871009250005', '2025-09-10 07:40:16'),
(26, 'KIU871109250001', '2025-09-11 02:04:16'),
(27, 'KIU871109250002', '2025-09-11 02:21:40'),
(28, 'KIU871109250003', '2025-09-11 06:42:30'),
(29, 'KIU561109250004', '2025-09-11 07:06:51'),
(30, 'KIU852909250001', '2025-09-29 03:57:39'),
(31, 'KIU852909250002', '2025-09-29 04:17:41'),
(32, 'KIU872909250003', '2025-09-29 05:03:25');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(9, 85, NULL, 'LLB1925185380', 'KIU850109250008', '', NULL, '7', '2025-09-01 15:24:55', '250000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-01', 'jne', '0', '0', '0', NULL, NULL),
(10, 85, NULL, 'GCJ3925185205', 'KIU850309250001', '', NULL, '7', '2025-09-03 09:23:40', '1115000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-03', 'jne', '0', '0', '0', NULL, NULL),
(11, 85, NULL, 'QCK3925185168', 'KIU850309250002', '', NULL, '2', '2025-09-03 15:02:17', '144000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-03', 'jne', '0', '0', '0', NULL, NULL),
(12, 85, NULL, 'SKC4925285390', 'KIU850409250001', '', NULL, '2', '2025-09-04 12:05:43', '2711500.00', 2, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-04', 'jne', '0', '0', '0', NULL, NULL),
(13, 94, NULL, 'DAM4925194025', 'KIU940409250002', '', NULL, '2', '2025-09-04 15:58:22', '2650000.00', 1, 2, 5, '{\"customer\":{\"name\":\"customer-coba-lagi1\",\"phone_number\":\"082296054781\",\"address\":\"Jl.Surabaya , Jawa Timur , Surabaya , Benowo\",\"shop_name\":\"Toko customer 1\",\"shop_address\":\"Jl.Surabaya , Jawa Timur , Surabaya , Benowo\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-04', 'pos', '0', '0', '0', NULL, NULL),
(14, 87, NULL, 'ACV5925187053', 'KIU870509250001', '', NULL, '7', '2025-09-05 16:07:15', '695000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-05', 'jne', '0', '0', '0', NULL, NULL),
(15, 87, NULL, 'YLA6925187706', 'KIU870609250001', '', NULL, '7', '2025-09-06 13:07:50', '34750.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-06', 'jnt', '0', '0', '0', NULL, NULL),
(16, 87, NULL, 'AUY9925187205', 'KIU870909250001', '', NULL, '7', '2025-09-09 12:48:09', '1440000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-09', 'jne', '0', '0', '0', NULL, NULL),
(17, 87, NULL, 'KVO9925187763', 'KIU870909250002', '', NULL, '7', '2025-09-09 12:51:55', '605000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-09', 'jne', '0', '0', '0', NULL, NULL),
(18, 87, NULL, 'FBT9925187695', 'KIU870909250003', '', NULL, '7', '2025-09-09 13:02:56', '2200000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-09', 'jne', '0', '0', '0', NULL, NULL),
(19, 87, NULL, 'AYZ9925187981', 'KIU870909250004', '', NULL, '7', '2025-09-09 14:44:37', '1115000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-09', 'jne', '0', '0', '0', NULL, NULL),
(20, 87, NULL, 'WUX10925187536', 'KIU871009250001', '', NULL, '7', '2025-09-10 08:34:47', '2880000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-10', 'jne', '0', '0', '0', NULL, NULL),
(21, 87, NULL, 'EXW10925287718', 'KIU871009250002', '', NULL, '7', '2025-09-10 09:41:07', '1386500.00', 2, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-10', 'jne', '0', '0', '0', NULL, NULL),
(22, 87, NULL, 'QAE10925187697', 'KIU871009250003', '', NULL, '7', '2025-09-10 12:42:28', '2650000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-10', 'jne', '0', '0', '0', NULL, NULL),
(23, 87, NULL, 'KMI10925187812', 'KIU871009250004', '', NULL, '7', '2025-09-10 13:57:04', '423500.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-10', 'jne', '0', '0', '0', NULL, NULL),
(24, 87, NULL, 'IPA10925187751', 'KIU871009250005', '', NULL, '7', '2025-09-10 14:40:16', '4400000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-10', 'jne', '0', '0', '0', NULL, NULL),
(25, 87, NULL, 'DPL11925187460', 'KIU871109250001', '', NULL, '7', '2025-09-11 09:04:16', '2650000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-11', 'jne', '0', '0', '0', NULL, NULL),
(26, 87, NULL, 'FHK11925287746', 'KIU871109250002', '', NULL, '7', '2025-09-11 09:21:40', '4850000.00', 2, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-11', 'jne', '0', '0', '0', NULL, NULL),
(27, 87, NULL, 'OBU11925187208', 'KIU871109250003', '', NULL, '7', '2025-09-11 13:42:30', '1325000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-11', 'jne', '0', '0', '0', NULL, NULL),
(28, 56, NULL, 'XOZ11925156213', 'KIU561109250004', '', NULL, '1', '2025-09-11 14:06:51', '2703000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"081122334455\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-11', '89', '0', '0', '0', NULL, NULL),
(29, 56, NULL, 'UAP22925156985', 'KIU562209250001', '', NULL, '4', '2025-09-22 13:28:26', '354450.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"081122334455\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-22', '89', '0', '0', '0', NULL, NULL),
(30, 85, NULL, 'LSI29925185807', 'KIU852909250001', '', NULL, '3', '2025-09-29 10:57:39', '2650000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-29', 'jne', '0', '0', '0', NULL, NULL),
(31, 85, NULL, 'HFO29925185516', 'KIU852909250002', '', NULL, '2', '2025-09-29 11:17:41', '144000.00', 1, 2, 5, '{\"customer\":{\"name\":\"maulana malik\",\"phone_number\":\"082264054784\",\"address\":\"Jl.Semangka 31 A\",\"shop_name\":\"trial umum \",\"shop_address\":\"Jl.Semangka 31 A\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-29', 'jne', '0', '0', '0', NULL, NULL),
(32, 87, NULL, 'HKR29925187605', 'KIU872909250003', '', NULL, '7', '2025-09-29 12:03:25', '220000.00', 1, 2, 5, '{\"customer\":{\"name\":\"cumum1\",\"phone_number\":\"681234567489\",\"address\":\"Jember\",\"shop_name\":\"-\",\"shop_address\":\"-\"},\"note\":\"\"}', NULL, NULL, NULL, '2025-09-29', 'jne', '0', '0', '0', NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `order_qty`, `order_price`, `satuan`, `satuan_text`, `satuan_qty`) VALUES
(34, 30, 5, 1, '2650000.00', 2, 'Box', 10),
(35, 31, 2, 1, '144000.00', 1, 'Pcs', 10),
(36, 32, 7, 1, '220000.00', 1, 'Pcs', 10);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) NOT NULL,
  `category_id` int(10) DEFAULT NULL,
  `sku` varchar(32) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `description`, `picture_name`, `price`, `price_2`, `price_3`, `stock`, `current_discount`, `product_unit`, `product_unit_1`, `product_unit_2`, `product_unit_value`, `product_type`, `product_unit_weight`, `is_available`, `add_date`, `user_level`) VALUES
(1, 1, 'QOLS00001', 'Abado 50 WP 20 X 20 X 10 gr', 'ABADO 50 WP merupakan fungisida sistemik dan protektif berbentuk tepung yang dapat disuspensikan untuk mengendalikan penyakit busuk buah kakao dan penyakit hawar daun pada tanaman kentang.\nBahan Aktif : Dimetomorf 50%\r\nIsi Bersih: 10 gr', 'ABADO_50WP_10GRAM.jpg', 7000, 7140, 7350, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(2, 1, 'QOLS00002', 'Abado 50 WP 20 X 6 X 40 gr', 'ABADO 50 WP merupakan fungisida sistemik dan protektif berbentuk tepung yang dapat disuspensikan untuk mengendalikan penyakit busuk buah kakao dan penyakit hawar daun pada tanaman kentang.\nBahan Aktif : Dimetomorf 50%\r\nIsi Bersih: 40 gr', 'ABADO_50WP_40GRAM.jpg', 25000, 25500, 26250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(3, 9, 'QOLS00003', 'Abenz 22 EC 100 X 100 ml', 'ABENZ 22 EC merupakan insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna kuning kecoklatan, untuk mengendalikan hama ulat grayak pada tanaman jagung dan penggerek buah pada tanaman tomat.\nBahan Aktif : Emamektin benzoat 22 g/l\r\nIsi Bersih: 100 ml', 'ABENZ_22EC_100ML.jpg', 69000, 70380, 72450, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(4, 9, 'QOLS00004', 'Abenz 22 EC 40 X 250 ml', 'ABENZ 22 EC merupakan insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna kuning kecoklatan, untuk mengendalikan hama ulat grayak pada tanaman jagung dan penggerek buah pada tanaman tomat.\nBahan Aktif : Emamektin benzoat 22 g/l\r\nIsi Bersih: 250 ml\r\n', 'ABENZ_22EC_250ML.jpg', 144000, 146880, 151200, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(5, 15, 'QOLS00005', 'Abojo 60 WP 10 X 20 X 50 gr', 'ABOJO 60 WP merupakan moluskisida racun kontak berbentuk tepung yang dapat disuspensikan berwarna putih. ABOJO 60 WP dapat digunakan untuk mengendalikan hama siput murbei pada tanaman padi sawah.\nBahan Aktif: Fentin Asetat 60%\r\nIsi Bersih: 50 gr\r\n', 'ABOJO_60WP_50GRAM.jpg', 25000, 25500, 26250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(6, 15, 'QOLS00006', 'Abojo 60 WP 100 X 100 gr', 'ABOJO 60 WP merupakan moluskisida racun kontak berbentuk tepung yang dapat disuspensikan berwarna putih. ABOJO 60 WP dapat digunakan untuk mengendalikan hama siput murbei pada tanaman padi sawah.\nBahan Aktif: Fentin Asetat 60%\r\nIsi Bersih: 100 gr\r\n', 'ABOJO_60WP_100GRAM.jpg', 49500, 50490, 51975, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(7, 1, 'QOLS00007', 'Acrobat WP 16 X 20 X 10 gr', 'ACROBAT 50 WP merupakan fungisida sistemik berbentuk tepung yang dapat disuspensikan berwarna putih digunakan untuk mengendalikan penyakit pada tanaman cabai, jagung, kentang, semangka, tembakau, dan tomat\nBahan aktif: Dimetomorf 50%\r\nIsi Bersih: 10 Gram', 'ACROBAT_50WP_10GRAM.jpg', 11000, 11220, 11550, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(8, 1, 'QOLS00008', 'Acrobat WP 20 X 6 X 40 gr', 'ACROBAT 50 WP merupakan fungisida sistemik berbentuk tepung yang dapat disuspensikan berwarna putih digunakan untuk mengendalikan penyakit pada tanaman cabai, jagung, kentang, semangka, tembakau, dan tomat\nBahan aktif: Dimetomorf 50%\r\nIsi Bersih: 40 Gram\r\n', 'ACROBAT_50WP_40GRAM.jpg', 39000, 39780, 40950, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(9, 9, 'QOLS00009', 'Afonil 50 SC 100 X 100 ml', 'AFONIL 50 SC merupakan insektisida sistemik racun kontak dan lambung berbentuk pekatan suspensi berwarna putih, untuk mengendalikan hama pada tanaman padi sawah.\nBahan Aktif : Fipronil 50 g/l\r\nIsi Bersih: 100 ml\r\n', 'AFONIL_50SC_100ML.jpg', 31000, 31620, 32550, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(10, 9, 'QOLS00010', 'Afonil 50 SC 100 X 50 ml', 'AFONIL 50 SC merupakan insektisida sistemik racun kontak dan lambung berbentuk pekatan suspensi berwarna putih, untuk mengendalikan hama pada tanaman padi sawah.\nBahan Aktif : Fipronil 50 g/l\r\nIsi Bersih: 50 ml\r\n', 'AFONIL_50SC_50ML.jpg', 18500, 18870, 19425, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(11, 9, 'QOLS00011', 'Afonil 50 SC 20 X 500 ml', 'AFONIL 50 SC merupakan insektisida sistemik racun kontak dan lambung berbentuk pekatan suspensi berwarna putih, untuk mengendalikan hama pada tanaman padi sawah.\nBahan Aktif : Fipronil 50 g/l\r\nIsi Bersih: 500 ml\r\n', 'AFONIL_50SC_500ML.jpg', 127000, 129540, 133350, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(12, 9, 'QOLS00012', 'Afonil 50 SC 40 X 250 ml', 'AFONIL 50 SC merupakan insektisida sistemik racun kontak dan lambung berbentuk pekatan suspensi berwarna putih, untuk mengendalikan hama pada tanaman padi sawah.\nBahan Aktif : Fipronil 50 g/l\r\nIsi Bersih: 250 ml\r\n', 'AFONIL_50SC_250ML.jpg', 71000, 72420, 74550, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(13, 16, 'QOLS00013', 'Agridex 715 EC 12 X 1 ltr', 'AGRIDEX 715 EC adalah bahan perata dan penetran yang berbentuk larutan yang dapat diemulsikan, berwarna kuning jernih, untuk mengurangi tegangan permukaan larutan semprot Pestisida, meratakan dan meningkatkan daya penetrasi pestisida ke dalam jaringan tanaman\nBahan aktif : Minyak Parafinik 715 g/l\r\nIsi Bersih: 1 Liter\r\n', 'AGRIDEX_715EC_1LITER.jpg', 85500, 87210, 89775, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(14, 9, 'QOLS00014', 'Agrimec 18 EC 40 X 250 ml', 'Agrimec merupakan Insektisida racun kontak berbentuk pekatan yang dapat diemulsikan, berwarna kuning pucat, untuk mengendalikan hama pada tanaman apel, cabai, jeruk, kacang hijau, kacang panjang, kelapa sawit, kentang, krisan, kubis, dan tomat.\nBahan Aktif:  18 g/l Abamektin\r\nKemasan : 250 ml\r\n', 'AGRIMEC_18EC_250ML.jpg', 95000, 96900, 99750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(15, 17, 'QOLS00015', 'Agristick 400 L 12 X 1 ltr', 'Agristick 400 L adalah merupakan bahan perata dan perekat non ionic berbentuk larutan tidak berwarna dengan, bahan aktif : Alkilaril poliglikol eter 400 g/L. Penyerapan perekat pestisida oleh petani akan tinggi menjelang menjelang hingga akhir hujan berlangsung. perekat penembus pestisida terbaik bisa anda buktikan dengan cara menyemprot daun talas atau tanaman bawang merah.\nBahan aktif: Alkilaril poliglikol eter 400 g/L\r\nIsi Bersih: 1 Liter\r\n', 'AGRISTICK_400L_1LITER.jpg', 95500, 97410, 100275, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(16, 9, 'QOLS00016', 'Agus 500 SC 100 X 100 ml', 'AGUS 500 SC merupakan Insektisida yang juga bersifat akarisida bekerja sebagai racun kontak da perut, berbentuk pekatan suspensi berwarna putih keabu-abuan yang dapat larut dalam air untuk mengendalikan hama serangga pada tanaman cabai.\nBahan Aktif: Diafentiuron 500 g/l\r\nIsi Bersih: 100 ml\r\n', 'AGUS_500SC_100ML.jpg', 62500, 63750, 65625, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(17, 9, 'QOLS00017', 'Agus 500 SC 40 X 250 ml', 'AGUS 500 SC merupakan Insektisida yang juga bersifat akarisida bekerja sebagai racun kontak da perut, berbentuk pekatan suspensi berwarna putih keabu-abuan yang dapat larut dalam air untuk mengendalikan hama serangga pada tanaman cabai.\nBahan Aktif: Diafentiuron 500 g/l\r\nIsi Bersih: 250 ml\r\n', 'AGUS_500SC_250ML.jpg', 142000, 144840, 149100, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(18, 2, 'QOLS00018', 'Akalis 550 SC 10 X 1 ltr', 'AKALIS 550 SC merupakan Herbisida sistemik selektif pra tumbuh dan purna tumbuh berbentuk pekatan suspensi berwarna putih digunakan untuk mengendalikan dulma di tanaman jagung.\nBahan Aktif : Atrazin 500 g/l + Mesotrion 50 g/l\r\nIsi Bersih: 1 Liter\r\n', 'AKALIS_550SC_1.jpg', 249900, 249900, 257250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(19, 2, 'QOLS00019', 'Akalis 550 SC 20 X 500 ml', 'AKALIS 550 SC merupakan Herbisida sistemik selektif pra tumbuh dan purna tumbuh berbentuk pekatan suspensi berwarna putih digunakan untuk mengendalikan dulma di tanaman jagung.\nBahan Aktif : Atrazin 500 g/l + Mesotrion 50 g/l\r\nIsi Bersih: 500 ml\r\n', 'AKALIS_550SC_500ML.jpg', 130000, 132600, 136500, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(20, 2, 'QOLS00020', 'Akalis 550 SC 40 X 250 ml', 'AKALIS 550 SC merupakan Herbisida sistemik selektif pra tumbuh dan purna tumbuh berbentuk pekatan suspensi berwarna putih digunakan untuk mengendalikan dulma di tanaman jagung.\nBahan Aktif : Atrazin 500 g/l + Mesotrion 50 g/l\r\nIsi Bersih: 250 ml\r\n', 'AKALIS_550SC_250ML.jpg', 71500, 72930, 75075, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(21, 9, 'QOLS00021', 'Alika 247 ZC 100 X 50 ml', 'Alika 247ZC merupakan insektisida racun kontak berbentuk pekatan suspensi, berwarna putih kecoklat - coklatan, berdaya kerja luas, untuk mengendalikan hama kutu - kutuan dan ulat - ulatan pada tanaman bawang merah, cabai, jagung jarak pagar, jeruk, kacang hijau, kacang panjang, kakao, kedelai, kelapa sawit, kentang, kopi, kubis, mangga, semangka, tembakau, terung, dan tomat\n\r\nBahan aktif: Lamda sihalotrin 106 g/l, tiametoksam 141 g/l\r\nisi kemasan: 50 ml', 'ALIKA_247ZC_50ML.jpg', 34750, 35445, 36488, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(22, 9, 'QOLS00022', 'Alika 247 ZC 40 X 250 ml', 'Alika 247ZC merupakan insektisida racun kontak berbentuk pekatan suspensi, berwarna putih kecoklat - coklatan, berdaya kerja luas, untuk mengendalikan hama kutu - kutuan dan ulat - ulatan pada tanaman bawang merah, cabai, jagung jarak pagar, jeruk, kacang hijau, kacang panjang, kakao, kedelai, kelapa sawit, kentang, kopi, kubis, mangga, semangka, tembakau, terung, dan tomat\n\r\nBahan aktif: Lamda sihalotrin 106 g/l, tiametoksam 141 g/l\r\nisi kemasan: 250 ml', 'ALIKA_247ZC_250ML.jpg', 144000, 146880, 151200, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(23, 9, 'QOLS00023', 'Alika 247 ZC 50 X 100 ml', 'Alika 247ZC merupakan insektisida racun kontak berbentuk pekatan suspensi, berwarna putih kecoklat - coklatan, berdaya kerja luas, untuk mengendalikan hama kutu - kutuan dan ulat - ulatan pada tanaman bawang merah, cabai, jagung jarak pagar, jeruk, kacang hijau, kacang panjang, kakao, kedelai, kelapa sawit, kentang, kopi, kubis, mangga, semangka, tembakau, terung, dan tomat\n\r\nBahan aktif: Lamda sihalotrin 106 g/l, tiametoksam 141 g/l\r\nisi kemasan: 100 ml', 'ALIKA_247ZC_100ML.jpg', 61500, 62730, 64575, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(24, 18, 'QOLS00024', 'Amira 10 X 500 ml', 'Dalam dunia pertanian, stres tanaman adalah salah satu penyebab utama penurunan hasil panen. Stres bisa disebabkan oleh cuaca ekstrem, kekeringan, serangan hama, atau ketidakseimbangan nutrisi. Untuk mengatasi tantangan ini, hadir Amira Anti-Stres—formulasi nutrisi tanaman yang dirancang khusus untuk memperkuat daya tahan tanaman dan meningkatkan penyerapan unsur hara.\nKandungan : Magnesium (MgO) 3,62 % w/w, Boron (B) 0.63% w/w & Mengandung 13% ekstrak rumput laut (Ascophylium nodosum)\r\nManfaat Amira :\r\n- Perlindungan maksimal tanaman dari stress\r\n- Tanaman kuat, tumbuh maksimal\r\nIsi Bersih: 500 ml\r\n', 'AMIRA_500ML.jpg', 120000, 122400, 126000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(25, 1, 'QOLS00025', 'Amistartop 325 SC 100 X 50 ml', 'Amistar Top 325 SC merupakan Fungisida Sistemik yang bersifat protektif, kuratif, dan preventif berbentuk pekatan suspensi, berwarna kuning muda, untuk mengendalikan penyakit pada tanaman bawang merah, buah naga, cabai, jagung, jarak (pembibitan), jeruk, kacang tanah, kakao, karet, kedelai, kelapa sawit (pembibitan), kentang, kopi, krisan, kubis, mangga, melon, padang rumput golf, padi, tembakau, dan tomat.\n\r\nBahan aktif : Azoksistrobin 200 g/l & Difenokonazol 125 g/l\r\nIsi : 50ml', 'AMISTARTOP_325EC_50ML.jpg', 60500, 61710, 63525, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(26, 1, 'QOLS00026', 'Amistartop 325 SC 20 X 250 ml', 'Amistar Top 325 SC merupakan Fungisida Sistemik yang bersifat protektif, kuratif, dan preventif berbentuk pekatan suspensi, berwarna kuning muda, untuk mengendalikan penyakit pada tanaman bawang merah, buah naga, cabai, jagung, jarak (pembibitan), jeruk, kacang tanah, kakao, karet, kedelai, kelapa sawit (pembibitan), kentang, kopi, krisan, kubis, mangga, melon, padang rumput golf, padi, tembakau, dan tomat.\n\r\nBahan aktif : Azoksistrobin 200 g/l & Difenokonazol 125 g/l\r\nIsi : 250ml', 'AMISTARTOP_325EC_250ML.jpg', 265000, 270300, 278250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(27, 1, 'QOLS00027', 'Amistartop 325 SC 50 X 100 ml', 'Amistar Top 325 SC merupakan Fungisida Sistemik yang bersifat protektif, kuratif, dan preventif berbentuk pekatan suspensi, berwarna kuning muda, untuk mengendalikan penyakit pada tanaman bawang merah, buah naga, cabai, jagung, jarak (pembibitan), jeruk, kacang tanah, kakao, karet, kedelai, kelapa sawit (pembibitan), kentang, kopi, krisan, kubis, mangga, melon, padang rumput golf, padi, tembakau, dan tomat.\n\r\nBahan aktif : Azoksistrobin 200 g/l & Difenokonazol 125 g/l\r\nIsi : 100ml', 'AMISTARTOP_325EC_100ML.jpg', 111500, 113730, 117075, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(28, 1, 'QOLS00028', 'Amolin 300 EC 100 X 100 ml', 'Fungisida sistemik berbentuk pekatan yang dapat diemulsikan untuk mengendalikan penyakit busuk pelepah pada tanaman padi.\nBahan Aktif : Difekonazole 150 g/l + Propikonazole 150g/l\r\nIsi Bersih: 100 ml\r\n', 'AMOLIN_300EC_100ML.jpg', 52000, 53040, 54600, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(29, 1, 'QOLS00029', 'Amolin 300 EC 40 X 250 ml', 'Fungisida sistemik berbentuk pekatan yang dapat diemulsikan untuk mengendalikan penyakit busuk pelepah pada tanaman padi.\nBahan Aktif : Difekonazole 150 g/l + Propikonazole 150g/l\r\nIsi Bersih: 250 ml\r\n', 'AMOLIN_300EC_250ML.jpg', 122000, 124440, 128100, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(30, 9, 'QOLS00030', 'Amuron 70 EC 100 X 100 ml', 'AMURON 70 EC merupakan Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna kuning bening digunakan untuk mengendalikan hama ulat penggerek buah pada tanaman cabai.\nBahan Aktif : Heksaflumuron 50g/l + Emamektin benzoat 20g/l\r\nIsi Bersih: 100 ml', 'AMURON_70EC_100ML.jpg', 60000, 61200, 63000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(31, 9, 'QOLS00031', 'Amuron 70 EC 40 X 250 ml', 'AMURON 70 EC merupakan Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna kuning bening digunakan untuk mengendalikan hama ulat penggerek buah pada tanaman cabai.\nBahan Aktif : Heksaflumuron 50g/l + Emamektin benzoat 20g/l\r\nIsi Bersih: 250 ml', 'AMURON_70EC_250ML.jpg', 130000, 132600, 136500, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(32, 9, 'QOLS00032', 'Android 72 EC 20 X 200 ml', 'ANDROID 72 EC merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna coklat, untuk mengendalikan hama pada tanaman Kedelai.\nBahan Aktif : Abamektin 72 g/l\r\nIsi Bersih: 200 ml\r\n', 'ANDROID_72EC_200ML.jpg', 112500, 114750, 118125, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(33, 9, 'QOLS00033', 'Android 72 EC 20 X 400 ml', 'ANDROID 72 EC merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna coklat, untuk mengendalikan hama pada tanaman Kedelai.\nBahan Aktif : Abamektin 72 g/l\r\nIsi Bersih: 400 ml\r\n', 'ANDROID_72EC_400ML.jpg', 220000, 224400, 231000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(34, 9, 'QOLS00034', 'Android 72 EC 50 X 80 ml', 'ANDROID 72 EC merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna coklat, untuk mengendalikan hama pada tanaman Kedelai.\nBahan Aktif : Abamektin 72 g/l\r\nIsi Bersih: 80 ml\r\n', 'ANDROID_72EC_80ML.jpg', 51000, 52020, 53550, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(35, 1, 'QOLS00035', 'Anmi 100 SC 40 X 250 ml', 'ANMI 100 SC adalah fungisida sistemik berbahan aktif heksakonazol yang diformulasikan khusus untuk mengendalikan penyakit bercak ungu (Alternaria porri) pada tanaman bawang merah. Dengan formulasi SC (Suspension Concentrate), produk ini mudah diaplikasikan dan memberikan perlindungan menyeluruh terhadap tanaman.\nBahan Aktif : Heksakonazol 100 g/l\r\nIsi Bersih: 250 ml\r\n', 'ANMI_100SC_250ML.jpg', 57000, 58140, 59850, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(36, 1, 'QOLS00036', 'Antracol 70 WP 12 X 1 kg', 'ANTRACOL 70 WP merupakan Fungisida kontak yang bersifat protektif berbentuk tepung yang dapat disuspensikan berwarna krem untuk mengendalikan penyakit-penyakit pada tanaman apel, anggrek, anggur, bawang merah, bawang daun, bawang putih, cabai merah, cengkeh, jagung, jarak, jeruk, kacang tanah, kacang panjang, kentang, kedelai, ketimun, kina, kopi, kubis, krisan, lada, mangga, padi, pembibitan kelapa sawit, petsai, rosela, semangka, strawberi, teh, dan tomat\nBahan aktif : Propineb 70%\r\nIsi Bersih: 1 Kilogram\r\n', 'ANTRACOL_70WP_1KG.jpg', 151000, 154020, 158550, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(37, 1, 'QOLS00037', 'Antracol 70 WP 20 X 500 gr', 'ANTRACOL 70 WP merupakan Fungisida kontak yang bersifat protektif berbentuk tepung yang dapat disuspensikan berwarna krem untuk mengendalikan penyakit-penyakit pada tanaman apel, anggrek, anggur, bawang merah, bawang daun, bawang putih, cabai merah, cengkeh, jagung, jarak, jeruk, kacang tanah, kacang panjang, kentang, kedelai, ketimun, kina, kopi, kubis, krisan, lada, mangga, padi, pembibitan kelapa sawit, petsai, rosela, semangka, strawberi, teh, dan tomat\nBahan aktif : Propineb 70%\r\nIsi Bersih: 500 gr\r\n', 'ANTRACOL_70WP_500GRAM.jpg', 78500, 80070, 82425, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(38, 1, 'QOLS00038', 'Antracol 70 WP 40 X 250 gr', 'ANTRACOL 70 WP merupakan Fungisida kontak yang bersifat protektif berbentuk tepung yang dapat disuspensikan berwarna krem untuk mengendalikan penyakit-penyakit pada tanaman apel, anggrek, anggur, bawang merah, bawang daun, bawang putih, cabai merah, cengkeh, jagung, jarak, jeruk, kacang tanah, kacang panjang, kentang, kedelai, ketimun, kina, kopi, kubis, krisan, lada, mangga, padi, pembibitan kelapa sawit, petsai, rosela, semangka, strawberi, teh, dan tomat\nBahan aktif : Propineb 70%\r\nIsi Bersih: 250 gram\r\n', 'ANTRACOL_70WP_250GRAM.jpg', 40000, 40800, 42000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(39, 1, 'QOLS00039', 'Anvil 50 SC 10 X 1 ltr', 'ANVIL 50SC merupakan Fungisida sistemik berbentuk suspensi, berwarna putih kecoklat-coklatan untuk mengendalikan penyakit pada tanaman apel, bawang merah, bawang putih, cabai, jambu mete, kacang tanah, karet, kedelai, kelapa sawit, kopi, padi, pisang, semangka, dan tomat\n\r\nBahan aktif : Heksakonazol 50 g/l\r\nIsi kemasan : 1 Liter', 'ANVIL_50SC_1LITER.jpg', 220000, 224400, 231000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(40, 1, 'QOLS00040', 'Anvil 50 SC 40 X 250 ml', 'ANVIL 50SC merupakan Fungisida sistemik berbentuk suspensi, berwarna putih kecoklat-coklatan untuk mengendalikan penyakit pada tanaman apel, bawang merah, bawang putih, cabai, jambu mete, kacang tanah, karet, kedelai, kelapa sawit, kopi, padi, pisang, semangka, dan tomat\n\r\nBahan aktif : Heksakonazol 50 g/l\r\nIsi kemasan : 250ml', 'ANVIL_50SC_250ML.jpg', 61500, 62730, 64575, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(41, 18, 'QOLS00041', 'A-PlusCal 20 X 250 gr', 'A-PlusCal merupakan pupuk daun berkualitas tinggi yang diformulasikan khusus untuk memenuhi kebutuhan tanaman akan kalsium tanpa menambahkan nitrogen. Dengan kandungan CaO 34%, Zinc (Zn) 1,6%, dan Boron (B) 0,6%, A-PlusCal menjadi solusi ideal bagi petani dalam menjaga kesehatan tanaman, terutama di musim hujan.\nKandungan : CaO : 34%, Zn : 1,6%, B : 0,6%\r\nIsi Bersih: 250 ml', 'A-PLUSCAL_250ML.jpg', 75000, 76500, 78750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(42, 2, 'QOLS00042', 'Arendi 20/10 WP 20 X 20 X 25 gr', 'ARENDI 20/10 WP merupakan Herbisida sistemik pra dan purna tumbuh berbentuk tepung yang dapat disuspensikan berwarna putih untuk mengendalikan gulma pada pertanaman padi\nBahan Aktif : Natrium bispiribak 20% + Etil pirazosulfuron 10%\r\nIsi Bersih: 25 gr\r\n', 'ARENDI_25GRAM.jpg', 26000, 26520, 27300, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(43, 1, 'QOLS00043', 'Aslinya 75 WP 100 X 100 gr', 'ASLINYA 75 WP merupakan fungisida sistemik berbentuk tepung yang dapat di suspensikan berwarna jingga untuk mengendalikan penyakit blas, patah leher, blas daun, blas leher, kresek pada tanaman padi\nBahan Aktif : Trisiklazol 75%\r\nIsi Bersih: 100 gr\r\n', 'ASLINYA_75WP_100GRAM.jpg', 58000, 59160, 60900, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(44, 1, 'QOLS00044', 'Aslinya 75 WP 20 X 20 X 25 gr', 'ASLINYA 75 WP merupakan fungisida sistemik berbentuk tepung yang dapat di suspensikan berwarna jingga untuk mengendalikan penyakit blas, patah leher, blas daun, blas leher, kresek pada tanaman padi\nBahan Aktif : Trisiklazol 75%\r\nIsi Bersih: 25 gr\r\n', 'ASLINYA_75WP_25GRAM.jpg', 15000, 15300, 15750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(45, 9, 'QOLS00045', 'Ayuna 50 EC 40 X 250 ml', 'AYUNA 50 EC adalah insektisida yang mengandung bahan aktif Klorfluazuron 50 g/l. Produk ini dirancang untuk mengendalikan hama Ulat Grayak (Spodoptera litura) pada tanaman. Bahan aktif yang bekerja dengan cara menghambat proses pembentukan kutikula pada hama, sehingga hama tidak dapat berkembang normal dan akhirnya mati. AYUNA 50 EC efektif digunakan untuk mengendalikan hama Ulat Grayak (Spodoptera litura), yang dapat menyebabkan kerusakan pada tanaman dengan memakan daun dan bagian tanaman lainnya.\nBahan Aktif : Klorfluazuron  50 g/I\r\nIsi Bersih: 250 ml\r\n', 'AYUNA_50EC_250ML.jpg', 88000, 89760, 92400, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(46, 2, 'QOLS00046', 'Aziziah 300/10 SC 20 X 500 ml', 'Aziziah 300/10 SC adalah herbisida sistemik selektif yang digunakan untuk mengendalikan gulma pada tanaman jagung. Herbisida ini berbentuk pekatan suspensi (SC) berwarna putih dan bekerja secara efektif baik sebelum (pra tumbuh) maupun sesudah (purna tumbuh) gulma muncul.\nBahan Aktif : Atrazin 300 g/l dan Mesotrion 10 g/l.\r\nIsi Bersih: 500 ml\r\n', 'AZIZIAH_500ML.jpg', 152000, 155040, 159600, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(47, 9, 'QOLS00047', 'Baycarb 500 EC 20 X 500 ml', 'Baycarb® 500 EC Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna coklat jernih untuk mengendalikan hama pada tanaman jagung, kakao, padi dan teh.\nBahan aktif : Fenobucarb (BPMC) 500 g/l\r\nIsi Bersih: 500 ml\r\n', 'BAYCARB_500EC_500ML.jpg', 71250, 72675, 74813, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(48, 9, 'QOLS00048', 'Belt Expert 480 SC 20 X 250 ml', 'Belt Expert 480 SC Insektisida sistemik racun kontak dan lambung berbentuk pekatan suspensi, berwarna putih kekuningan, mengendalikan hama pada tanaman padi sawah dan jagung\nBahan Aktif : Flubendiamida 240 g/l + Fiakloprid 240 g/l\r\nIsi Bersih: 250 ml\r\n', 'BELT_EXPERT_480SC_250ML.jpg', 386500, 394230, 405825, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(49, 1, 'QOLS00049', 'Bion M 1/48 WP 20 X 500 gr', 'Bion M merupakan Fungisida protektif yang berbentuk tepung yang dapat disuspensikan berwarna coklat kekuning-kuningan, untuk mengendalikan penyakit yang disebabkan oleh cendawan dan bakteri pada tanaman cabai, kentang, kubis dan tomat.\n\r\nBahan aktif : Asibenzolar-s-metil 1% + mankozeb 48%\r\nIsi kemasan : 500gr', 'BION_M_500GR.jpg', 179500, 183090, 188475, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(50, 9, 'QOLS00050', 'Brasso 250 EC 20 X 250 ml', 'BRASSO 250 EC merupakan Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan, berwarna merah kecoklatan, untuk mengendalikan hama pada tanaman Kakao, Cabai, dan Kedelai.\nBahan Aktif : Sipermetrin 250 g/l\r\nIsi Bersih: 250 ml\r\n', 'BRASSO_250EC_250ML.jpg', 48000, 48960, 50400, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(51, 9, 'QOLS00051', 'Brasso 250 EC 20 X 400 ml', 'BRASSO 250 EC merupakan Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan, berwarna merah kecoklatan, untuk mengendalikan hama pada tanaman Kakao, Cabai, dan Kedelai.\nBahan Aktif : Sipermetrin 250 g/l\r\nIsi Bersih: 400 ml\r\n', 'BRASSO_250EC_400ML.jpg', 71000, 72420, 74550, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(52, 1, 'QOLS00052', 'Cabrio 250 EC 24 X 250 ml', 'CABRIO 250 EC merupakan Fungisida yang bersifat protektif dan kuratif berbentuk pekatan yang dapat diemulsikan untuk mengendalikan penyakit pada tanaman tomat, kentang dan bawang merah.\nBahan Aktif: Piraklostrobin 250 g/l\r\nIsi Bersih: 250 ml\r\n', 'CABRIO_250EC_250ML.jpg', 193000, 196860, 202650, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(53, 1, 'QOLS00053', 'Cabrio 250 EC 48 X 100 ml', 'CABRIO 250 EC merupakan Fungisida yang bersifat protektif dan kuratif berbentuk pekatan yang dapat diemulsikan untuk mengendalikan penyakit pada tanaman tomat, kentang dan bawang merah.\nBahan Aktif: Piraklostrobin 250 g/l\r\nIsi Bersih: 100 ml\r\n', 'CABRIO_250EC_100ML.jpg', 83500, 85170, 87675, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(54, 2, 'QOLS00054', 'Calaris 550 SC 10 X 1 ltr', 'Calaris merupakan Herbisida sistemik dan selektif awal purna tumbuh berbentuk pekatan suspensi berwarna coklat muda untuk mengendalikan gulma berdaun lebar, gulma berdaun sempit dan teki-tekian pada pertanaman jagung.\n\r\nBahan aktif : Mesotrion 50 g/l + Atrazin 500 g/l\r\nIsi kemasan : 1 Liter', 'CALARIS_550SC_1LITER.jpg', 265000, 270300, 278250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(55, 2, 'QOLS00055', 'Calaris 550 SC 20 X 250 ml', 'Calaris merupakan Herbisida sistemik dan selektif awal purna tumbuh berbentuk pekatan suspensi berwarna coklat muda untuk mengendalikan gulma berdaun lebar, gulma berdaun sempit dan teki-tekian pada pertanaman jagung.\n\r\nBahan aktif : Mesotrion 50 g/l + Atrazin 500 g/l\r\nIsi kemasan : 250ml', 'CALARIS_550SC_250ML.jpg', 73000, 74460, 76650, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(56, 2, 'QOLS00056', 'Calaris 550 SC 20 X 500 ml', 'Calaris merupakan Herbisida sistemik dan selektif awal purna tumbuh berbentuk pekatan suspensi berwarna coklat muda untuk mengendalikan gulma berdaun lebar, gulma berdaun sempit dan teki-tekian pada pertanaman jagung.\n\r\nBahan aktif : Mesotrion 50 g/l + Atrazin 500 g/l\r\nIsi kemasan : 500ml', 'CALARIS_550SC_500ML.jpg', 138000, 140760, 144900, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(57, 9, 'QOLS00057', 'Confidor 200 SL 100 X 60 ml', 'Confidor 200 SL Insektisida sistemik racun kontak dan lambung berbentuk larutan dalam air, berwarna coklat jernih, untuk mengendalikan hama pada tanaman apel, cabai, jagung, jeruk, kacang panjang, kapas, kelapa sawit, kentang, ketimun, mangga, padi, semangka, teh, tembakau dan tomat\nBahan aktif : Imidakloprid 200 g/l\r\nIsi Bersih: 60 ml\r\n', 'CONFIDOR_200SL_60ML.jpg', 59750, 60945, 62738, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(58, 9, 'QOLS00058', 'Confidor Extra 350 SC 100 X 100 ml', 'CONFIDOR EXTRA® 350 SC Insektisida sistemik yang bekerja secara racun kontak dan lambung berbentuk pekatan suspensi yang larut dalam air berwarna putih untuk mengendalikan hama pada tanaman apel, cabai, jagung, jeruk, kacang panjang, kentang, mangga, mentimun, padi sawah, semangka, teh, tembakau dan tomat.\nBahan aktif : imidakloprid 350 g/l\r\nIsi Bersih: 100 ml\r\n', 'CONFIDOR_EXTRA_100ML.jpg', 93500, 95370, 98175, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(59, 2, 'QOLS00059', 'Council Complete SC 300 50 X 100 ml', 'Council Complete® 300 SC Herbisida selektif, berbentuk pekatan suspensi berwarna putih kekuningan, bekerja secara sistemik dan digunakan untuk mengendalikan gulma pada pertanaman padi tanam pindah (TAPIN) dan tanam benih langsung (TABELA).\nBahan aktif : Triafamone 100 g/l + Tefuryltrione 200 g/l\r\nIsi Bersih: 100 ml\r\n', 'COUNCIL_COMPLETE_100ML.jpg', 172500, 175950, 181125, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(60, 2, 'QOLS00060', 'Council Complete SC 300 50 X 50 ml', 'Council Complete® 300 SC Herbisida selektif, berbentuk pekatan suspensi berwarna putih kekuningan, bekerja secara sistemik dan digunakan untuk mengendalikan gulma pada pertanaman padi tanam pindah (TAPIN) dan tanam benih langsung (TABELA).\nBahan aktif : Triafamone 100 g/l + Tefuryltrione 200 g/l\r\nIsi Bersih: 50 ml\r\n', 'COUNCIL_COMPLETE_50ML.jpg', 86000, 87720, 90300, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(61, 9, 'QOLS00061', 'Curacron 500 EC 20 X 500 ml', 'Curacron merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna kuning kecoklat-coklatan, untuk mengendalikan hama pada tanaman bawang merah, cabai, jeruk, kacang hijau, kapas, kentang, kubis, semangka, tebu, tembakau dan tomat.\n\r\nBahan aktif : Profenofos 500 g/l\r\nIsi kemasan : 500ml', 'CURACRON_500EC_500ML.jpg', 136750, 139485, 143588, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(62, 9, 'QOLS00062', 'Curacron 500 EC 40 X 250 ml', 'Curacron merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna kuning kecoklat-coklatan, untuk mengendalikan hama pada tanaman bawang merah, cabai, jeruk, kacang hijau, kapas, kentang, kubis, semangka, tebu, tembakau dan tomat.\n\r\nBahan aktif : Profenofos 500 g/l\r\nIsi kemasan : 250ml', 'CURACRON_500EC_250ML.jpg', 70000, 71400, 73500, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(63, 9, 'QOLS00063', 'Curacron 500 EC 50 X 100 ml', 'Curacron merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna kuning kecoklat-coklatan, untuk mengendalikan hama pada tanaman bawang merah, cabai, jeruk, kacang hijau, kapas, kentang, kubis, semangka, tebu, tembakau dan tomat.\n\r\nBahan aktif : Profenofos 500 g/l\r\nIsi kemasan : 100ml', 'CURACRON_500EC_100ML.jpg', 30000, 30600, 31500, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(64, 9, 'QOLS00064', 'Cymbush 50 EC 100 X 100 ml', 'Cymbush merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna coklat muda jernih, untuk mengendalikan hama-hama pada tanaman kedelai, kubis dan tembakau.\n\r\nBahan aktif : Sipermetrin 50 g/l\r\nIsi kemasan : 100ml', 'CYMBUSH_50EC_100ML.jpg', 20000, 20400, 21000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(65, 9, 'QOLS00065', 'Cymbush 50 EC 40 X 250 ml', 'Cymbush merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna coklat muda jernih, untuk mengendalikan hama-hama pada tanaman kedelai, kubis dan tembakau.\nBahan aktif : Sipermetrin 50 g/l\r\nIsi kemasan : 250ml', 'CYMBUSH_50EC_250ML.jpg', 49000, 49980, 51450, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(66, 9, 'QOLS00066', 'Decis 25 EC 100 X 100 ml', 'Decis® 25 EC Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna kuning jernih untuk mengendalikan hama pada tanaman anggrek, apel, bawang merah, belimbing, benih jagung, cabai, jagung, jarak pagar, jeruk, kacang hijau, kacang panjang, kakao, kedelai, kelapa sawit, kentang, ketimun, kopi, kubis, lada, mangga, melon, semangka, teh, tembakau dan tomat.\nBahan aktif : Deltametrin 25 g/ℓ\r\nIsi Bersih: 100 ml\r\n', 'DECIS_25EC_100ML.jpg', 27500, 28050, 28875, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(67, 9, 'QOLS00067', 'Decis 25 EC 100 X 50 ml', 'Decis® 25 EC Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna kuning jernih untuk mengendalikan hama pada tanaman anggrek, apel, bawang merah, belimbing, benih jagung, cabai, jagung, jarak pagar, jeruk, kacang hijau, kacang panjang, kakao, kedelai, kelapa sawit, kentang, ketimun, kopi, kubis, lada, mangga, melon, semangka, teh, tembakau dan tomat.\nBahan aktif : Deltametrin 25 g/ℓ\r\nIsi Bersih: 50 ml\r\n', 'DECIS_25EC_50ML.jpg', 15150, 15453, 15908, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(68, 9, 'QOLS00068', 'Decis 25 EC 20 X 500 ml', 'Decis® 25 EC Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna kuning jernih untuk mengendalikan hama pada tanaman anggrek, apel, bawang merah, belimbing, benih jagung, cabai, jagung, jarak pagar, jeruk, kacang hijau, kacang panjang, kakao, kedelai, kelapa sawit, kentang, ketimun, kopi, kubis, lada, mangga, melon, semangka, teh, tembakau dan tomat.\nBahan aktif : Deltametrin 25 g/ℓ\r\nIsi Bersih: 500 ml\r\n', 'DECIS_25EC_500ML.jpg', 124750, 127245, 130988, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(69, 9, 'QOLS00069', 'Decis 25 EC 40 X 250 ml', 'Decis® 25 EC Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna kuning jernih untuk mengendalikan hama pada tanaman anggrek, apel, bawang merah, belimbing, benih jagung, cabai, jagung, jarak pagar, jeruk, kacang hijau, kacang panjang, kakao, kedelai, kelapa sawit, kentang, ketimun, kopi, kubis, lada, mangga, melon, semangka, teh, tembakau dan tomat.\nBahan aktif : Deltametrin 25 g/ℓ\r\nIsi Bersih: 250 ml\r\n', 'DECIS_25EC_250ML.jpg', 67250, 68595, 70613, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(70, 9, 'QOLS00070', 'Elestal Neo 54 WG 100 X 30 gr', 'Elestal Neo merupakan Insektisida racun kontak dan lambung berbentuk butiran yang dapat didispersikan dalam air yang bekerja dengan menghambat biosintesa lemak dan menyebabkan serangga menjadi lumpuh, berwarna coklat muda untuk mengendalikan hama di tanaman cabai dan tomat.  \n\r\nBahan aktif : Spiropidion 30% + acetamiprid 24%\r\nIsi kemasan : 30 Gram', 'ESTAL_NEO_30GRAM.jpg', 95000, 96900, 99750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(71, 9, 'QOLS00071', 'Elestal Neo 54 WG 20 X 150 gr', 'Elestal Neo merupakan Insektisida racun kontak dan lambung berbentuk butiran yang dapat didispersikan dalam air yang bekerja dengan menghambat biosintesa lemak dan menyebabkan serangga menjadi lumpuh, berwarna coklat muda untuk mengendalikan hama di tanaman cabai dan tomat.  \n\r\nBahan aktif : Spiropidion 30% + acetamiprid 24%\r\nIsi kemasan : 150 Gram', 'ESTAL_NEO_150GRAM.jpg', 405000, 413100, 425250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(72, 20, 'QOLS00072', 'Ethrel 480 SL 100 X 100 ml', 'ETHREL 480 SL merupakan zat pengatur tumbuh tanaman berbentuk larutan dalam air yang berwarna coklat jernih digunakan sebagai zat pengatur tumbuh tanaman pada tanaman Apel, Kedelai, Kopi, Nenas, Pisang, dan Tembakau\nBahan aktif : Etefon 480 g/l\r\nIsi Bersih: 100 ml\r\n', 'ETHREL_480SL_100ML.jpg', 42500, 43350, 44625, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(73, 9, 'QOLS00073', 'Fenval 200 EC 15 X 1 ltr', 'FENVAL 200 g/l merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna coklat kemerahan, untuk mengendalikan hama pada tanaman Bawang Merah, Cabai, Jeruk, Kakao, Kapas, Kedelai, Kelapa Sawit, Kubis, Teh, Tembakau dan Tomat.\nBahan Aktif: Fenvalerat 200 g/l\r\nIsi Bersih: 1 Liter\r\n', 'FENVAL_200EC_1LITER.jpg', 136000, 138720, 142800, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(74, 9, 'QOLS00074', 'Fenval 200 EC 20 X 250 ml', 'FENVAL 200 g/l merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna coklat kemerahan, untuk mengendalikan hama pada tanaman Bawang Merah, Cabai, Jeruk, Kakao, Kapas, Kedelai, Kelapa Sawit, Kubis, Teh, Tembakau dan Tomat.\nBahan Aktif: Fenvalerat 200 g/l\r\nIsi Bersih: 250 ml\r\n', 'FENVAL_200EC_250ML.jpg', 34000, 34680, 35700, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(75, 9, 'QOLS00075', 'Fenval 200 EC 20 X 500 ml', 'FENVAL 200 g/l merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna coklat kemerahan, untuk mengendalikan hama pada tanaman Bawang Merah, Cabai, Jeruk, Kakao, Kapas, Kedelai, Kelapa Sawit, Kubis, Teh, Tembakau dan Tomat.\nBahan Aktif: Fenvalerat 200 g/l\r\nIsi Bersih: 500 ml\r\n', 'FENVAL_200EC_500ML.jpg', 70000, 71400, 73500, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(76, 9, 'QOLS00076', 'Fenval 200 EC 50 X 100 ml', 'FENVAL 200 g/l merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna coklat kemerahan, untuk mengendalikan hama pada tanaman Bawang Merah, Cabai, Jeruk, Kakao, Kapas, Kedelai, Kelapa Sawit, Kubis, Teh, Tembakau dan Tomat.\nBahan Aktif: Fenvalerat 200 g/l\r\nIsi Bersih: 100 ml\r\n', 'FENVAL_200EC_100ML.jpg', 15000, 15300, 15750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(77, 1, 'QOLS00077', 'Filia 525 SE 100 X 50 ml', 'Filia merupakan fungisida protektif untuk mengendalikan penyakit blas Pyricularia oryzae pada tanaman padi. Dengan kombinasi dua bahan aktif pilihan yang do formulasi secara tepat, maka Filia 525 SE tidak saja mengendalikan penyakit blas yang sering terjadi pada tanaman padi seperti Ustilago, hawar pelepah, \"dirty panicle\" pada bulir tetapi juga membuat batang lebih kuat, enampilan daun dan tamana padi lebih hijau dan memberikan peningkatan hasil yang signfikan. Filia 525 SE juga dapat digunakan pada tanaman bawang merah untuk mengendalikan penyakit dan meningkatkan kualitas umbi serta hasil panen.\n\r\nBahan aktif : Trisiklazol 400 g/l + Propikonazol 125 g/l\r\nIsi kemasan : 50ml', 'FILIA_525SE_50ML.jpg', 29250, 29835, 30713, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(78, 1, 'QOLS00078', 'Filia 525 SE 20 X 250 ml', 'Filia merupakan fungisida protektif untuk mengendalikan penyakit blas Pyricularia oryzae pada tanaman padi. Dengan kombinasi dua bahan aktif pilihan yang do formulasi secara tepat, maka Filia 525 SE tidak saja mengendalikan penyakit blas yang sering terjadi pada tanaman padi seperti Ustilago, hawar pelepah, \"dirty panicle\" pada bulir tetapi juga membuat batang lebih kuat, enampilan daun dan tamana padi lebih hijau dan memberikan peningkatan hasil yang signfikan. Filia 525 SE juga dapat digunakan pada tanaman bawang merah untuk mengendalikan penyakit dan meningkatkan kualitas umbi serta hasil panen.\n\r\nBahan aktif : Trisiklazol 400 g/l + Propikonazol 125 g/l\r\nIsi kemasan : 250ml', 'FILIA_525SE_250ML.jpg', 127000, 129540, 133350, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(79, 1, 'QOLS00079', 'Folirfos 400 SL 15 X 1 ltr', 'Folirfos 400 SL merupakan Fungisida sistemik untuk mengendalikan penyakit yang disebabkan oleh jamur pada tanaman anggur, bawang merah, jeruk, kakao, kelapa, kentang, lada, nanas, padi, dan tomat\nBahan Aktif: Asam fosfit 400 g/l\r\nIsi Bersih: 1 Liter\r\n', 'FOLIRFOS_400SL_1LITER.jpg', 95000, 96900, 99750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(80, 1, 'QOLS00080', 'Folirfos 400 SL 20 X 500 ml', 'Folirfos 400 SL merupakan Fungisida sistemik untuk mengendalikan penyakit yang disebabkan oleh jamur pada tanaman anggur, bawang merah, jeruk, kakao, kelapa, kentang, lada, nanas, padi, dan tomat\nBahan Aktif: Asam fosfit 400 g/l\r\nIsi Bersih: 500 ml\r\n', 'FOLIRFOS_400SL_500ML.jpg', 52500, 53550, 55125, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(81, 1, 'QOLS00081', 'Folirfos 400 SL 4 X 5 ltr', 'Folirfos 400 SL merupakan Fungisida sistemik untuk mengendalikan penyakit yang disebabkan oleh jamur pada tanaman anggur, bawang merah, jeruk, kakao, kelapa, kentang, lada, nanas, padi, dan tomat\nBahan Aktif: Asam fosfit 400 g/l\r\nIsi Bersih: 5 Liter\r\n', 'FOLIRFOS_400SL_5LITER.jpg', 450000, 459000, 472500, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(82, 19, 'QOLS00082', 'Forsil 20 X 250 ml', 'FORSIL merupakan pupuk mikro majemuk an-organik yang dapat terlarut sempurnadalam air. Forsil yang diperkaya silika yang dapat meningkatkan kualitas dan hasilpanen, selain itu juga berfungsi sebagai Biostimulant yang dapat melindungi tanaman dari serangan hama dan penyakit, kekeringan, serta perubahan cuaca\nKandungan Hara: Silika (SiO2) 20%, Seng (Zn) 0,25%, Molibdenum (Mo) 0,001%\r\nIsi Bersih: 250 ml\r\n', 'FORSIL_250ML.jpg', 56000, 57120, 58800, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(83, 19, 'QOLS00083', 'Forsil 20 X 500 ml', 'FORSIL merupakan pupuk mikro majemuk an-organik yang dapat terlarut sempurnadalam air. Forsil yang diperkaya silika yang dapat meningkatkan kualitas dan hasilpanen, selain itu juga berfungsi sebagai Biostimulant yang dapat melindungi tanaman dari serangan hama dan penyakit, kekeringan, serta perubahan cuaca\nKandungan Hara: Silika (SiO2) 20%, Seng (Zn) 0,25%, Molibdenum (Mo) 0,001%\r\nIsi Bersih: 500 ml\r\n', 'FORSIL_500ML.jpg', 103500, 105570, 108675, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(84, 1, 'QOLS00084', 'Insure Max 510 FS 100 X 25 ml', 'INSURE MAX 510 FS merupakan Fungisida sistemik yang bersifat protektif dan kuratif berbentuk pekatan suspensi berwarna merah muda digunakan untuk mengendalikan penyakit Bulai pada tanaman Jagung.\nBahan Aktif: Dimetomorf 500 g/l & Piraklostrobin 10 g/l\r\nIsi Bersih: 25 ml\r\n', 'INSURE_MAX_25ML.jpg', 34000, 34680, 35700, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(85, 14, 'QOLS00085', 'Jagung ADV 132 20 X 1 kg', 'Jagung ADV 132 adalah benih jagung hibrida yang diproduksi oleh perusahaan Advanta Seeds Indonesia dengan nama produk ADV Montok. Benih ini dikenal karena toleransinya terhadap penyakit bulai dan rendemennya yang sangat tinggi. Tipe persilangan: Hibrida silang tunggal.Batang: Kokoh dan tahan dari kerobohan.Pengakaran: Dalam, memberikan stabilitas pada tanaman.Toleransi penyakit: Sangat toleran terhadap penyakit bulai dan busuk batang.Tongkol: Montok, terisi penuh hingga ujung, dan kelobot menutup dengan sempurna, sehingga tidak mudah busuk.Hasil panen: Rendemennya sangat tinggi, mencapai kurang lebih 85%.\nIsi Bersih: 1 Kilogram\r\n', 'JAGUNG_ADV_132_1KG.jpg', 89000, 90780, 93450, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(86, 14, 'QOLS00086', 'Jagung DK 771 20 X 1 kg (Bayer)', 'Benih Jagung Hibrida Dekalb 771 Produksinya tinggi, juga tahan terhadap serangan penyakit bulai dan mampu menghasilkan jagung dengan tongkol lebih panjang dan besar. Benih jagung ini juga mudah ditanam di lahan datar, lereng gunung, ataupun di lahan-lahan marginal lainnya.\nIsi Bersih: 1 Kilogram\r\n', 'JAGUNG_DK771_1KG.jpg', 81000, 82620, 85050, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(87, 14, 'QOLS00087', 'Jagung DK 9209C 20 X 1 kg (Bayer)', 'Jagung DK 9209C adalah benih jagung hibrida dari Bayer dengan keunggulan ketahanan terhadap penyakit bulai dan busuk batang, adaptasi luas di berbagai kondisi tanah, serta potensi hasil yang tinggi hingga 15,2 ton per hektare pipil kering, sehingga cocok untuk meningkatkan produktivitas petani. Benih ini menghasilkan tongkol yang besar dengan biji yang rapat dan dapat dipanen pada berbagai usia.\nIsi Bersih: 1 Kilogram\r\n', '-', 102000, 104040, 107100, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(88, 14, 'QOLS00088', 'Jagung DK 95R 20 X 1 kg', 'DEKALB DK95R adalah Jagung Bioteknologi Tahan Roundup (glifosat), memungkinkan penggunaan herbisida berbasis glifosat tanpa merusak tanaman jagung.\nKeunggulan:\r\n- Tahan Herbisida Glyphosate: Mengurangi gulma secara efisien tanpa merusak tanaman.\r\n- Hasil Panen Tinggi: Teruji di lahan petani dengan produktivitas tinggi.\r\n- Kualitas Benih Terjamin: Diproduksi oleh Bayer, perusahaan global terpercaya di bidang pertanian.\r\n- Efisiensi Biaya: Mengurangi kebutuhan tenaga kerja dan biaya penyiangan manual.\r\n- Cocok untuk Wilayah Tropis: Telah diuji dan terbukti adaptif untuk kondisi Indonesia.\r\nIsi Bersih: 1 Kilogram\r\n', 'JAGUNG_DK95R_1KG.jpg', 110000, 115000, 120000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(89, 14, 'QOLS00089', 'Jagung NK 212 20 X 1 kg', 'NK212 memiliki kemampuan beradaptasi di lahan tadah hujan (tegalan) dan lahan sawah dengan kondisi tanah yang marjinal serta kekurangan air. NK 212 adalah jagung yang mempunyai pertumbuhan awal sangat bagus, warna oranye, dan pengisian penuh. Serta benih jagung ini juga mudah beradptasi di berbagai kondisi lahan.\nPotensi Hasil: 10,8 Ton/Ha pipilan kering.\r\nRata-Rata Hasil: 9,5 Ton/Ha pipilan kering.\r\nUmur Panen: ± 101 HST\r\nKemasan: 1 kilogram', 'JAGUNG_NK212_1KG.jpg', 82500, 84150, 86625, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(90, 14, 'QOLS00090', 'Jagung NK 212 BTGT 20 X 1 kg', 'NK212s | NK Pendekar Sakti adalah benih Jagung Hibrida Bioteknologi NK212s-Bt11GA21 yang sudah ditambahkan dengan beberapa kesaktian. NK Pendekar Sakti memiliki kesaktian (toleran) terhadap penyemprotan herbisida glyphosate murni dan juga sakti (tahan) terhadap serangan hama penggerek batang-Asian Corn Borer. Kesaktian yang dimiliki NK Pendekar Sakti menawarkan budidaya tanaman jagung yang lebih mudah dan murah, serta pastinya meningkatkan hasil bagi petani.\nPotensi Hasil: 11,8 Ton/Ha (KA 15%).\r\nRata-Rata Hasil: 9,0 Ton/Ha (KA 15%).\r\n Umur Panen: ± 103 HST\r\nKemasan: 1 Kilogram\r\n', 'JAGUNG_NK212_SAKTI_1KG.jpg', 106000, 108120, 111300, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(91, 14, 'QOLS00091', 'Jagung NK 7202 20 X 1 kg', 'NK7202 | NK JUARA memiliki kemampuan beradaptasi di lahan sawah. Benih NK Juara dapat ditanam oleh petani yang mempunyai masalah endemik penyakit bulai dan busuk batang, karena benih ini memiliki ketahanan terhadap penyakit tersebut. Selain itu benih ini juga memiliki potensi hasil yang tinggi, sehingga membuat petani aman dan menguntungkan dengan hasil tanam jagungnya.\nPotensi Hasil: 12,9 Ton/Ha.\r\nRata-Rata Hasil: 10,7 Ton/Ha.\r\nUmur Panen: ± 107 HST \r\nKemasan: 1 Kilogram\r\n', 'JAGUNG_NK7202_JUARA_1KG.jpg', 96500, 98430, 101325, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(92, 14, 'QOLS00092', 'Jagung NK 7328 20 X 1 kg', 'NK7328 | NK SUMO memiliki kemampuan beradaptasi di lahan tadah hujan (tegalan) dan untuk petani progressive. Benih ini memiliki tanaman yang Sumo dengan hasil yang Sumo. Petani tidak perlu khawatir tanaman rebah karena batang yang kokoh, serta pertumbuhan tanaman yang menarik yang disukai oleh petani jagung Indonesia.\nPotensi Hasil: 12,4 Ton/Ha (KA 15%). \r\nRata-Rata Hasil: 9,9 Ton/Ha (KA 15%). \r\nUmur Panen: ± 115 HST\r\nKemasan: 1 Kilogram\r\n', 'JAGUNG_NK7328_1KG.jpg', 113500, 115770, 119175, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(93, 14, 'QOLS00093', 'Jagung NK 7328 BTGT 20 X 1 kg', 'NK7328s | NK Sumo Sakti adalah benih Jagung Hibrida Bioteknologi NK7328s-Bt11GA21 dengan performa tanaman yang disukai petani, berbatang Kokoh, dan juga memiliki kesaktian tambahan dari varietas NK Sumo sebelumnya. Kesaktian yang dimiliki oleh NK Sumo Sakti adalah ketahanan terhadap penyemprotan herbisida jenis Glyphosate murni dan juga ketahanan terhadap serangan hama penggerek batang (Asian Corn Borer). Petani akan lebih mudah dalam membudidayakan jagung hibrida, lebih sedikit menggunakan pestisida untuk perawatan tanaman sehingga lebih menguntungkan, dan tentunya meningkatkan hasil bagi petani.\nPotensi Hasil: 11.5 Ton/Ha (KA 15%). \r\nRata-Rata Hasil: 9.0 Ton/Ha (KA 15%) \r\nUmur Panen: ± 105 HST\r\nKemasan: 1 Kilogram\r\n', 'NK_SUMO_7328_SAKTI_1KG.jpg', 133500, 136170, 140175, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(94, 14, 'QOLS00094', 'Kubis Hibrida F1 Kaelo 30 X 15 gr', 'Kubis Hibrida F1 Kaelo adalah varietas kubis produk Seminis yang cocok untuk dataran rendah-menengah, memiliki umur panen genjah (sekitar 50-65 HST), menghasilkan krop bulat pipih, keras, dan padat dengan berat 1,5-2,5 kg, serta tahan penyakit akar gada, bercak daun, dan jamur. Kubis ini memiliki kemampuan adaptasi luas, tahan cuaca panas, dan cocok untuk pengangkutan jarak jauh karena tidak mudah pecah.\nIsi Bersih: 15 Gram\r\n', 'KUBIS_KAELO_15GRAM.jpg', 86000, 87720, 90300, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(95, 1, 'QOLS00095', 'Luna Smart 250/250 SC 100 X 100 ml', 'Fungisida yang bersifat preventif, kuratif dan sistemik berbentuk pekatan suspensi yang larut dalam air berwarna putih keabu-abuan untuk mengendalikan penyakit-penyakit pada tanaman apel, bawang merah, cabai, jeruk, kakao, kentang, mangga, semangka dan tomat\nBahan aktif : Fluopiram 250 g/l + Trifloksistrobin 250 g/l\r\nIsi Bersih: 100 ml\r\n', 'LUNA_SMART_100ML.jpg', 135500, 138210, 142275, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(96, 1, 'QOLS00096', 'Luna Smart 250/250 SC 50 X 100 ml', 'Fungisida yang bersifat preventif, kuratif dan sistemik berbentuk pekatan suspensi yang larut dalam air berwarna putih keabu-abuan untuk mengendalikan penyakit-penyakit pada tanaman apel, bawang merah, cabai, jeruk, kakao, kentang, mangga, semangka dan tomat\nBahan aktif : Fluopiram 250 g/l + Trifloksistrobin 250 g/l\r\nIsi Bersih: 100 ml\r\n', 'LUNA_SMART_100ML.jpg', 135500, 138210, 142275, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0);
INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `description`, `picture_name`, `price`, `price_2`, `price_3`, `stock`, `current_discount`, `product_unit`, `product_unit_1`, `product_unit_2`, `product_unit_value`, `product_type`, `product_unit_weight`, `is_available`, `add_date`, `user_level`) VALUES
(97, 9, 'QOLS00097', 'Matador 25 EC 100 X 50 ml', 'Matador merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna kuning jerami jernih untuk mengendalikan hama-hama pada tanaman bawang merah, bawang putih, cabai, jagung, jeruk, kacang panjang, kakao, kapas, kedelai, kelapa sawit, kubis, lada, lamtoro, mangga, teh, tembakau dan tomat. \nBahan Aktif:\r\n25 g/l lamda sihalotrin\r\nIsi Bersih: 50 ml\r\n', 'MATADOR_25EC_50ML.jpg', 15700, 16014, 16485, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(98, 9, 'QOLS00098', 'Matador 25 EC 100 X 80 ml', 'Matador merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna kuning jerami jernih untuk mengendalikan hama-hama pada tanaman bawang merah, bawang putih, cabai, jagung, jeruk, kacang panjang, kakao, kapas, kedelai, kelapa sawit, kubis, lada, lamtoro, mangga, teh, tembakau dan tomat. \nBahan Aktif:\r\n25 g/l lamda sihalotrin\r\nIsi Bersih: 80 ml\r\n', 'MATADOR_25EC_80ML.jpg', 22250, 22695, 23363, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(99, 9, 'QOLS00099', 'Matador 25 EC 40 X 250 ml', 'Matador merupakan Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna kuning jerami jernih untuk mengendalikan hama-hama pada tanaman bawang merah, bawang putih, cabai, jagung, jeruk, kacang panjang, kakao, kapas, kedelai, kelapa sawit, kubis, lada, lamtoro, mangga, teh, tembakau dan tomat. \nBahan Aktif:\r\n25 g/l lamda sihalotrin\r\nIsi Bersih: 250 ml\r\n', 'MATADOR_25EC_250ML.jpg', 62750, 64005, 65888, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(100, 1, 'QOLS00100', 'Melyra 200/200 SC 24 X 250 ml', 'Fungisida Melyra® 200/200SC merupakan fungisida dengan 2 bahan aktif Mefentrifluconazole 200g/l dan Piraklostrobin 200g/l untuk perlindungan tanaman jagung terhadap penyakit yang diakibatkan jamur secara lebih efektif dan meningkatkan hasil panen lebih maksimal.\nBahan Aktif: Mefentriflukonazol 200 g/L + Piraklostrobin 200 g/L\r\nIsi Bersih: 250 ml\r\n', 'MELYRA_250ML.jpg', 232500, 240000, 250000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(101, 9, 'QOLS00101', 'Metindo 25 WP 10 X 10 X 100 gr', 'METINDO 25 WP merupakan Insektisida racun kontak dan lambung, berbentuk tepung yang dapat disuspensikan, berwarna putih sampai krem, untuk mengendalikan hama pada tanaman Bawang Merah, Cabai, Kedelai, dan Tembakau.\nBahan Aktif: Metomil 25%\r\nIsi Bersih: 100 ml\r\n', 'METINDO_25WP_100GRAM.jpg', 15000, 15300, 15750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(102, 9, 'QOLS00102', 'Metindo 25 WP 40 X 250 gr', 'METINDO 25 WP merupakan Insektisida racun kontak dan lambung, berbentuk tepung yang dapat disuspensikan, berwarna putih sampai krem, untuk mengendalikan hama pada tanaman Bawang Merah, Cabai, Kedelai, dan Tembakau.\nBahan Aktif: Metomil 25%\r\nIsi Bersih: 250 ml\r\n', 'METINDO_25WP_250GRAM.jpg', 36250, 36975, 38063, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(103, 9, 'QOLS00103', 'Metindo 25 WP 40 X 500 gr', 'METINDO 25 WP merupakan Insektisida racun kontak dan lambung, berbentuk tepung yang dapat disuspensikan, berwarna putih sampai krem, untuk mengendalikan hama pada tanaman Bawang Merah, Cabai, Kedelai, dan Tembakau.\nBahan Aktif: Metomil 25%\r\nIsi Bersih: 500 ml\r\n', 'METINDO_25WP_500GRAM.jpg', 68500, 69870, 71925, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(104, 9, 'QOLS00104', 'Metindo 40 SP 10 X 10 X 100 gr', 'METINDO 40 SP adalah pestisida berjenis insektisida sistemik racun kontak dan lambung berbentuk tepung yang dapat larut dalam air. Termasuk keluarga karbamat yang memiliki spektrum pengendalian hama yang luas, yang utamanya sangat efektif untuk mengendalikan segala jenis ulat.\nBahan aktif: Metomil 40%\r\nIsi Bersih: 100 gr', 'METINDO_40SP_100GRAM.jpg', 22750, 23205, 23888, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(105, 9, 'QOLS00105', 'Metindo 40 SP 20 X 800 gr', 'METINDO 40 SP adalah pestisida berjenis insektisida sistemik racun kontak dan lambung berbentuk tepung yang dapat larut dalam air. Termasuk keluarga karbamat yang memiliki spektrum pengendalian hama yang luas, yang utamanya sangat efektif untuk mengendalikan segala jenis ulat.\nBahan aktif: Metomil 40%\r\nIsi Bersih: 800 gr', 'METINDO_40SP_800GRAM.jpg', 160000, 163200, 168000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(106, 9, 'QOLS00106', 'Metindo 40 SP 40 X 200 gr', 'METINDO 40 SP adalah pestisida berjenis insektisida sistemik racun kontak dan lambung berbentuk tepung yang dapat larut dalam air. Termasuk keluarga karbamat yang memiliki spektrum pengendalian hama yang luas, yang utamanya sangat efektif untuk mengendalikan segala jenis ulat.\nBahan aktif: Metomil 40%\r\nIsi Bersih: 200 gr\r\n', 'METINDO_40SP_200GRAM.jpg', 43250, 44115, 45413, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(107, 9, 'QOLS00107', 'Metindo 40 SP 40 X 400 gr', 'METINDO 40 SP adalah pestisida berjenis insektisida sistemik racun kontak dan lambung berbentuk tepung yang dapat larut dalam air. Termasuk keluarga karbamat yang memiliki spektrum pengendalian hama yang luas, yang utamanya sangat efektif untuk mengendalikan segala jenis ulat.\nBahan aktif: Metomil 40%\r\nIsi Bersih: 400 gr', 'METINDO_40SP_400GRAM.jpg', 85000, 86700, 89250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(108, 2, 'QOLS00108', 'Metsulindo Plus 80 WP 8 X 25 X 40 gr', 'Herbisida sistemik selektif pra tumbuh dan purna tumbuh, berbentuk tepung yang dapat disuspensikan, berwarna putih sampai krem untuk mengendalikan gulma berdaun lebar dan teki pada pertanaman padi sawah.\nBahan Aktif: 2,4-D natrium 76% (setara dengan 2,4-D 69%), metil metsulfuron 2%, etil klorimuron 2%\r\nIsi Bersih: 40 gram\r\n', 'METSULINDO_PLUS_40GRAM.jpg', 8600, 8772, 9030, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(109, 9, 'QOLS00109', 'Minecto Xtra 200/200 SC 100 X 50 ml', 'Minecto Xtra merupakan Insektisida racun kontak, lambung dan penghambat pertumbuhan serangga berbentuk pekatan suspensi, berwarna putih, untuk mengendalikan hama pada cabai, jagung, dan tomat. \nBahan Aktif: 200 g/l lufenuron +200 g/l\" siantraniliprol\"\r\nKemasan : 50 ml\r\n', 'MINECTO_XTRA_50ML.jpg', 115000, 117300, 120750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(110, 9, 'QOLS00110', 'Minecto Xtra 200/200 SC 50 X 100 ml', 'Minecto Xtra merupakan Insektisida racun kontak, lambung dan penghambat pertumbuhan serangga berbentuk pekatan suspensi, berwarna putih, untuk mengendalikan hama pada cabai, jagung, dan tomat. \nBahan Aktif: 200 g/l lufenuron +200 g/l\" siantraniliprol\"\r\nKemasan : 100 ml\r\n', 'MINECTO_XTRA_100ML.jpg', 232000, 236640, 243600, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(111, 9, 'QOLS00111', 'Mition 500 EC 15 X 1 ltr', 'Mition 500 EC Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna kuning, untuk mengendalikan hama pada tanaman cabai.\nBahan Aktif: etion 500 g/l\r\nIsi Bersih: 1 Liter\r\n', 'MITION_500EC_1LITER.jpg', 235000, 239700, 246750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(112, 9, 'QOLS00112', 'Mition 500 EC 20 X 500 ml', 'Mition 500 EC Insektisida racun kontak dan lambung, berbentuk pekatan yang dapat diemulsikan, berwarna kuning, untuk mengendalikan hama pada tanaman cabai.\nBahan Aktif: etion 500 g/l\r\nIsi Bersih: 500 ml\r\n', 'MITION_500EC_500ML.jpg', 120000, 122400, 126000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(113, 9, 'QOLS00113', 'Movento Energy 240 SC 50 X 100 ml', 'Movento Energy 240 SC Insektisida sistemik racun kontak dan lambung berbentuk pekatan suspensi putih keabu-abuan untuk mengendalikan hama pada tanaman apel, bawang merah, cabai, jambu biji, jeruk, kentang, kopi, kubis, mangga, melon, rambutan, semangka, tembakau dan tomat\nBahan aktif : Imidakloprid 120 g/ℓ + Spirotetramat 120 g/ℓ\r\nIsi Bersih: 100 ml\r\n', 'MOVENTO_ENERGY_100ML.jpg', 105500, 107610, 110775, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(114, 1, 'QOLS00114', 'Nativo 75 WG 16 X 20 X 12.5 gr', 'Nativo® adalah fungisida sistemik yang bersifat protektif, preventif, kuratif dan eradikatif berbentuk butiran yang dapat didispersikan dalam air berwarna putih untuk mengendalikan penyakit pada tanaman anggrek, apel, bawang merah, cabai, jagung, jeruk, kacang hijau, kacang tanah, kacang panjang, kakao, karet, krisan, kedelai, kopi, mangga, melon, mentimun, padi, pembibitan kelapa sawit, pisang, semangka, teh, tembakau, dan tomat.\nBahan aktif : Trifloksistrobin 25%  + Tebukonazol 50%\r\nIsi Bersih: 12.5 gr\r\n', 'NATIVO_75WP_12,5GRAM.jpg', 17000, 17340, 17850, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(115, 9, 'QOLS00115', 'Nemaguard 10 GR 10 X 1 kg', 'NEMAGUARD 10 GR Insektisida racun kontak dan lambung, berbentuk butiran, berwarna hijau, untuk mengendalikan hama ulat tanah Agrotis ipsilon pada tanaman kentang.\nBahan Aktif: oksamil 10 %\r\nIsi Bersih: 1 Kilogram\r\n', 'NEMAGUARD_10GR_1KG.jpg', 88000, 89760, 92400, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(116, 9, 'QOLS00116', 'Nemaguard 10 GR 20 X 500 gr', 'NEMAGUARD 10 GR Insektisida racun kontak dan lambung, berbentuk butiran, berwarna hijau, untuk mengendalikan hama ulat tanah Agrotis ipsilon pada tanaman kentang.\nBahan Aktif: oksamil 10 %\r\nIsi Bersih: 500 Gram\r\n', 'NEMAGUARD_10GR_500GRAM.jpg', 47500, 48450, 49875, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(117, 2, 'QOLS00117', 'Neocron 80 OL 15 X 1 ltr', 'Herbisida selektif purna tumbuh berbentuk larutan dalam minyak berwarna putih untuk mengendalikan gulma berdaun lebar dan gulma berdaun sempit pada pertanaman jagung.\nBahan Aktif: nikosulfuron 80 g/l\r\nIsi Bersih: 1 Liter', 'NEOCRON_80OL_1LITER.jpg', 325000, 331500, 341250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(118, 2, 'QOLS00118', 'Neocron 80 OL 20 X 250 ml', 'Herbisida selektif purna tumbuh berbentuk larutan dalam minyak berwarna putih untuk mengendalikan gulma berdaun lebar dan gulma berdaun sempit pada pertanaman jagung.\nBahan Aktif: nikosulfuron 80 g/l\r\nIsi Bersih: 250 ml', 'NEOCRON_80OL_250ML.jpg', 86500, 88230, 90825, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(119, 2, 'QOLS00119', 'Neocron 80 OL 20 X 500 ml', 'Herbisida selektif purna tumbuh berbentuk larutan dalam minyak berwarna putih untuk mengendalikan gulma berdaun lebar dan gulma berdaun sempit pada pertanaman jagung.\nBahan Aktif: nikosulfuron 80 g/l\r\nIsi Bersih: 500 ml\r\n', 'NEOCRON_80OL_500ML.jpg', 165000, 168300, 173250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(120, 1, 'QOLS00120', 'Orondis Opti 6/400 SC 20 X 500 ml', 'Orondis Opti merupakan Fungisida sistemik yang berbentuk pekatan suspensi, berwarna putih untuk mengendalikan penyakit pada tanaman melon, semangka, timun dan tomat.\nBahan Aktif: 400 g/lklorotalonil + 6 g/l\"oksatiapiprolin\"\r\nKemasan : 500 ml\r\n', 'ORONDIS_OPTI_500ML.jpg', 160000, 163200, 168000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(121, 2, 'QOLS00121', 'Paket Convey 336 SC 10 X 40 ml', 'Convey® adalah herbisida sistemik purna tumbuh berbentuk pekatan suspensi berwarna cokelat muda yang aman untuk tanaman dan fleksibel dalam waktu aplikasinya dibanding dengan herbisida sejenis, sehingga melindungi lebih lama.\nBahan Aktif: Topramezon 336g/l\r\nIsi Bersih: 40 ml\r\n', 'CONVEY_336SC_40ML.jpg', 327000, 333540, 343350, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(122, 2, 'QOLS00122', 'Paket Convey 336 SC 20 X 20 ml', 'Convey® adalah herbisida sistemik purna tumbuh berbentuk pekatan suspensi berwarna cokelat muda yang aman untuk tanaman dan fleksibel dalam waktu aplikasinya dibanding dengan herbisida sejenis, sehingga melindungi lebih lama.\nBahan Aktif: Topramezon 336g/l\r\nIsi Bersih: 20 ml\r\n', 'CONVEY_336SC_20ML.jpg', 193500, 197370, 203175, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(123, 9, 'QOLS00123', 'Pegasus 500 SC 50 X 80 ml', 'Pegasus merupakan Insektisida dan akarisida racun kontak dan lambung, berbentuk pekatan suspensi berwarna putih keabu-abuan yang dapat larut dalam air, untuk mengendalikan hama serangga pada tanaman apel, bawang merah, cabai, kentang, kubis, semangka dan tomat.\nBahan Aktif: 500 g/l Diafenthiuron\r\nKemasan : 80 ml\r\n', 'PEGASUS_500SC_80ML.jpg', 81500, 83130, 85575, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(124, 9, 'QOLS00124', 'Plenum 50 WG 12 X 10 X 25 gr', 'Plenum merupakan Insektisida sistemik yang bersifat menghambat aktivitas makan serangga, berbentuk butiran berwarna kecoklatan yang dapat disuspensikan dalam air untuk mengendalikan hama wereng coklat, wereng punggung putih, wereng hijau, kepinding tanah dan walang sangit pada tanaman padi.\nBahan Aktif: 50% pimetrozin\r\nKemasan : 25 gram\r\n', 'PLENUM_50WG_25GRAM.jpg', 35000, 35700, 36750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(125, 9, 'QOLS00125', 'Plenum 50 WG 50 X 100 gr', 'Plenum merupakan Insektisida sistemik yang bersifat menghambat aktivitas makan serangga, berbentuk butiran berwarna kecoklatan yang dapat disuspensikan dalam air untuk mengendalikan hama wereng coklat, wereng punggung putih, wereng hijau, kepinding tanah dan walang sangit pada tanaman padi.\nBahan Aktif: 50% pimetrozin\r\nKemasan : 100 gram\r\n', 'PLENUM_50WG_100GRAM.jpg', 129500, 132090, 135975, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(126, 1, 'QOLS00126', 'Previcur N 722SL 100 X 100 ml', 'Previcur® N 722 SL Fungisida sistemik berbentuk larutan dalam air berwarna kekuning-kuningan yang ditranslokasikan ke seluruh bagian tanaman untuk mengendalikan penyakit pada tanaman bawang merah, cabai, kentang, kubis, melon, pinus, tembakau dan tomat.\nBahan aktif : Propamokarb hidroklorida 722 g/l\r\nIsi Bersih: 100 ml\r\n', 'PREVICUR_N_722SL_100ML.jpg', 36000, 36720, 37800, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(127, 1, 'QOLS00127', 'Previcur N 722SL 20 X 500 ml', 'Previcur® N 722 SL Fungisida sistemik berbentuk larutan dalam air berwarna kekuning-kuningan yang ditranslokasikan ke seluruh bagian tanaman untuk mengendalikan penyakit pada tanaman bawang merah, cabai, kentang, kubis, melon, pinus, tembakau dan tomat.\nBahan aktif : Propamokarb hidroklorida 722 g/l\r\nIsi Bersih: 500 ml\r\n', 'PREVICUR_N_722SL_500ML.jpg', 162750, 166005, 170888, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(128, 9, 'QOLS00128', 'Regent 50 SC Red 24 X 250 ml', 'Regent® 50 SC RED adalah insektisida sistemik racun kontak dan lambung  berbentuk pekatan suspensi berwarna merah yang digunakan untuk mengendalikan hama pada tanaman kedelai, jagung dan padi serta sebagai ZPT pada Jagung.\nBahan Aktif: Fipronil 50 g/l\r\nIsi Bersih: 250 ml\r\n', 'REGENT_RED_50SC_250ML.jpg', 97000, 98940, 101850, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(129, 9, 'QOLS00129', 'Regent 50 SC Red 48 X 100 ml', 'Regent® 50 SC RED adalah insektisida sistemik racun kontak dan lambung  berbentuk pekatan suspensi berwarna merah yang digunakan untuk mengendalikan hama pada tanaman kedelai, jagung dan padi serta sebagai ZPT pada Jagung.\nBahan Aktif: Fipronil 50 g/l\r\nIsi Bersih: 100 ml\r\n', 'REGENT_RED_50SC_100ML.jpg', 42500, 43350, 44625, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(130, 9, 'QOLS00130', 'Regent 50 SC Red 80 X 50 ml', 'Regent® 50 SC RED adalah insektisida sistemik racun kontak dan lambung  berbentuk pekatan suspensi berwarna merah yang digunakan untuk mengendalikan hama pada tanaman kedelai, jagung dan padi serta sebagai ZPT pada Jagung.\nBahan Aktif: Fipronil 50 g/l\r\nIsi Bersih: 50 ml\r\n', 'REGENT_RED_50SC_50ML.jpg', 22500, 22950, 23625, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(131, 1, 'QOLS00131', 'Remazole-P 490 EC 20 X 400 ml', 'Remazole P 490 EC adalah fungisida sistemik dalam bentuk pekatan yang dapat diemulsikan (EC) untuk mengendalikan berbagai penyakit jamur pada tanaman seperti padi, bawang merah, kedelai, dan mangga. Fungisida ini mengandung dua bahan aktif, yaitu Prokloraz (400 g/L) dan Propikonazol (90 g/L). Remazole P 490 EC bekerja secara protektif, kuratif, dan eradikatif, efektif untuk melawan penyakit seperti bercak daun, karat daun, hawar pelepah, dan bercak ungu.\nBahan Aktif:\r\nProkloraz (400 g/L) dan Propikonazol (90 g/L).\r\nIsi Bersih: 400 ml\r\n', 'REMAZOLE_P_490EC_400ML.jpg', 333500, 340170, 350175, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(132, 1, 'QOLS00132', 'Remazole-P 490 EC 40 X 250 ml', 'Remazole P 490 EC adalah fungisida sistemik dalam bentuk pekatan yang dapat diemulsikan (EC) untuk mengendalikan berbagai penyakit jamur pada tanaman seperti padi, bawang merah, kedelai, dan mangga. Fungisida ini mengandung dua bahan aktif, yaitu Prokloraz (400 g/L) dan Propikonazol (90 g/L). Remazole P 490 EC bekerja secara protektif, kuratif, dan eradikatif, efektif untuk melawan penyakit seperti bercak daun, karat daun, hawar pelepah, dan bercak ungu.\nBahan Aktif:\r\nProkloraz (400 g/L) dan Propikonazol (90 g/L).\r\nIsi Bersih: 250 ml\r\n', 'REMAZOLE_P_490EC_250ML.jpg', 217000, 221340, 227850, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(133, 1, 'QOLS00133', 'Revus Opti 440 SC 20 X 250 ml', 'Revus Opti merupakan Fungisida protektif yang bersifat sistemik dan kontak, berbentuk pekatan suspensi, berwarna putih keabu-abuan, untuk mengendalikan penyakit secara preventif dan kuratif pada tanaman bawang merah, cabai, jeruk, kakao, kentang, mentimun, melon, semangka, tembakau dan tomat. \nBahan Aktif:\r\n40 g/l mandipropamid\r\n400 g/l klorotalonil\r\nKemasan : 250 ml\r\n', 'REVUS_OPTI_440SC_250ML.jpg', 105000, 107100, 110250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(134, 2, 'QOLS00134', 'Ricestar Xtra 89 OD 100 X 100 ml', 'Ricestar® Xtra 89 OD Herbisida sistemik purna tumbuh berbentuk larutan dalam minyak berwarna putih keabu-abuan untuk mengendalikan gulma pada pertanaman padi.\nBahan aktif : Fenoksaprop-p-etil 69 g/ℓ + Etoksisulfuron 20 g/l\r\nIsi Bersih: 100 ml\r\n', 'RICESTAR_XTRA_100ML.jpg', 59500, 60690, 62475, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(135, 2, 'QOLS00135', 'Ricestar Xtra 89 OD 40 X 250 ml', 'Ricestar® Xtra 89 OD Herbisida sistemik purna tumbuh berbentuk larutan dalam minyak berwarna putih keabu-abuan untuk mengendalikan gulma pada pertanaman padi.\nBahan aktif : Fenoksaprop-p-etil 69 g/ℓ + Etoksisulfuron 20 g/l\r\nIsi Bersih: 250 ml\r\n', 'RICESTAR_XTRA_250ML.jpg', 140000, 142800, 147000, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(136, 1, 'QOLS00136', 'Ridomil Gold 350 ES 4 X 20 X 12.5 ml', 'Fungisida sistemik berbentuk larutan emulsi berwarna merah muda untuk perlakuan benih untuk mengendalikan penyakit pada tanaman cabai, jagung, lada, dan tembakau yang digunakan dengan cara dicampurkan dengan benih sebelum tanam agar benih yang ditanam lebih tanam terhadap serangan jamur yang rawan menyerang tanaman pada saat umur muda.\nBahan Aktif: Mefenoksam 350 g/l\r\nKemasan : 12,5 ml\r\n', 'RIDOMIL_GOLD_350ES_12,5ML.jpg', 21750, 22185, 22838, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(137, 1, 'QOLS00137', 'Ridomil Gold MZ 4/64 WG 100 X 100 gr', 'Fungisida sistemik berbentuk larutan emulsi berwarna merah muda untuk perlakuan benih untuk mengendalikan penyakit pada tanaman cabai, jagung, lada, dan tembakau yang digunakan dengan cara dicampurkan dengan benih sebelum tanam agar benih yang ditanam lebih tanam terhadap serangan jamur yang rawan menyerang tanaman pada saat umur muda.\nBahan Aktif: Mefenoksam 350 g/l\r\nKemasan : 100 gram\r\n', 'RIDOMIL_GOLD_100GRAM.jpg', 37000, 37740, 38850, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(138, 1, 'QOLS00138', 'Ridomil Gold MZ 4/64 WG 20 X 500 gr', 'Fungisida sistemik berbentuk larutan emulsi berwarna merah muda untuk perlakuan benih untuk mengendalikan penyakit pada tanaman cabai, jagung, lada, dan tembakau yang digunakan dengan cara dicampurkan dengan benih sebelum tanam agar benih yang ditanam lebih tanam terhadap serangan jamur yang rawan menyerang tanaman pada saat umur muda.\nBahan Aktif: Mefenoksam 350 g/l\r\nKemasan : 500 gram\r\n', 'RIDOMIL_GOLD_500GRAM.jpg', 172000, 175440, 180600, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(139, 1, 'QOLS00139', 'Ridomil Gold MZ 4/64 WG 40 X 250 gr', 'Fungisida sistemik berbentuk larutan emulsi berwarna merah muda untuk perlakuan benih untuk mengendalikan penyakit pada tanaman cabai, jagung, lada, dan tembakau yang digunakan dengan cara dicampurkan dengan benih sebelum tanam agar benih yang ditanam lebih tanam terhadap serangan jamur yang rawan menyerang tanaman pada saat umur muda.\nBahan Aktif: Mefenoksam 350 g/l\r\nKemasan : 250 gram\r\n', 'RIDOMIL_GOLD_250GRAM.jpg', 88000, 89760, 92400, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(140, 9, 'QOLS00140', 'Rotraz 200 EC 20 X 500 ml', 'Rotraz 200 EC Insektisida dan akarisida racun kontak dan pernafasan, berbentuk pekatan yang dapat diemulsikan dalam air, berwarna coklat kemerahan, untuk mengendalikan hama pada tanaman apel dan cabai.\nBahan Aktif: amitraz 200 g/l\r\nIsi Bersih: 500 ml\r\n', 'ROTRAZ_200EC_500ML.jpg', 95000, 96900, 99750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(141, 9, 'QOLS00141', 'Rotraz 200 EC 50 X 100 ml', 'Rotraz 200 EC Insektisida dan akarisida racun kontak dan pernafasan, berbentuk pekatan yang dapat diemulsikan dalam air, berwarna coklat kemerahan, untuk mengendalikan hama pada tanaman apel dan cabai.\nBahan Aktif: amitraz 200 g/l\r\nIsi Bersih: 100 ml\r\n', 'ROTRAZ_200EC_100ML.jpg', 27000, 27540, 28350, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(142, 2, 'QOLS00142', 'Rumpas 110 EW 100 X 100 ml', 'Rumpas® 110 EW Herbisida kontak dan sistemik purna tumbuh berbentuk pekatan emulsi minyak dalam air berwarna putih untuk mengendalikan gulma pada pertanaman bawang merah, cabai, kacang hijau, kacang panjang, kacang tanah, kedelai, ketimun, nanas, padi sawah dan tebu.\nBahan aktif : Fenoksaprop -p-etil 110 g/l\r\nIsi Bersih: 100 ml\r\n', 'RUMPAS_110EW_100ML.jpg', 41250, 42075, 43313, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(143, 2, 'QOLS00143', 'Rumpas 110 EW 40 X 250 ml', 'Rumpas® 110 EW Herbisida kontak dan sistemik purna tumbuh berbentuk pekatan emulsi minyak dalam air berwarna putih untuk mengendalikan gulma pada pertanaman bawang merah, cabai, kacang hijau, kacang panjang, kacang tanah, kedelai, ketimun, nanas, padi sawah dan tebu.\nBahan aktif : Fenoksaprop -p-etil 110 g/l\r\nIsi Bersih: 250 ml\r\n', 'RUMPAS_110EW_250ML.jpg', 98500, 100470, 103425, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(144, 1, 'QOLS00144', 'Score 250 EC 40 X 250 ml', 'Score merupakan Fungisida sistemik berbentuk pekatan yang dapat diemulsikan, berwarna coklat kekuning-kuningan sampai coklat tua untuk mengendalikan penyakit pada tanaman apel, bawang merah, bawang putih, cabai, jagung, jarak pagar, jeruk, kacang panjang, kedelai, kelapa sawit, kentang, mangga, padi, semangka, tembakau, dan tomat. \nBahan Aktif: 250 g/l difenokonazol\r\nKemasan : 250 ml\r\n', 'SCORE_250EC_250ML.jpg', 165250, 168555, 173513, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(145, 1, 'QOLS00145', 'Score 250 EC 50 X 80 ml', 'Score merupakan Fungisida sistemik berbentuk pekatan yang dapat diemulsikan, berwarna coklat kekuning-kuningan sampai coklat tua untuk mengendalikan penyakit pada tanaman apel, bawang merah, bawang putih, cabai, jagung, jarak pagar, jeruk, kacang panjang, kedelai, kelapa sawit, kentang, mangga, padi, semangka, tembakau, dan tomat. \nBahan Aktif: 250 g/l difenokonazol\r\nKemasan : 80 ml\r\n', 'SCORE_250EC_80ML.jpg', 55250, 56355, 58013, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(146, 1, 'QOLS00146', 'Seltima 100 CS 20 X 500 ml', 'Seltima® 100 CS adalah fungisida dengan formulasi inovatif yang bekerja secara sistemik, bekerja protektif dan kuratif berbentuk mikrokapsul dalam pekatan yang dapat disuspensikan berwarna putih untuk mengendalikan penyakit pada tanaman padi. Seltima® juga memiliki efek Agcelence yang berperan dalam pengisian gabah sehingga gabah terisi maksimal dan mengurangi jumlah gabah kosong.\nBahan aktif: Piraklostrobin 100 g/l\r\nIsi Bersih: 500 ml\r\n', 'SELTIMA_100CS_500ML.jpg', 183500, 187170, 192675, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(147, 1, 'QOLS00147', 'Seltima 100 CS 24 X 250 ml', 'Seltima® 100 CS adalah fungisida dengan formulasi inovatif yang bekerja secara sistemik, bekerja protektif dan kuratif berbentuk mikrokapsul dalam pekatan yang dapat disuspensikan berwarna putih untuk mengendalikan penyakit pada tanaman padi. Seltima® juga memiliki efek Agcelence yang berperan dalam pengisian gabah sehingga gabah terisi maksimal dan mengurangi jumlah gabah kosong.\nBahan aktif: Piraklostrobin 100 g/l\r\nIsi Bersih: 250 ml\r\n', 'SELTIMA_100CS_250ML.jpg', 99500, 101490, 104475, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(148, 9, 'QOLS00148', 'Simodis 100 DC 50 X 100 ml', 'Simodis merupakan Insektisida racun kontak dan lambung berbentuk pekatan yang dapat didispersikan, berwarna coklat, untuk mengendalikan hama pada tanaman bawang merah, cabai, kubis dan tomat.\nBahan Aktif:\r\n100 g/l isosikloseram \r\nKemasan : 100 ml\r\n', 'SIMODIS_100DC_100ML.jpg', 128000, 130560, 134400, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(149, 9, 'QOLS00149', 'Sivanto Prime 200 SL 100 X 100 ml', 'Sivanto Prime 200 SL Insektisida sistemik berbentuk larutan dalam air, berwarna kuning kecoklatan / merah, untuk mengendalikan hama pada tanaman cabai, jeruk, tembakau, terong, dan tomat\nBahan aktif : Flupiradifuron 200 g/l\r\nIsi Bersih: 100 ml\r\n', 'SIVANTO_PRIME_100ML.jpg', 61000, 62220, 64050, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(150, 15, 'QOLS00150', 'Stardon 60 WP 10 X 8 X 100 gr', 'Stardon 60 WP Moluskisida racun kontak berbentuk tepung yang dapat disuspensikan, berwarna putih, untuk mengendalikan siput murbei (Pomacea canaliculata) pada tanaman padi sawah.\nBahan Aktif: fentin asetat 60%\r\nIsi Bersih: 100 Gram\r\n', 'STARDON_60WP_100GRAM.jpg', 53000, 54060, 55650, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(151, 9, 'QOLS00151', 'Symphony Plus 32 X 250 ml', 'SYMPHONY 100 EC merupakan Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna kuning bening, untuk mengendalikan hama pada tanaman Bawang Merah, Cabai, dan Kubis.\nBahan Aktif : Piridalil 100 g/L\r\nIsi Bersih: 250 ml\r\n', 'SYMPHONY_PLUS_250ML.jpg', 330000, 336600, 346500, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(152, 9, 'QOLS00152', 'Symphony Plus 40 X 100 ml', 'SYMPHONY 100 EC merupakan Insektisida racun kontak dan lambung berbentuk pekatan yang dapat diemulsikan berwarna kuning bening, untuk mengendalikan hama pada tanaman Bawang Merah, Cabai, dan Kubis.\nBahan Aktif : Piridalil 100 g/L\r\nIsi Bersih: 100 ml\r\n', 'SYMPHONY_PLUS_100ML.jpg', 135000, 137700, 141750, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(153, 1, 'QOLS00153', 'Topsindo 70 WP 20 X 500 gr', 'Topsindo 70 WP Fungisida kontak dan sistemik berbentuk tepung yang dapat disuspensikan, berwarna putih sampai krem, untuk mengendalikan penyakit yang disebabkan oleh jamur pada tanaman padi dan tembakau.\nBahan Aktif: metil tiofanat 70%\r\nIsi Bersih: 500 ml\r\n', 'TOPSINDO_70WP_500GRAM.jpg', 98000, 99960, 102900, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(154, 9, 'QOLS00154', 'Trigard 75 WP 10 X 10 X 50 gr', 'Trigard® 75 WP merupakan insektisida sistemik penghambat pertumbuhan serangga, berbentuk tepung yang dapat disuspensikan berwarna putih kecoklat-coklatan, digunakan untuk mengendalikan hama pada tanaman bawang merah, kacang panjang, kentang, dan tomat. \nBahan Aktif: 75% siromazin\r\nKemasan : 50 gram\r\n', 'TRIGARD_75WP_50GRAM.jpg', 201500, 205530, 211575, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(155, 9, 'QOLS00155', 'Trigard 75 WP 12 X 10 X 25 gr', 'Trigard® 75 WP merupakan insektisida sistemik penghambat pertumbuhan serangga, berbentuk tepung yang dapat disuspensikan berwarna putih kecoklat-coklatan, digunakan untuk mengendalikan hama pada tanaman bawang merah, kacang panjang, kentang, dan tomat. \nBahan Aktif: 75% siromazin\r\nKemasan : 25 gram\r\n', 'TRIGARD_75WP_25GRAM.jpg', 105500, 107610, 110775, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(156, 2, 'QOLS00156', 'Unibro 500/50 SC 20 X 250 ml', 'UNIBRO 500/50 EC Herbisida sistemik selektif pra tumbuh dan purna tumbuh berbentuk pekatan suspensi, berwarna coklat muda, untuk mengendalikan gulma berdaun lebar, gulma golongan rumput dan teki pada budidaya tanaman jagung.\nBahan Aktif: atrazin 500 g/l mesotrion 50 g/l\r\nIsi Bersih: 250 ml\r\n', 'UNIBRO_250ML.jpg', 65000, 66300, 68250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(157, 2, 'QOLS00157', 'Unibro 500/50 SC 20 X 500 ml', 'UNIBRO 500/50 EC Herbisida sistemik selektif pra tumbuh dan purna tumbuh berbentuk pekatan suspensi, berwarna coklat muda, untuk mengendalikan gulma berdaun lebar, gulma golongan rumput dan teki pada budidaya tanaman jagung.\nBahan Aktif: atrazin 500 g/l mesotrion 50 g/l\r\nIsi Bersih: 500 ml\r\n', 'UNIBRO_500ML.jpg', 125000, 127500, 131250, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(158, 9, 'QOLS00158', 'Vayego 200 SC 50 X 100 ml', 'Vayego® 200 SC Insektisida sistemik, racun kontak dan lambung berbentuk pekatan suspensi, berwarna putih sampai kecoklatan, untuk mengendalikan hama pada tanaman bawang merah, cabai, jagung, kelapa sawit dan kubis.\nBahan aktif: Tetraniliprol 200 g/l\r\nIsi Bersih: 100 ml\r\n', 'VAYEGO_200SC_100ML.jpg', 206000, 210120, 216300, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(159, 9, 'QOLS00159', 'Virtako 300 SC 10 X 20 X 10 ml', 'Virtako 300SC merupakan Insektisida racun kontak dan sistemik berbentuk pekatan suspensi berwarna putih kecoklatan untuk mengendalikan hama pada tanaman bawang merah, cabai, dan padi sawah.\nBahan Aktif:\r\n100 g/l Chlorantraniliprole\r\n200 g/l Thiametoxam\r\nIsi Bersih: 10 ml\r\n', 'VIRTAKO_300SC_10ML.jpg', 21750, 22185, 22838, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(160, 9, 'QOLS00160', 'Virtako 300 SC 100 X 50 ml', 'Virtako 300SC merupakan Insektisida racun kontak dan sistemik berbentuk pekatan suspensi berwarna putih kecoklatan untuk mengendalikan hama pada tanaman bawang merah, cabai, dan padi sawah.\nBahan Aktif:\r\n100 g/l Chlorantraniliprole\r\n200 g/l Thiametoxam\r\nIsi Bersih: 50 ml\r\n', 'VIRTAKO_300SC_50ML.jpg', 102000, 104040, 107100, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0),
(161, 9, 'QOLS00161', 'Virtako 300 SC 50 X 100 ml', 'Virtako 300SC merupakan Insektisida racun kontak dan sistemik berbentuk pekatan suspensi berwarna putih kecoklatan untuk mengendalikan hama pada tanaman bawang merah, cabai, dan padi sawah.\nBahan Aktif:\r\n100 g/l Chlorantraniliprole\r\n200 g/l Thiametoxam\r\nIsi Bersih: 100 ml\r\n', 'VIRTAKO_300SC_100ML.jpg', 198000, 201960, 207900, 1000, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, NULL, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_category`
--

CREATE TABLE `product_category` (
  `id` int(10) NOT NULL,
  `name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_category`
--

INSERT INTO `product_category` (`id`, `name`) VALUES
(1, 'Fungisida'),
(2, 'Herbisida'),
(9, 'Insektisida'),
(10, 'Obat-Obat'),
(12, 'Sarana Pertanian'),
(13, 'Lain Lain'),
(14, 'Benih'),
(15, 'Moluskisida'),
(16, 'Perata & Penetran'),
(17, 'Perata & Perekat'),
(18, 'Biostimulant'),
(19, 'Pupuk cair'),
(20, 'zpt');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` int(10) NOT NULL,
  `key` varchar(32) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(7, 'Jalur Nugraha Ekakurir (JNE);REG;Layanan Reguler;6 day;83000', 'KIU850109250008', 'jne', '85', 3, '2025-09-01'),
(8, 'Jalur Nugraha Ekakurir (JNE);REG;Layanan Reguler;6 day;83000', 'KIU850309250001', 'jne', '85', 3, '2025-09-03'),
(9, 'Jalur Nugraha Ekakurir (JNE);REG;Layanan Reguler;11 day;83000', 'KIU850309250002', 'jne', '85', 3, '2025-09-03'),
(10, 'Jalur Nugraha Ekakurir (JNE);REG;Layanan Reguler;3 day;20000', 'KIU850409250001', 'jne', '85', 3, '2025-09-04'),
(11, 'POS Indonesia (POS);Pos Reguler;240;8 day;44000', 'KIU940409250002', 'pos', '94', 3, '2025-09-04'),
(12, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;20000', 'KIU870509250001', 'jne', '87', 3, '2025-09-05'),
(13, 'J&T Express;EZ;Reguler;;12000', 'KIU870609250001', 'jnt', '87', 3, '2025-09-06'),
(14, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;10000', 'KIU870909250001', 'jne', '87', 3, '2025-09-09'),
(15, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;10000', 'KIU870909250002', 'jne', '87', 3, '2025-09-09'),
(16, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;10000', 'KIU870909250003', 'jne', '87', 3, '2025-09-09'),
(17, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;10000', 'KIU870909250004', 'jne', '87', 3, '2025-09-09'),
(18, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;20000', 'KIU871009250001', 'jne', '87', 3, '2025-09-10'),
(19, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;60000', 'KIU871009250002', 'jne', '87', 3, '2025-09-10'),
(20, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;10000', 'KIU871009250003', 'jne', '87', 3, '2025-09-10'),
(21, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;70000', 'KIU871009250004', 'jne', '87', 3, '2025-09-10'),
(22, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;20000', 'KIU871009250005', 'jne', '87', 3, '2025-09-10'),
(23, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;10000', 'KIU871109250001', 'jne', '87', 3, '2025-09-11'),
(24, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;20000', 'KIU871109250002', 'jne', '87', 3, '2025-09-11'),
(25, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;50000', 'KIU871109250003', 'jne', '87', 3, '2025-09-11'),
(26, 'Jalur Nugraha Ekakurir (JNE);REG;Layanan Reguler;3 day;10000', 'KIU852909250001', 'jne', '85', 3, '2025-09-29'),
(27, 'Jalur Nugraha Ekakurir (JNE);JTR;JNE Trucking;5 day;100000', 'KIU852909250002', 'jne', '85', 3, '2025-09-29'),
(28, 'Jalur Nugraha Ekakurir (JNE);CTC;JNE City Courier;8 day;10000', 'KIU872909250003', 'jne', '87', 3, '2025-09-29');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `profile_picture` varchar(128) DEFAULT NULL,
  `role` varchar(32) DEFAULT '0' COMMENT '1 = admin, 2 = customer',
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
(87, NULL, 'cumum1@gmail.com', NULL, '$2y$10$YdLJCYIlC5wFJX6zhBT5yO7OdiWGUFYkHCoaxR003w61K4MRIlXD2', NULL, 'customer', '2025-02-28 14:51:17', 1),
(94, NULL, 'customer1@gmail.com', NULL, '$2y$10$/RifMTeib65OKDHmq5dfX.cxP49ay3XykOT4WH9j9FBchEkCmmTYi', NULL, 'customer', '2025-09-04 15:51:05', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_bk`
--

CREATE TABLE `users_bk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(191) NOT NULL,
  `profile_picture` varchar(128) DEFAULT NULL,
  `role` varchar(32) DEFAULT '0' COMMENT '1 = admin, 2 = customer',
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `briva_api`
--
ALTER TABLE `briva_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `customer_location`
--
ALTER TABLE `customer_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `generate_kdchart`
--
ALTER TABLE `generate_kdchart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `message`
--
ALTER TABLE `message`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `orders_bk`
--
ALTER TABLE `orders_bk`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT untuk tabel `product_category`
--
ALTER TABLE `product_category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `tmp_cart`
--
ALTER TABLE `tmp_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

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
