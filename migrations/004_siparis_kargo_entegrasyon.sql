-- Migration: 004_siparis_kargo_entegrasyon
-- Description: Add shipping tracking fields to orders
-- Date: 2025-04-10
-- Requires: 001_initial_schema

ALTER TABLE `mn_siparisler`
  ADD COLUMN IF NOT EXISTS `kargo_firma`       VARCHAR(60)  NULL AFTER `kargo_no`,
  ADD COLUMN IF NOT EXISTS `tahmini_teslimat`  DATE         NULL AFTER `kargo_firma`,
  ADD COLUMN IF NOT EXISTS `musteri_notu`      TEXT         NULL AFTER `not`,
  ADD COLUMN IF NOT EXISTS `iptal_nedeni`      VARCHAR(255) NULL AFTER `musteri_notu`;

ALTER TABLE `mn_siparisler`
  ADD INDEX IF NOT EXISTS `idx_created` (`created_at`);
