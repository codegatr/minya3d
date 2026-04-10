<?php
$pageTitle = 'Kategoriler';
require_once __DIR__ . '/includes/header.php';
$kategoriler = DB::all("SELECT k.*, (SELECT COUNT(*) FROM mn_urunler WHERE kategori_id=k.id AND aktif=1) AS adet FROM mn_kategoriler k WHERE k.aktif=1 ORDER BY k.sira, k.id");
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container">
    <span class="section-tag">▸ KATEGORİLER</span>
    <h1 class="section-title">Tüm <span>Kategoriler</span></h1>
    <div class="cat-grid" style="margin-top:2rem;grid-template-columns:repeat(auto-fill,minmax(200px,1fr))">
      <?php foreach ($kategoriler as $k): ?>
      <a href="/urunler.php?kat=<?= e($k['slug']) ?>" class="cat-card">
        <div class="cat-icon-wrap"><?= $k['ikon'] ?: '📦' ?></div>
        <div class="cat-card-title"><?= e($k['baslik']) ?></div>
        <div class="cat-card-count"><?= $k['adet'] ?> ürün</div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
