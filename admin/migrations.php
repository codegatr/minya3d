<?php $adminTitle = 'Veritabanı Migrations'; require_once __DIR__ . '/includes/header.php'; ?>
<?php
require_once dirname(__DIR__) . '/includes/migration.php';

$log = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {

    if ($_POST['action'] === 'run_all') {
        $results = Migration::run();
        foreach ($results as $r) {
            $log[] = [$r['ok'] ? 'ok' : 'err', $r['msg']];
        }
        if (empty($results)) {
            $log[] = ['info', 'Bekleyen migration yok — veritabanı güncel.'];
        }
    }

    if ($_POST['action'] === 'run_one' && !empty($_POST['file'])) {
        $file = basename($_POST['file']);  // path traversal engelle
        $r    = Migration::apply($file);
        $log[] = [$r['ok'] ? 'ok' : 'err', $r['msg']];
    }
}

$allFiles = Migration::all();
$applied  = Migration::applied();
$pending  = Migration::pending();
?>

<!-- Durum özeti -->
<div class="stat-grid" style="margin-bottom:1.5rem">
  <div class="stat-card">
    <div class="stat-card-icon">📋</div>
    <div class="stat-card-label">Toplam Migration</div>
    <div class="stat-card-val blue"><?= count($allFiles) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon">✅</div>
    <div class="stat-card-label">Uygulanmış</div>
    <div class="stat-card-val green"><?= count($applied) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon">⏳</div>
    <div class="stat-card-label">Bekleyen</div>
    <div class="stat-card-val <?= count($pending) > 0 ? 'orange' : 'green' ?>"><?= count($pending) ?></div>
  </div>
</div>

<!-- Toplu çalıştır -->
<?php if (!empty($pending)): ?>
<div class="alert alert-warning" style="margin-bottom:1.25rem">
  ⚠ <strong><?= count($pending) ?></strong> migration bekliyor:
  <?= implode(', ', array_map(fn($f) => "<code>$f</code>", $pending)) ?>
</div>
<form method="POST" style="margin-bottom:1.5rem">
  <input type="hidden" name="csrf" value="<?= csrf() ?>">
  <input type="hidden" name="action" value="run_all">
  <button type="submit" class="btn btn-primary"
          onclick="this.disabled=true;this.textContent='⏳ Uygulanıyor...';this.form.submit();">
    ▶ Tüm Bekleyenleri Uygula
  </button>
</form>
<?php else: ?>
<div class="alert alert-success" style="margin-bottom:1.25rem">✓ Tüm migration'lar uygulanmış — veritabanı güncel.</div>
<?php endif; ?>

<!-- Log -->
<?php if (!empty($log)): ?>
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header"><span class="card-title">📋 İşlem Logu</span></div>
  <div class="update-log"><?php
    foreach ($log as [$type, $msg]) {
        $cls = $type === 'ok' ? 'log-ok' : ($type === 'err' ? 'log-err' : 'log-info');
        $pfx = $type === 'ok' ? '[OK]    ' : ($type === 'err' ? '[HATA]  ' : '[BİLGİ] ');
        echo "<span class='$cls'>$pfx" . e($msg) . "</span>\n";
    }
  ?></div>
</div>
<?php endif; ?>

