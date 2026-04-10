<?php $adminTitle = 'Güncelleme Merkezi'; require_once __DIR__ . '/includes/header.php'; ?>
<?php
if (!defined('ROOT_DIR'))   define('ROOT_DIR',   dirname(__DIR__));
if (!defined('BACKUP_DIR')) define('BACKUP_DIR', ROOT_DIR . '/backups/');
if (!defined('TMP_DIR'))    define('TMP_DIR',    ROOT_DIR . '/tmp/');
if (!defined('GITHUB_API')) define('GITHUB_API', 'https://api.github.com/repos/' . GITHUB_REPO . '/releases/latest');

@mkdir(BACKUP_DIR, 0755, true);
@mkdir(TMP_DIR,    0755, true);

// ── cURL ile HTTP GET ─────────────────────────────────────────────────────────
function mn_curl(string $url, bool $toFile = false, string $dest = ''): string|bool {
    if (!function_exists('curl_init')) return false;
    $token = ayar('github_token', GITHUB_TOKEN);
    $hdrs  = array_filter([
        'User-Agent: minya3d-updater/1.0',
        'Accept: application/vnd.github+json',
        $token ? "Authorization: Bearer $token" : null,
    ]);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => !$toFile,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 8,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER     => array_values($hdrs),
    ]);
    if ($toFile && $dest) {
        $fp = fopen($dest, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
    }
    $body = curl_exec($ch);
    if ($toFile && $dest) fclose($fp);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $cerr = curl_error($ch);
    curl_close($ch);
    if ($cerr || $code >= 400) return false;
    return $toFile ? (file_exists($dest) && filesize($dest) > 512) : $body;
}

// file_get_contents fallback
function mn_fgc(string $url): string|false {
    $token = ayar('github_token', GITHUB_TOKEN);
    $ctx   = stream_context_create(['http' => [
        'method'          => 'GET',
        'timeout'         => 20,
        'follow_location' => 1,
        'header'          => implode("\r\n", array_filter([
            'User-Agent: minya3d-updater/1.0',
            'Accept: application/vnd.github+json',
            $token ? "Authorization: Bearer $token" : null,
        ])),
    ]]);
    return @file_get_contents($url, false, $ctx);
}

function mn_get(string $url): string|false {
    $r = mn_curl($url);
    return ($r !== false) ? $r : mn_fgc($url);
}

// ── GitHub son release ────────────────────────────────────────────────────────
function mn_release(): ?array {
    $body = mn_get(GITHUB_API);
    if (!$body) return null;
    $d = json_decode($body, true);
    return (is_array($d) && isset($d['tag_name'])) ? $d : null;
}

// ── Versiyon karşılaştırma ────────────────────────────────────────────────────
function mn_newer(string $a, string $b): bool {
    return version_compare(ltrim($a, 'v'), ltrim($b, 'v'), '>');
}

