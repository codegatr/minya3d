<?php $adminTitle = 'Materyaller'; require_once __DIR__ . '/includes/header.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $id     = (int)($_POST['id'] ?? 0);
    $baslik = trim($_POST['baslik'] ?? '');
    $renk   = trim($_POST['renk'] ?? '#0EA5E9');
    $aktif  = isset($_POST['aktif']) ? 1 : 0;
    if ($baslik) {
        if ($id > 0) DB::update('mn_materyaller', compact('baslik','renk','aktif'), 'id=?', [$id]);
        else         DB::insert('mn_materyaller', compact('baslik','renk','aktif'));
        flash('ok', 'Kaydedildi.');
    }
    redirect('/admin/materyaller.php');
}
if (isset($_GET['sil']) && csrfCheck()) {
    DB::q("DELETE FROM mn_materyaller WHERE id=?", [(int)$_GET['sil']]);
    redirect('/admin/materyaller.php');
}
$materyaller = DB::all("SELECT * FROM mn_materyaller ORDER BY baslik");
$duzenle     = isset($_GET['id']) ? DB::row("SELECT * FROM mn_materyaller WHERE id=?", [(int)$_GET['id']]) : null;
$ok = getFlash('ok');
?>
<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>
<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem">
  <div class="card" style="padding:0">
    <div class="card-header" style="padding:1.25rem 1.5rem"><span class="card-title">Materyaller</span></div>
    <div class="table-wrap">
      <table class="admin-table">
        <thead><tr><th>Renk</th><th>Materyal</th><th>Durum</th><th>İşlem</th></tr></thead>
        <tbody>
          <?php foreach ($materyaller as $m): ?>
          <tr>
            <td><span style="display:inline-block;width:20px;height:20px;border-radius:50%;background:<?= e($m['renk']) ?>"></span></td>
            <td><?= e($m['baslik']) ?></td>
            <td><span class="badge <?= $m['aktif']?'badge-green':'badge-gray' ?>"><?= $m['aktif']?'Aktif':'Pasif' ?></span></td>
            <td>
              <div style="display:flex;gap:.4rem">
                <a href="/admin/materyaller.php?id=<?= $m['id'] ?>" class="btn btn-outline btn-sm btn-icon">✏️</a>
                <a href="/admin/materyaller.php?sil=<?= $m['id'] ?>&csrf=<?= csrf() ?>" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Silinsin mi?')">🗑️</a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card">
    <div class="card-header"><span class="card-title"><?= $duzenle?'Düzenle':'Yeni Materyal' ?></span></div>
    <form method="POST">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">
      <?php if ($duzenle): ?><input type="hidden" name="id" value="<?= $duzenle['id'] ?>"><?php endif; ?>
      <div class="form-group"><label class="form-label">Materyal Adı</label><input type="text" name="baslik" class="form-control" value="<?= e($duzenle['baslik']??'') ?>" required></div>
      <div class="form-group"><label class="form-label">Renk</label><input type="color" name="renk" class="form-control" style="height:46px" value="<?= e($duzenle['renk']??'#0EA5E9') ?>"></div>
      <div class="form-group"><label class="form-check"><input type="checkbox" name="aktif" value="1" <?= ($duzenle['aktif']??1)?'checked':'' ?>> Aktif</label></div>
      <div style="display:flex;gap:.75rem">
        <button type="submit" class="btn btn-primary"><?= $duzenle?'💾 Güncelle':'➕ Ekle' ?></button>
        <?php if ($duzenle): ?><a href="/admin/materyaller.php" class="btn btn-outline">İptal</a><?php endif; ?>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
