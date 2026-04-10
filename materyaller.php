<?php
$pageTitle = 'Materyaller';
$pageDesc  = 'Minya 3D materyalleri: PLA+, ABS, PETG, TPU, Recine. Projenize uygun materyal secin.';
require_once __DIR__ . '/includes/header.php';
$materyaller = DB::all("SELECT * FROM mn_materyaller WHERE aktif=1 ORDER BY baslik");
$bilgi = [
    'PLA+' => ['Çevre dostu, kolay baskı, geniş renk seçeneği. Prototipler ve iç mekan kullanımı için ideal.','70°C','★★★★☆','★★★★★'],
    'ABS'  => ['Yüksek dayanıklılık, ısı direnci. Dış mekan ve mekanik parçalar için uygun.','100°C','★★★★★','★★★☆☆'],
    'PETG' => ['ABS ile PLA arası denge. Esnek, şeffaf seçenekler. Gıda teması için güvenli.','80°C','★★★★☆','★★★★☆'],
    'TPU (Esnek)' => ['Esnek, çarpma dirençli. Contalar, koruyucular ve wearable ürünler için.','60°C','★★★★☆','★★★☆☆'],
    'Reçine (SLA)' => ['Ultra yüksek detay. Dental, mücevher ve figür üretimi için mükemmel.','50°C','★★★★★','★★★★★'],
];
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container">
    <span class="section-tag">▸ MATERYALLER</span>
    <h1 class="section-title">Hangi <span>Materyal?</span></h1>
    <p class="section-sub" style="margin-bottom:3rem">Projenize en uygun filament veya reçineyi seçin. Emin değilseniz bize danışın.</p>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem">
      <?php foreach ($materyaller as $m):
        $bi = $bilgi[$m['baslik']] ?? ['','–','–','–'];
        [$acik,$isi,$dayan,$detay] = $bi;
      ?>
      <div class="feature-card">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem">
          <span style="display:inline-block;width:16px;height:16px;border-radius:50%;background:<?= e($m['renk']) ?>;flex-shrink:0"></span>
          <h3 style="font-size:1.1rem"><?= e($m['baslik']) ?></h3>
        </div>
        <?php if ($acik): ?><p style="color:var(--muted);font-size:.88rem;line-height:1.7;margin-bottom:1rem"><?= e($acik) ?></p><?php endif; ?>
        <?php if ($isi !== '–'): ?>
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;font-size:.78rem;color:var(--muted)">
          <span>🌡️ Isı direnci: <?= $isi ?></span>
          <span>💪 Dayanım: <?= $dayan ?></span>
          <span>🔬 Detay: <?= $detay ?></span>
        </div>
        <?php endif; ?>
        <a href="/urunler.php?materyal=<?= urlencode($m['baslik']) ?>" class="btn btn-outline btn-sm" style="margin-top:1rem">Bu Materyal ile Ürünler →</a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
