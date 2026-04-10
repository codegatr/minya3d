<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();

$adminTitle = $adminTitle ?? 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($adminTitle) ?> – <?= SITE_NAME ?> Admin</title>
<meta name="robots" content="noindex,nofollow">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/admin.css?v=<?= APP_VERSION ?>">
</head>
<body>
<div class="admin-layout">
<?php require_once __DIR__ . '/sidebar.php'; ?>
<div class="admin-main">
<header class="admin-topbar">
  <div style="display:flex;align-items:center;gap:1rem">
    <button id="sidebarToggle" style="display:none;color:var(--muted);font-size:1.2rem" onclick="document.getElementById('sidebar').classList.toggle('mobile-open')">☰</button>
    <span class="topbar-title"><?= e($adminTitle) ?></span>
  </div>
  <div class="topbar-actions">
    <?php
    $yeniSiparis = DB::row("SELECT COUNT(*) AS c FROM mn_siparisler WHERE durum='bekliyor'")['c'] ?? 0;
    if ($yeniSiparis > 0): ?>
    <a href="/admin/siparisler.php" class="badge badge-orange" style="text-decoration:none">
      🛒 <?= $yeniSiparis ?> yeni sipariş
    </a>
    <?php endif; ?>
    <span style="font-size:.8rem;color:var(--faint)">v<?= APP_VERSION ?></span>
  </div>
</header>
<div class="admin-content">
