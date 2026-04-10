<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';

$pageTitle = 'PLA 3D Baskı Hizmeti – Bambu Lab A1 Combo – Konya';
$pageDesc  = 'Minya 3D: Bambu Lab A1 Combo ile PLA+ 3D baskı hizmeti. Konya merkezli, Türkiye\'ye hızlı kargo. Ev, ofis, endüstriyel parça, dekorasyon.';

SEO::canonical(SITE_URL . '/');
SEO::addSchema(SEO::schemaLocalBusiness('Konya'));

require_once __DIR__ . '/includes/header.php';

// Öne çıkan kategoriler
$kategoriler = DB::all("SELECT * FROM mn_kategoriler WHERE aktif=1 ORDER BY sira ASC LIMIT 6");

// Öne çıkan ürünler
$vitrinUrünler = DB::all("
  SELECT u.*, k.baslik AS kat_baslik, k.slug AS kat_slug
  FROM mn_urunler u
  LEFT JOIN mn_kategoriler k ON k.id = u.kategori_id
  WHERE u.aktif=1 AND u.vitrin=1
  ORDER BY u.id DESC LIMIT 8
");

// Site istatistikleri
$stats = [
  'urun'     => DB::row("SELECT COUNT(*) AS c FROM mn_urunler WHERE aktif=1")['c'] ?? 0,
  'siparis'  => DB::row("SELECT COUNT(*) AS c FROM mn_siparisler")['c'] ?? 0,
  'materyal' => DB::row("SELECT COUNT(*) AS c FROM mn_materyaller WHERE aktif=1")['c'] ?? 0,
];
?>

<!-- ░░░ HERO ░░░ -->
<section class="hero">
  <div class="hero-glow hero-glow-1"></div>
  <div class="hero-glow hero-glow-2"></div>

  <div class="hero-container container">

    <!-- Sol: İçerik -->
    <div class="hero-content reveal">
      <div class="hero-badge">
        <span class="badge-dot"></span>
        TÜRKİYE'NİN 3D BASKI PLATFORMU
      </div>

      <h1>
        Hayal Ettiğinizi<br>
        <span class="grad">3D ile Üretiyoruz</span>
      </h1>

      <p class="hero-sub">
        Bambu Lab A1 Combo ile endüstriyel kalitede çok renkli baskı.
        PLA, ABS, PETG, Reçine ve daha fazla materyalle
        prototipler, parçalar, sanat eserleri ve özel tasarımlar.
        Sipariş verin, kapınıza gelsin.
      </p>

      <div class="hero-actions">
        <a href="/urunler.php" class="btn btn-primary">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          Ürünleri Keşfet
        </a>
        <a href="/ozel-siparis.php" class="btn btn-outline">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
          Özel Sipariş
        </a>
      </div>

      <div class="hero-stats">
        <div>
          <span class="hstat-num"><?= number_format($stats['urun']) ?>+</span>
          <span class="hstat-label">ÜRÜN ÇEŞİDİ</span>
        </div>
        <div>
          <span class="hstat-num o">48 Saat</span>
          <span class="hstat-label">ORTALAMA TESLİMAT</span>
        </div>
        <div>
          <span class="hstat-num p"><?= $stats['materyal'] ?>+</span>
          <span class="hstat-label">MATERYAL TİPİ</span>
        </div>
      </div>
    </div>

    <!-- Sağ: Bambu Lab A1 Combo -->
    <div class="hero-visual reveal">
      <div class="printer-wrap">
        <div class="printer-corner tl"></div>
        <div class="printer-corner tr"></div>
        <div class="printer-corner bl"></div>
        <div class="printer-corner br"></div>
        <div class="scan-overlay"><div class="scan-line"></div></div>

        <!-- Bambu Lab A1 Combo resmi görsel -->
        <img
          src="/assets/img/bambu-a1-combo.webp"
          alt="Bambu Lab A1 Combo – Minya 3D"
          onerror="this.src='/assets/img/bambu-a1-combo-fallback.png';this.onerror=null;"
          style="padding:1.5rem;"
        >

        <div class="printer-status">
          <span class="status-dot"></span>
          <span>BAMBU LAB A1 COMBO &nbsp;|&nbsp; HAZIR &nbsp;|&nbsp; 4 RENK</span>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ░░░ STATS BAR ░░░ -->
<div class="stats-bar">
  <div class="stats-inner">
    <div class="stat">
      <span class="stat-num"><?= number_format($stats['urun']) ?>+</span>
      <span class="stat-label">ÜRÜN ÇEŞİDİ</span>
    </div>
    <div class="stat">
      <span class="stat-num o">48 Saat</span>
      <span class="stat-label">ORTALAMA TESLİMAT</span>
    </div>
    <div class="stat">
      <span class="stat-num p"><?= $stats['materyal'] ?>+</span>
      <span class="stat-label">MATERYAL TİPİ</span>
    </div>
    <div class="stat">
      <span class="stat-num">4.9★</span>
      <span class="stat-label">MÜŞTERİ PUANI</span>
    </div>
    <div class="stat">
      <span class="stat-num o">%100</span>
      <span class="stat-label">YERLİ ÜRETİM</span>
    </div>
  </div>
</div>

<!-- ░░░ KATEGORİLER ░░░ -->
<section class="section" style="background:rgba(13,31,53,.3);border-bottom:1px solid var(--border)">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="section-tag">▸ KATEGORİLER</span>
        <h2 class="section-title">Ne Arıyorsunuz?<br><span>Hepsi Burada.</span></h2>
        <p class="section-sub">Endüstriyel parçalardan sanat eserlerine, mimari modellerden kişisel aksesuvarlara.</p>
      </div>
      <a href="/kategoriler.php" class="btn btn-outline">Tümünü Gör →</a>
    </div>

    <div class="cat-grid">
      <?php foreach ($kategoriler as $kat):
        $count = DB::row("SELECT COUNT(*) AS c FROM mn_urunler WHERE kategori_id=? AND aktif=1", [$kat['id']])['c'];
      ?>
      <a href="/kategori/<?= e($kat['slug']) ?>" class="cat-card reveal">
        <div class="cat-icon-wrap"><?= $kat['ikon'] ?: '📦' ?></div>
        <div class="cat-card-title"><?= e($kat['baslik']) ?></div>
        <div class="cat-card-count"><?= $count ?> ürün</div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ░░░ VİTRİN ÜRÜNLER ░░░ -->
<section class="section">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="section-tag">▸ ÖNE ÇIKAN ÜRÜNLER</span>
        <h2 class="section-title">En Çok <span>Tercih Edilenler</span></h2>
      </div>
      <a href="/urunler.php" class="btn btn-outline">Tüm Ürünler →</a>
    </div>

    <div class="products-grid">
      <?php foreach ($vitrinUrünler as $u): ?>
      <div class="product-card reveal">
        <div class="product-thumb">
          <img src="<?= urunGorsel($u) ?>" alt="<?= e($u['baslik']) ?>" loading="lazy">
          <div class="product-badge-wrap">
            <?php if ($u['vitrin']): ?><span class="pbadge pbadge-new">VİTRİN</span><?php endif; ?>
            <?php if ($u['indirim_fiyat'] > 0): ?><span class="pbadge pbadge-sale">İNDİRİM</span><?php endif; ?>
          </div>
        </div>
        <div class="product-body">
          <span class="product-material"><?= e($u['materyal'] ?: $u['kat_baslik']) ?></span>
          <div class="product-title"><?= e($u['baslik']) ?></div>
          <div class="product-footer">
            <div class="product-price">
              <?= para($u['indirim_fiyat'] > 0 ? $u['indirim_fiyat'] : $u['fiyat']) ?>
              <small>/ adet</small>
            </div>
            <button class="add-to-cart" data-id="<?= $u['id'] ?>" aria-label="Sepete Ekle">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ░░░ NASIL ÇALIŞIR ░░░ -->
<section class="section" style="background:rgba(13,31,53,.3);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
  <div class="container">
    <div style="text-align:center;margin-bottom:3rem">
      <span class="section-tag">▸ NASIL ÇALIŞIR</span>
      <h2 class="section-title">4 Adımda <span>Ürününüz Hazır</span></h2>
    </div>
    <div class="process-grid">
      <?php
      $steps = [
        ['01','🎯','Tasarımı Seç','Hazır modellerden seçin ya da kendi STL / OBJ / 3MF dosyanızı yükleyin.'],
        ['02','🎨','Materyal & Renk','12+ materyal tipi ve onlarca renk. Boyutunuzu ve infill oranını belirleyin.'],
        ['03','🖨️','Baskıya Gönder','Sipariş onaylanır, Bambu Lab A1 Combo ile 24-48 saat içinde üretim başlar.'],
        ['04','📦','Kapıya Teslim','Özenle paketlenerek Türkiye\'nin her yerine güvenli kargo ile teslim.'],
      ];
      foreach ($steps as [$num,$icon,$title,$desc]): ?>
      <div class="process-step reveal">
        <div class="step-number"><?= $num ?></div>
        <div class="step-circle"><?= $icon ?></div>
        <h3><?= e($title) ?></h3>
        <p><?= e($desc) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ░░░ NEDEN MİNYA 3D ░░░ -->
<section class="section">
  <div class="container">
    <div class="section-head" style="margin-bottom:2.5rem">
      <div>
        <span class="section-tag">▸ NEDEN MİNYA 3D</span>
        <h2 class="section-title">Fark Yaratan <span>Teknoloji</span></h2>
      </div>
    </div>
    <div class="features-grid">
      <?php
      $feats = [
        ['feat-blue','🏭','Bambu Lab A1 Combo','Tam otomatik kalibrasyon, 4 renkli çok malzeme baskı, AMS Lite ile filament yönetimi. ±0.1mm tolerans.'],
        ['feat-orange','⚡','Hızlı Teslimat','Stok ürünlerde 24 saat, özel siparişlerde 48-72 saat üretim süresi. Aynı gün kargo seçeneği.'],
        ['feat-purple','🔬','12+ Materyal','PLA+, ABS, PETG, TPU, Reçine (SLA), Nylon, PC ve metal katkılı filamentler.'],
        ['feat-green','📐','Özel Boyut','Mikro parçalardan büyük formatlı modellere. Müşteri dosyası desteği (STL / OBJ / 3MF).'],
        ['feat-blue','🎨','Son İşlem Hizmeti','Boya, vernik, taşlama ve asetona daldırma ile profesyonel yüzey bitişi.'],
        ['feat-orange','🔐','Güvenli Alışveriş','SSL, 3D Secure ödeme, e-fatura garantisi. %100 yerli firma güvencesi.'],
      ];
      foreach ($feats as [$cls,$icon,$title,$desc]): ?>
      <div class="feature-card reveal">
        <div class="feat-icon <?= $cls ?>"><?= $icon ?></div>
        <h3><?= e($title) ?></h3>
        <p><?= e($desc) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ░░░ CTA ░░░ -->
<section class="section" style="text-align:center;background:linear-gradient(135deg,rgba(14,165,233,.07),rgba(139,92,246,.07));border-top:1px solid var(--border)">
  <div class="container">
    <span class="section-tag">▸ BAŞLAYIN</span>
    <h2 class="section-title" style="font-size:clamp(2rem,4vw,3rem)">
      Hayal Ettiğiniz Ürün<br><span>Bir Tıkta Sizin</span>
    </h2>
    <p class="section-sub" style="margin:0 auto 2.5rem;font-size:1.05rem">
      Dosyanızı yükleyin, saniyeler içinde fiyat alın. Risk yok, taahhüt yok.
    </p>
    <div style="display:flex;justify-content:center;gap:1rem;flex-wrap:wrap">
      <a href="/urunler.php" class="btn btn-primary" style="font-size:1rem;padding:.9rem 2.25rem">
        🚀 Alışverişe Başla
      </a>
      <a href="/ozel-siparis.php" class="btn btn-outline" style="font-size:1rem;padding:.9rem 2.25rem">
        📁 Dosya Yükle & Fiyat Al
      </a>
    </div>
  </div>
</section>

<style>
.reveal { opacity:0; transform:translateY(24px); transition:opacity .65s ease,transform .65s ease; }
.reveal.revealed { opacity:1; transform:none; }
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
