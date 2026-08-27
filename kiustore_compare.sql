-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 27 Agu 2026 pada 06.50
-- Versi server: 11.8.8-MariaDB-log
-- Versi PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u676129830_kiustoreonline`
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
  `alamat_kirim` text NOT NULL,
  `max_credit` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `profile_picture` varchar(191) DEFAULT NULL,
  `salesman_id` int(11) NOT NULL,
  `kode_customer` varchar(10) NOT NULL,
  `va_code` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Struktur dari tabel `mobile_api_tokens`
--

CREATE TABLE `mobile_api_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token_hash` char(64) NOT NULL,
  `device_name` varchar(100) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `last_used_at` datetime DEFAULT NULL,
  `revoked_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mobile_cart_items`
--

CREATE TABLE `mobile_cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `unit_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 = unit pertama, 2 = unit kedua',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mobile_shipping_quotes`
--

CREATE TABLE `mobile_shipping_quotes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `origin_id` int(10) UNSIGNED NOT NULL,
  `destination_id` int(10) UNSIGNED NOT NULL,
  `weight` int(10) UNSIGNED NOT NULL,
  `courier` varchar(50) NOT NULL,
  `options_json` mediumtext NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
  `order_status` enum('1','2','3','4','5','6','7','8','9','10','11','12') DEFAULT '1',
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
  `nama_ekspedisi` text NOT NULL,
  `estimasi_kirim` text NOT NULL,
  `shipping_cost` text DEFAULT '0',
  `insurance` text DEFAULT '0',
  `rating` int(11) DEFAULT NULL,
  `rating_desc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `price_3` int(11) NOT NULL,
  `price_2` int(11) NOT NULL,
  `stock` int(10) NOT NULL,
  `current_discount` double NOT NULL,
  `product_unit` varchar(32) DEFAULT NULL,
  `product_unit_1` varchar(25) NOT NULL,
  `product_unit_2` varchar(25) NOT NULL,
  `product_unit_value` text NOT NULL,
  `product_type` varchar(25) NOT NULL,
  `product_unit_weight` double NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `add_date` datetime DEFAULT NULL,
  `user_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_category`
--

