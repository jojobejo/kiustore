-- BRIVA SWITCH local/production setting.
-- Default production menjaga flow BRIVA existing bila migrasi belum dijalankan.

INSERT INTO `settings` (`key`, `content`)
SELECT 'briva_payment_mode', 'production'
WHERE NOT EXISTS (
    SELECT 1 FROM `settings` WHERE `key` = 'briva_payment_mode'
);
