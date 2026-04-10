<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$cartCount = sepetAdet();
$siteName  = ayar('site_adi', SITE_NAME);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' – ' : '' ?><?= e($siteName) ?></title>
<meta name="description" content="<?= e($pageDesc ?? '3D baskı ürünleri ve hizmetleri. Endüstriyel kalitede PLA, ABS, Reçine ve Metal materyallerle üretim.') ?>">
<link rel="icon" href="/assets/img/favicon.ico">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Exo+2:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/main.css?v=<?= APP_VERSION ?>">
</head>
<body>

<nav class="navbar" id="navbar">
  <div class="nav-container">

    <a href="/" class="nav-brand">
      <img src="/assets/img/logo.svg" alt="<?= e($siteName) ?>" class="nav-logo">
    </a>

    <ul class="nav-links" id="navLinks">
      <li><a href="/urunler.php">Ürünler</a></li>
      <li><a href="/kategoriler.php">Kategoriler</a></li>
      <li><a href="/materyaller.php">Materyaller</a></li>
      <li><a href="/ozel-siparis.php">Özel Sipariş</a></li>
      <li><a href="/hakkimizda.php">Hakkımızda</a></li>
      <li><a href="/iletisim.php">İletişim</a></li>
    </ul>

    <div class="nav-actions">
      <a href="/sepet.php" class="nav-cart" aria-label="Sepet">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <?php if ($cartCount > 0): ?>
        <span class="cart-badge"><?= $cartCount ?></span>
        <?php endif; ?>
      </a>
      <button class="nav-burger" id="navBurger" aria-label="Menü">
        <span></span><span></span><span></span>
      </button>
    </div>

  </div>
</nav>
