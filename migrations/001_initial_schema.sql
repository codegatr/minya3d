-- Migration: 001_initial_schema
-- Description: Minya 3D base tables
-- Date: 2025-04-10
-- Requires: (none)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `mn_adminler` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ad`         VARCHAR(80)  NOT NULL,
  `email`      VARCHAR(120) NOT NULL UNIQUE,
  `sifre_hash` VARCHAR(255) NOT NULL,
  `rol`        ENUM('super','editor') DEFAULT 'editor',
  `aktif`      TINYINT(1) DEFAULT 1,
  `son_giris`  DATETIME NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mn_kategoriler` (
  `id`     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `baslik` VARCHAR(120) NOT NULL,
  `slug`   VARCHAR(140) NOT NULL UNIQUE,
  `ikon`   VARCHAR(20)  DEFAULT '?',
  `sira`   SMALLINT UNSIGNED DEFAULT 0,
  `aktif`  TINYINT(1) DEFAULT 1
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mn_materyaller` (
  `id`     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `baslik` VARCHAR(80) NOT NULL,
  `renk`   VARCHAR(20) DEFAULT '#0EA5E9',
  `aktif`  TINYINT(1) DEFAULT 1
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mn_urunler` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `baslik`        VARCHAR(200) NOT NULL,
  `slug`          VARCHAR(220) NOT NULL UNIQUE,
  `aciklama`      TEXT,
  `fiyat`         DECIMAL(10,2) NOT NULL DEFAULT 0,
  `indirim_fiyat` DECIMAL(10,2) DEFAULT 0,
  `stok`          INT DEFAULT 0,
  `materyal`      VARCHAR(80),
  `boyut`         VARCHAR(80),
  `gorsel`        VARCHAR(200),
  `gorseller`     JSON,
  `kategori_id`   INT UNSIGNED,
  `vitrin`        TINYINT(1) DEFAULT 0,
  `aktif`         TINYINT(1) DEFAULT 1,
  `sira`          SMALLINT UNSIGNED DEFAULT 0,
  `whatsapp_msg`  VARCHAR(255),
  `meta_desc`     VARCHAR(255),
  `created_at`    DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`kategori_id`) REFERENCES `mn_kategoriler`(`id`) ON DELETE SET NULL,
  INDEX idx_slug   (`slug`),
  INDEX idx_aktif  (`aktif`),
  INDEX idx_vitrin (`vitrin`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mn_musteriler` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ad_soyad`   VARCHAR(120) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `telefon`    VARCHAR(30),
  `adres`      TEXT,
  `sehir`      VARCHAR(60),
  `ilce`       VARCHAR(60),
  `posta_kodu` VARCHAR(10),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email (`email`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mn_siparisler` (
  `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `musteri_id`     INT UNSIGNED,
  `toplam`         DECIMAL(10,2) NOT NULL DEFAULT 0,
  `kargo`          DECIMAL(10,2) DEFAULT 0,
  `durum`          ENUM('bekliyor','hazirlaniyor','kargoda','tamamlandi','iptal') DEFAULT 'bekliyor',
  `odeme_durum`    ENUM('bekliyor','odendi','iade') DEFAULT 'bekliyor',
  `odeme_yontemi`  VARCHAR(40) DEFAULT 'kart',
  `kargo_no`       VARCHAR(80),
  `not`            TEXT,
  `adres_snapshot` JSON,
  `created_at`     DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`musteri_id`) REFERENCES `mn_musteriler`(`id`) ON DELETE SET NULL,
  INDEX idx_durum (`durum`)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mn_siparis_kalemleri` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `siparis_id` INT UNSIGNED NOT NULL,
  `urun_id`    INT UNSIGNED,
  `baslik`     VARCHAR(200),
  `fiyat`      DECIMAL(10,2) NOT NULL,
  `adet`       SMALLINT NOT NULL DEFAULT 1,
  `toplam`     DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`siparis_id`) REFERENCES `mn_siparisler`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`urun_id`)    REFERENCES `mn_urunler`(`id`)    ON DELETE SET NULL
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mn_ayarlar` (
  `id`      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `anahtar` VARCHAR(80) NOT NULL UNIQUE,
  `deger`   TEXT
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

INSERT IGNORE INTO `mn_ayarlar` (`anahtar`, `deger`) VALUES
  ('site_adi',             'Minya 3D'),
  ('site_slogan',          'Gecelegi 3D ile Uretiyoruz'),
  ('email',                'info@minya3d.com'),
  ('para_birimi',          'TL'),
  ('kargo_ucreti',         '0'),
  ('min_ucretsiz_kargo',   '0');

INSERT IGNORE INTO `mn_materyaller` (`baslik`, `renk`, `aktif`) VALUES
  ('PLA+', '#0EA5E9', 1);
