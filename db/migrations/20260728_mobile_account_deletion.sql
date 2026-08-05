-- KIU Store Mobile Account Deletion
-- Jalankan pada database aktif jika migrasi 20260629_mobile_api.sql sudah pernah dijalankan sebelumnya.

CREATE TABLE IF NOT EXISTS `mobile_account_deletions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `email_hash` CHAR(64) NOT NULL,
  `deleted_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_mobile_account_deletion_user` (`user_id`),
  KEY `idx_mobile_account_deletion_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
