<?php
$pageTitle = 'Referanslar';
require_once __DIR__ . '/includes/header.php';
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container">
    <span class="section-tag">▸ REFERANSLAR</span>
    <h1 class="section-title">Müşterilerimiz <span>Ne Diyor?</span></h1>
    <p class="section-sub" style="margin-bottom:3rem">Binlerce memnun müşterimizden bazıları.</p>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;margin-bottom:4rem">
      <?php
      $yorumlar = [
        ['Ahmet Y.','Makine Mühendisi','PLA+ ile üretilen parçalar mükemmeldi. Toleranslar tam istediğim gibiydi.','⭐⭐⭐⭐⭐'],
        ['Selin K.','Mimar','Mimari maket kalitesi gerçekten etkileyiciydi. Hızlı teslimat için teşekkürler.','⭐⭐⭐⭐⭐'],
        ['Murat D.','Girişimci','Prototip üretiminde çok yardımcı oldular. Kesinlikle tekrar çalışacağım.','⭐⭐⭐⭐⭐'],
        ['Fatma A.','Tasarımcı','Reçine baskı kalitesi inanılmaz. Figürlerim kusursuz çıktı.','⭐⭐⭐⭐⭐'],
        ['Kemal Ş.','Fabrika Sahibi','Toplu sipariş için çok makul fiyat ve hızlı üretim. Harika hizmet.','⭐⭐⭐⭐☆'],
        ['Zeynep T.','Öğrenci','Tez projem için model ürettim. Çok detaylı ve kaliteliydi.','⭐⭐⭐⭐⭐'],
      ];
      foreach ($yorumlar as [$ad,$meslek,$yorum,$puan]): ?>
      <div class="card reveal">
        <div style="margin-bottom:1rem;font-size:1.1rem;letter-spacing:.05em"><?= $puan ?></div>
        <p style="color:var(--muted);font-size:.92rem;line-height:1.8;margin-bottom:1.25rem;font-style:italic">"<?= e($yorum) ?>"</p>
        <div style="display:flex;align-items:center;gap:.75rem">
          <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--purple));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.88rem;color:#fff;flex-shrink:0">
            <?= mb_substr($ad, 0, 1) ?>
          </div>
          <div>
            <div style="font-weight:600;font-size:.92rem"><?= e($ad) ?></div>
            <div style="font-size:.8rem;color:var(--muted)"><?= e($meslek) ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center;background:linear-gradient(135deg,rgba(14,165,233,.08),rgba(139,92,246,.08));border:1px solid var(--border);border-radius:var(--radius2);padding:3rem 2rem">
      <h2 class="section-title" style="margin-bottom:1rem">Siz de Müşterimiz <span>Olun</span></h2>
      <p style="color:var(--muted);margin-bottom:2rem">Kaliteli üretim, hızlı teslimat, uygun fiyat.</p>
      <a href="/urunler.php" class="btn btn-primary">🚀 Alışverişe Başla</a>
    </div>
  </div>
</section>

<style>.reveal{opacity:0;transform:translateY(20px);transition:opacity .6s ease,transform .6s ease}.reveal.revealed{opacity:1;transform:none}</style>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
