-- Migration: 003_urun_etiket_begeni
-- Description: Add tags, like count and view count to products
-- Date: 2025-04-10
-- Requires: 001_initial_schema

ALTER TABLE `mn_urunler`
  ADD COLUMN IF NOT EXISTS `etiketler`     VARCHAR(500) NULL      AFTER `meta_desc`,
  ADD COLUMN IF NOT EXISTS `begeni_sayisi` INT UNSIGNED DEFAULT 0 AFTER `etiketler`,
  ADD COLUMN IF NOT EXISTS `goruntuleme`   INT UNSIGNED DEFAULT 0 AFTER `begeni_sayisi`;