// ── Yedek al ─────────────────────────────────────────────────────────────────
function mn_backup(): string {
    $file = BACKUP_DIR . 'backup_' . date('Ymd_His') . '.zip';
    $zip  = new ZipArchive();
    if ($zip->open($file, ZipArchive::CREATE) !== true) return '';
    $skip = ['backups', '.git', 'tmp', 'node_modules'];
    $it   = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(ROOT_DIR, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($it as $f) {
        $rel = str_replace(ROOT_DIR . DIRECTORY_SEPARATOR, '', $f->getRealPath());
        if (in_array(explode(DIRECTORY_SEPARATOR, $rel)[0], $skip)) continue;
        $zip->addFile($f->getRealPath(), $rel);
    }
    $zip->close();
    return file_exists($file) ? $file : '';
}

// ── Güncelleme uygula ─────────────────────────────────────────────────────────
function mn_update(string $zipUrl, string $tag): array {
    $log    = [];
    $tmpZip = TMP_DIR . 'upd_' . time() . '.zip';

    // İndir
    $log[] = ['info', "İndiriliyor: $zipUrl"];
    $ok    = mn_curl($zipUrl, true, $tmpZip);
    if (!$ok) {
        // cURL başarısız → file_get_contents ile dene
        $data = mn_fgc($zipUrl);
        if ($data && strlen($data) > 1024) {
            file_put_contents($tmpZip, $data);
            $ok = true;
        }
    }
    if (!$ok || !file_exists($tmpZip) || filesize($tmpZip) < 1024) {
        @unlink($tmpZip);
        $log[] = ['err', 'ZIP indirilemedi. Sunucu → GitHub bağlantısını ve token\'ı kontrol edin.'];
        return $log;
    }
    $log[] = ['ok', 'ZIP indirildi — ' . round(filesize($tmpZip) / 1024) . ' KB'];

    // Aç
    $zip = new ZipArchive();
    if ($zip->open($tmpZip) !== true) {
        @unlink($tmpZip);
        $log[] = ['err', 'ZIP açılamadı.'];
        return $log;
    }

    // GitHub zipball inner prefix: "owner-repo-sha/"
    $first  = $zip->getNameIndex(0);
    $prefix = rtrim(explode('/', $first)[0], '/') . '/';
    $log[]  = ['info', "ZIP prefix: $prefix"];

    // manifest.json
    $mfRaw = $zip->getFromName($prefix . 'manifest.json');
    $mf    = $mfRaw ? json_decode($mfRaw, true) : null;
    $files = $mf['files'] ?? [];

    if (empty($files)) {
        // Tüm dosyaları yükle
        $n = $zip->count(); $c = 0;
        for ($i = 0; $i < $n; $i++) {
            $name = $zip->getNameIndex($i);
            if (str_ends_with($name, '/')) continue;
            $rel = str_replace(['../', '..\\'], '', substr($name, strlen($prefix)));
            if (!$rel) continue;
            $dest = ROOT_DIR . '/' . $rel;
            @mkdir(dirname($dest), 0755, true);
            if (file_put_contents($dest, $zip->getFromIndex($i)) !== false) $c++;
        }
        $log[] = ['ok', "$c dosya güncellendi (manifest yok)"];
    } else {
        $c = $m = 0;
        foreach ($files as $rel) {
            $rel  = ltrim(str_replace(['../', '..\\'], '', $rel), '/');
            $data = $zip->getFromName($prefix . $rel);
            if ($data === false) { $log[] = ['warn', "Atlandı (ZIP'te yok): $rel"]; $m++; continue; }
            $dest = ROOT_DIR . '/' . $rel;
            @mkdir(dirname($dest), 0755, true);
            if (file_put_contents($dest, $data) !== false) {
                $log[] = ['ok', "✓ $rel"]; $c++;
            } else {
                $log[] = ['err', "Yazılamadı: $rel"];
            }
        }
        $log[] = ['ok', "Tamamlandı — $c güncellendi" . ($m ? ", $m atlandı" : '')];
    }

    $zip->close();
    @unlink($tmpZip);

    // config.php APP_VERSION güncelle
    $newVer = $mf['version'] ?? ltrim($tag, 'v');
    $cfg    = ROOT_DIR . '/config.php';
    if (file_exists($cfg)) {
        $src = file_get_contents($cfg);
        $src = preg_replace("/define\('APP_VERSION',\s*'[^']+'\)/", "define('APP_VERSION', '$newVer')", $src);
        file_put_contents($cfg, $src);
        $log[] = ['ok', "APP_VERSION → $newVer"];
    }

    // ── Bekleyen migration'ları otomatik çalıştır ──────────────────────────
    $migFile = ROOT_DIR . '/includes/migration.php';
    if (file_exists($migFile)) {
        require_once $migFile;
        $migResults = Migration::run();
        if (empty($migResults)) {
            $log[] = ['info', 'Migration: bekleyen yok'];
        } else {
            foreach ($migResults as $mr) {
                $log[] = [$mr['ok'] ? 'ok' : 'err', 'Migration: ' . $mr['msg']];
            }
        }
    }

    return $log;
}

// ── İşlem yönetimi ───────────────────────────────────────────────────────────
$release    = null;
$apiErr     = '';
$log        = [];
$updateDone = false;
$curVer     = APP_VERSION;

try { $release = mn_release(); if (!$release) $apiErr = 'API yanıt vermedi veya token eksik.'; }
catch (Throwable $e) { $apiErr = $e->getMessage(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $act = $_POST['action'] ?? '';

    if ($act === 'backup') {
        $bf = mn_backup();
        flash($bf ? 'ok' : 'err', $bf ? 'Yedek alındı: ' . basename($bf) : 'Yedek alınamadı!');
        redirect('/admin/guncelle.php');
    }

    if ($act === 'update' && $release) {
        $bf    = mn_backup();
        $log[] = $bf ? ['ok', 'Yedek: ' . basename($bf)] : ['warn', 'Yedek alınamadı, devam ediliyor'];
        $zipUrl = '';
        foreach ($release['assets'] ?? [] as $a) {
            if (str_ends_with(strtolower($a['name']), '.zip')) { $zipUrl = $a['url']; break; }
        }
        if (!$zipUrl) $zipUrl = $release['zipball_url'] ?? '';
        if ($zipUrl) {
            $log       = array_merge($log, mn_update($zipUrl, $release['tag_name']));
            $updateDone = true;
        } else {
            $log[] = ['err', 'ZIP URL bulunamadı.'];
        }
    }

    if ($act === 'diagnose') {
        $log[] = ['info', 'PHP: '         . PHP_VERSION];
        $log[] = ['info', 'cURL: '        . (function_exists('curl_init') ? 'VAR ✓' : 'YOK ✗')];
        $log[] = ['info', 'ZipArchive: '  . (class_exists('ZipArchive')   ? 'VAR ✓' : 'YOK ✗')];
        $log[] = ['info', 'allow_url_fopen: ' . (ini_get('allow_url_fopen') ? 'Açık' : 'Kapalı')];
        $log[] = ['info', 'open_basedir: '    . (ini_get('open_basedir') ?: '(kısıtsız)')];
        $log[] = ['info', 'ROOT_DIR yazılabilir: ' . (is_writable(ROOT_DIR)    ? 'EVET ✓' : 'HAYIR ✗')];
        $log[] = ['info', 'TMP_DIR yazılabilir: '  . (is_writable(TMP_DIR)     ? 'EVET ✓' : 'HAYIR ✗')];
        $tok   = ayar('github_token', GITHUB_TOKEN);
        $log[] = ['info', 'GitHub Token: ' . ($tok ? 'Mevcut (' . strlen($tok) . ' karakter) ✓' : 'EKSİK ✗')];
        // API testi
        $res = mn_curl(GITHUB_API);
        if ($res) {
            $d = json_decode($res, true);
            $log[] = ['ok', 'GitHub API: BAŞARILI ✓ — son sürüm: ' . ($d['tag_name'] ?? '?')];
        } else {
            $log[] = ['err', 'GitHub API: BAŞARISIZ ✗ — token veya cURL sorunu'];
        }
        // ZIP HEAD testi
        if ($release) {
            $zipUrl = $release['zipball_url'] ?? '';
            $tok    = ayar('github_token', GITHUB_TOKEN);
            $ch     = curl_init($zipUrl);
            curl_setopt_array($ch, [
                CURLOPT_NOBODY => true, CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 10, CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array_filter([
                    'User-Agent: minya3d-updater/1.0',
                    $tok ? "Authorization: Bearer $tok" : null,
                ]),
            ]);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $log[] = ($code < 400)
                ? ['ok',  "ZIP erişim testi: HTTP $code ✓"]
                : ['err', "ZIP erişim testi: HTTP $code ✗"];
        }
    }
}

$newVer = $release && mn_newer($release['tag_name'], $curVer);
$ok  = getFlash('ok');
$err = getFlash('err');
?>

<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error">✗ <?= e($err) ?></div><?php endif; ?>

<div style="max-width:780px">

<!-- Durum -->
<div class="card">
  <div class="card-header"><span class="card-title">📦 Sürüm Durumu</span></div>
  <div style="display:flex;gap:2.5rem;flex-wrap:wrap;align-items:center">
    <div>
      <div style="font-size:.75rem;color:var(--muted);margin-bottom:.3rem">Kurulu</div>
      <div class="version-tag"><?= e($curVer) ?></div>
    </div>
    <?php if ($release): ?>
    <div>
      <div style="font-size:.75rem;color:var(--muted);margin-bottom:.3rem">GitHub</div>
      <div class="version-tag" style="color:<?= $newVer ? 'var(--orange)' : 'var(--green)' ?>">
        <?= e($release['tag_name']) ?>
      </div>
    </div>
    <?php endif; ?>
    <div>
      <?php if ($apiErr): ?><span class="badge badge-red">API Hatası</span>
      <?php elseif ($newVer): ?><span class="badge badge-orange">⚠ Güncelleme Var</span>
      <?php elseif ($release): ?><span class="badge badge-green">✓ Güncel</span>
      <?php else: ?><span class="badge badge-gray">—</span>
      <?php endif; ?>
    </div>
    <a href="https://github.com/<?= GITHUB_REPO ?>/releases" target="_blank"
       style="color:var(--blue);font-size:.85rem;margin-left:auto">Tüm Sürümler →</a>
  </div>
</div>

<?php if ($apiErr): ?>
<div class="alert alert-error" style="margin-bottom:1rem">
  ✗ <?= e($apiErr) ?> — <strong>Tanı Modu</strong>'nu çalıştırın.
</div>
<?php endif; ?>

<!-- Release bilgisi + güncelle -->
<?php if ($release): ?>
<div class="card">
  <div class="card-header">
    <span class="card-title">🚀 Son Sürüm: <?= e($release['tag_name']) ?></span>
    <span style="color:var(--muted);font-size:.8rem">
      <?= date('d.m.Y H:i', strtotime($release['published_at'])) ?>
    </span>
  </div>
  <?php if ($release['body']): ?>
  <div class="update-log" style="margin-bottom:1.25rem;max-height:180px"><?= e(mb_substr($release['body'], 0, 1200)) ?></div>
  <?php endif; ?>

  <?php if ($newVer): ?>
  <div class="alert alert-warning" style="margin-bottom:1rem">
    ⚠ <strong><?= e($release['tag_name']) ?></strong> sürümüne güncellenebilir.
    Önce otomatik yedek alınır.
  </div>
  <form method="POST" style="display:inline-block">
    <input type="hidden" name="csrf" value="<?= csrf() ?>">
    <input type="hidden" name="action" value="update">
    <button type="submit" class="btn btn-primary"
            onclick="this.disabled=true;this.textContent='⏳ Güncelleniyor...';this.form.submit();">
      🔄 Güncellemeyi Başlat
    </button>
  </form>
  <?php else: ?>
  <div class="alert alert-success">✓ Zaten güncel.</div>
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- Alt butonlar -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem">
  <div class="card">
    <div class="card-header"><span class="card-title">💾 Manuel Yedek</span></div>
    <p style="font-size:.85rem;color:var(--muted);margin-bottom:1rem">Tüm dosyaları ZIP olarak yedekler.</p>
    <form method="POST">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">
      <input type="hidden" name="action" value="backup">
      <button type="submit" class="btn btn-outline">💾 Yedekle</button>
    </form>
  </div>
  <div class="card">
    <div class="card-header"><span class="card-title">🔬 Tanı Modu</span></div>
    <p style="font-size:.85rem;color:var(--muted);margin-bottom:1rem">cURL, izinler ve API bağlantısını test eder.</p>
    <form method="POST">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">
      <input type="hidden" name="action" value="diagnose">
      <button type="submit" class="btn btn-outline">🔬 Tanı Çalıştır</button>
    </form>
  </div>
</div>

<!-- Yedek listesi -->
<?php $bks = array_reverse(glob(BACKUP_DIR . 'backup_*.zip') ?: []); ?>
<?php if ($bks): ?>
<div class="card">
  <div class="card-header"><span class="card-title">📁 Yedekler</span></div>
  <?php foreach (array_slice($bks, 0, 6) as $b): ?>
  <div style="display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid rgba(14,165,233,.07);font-size:.83rem">
    <span style="color:var(--muted)"><?= e(basename($b)) ?></span>
    <span style="color:var(--faint)"><?= round(filesize($b)/1024/1024,1) ?> MB</span>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Log -->
<?php if (!empty($log)): ?>
<div class="card">
  <div class="card-header"><span class="card-title">📋 İşlem Logu</span></div>
  <div class="update-log"><?php
    foreach ($log as [$type, $msg]) {
        $cls = match($type) {
            'ok'   => 'log-ok',
            'err'  => 'log-err',
            'warn' => 'log-err',
            default=> 'log-info',
        };
        $pfx = match($type) {
            'ok'   => '[OK]    ',
            'err'  => '[HATA]  ',
            'warn' => '[UYARI] ',
            default=> '[BİLGİ] ',
        };
        echo "<span class='$cls'>$pfx" . e($msg) . "</span>\n";
    }
  ?></div>
  <?php if ($updateDone): ?>
  <div class="alert alert-success" style="margin-top:1rem">✓ Güncelleme tamamlandı!</div>
  <a href="/admin/guncelle.php" class="btn btn-blue" style="margin-top:.5rem">🔄 Yenile</a>
  <?php endif; ?>
</div>
<?php endif; ?>

</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
