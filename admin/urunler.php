<?php $adminTitle = 'Ürünler'; require_once __DIR__ . '/includes/header.php'; ?>

<?php
// Silme işlemi
if (isset($_GET['sil']) && csrfCheck()) {
    $id = (int)$_GET['sil'];
    $u  = DB::row("SELECT gorsel FROM mn_urunler WHERE id=?", [$id]);
    if ($u) {
        if ($u['gorsel']) @unlink(UPLOAD_DIR . 'urunler/' . $u['gorsel']);
        DB::q("DELETE FROM mn_urunler WHERE id=?", [$id]);
        flash('ok', 'Ürün silindi.');
    }
    redirect('/admin/urunler.php');
}

$page    = max(1, (int)($_GET['sayfa'] ?? 1));
$perPage = 20;
$offset  = ($page - 1) * $perPage;
$where   = ['1=1'];
$params  = [];

if (!empty($_GET['q'])) { $where[] = 'baslik LIKE ?'; $params[] = '%'.$_GET['q'].'%'; }
if (!empty($_GET['kat'])) { $where[] = 'kategori_id = ?'; $params[] = (int)$_GET['kat']; }
if (isset($_GET['aktif']) && $_GET['aktif'] !== '') { $where[] = 'aktif = ?'; $params[] = (int)$_GET['aktif']; }

$whereStr = implode(' AND ', $where);
$total    = (int)DB::row("SELECT COUNT(*) AS c FROM mn_urunler WHERE $whereStr", $params)['c'];
$pages    = (int)ceil($total / $perPage);
$urunler  = DB::all(
    "SELECT u.*, k.baslik AS kat FROM mn_urunler u LEFT JOIN mn_kategoriler k ON k.id=u.kategori_id WHERE $whereStr ORDER BY u.id DESC LIMIT $perPage OFFSET $offset",
    $params
);
$kategoriler = DB::all("SELECT id, baslik FROM mn_kategoriler WHERE aktif=1 ORDER BY baslik");

$ok  = getFlash('ok');
$err = getFlash('err');
?>

<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error">✗ <?= e($err) ?></div><?php endif; ?>

<div class="search-bar">
  <form method="GET" style="display:flex;gap:.6rem;flex:1;flex-wrap:wrap">
    <input type="text" name="q" value="<?= e($_GET['q']??'') ?>" placeholder="Ürün ara..." class="form-control search-input">
    <select name="kat" class="form-control" style="width:auto">
      <option value="">Tüm Kategoriler</option>
      <?php foreach ($kategoriler as $k): ?>
      <option value="<?= $k['id'] ?>" <?= (($_GET['kat']??'') == $k['id']) ? 'selected' : '' ?>><?= e($k['baslik']) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="aktif" class="form-control" style="width:auto">
      <option value="">Tüm Durum</option>
      <option value="1" <?= (($_GET['aktif']??'') === '1') ? 'selected' : '' ?>>Aktif</option>
      <option value="0" <?= (($_GET['aktif']??'') === '0') ? 'selected' : '' ?>>Pasif</option>
    </select>
    <button type="submit" class="btn btn-blue">Filtrele</button>
    <a href="/admin/urunler.php" class="btn btn-outline">Sıfırla</a>
  </form>
  <a href="/admin/urun-ekle.php" class="btn btn-primary">➕ Yeni Ürün</a>
</div>

<div class="card" style="padding:0">
  <div class="table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th><th>Görsel</th><th>Ürün Adı</th><th>Kategori</th>
          <th>Fiyat</th><th>Stok</th><th>Durum</th><th>Vitrin</th><th>İşlem</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($urunler as $u): ?>
        <tr>
          <td style="color:var(--faint)"><?= $u['id'] ?></td>
          <td>
            <?php if ($u['gorsel']): ?>
            <img src="<?= UPLOAD_URL ?>urunler/<?= e($u['gorsel']) ?>" style="width:44px;height:44px;object-fit:cover;border-radius:8px">
            <?php else: ?>
            <div style="width:44px;height:44px;border-radius:8px;background:var(--navy3);display:flex;align-items:center;justify-content:center">📦</div>
            <?php endif; ?>
          </td>
          <td>
            <div style="font-weight:500"><?= e($u['baslik']) ?></div>
            <div style="font-size:.78rem;color:var(--muted)"><?= e($u['slug']) ?></div>
          </td>
          <td style="color:var(--muted)"><?= e($u['kat'] ?? '–') ?></td>
          <td>
            <div style="font-family:'Orbitron',sans-serif;font-size:.9rem;color:var(--orange)">₺<?= number_format($u['fiyat'],2,',','.') ?></div>
            <?php if ($u['indirim_fiyat'] > 0): ?>
            <div style="font-size:.75rem;color:var(--green)">₺<?= number_format($u['indirim_fiyat'],2,',','.') ?></div>
            <?php endif; ?>
          </td>
          <td>
            <span class="badge <?= $u['stok'] > 0 ? 'badge-green' : 'badge-red' ?>"><?= $u['stok'] ?></span>
          </td>
          <td><span class="badge <?= $u['aktif'] ? 'badge-green' : 'badge-gray' ?>"><?= $u['aktif'] ? 'Aktif' : 'Pasif' ?></span></td>
          <td><span class="badge <?= $u['vitrin'] ? 'badge-blue' : 'badge-gray' ?>"><?= $u['vitrin'] ? '★' : '–' ?></span></td>
          <td>
            <div style="display:flex;gap:.4rem">
              <a href="/admin/urun-ekle.php?id=<?= $u['id'] ?>" class="btn btn-outline btn-sm btn-icon" title="Düzenle">✏️</a>
              <a href="/urun/<?= e($u['slug']) ?>" target="_blank" class="btn btn-outline btn-sm btn-icon" title="Görüntüle">👁️</a>
              <a href="/admin/urunler.php?sil=<?= $u['id'] ?>&csrf=<?= csrf() ?>"
                 class="btn btn-danger btn-sm btn-icon"
                 onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?')" title="Sil">🗑️</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($urunler)): ?>
        <tr><td colspan="9" style="text-align:center;padding:2rem;color:var(--muted)">Ürün bulunamadı.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if ($pages > 1): ?>
<div class="pagination" style="margin-top:1rem">
  <?php for ($i=1;$i<=$pages;$i++): ?>
  <a href="?<?= http_build_query(array_merge($_GET,['sayfa'=>$i])) ?>"
     class="page-btn <?= $i===$page?'active':'' ?>"><?= $i ?></a>
  <?php endfor; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
