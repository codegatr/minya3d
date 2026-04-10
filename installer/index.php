<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Minya 3D – Kurulum Sihirbazı</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Exo+2:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#020C1B;color:#E2E8F0;font-family:'Exo 2',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
body::before{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(14,165,233,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(14,165,233,.04) 1px,transparent 1px);background-size:60px 60px;z-index:0;pointer-events:none}
.wrap{position:relative;z-index:1;width:100%;max-width:560px}
.logo{text-align:center;margin-bottom:2rem}
.logo svg{width:200px;height:auto}
.logo-text{font-family:'Orbitron',sans-serif;font-size:1.8rem;font-weight:900;background:linear-gradient(135deg,#0EA5E9,#8B5CF6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:.3rem}
.logo-sub{font-size:.85rem;color:#94A3B8}
.card{background:rgba(13,31,53,.9);border:1px solid rgba(14,165,233,.2);border-radius:20px;padding:2rem}
.step-indicator{display:flex;justify-content:center;gap:.5rem;margin-bottom:2rem}
.step-dot{width:10px;height:10px;border-radius:50%;background:rgba(14,165,233,.2);transition:all .3s}
.step-dot.done{background:#22C55E}.step-dot.active{background:#0EA5E9;box-shadow:0 0 12px rgba(14,165,233,.6)}
h2{font-family:'Orbitron',sans-serif;font-size:1.2rem;font-weight:700;margin-bottom:1.5rem;color:#E2E8F0}
.form-group{margin-bottom:1rem}
label{display:block;font-size:.82rem;font-weight:600;color:#94A3B8;margin-bottom:.4rem}
input,select{width:100%;padding:.65rem .9rem;border-radius:8px;background:rgba(10,22,40,.8);border:1px solid rgba(14,165,233,.2);color:#E2E8F0;font-size:.9rem;font-family:inherit;outline:none;transition:border-color .2s}
input:focus,select:focus{border-color:#0EA5E9;box-shadow:0 0 0 3px rgba(14,165,233,.12)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.8rem;border-radius:10px;font-weight:700;font-size:.95rem;letter-spacing:.03em;cursor:pointer;border:none;transition:all .3s;margin-top:.5rem}
.btn-primary{background:linear-gradient(135deg,#F97316,#EA580C);color:#fff;box-shadow:0 0 24px rgba(249,115,22,.3)}
.btn-primary:hover{box-shadow:0 0 40px rgba(249,115,22,.55)}
.btn-blue{background:linear-gradient(135deg,#0EA5E9,#0284C7);color:#fff}
.alert{padding:.8rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.88rem;border:1px solid}
.alert-err{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#f87171}
.alert-ok{background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.3);color:#4ade80}
.alert-info{background:rgba(14,165,233,.1);border-color:rgba(14,165,233,.3);color:#38bdf8}
.check-row{display:flex;align-items:center;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid rgba(14,165,233,.1);font-size:.88rem}
.check-ok{color:#4ade80}.check-fail{color:#f87171}.check-warn{color:#fbbf24}
.log-box{background:rgba(2,12,27,.8);border:1px solid rgba(14,165,233,.15);border-radius:8px;padding:1rem;font-family:monospace;font-size:.82rem;line-height:1.8;max-height:280px;overflow-y:auto;margin:1rem 0;color:#94A3B8}
.log-ok{color:#4ade80}.log-err{color:#f87171}.log-info{color:#38bdf8}
.divider{border:none;border-top:1px solid rgba(14,165,233,.12);margin:1.25rem 0}
</style>
</head>
<body>
<div class="wrap">
  <div class="logo">
    <div class="logo-text">MiNYA 3D</div>
    <div class="logo-sub">Kurulum Sihirbazı</div>
  </div>

<?php
$step    = (int)($_POST['step'] ?? $_GET['step'] ?? 1);
$rootDir = dirname(__DIR__);

// ── ADIM 1: Gereksinim Kontrolü ──────────────────────────────────────────
if ($step === 1) {
    $checks = [
        ['PHP 8.3+',       version_compare(PHP_VERSION,'8.3','>='), PHP_VERSION],
        ['PDO MySQL',      extension_loaded('pdo_mysql'), ''],
        ['ZipArchive',     class_exists('ZipArchive'), ''],
        ['JSON',           extension_loaded('json'), ''],
        ['cURL / stream',  function_exists('file_get_contents'), ''],
        ['uploads/ yazılabilir', is_writable($rootDir.'/uploads') || @mkdir($rootDir.'/uploads/urunler',0755,true), ''],
        ['config.php yazılabilir', is_writable($rootDir) || file_exists($rootDir.'/config.php'), ''],
    ];
    $ok = !in_array(false, array_column($checks,1), true);
?>
  <div class="card">
    <div class="step-indicator">
      <div class="step-dot active"></div>
      <div class="step-dot"></div>
      <div class="step-dot"></div>
      <div class="step-dot"></div>
    </div>
    <h2>⚙️ Sistem Gereksinimleri</h2>
    <?php foreach ($checks as [$lbl,$pass,$val]): ?>
    <div class="check-row">
      <span><?= htmlspecialchars($lbl) ?><?= $val ? " ($val)" : '' ?></span>
      <span class="<?= $pass ? 'check-ok':'check-fail' ?>"><?= $pass ? '✓ Tamam':'✗ Hata' ?></span>
    </div>
    <?php endforeach; ?>
    <hr class="divider">
    <?php if ($ok): ?>
    <div class="alert alert-ok">✓ Tüm gereksinimler karşılanıyor. Devam edebilirsiniz.</div>
    <form method="POST"><input type="hidden" name="step" value="2">
      <button type="submit" class="btn btn-primary">Devam Et →</button>
    </form>
    <?php else: ?>
    <div class="alert alert-err">Bazı gereksinimler karşılanmıyor. Hosting ayarlarınızı kontrol edin.</div>
    <?php endif; ?>
  </div>

<?php
}

// ── ADIM 2: Veritabanı Bağlantısı ────────────────────────────────────────
elseif ($step === 2) {
    $err = '';
    if (!empty($_POST['db_host'])) {
        $dbHost = trim($_POST['db_host']);
        $dbName = trim($_POST['db_name']);
        $dbUser = trim($_POST['db_user']);
        $dbPass = $_POST['db_pass'];
        $siteUrl = rtrim(trim($_POST['site_url']), '/');
        try {
            $pdo = new PDO("mysql:host=$dbHost;charset=utf8mb4", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `$dbName`");
            // Şema çalıştır
            $sql = file_get_contents(__DIR__ . '/schema.sql');
            foreach (array_filter(explode(';', $sql)) as $q) {
                $q = trim($q); if ($q) $pdo->exec($q);
            }
            // Config yaz
            $cfg = <<<PHP
<?php
define('DB_HOST', '$dbHost');
define('DB_NAME', '$dbName');
define('DB_USER', '$dbUser');
define('DB_PASS', '$dbPass');
define('DB_CHARSET', 'utf8mb4');
define('SITE_URL', '$siteUrl');
define('SITE_NAME', 'Minya 3D');
define('SITE_EMAIL', 'info@minya3d.com');
define('ADMIN_PATH', '/admin');
define('GITHUB_REPO', 'codegatr/minya3d');
define('GITHUB_TOKEN', '');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('MAX_UPLOAD_MB', 50);
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'production');
error_reporting(0); ini_set('display_errors', 0);
date_default_timezone_set('Europe/Istanbul');
mb_internal_encoding('UTF-8');
PHP;
            file_put_contents($rootDir . '/config.php', $cfg);

            // Migration takip tablosunu oluştur (mn_migrations)
            $pdo->exec("CREATE TABLE IF NOT EXISTS `mn_migrations` (
                `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `dosya`        VARCHAR(200) NOT NULL UNIQUE,
                `durum`        ENUM('ok','hata') DEFAULT 'ok',
                `hata_mesaji`  TEXT,
                `uygulandi_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            // 001_initial_schema ve 002_blog_table zaten schema.sql'den uygulandı
            // Bunları mn_migrations'a "uygulandı" olarak kaydet (tekrar çalışmasın)
            $baseApplied = ['001_initial_schema.sql', '002_blog_table.sql'];
            foreach ($baseApplied as $mf) {
                $pdo->exec("INSERT IGNORE INTO mn_migrations (dosya, durum, uygulandi_at)
                            VALUES ('$mf', 'ok', NOW())");
            }

            // Session'a kaydet
            session_start();
            $_SESSION['installer'] = compact('dbHost','dbName','dbUser','dbPass','siteUrl');
            $step = 3;
        } catch (Throwable $e) {
            $err = $e->getMessage();
        }
    }

    if ($step !== 3): ?>
  <div class="card">
    <div class="step-indicator">
      <div class="step-dot done"></div>
      <div class="step-dot active"></div>
      <div class="step-dot"></div>
      <div class="step-dot"></div>
    </div>
    <h2>🗄️ Veritabanı Ayarları</h2>
    <?php if ($err): ?><div class="alert alert-err">✗ <?= htmlspecialchars($err) ?></div><?php endif; ?>
    <form method="POST">
      <input type="hidden" name="step" value="2">
      <div class="form-group"><label>Veritabanı Sunucu</label><input type="text" name="db_host" value="localhost" required></div>
      <div class="form-group"><label>Veritabanı Adı</label><input type="text" name="db_name" value="minya3d_db" required></div>
      <div class="form-group"><label>Kullanıcı Adı</label><input type="text" name="db_user" required></div>
      <div class="form-group"><label>Şifre</label><input type="password" name="db_pass"></div>
      <hr class="divider">
      <div class="form-group"><label>Site URL (https://minya3d.com)</label><input type="url" name="site_url" placeholder="https://minya3d.com" required></div>
      <button type="submit" class="btn btn-primary">Bağlantıyı Test Et & Devam →</button>
    </form>
  </div>
    <?php endif; }

// ── ADIM 3: Admin Hesabı ──────────────────────────────────────────────────
if ($step === 3) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $err = '';
    if (!empty($_POST['admin_email'])) {
        $ad    = trim($_POST['admin_ad']);
        $email = trim($_POST['admin_email']);
        $sifre = $_POST['admin_sifre'];
        $sifre2= $_POST['admin_sifre2'];
        if (strlen($sifre) < 8)        $err = 'Şifre en az 8 karakter olmalı.';
        elseif ($sifre !== $sifre2)    $err = 'Şifreler eşleşmiyor.';
        else {
            require_once $rootDir . '/config.php';
            require_once $rootDir . '/includes/db.php';
            $hash = password_hash($sifre, PASSWORD_BCRYPT);
            DB::q("INSERT INTO mn_adminler (ad,email,sifre_hash,rol,aktif) VALUES(?,?,?,'super',1)
                   ON DUPLICATE KEY UPDATE sifre_hash=?, ad=?", [$ad,$email,$hash,$hash,$ad]);
            $step = 4;
        }
    }
    if ($step !== 4): ?>
  <div class="card">
    <div class="step-indicator">
      <div class="step-dot done"></div>
      <div class="step-dot done"></div>
      <div class="step-dot active"></div>
      <div class="step-dot"></div>
    </div>
    <h2>👤 Admin Hesabı Oluştur</h2>
    <?php if ($err): ?><div class="alert alert-err">✗ <?= htmlspecialchars($err) ?></div><?php endif; ?>
    <form method="POST">
      <input type="hidden" name="step" value="3">
      <div class="form-group"><label>Ad Soyad</label><input type="text" name="admin_ad" required></div>
      <div class="form-group"><label>E-posta</label><input type="email" name="admin_email" required></div>
      <div class="form-group"><label>Şifre (min. 8 karakter)</label><input type="password" name="admin_sifre" required minlength="8"></div>
      <div class="form-group"><label>Şifre Tekrar</label><input type="password" name="admin_sifre2" required></div>
      <button type="submit" class="btn btn-primary">Hesabı Oluştur →</button>
    </form>
  </div>
    <?php endif; }

// ── ADIM 4: Tamamlandı ───────────────────────────────────────────────────
if ($step === 4): ?>
  <div class="card">
    <div class="step-indicator">
      <div class="step-dot done"></div>
      <div class="step-dot done"></div>
      <div class="step-dot done"></div>
      <div class="step-dot active"></div>
    </div>
    <h2>🎉 Kurulum Tamamlandı!</h2>
    <div class="alert alert-ok">✓ Minya 3D başarıyla kuruldu.</div>
    <div class="log-box">
      <span class="log-ok">✓ Veritabanı tabloları oluşturuldu</span>
<span class="log-ok">✓ config.php yazıldı</span>
<span class="log-ok">✓ Admin hesabı oluşturuldu</span>
<span class="log-ok">✓ Başlangıç verileri yüklendi</span>
<span class="log-ok">✓ Migration takip tablosu oluşturuldu</span>
<span class="log-info">ℹ Bambu Lab A1 Combo görseli: /assets/img/bambu-a1-combo.webp dosyasını yükleyin</span>
<span class="log-info">ℹ Güvenlik için bu installer/ klasörünü silin veya erişimi engelleyin</span>
    </div>
    <div style="display:flex;flex-direction:column;gap:.75rem;margin-top:1rem">
      <a href="/admin/" class="btn btn-primary">Admin Panele Git →</a>
      <a href="/admin/migrations.php" class="btn btn-blue">Migrations Kontrol Et →</a>
      <a href="/admin/urunler-seed.php" class="btn btn-blue">Ürün Kataloğunu Yükle (130 ürün) →</a>
      <a href="/" class="btn btn-outline" style="text-align:center">Siteyi Gör →</a>
    </div>
    <hr class="divider">
    <div class="alert alert-err" style="font-size:.82rem">
      ⚠️ <strong>GÜVENLİK:</strong> Kurulum tamamlandıktan sonra <code>installer/</code> klasörünü silin
      ya da .htaccess ile erişimi engelleyin.
    </div>
  </div>
<?php endif; ?>

</div>
</body>
</html>
