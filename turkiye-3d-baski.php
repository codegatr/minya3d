<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';

$pageTitle = "Türkiye 3D Baskı Hizmeti – PLA+ Baskı, 81 İle Kargo";
$pageDesc  = "Minya 3D ile Türkiye'nin 81 iline 3D baskı hizmeti. Bambu Lab A1 Combo, PLA+ materyal, hızlı kargo. Ev, ofis, endüstriyel üretim.";

SEO::canonical(SITE_URL . '/turkiye-3d-baski');
SEO::addSchema(SEO::schemaLocalBusiness('Türkiye'));
SEO::addSchema(SEO::schemaBreadcrumb([
    ['Ana Sayfa',           SITE_URL . '/'],
    ['Türkiye 3D Baskı',    SITE_URL . '/turkiye-3d-baski'],
]));
SEO::addSchema(SEO::schemaFAQ([
    ['Türkiye genelinde 3D baskı hizmeti veriyor musunuz?',
     'Evet. Minya 3D olarak Türkiye\'nin 81 iline 3D baskı gönderimi yapıyoruz. Konya merkezli üretim atölyemizden siparişiniz hazırlanıp kargoya verilir.'],
    ['Hangi kargo firmaları kullanılıyor?',
     'Yurtiçi Kargo ve MNG Kargo ile çalışıyoruz. 1-3 iş günü içinde Türkiye\'nin her yerine teslimat yapılır.'],
    ['Teslimat süresi ne kadar?',
     '48-72 saat üretim + 1-3 iş günü kargo. Toplam 2-5 iş günü içinde kapınıza teslim.'],
    ['En yakın ilçemize de gönderiyor musunuz?',
     'Evet, kargo ağımız Türkiye\'nin tüm ilçelerine ulaşmaktadır.'],
]));

require_once __DIR__ . '/includes/header.php';

$iller = seo_iller();
$vitrin = DB::all("SELECT u.*, k.baslik AS kat, k.slug AS kat_slug FROM mn_urunler u LEFT JOIN mn_kategoriler k ON k.id=u.kategori_id WHERE u.aktif=1 AND u.vitrin=1 LIMIT 4");
?>

<div style="margin-top:70px"></div>

<section style="padding:4rem 2rem 3rem;background:linear-gradient(135deg,rgba(14,165,233,.07),rgba(139,92,246,.05));border-bottom:1px solid var(--border);position:relative;z-index:1">
  <div class="container" style="max-width:920px">
    <div class="breadcrumb">
      <a href="/">Ana Sayfa</a><span>/</span>
      <span class="current">Türkiye 3D Baskı</span>
    </div>
    <div class="hero-badge" style="margin-top:1rem">
      <span class="badge-dot"></span> 81 İle Kargo
    </div>
    <h1 style="font-family:'Orbitron',sans-serif;font-size:clamp(1.8rem,3.5vw,3rem);font-weight:900;margin:.75rem 0 1rem;line-height:1.15">
      Türkiye'nin Her Yerine<br>
      <span style="background:linear-gradient(135deg,var(--blue),var(--purple));-webkit-background-clip:text;-webkit-text-fill-color:transparent">3D Baskı Hizmeti</span>
    </h1>
    <p style="color:var(--muted);font-size:1.05rem;line-height:1.85;max-width:700px;margin-bottom:2rem">
      Minya 3D olarak Konya'daki üretim atölyemizden <strong style="color:var(--blue)">Bambu Lab A1 Combo</strong> ile
      PLA+ baskı yapıp Türkiye'nin 81 iline hızlı kargo ile gönderiyoruz.
      Ev aksesuarından endüstriyel prototipe, dekorasyon parçasından eğitim modellerine kadar her şeyi üretiyoruz.
    </p>
    <div style="display:flex;gap:1rem;flex-wrap:wrap">
      <a href="/ozel-siparis.php" class="btn btn-primary">📁 Dosya Gönder & Fiyat Al</a>
      <a href="/urunler.php" class="btn btn-outline">📦 Hazır Ürünlere Bak</a>
    </div>
  </div>
</section>

<!-- TÜM İLLER -->
<section style="padding:3rem 2rem;background:rgba(13,31,53,.3);border-bottom:1px solid var(--border);position:relative;z-index:1">
  <div class="container">
    <h2 style="font-family:'Orbitron',sans-serif;font-size:1.25rem;font-weight:700;margin-bottom:1.5rem">
      Hizmet Verilen 81 İl
    </h2>
    <div style="display:flex;flex-wrap:wrap;gap:.5rem">
      <?php foreach ($iller as $slug => $il): ?>
      <a href="/<?= $slug ?>-3d-baski"
         style="background:rgba(14,165,233,.07);border:1px solid var(--border);border-radius:8px;padding:.4rem .9rem;font-size:.85rem;color:var(--muted);transition:all .2s"
         onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
         onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
        <?= e($il['ad']) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- VİTRİN ÜRÜNLER -->
<?php if (!empty($vitrin)): ?>
<section style="padding:3rem 2rem;position:relative;z-index:1">
  <div class="container">
    <h2 style="font-family:'Orbitron',sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:1.5rem">
      Türkiye'ye Gönderilen Popüler Ürünler
    </h2>
    <div class="products-grid" style="grid-template-columns:repeat(auto-fill,minmax(240px,1fr))">
      <?php foreach ($vitrin as $u): ?>
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
<?php endif; ?>

<!-- SSS -->
<section style="padding:3rem 2rem;background:rgba(13,31,53,.3);border-top:1px solid var(--border);position:relative;z-index:1">
  <div class="container" style="max-width:780px">
    <h2 style="font-family:'Orbitron',sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:1.5rem">
      Sık Sorulan Sorular
    </h2>
    <?php
    $faqs = [
      ['Türkiye genelinde 3D baskı hizmeti veriyor musunuz?',
       'Evet. Minya 3D olarak Türkiye\'nin 81 iline 3D baskı gönderimi yapıyoruz. Konya merkezli üretim atölyemizden siparişiniz hazırlanıp kargoya verilir.'],
      ['Hangi materyaller var?',
       'Şu an PLA+ materyali ile üretim yapıyoruz. Dayanıklı, çevre dostu, geniş renk yelpazesi.'],
      ['Minimum sipariş adedi nedir?',
       '1 adet sipariş kabul edilmektedir. Tek parçadan toplu siparişe kadar her ihtiyaç karşılanır.'],
      ['Kargo süresi ne kadar?',
       '48-72 saat üretim + 1-3 iş günü kargo ile kapınıza teslim edilir.'],
    ];
    foreach ($faqs as [$q,$a]): ?>
    <details style="border:1px solid var(--border);border-radius:8px;margin-bottom:.6rem;overflow:hidden">
      <summary style="padding:.9rem 1.1rem;cursor:pointer;font-weight:600;font-size:.92rem;list-style:none;display:flex;justify-content:space-between;align-items:center">
        <?= e($q) ?> <span style="color:var(--blue)">+</span>
      </summary>
      <div style="padding:.75rem 1.1rem 1rem;color:var(--muted);font-size:.9rem;line-height:1.8;border-top:1px solid var(--border)">
        <?= e($a) ?>
      </div>
    </details>
    <?php endforeach; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
