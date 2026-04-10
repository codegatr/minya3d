<?php $adminTitle = 'Yedekleme'; require_once __DIR__ . '/includes/header.php'; ?>
<?php
define('BACKUP_DIR', dirname(__DIR__) . '/backups/');
@mkdir(BACKUP_DIR, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $zip  = new ZipArchive();
    $file = BACKUP_DIR . 'backup_' . date('Ymd_His') . '.zip';
    if ($zip->open($file, ZipArchive::CREATE) === true) {
        $root = dirname(__DIR__);
        $skip = ['backups', '.git', 'node_modules'];
        $it   = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($it as $f) {
            $rel = str_replace($root . DIRECTORY_SEPARATOR, '', $f->getRealPath());
            if (in_array(explode(DIRECTORY_SEPARATOR, $rel)[0], $skip)) continue;
            $zip->addFile($f->getRealPath(), $rel);
        }
        $zip->close();
        flash('ok', 'Yedek alındı: ' . basename($file));
    } else {
        flash('err', 'Yedek alınamadı.');
    }
    redirect('/admin/yedek.php');
}
if (isset($_GET['sil']) && csrfCheck()) {
    $f = BACKUP_DIR . basename($_GET['sil']);
    if (file_exists($f)) { unlink($f); flash('ok', 'Yedek silindi.'); }
    redirect('/admin/yedek.php');
}

$backups = array_reverse(glob(BACKUP_DIR . 'backup_*.zip') ?: []);
$ok  = getFlash('ok');
$err = getFlash('err');
?>
<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error">✗ <?= e($err) ?></div><?php endif; ?>

<div style="max-width:700px">
  <div class="card">
    <div class="card-header"><span class="card-title">💾 Yedek Al</span></div>
    <p style="color:var(--muted);font-size:.9rem;margin-bottom:1.25rem">Tüm site dosyalarını ZIP formatında yedekler. Veritabanı yedeği için hosting cPanel/DirectAdmin yönetim panelini kullanın.</p>
    <form method="POST">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">
      <button type="submit" class="btn btn-primary" style="width:auto">💾 Şimdi Yedek Al</button>
    </form>
  </div>

  <div class="card" style="padding:0">
    <div class="card-header" style="padding:1.25rem 1.5rem"><span class="card-title">Yedekler (<?= count($backups) ?>)</span></div>
    <div class="table-wrap">
      <table class="admin-table">
        <thead><tr><th>Dosya</th><th>Boyut</th><th>Tarih</th><th>İşlem</th></tr></thead>
        <tbody>
          <?php foreach ($backups as $b): ?>
          <tr>
            <td><?= e(basename($b)) ?></td>
            <td><?= round(filesize($b)/1024/1024,1) ?> MB</td>
            <td style="color:var(--muted);font-size:.82rem"><?= date('d.m.Y H:i', filemtime($b)) ?></td>
            <td>
              <a href="/admin/yedek.php?sil=<?= urlencode(basename($b)) ?>&csrf=<?= csrf() ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silinsin mi?')">🗑️</a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($backups)): ?><tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--muted)">Henüz yedek yok.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
