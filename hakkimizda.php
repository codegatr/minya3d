<?php
$pageTitle = 'Hakkımızda';
require_once __DIR__ . '/includes/header.php';
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container" style="max-width:900px">
    <span class="section-tag">▸ HAKKIMIZDA</span>
    <h1 class="section-title">Minya 3D <span>Kimdir?</span></h1>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;margin-top:2.5rem;align-items:center">
      <div>
        <p style="color:var(--muted);line-height:1.9;margin-bottom:1.25rem">
          <strong style="color:var(--text)">Minya 3D</strong>, Türkiye'nin önde gelen 3D baskı hizmet ve ürün platformudur.
          Endüstriyel kalitede <strong style="color:var(--blue)">Bambu Lab A1 Combo</strong> yazıcılarımızla
          PLA, ABS, PETG, TPU, Reçine ve daha pek çok materyalle üretim yapıyoruz.
        </p>
        <p style="color:var(--muted);line-height:1.9;margin-bottom:1.25rem">
          Endüstriyel parçalardan mimari maketlere, sanat eserlerinden medikal modellere kadar
          geniş bir ürün yelpazesiyle müşterilerimize hizmet veriyoruz.
          Tüm ürünlerimiz %100 yerli üretimdir ve Konya'dan Türkiye'nin dört bir yanına gönderim yapıyoruz.
        </p>
        <p style="color:var(--muted);line-height:1.9">
          Özel tasarımlarınız için STL, OBJ veya 3MF dosyalarınızı bize gönderin;
          saniyeler içinde fiyat teklifi alın, 24-48 saat içinde üretiminiz tamamlansın.
        </p>
      </div>
      <div>
        <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius2);padding:2rem">
          <?php
          $vals = [['🏭','Bambu Lab A1 Combo','Tam otomatik, 4 renkli baskı'],['⚡','48 Saat','Ortalama üretim süresi'],['🎨','12+ Materyal','PLA, ABS, PETG, Reçine...'],['📦','Türkiye\'ye Kargo','Her ile güvenli teslimat']];
          foreach ($vals as [$icon,$title,$sub]): ?>
          <div style="display:flex;align-items:center;gap:1rem;padding:.85rem 0;border-bottom:1px solid rgba(14,165,233,.08)">
            <div style="width:44px;height:44px;border-radius:10px;background:rgba(14,165,233,.1);border:1px solid rgba(14,165,233,.2);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0"><?= $icon ?></div>
            <div><div style="font-weight:600"><?= $title ?></div><div style="font-size:.85rem;color:var(--muted)"><?= $sub ?></div></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div style="margin-top:3rem;text-align:center">
      <a href="/urunler.php" class="btn btn-primary" style="margin-right:1rem">🚀 Ürünleri İncele</a>
      <a href="/iletisim.php" class="btn btn-outline">📩 İletişime Geç</a>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
