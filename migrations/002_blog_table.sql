-- Migration: 002_blog_table
-- Description: Blog posts table
-- Date: 2025-04-10
-- Requires: 001_initial_schema

CREATE TABLE IF NOT EXISTS `mn_blog` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `baslik`     VARCHAR(200) NOT NULL,
  `slug`       VARCHAR(220) NOT NULL UNIQUE,
  `ozet`       TEXT,
  `icerik`     LONGTEXT,
  `kapak`      VARCHAR(200),
  `aktif`      TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_slug  (`slug`),
  INDEX idx_aktif (`aktif`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