CREATE TABLE `product_category` (
  `id` int(10) NOT NULL,
  `name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `no_resi` text NOT NULL,
  `resi_sts` int(2) NOT NULL,
  `status` int(2) NOT NULL,
  `create_at` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
,`description` text
,`picture_name` varchar(191)
,`product_unit_value` text
,`product_unit_1` varchar(25)
,`product_unit_2` varchar(25)
,`product_type` varchar(25)
,`product_unit_weight` double
,`promo` int(2)
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
-- Indeks untuk tabel `mobile_api_tokens`
--
ALTER TABLE `mobile_api_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_mobile_api_token_hash` (`token_hash`),
  ADD KEY `idx_mobile_api_token_user` (`user_id`),
  ADD KEY `idx_mobile_api_token_expiry` (`expires_at`);

--
-- Indeks untuk tabel `mobile_cart_items`
--
ALTER TABLE `mobile_cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_mobile_cart_product_unit` (`user_id`,`product_id`,`unit_type`),
  ADD KEY `idx_mobile_cart_product` (`product_id`);

--
-- Indeks untuk tabel `mobile_shipping_quotes`
--
ALTER TABLE `mobile_shipping_quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mobile_quote_user` (`user_id`),
  ADD KEY `idx_mobile_quote_expiry` (`expires_at`);

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
  ADD KEY `idx_products_category_id` (`category_id`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `briva_api`
--
ALTER TABLE `briva_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `customer_location`
--
ALTER TABLE `customer_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `generate_kdchart`
--
ALTER TABLE `generate_kdchart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `message`
--
ALTER TABLE `message`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mobile_api_tokens`
--
ALTER TABLE `mobile_api_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mobile_cart_items`
--
ALTER TABLE `mobile_cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mobile_shipping_quotes`
--
ALTER TABLE `mobile_shipping_quotes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders_bk`
--
ALTER TABLE `orders_bk`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `order_items_bk`
--
ALTER TABLE `order_items_bk`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `piutang`
--
ALTER TABLE `piutang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `product_category`
--
ALTER TABLE `product_category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `promo`
--
ALTER TABLE `promo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbtestongkir`
--
ALTER TABLE `tbtestongkir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tmp_cart`
--
ALTER TABLE `tmp_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_products`
--
DROP TABLE IF EXISTS `v_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u676129830_kiustoreonline`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_products`  AS SELECT `a`.`id` AS `id`, `a`.`category_id` AS `category_id`, `a`.`sku` AS `sku`, `a`.`name` AS `name`, `a`.`description` AS `description`, `a`.`picture_name` AS `picture_name`, `a`.`product_unit_value` AS `product_unit_value`, `a`.`product_unit_1` AS `product_unit_1`, `a`.`product_unit_2` AS `product_unit_2`, `a`.`product_type` AS `product_type`, `a`.`product_unit_weight` AS `product_unit_weight`, if(`b`.`credit` is not null,1,0) AS `promo`, `a`.`price` AS `price`, `a`.`price_2` AS `price_2`, `a`.`price_3` AS `price_3`, if(`b`.`credit` is not null,`a`.`price` - `b`.`credit`,`a`.`price`) AS `promo_price`, if(`b`.`credit` is not null,`a`.`price_2` - `b`.`credit`,`a`.`price_2`) AS `promo_price_2`, if(`b`.`credit` is not null,`a`.`price_3` - `b`.`credit`,`a`.`price_3`) AS `promo_price_3`, if(`b`.`credit` is not null,round(`b`.`credit` / `a`.`price` * 100,0),0) AS `discount`, if(`b`.`credit` is not null,round(`b`.`credit` / `a`.`price_2` * 100,0),0) AS `discount_2`, if(`b`.`credit` is not null,round(`b`.`credit` / `a`.`price_3` * 100,0),0) AS `discount_3`, `a`.`stock` AS `stock`, `a`.`product_unit_1` AS `product_unit`, `a`.`is_available` AS `is_available`, `a`.`add_date` AS `add_date`, CASE WHEN `a`.`price` <> 0 AND `a`.`price_2` = 0 AND `a`.`price_3` = 0 THEN '1' WHEN `a`.`price` = 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` = 0 THEN '2' WHEN `a`.`price` = 0 AND `a`.`price_2` = 0 AND `a`.`price_3` <> 0 THEN '3' WHEN `a`.`price` <> 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` = 0 THEN '1,2' WHEN `a`.`price` <> 0 AND `a`.`price_2` = 0 AND `a`.`price_3` <> 0 THEN '1,3' WHEN `a`.`price` = 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` <> 0 THEN '2,3' WHEN `a`.`price` <> 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` <> 0 THEN '1,2,3' END AS `level_product` FROM (`products` `a` left join `promo` `b` on(`b`.`product_id` = `a`.`id` and cast(`b`.`start_date` as date) <= curdate() and cast(`b`.`expired_date` as date) >= curdate())) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_tagihan`
--
DROP TABLE IF EXISTS `v_tagihan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u676129830_kiustoreonline`@`127.0.0.1` SQL SECURITY DEFINER VIEW `v_tagihan`  AS SELECT `a`.`user_id` AS `user_id`, sum(`a`.`tagihan`) AS `tagihan`, `a`.`max_credit` AS `max_credit` FROM (select `a`.`user_id` AS `user_id`,ifnull(`b`.`total_price`,0) + ifnull(`b`.`shipping_cost`,0) + ifnull(`b`.`insurance`,0) AS `tagihan`,`a`.`max_credit` AS `max_credit` from (`customers` `a` left join `orders` `b` on(`a`.`user_id` = `b`.`user_id` and `b`.`payment_method` = 1 and `b`.`order_status` < 6))) AS `a` GROUP BY `a`.`user_id` ;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
