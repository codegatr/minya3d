<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (isAdmin()) redirect('/admin/');

$hata = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) { $hata = 'Güvenlik hatası.'; }
    else {
        $email    = trim($_POST['email'] ?? '');
        $sifre    = $_POST['sifre'] ?? '';
        $admin    = DB::row("SELECT * FROM mn_adminler WHERE email=? AND aktif=1", [$email]);
        if ($admin && password_verify($sifre, $admin['sifre_hash'])) {
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_ad']   = $admin['ad'];
            $_SESSION['admin_role'] = $admin['rol'];
            DB::q("UPDATE mn_adminler SET son_giris=NOW() WHERE id=?", [$admin['id']]);
            redirect('/admin/');
        } else {
            $hata = 'E-posta veya şifre hatalı.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Giriş – <?= SITE_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Exo+2:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/admin.css?v=<?= APP_VERSION ?>">
</head>
<body class="login-body">
<div class="login-wrap">
  <div class="login-box">
    <div class="login-logo">
      <img src="/assets/img/logo.svg" alt="<?= SITE_NAME ?>">
    </div>
    <h1>Admin Paneli</h1>
    <p>Giriş yapmak için bilgilerinizi giriniz.</p>

    <?php if ($hata): ?>
    <div class="alert alert-error"><?= e($hata) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">
      <div class="form-group">
        <label class="form-label">E-posta</label>
        <input type="email" name="email" class="form-control" required autocomplete="email" autofocus>
      </div>
      <div class="form-group">
        <label class="form-label">Şifre</label>
        <input type="password" name="sifre" class="form-control" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn btn-primary btn-full" style="margin-top:.5rem">
        Giriş Yap →
      </button>
    </form>

    <p style="text-align:center;margin-top:1.5rem;font-size:.85rem;color:var(--muted)">
      <a href="/" style="color:var(--blue)">← Siteye Dön</a>
    </p>
  </div>
</div>
</body>
</html>
