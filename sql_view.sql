SELECT
    `a`.`id` AS `id`,
    `a`.`category_id` AS `category_id`,
    `a`.`sku` AS `sku`,
    `a`.`name` AS `name`,
    `a`.`description` AS `description`,
    `a`.`picture_name` AS `picture_name`,
    `a`.`product_unit_value` AS `product_unit_value`,
    `a`.`product_type` AS `product_type`,
    `a`.`product_unit_1` AS `product_unit_1`,
    IF(`b`.`credit` IS NOT NULL, 1, 0) AS `promo`,
    `a`.`price` AS `price`,
    `a`.`price_2` AS `price_2`,
    `a`.`price_3` AS `price_3`,
    IF(
        `b`.`credit` IS NOT NULL,
        `a`.`price` - `b`.`credit`,
        `a`.`price`
    ) AS `promo_price`,
    IF(
        `b`.`credit` IS NOT NULL,
        `a`.`price_2` - `b`.`credit`,
        `a`.`price_2`
    ) AS `promo_price_2`,
    IF(
        `b`.`credit` IS NOT NULL,
        `a`.`price_3` - `b`.`credit`,
        `a`.`price_3`
    ) AS `promo_price_3`,
    IF(
        `b`.`credit` IS NOT NULL,
        ROUND(`b`.`credit` / `a`.`price` * 100, 0),
        0
    ) AS `discount`,
    IF(
        `b`.`credit` IS NOT NULL,
        ROUND(`b`.`credit` / `a`.`price_2` * 100, 0),
        0
    ) AS `discount_2`,
    IF(
        `b`.`credit` IS NOT NULL,
        ROUND(`b`.`credit` / `a`.`price_3` * 100, 0),
        0
    ) AS `discount_3`,
    `a`.`current_discount` AS `current_discount`,
    `a`.`stock` AS `stock`,
    `a`.`product_unit` AS `product_unit`,
    `a`.`is_available` AS `is_available`,
    `a`.`add_date` AS `add_date`,
    CASE WHEN `a`.`price` <> 0 AND `a`.`price_2` = 0 AND `a`.`price_3` = 0 THEN '1' WHEN `a`.`price` = 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` = 0 THEN '2' WHEN `a`.`price` = 0 AND `a`.`price_2` = 0 AND `a`.`price_3` <> 0 THEN '3' WHEN `a`.`price` <> 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` = 0 THEN '1,2' WHEN `a`.`price` <> 0 AND `a`.`price_2` = 0 AND `a`.`price_3` <> 0 THEN '1,3' WHEN `a`.`price` = 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` <> 0 THEN '2,3' WHEN `a`.`price` <> 0 AND `a`.`price_2` <> 0 AND `a`.`price_3` <> 0 THEN '1,2,3'
END AS `level_product`
FROM
    (
        `kiucoid_kiustore`.`products` `a`
    LEFT JOIN `kiucoid_kiustore`.`promo` `b`
    ON
        (
            `b`.`product_id` = `a`.`id` AND CAST(`b`.`start_date` AS DATE) <= CURDATE() AND CAST(`b`.`expired_date` AS DATE) >= CURDATE()))