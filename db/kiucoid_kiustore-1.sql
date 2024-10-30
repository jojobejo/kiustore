-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Okt 2024 pada 15.54
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
(2, 2, 'bn1.jpg', '2022-06-18 02:23:10'),
(3, 8, 'bn2.jpg', '2022-06-18 02:24:15'),
(4, 11, 'bn3.jpg', '2022-06-18 02:24:43');

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
('7c6kdj43gk95skva31hi4fokghaac4cn', '::1', 1729254111, 0x5f5f63695f6c6173745f726567656e65726174657c693a313732393235343131313b),
('bt4kqkapbnj5huop8b7n0csn0n37lld9', '::1', 1729259692, 0x5f5f63695f6c6173745f726567656e65726174657c693a313732393235333631373b5f5f4143544956455f53455353494f4e5f444154417c733a3238303a2263643233343566326431373735623065303034656636326139353266313366336462393666313536656663633033393665633739643635326635386330613763363138653236346234303939376462343361326130646130363034613538663437633064636663313431663330303066353065303163663737373764393761396578593356585951346b7434376e6d594f687948744f613443656c4b784e4a2f2b6a70654334534d564a544e45495a79333832633344444f59394b4f4647305038354d38717464474f495a615037513441624149573639516a6d316f462f79735579764169747742576953597744673436424b6f3157316b6c337a347556336b4b462b6b6c426c565165315350656f6e6531736a76673d3d223b),
('h2donkhrctn2vhnbijk8dvefev0m7l5f', '::1', 1729256553, 0x5f5f63695f6c6173745f726567656e65726174657c693a313732393235363535333b5f5f4143544956455f53455353494f4e5f444154417c733a3334343a2238333433303732323337363961633763623231366439303561326263643732313361336338656534336536613463313730646462396565373737633630366463396565363231373039323037306565666364613066646136613165346537323162613936663437323465336637656139316636346137666462386635326632387a357a6535436830556e687755464e45754149505733346237497459476a3756464256796e4e2b77394b51667a346b362b3346414c53757a517470703945777431476745694e465042732b6d36644f5339482b4d4165387571417570626e786556682b755244672f5942396a2b6164486372515257472b70756e4149515362326d497a624c64582b547042552b706d6364486832477a6579647545794b41395775755437424c364a2b7745776d706e4544394955312b786558324d73696a59795151443254685a535256307072724545656d457a45513d3d223b636172745f636f6e74656e74737c613a333a7b733a31303a22636172745f746f74616c223b643a39393030303b733a31313a22746f74616c5f6974656d73223b643a313b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b613a31303a7b733a323a226964223b733a313a2231223b733a333a22717479223b643a313b733a363a2273617475616e223b733a313a2231223b733a31313a2273617475616e5f74657874223b733a333a22506373223b733a31303a2273617475616e5f717479223b733a323a223130223b733a353a227072696365223b643a39393030303b733a343a226e616d65223b733a373a224163652d4f6e65223b733a31323a2270726f647563745f74797065223b733a313a2231223b733a353a22726f776964223b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b733a383a22737562746f74616c223b643a39393030303b7d7d),
('1da4tuodfagur52ljhp02p2tqaffmgj8', '::1', 1729257101, 0x5f5f63695f6c6173745f726567656e65726174657c693a313732393235373130313b5f5f4143544956455f53455353494f4e5f444154417c733a3334343a2238333433303732323337363961633763623231366439303561326263643732313361336338656534336536613463313730646462396565373737633630366463396565363231373039323037306565666364613066646136613165346537323162613936663437323465336637656139316636346137666462386635326632387a357a6535436830556e687755464e45754149505733346237497459476a3756464256796e4e2b77394b51667a346b362b3346414c53757a517470703945777431476745694e465042732b6d36644f5339482b4d4165387571417570626e786556682b755244672f5942396a2b6164486372515257472b70756e4149515362326d497a624c64582b547042552b706d6364486832477a6579647545794b41395775755437424c364a2b7745776d706e4544394955312b786558324d73696a59795151443254685a535256307072724545656d457a45513d3d223b636172745f636f6e74656e74737c613a333a7b733a31303a22636172745f746f74616c223b643a39393030303b733a31313a22746f74616c5f6974656d73223b643a313b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b613a31303a7b733a323a226964223b733a313a2231223b733a333a22717479223b643a313b733a363a2273617475616e223b733a313a2231223b733a31313a2273617475616e5f74657874223b733a333a22506373223b733a31303a2273617475616e5f717479223b733a323a223130223b733a353a227072696365223b643a39393030303b733a343a226e616d65223b733a373a224163652d4f6e65223b733a31323a2270726f647563745f74797065223b733a313a2231223b733a353a22726f776964223b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b733a383a22737562746f74616c223b643a39393030303b7d7d),
('leq4umhgv6gfil0nadnif57vlb2dmoij', '::1', 1729257427, 0x5f5f63695f6c6173745f726567656e65726174657c693a313732393235373432373b5f5f4143544956455f53455353494f4e5f444154417c733a3334343a2238333433303732323337363961633763623231366439303561326263643732313361336338656534336536613463313730646462396565373737633630366463396565363231373039323037306565666364613066646136613165346537323162613936663437323465336637656139316636346137666462386635326632387a357a6535436830556e687755464e45754149505733346237497459476a3756464256796e4e2b77394b51667a346b362b3346414c53757a517470703945777431476745694e465042732b6d36644f5339482b4d4165387571417570626e786556682b755244672f5942396a2b6164486372515257472b70756e4149515362326d497a624c64582b547042552b706d6364486832477a6579647545794b41395775755437424c364a2b7745776d706e4544394955312b786558324d73696a59795151443254685a535256307072724545656d457a45513d3d223b636172745f636f6e74656e74737c613a333a7b733a31303a22636172745f746f74616c223b643a39393030303b733a31313a22746f74616c5f6974656d73223b643a313b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b613a31303a7b733a323a226964223b733a313a2231223b733a333a22717479223b643a313b733a363a2273617475616e223b733a313a2231223b733a31313a2273617475616e5f74657874223b733a333a22506373223b733a31303a2273617475616e5f717479223b733a323a223130223b733a353a227072696365223b643a39393030303b733a343a226e616d65223b733a373a224163652d4f6e65223b733a31323a2270726f647563745f74797065223b733a313a2231223b733a353a22726f776964223b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b733a383a22737562746f74616c223b643a39393030303b7d7d),
('1i4r4dfk6c16ik9gsthia685dlgt5c85', '::1', 1729257968, 0x5f5f63695f6c6173745f726567656e65726174657c693a313732393235373936383b5f5f4143544956455f53455353494f4e5f444154417c733a3334343a2238333433303732323337363961633763623231366439303561326263643732313361336338656534336536613463313730646462396565373737633630366463396565363231373039323037306565666364613066646136613165346537323162613936663437323465336637656139316636346137666462386635326632387a357a6535436830556e687755464e45754149505733346237497459476a3756464256796e4e2b77394b51667a346b362b3346414c53757a517470703945777431476745694e465042732b6d36644f5339482b4d4165387571417570626e786556682b755244672f5942396a2b6164486372515257472b70756e4149515362326d497a624c64582b547042552b706d6364486832477a6579647545794b41395775755437424c364a2b7745776d706e4544394955312b786558324d73696a59795151443254685a535256307072724545656d457a45513d3d223b636172745f636f6e74656e74737c613a333a7b733a31303a22636172745f746f74616c223b643a39393030303b733a31313a22746f74616c5f6974656d73223b643a313b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b613a31303a7b733a323a226964223b733a313a2231223b733a333a22717479223b643a313b733a363a2273617475616e223b733a313a2231223b733a31313a2273617475616e5f74657874223b733a333a22506373223b733a31303a2273617475616e5f717479223b733a323a223130223b733a353a227072696365223b643a39393030303b733a343a226e616d65223b733a373a224163652d4f6e65223b733a31323a2270726f647563745f74797065223b733a313a2231223b733a353a22726f776964223b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b733a383a22737562746f74616c223b643a39393030303b7d7d),
('vg17o8ib8v7t7t2476742vebqk87eb2l', '::1', 1729258881, 0x5f5f63695f6c6173745f726567656e65726174657c693a313732393235383838313b5f5f4143544956455f53455353494f4e5f444154417c733a3334343a2238333433303732323337363961633763623231366439303561326263643732313361336338656534336536613463313730646462396565373737633630366463396565363231373039323037306565666364613066646136613165346537323162613936663437323465336637656139316636346137666462386635326632387a357a6535436830556e687755464e45754149505733346237497459476a3756464256796e4e2b77394b51667a346b362b3346414c53757a517470703945777431476745694e465042732b6d36644f5339482b4d4165387571417570626e786556682b755244672f5942396a2b6164486372515257472b70756e4149515362326d497a624c64582b547042552b706d6364486832477a6579647545794b41395775755437424c364a2b7745776d706e4544394955312b786558324d73696a59795151443254685a535256307072724545656d457a45513d3d223b636172745f636f6e74656e74737c613a333a7b733a31303a22636172745f746f74616c223b643a39393030303b733a31313a22746f74616c5f6974656d73223b643a313b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b613a31303a7b733a323a226964223b733a313a2231223b733a333a22717479223b643a313b733a363a2273617475616e223b733a313a2231223b733a31313a2273617475616e5f74657874223b733a333a22506373223b733a31303a2273617475616e5f717479223b733a323a223130223b733a353a227072696365223b643a39393030303b733a343a226e616d65223b733a373a224163652d4f6e65223b733a31323a2270726f647563745f74797065223b733a313a2231223b733a353a22726f776964223b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b733a383a22737562746f74616c223b643a39393030303b7d7d),
('kctr453vngfui6q0gkd846apdsmiejtp', '::1', 1729258888, 0x5f5f63695f6c6173745f726567656e65726174657c693a313732393235383838313b5f5f4143544956455f53455353494f4e5f444154417c733a3334343a2238333433303732323337363961633763623231366439303561326263643732313361336338656534336536613463313730646462396565373737633630366463396565363231373039323037306565666364613066646136613165346537323162613936663437323465336637656139316636346137666462386635326632387a357a6535436830556e687755464e45754149505733346237497459476a3756464256796e4e2b77394b51667a346b362b3346414c53757a517470703945777431476745694e465042732b6d36644f5339482b4d4165387571417570626e786556682b755244672f5942396a2b6164486372515257472b70756e4149515362326d497a624c64582b547042552b706d6364486832477a6579647545794b41395775755437424c364a2b7745776d706e4544394955312b786558324d73696a59795151443254685a535256307072724545656d457a45513d3d223b636172745f636f6e74656e74737c613a333a7b733a31303a22636172745f746f74616c223b643a39393030303b733a31313a22746f74616c5f6974656d73223b643a313b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b613a31303a7b733a323a226964223b733a313a2231223b733a333a22717479223b643a313b733a363a2273617475616e223b733a313a2231223b733a31313a2273617475616e5f74657874223b733a333a22506373223b733a31303a2273617475616e5f717479223b733a323a223130223b733a353a227072696365223b643a39393030303b733a343a226e616d65223b733a373a224163652d4f6e65223b733a31323a2270726f647563745f74797065223b733a313a2231223b733a353a22726f776964223b733a33323a226334636134323338613062393233383230646363353039613666373538343962223b733a383a22737562746f74616c223b643a39393030303b7d7d);

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
  `kota_id` int(11) NOT NULL,
  `address` varchar(191) NOT NULL,
  `shop_name` varchar(100) NOT NULL,
  `shop_address` varchar(200) DEFAULT NULL,
  `max_credit` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `profile_picture` varchar(191) DEFAULT NULL,
  `salesman_id` int(11) NOT NULL,
  `kode_customer` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `nik`, `npwp`, `name`, `phone_number`, `kota_id`, `address`, `shop_name`, `shop_address`, `max_credit`, `level`, `profile_picture`, `salesman_id`, `kode_customer`) VALUES
