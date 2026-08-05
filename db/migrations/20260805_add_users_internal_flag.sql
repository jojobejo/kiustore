-- Migration: add internal-account flag for KIU Store users.
-- Safe to rerun on MySQL/MariaDB.

SET @db_name := DATABASE();

SET @users_internal_column_exists := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'users'
      AND COLUMN_NAME = 'is_internal'
);

SET @add_users_internal_column_sql := IF(
    @users_internal_column_exists = 0,
    'ALTER TABLE users ADD COLUMN is_internal TINYINT(1) NOT NULL DEFAULT 0 AFTER status',
    'SELECT ''users.is_internal already exists'' AS message'
);

PREPARE add_users_internal_column_stmt FROM @add_users_internal_column_sql;
EXECUTE add_users_internal_column_stmt;
DEALLOCATE PREPARE add_users_internal_column_stmt;

SET @users_internal_index_exists := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'users'
      AND INDEX_NAME = 'idx_users_is_internal_role'
);

SET @add_users_internal_index_sql := IF(
    @users_internal_index_exists = 0,
    'ALTER TABLE users ADD INDEX idx_users_is_internal_role (is_internal, role)',
    'SELECT ''idx_users_is_internal_role already exists'' AS message'
);

PREPARE add_users_internal_index_stmt FROM @add_users_internal_index_sql;
EXECUTE add_users_internal_index_stmt;
DEALLOCATE PREPARE add_users_internal_index_stmt;
