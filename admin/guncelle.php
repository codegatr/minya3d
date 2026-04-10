<?php $adminTitle = 'Güncelleme Merkezi'; require_once __DIR__ . '/includes/header.php'; ?>

<?php
define('GITHUB_API', 'https://api.github.com/repos/' . GITHUB_REPO . '/releases/latest');
define('ROOT_DIR', dirname(__DIR__));
define('BACKUP_DIR', ROOT_DIR . '/backups/');

function githubRequest(string $url): ?array {
    $token = ayar('github_token', GITHUB_TOKEN);
    $opts  = [
        'http' => [
            'method'  => 'GET',
            'header'  => implode("\r\n", [
                'User-Agent: minya3d-updater/1.0',
                'Accept: application/vnd.github+json',
                $token ? "Authorization: Bearer $token" : '',
            ]),
            'timeout' => 15,
        ],
    ];
    $ctx  = stream_context_create($opts);
    $body = @file_get_contents($url, false, $ctx);
    if ($body === false) return null;
    return json_decode($body, true);
}

function versionCompare(string $a, string $b): bool {
    return version_compare(ltrim($a,'v'), ltrim($b,'v'), '>');
}

function doBackup(): string {
    @mkdir(BACKUP_DIR, 0755, true);
    $file = BACKUP_DIR . 'backup_' . date('Ymd_His') . '.zip';
    $zip  = new ZipArchive();
    if ($zip->open($file, ZipArchive::CREATE) !== true) return '';
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ROOT_DIR, RecursiveDirectoryIterator::SKIP_DOTS));
    $skip = ['backups', '.git', 'node_modules', 'vendor'];
    foreach ($it as $f) {
        $rel = str_replace(ROOT_DIR . DIRECTORY_SEPARATOR, '', $f->getRealPath());
        $top = explode(DIRECTORY_SEPARATOR, $rel)[0];
        if (in_array($top, $skip)) continue;
        $zip->addFile($f->getRealPath(), $rel);
    }
    $zip->close();
    return $file;
}

function applyUpdate(string $zipUrl): array {
    $log = [];
    // ZIP indir
    $tmp = sys_get_temp_dir() . '/minya3d_update_' . time() . '.zip';
    $token = ayar('github_token', GITHUB_TOKEN);
    $opts = ['http'=>['method'=>'GET','header'=>"User-Agent: minya3d-updater/1.0\r\n".($token?"Authorization: Bearer $token\r\n":''),'timeout'=>60,'follow_location'=>1]];
    $data = @file_get_contents($zipUrl, false, stream_context_create($opts));
    if (!$data) { $log[] = ['err','ZIP indirilemedi: '.$zipUrl]; return $log; }
    file_put_contents($tmp, $data);
    $log[] = ['ok', 'ZIP indirildi ('.round(strlen($data)/1024).' KB)'];

    // ZIP aç
    $zip = new ZipArchive();
    if ($zip->open($tmp) !== true) { $log[] = ['err','ZIP açılamadı.']; return $log; }
    $tmpDir = sys_get_temp_dir() . '/minya3d_upd_' . time() . '/';
    $zip->extractTo($tmpDir);
    $zip->close();
    unlink($tmp);
    $log[] = ['ok','ZIP çıkarıldı.'];

    // manifest.json oku
    $inner = glob($tmpDir . '*/')[0] ?? $tmpDir;
    $manifest = $inner . 'manifest.json';
    if (!file_exists($manifest)) {
        // manifest yoksa tüm dosyaları kopyala
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($inner, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($it as $f) {
            $rel  = str_replace($inner, '', $f->getRealPath());
            $dest = ROOT_DIR . '/' . $rel;
            @mkdir(dirname($dest), 0755, true);
            copy($f->getRealPath(), $dest);
        }
        $log[] = ['info','Tüm dosyalar kopyalandı (manifest yok).'];
    } else {
        $mf    = json_decode(file_get_contents($manifest), true);
        $files = $mf['files'] ?? [];
        foreach ($files as $f) {
            $src  = $inner . $f;
            $dest = ROOT_DIR . '/' . $f;
            if (!file_exists($src)) { $log[] = ['err',"Bulunamadı: $f"]; continue; }
            @mkdir(dirname($dest), 0755, true);
            copy($src, $dest);
            $log[] = ['ok',"Güncellendi: $f"];
        }
    }

    // Geçici dizini temizle
    array_map('unlink', glob($tmpDir.'*') ?: []);
    @rmdir($tmpDir);

    $log[] = ['ok','Güncelleme tamamlandı.'];
    return $log;
}

// ── İşlem ──
$log        = [];
$release    = null;
$curVer     = APP_VERSION;
$apiErr     = '';
$updateDone = false;

try {
    $release = githubRequest(GITHUB_API);
} catch (Throwable $e) {
    $apiErr = $e->getMessage();
}

// Güncelle butonu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck() && isset($_POST['action'])) {
    if ($_POST['action'] === 'backup') {
        $bf = doBackup();
        if ($bf) flash('ok', 'Yedek alındı: ' . basename($bf));
        else     flash('err', 'Yedek alınamadı.');
        redirect('/admin/guncelle.php');
    }
    if ($_POST['action'] === 'update' && $release) {
        // Önce yedek al
        doBackup();
        // ZIP bul
        $zipUrl = '';
        foreach ($release['assets'] ?? [] as $a) {
            if (str_ends_with($a['name'], '.zip')) { $zipUrl = $a['browser_download_url']; break; }
        }
        if (!$zipUrl) $zipUrl = $release['zipball_url'] ?? '';
        $log        = applyUpdate($zipUrl);
        $updateDone = true;
    }
}

