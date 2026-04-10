<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';

// URL: /{il-slug}-3d-baski → sehir.php?sehir={il-slug}
$slug = trim($_GET['sehir'] ?? '');
if (!$slug) {
    $uri  = $_SERVER['REQUEST_URI'] ?? '/';
    $slug = preg_replace('/-3d-baski.*$/', '', ltrim(parse_url($uri, PHP_URL_PATH), '/'));
}

$iller = seo_iller();
if (!isset($iller[$slug])) {
    $pageTitle = 'Sayfa Bulunamadı';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container" style="padding:8rem 2rem;text-align:center"><h2>Sayfa Bulunamadı</h2><a href="/" class="btn btn-primary" style="margin-top:2rem">Ana Sayfaya Dön</a></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$il      = $iller[$slug];
$ilAdi   = $il['ad'];
$isKonya = ($slug === 'konya');

// SEO — header include edilmeden ÖNCE
$pageTitle = "$ilAdi 3D Baskı Hizmeti – PLA+ Baskı, Hızlı Kargo";
$pageDesc  = "Minya 3D ile $ilAdi'da 3D baskı hizmeti. Bambu Lab A1 Combo, PLA+ materyal. Ev, ofis, endüstriyel parça, dekorasyon. Sipariş ver, $ilAdi'ya kargo.";

SEO::canonical(SITE_URL . "/$slug-3d-baski");
SEO::addSchema(SEO::schemaLocalBusiness($ilAdi));
SEO::addSchema(SEO::schemaBreadcrumb([
    ['Ana Sayfa',        SITE_URL . '/'],
    ["$ilAdi 3D Baskı", SITE_URL . "/$slug-3d-baski"],
]));
SEO::addSchema(SEO::schemaFAQ([
    ["$ilAdi'da 3D baskı hizmeti var mı?",
     "Evet. Minya 3D olarak $ilAdi'ya 3D baskı siparişi alıyoruz. Konya'daki atölyemizden Bambu Lab A1 Combo ile PLA+ üretim yapıp kargo ile gönderiyoruz."],
    ['Teslimat süresi ne kadar?',
     '48-72 saat üretim + 1-3 iş günü kargo ile kapınıza teslim edilir.'],
    ['Hangi materyaller kullanılıyor?',
     'Şu an PLA+ materyal ile üretim yapıyoruz. Dayanıklı, çevre dostu, geniş renk yelpazesi.'],
    ['Özel tasarım sipariş edebilir miyim?',
     "Evet. STL, OBJ veya 3MF dosyanızı gönderin; $ilAdi'ya özel baskı siparişi alıyoruz."],
]));

require_once __DIR__ . '/includes/header.php';

// İlçe listesi (sadece Konya için)
$ilceler = $isKonya ? seo_konya_ilceleri() : [];

// Öne çıkan ürünler
$vitrinUrünler = DB::all("SELECT u.*, k.baslik AS kat, k.slug AS kat_slug FROM mn_urunler u LEFT JOIN mn_kategoriler k ON k.id=u.kategori_id WHERE u.aktif=1 AND u.vitrin=1 LIMIT 4");
?>

<div style="margin-top:70px"></div>

<!-- HERO -->
<section style="padding:4rem 2rem 3rem;background:linear-gradient(135deg,rgba(14,165,233,.07),rgba(139,92,246,.05));border-bottom:1px solid var(--border);position:relative;z-index:1">
  <div class="container" style="max-width:900px">
    <div class="breadcrumb">
      <a href="/">Ana Sayfa</a><span>/</span>
      <span class="current"><?= e($ilAdi) ?> 3D Baskı</span>
    </div>
    <div class="hero-badge" style="margin-top:1rem">
      <span class="badge-dot"></span> <?= e($ilAdi) ?> – Hızlı Kargo
    </div>
    <h1 style="font-family:'Orbitron',sans-serif;font-size:clamp(1.8rem,3.5vw,3rem);font-weight:900;margin:.75rem 0 1rem;line-height:1.15">
      <?= e($ilAdi) ?>'da<br><span style="background:linear-gradient(135deg,var(--blue),var(--purple));-webkit-background-clip:text;-webkit-text-fill-color:transparent">3D Baskı Hizmeti</span>
    </h1>
    <p style="color:var(--muted);font-size:1.05rem;line-height:1.85;max-width:680px;margin-bottom:2rem">
      Minya 3D olarak <strong style="color:var(--text)"><?= e($ilAdi) ?></strong>'a 3D baskı siparişi alıyoruz.
      Konya'daki atölyemizde <strong style="color:var(--blue)">Bambu Lab A1 Combo</strong> ile PLA+ üretim yapıyor,
      <?= e($ilAdi) ?> adresinize hızlı kargo ile gönderiyoruz.
    </p>
    <div style="display:flex;gap:1rem;flex-wrap:wrap">
      <a href="/ozel-siparis.php" class="btn btn-primary">📁 Dosya Gönder & Fiyat Al</a>
      <a href="/urunler.php" class="btn btn-outline">📦 Hazır Ürünlere Bak</a>
      <?php if ($wa = ayar('whatsapp','')): ?>
      <a href="https://wa.me/<?= e(preg_replace('/\D/','',$wa)) ?>?text=<?= urlencode("Merhaba, $ilAdi için 3D baskı siparişi vermek istiyorum.") ?>"
         target="_blank" class="btn btn-outline">💬 WhatsApp</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- STATS -->
<div style="background:rgba(13,31,53,.6);border-bottom:1px solid var(--border);padding:1.5rem 2rem;position:relative;z-index:1">
  <div class="container" style="display:flex;gap:2.5rem;flex-wrap:wrap;justify-content:center;text-align:center">
    <?php
    $stats = [
      ['48 Saat', 'Ortalama Üretim', 'o'],
      ['PLA+',    'Premium Materyal','p'],
      ['%100',    'Yerli Üretim',    'blue'],
      ['Kargo',   e($ilAdi) . '\'a Teslimat', 'green'],
    ];
    foreach ($stats as [$val,$lbl,$cls]): ?>
    <div>
      <div style="font-family:'Orbitron',sans-serif;font-size:1.4rem;font-weight:700;color:var(--<?= $cls ?>)"><?= $val ?></div>
      <div style="font-size:.78rem;color:var(--muted);letter-spacing:.06em"><?= $lbl ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- KONYA İLÇELERİ -->
<?php if ($isKonya && !empty($ilceler)): ?>
<section class="section" style="padding:3rem 2rem;background:rgba(13,31,53,.3);border-bottom:1px solid var(--border)">
  <div class="container">
    <h2 style="font-family:'Orbitron',sans-serif;font-size:1.3rem;font-weight:700;margin-bottom:1.5rem">
      Konya İlçelerinde 3D Baskı
    </h2>
    <div style="display:flex;flex-wrap:wrap;gap:.6rem">
      <?php foreach ($ilceler as $ilce):
        $ilceAd = seo_konya_ilce_adi($ilce);
      ?>
      <a href="/konya-<?= $ilce ?>-3d-baski"
         style="background:rgba(14,165,233,.08);border:1px solid var(--border);border-radius:8px;padding:.45rem 1rem;font-size:.85rem;color:var(--muted);transition:all .2s"
         onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
         onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
        <?= e($ilceAd) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ÖNE ÇIKAN ÜRÜNLER -->
<?php if (!empty($vitrinUrünler)): ?>
<section class="section" style="padding:3rem 2rem">
  <div class="container">
    <h2 style="font-family:'Orbitron',sans-serif;font-size:1.3rem;font-weight:700;margin-bottom:1.5rem">
      <?= e($ilAdi) ?>'a Gönderilen Popüler Ürünler
    </h2>
    <div class="products-grid" style="grid-template-columns:repeat(auto-fill,minmax(240px,1fr))">
      <?php foreach ($vitrinUrünler as $u): ?>
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
    <div style="margin-top:1.5rem;text-align:center">
      <a href="/urunler.php" class="btn btn-outline">Tüm Ürünleri Gör →</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- HİZMET AÇIKLAMASI + FAQ -->
<section class="section" style="padding:3rem 2rem;background:rgba(13,31,53,.3);border-top:1px solid var(--border)">
  <div class="container" style="max-width:860px">
    <h2 style="font-family:'Orbitron',sans-serif;font-size:1.3rem;font-weight:700;margin-bottom:1.5rem">
      <?= e($ilAdi) ?> 3D Baskı Nasıl Çalışır?
    </h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.25rem;margin-bottom:2.5rem">
      <?php
      $adimlar = [
        ['📁','Dosya Gönder','STL, OBJ veya 3MF dosyanızı WhatsApp ya da site üzerinden gönderin.'],
        ['💬','Fiyat Alın',"24 saat içinde fiyat ve üretim süresi için size geri döneriz."],
        ['🖨️','Üretilsin','Bambu Lab A1 Combo ile PLA+ baskınız Konya\'da üretilir.'],
        ['📦','Kargolansın', e($ilAdi) . '\'a 1-3 iş günü içinde teslim edilir.'],
      ];
      foreach ($adimlar as [$ic,$t,$d]): ?>
      <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius2);padding:1.25rem">
        <div style="font-size:1.8rem;margin-bottom:.6rem"><?= $ic ?></div>
        <div style="font-weight:600;margin-bottom:.4rem"><?= $t ?></div>
        <div style="font-size:.85rem;color:var(--muted);line-height:1.7"><?= $d ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- SSS -->
    <h2 style="font-family:'Orbitron',sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:1.25rem">
      Sık Sorulan Sorular
    </h2>
    <?php
    $faqlar = [
      ["$ilAdi'da 3D baskı yapan firma var mı?",
       "Minya 3D olarak $ilAdi'na 3D baskı gönderimi yapıyoruz. Konya merkezli üretim atölyemizden siparişinizi hazırlayıp $ilAdi adresinize kargo ile gönderiyoruz."],
      ['Minimum sipariş adedi nedir?',
       '1 adet sipariş kabul edilmektedir. Tek parça baskıdan toplu siparişe kadar her ihtiyaç karşılanır.'],
      ['PLA ne kadar dayanıklı?',
       'PLA+ yüksek çekme mukavemeti, iyi katman yapışması ve 60°C'ye kadar ısı direnci sunar. Ev ve ofis kullanımı, prototip ve dekorasyon için idealdir.'],
      ['Sipariş takibini nasıl yapabilirim?',
       'Sipariş onayı e-posta ile iletilir. Kargo gönderiminde takip numarası paylaşılır.'],
    ];
    foreach ($faqlar as [$q,$a]): ?>
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

<!-- DİĞER İLLER -->
<section style="padding:2.5rem 2rem;border-top:1px solid var(--border);position:relative;z-index:1">
  <div class="container">
    <h3 style="font-size:.95rem;font-weight:600;margin-bottom:1.25rem;color:var(--muted);letter-spacing:.06em">
      DİĞER İLLERDE DE HİZMET VERİYORUZ
    </h3>
    <div style="display:flex;flex-wrap:wrap;gap:.5rem">
      <?php
      $diger = array_slice(array_filter($iller, fn($k) => $k !== $slug, ARRAY_FILTER_USE_KEY), 0, 40, true);
      foreach ($diger as $s => $il): ?>
      <a href="/<?= $s ?>-3d-baski"
         style="font-size:.8rem;color:var(--faint);padding:.25rem .65rem;border-radius:6px;border:1px solid rgba(14,165,233,.1);transition:all .2s"
         onmouseover="this.style.color='var(--blue)';this.style.borderColor='var(--border)'"
         onmouseout="this.style.color='var(--faint)';this.style.borderColor='rgba(14,165,233,.1)'">
        <?= e($il['ad']) ?>
      </a>
      <?php endforeach; ?>
      <a href="/turkiye-3d-baski" style="font-size:.8rem;color:var(--blue);padding:.25rem .65rem">Tümünü Gör →</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
