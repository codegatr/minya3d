-- Migration: 003_urun_etiket_ve_begeni
-- Açıklama : Ürünlere etiket (tags) alanı ve beğeni sayacı eklenir
-- Tarih    : 2025-04-10
-- Bağımlılık: 001_initial_schema

-- Etiketler (virgülle ayrılmış metin olarak saklanır, hızlı uygulama için)
ALTER TABLE `mn_urunler`
  ADD COLUMN IF NOT EXISTS `etiketler`    VARCHAR(500) NULL AFTER `meta_desc`,
  ADD COLUMN IF NOT EXISTS `begeni_sayisi` INT UNSIGNED DEFAULT 0 AFTER `etiketler`,
  ADD COLUMN IF NOT EXISTS `goruntuleme`   INT UNSIGNED DEFAULT 0 AFTER `begeni_sayisi`;

-- Etiket araması için full-text index (MySQL 5.7+)
ALTER TABLE `mn_urunler`
  ADD FULLTEXT INDEX IF NOT EXISTS `ft_urun_ara` (`baslik`, `aciklama`, `etiketler`);
