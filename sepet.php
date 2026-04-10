<?php
$pageTitle = 'Sepetim';
$pageDesc  = '3D baski siparisini tamamla. Sepetimdeki urunleri incele ve guvenli odeme yap.';
require_once __DIR__ . '/includes/header.php';

$sepet    = $_SESSION['sepet'] ?? [];
$urunler  = [];
$toplamFi = 0;

if (!empty($sepet)) {
    $ids = array_keys($sepet);
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $rows = DB::all("SELECT id,baslik,slug,gorsel,fiyat,indirim_fiyat,stok FROM mn_urunler WHERE id IN ($in)", $ids);
    foreach ($rows as $r) {
        $adet  = $sepet[$r['id']];
        $birim = $r['indirim_fiyat'] > 0 ? $r['indirim_fiyat'] : $r['fiyat'];
        $r['adet'] = $adet;
        $r['birim'] = $birim;
        $r['toplam'] = $birim * $adet;
        $toplamFi   += $r['toplam'];
        $urunler[]   = $r;
    }
}
?>

<div style="margin-top:70px"></div>
<div class="container" style="padding:2rem">
  <div class="breadcrumb">
    <a href="/">Ana Sayfa</a><span>/</span>
    <span class="current">Sepetim</span>
  </div>
  <h1 class="section-title" style="margin-bottom:2rem">Sepetim</h1>

  <?php if (empty($urunler)): ?>
  <div style="text-align:center;padding:4rem;background:var(--card);border:1px solid var(--border);border-radius:var(--radius2)">
    <div style="font-size:4rem;margin-bottom:1rem">🛒</div>
    <h3 style="margin-bottom:.75rem">Sepetiniz Boş</h3>
    <p style="color:var(--muted);margin-bottom:2rem">Ürünleri keşfetmek için alışverişe başlayın.</p>
    <a href="/urunler.php" class="btn btn-primary">Ürünlere Gözat</a>
  </div>
  <?php else: ?>

  <div style="display:grid;grid-template-columns:1fr 320px;gap:2rem;align-items:start">
    <div>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Ürün</th><th>Birim Fiyat</th><th>Adet</th><th>Toplam</th><th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($urunler as $u): ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:1rem">
                <?php if ($u['gorsel']): ?>
                <img src="<?= UPLOAD_URL ?>urunler/<?= e($u['gorsel']) ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;border:1px solid var(--border)">
                <?php endif; ?>
                <a href="/urun/<?= e($u['slug']) ?>" style="font-weight:500"><?= e($u['baslik']) ?></a>
              </div>
            </td>
            <td><?= para($u['birim']) ?></td>
            <td>
              <div class="cart-qty">
                <button class="qty-btn" data-id="<?= $u['id'] ?>" data-action="azalt">−</button>
                <span class="qty-val"><?= $u['adet'] ?></span>
                <button class="qty-btn" data-id="<?= $u['id'] ?>" data-action="ekle">+</button>
              </div>
            </td>
            <td style="font-family:'Orbitron',sans-serif;color:var(--orange)"><?= para($u['toplam']) ?></td>
            <td>
              <button class="qty-btn" data-id="<?= $u['id'] ?>" data-action="sil" style="color:var(--red)">✕</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius2);padding:1.75rem;position:sticky;top:90px">
      <h3 style="margin-bottom:1.25rem">Sipariş Özeti</h3>
      <div style="display:flex;justify-content:space-between;margin-bottom:.6rem;color:var(--muted)">
        <span>Ara Toplam</span><span><?= para($toplamFi) ?></span>
      </div>
      <div style="display:flex;justify-content:space-between;margin-bottom:.6rem;color:var(--muted)">
        <span>Kargo</span><span style="color:var(--green)">Ücretsiz</span>
      </div>
      <div style="border-top:1px solid var(--border);padding-top:1rem;margin-top:1rem;display:flex;justify-content:space-between;font-family:'Orbitron',sans-serif;font-size:1.1rem">
        <span>Toplam</span><span style="color:var(--orange)"><?= para($toplamFi) ?></span>
      </div>
      <a href="/odeme.php" class="btn btn-primary btn-full" style="margin-top:1.5rem">
        Ödemeye Geç →
      </a>
      <a href="/urunler.php" class="btn btn-outline btn-full" style="margin-top:.75rem">
        Alışverişe Devam
      </a>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
