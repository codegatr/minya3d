<?php
$pageTitle = 'Dosya Yükle & Fiyat Al';
$pageDesc  = 'STL veya 3MF dosyani yukle, 24 saat icerisinde ucretsiz 3D baski fiyat teklifi al.';
require_once __DIR__ . '/includes/header.php';
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container" style="max-width:700px;text-align:center">
    <span class="section-tag">▸ HIZLI FİYAT</span>
    <h1 class="section-title">Dosyanızı Yükleyin,<br><span>Fiyat Alın</span></h1>
    <p style="color:var(--muted);font-size:1.05rem;line-height:1.8;margin-bottom:3rem">
      STL, OBJ veya 3MF dosyanızı gönderin. 24 saat içinde materyal seçenekleri,
      üretim süresi ve fiyat teklifini e-posta ile iletelim.
    </p>

    <div style="background:var(--card);border:2px dashed rgba(14,165,233,.35);border-radius:20px;padding:3rem 2rem;margin-bottom:2rem;position:relative">
      <div style="font-size:4rem;margin-bottom:1rem">📁</div>
      <h3 style="font-family:'Orbitron',sans-serif;font-size:1.1rem;margin-bottom:.75rem">STL / OBJ / 3MF / STEP</h3>
      <p style="color:var(--muted);font-size:.9rem;margin-bottom:1.5rem">Desteklenen formatlar: .stl .obj .3mf .step .iges .zip</p>
      <a href="/ozel-siparis.php" class="btn btn-primary" style="font-size:1.05rem;padding:.9rem 2.5rem">
        📤 Dosyayı Yükle →
      </a>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-top:2rem">
      <?php
      $adimlar = [['📁','Dosya Gönderin','STL veya 3MF'],['💬','Teklif Alın','24 saat içinde'],['🖨️','Onaylayın','Baskı başlasın']];
      foreach ($adimlar as [$i,$t,$s]): ?>
      <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius2);padding:1.25rem">
        <div style="font-size:1.8rem;margin-bottom:.6rem"><?= $i ?></div>
        <div style="font-weight:600;font-size:.95rem;margin-bottom:.3rem"><?= $t ?></div>
        <div style="font-size:.82rem;color:var(--muted)"><?= $s ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="margin-top:2rem">
      <a href="/urunler.php" style="color:var(--muted);font-size:.9rem">ya da hazır ürünlere göz atın →</a>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
