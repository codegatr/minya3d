<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';

// URL: /konya-{ilce}-3d-baski → ilce.php?sehir_ilce=konya-{ilce}
$uri     = trim($_GET['sehir_ilce'] ?? '');
if (!$uri) {
    $path = ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
    $uri  = preg_replace('/-3d-baski.*$/', '', $path);
}
$parts  = explode('-', $uri, 2);
$sehirS = $parts[0] ?? '';
$ilceS  = $parts[1] ?? '';

$iller   = seo_iller();
$ilceler = seo_konya_ilceleri();

if ($sehirS !== 'konya' || !in_array($ilceS, $ilceler) || !isset($iller[$sehirS])) {
    $pageTitle = 'Sayfa Bulunamadı';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container" style="padding:8rem 2rem;text-align:center"><h2>Sayfa Bulunamadı</h2><a href="/" class="btn btn-primary" style="margin-top:2rem">Ana Sayfaya Dön</a></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$ilceAd = seo_konya_ilce_adi($ilceS);

// SEO — header include edilmeden ÖNCE
$pageTitle = "Konya $ilceAd 3D Baskı Hizmeti – PLA+";
$pageDesc  = "Konya $ilceAd bölgesinde 3D baskı hizmeti. Minya 3D ile PLA+ baskı, hızlı üretim, Konya içi kargo. Ev, ofis, prototip siparişi alınır.";

SEO::canonical(SITE_URL . "/konya-$ilceS-3d-baski");
SEO::addSchema(SEO::schemaLocalBusiness('Konya', $ilceAd));
SEO::addSchema(SEO::schemaBreadcrumb([
    ['Ana Sayfa',             SITE_URL . '/'],
    ['Konya 3D Baskı',        SITE_URL . '/konya-3d-baski'],
    ["$ilceAd 3D Baskı",     SITE_URL . "/konya-$ilceS-3d-baski"],
]));
SEO::addSchema(SEO::schemaFAQ([
    ["Konya $ilceAd'da 3D baskı hizmeti var mı?",
     "Evet. Minya 3D olarak Konya $ilceAd bölgesine 3D baskı hizmeti sunuyoruz. PLA+ materyal ile üretim yapıp Konya içi kargo veya elden teslim seçeneği mevcuttur."],
    ['Teslimat süresi ne kadar?',
     "Konya içi siparişlerde 24-48 saat üretim, $ilceAd adresinize ertesi gün teslim mümkündür."],
]));

require_once __DIR__ . '/includes/header.php';
?>

<div style="margin-top:70px"></div>

<section style="padding:4rem 2rem 3rem;background:linear-gradient(135deg,rgba(14,165,233,.07),rgba(139,92,246,.05));border-bottom:1px solid var(--border);position:relative;z-index:1">
  <div class="container" style="max-width:860px">
    <div class="breadcrumb">
      <a href="/">Ana Sayfa</a><span>/</span>
      <a href="/konya-3d-baski">Konya 3D Baskı</a><span>/</span>
      <span class="current"><?= e($ilceAd) ?></span>
    </div>
    <h1 style="font-family:'Orbitron',sans-serif;font-size:clamp(1.6rem,3vw,2.6rem);font-weight:900;margin:1rem 0;line-height:1.2">
      Konya <span style="background:linear-gradient(135deg,var(--blue),var(--purple));-webkit-background-clip:text;-webkit-text-fill-color:transparent"><?= e($ilceAd) ?></span><br>
      3D Baskı Hizmeti
    </h1>
    <p style="color:var(--muted);font-size:1rem;line-height:1.85;max-width:640px;margin-bottom:2rem">
      Minya 3D olarak Konya <strong style="color:var(--text)"><?= e($ilceAd) ?></strong> bölgesine 3D baskı siparişi alıyoruz.
      PLA+ materyal, Bambu Lab A1 Combo kalitesi, Konya içi hızlı teslimat.
    </p>
    <div style="display:flex;gap:1rem;flex-wrap:wrap">
      <a href="/ozel-siparis.php" class="btn btn-primary">📁 Dosya Gönder & Fiyat Al</a>
      <a href="/urunler.php" class="btn btn-outline">📦 Hazır Ürünler</a>
      <?php if ($wa = ayar('whatsapp','')): ?>
      <a href="https://wa.me/<?= e(preg_replace('/\D/','',$wa)) ?>?text=<?= urlencode("Merhaba, Konya $ilceAd için 3D baskı siparişi vermek istiyorum.") ?>"
         target="_blank" class="btn btn-outline">💬 WhatsApp</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- DİĞER KONYA İLÇELERİ -->
<section style="padding:2rem;border-bottom:1px solid var(--border);position:relative;z-index:1">
  <div class="container">
    <p style="font-size:.82rem;color:var(--muted);margin-bottom:.9rem">Konya'nın diğer ilçeleri:</p>
    <div style="display:flex;flex-wrap:wrap;gap:.5rem">
      <?php foreach ($ilceler as $i):
        $iad = seo_konya_ilce_adi($i);
        $active = $i === $ilceS;
      ?>
      <a href="/konya-<?= $i ?>-3d-baski"
         style="font-size:.82rem;padding:.3rem .75rem;border-radius:7px;border:1px solid <?= $active ? 'var(--blue)' : 'var(--border)' ?>;color:<?= $active ? 'var(--blue)' : 'var(--muted)' ?>">
        <?= e($iad) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Ürünler -->
<section style="padding:3rem 2rem;position:relative;z-index:1">
  <div class="container">
    <h2 style="font-family:'Orbitron',sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:1.5rem">
      Popüler Ürünler
    </h2>
    <div class="products-grid">
      <?php
      $urunler = DB::all("SELECT u.*,k.baslik AS kat FROM mn_urunler u LEFT JOIN mn_kategoriler k ON k.id=u.kategori_id WHERE u.aktif=1 LIMIT 8");
      foreach ($urunler as $u): ?>
      <div class="product-card">
        <a href="/urun/<?= e($u['slug']) ?>" class="product-thumb" style="display:block">
          <img src="<?= urunGorsel($u) ?>" alt="<?= e($u['baslik']) ?>" loading="lazy">
        </a>
        <div class="product-body">
          <span class="product-material">PLA+</span>
          <a href="/urun/<?= e($u['slug']) ?>" class="product-title"><?= e($u['baslik']) ?></a>
          <div class="product-footer">
            <div class="product-price"><?= para($u['indirim_fiyat']>0?$u['indirim_fiyat']:$u['fiyat']) ?></div>
            <button class="add-to-cart" data-id="<?= $u['id'] ?>">+</button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
