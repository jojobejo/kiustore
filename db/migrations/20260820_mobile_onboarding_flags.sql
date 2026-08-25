-- KIU Store Mobile Onboarding Flags
-- Jalankan sekali pada database aktif aplikasi.

CREATE TABLE IF NOT EXISTS `mobile_onboarding_flags` (
  `user_id` BIGINT UNSIGNED NOT NULL,
  `is_new_user` TINYINT(1) NOT NULL DEFAULT 1,
  `tutorial_completed_at` DATETIME DEFAULT NULL,
  `completion_splash_shown_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `idx_mobile_onboarding_new_user` (`is_new_user`),
  KEY `idx_mobile_onboarding_completed_at` (`tutorial_completed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Foreign key tidak dipaksakan agar aman pada dump lama project
-- yang memiliki perbedaan engine dan struktur historis.
