<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';

$q = trim($_GET['q'] ?? '');
$pageTitle = $q ? "\"" . e($q) . "\" – Arama Sonuçları" : 'Ürün Arama';
$pageDesc  = $q
    ? "\"$q\" için Minya 3D 3D baskı ürünleri arama sonuçları. PLA+ materyal, Bambu Lab A1 Combo kalitesi."
    : 'Minya 3D ürün kataloğunda arama yapın. 130+ PLA+ 3D baskı ürünü arasında ihtiyacınızı bulun.';

// Arama sonuç sayfaları index'lenmemeli
if ($q) SEO::noindex();require_once __DIR__ . '/includes/header.php';

$urunler = [];
$total   = 0;
if ($q !== '') {
    $like    = "%$q%";
    $total   = (int)DB::row("SELECT COUNT(*) AS c FROM mn_urunler WHERE aktif=1 AND (baslik LIKE ? OR aciklama LIKE ? OR materyal LIKE ?)", [$like,$like,$like])['c'];
    $urunler = DB::all(
        "SELECT u.*, k.baslik AS kat FROM mn_urunler u LEFT JOIN mn_kategoriler k ON k.id=u.kategori_id WHERE u.aktif=1 AND (u.baslik LIKE ? OR u.aciklama LIKE ? OR u.materyal LIKE ?) ORDER BY u.vitrin DESC, u.id DESC LIMIT 24",
        [$like,$like,$like]
    );
}
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container">
    <!-- Arama kutusu -->
    <div style="max-width:600px;margin:0 auto 3rem">
      <h1 class="section-title" style="text-align:center;margin-bottom:1.5rem">
        <?= $q ? 'Sonuçlar: <span>"'.e($q).'"</span>' : 'Ürün <span>Ara</span>' ?>
      </h1>
      <form method="GET" style="display:flex;gap:.75rem">
        <input type="text" name="q" value="<?= e($q) ?>" placeholder="Ürün, materyal, kategori..." class="form-control" style="font-size:1rem" autofocus>
        <button type="submit" class="btn btn-primary">🔍</button>
      </form>
    </div>

    <?php if ($q !== ''): ?>
      <p style="color:var(--muted);font-size:.9rem;margin-bottom:1.5rem">
        <strong><?= $total ?></strong> sonuç bulundu
      </p>

      <?php if (empty($urunler)): ?>
      <div style="text-align:center;padding:4rem;background:var(--card);border:1px solid var(--border);border-radius:var(--radius2)">
        <div style="font-size:3rem;margin-bottom:1rem">🔍</div>
        <h3 style="margin-bottom:.75rem">Sonuç Bulunamadı</h3>
        <p style="color:var(--muted);margin-bottom:1.5rem">Farklı anahtar kelimeler deneyin veya kategorilere göz atın.</p>
        <a href="/urunler.php" class="btn btn-primary">Tüm Ürünler →</a>
      </div>
      <?php else: ?>
      <div class="products-grid">
        <?php foreach ($urunler as $u): ?>
        <div class="product-card">
          <a href="/urun/<?= e($u['slug']) ?>" class="product-thumb" style="display:block">
            <?php if ($u['gorsel']): ?><img src="<?= UPLOAD_URL ?>urunler/<?= e($u['gorsel']) ?>" alt="<?= e($u['baslik']) ?>">
            <?php else: ?><span class="product-thumb-placeholder">📦</span><?php endif; ?>
          </a>
          <div class="product-body">
            <span class="product-material"><?= e($u['materyal'] ?: $u['kat']) ?></span>
            <a href="/urun/<?= e($u['slug']) ?>" class="product-title"><?= e($u['baslik']) ?></a>
            <div class="product-footer">
              <div class="product-price"><?= para($u['indirim_fiyat']>0?$u['indirim_fiyat']:$u['fiyat']) ?></div>
              <button class="add-to-cart" data-id="<?= $u['id'] ?>">+</button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