(1, 56, 'nikcwahyu', '123123123123', 'cwahyu', '123123123123', 0, 'alamatcwahyu7', 'tokowahyu7', 'jatimulyo gang ampel no 167', 25000000, 3, NULL, 54, ''),
(2, 57, '', '', 'Kurohige', '123123123', 0, 'Impeldawn', '', NULL, 2000000, 1, NULL, 54, ''),
(3, 58, '3509200811830003', '21564465651', 'Erix Aqroby', '0982312312', 0, 'jember', 'Kios Sahroni', NULL, 10000000, 2, NULL, 59, ''),
(4, 61, '030233432423', '1343432', 'PELANGGAN', '4534542524', 0, 'BALUNG KIDUL', 'SENTOSA MAKMUR', 'PUGER WETAN', 10000000, 2, NULL, 54, ''),
(5, 66, '', '', 'test', '081236571723', 0, 'alamat', '', NULL, 0, 1, NULL, 40, ''),
(6, 67, '', '', 'Cust', '08643664576', 0, 'Balung', '', NULL, 0, 1, NULL, 40, ''),
(7, 68, '', '', 'Mope', '082333555777', 0, 'Jember', 'Jaya selalu', 'Jl sumbersari', 0, 1, 'IMG-20221029-WA0004.jpg', 62, ''),
(8, 69, '', '', 'Test2', '086263626', 0, 'Alamat nya', '', NULL, 0, 1, NULL, 40, ''),
(9, 70, '', '', 'Sayuri', '08276262727', 0, 'Balung', 'Anugrah', 'Jl puger', 0, 1, 'IMG-20221101-WA00021.jpg', 62, ''),
(10, 71, '', '', 'Iwan', '082334567893', 0, 'Jember', '', NULL, 0, 1, NULL, 40, ''),
(11, 74, '', '', 'faisal', '085633435633', 0, 'ambulu', '', NULL, 0, 1, NULL, 62, ''),
(12, 76, '350912345870003', '10229373', 'RIAN', '08576543456', 0, 'JL.KAHURIPAN 37 SUMBERSARI', 'UD. SEJAHTERA', 'JL SAHARA 14 KALIWINING', 25000000, 2, NULL, 54, ''),
(13, 78, '', '', 'akunbaru1', '081208108', 0, 'Jl.Semeru Baru', 'belum ada', 'belum ada ', 0, 1, NULL, 0, ''),
(14, 80, '123456', '123123123', 'Custumer Trial', '08123123546', 0, 'Jl.Cutomer Trial 1', 'Toko Trial', 'Jl. Trial Toko', 5000000, 2, NULL, 79, ''),
(15, 81, '123456123456', '132456132456', 'custemer trial', '01230123', 0, 'JL,Toko baru', 'Toko Trial Baru 123', 'JL,Toko baru', 17000000, 3, NULL, 79, ''),
(16, 85, '12345678945613654321', '12345678945613654321', 'maulana malik', '082264054747', 444, 'jalan jalan saja', 'trial umum ', 'trial umum', 0, 1, NULL, 59, '');

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