<!-- Tablo -->
<div class="card" style="padding:0">
  <div class="card-header" style="padding:1.25rem 1.5rem">
    <span class="card-title">Migration Listesi</span>
    <a href="/admin/migrations.php" class="btn btn-outline btn-sm">🔄 Yenile</a>
  </div>
  <div class="table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Dosya Adı</th>
          <th>Açıklama</th>
          <th>Durum</th>
          <th>Uygulandı</th>
          <th>İşlem</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 0;
        foreach ($allFiles as $file):
            $i++;
            $isApplied = in_array($file, $applied);
            $statusRow = Migration::status($file);

            // SQL dosyasından açıklama satırını oku
            $path    = dirname(__DIR__) . '/migrations/' . $file;
            $head    = file_exists($path) ? array_slice(file($path), 0, 5) : [];
            $aciklama = '';
            foreach ($head as $line) {
                if (preg_match('/^--\s*Açıklama\s*:\s*(.+)/u', $line, $m)) {
                    $aciklama = trim($m[1]);
                    break;
                }
            }
        ?>
        <tr>
          <td style="color:var(--faint);font-family:'Orbitron',monospace;font-size:.78rem"><?= str_pad($i, 3, '0', STR_PAD_LEFT) ?></td>
          <td>
            <code style="font-size:.82rem;color:var(--blue);background:rgba(14,165,233,.08);padding:.15rem .4rem;border-radius:4px">
              <?= e($file) ?>
            </code>
          </td>
          <td style="color:var(--muted);font-size:.85rem"><?= e($aciklama ?: '—') ?></td>
          <td>
            <?php if ($isApplied): ?>
              <span class="badge badge-green">✓ Uygulandı</span>
            <?php elseif ($statusRow && $statusRow['durum'] === 'hata'): ?>
              <span class="badge badge-red" title="<?= e($statusRow['hata_mesaji'] ?? '') ?>">✗ Hata</span>
            <?php else: ?>
              <span class="badge badge-orange">⏳ Bekliyor</span>
            <?php endif; ?>
          </td>
          <td style="color:var(--muted);font-size:.8rem;white-space:nowrap">
            <?= $statusRow ? date('d.m.Y H:i', strtotime($statusRow['uygulandi_at'])) : '—' ?>
          </td>
          <td>
            <?php if (!$isApplied): ?>
            <form method="POST" style="display:inline">
              <input type="hidden" name="csrf"   value="<?= csrf() ?>">
              <input type="hidden" name="action" value="run_one">
              <input type="hidden" name="file"   value="<?= e($file) ?>">
              <button type="submit" class="btn btn-blue btn-sm"
                      onclick="return confirm('<?= e($file) ?> uygulanacak. Devam?')">
                ▶ Uygula
              </button>
            </form>
            <?php elseif ($statusRow && $statusRow['durum'] === 'hata'): ?>
            <form method="POST" style="display:inline">
              <input type="hidden" name="csrf"   value="<?= csrf() ?>">
              <input type="hidden" name="action" value="run_one">
              <input type="hidden" name="file"   value="<?= e($file) ?>">
              <button type="submit" class="btn btn-danger btn-sm">Tekrar Dene</button>
            </form>
            <?php else: ?>
            <span style="color:var(--faint);font-size:.82rem">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($allFiles)): ?>
        <tr>
          <td colspan="6" style="text-align:center;padding:2rem;color:var(--muted)">
            migrations/ klasöründe henüz SQL dosyası yok.
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Nasıl Kullanılır -->
<div class="card" style="margin-top:1.5rem">
  <div class="card-header"><span class="card-title">📖 Migration Nasıl Yazılır?</span></div>
  <div style="font-size:.88rem;color:var(--muted);line-height:1.9">
    <p style="margin-bottom:.75rem">
      <code>migrations/</code> dizinine <strong>sıralı numaralı</strong> SQL dosyaları ekleyin:
    </p>
    <pre style="background:rgba(2,12,27,.8);border:1px solid var(--border);border-radius:8px;padding:1rem;font-size:.82rem;overflow-x:auto;color:var(--text)">-- Migration: 005_ornek_tablo
-- Açıklama : Örnek yeni tablo ekleme
-- Tarih    : 2025-04-15
-- Bağımlılık: 001_initial_schema

CREATE TABLE IF NOT EXISTS `mn_ornek` (
  `id`    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ad`    VARCHAR(100) NOT NULL,
  `aktif` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB CHARSET=utf8mb4;

ALTER TABLE `mn_urunler`
  ADD COLUMN IF NOT EXISTS `yeni_alan` VARCHAR(100) NULL AFTER `meta_desc`;</pre>
    <ul style="margin-top:.75rem;padding-left:1.25rem">
      <li>Her migration <strong>geri alınamaz</strong> — dikkatli yazın, test edin</li>
      <li><code>IF NOT EXISTS</code> ve <code>IF EXISTS</code> kullanın — tekrar çalışmaya karşı güvenli olsun</li>
      <li>Tek bir migration'da hem <code>CREATE TABLE</code> hem <code>ALTER TABLE</code> olabilir</li>
      <li>Güncelleme motoruna dosyayı <code>manifest.json</code>'ın <code>files</code> listesine ekleyin</li>
      <li>Güncelleme sonrası <strong>otomatik</strong> olarak bu sayfa kontrol edilir</li>
    </ul>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
