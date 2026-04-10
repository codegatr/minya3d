<?php $adminTitle = 'Blog Yazıları'; require_once __DIR__ . '/includes/header.php'; ?>
<?php
// Blog tablosu yoksa oluştur
DB::q("CREATE TABLE IF NOT EXISTS `mn_blog` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `baslik`     VARCHAR(200) NOT NULL,
  `slug`       VARCHAR(220) NOT NULL UNIQUE,
  `ozet`       TEXT,
  `icerik`     LONGTEXT,
  `kapak`      VARCHAR(200),
  `aktif`      TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

if (isset($_GET['sil']) && csrfCheck()) {
    DB::q("DELETE FROM mn_blog WHERE id=?", [(int)$_GET['sil']]);
    flash('ok','Yazı silindi.');
    redirect('/admin/blog.php');
}

$id      = (int)($_GET['id'] ?? 0);
$editing = $id > 0;
$yazi    = $editing ? DB::row("SELECT * FROM mn_blog WHERE id=?", [$id]) : null;
$hata = $ok2 = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $baslik = trim($_POST['baslik'] ?? '');
    $sl     = slug($_POST['slug'] ?? $baslik);
    $ozet   = trim($_POST['ozet'] ?? '');
    $icerik = $_POST['icerik'] ?? '';
    $aktif  = isset($_POST['aktif']) ? 1 : 0;
    if (!$baslik) { $hata = 'Başlık zorunludur.'; }
    else {
        $kapak = $yazi['kapak'] ?? '';
        if (!empty($_FILES['kapak']['name'])) {
            $yeni = uploadFile($_FILES['kapak'], UPLOAD_DIR . 'urunler/');
            if ($yeni) { if ($kapak) @unlink(UPLOAD_DIR.'urunler/'.$kapak); $kapak = $yeni; }
        }
        $data = compact('baslik','ozet','icerik','aktif','kapak') + ['slug' => $sl];
        if ($editing) { DB::update('mn_blog', $data, 'id=?', [$id]); $ok2 = 'Güncellendi.'; $yazi = DB::row("SELECT * FROM mn_blog WHERE id=?", [$id]); }
        else { $nId = DB::insert('mn_blog', $data + ['created_at'=>date('Y-m-d H:i:s')]); flash('ok','Eklendi.'); redirect('/admin/blog.php?id='.$nId); }
    }
}

$yazilar = DB::all("SELECT id,baslik,aktif,created_at FROM mn_blog ORDER BY id DESC LIMIT 30");
$ok = getFlash('ok');
?>
<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>
<?php if ($ok2): ?><div class="alert alert-success">✓ <?= e($ok2) ?></div><?php endif; ?>
<?php if ($hata): ?><div class="alert alert-error">✗ <?= e($hata) ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:280px 1fr;gap:1.5rem;align-items:start">
  <div class="card" style="padding:0">
    <div class="card-header" style="padding:1rem 1.25rem;display:flex;justify-content:space-between;align-items:center">
      <span class="card-title">Yazılar</span>
      <a href="/admin/blog.php" class="btn btn-outline btn-sm">+ Yeni</a>
    </div>
    <?php foreach ($yazilar as $y): ?>
    <div style="display:flex;align-items:center;justify-content:space-between;padding:.6rem 1.25rem;border-bottom:1px solid rgba(14,165,233,.06)">
      <div style="min-width:0;flex:1">
        <a href="/admin/blog.php?id=<?= $y['id'] ?>" style="font-size:.85rem;font-weight:500;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:<?= ($id===$y['id']?'var(--blue)':'var(--text)') ?>"><?= e($y['baslik']) ?></a>
        <div style="font-size:.75rem;color:var(--faint)"><?= date('d.m.Y',strtotime($y['created_at'])) ?></div>
      </div>
      <div style="display:flex;gap:.3rem;flex-shrink:0;margin-left:.5rem">
        <span class="badge <?= $y['aktif']?'badge-green':'badge-gray' ?>" style="font-size:.68rem"><?= $y['aktif']?'✓':'–' ?></span>
        <a href="/admin/blog.php?sil=<?= $y['id'] ?>&csrf=<?= csrf() ?>" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Silinsin mi?')">🗑️</a>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($yazilar)): ?><div style="padding:2rem;text-align:center;color:var(--muted);font-size:.88rem">Henüz yazı yok.</div><?php endif; ?>
  </div>

  <div class="card">
    <div class="card-header"><span class="card-title"><?= $editing ? 'Yazı Düzenle' : 'Yeni Yazı' ?></span></div>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">
      <div class="form-group"><label class="form-label">Başlık *</label><input type="text" name="baslik" class="form-control" value="<?= e($yazi['baslik']??'') ?>" required></div>
      <div class="form-group"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" value="<?= e($yazi['slug']??'') ?>"></div>
      <div class="form-group"><label class="form-label">Özet</label><textarea name="ozet" class="form-control" style="min-height:80px"><?= e($yazi['ozet']??'') ?></textarea></div>
      <div class="form-group"><label class="form-label">İçerik</label><textarea name="icerik" class="form-control" style="min-height:240px"><?= e($yazi['icerik']??'') ?></textarea></div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Kapak Görseli</label>
          <?php if (!empty($yazi['kapak'])): ?><img src="<?= UPLOAD_URL ?>urunler/<?= e($yazi['kapak']) ?>" id="blogImg" class="img-preview"><?php else: ?><img id="blogImg" src="" class="img-preview" style="display:none"><?php endif; ?>
          <label class="img-upload-area" style="margin-top:.5rem"><input type="file" name="kapak" accept="image/*" data-preview="blogImg" style="display:none"> 📷 Görsel Seç</label>
        </div>
        <div class="form-group" style="align-self:end">
          <label class="form-check" style="margin-bottom:1rem"><input type="checkbox" name="aktif" value="1" <?= ($yazi['aktif']??1)?'checked':'' ?>> Yayında</label>
          <button type="submit" class="btn btn-primary btn-full"><?= $editing?'💾 Güncelle':'➕ Yayınla' ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
