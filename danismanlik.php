<?php
$pageTitle = 'Danışmanlık';
require_once __DIR__ . '/includes/header.php';
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container" style="max-width:860px">
    <span class="section-tag">▸ DANIŞMANLIK</span>
    <h1 class="section-title">3D Baskı <span>Danışmanlığı</span></h1>
    <p class="section-sub" style="margin-bottom:3rem">Hangi materyal, hangi teknoloji, hangi boyut? Uzman ekibimiz doğru kararı vermenize yardımcı olur.</p>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.5rem;margin-bottom:3rem">
      <?php
      $hizmetler = [
        ['🎯','Materyal Seçimi','Projenizin gereksinimlerine göre en uygun filament veya reçineyi belirliyoruz.','feat-blue'],
        ['📐','Tasarım Optimizasyonu','STL dosyanızı baskı için optimize ediyoruz. Destek, infill ve yönlendirme önerileri.','feat-orange'],
        ['💰','Maliyet Analizi','Bütçenize en uygun üretim stratejisini birlikte planlıyoruz.','feat-purple'],
        ['🏭','Seri Üretim Planı','Toplu üretim için süreç ve kalite yönetim danışmanlığı.','feat-green'],
        ['🔬','Prototip → Ürün','Prototipten nihai ürüne geçiş sürecinde teknik destek.','feat-blue'],
        ['📋','Teknik Rapor','Yapısal analiz, tolerans ve dayanım test raporları.','feat-orange'],
      ];
      foreach ($hizmetler as [$ic,$t,$d,$cls]): ?>
      <div class="feature-card">
        <div class="feat-icon <?= $cls ?>"><?= $ic ?></div>
        <h3><?= e($t) ?></h3>
        <p><?= e($d) ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="background:linear-gradient(135deg,rgba(14,165,233,.08),rgba(139,92,246,.08));border:1px solid var(--border);border-radius:var(--radius2);padding:2.5rem;text-align:center">
      <h2 style="font-family:'Orbitron',sans-serif;font-size:1.4rem;margin-bottom:.75rem">Ücretsiz İlk Görüşme</h2>
      <p style="color:var(--muted);margin-bottom:2rem">Projenizi anlatın, size en uygun çözümü önerelim.</p>
      <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
        <a href="/iletisim.php" class="btn btn-primary">📩 İletişime Geç</a>
        <?php if ($wa = ayar('whatsapp','')): ?>
        <a href="https://wa.me/<?= e(preg_replace('/\D/','',$wa)) ?>" target="_blank" class="btn btn-outline">💬 WhatsApp</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
