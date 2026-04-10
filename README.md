# Minya 3D – E-Ticaret & Kurumsal Platform

> Bambu Lab A1 Combo destekli futuristik 3D baskı ürün satış sistemi.

## Gereksinimler
- PHP 8.3+
- MySQL 8.0+
- ZipArchive, PDO, JSON eklentileri
- mod_rewrite (Apache)

## Kurulum

1. Dosyaları sunucuya yükleyin
2. `https://minya3d.com/installer/` adresine gidin
3. Adımları takip edin (DB bilgileri → Admin hesabı)
4. `installer/` klasörünü silin

## Bambu Lab A1 Combo Görseli

Hero bölümünde Bambu Lab A1 Combo görseli için:
```
/assets/img/bambu-a1-combo.webp
```
dosyasını yükleyin. Bambu Lab'ın resmi sitesinden ürün görselini indirip bu yola yerleştirin.

## Güncelleme Sistemi

Admin paneli → **Güncelleme Merkezi** bölümünden:
- GitHub `codegatr/minya3d` repo'sunun son release'ini çeker
- Otomatik yedek alır
- `manifest.json` içindeki değişen dosyaları günceller

### Release Yayınlama (Geliştirici)
```bash
git add .
git commit -m "v1.0.1: Açıklama"
git tag v1.0.1
git push origin main --tags
```
GitHub'da tag'ten otomatik release oluşturulur.

## Dizin Yapısı
```
/
├── installer/          Kurulum sihirbazı (kurulumdan sonra sil)
├── admin/              Admin paneli
├── api/                AJAX endpoint'leri
├── assets/             CSS, JS, görseller
├── includes/           Ortak PHP dosyaları
├── uploads/            Yüklenen dosyalar
├── backups/            Otomatik yedekler
├── config.php          DB ayarları (installer yazar)
├── index.php           Ana sayfa
├── urunler.php         Ürün listesi
├── urun.php            Ürün detay (/urun/slug)
├── sepet.php           Sepet
├── manifest.json       Versiyon takibi
└── .htaccess           URL yönlendirme & güvenlik
```

## Admin Panel
`/admin/login.php` — kurulumda oluşturulan e-posta/şifre ile giriş.

## Versiyon
1.0.0 – İlk sürüm
