ALTER TABLE banner_product
  ADD COLUMN IF NOT EXISTS banner_title VARCHAR(150) NULL AFTER banner_image,
  ADD COLUMN IF NOT EXISTS redirect_type ENUM('product','category','custom') NOT NULL DEFAULT 'product' AFTER banner_title,
  ADD COLUMN IF NOT EXISTS redirect_product_id INT(11) NULL AFTER redirect_type,
  ADD COLUMN IF NOT EXISTS redirect_category_id INT(11) NULL AFTER redirect_product_id,
  ADD COLUMN IF NOT EXISTS redirect_url VARCHAR(255) NULL AFTER redirect_category_id,
  ADD COLUMN IF NOT EXISTS display_order INT(11) NOT NULL DEFAULT 0 AFTER redirect_url,
  ADD COLUMN IF NOT EXISTS is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER display_order;

UPDATE banner_product
SET
  banner_title = COALESCE(NULLIF(banner_title, ''), CONCAT('Banner Produk #', id)),
  redirect_type = CASE
    WHEN redirect_type IN ('product', 'category', 'custom') THEN redirect_type
    ELSE 'product'
  END,
  redirect_product_id = CASE
    WHEN redirect_type = 'product' AND redirect_product_id IS NULL AND product_id > 0 THEN product_id
    ELSE redirect_product_id
  END,
  display_order = CASE
    WHEN display_order < 1 THEN id
    ELSE display_order
  END
WHERE banner_title IS NULL
   OR banner_title = ''
   OR (redirect_type = 'product' AND redirect_product_id IS NULL AND product_id > 0)
   OR display_order < 1;
