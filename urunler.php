<?php
$pageTitle = 'Ürünler';
$pageDesc  = 'Minya 3D urun katalogu: Ev, ofis, dekorasyon, oyun, egitim. PLA+ materyal, Bambu Lab A1 Combo kalitesi. Turkiye hizli kargo.';
require_once __DIR__ . '/includes/header.php';

$page    = max(1, (int)($_GET['sayfa'] ?? 1));
$perPage = 12;
$offset  = ($page - 1) * $perPage;

$where   = ['u.aktif = 1'];
$params  = [];

if (!empty($_GET['kat'])) {
    $kat = DB::row("SELECT id, baslik FROM mn_kategoriler WHERE slug=? AND aktif=1", [$_GET['kat']]);
    if ($kat) { $where[] = 'u.kategori_id = ?'; $params[] = $kat['id']; }
}
if (!empty($_GET['materyal'])) {
    $where[] = 'u.materyal = ?'; $params[] = $_GET['materyal'];
}
if (!empty($_GET['q'])) {
    $where[] = '(u.baslik LIKE ? OR u.aciklama LIKE ?)';
    $params[] = '%'.$_GET['q'].'%'; $params[] = '%'.$_GET['q'].'%';
}

$sort = match($_GET['siralama'] ?? '') {
    'fiyat_asc'  => 'fiyat ASC',
    'fiyat_desc' => 'fiyat DESC',
    'yeni'       => 'u.id DESC',
    default      => 'u.vitrin DESC, u.id DESC',
};

$whereStr = implode(' AND ', $where);
$total    = (int)DB::row("SELECT COUNT(*) AS c FROM mn_urunler u WHERE $whereStr", $params)['c'];
$pages    = (int)ceil($total / $perPage);
$urunler  = DB::all(
    "SELECT u.*, k.baslik AS kat_baslik FROM mn_urunler u
     LEFT JOIN mn_kategoriler k ON k.id=u.kategori_id
     WHERE $whereStr ORDER BY $sort LIMIT $perPage OFFSET $offset",
    [...$params, $perPage, $offset]
);

$kategoriler  = DB::all("SELECT id, baslik, slug FROM mn_kategoriler WHERE aktif=1 ORDER BY sira");
$materyaller  = DB::all("SELECT DISTINCT materyal FROM mn_urunler WHERE aktif=1 AND materyal != '' ORDER BY materyal");

$pageTitle = (!empty($kat) ? $kat['baslik'].' – ' : '') . 'Ürünler';
?>