$newVerAvailable = $release && isset($release['tag_name']) && versionCompare($release['tag_name'], $curVer);

$ok  = getFlash('ok');
$err = getFlash('err');
?>

<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error">✗ <?= e($err) ?></div><?php endif; ?>

<div style="max-width:780px">

  <!-- MEVCUT VERSİYON -->
  <div class="card">
    <div class="card-header"><span class="card-title">📦 Mevcut Versiyon</span></div>
    <div style="display:flex;align-items:center;gap:2rem;flex-wrap:wrap">
      <div>
        <div style="font-size:.8rem;color:var(--muted);margin-bottom:.3rem">Kurulu Versiyon</div>
        <div class="version-tag"><?= e($curVer) ?></div>
      </div>
      <div>
        <div style="font-size:.8rem;color:var(--muted);margin-bottom:.3rem">GitHub Repo</div>
        <a href="https://github.com/<?= GITHUB_REPO ?>" target="_blank" style="color:var(--blue)">
          <?= GITHUB_REPO ?>
        </a>
      </div>
      <div>
        <div style="font-size:.8rem;color:var(--muted);margin-bottom:.3rem">Durum</div>
        <?php if ($apiErr): ?>
          <span class="badge badge-red">API Hatası</span>
        <?php elseif (!$release): ?>
          <span class="badge badge-gray">Kontrol edilmedi</span>
        <?php elseif ($newVerAvailable): ?>
          <span class="badge badge-orange">⚠️ Güncelleme Var</span>
        <?php else: ?>
          <span class="badge badge-green">✓ Güncel</span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php if ($apiErr): ?>
  <div class="alert alert-error">GitHub API hatası: <?= e($apiErr) ?><br><small>Token ayarlarını kontrol edin.</small></div>
  <?php endif; ?>

  <!-- SON RELEASE -->
  <?php if ($release): ?>
  <div class="card">
    <div class="card-header">
      <span class="card-title">🚀 GitHub Son Sürüm</span>
      <span class="badge <?= $newVerAvailable ? 'badge-orange' : 'badge-green' ?>"><?= e($release['tag_name']) ?></span>
    </div>
    <div style="margin-bottom:1rem">
      <div style="font-size:.85rem;color:var(--muted);margin-bottom:.5rem">
        <?= date('d.m.Y H:i', strtotime($release['published_at'])) ?> · <?= e($release['author']['login'] ?? '') ?>
      </div>
      <?php if ($release['body']): ?>
      <div style="background:rgba(2,12,27,.8);border:1px solid var(--border);border-radius:10px;padding:1rem;font-size:.85rem;color:var(--muted);line-height:1.8;max-height:200px;overflow-y:auto">
        <?= nl2br(e(mb_substr($release['body'], 0, 1200))) ?>
      </div>
      <?php endif; ?>
    </div>

    <?php if ($newVerAvailable): ?>
    <div class="alert alert-warning">
      ⚠️ <strong>Yeni sürüm mevcut: <?= e($release['tag_name']) ?></strong><br>
      Güncellemeden önce otomatik olarak yedek alınacaktır.
    </div>
    <form method="POST">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">
      <input type="hidden" name="action" value="update">
      <button type="submit" class="btn btn-primary"
              onclick="return confirm('Güncelleme başlatılsın mı? Önce yedek alınacaktır.')">
        🔄 Güncellemeyi Başlat
      </button>
    </form>
    <?php else: ?>
    <div class="alert alert-success">✓ Sistem güncel. Herhangi bir güncelleme gerekmemektedir.</div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- YEDEKLEME -->
  <div class="card">
    <div class="card-header"><span class="card-title">💾 Manuel Yedek Al</span></div>
    <p style="color:var(--muted);font-size:.9rem;margin-bottom:1rem">Güncelleme öncesi veya herhangi bir zamanda site dosyalarını ZIP olarak yedekler.</p>
    <form method="POST">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">
      <input type="hidden" name="action" value="backup">
      <button type="submit" class="btn btn-outline">💾 Yedek Al</button>
    </form>
    <?php
    $backups = array_reverse(glob(ROOT_DIR . '/backups/backup_*.zip') ?: []);
    if (!empty($backups)): ?>
    <div style="margin-top:1.25rem">
      <div style="font-size:.82rem;color:var(--muted);margin-bottom:.5rem">Son Yedekler</div>
      <?php foreach (array_slice($backups, 0, 5) as $b): ?>
      <div style="font-size:.85rem;padding:.3rem 0;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
        <span><?= e(basename($b)) ?></span>
        <span style="color:var(--faint)"><?= round(filesize($b)/1024/1024, 1) ?> MB</span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- GÜNCELLEME LOGU -->
  <?php if (!empty($log)): ?>
  <div class="card">
    <div class="card-header"><span class="card-title">📋 Güncelleme Logu</span></div>
    <div class="update-log"><?php foreach ($log as [$type,$msg]): ?><span class="log-<?= $type ?>">[<?= strtoupper($type) ?>] <?= e($msg) ?></span>
<?php endforeach; ?></div>
    <?php if ($updateDone): ?>
    <div class="alert alert-success" style="margin-top:1rem">
      ✓ Güncelleme tamamlandı. Sayfayı yenileyin.
    </div>
    <a href="/admin/guncelle.php" class="btn btn-blue">Sayfayı Yenile</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