--
-- Dumping data untuk tabel `message`
--

INSERT INTO `message` (`id`, `salesman_id`, `customer_id`, `message`, `chat_from`, `created_at`, `status`, `reply_at`) VALUES
(1, 54, 56, 'Hhh', 2, '2022-10-21 12:54:02', 1, NULL),
(2, 54, 56, 'Cuy', 2, '2022-10-21 12:54:29', 1, NULL),
(3, 54, 56, 'jjk', 1, '2022-10-21 12:54:33', 1, NULL),
(4, 54, 56, 'Y', 2, '2022-10-21 12:55:21', 1, NULL),
(5, 54, 56, 'kak nego harga Qiuvita merah K 32 500 gram dengan harga 50000', 2, '2022-10-25 09:16:41', 1, NULL),
(6, 54, 56, 'ok', 1, '2022-10-25 09:17:03', 1, NULL),
(7, 54, 56, 'kak sudah di proses mohon segera melakukan pembayaran ', 1, '2022-10-25 09:19:47', 1, NULL),
(8, 54, 56, '#OGI251022156437', 1, '2022-10-25 09:20:07', 1, NULL),
(9, 54, 56, 'tes', 1, '2022-11-01 19:28:35', 1, NULL),
(10, 40, 68, 'Gfgh', 2, '2022-11-01 20:04:23', 2, NULL),
(11, 40, 68, '', 2, '2022-11-01 20:16:41', 2, NULL),
(12, 62, 68, 'Test', 2, '2022-11-03 10:21:30', 1, NULL),
(13, 62, 68, 'Test', 2, '2022-11-03 10:21:57', 1, NULL),
(14, 62, 68, 'Morning', 2, '2022-11-03 10:22:47', 1, NULL),
(15, 62, 68, 'Yes', 2, '2022-11-03 12:30:27', 1, NULL),
(16, 62, 68, 'Ttt', 2, '2022-11-03 12:42:04', 1, NULL),
(17, 62, 68, 'Ref', 2, '2022-11-03 12:45:17', 1, NULL),
(18, 54, 56, 'test', 2, '2022-11-14 21:08:46', 1, NULL),
(19, 54, 56, '', 2, '2022-11-14 21:08:47', 1, NULL),
(20, 54, 56, '', 2, '2022-11-14 21:08:50', 1, NULL),
(21, 54, 56, 'Cek', 2, '2022-11-24 23:03:30', 1, NULL),
(22, 54, 56, 'Tes', 2, '2022-11-24 23:03:53', 1, NULL),
(23, 54, 56, 'tes', 2, '2022-11-28 11:23:01', 1, NULL),
(24, 54, 56, 'a', 1, '2022-11-28 11:23:56', 1, NULL),
(25, 54, 56, 'a', 2, '2022-11-28 11:24:07', 1, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coupon_id` bigint(20) DEFAULT NULL,
  `order_number` varchar(16) NOT NULL,
  `invoice_number` text NOT NULL,
  `ttb_number` text DEFAULT NULL,
  `order_status` enum('1','2','3','4','5','6','7') DEFAULT '1',
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

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `coupon_id`, `order_number`, `invoice_number`, `ttb_number`, `order_status`, `order_date`, `total_price`, `total_items`, `payment_method`, `shipping_method`, `delivery_data`, `delivered_date`, `deliver_by`, `finish_date`, `due_date`, `shipping_cost`, `insurance`, `rating`, `rating_desc`) VALUES
(1, 56, NULL, 'JWG231022256398', '1234567', '11', '6', '2022-10-23 12:53:00', '305000.00', 2, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-10-23 00:00:00', '67589', NULL, '2022-11-23', '6000', '2000', NULL, NULL),
(2, 56, NULL, 'CXJ231022256328', '3456778', '11', '6', '2022-10-23 13:06:59', '310000.00', 2, 1, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-10-23 00:00:00', '6578', NULL, '2022-11-26', '6000', '2000', NULL, NULL),
(3, 56, NULL, 'OGI251022156437', 'fakturzahir1', '11', '6', '2022-10-25 09:13:55', '80000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-10-25 00:00:00', 'k10... no resin', NULL, '2022-11-25', '5000', '2000', NULL, NULL),
(4, 56, NULL, 'PNF251022156174', 'faktur123', NULL, '2', '2022-10-25 10:28:36', '25000.00', 1, 1, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-10-25 00:00:00', 'k12', NULL, '2022-10-24', '0', '0', NULL, NULL),
(5, 56, NULL, 'MZI251022156376', 'faktur93', NULL, '2', '2022-10-25 10:30:34', '290000.00', 1, 1, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-10-25 00:00:00', 'k12', NULL, '2022-10-23', '0', '0', NULL, NULL),
(6, 56, NULL, 'HRE251022156213', 'jjdwjdja', NULL, '2', '2022-10-25 10:32:46', '268500.00', 1, 1, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-10-25 00:00:00', 'k12', NULL, '2022-11-25', '0', '0', NULL, NULL),
(7, 56, NULL, 'XVH311022156861', '1212312', NULL, '2', '2022-10-31 08:16:09', '268500.00', 1, 1, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-11-02 00:00:00', 'P 5444 XC', '2022-11-02 14:35:20', NULL, NULL, NULL, 4, 'Sales melayani dengan baik'),
(8, 56, NULL, 'LTW311022156150', '', NULL, '1', '2022-10-31 08:26:10', '290000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-01', '0', '0', NULL, NULL),
(9, 56, NULL, 'UIC11122256743', '', NULL, '7', '2022-11-01 17:49:22', '12179500.00', 2, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-01', '0', '0', NULL, NULL),
(10, 56, 7, 'FYL111223567453', '', NULL, '1', '2022-11-01 19:08:35', '8251500.01', 3, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-01', '0', '0', NULL, NULL),
(11, 68, NULL, 'HJH11122568192', '36456345', NULL, '4', '2022-11-01 20:31:02', '689000.00', 5, 2, 1, '{\"customer\":{\"name\":\"Mope\",\"phone_number\":\"082333555777\",\"address\":\"Jember\",\"shop_name\":\"Jaya selalu\",\"shop_address\":\"Jl sumbersari\"},\"note\":\"\"}', '2022-11-04 00:00:00', 'P 4323 LH', NULL, '2022-12-01', '150000', '0', NULL, NULL),
(12, 70, NULL, 'AIR11122270296', 'nofakturs1', NULL, '2', '2022-11-01 20:37:47', '350000.00', 2, 2, 1, '{\"customer\":{\"name\":\"Sayuri\",\"phone_number\":\"08276262727\",\"address\":\"Balung\",\"shop_name\":\"Anugrah\",\"shop_address\":\"Jl puger\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-01', '20000', '0', NULL, NULL),
(13, 56, NULL, 'NGJ31122156470', '', NULL, '1', '2022-11-03 08:17:17', '88000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-03', '0', '0', NULL, NULL),
(14, 56, 8, 'JGP311222568817', 'nofakturs1', '12', '3', '2022-11-03 09:17:06', '425000.00', 2, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-11-04 00:00:00', 'P 4323 LH', '2022-11-03 09:34:47', '2022-12-03', '10000', '0', 5, 'Pengiriman cepat'),
(15, 56, NULL, 'VRA31122256923', '', NULL, '1', '2022-05-04 10:19:29', '445000.00', 2, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-03', '0', '0', NULL, NULL),
(16, 68, NULL, 'YSW31122268098', '12334', '11', '3', '2022-11-03 10:19:47', '41500.00', 2, 2, 1, '{\"customer\":{\"name\":\"Mope\",\"phone_number\":\"082333555777\",\"address\":\"Jember\",\"shop_name\":\"Jaya selalu\",\"shop_address\":\"Jl sumbersari\"},\"note\":\"\"}', '2022-11-03 00:00:00', 'P 4323 LH', '2022-11-03 12:24:12', '2022-12-03', '10000', '0', 1, 'Baf'),
(17, 56, NULL, 'GDP31122156529', '', NULL, '1', '2022-11-03 10:21:07', '310000.00', 1, 1, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-03', '0', '0', NULL, NULL),
(18, 56, NULL, 'SDL31122156087', '', NULL, '1', '2022-01-01 10:22:48', '290000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-03', '0', '0', NULL, NULL),
(19, 68, 8, 'WSB311222688835', '', NULL, '7', '2022-11-03 12:16:42', '28000.00', 2, 2, 1, '{\"customer\":{\"name\":\"Mope\",\"phone_number\":\"082333555777\",\"address\":\"Jember\",\"shop_name\":\"Jaya selalu\",\"shop_address\":\"Jl sumbersari\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-03', '0', '0', NULL, NULL),
(20, 68, 8, 'JFX311221688245', '', NULL, '7', '2022-11-03 12:18:27', '70000.00', 1, 2, 1, '{\"customer\":{\"name\":\"Mope\",\"phone_number\":\"082333555777\",\"address\":\"Jember\",\"shop_name\":\"Jaya selalu\",\"shop_address\":\"Jl sumbersari\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-03', '0', '0', NULL, NULL),
(21, 68, 8, 'TWA311221688890', '', NULL, '1', '2022-11-03 12:19:14', '70000.00', 1, 2, 1, '{\"customer\":{\"name\":\"Mope\",\"phone_number\":\"082333555777\",\"address\":\"Jember\",\"shop_name\":\"Jaya selalu\",\"shop_address\":\"Jl sumbersari\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-03', '0', '0', NULL, NULL),
(22, 56, 8, 'IYB311221568752', '', NULL, '1', '2022-11-03 12:20:16', '68000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-03', '0', '0', NULL, NULL),
(23, 68, NULL, 'RMC31122168561', '', NULL, '1', '2022-11-03 12:21:03', '90000.00', 1, 2, 1, '{\"customer\":{\"name\":\"Mope\",\"phone_number\":\"082333555777\",\"address\":\"Jember\",\"shop_name\":\"Jaya selalu\",\"shop_address\":\"Jl sumbersari\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-03', '0', '0', NULL, NULL),
(24, 68, NULL, 'TXC31122168817', '', NULL, '1', '2022-11-03 12:47:30', '90000.00', 1, 2, 1, '{\"customer\":{\"name\":\"Mope\",\"phone_number\":\"082333555777\",\"address\":\"Jember\",\"shop_name\":\"Jaya selalu\",\"shop_address\":\"Jl sumbersari\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-03', '0', '0', NULL, NULL),
(25, 56, NULL, 'UHK61122256715', '', NULL, '1', '2022-11-06 01:22:35', '510000.00', 2, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-06', '0', '0', NULL, NULL),
(26, 70, NULL, 'DJX71122270495', '', NULL, '1', '2022-11-07 12:17:12', '139000.00', 2, 2, 1, '{\"customer\":{\"name\":\"Sayuri\",\"phone_number\":\"08276262727\",\"address\":\"Balung\",\"shop_name\":\"Anugrah\",\"shop_address\":\"Jl puger\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-07', '0', '0', NULL, NULL),
(27, 56, NULL, 'PYE141122156514', '', NULL, '1', '2022-11-14 21:09:25', '88000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-14', '0', '0', NULL, NULL),
(28, 56, 10, 'WNJ1511221561095', '', NULL, '1', '2022-11-15 12:47:20', '43000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-15', '0', '0', NULL, NULL),
(29, 56, NULL, 'ETH151122156521', '', NULL, '1', '2022-11-15 13:25:29', '19500.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-15', '0', '0', NULL, NULL),
(30, 56, NULL, 'ILQ151122156294', '', NULL, '1', '2022-11-15 13:29:30', '440000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-15', '0', '0', NULL, NULL),
(31, 56, NULL, 'THV151122156381', '', NULL, '1', '2022-11-15 13:31:26', '101500.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-15', '0', '0', NULL, NULL),
(32, 56, NULL, 'MPV151122156948', '', NULL, '1', '2022-11-15 13:32:04', '147000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-15', '0', '0', NULL, NULL),
(33, 56, NULL, 'ANP151122256320', '', NULL, '1', '2022-11-15 14:04:30', '437000.00', 2, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-15', '0', '0', NULL, NULL),
(34, 56, NULL, 'GYH151122156956', '', NULL, '1', '2022-11-15 14:08:05', '88000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-15', '0', '0', NULL, NULL),
(35, 56, NULL, 'VCJ151122156976', '', NULL, '1', '2022-11-15 14:09:16', '440000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-15', '0', '0', NULL, NULL),
(36, 56, NULL, 'MCU151122156530', '', NULL, '1', '2022-11-15 14:10:16', '147000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-15', '0', '0', NULL, NULL),
(37, 56, NULL, 'YJP151122156751', '', NULL, '1', '2022-11-15 14:11:37', '147000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-15', '0', '0', NULL, NULL),
(38, 56, NULL, 'DUM151122256745', 'faktur123', NULL, '3', '2022-11-15 14:13:51', '367000.00', 2, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-11-24 00:00:00', 'k11', '2022-11-15 14:21:08', '2022-12-23', '15000', '0', 5, 'Sukses'),
(39, 68, NULL, 'TFV241122268321', '', NULL, '1', '2022-11-24 22:26:43', '110500.00', 2, 2, 1, '{\"customer\":{\"name\":\"Mope\",\"phone_number\":\"082333555777\",\"address\":\"Jember\",\"shop_name\":\"Jaya selalu\",\"shop_address\":\"Jl sumbersari\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(40, 56, NULL, 'ZQR241122256897', '', NULL, '1', '2022-11-24 22:27:20', '375000.00', 2, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(41, 68, NULL, 'SBL241122268942', '', NULL, '1', '2022-11-24 22:28:24', '29500.00', 2, 2, 1, '{\"customer\":{\"name\":\"Mope\",\"phone_number\":\"082333555777\",\"address\":\"Jember\",\"shop_name\":\"Jaya selalu\",\"shop_address\":\"Jl sumbersari\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(42, 56, NULL, 'NOS241122156715', '', NULL, '1', '2022-11-24 22:37:44', '88000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(43, 56, NULL, 'NRG241122156835', '', NULL, '1', '2022-11-24 22:38:14', '176000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(44, 56, NULL, 'YSJ241122156912', '', NULL, '1', '2022-11-24 22:50:21', '220000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(45, 56, NULL, 'BYY241122156530', '', NULL, '1', '2022-11-24 22:51:57', '155000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(46, 56, NULL, 'GHV241122156943', '', NULL, '1', '2022-11-24 22:54:03', '155000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(47, 56, NULL, 'FPV241122156376', '', NULL, '1', '2022-11-24 22:57:42', '88000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(48, 56, NULL, 'NEZ241122256095', '', NULL, '1', '2022-11-24 23:01:06', '300000.00', 2, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(49, 56, NULL, 'LOB241122156947', '', NULL, '1', '2022-11-24 23:02:00', '290000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(50, 56, NULL, 'BKJ241122156185', '', NULL, '1', '2022-11-24 23:03:17', '220000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(51, 56, NULL, 'IPN241122156028', 'fktur123', NULL, '2', '2022-11-24 23:04:44', '88000.00', 1, 2, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', NULL, NULL, NULL, '2022-12-24', '0', '0', NULL, NULL),
(52, 78, NULL, 'TWK281122278764', 'FAKTUR1', NULL, '6', '2022-11-28 10:49:57', '99000.00', 2, 2, 1, '{\"customer\":{\"name\":\"akunbaru1\",\"phone_number\":\"081208108\",\"address\":\"Jl.Semeru Baru\",\"shop_name\":\"belum ada\",\"shop_address\":\"belum ada \"},\"note\":\"\"}', '2022-11-29 00:00:00', 'K12', '2022-11-28 10:56:10', '2022-12-28', '5000', '0', 5, 'bagus'),
(53, 56, NULL, 'HXV281122456413', 'FK0002', NULL, '2', '2022-11-28 11:03:51', '762000.00', 4, 1, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-11-28 00:00:00', 'K123', '2022-11-28 11:07:16', '2022-12-28', '10000', '5000', 4, ''),
(54, 56, NULL, 'AAW281122156958', 'FK12333', NULL, '2', '2022-11-28 11:09:02', '4640000.00', 1, 1, 1, '{\"customer\":{\"name\":\"cwahyu\",\"phone_number\":\"123123123123\",\"address\":\"alamatcwahyu7\",\"shop_name\":\"tokowahyu7\",\"shop_address\":\"jatimulyo gang ampel no 167\"},\"note\":\"\"}', '2022-11-29 00:00:00', 'K15', '2022-11-28 11:10:19', '2022-12-28', '10000', '0', 4, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `order_qty` int(10) NOT NULL,
  `order_price` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `payment_price` decimal(8,2) DEFAULT NULL,
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
(1, 1, '313000.00', '2022-10-23 12:57:52', 'Screenshot_2022-10-22-19-24-16-079_com_shopee_id1.jpg', '2', NULL, '{\"transfer_to\":\"bank-mandiri\",\"source\":{\"bank\":\"Mandiri\",\"name\":\"Wahyu\",\"number\":\"237890\"}}'),
(2, 2, '318000.00', '2022-10-23 13:13:28', 'Screenshot_2022-10-22-19-10-02-227_com_shopee_id.jpg', '2', NULL, '{\"transfer_to\":\"bank-bca\",\"source\":{\"bank\":\"BCA\",\"name\":\"Wahyu\",\"number\":\"3776289\"}}'),
(3, 3, '87000.00', '2022-10-25 09:22:42', '0c4ec7ae2a47bd3163951e3161eafc95.png', '2', NULL, '{\"transfer_to\":\"bank-bca\",\"source\":{\"bank\":\"mandiri\",\"name\":\"wahyu\",\"number\":\"1231321\"}}'),
(4, 11, '800000.00', '2022-11-01 20:46:35', '1667268804213.png', '2', NULL, '{\"transfer_to\":\"bank-bca\",\"source\":{\"bank\":\"Mandiri\",\"name\":\"Gusti\",\"number\":\"18262719197\"}}'),
(5, 14, '435000.00', '2022-11-03 09:28:35', 'gpn.png', '2', NULL, '{\"transfer_to\":\"bank-bca\",\"source\":{\"bank\":\"bca\",\"name\":\"adi\",\"number\":\"123123\"}}'),
(6, 16, '2000.00', '2022-11-03 12:22:34', 'IMG-20221031-WA0002.jpg', NULL, NULL, '{\"transfer_to\":\"bank-bca\",\"source\":{\"bank\":\"Gusti\",\"name\":\"Hdyejenj\",\"number\":\"1637299377\"}}'),
(7, 38, '382000.00', '2022-11-15 14:18:59', 'C2E20722-FDB6-4A08-A309-D4189851AB35.png', NULL, NULL, '{\"transfer_to\":\"bank-bca\",\"source\":{\"bank\":\"Wahyu\",\"name\":\"Ahyu\",\"number\":\"Wahyu\"}}'),
(8, 52, '104000.00', '2022-11-28 10:53:02', 'beasiswa.jpg', '2', NULL, '{\"transfer_to\":\"bank-bca\",\"source\":{\"bank\":\"adasd\",\"name\":\"asdasd\",\"number\":\"asdasd\"}}');

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

--
-- Dumping data untuk tabel `piutang`
--

INSERT INTO `piutang` (`id`, `no_faktur`, `name`, `address`, `payment_price`, `pay`, `payment_date`, `payment_status`, `confirm_date`) VALUES
(1, 'KS03133332', 'ALDI', 'PERUM BUMI MANGLI', 5300000, 4300000, '2022-04-05', 1, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `description`, `picture_name`, `price`, `price_2`, `price_3`, `stock`, `current_discount`, `product_unit`, `product_unit_1`, `product_unit_2`, `product_unit_value`, `product_type`, `product_unit_weight`, `is_available`, `add_date`, `user_level`) VALUES
(1, 2, 'SB750372', 'Ace-One', 'NULL', 'ace_one_99000.jpg', 99000, 98000, 97000, 50, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(2, 2, 'BS350420', 'Akalis 500 SC', 'NULL', 'akalis_500sc_268500.jpg', 0, 268500, 228500, 10, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(4, 2, 'TS120790', 'Sinergy 300 EC', 'NULL', 'sinergy300ec_155000.jpg', 0, 120000, 155000, 20, 0, 'Botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(5, 2, 'WS120811', 'Cornbelt 336 SC', 'NULL', 'cornbelt_336_sc_290000.jpg', 0, 0, 290000, 20, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(8, 2, 'PS220885', 'Baltiko', 'NULL', 'baltiko_49000.jpg', 20000, 19000, 17500, 20, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(9, 2, 'AB450163', 'Topida 25 WP', 'NULL', 'topida_25_wp_25000.jpg', 25000, 0, 0, 50, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(10, 2, 'BMS120283', 'Paskal 50 WP', 'NULL', 'paskal_50wp_23000.jpg', 23000, 0, 0, 20, 0, 'Pcs', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(11, 2, 'URS13301', 'Biosoft 500ml', 'NULL', 'Biosoft_-_500ml_101500.jpg', 0, 0, 101500, 300, 0, 'Botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(12, 2, 'BPS15347', 'Nostro 440 EC - 500ml (FUNGISIDA)', 'NULL', 'Nostro_440_EC_-_500ml_(FUNGISIDA)_100000.jpg', 0, 110000, 100000, 51, 0, 'Botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(13, 2, 'KPS223370', 'Hexacar 100 SC - 250ml (FUNGISIDA)', 'NULL', 'Hexacar_100_SC_-_250ml_(FUNGISIDA)_50000.jpg', 0, 55000, 50000, 23, 0, 'Botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(14, 2, 'CMS410424', 'BM Ematez 19 EC - 100ml (INSEKTISIDA)', 'NULL', 'BM_Ematez_19_EC_-_100ml_(INSEKTISIDA)_45000.jpg', 0, 0, 45000, 10, 0, 'Botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(15, 2, 'B5MP8100547', 'Blast 500 ML', 'NULL', '1820703_e3c79fa6-7398-41d6-a8ce-ee27b694d3e9.jpg', 0, 0, 80000, 100, 0, 'botol', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(18, 1, 'QMK35GP1805', 'Qiuvita merah K 32 500 gram', 'NULL', 'Qiuvita_Merah.jpg', 16500, 0, 0, 50, 0, 'pack', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(19, 1, 'FF111000791', 'FUNGISIDA', 'NULL', 'gambar_otak.png', 13000, 11000, 10000, 50, 0, 'BOX', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(20, 9, 'OII112500872', 'OBAT ISEKTIDA', 'NULL', 'abstrak.jpeg', 15000, 12500, 11000, 99, 0, 'BOX', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0),
(0, 15, 'AAP12876', 'asdasd', '', 'NULL', 1, 2, 3, 1000, 0, 'NULL', 'Pcs', 'Box', '10', '1', 1000, 1, '0000-00-00 00:00:00', 0);

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

--
-- Dumping data untuk tabel `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `order_id`, `title`, `review_text`, `review_date`, `status`) VALUES
(2, 7, 6, 'Sangat puas', 'Pengiriman cepat dan sayur segar', '2020-03-30 08:31:31', 1),
(3, 7, 5, 'Buah segar', 'Buah segar dan kualitasnya sangat bagus', '2020-03-30 08:33:10', 1);

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
(57, NULL, 'qwert@gmail.com', NULL, '$2y$10$rd4I82jFfR1aqfLarLysVuORCEFdjBUj47sJkudn6cR54KcCnAg/y', NULL, 'customer', '2022-09-22 19:38:29', 1),
(58, NULL, 'aqrobye@gmail.com', NULL, '$2y$10$7C7q54ZUl241VcEKrkK/HOhkuQt.nWhmKBasc2HIUoyFC0fV5D64G', NULL, 'customer', '2022-09-23 04:27:56', 1),
(59, 'Admin Penjualan', 'penjualan@gmail.com', NULL, '$2y$10$oXf2VHVBbngML9Pl4WevyORyeMQNKzQ.zLBfk/NfzU.mwpSh6MWZ6', NULL, 'salesman', '2022-09-23 04:29:41', 1),
(61, NULL, 'pelanggan@gmail.com', NULL, '$2y$10$o.hUZ.KztVzCfozdyNR1YeVMR7uF3qIJbxU2zka69YAMWnG9JGhJ6', NULL, 'customer', '2022-10-10 12:38:19', 1),
(62, 'Admin Online', 'adminonline@gmail.com', NULL, '$2y$10$Piw9jb8Sd.SVUh2SL4PRJuFD5aJ3bSDOHxy78I/dAPUeJ9GBtR6QW', NULL, 'adminonline', '2022-10-26 20:29:19', 1),
(63, 'Keuangan', 'keuangan@gmail.com', NULL, '$2y$10$YhoKD4bwf8eW9fSKKtmluujAswZtex5M/xS07wIw4W/t2WdglqMtW', NULL, 'keuangan', '2022-10-26 22:19:19', 1),
(64, NULL, 'mope@gmail.com', NULL, '$2y$10$P5pGf88j.SsFbeQ.nQ9ti.JbiPmjJ5AsIkef3AEPp6v5eYGS7eD2C', NULL, 'customer', '2022-11-01 18:02:05', 0),
(65, NULL, 'supri@gmail.com', NULL, '$2y$10$0ymggK6QZQz4x2lmyVDS5.GV7KP00cYltrW5zn4Ov98wUC9mzl6Jm', NULL, 'customer', '2022-11-01 18:06:31', 0),
(66, NULL, 'test@gmail.com', NULL, '$2y$10$yVuLQt7ILIsU.6uxpYH1DecAB/tA5q6DhcOhlHTw4MTvXQCXyucx.', NULL, 'customer', '2022-11-01 19:31:00', 1),
(67, NULL, 'customer@gmail.com', NULL, '$2y$10$6Vwyy0LXRcLxA8UZdeDRMuQiuG3XYOwRnBpshwREc90vkEcMebYTm', NULL, 'customer', '2022-11-01 19:36:22', 0),
(68, NULL, 'gusti.mope819@gmail.com', NULL, '$2y$10$iBC6YDMnusxQSm5RO2MtE.hfibYR3bwpwxhfDbuOxyQ2o7NBW3Nwq', NULL, 'customer', '2022-11-01 19:36:53', 1),
(69, NULL, 'emailnya@gmail.com', NULL, '$2y$10$nG1//i4cJnT9vwVKC94/6OHCypEiBkG6Byg6hoHTnZKUtnfERBcoG', NULL, 'customer', '2022-11-01 19:38:13', 0),
(70, NULL, 'sayuri@gmail.com', NULL, '$2y$10$7PerRcfHb5QJD4xNO9KwqOMC2V2RUp/rytpe3tBgplHbTWZBslYe6', NULL, 'customer', '2022-11-01 19:39:10', 1),
(71, NULL, 'iwan@gmail.com', NULL, '$2y$10$NLey.oZELOsmX.8K5uEBzuydLCMhfmONouP1AvhXuyfOF4bLWZwWe', NULL, 'customer', '2022-11-01 19:39:51', 0),
(72, 'Kadep', 'kadep@gmail.com', NULL, '$2y$10$Brm3RNWFKvZ1e0ej1vBz9.QbFMW21q0l/iDSt5aDOoGj9zlLFuxh6', NULL, 'kadep', '2022-11-02 11:56:19', 1),
(73, 'Kadep 1', 'kadep1@gmail.com', NULL, '$2y$10$8OhbMGn21ZMLHqj1ilLjKeQzMBsvciI7qNXFftJHtf4e8RSwIk99.', NULL, 'kadep', '2022-11-03 09:37:40', 1),
(74, NULL, 'faisal@gmail.com', NULL, '$2y$10$y9dKpR6lsu6FbyX5/sJY9.SB2Al9a7Pi9LpnN5jq18K.uxRskevi.', NULL, 'customer', '2022-11-03 10:02:40', 0),
(75, NULL, 'arif@gmail.com', NULL, '$2y$10$sDRmrH6uGpYWnoh4TYYabe33M11zkAyiPPTdmx0OMM.q81PcZkSSa', NULL, 'customer', '2022-11-09 20:21:01', 1),
(76, NULL, 'rian@gmail.com', NULL, '$2y$10$6nTIg8LQ/fr4zyrz/lA1NOrgXa1jpnZGoVEczfwvpNyfsj3Jg1nqq', NULL, 'customer', '2022-11-10 20:15:52', 1),
(77, 'ALIF', 'alif@gmail.com', NULL, '$2y$10$ADfDKeO8ZrPLWXFFSx3dgevM1woD4Uv9jADCDpwXygW29//jLS1NS', NULL, 'keuangan', '2022-11-10 20:17:18', 1),
(78, NULL, 'akunbaru1@gmail.com', NULL, '$2y$10$mB.ICCiM3nnYAYLuoCxdj.27KJ4ShiEr.qsxmCwdDYr6F5Vj5vH0i', NULL, 'customer', '2022-11-28 10:42:17', 1),
(79, 'Sales Trial', 'sales@gmail.com', NULL, '$2y$10$IRZLM8vlw9yvDnBO8R96We1pswHQCnTRATn/i1yFSBDY7wP82ipVu', NULL, 'salesman', '2022-11-30 13:18:01', 1),
(80, NULL, 'custrial@gmail.com', NULL, '$2y$10$o2rPkdmMxXRzjEmhddDun.zGKicsVNmVEl9cjX3u1kI57B47v8ZBS', NULL, 'customer', '2022-11-30 13:20:23', 1),
(81, NULL, 'customer2@gmail.com', NULL, '$2y$10$xXQXksNLB8SERgEnjDHnQur9gXHaijVS4usUdgKPW2MkNHr4DwxRW', NULL, 'customer', '2022-11-30 13:36:45', 1),
(85, NULL, 'cumum@gmail.com', NULL, '$2y$10$JdC3psY/Y1wrRdRedKykP.Y1FYMYcoQ66ZCOw2fYl8RhI2NTfm2hy', NULL, 'customer', '2024-10-07 21:46:12', 1);

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

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_products`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_products` (
`id` bigint(20)
,`category_id` int(10)
,`sku` varchar(32)
,`name` varchar(191)
,`description` varchar(191)
,`picture_name` varchar(191)
,`product_unit_value` text
,`product_unit_1` varchar(25)
,`product_unit_2` varchar(25)
,`product_type` varchar(25)
,`product_unit_weight` int(25)
,`promo` int(1)
,`price` int(11)
,`price_2` int(11)
,`price_3` int(11)
,`promo_price` decimal(13,2)
,`promo_price_2` decimal(13,2)
,`promo_price_3` decimal(13,2)
,`discount` decimal(10,0)
,`discount_2` decimal(10,0)
,`discount_3` decimal(10,0)
,`stock` int(10)
,`product_unit` varchar(25)
,`is_available` tinyint(1)
,`add_date` datetime
,`level_product` varchar(5)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_tagihan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_tagihan` (
`user_id` bigint(20) unsigned
,`tagihan` double
,`max_credit` int(11)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_products`
--
DROP TABLE IF EXISTS `v_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_products`  AS SELECT `a`.`id` AS `id`, `a`.`category_id` AS `category_id`, `a`.`sku` AS `sku`, `a`.`name` AS `name`, `a`.`description` AS `description`, `a`.`picture_name` AS `picture_name`, `a`.`product_unit_value` AS `product_unit_value`, `a`.`product_unit_1` AS `product_unit_1`, `a`.`product_unit_2` AS `product_unit_2`, `a`.`product_type` AS `product_type`, `a`.`product_unit_weight` AS `product_unit_weight`, if(`b`.`credit` is not null,1,0) AS `promo`, `a`.`price` AS `price`, `a`.`price_2` AS `price_2`, `a`.`price_3` AS `price_3`, if(`b`.`credit` is not null,`a`.`price` - `b`.`credit`,`a`.`price`) AS `promo_price`, if(`b`.`credit` is not null,`a`.`price_2` - `b`.`credit`,`a`.`price_2`) AS `promo_price_2`, if(`b`.`credit` is not null,`a`.`price_3` - `b`.`credit`,`a`.`price_3`) AS `promo_price_3`, if(`b`.`credit` is not null,round(`b`.`credit` / `a`.`price` * 100,0),0) AS `discount`, if(`b`.`credit` is not null,round(`b`.`credit` / `a`.`price_2` * 100,0),0) AS `discount_2`, if(`b`.`credit` is not null,round(`b`.`credit` / `a`.`price_3` * 100,0),0) AS `discount_3`, `a`.`stock` AS `stock`, `a`.`product_unit_1` AS `product_unit`, `a`.`is_available` AS `is_available`, `a`.`add_date` AS `add_date`, CASE WHEN `a`.`price` <> 0 AND `a`.`price_2` = 0 AND `a`.`price_3` = 0 THEN '1' WHEN `a`.`price` = 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` = 0 THEN '2' WHEN `a`.`price` = 0 AND `a`.`price_2` = 0 AND `a`.`price_3` <> 0 THEN '3' WHEN `a`.`price` <> 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` = 0 THEN '1,2' WHEN `a`.`price` <> 0 AND `a`.`price_2` = 0 AND `a`.`price_3` <> 0 THEN '1,3' WHEN `a`.`price` = 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` <> 0 THEN '2,3' WHEN `a`.`price` <> 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` <> 0 THEN '1,2,3' END AS `level_product` FROM (`products` `a` left join `promo` `b` on(`b`.`product_id` = `a`.`id` and cast(`b`.`start_date` as date) <= curdate() and cast(`b`.`expired_date` as date) >= curdate()))  ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_tagihan`
--
DROP TABLE IF EXISTS `v_tagihan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_tagihan`  AS SELECT `a`.`user_id` AS `user_id`, sum(`a`.`tagihan`) AS `tagihan`, `a`.`max_credit` AS `max_credit` FROM (select `a`.`user_id` AS `user_id`,ifnull(`b`.`total_price`,0) + ifnull(`b`.`shipping_cost`,0) + ifnull(`b`.`insurance`,0) AS `tagihan`,`a`.`max_credit` AS `max_credit` from (`customers` `a` left join `orders` `b` on(`a`.`user_id` = `b`.`user_id` and `b`.`payment_method` = 1 and `b`.`order_status` < 6))) AS `a` GROUP BY `a`.`user_id``user_id`  ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `banner_product`
--
ALTER TABLE `banner_product`
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
