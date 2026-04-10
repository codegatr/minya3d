<?php $adminTitle = 'Kategoriler'; require_once __DIR__ . '/includes/header.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $id     = (int)($_POST['id'] ?? 0);
    $baslik = trim($_POST['baslik'] ?? '');
    $ikon   = trim($_POST['ikon'] ?? '📦');
    $sira   = (int)($_POST['sira'] ?? 0);
    $aktif  = isset($_POST['aktif']) ? 1 : 0;
    $sl     = slug($_POST['slug'] ?? $baslik);
    if ($baslik) {
        if ($id > 0) DB::update('mn_kategoriler', compact('baslik','ikon','sira','aktif','sl'), 'id=?', [$id]);
        else         DB::insert('mn_kategoriler', ['baslik'=>$baslik,'slug'=>$sl,'ikon'=>$ikon,'sira'=>$sira,'aktif'=>$aktif]);
        flash('ok', $id ? 'Kategori güncellendi.' : 'Kategori eklendi.');
    }
    redirect('/admin/kategoriler.php');
}
if (isset($_GET['sil']) && csrfCheck()) {
    DB::q("DELETE FROM mn_kategoriler WHERE id=?", [(int)$_GET['sil']]);
    flash('ok', 'Kategori silindi.');
    redirect('/admin/kategoriler.php');
}
if (isset($_GET['toggle']) && csrfCheck()) {
    $kat = DB::row("SELECT aktif FROM mn_kategoriler WHERE id=?", [(int)$_GET['toggle']]);
    DB::q("UPDATE mn_kategoriler SET aktif=? WHERE id=?", [$kat ? (int)!$kat['aktif'] : 1, (int)$_GET['toggle']]);
    redirect('/admin/kategoriler.php');
}

$kategoriler = DB::all("SELECT *, (SELECT COUNT(*) FROM mn_urunler WHERE kategori_id=mn_kategoriler.id) AS urun_sayisi FROM mn_kategoriler ORDER BY sira, id");
$duzenle     = isset($_GET['id']) ? DB::row("SELECT * FROM mn_kategoriler WHERE id=?", [(int)$_GET['id']]) : null;

$ok = getFlash('ok');
?>
<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem">

  <div class="card" style="padding:0">
    <div class="card-header" style="padding:1.25rem 1.5rem"><span class="card-title">Kategoriler</span></div>
    <div class="table-wrap">
      <table class="admin-table">
        <thead><tr><th>Sıra</th><th>İkon</th><th>Kategori</th><th>Ürün</th><th>Durum</th><th>İşlem</th></tr></thead>
        <tbody>
          <?php foreach ($kategoriler as $k): ?>
          <tr>
            <td style="color:var(--faint)"><?= $k['sira'] ?></td>
            <td style="font-size:1.4rem"><?= e($k['ikon']) ?></td>
            <td><div style="font-weight:500"><?= e($k['baslik']) ?></div><div style="font-size:.75rem;color:var(--muted)"><?= e($k['slug']) ?></div></td>
            <td><span class="badge badge-blue"><?= $k['urun_sayisi'] ?></span></td>
            <td><a href="/admin/kategoriler.php?toggle=<?= $k['id'] ?>&csrf=<?= csrf() ?>" class="badge <?= $k['aktif'] ? 'badge-green':'badge-gray' ?>"><?= $k['aktif']?'Aktif':'Pasif' ?></a></td>
            <td>
              <div style="display:flex;gap:.4rem">
                <a href="/admin/kategoriler.php?id=<?= $k['id'] ?>" class="btn btn-outline btn-sm btn-icon">✏️</a>
                <a href="/admin/kategoriler.php?sil=<?= $k['id'] ?>&csrf=<?= csrf() ?>" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Silinsin mi?')">🗑️</a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><span class="card-title"><?= $duzenle ? 'Kategori Düzenle' : 'Yeni Kategori' ?></span></div>
    <form method="POST">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">
      <?php if ($duzenle): ?><input type="hidden" name="id" value="<?= $duzenle['id'] ?>"><?php endif; ?>
      <div class="form-group"><label class="form-label">Kategori Adı *</label><input type="text" name="baslik" class="form-control" value="<?= e($duzenle['baslik']??'') ?>" required></div>
      <div class="form-group"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" value="<?= e($duzenle['slug']??'') ?>"></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">İkon (emoji)</label><input type="text" name="ikon" class="form-control" value="<?= e($duzenle['ikon']??'📦') ?>"></div>
        <div class="form-group"><label class="form-label">Sıra</label><input type="number" name="sira" class="form-control" value="<?= $duzenle['sira']??0 ?>"></div>
      </div>
      <div class="form-group"><label class="form-check"><input type="checkbox" name="aktif" value="1" <?= ($duzenle['aktif']??1)?'checked':'' ?>> Aktif</label></div>
      <div style="display:flex;gap:.75rem">
        <button type="submit" class="btn btn-primary"><?= $duzenle?'💾 Güncelle':'➕ Ekle' ?></button>
        <?php if ($duzenle): ?><a href="/admin/kategoriler.php" class="btn btn-outline">İptal</a><?php endif; ?>
      </div>
    </form>
  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
