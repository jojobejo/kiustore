CREATE TABLE IF NOT EXISTS `zahir_stock_product_aliases` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `zahir_name` VARCHAR(191) NOT NULL,
  `normalized_zahir_name` VARCHAR(191) NOT NULL,
  `product_id` BIGINT(20) DEFAULT NULL,
  `product_name` VARCHAR(191) NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `notes` VARCHAR(255) DEFAULT NULL,
  `created_by` BIGINT(20) DEFAULT NULL,
  `approved_by` BIGINT(20) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_zahir_stock_product_aliases_normalized` (`normalized_zahir_name`),
  KEY `idx_zahir_stock_product_aliases_product` (`product_id`),
  KEY `idx_zahir_stock_product_aliases_active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET @now = NOW();

ALTER TABLE `zahir_stock_product_aliases` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

INSERT INTO `zahir_stock_product_aliases`
  (`zahir_name`, `normalized_zahir_name`, `product_name`, `notes`, `created_at`)
VALUES
  ('Alfatox 50 EC 20 X 400 ml', 'alfatox 50 ec 20 x 400 ml', 'Alfatox 50 EC 20 X 400 ml (AGM)', 'Imported from mapping_zahir_barang.xlsx row 3', @now),
  ('Alfatox 50 EC 40 X 200 ml', 'alfatox 50 ec 40 x 200 ml', 'Alfatox 50 EC 50 X 200 ml (AGM)', 'Imported from mapping_zahir_barang.xlsx row 4', @now),
  ('Alfatox 50 EC 50 X 80 ml', 'alfatox 50 ec 50 x 80 ml', 'Alfatox 50 EC 50 X 80 ml (AGM)', 'Imported from mapping_zahir_barang.xlsx row 5', @now),
  ('Aneto 50 EC 30 X 500 ml + 2 pcs Neptune 8 gr', 'aneto 50 ec 30 x 500 ml + 2 pcs neptune 8 gr', 'Aneto 50 EC 30 X 500 ml', 'Imported from mapping_zahir_barang.xlsx row 6', @now),
  ('Aneto 50 EC 50 X 100 ml', 'aneto 50 ec 50 x 100 ml', 'Aneto 50 EC 50 X 100 ml (M2U)', 'Imported from mapping_zahir_barang.xlsx row 7', @now),
  ('Aneto 50 EC 50 X 250 ml + 1 pcs Neptune 8 gr', 'aneto 50 ec 50 x 250 ml + 1 pcs neptune 8 gr', 'Aneto 50 EC 50 X 250 ml', 'Imported from mapping_zahir_barang.xlsx row 8', @now),
  ('Bamex 18 EC 100 X 50 ml', 'bamex 18 ec 100 x 50 ml', 'Bamex 18 EC 100 X 50 ml (M2U)', 'Imported from mapping_zahir_barang.xlsx row 9', @now),
  ('Bamex 18 EC 20 X 200 ml', 'bamex 18 ec 20 x 200 ml', 'Bamex 18 EC 20 X 200 ml (M2U)', 'Imported from mapping_zahir_barang.xlsx row 10', @now),
  ('Bamex 18 EC 50 X 100 ml', 'bamex 18 ec 50 x 100 ml', 'Bamex 18 EC 50 X 100 ml (M2U)', 'Imported from mapping_zahir_barang.xlsx row 11', @now),
  ('Centa-Fur 3GR 2 kg', 'centa-fur 3gr 2 kg', 'Centa-Fur 3GR 10 X 2 kg', 'Imported from mapping_zahir_barang.xlsx row 12', @now),
  ('Fetrilon Combi 1 10 X 40 X 25 gr', 'fetrilon combi 1 10 x 40 x 25 gr', 'Fetrilon Combi 1 12 X 40 X 25 gr', 'Imported from mapping_zahir_barang.xlsx row 13', @now),
  ('Fost Up 486 SL 20 X 1 ltr + 1 pack Pilly 5 gr', 'fost up 486 sl 20 x 1 ltr + 1 pack pilly 5 gr', 'Fost Up 486 SL 20 X 1 ltr', 'Imported from mapping_zahir_barang.xlsx row 14', @now),
  ('Futay-1 (Padi) 10 X 1 ltr + Kaos', 'futay-1 (padi) 10 x 1 ltr + kaos', 'Futay-1 (Padi) 10 X 1 ltr', 'Imported from mapping_zahir_barang.xlsx row 15', @now),
  ('Futay-1 30 X 500 ml', 'futay-1 30 x 500 ml', 'Futay-1 (Padi) 30 X 500 ml', 'Imported from mapping_zahir_barang.xlsx row 16', @now),
  ('Futay-6 (Sayur Daun) 10 X 1 ltr + Kaos', 'futay-6 (sayur daun) 10 x 1 ltr + kaos', 'Futay-6 (Sayur Daun) 10 X 1 ltr', 'Imported from mapping_zahir_barang.xlsx row 17', @now),
  ('Futay-7 (Sayuran Buah) 10 X 1 ltr + Kaos', 'futay-7 (sayuran buah) 10 x 1 ltr + kaos', 'Futay-7 (Sayuran Buah) 10 X 1 ltr', 'Imported from mapping_zahir_barang.xlsx row 18', @now),
  ('Gallery 403 SL 10 X 1 ltr + SG.02 100 gr', 'gallery 403 sl 10 x 1 ltr + sg.02 100 gr', 'Gallery 403 SL 10 X 1 ltr', 'Imported from mapping_zahir_barang.xlsx row 19', @now),
  ('Gallery 403 SL 30 X 500 ml + SG.02 100 gr', 'gallery 403 sl 30 x 500 ml + sg.02 100 gr', 'Gallery 403 SL 30 X 500 ml', 'Imported from mapping_zahir_barang.xlsx row 20', @now),
  ('Gallery 403 SL 50 X 200 ml', 'gallery 403 sl 50 x 200 ml', 'Gallery 403 SL 50 X 200 ml (M2U)', 'Imported from mapping_zahir_barang.xlsx row 21', @now),
  ('GG Akar 80 X 5 ml + Brosur (M2U)', 'gg akar 80 x 5 ml + brosur (m2u)', 'GG Akar 80 X 5 ml', 'Imported from mapping_zahir_barang.xlsx row 22', @now),
  ('GG Cuaca 50 X 100 ml (M2U)', 'gg cuaca 50 x 100 ml (m2u)', 'GG Cuaca 50 X 100 ml', 'Imported from mapping_zahir_barang.xlsx row 23', @now),
  ('Goal 240 EC 50 X 100 ml + kaos', 'goal 240 ec 50 x 100 ml + kaos', 'Goal 240 EC 50 X 100 ml', 'Imported from mapping_zahir_barang.xlsx row 24', @now),
  ('Insure Max 510 FS 2 X 50 X 25 ml', 'insure max 510 fs 2 x 50 x 25 ml', 'Insure Max 510 FS 100 X 25 ml', 'Imported from mapping_zahir_barang.xlsx row 25', @now),
  ('Jagung DK 9209C 20 X 1 kg', 'jagung dk 9209c 20 x 1 kg', 'Jagung DK 9209C 20 X 1 kg (Bayer)', 'Imported from mapping_zahir_barang.xlsx row 26', @now),
  ('Jagung Q-235 10 X 1 Kg + Paket GG Akar Akrostar', 'jagung q-235 10 x 1 kg + paket gg akar akrostar', 'Jagung Q-235 10 X 1 Kg', 'Imported from mapping_zahir_barang.xlsx row 27', @now),
  ('Japra 400 SE 30 X 500 ml (M2U)+2 pcs Borer', 'japra 400 se 30 x 500 ml (m2u)+2 pcs borer', 'Japra 400 SE 30 X 500 ml (M2U)', 'Imported from mapping_zahir_barang.xlsx row 28', @now),
  ('Japra 400 SE 50 X 250 ml (M2U)+1 pcs Borer', 'japra 400 se 50 x 250 ml (m2u)+1 pcs borer', 'Japra 400 SE 50 X 250 ml (M2U)', 'Imported from mapping_zahir_barang.xlsx row 29', @now),
  ('Kaliandra 482 EC 20 X 400 ml', 'kaliandra 482 ec 20 x 400 ml', 'Kaliandra 482 EC 30 X 400 ml', 'Imported from mapping_zahir_barang.xlsx row 30', @now),
  ('Kaliandra 482 EC 40 X 200 ml', 'kaliandra 482 ec 40 x 200 ml', 'Kaliandra 482 EC 50 X 200 ml', 'Imported from mapping_zahir_barang.xlsx row 31', @now),
  ('Kaliandra 482 EC 50 X 80 ml', 'kaliandra 482 ec 50 x 80 ml', 'Kaliandra 482 EC 50 X 80 ml (AGM)', 'Imported from mapping_zahir_barang.xlsx row 32', @now),
  ('Karimazu 590 EC 20 X 400 ml (Label Baru)', 'karimazu 590 ec 20 x 400 ml (label baru)', 'Karimazu 590 EC 20 X 400 ml', 'Imported from mapping_zahir_barang.xlsx row 33', @now),
  ('Karimazu 590 EC 40 X 200 ml (Label Baru)', 'karimazu 590 ec 40 x 200 ml (label baru)', 'Karimazu 590 EC 40 X 200 ml', 'Imported from mapping_zahir_barang.xlsx row 34', @now),
  ('Karimazu 590 EC 50 X 100 ml (Label Baru)', 'karimazu 590 ec 50 x 100 ml (label baru)', 'Karimazu 590 EC 50 X 100 ml', 'Imported from mapping_zahir_barang.xlsx row 35', @now),
  ('Kubis Hibrida F1 Kaelo 20 X 50 gr', 'kubis hibrida f1 kaelo 20 x 50 gr', 'Kubis Hibrida F1 Kaelo 30 X 15 gr', 'Imported from mapping_zahir_barang.xlsx row 36', @now),
  ('Liding 240 EC 48 X 200 ml', 'liding 240 ec 48 x 200 ml', 'Liding 240 EC (Piktogram Biru) 48 X 200 ml', 'Imported from mapping_zahir_barang.xlsx row 37', @now),
  ('Liding 240 EC 64 X 90 ml', 'liding 240 ec 64 x 90 ml', 'Liding 240 EC (Piktogram Biru) 64 X 90 ml', 'Imported from mapping_zahir_barang.xlsx row 38', @now),
  ('Mex-done 36 EC 20 X 500 ml', 'mex-done 36 ec 20 x 500 ml', 'Mexdone 36 EC 20 X 500 ml', 'Imported from mapping_zahir_barang.xlsx row 39', @now),
  ('Mulsa Karisma Q989 60 cm X 8 kg (Roll) Merah MT', 'mulsa karisma q989 60 cm x 8 kg (roll) merah mt', 'Mulsa Karisma Q989 60 cm X 17 kg (Roll) Merah MT*', 'Imported from mapping_zahir_barang.xlsx row 40', @now),
  ('Mulsa Ori Gajah 0.3 70 cm X 18 kg (Roll) + Kaos', 'mulsa ori gajah 0.3 70 cm x 18 kg (roll) + kaos', 'Mulsa Ori Gajah 0.3 70 cm X 18 kg (Roll)*', 'Imported from mapping_zahir_barang.xlsx row 41', @now),
  ('Paket Piribac 400 SC 50 X 100 ml + Axial 200 ml', 'paket piribac 400 sc 50 x 100 ml + axial 200 ml', 'Paket Piribac 400 SC 50 X 100 ml', 'Imported from mapping_zahir_barang.xlsx row 42', @now),
  ('Penta So-Z 505 SL 20 X 1 ltr + 1 pack Penta-Furon 5 gr', 'penta so-z 505 sl 20 x 1 ltr + 1 pack penta-furon 5 gr', 'Penta So-Z 505 SL 20 X 1 ltr', 'Imported from mapping_zahir_barang.xlsx row 43', @now),
  ('Pinamec 20 EC 15 X 1 ltr', 'pinamec 20 ec 15 x 1 ltr', 'Pinamec 20 EC 20 X 1 ltr (Hitam)', 'Imported from mapping_zahir_barang.xlsx row 44', @now),
  ('Pinamec 20 EC 15 X 1 ltr + Kaos', 'pinamec 20 ec 15 x 1 ltr + kaos', 'Pinamec 20 EC 20 X 1 ltr (Hitam)', 'Imported from mapping_zahir_barang.xlsx row 45', @now),
  ('Prima-Clink 40 X 250 ml', 'prima-clink 40 x 250 ml', 'Prima-Clink 20 X 250 ml', 'Imported from mapping_zahir_barang.xlsx row 46', @now),
  ('Qiuvita Biru 16.32.16 20 X 500 gr + Kaos', 'qiuvita biru 16.32.16 20 x 500 gr + kaos', 'Qiuvita Biru 16.32.16 20 X 500 gr', 'Imported from mapping_zahir_barang.xlsx row 47', @now),
  ('Qiuvita Merah 16.11.32 20 X 500 gr + Kaos', 'qiuvita merah 16.11.32 20 x 500 gr + kaos', 'Qiuvita Merah 16.11.32 20 X 500 gr', 'Imported from mapping_zahir_barang.xlsx row 48', @now),
  ('Symbisect 2/30 WG 75 X 100 gr', 'symbisect 2/30 wg 75 x 100 gr', 'Symbisect 2/30 WG 50 X 100 gr', 'Imported from mapping_zahir_barang.xlsx row 49', @now),
  ('Throne 250 EC 48 X 100 ml', 'throne 250 ec 48 x 100 ml', 'Throne 250 EC 20 X 100 ml', 'Imported from mapping_zahir_barang.xlsx row 50', @now)
ON DUPLICATE KEY UPDATE
  `zahir_name` = VALUES(`zahir_name`),
  `product_name` = VALUES(`product_name`),
  `notes` = VALUES(`notes`),
  `active` = 1,
  `updated_at` = @now;

UPDATE `zahir_stock_product_aliases` za JOIN `products` p ON (LOWER(TRIM(REPLACE(p.`name`, '*', ''))) COLLATE utf8mb4_general_ci) = (LOWER(TRIM(REPLACE(za.`product_name`, '*', ''))) COLLATE utf8mb4_general_ci) SET za.`product_id` = p.`id`, za.`updated_at` = @now WHERE za.`product_id` IS NULL OR za.`product_id` <> p.`id`;
