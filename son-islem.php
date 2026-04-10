<?php
$pageTitle = 'Son İşlem Hizmetleri';
require_once __DIR__ . '/includes/header.php';
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container" style="max-width:860px">
    <span class="section-tag">▸ SON İŞLEM</span>
    <h1 class="section-title">Profesyonel <span>Son İşlem</span></h1>
    <p class="section-sub" style="margin-bottom:3rem">Ham baskıyı bitmiş ürüne dönüştürün. Boya, vernik, taşlama ve özel kaplamalar.</p>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.5rem;margin-bottom:3rem">
      <?php
      $hizmetler = [
        ['🎨','Boyama & Boylama','Akrilik, epoksi veya püskürtme boya ile tek veya çok renk kaplama. UV dayanımlı laklar.','feat-blue'],
        ['🪚','Taşlama & Zımparalama','Katman izlerini gidermek için kademe kademe zımparalama ve yüzey pürüzsüzleştirme.','feat-orange'],
        ['💧','Aseton Banyosu (ABS)','ABS baskılar için erimiş aseton ile kimyasal yüzey parlatma. Ayna gibi pürüzsüz sonuç.','feat-purple'],
        ['⚗️','Reçine Kaplama','UV reçine ile kaplama ve parlama — figürler ve dekoratif parçalar için.','feat-green'],
        ['🔩','Delik & Diş Açma','Montaj için ölçülü delik genişletme, M serisi vida diş açma.','feat-blue'],
        ['🧲','Metal Kaplama','Elektrokimyasal yöntemle nikel veya krom kaplama (özel sipariş).','feat-orange'],
      ];
      foreach ($hizmetler as [$ic,$t,$d,$cls]): ?>
      <div class="feature-card">
        <div class="feat-icon <?= $cls ?>"><?= $ic ?></div>
        <h3><?= e($t) ?></h3>
        <p><?= e($d) ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="card" style="padding:2rem;text-align:center">
      <p style="color:var(--muted);margin-bottom:1.5rem">Son işlem fiyatları ürün boyutu ve karmaşıklığına göre değişir. Teklif almak için bizimle iletişime geçin.</p>
      <a href="/ozel-siparis.php" class="btn btn-primary" style="margin-right:1rem">📁 Dosya Gönder & Teklif Al</a>
      <a href="/iletisim.php" class="btn btn-outline">📩 İletişim</a>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