<div style="margin-top:70px"></div>
<div class="container" style="padding:2rem">
  <div class="breadcrumb">
    <a href="/">Ana Sayfa</a><span>/</span>
    <span class="current">Ürünler</span>
  </div>

  <div style="display:grid;grid-template-columns:240px 1fr;gap:2.5rem;align-items:start">

    <!-- FİLTRE PANEL -->
    <aside class="filter-panel">
      <div class="filter-box">
        <h4>Kategoriler</h4>
        <ul>
          <li><a href="/urunler.php" class="<?= empty($_GET['kat']) ? 'active' : '' ?>">Tümü <span>(<?= $total ?>)</span></a></li>
          <?php foreach ($kategoriler as $k): ?>
          <li>
            <a href="/urunler.php?kat=<?= e($k['slug']) ?>" class="<?= ($_GET['kat'] ?? '') === $k['slug'] ? 'active' : '' ?>">
              <?= e($k['baslik']) ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php if (!empty($materyaller)): ?>
      <div class="filter-box" style="margin-top:1.5rem">
        <h4>Materyal</h4>
        <ul>
          <?php foreach ($materyaller as $m): ?>
          <li>
            <a href="?<?= http_build_query(array_merge($_GET,['materyal'=>$m['materyal']])) ?>"
               class="<?= ($_GET['materyal'] ?? '') === $m['materyal'] ? 'active' : '' ?>">
              <?= e($m['materyal']) ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
    </aside>

    <!-- ÜRÜN LİSTESİ -->
    <main>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
        <span style="color:var(--muted);font-size:.9rem"><?= $total ?> ürün bulundu</span>
        <select class="form-control" style="width:auto" onchange="location='?<?= http_build_query(array_merge($_GET,['siralama'=>''])) ?>&siralama='+this.value">
          <option value="">Önerilen Sıralama</option>
          <option value="yeni" <?= ($_GET['siralama']??'')=='yeni'?'selected':'' ?>>En Yeni</option>
          <option value="fiyat_asc" <?= ($_GET['siralama']??'')=='fiyat_asc'?'selected':'' ?>>Fiyat: Düşük → Yüksek</option>
          <option value="fiyat_desc" <?= ($_GET['siralama']??'')=='fiyat_desc'?'selected':'' ?>>Fiyat: Yüksek → Düşük</option>
        </select>
      </div>

      <?php if (empty($urunler)): ?>
      <div class="alert alert-info">Bu filtreyle ürün bulunamadı.</div>
      <?php else: ?>
      <div class="products-grid">
        <?php foreach ($urunler as $u): ?>
        <div class="product-card">
          <a href="/urun/<?= e($u['slug']) ?>" class="product-thumb" style="display:block">
            <?php if ($u['gorsel']): ?>
            <img src="<?= UPLOAD_URL ?>urunler/<?= e($u['gorsel']) ?>" alt="<?= e($u['baslik']) ?>">
            <?php else: ?>
            <span class="product-thumb-placeholder">📦</span>
            <?php endif; ?>
            <div class="product-badge-wrap">
              <?php if ($u['vitrin']): ?><span class="pbadge pbadge-new">VİTRİN</span><?php endif; ?>
              <?php if ($u['indirim_fiyat'] > 0): ?>
              <span class="pbadge pbadge-sale">
                -<?= round((1 - $u['indirim_fiyat']/$u['fiyat'])*100) ?>%
              </span>
              <?php endif; ?>
            </div>
          </a>
          <div class="product-body">
            <span class="product-material"><?= e($u['materyal'] ?: $u['kat_baslik']) ?></span>
            <a href="/urun/<?= e($u['slug']) ?>" class="product-title"><?= e($u['baslik']) ?></a>
            <div class="product-footer">
              <div class="product-price">
                <?= para($u['indirim_fiyat'] > 0 ? $u['indirim_fiyat'] : $u['fiyat']) ?>
                <?php if ($u['indirim_fiyat'] > 0): ?>
                <small style="text-decoration:line-through;color:var(--faint)"><?= para($u['fiyat']) ?></small>
                <?php endif; ?>
              </div>
              <button class="add-to-cart" data-id="<?= $u['id'] ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- PAGİNATION -->
      <?php if ($pages > 1): ?>
      <div class="pagination">
        <?php for ($i=1; $i<=$pages; $i++): ?>
        <a href="?<?= http_build_query(array_merge($_GET,['sayfa'=>$i])) ?>"
           class="page-btn <?= $i===$page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
      <?php endif; ?>
    </main>

  </div>
</div>

<style>
.filter-panel { position:sticky;top:90px; }
.filter-box { background:var(--card);border:1px solid var(--border);border-radius:var(--radius2);padding:1.25rem; }
.filter-box h4 { font-size:.82rem;font-weight:700;letter-spacing:.12em;color:var(--blue);text-transform:uppercase;margin-bottom:.9rem; }
.filter-box ul li { margin-bottom:.4rem; }
.filter-box a { display:flex;justify-content:space-between;color:var(--muted);font-size:.9rem;padding:.3rem .5rem;border-radius:6px;transition:all .2s; }
.filter-box a:hover,.filter-box a.active { color:var(--text);background:rgba(14,165,233,.08); }
.filter-box a span { color:var(--faint);font-size:.82rem; }
@media(max-width:900px){
  .filter-panel { display:none; }
  [style*="grid-template-columns:240px"] { grid-template-columns:1fr !important; }
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
