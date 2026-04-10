<?php
$curPage = basename($_SERVER['PHP_SELF']);
function navItem(string $href, string $icon, string $label, string $cur): string {
    $active = (basename($href) === $cur || (str_contains($href, $cur) && $cur !== 'index.php')) ? 'active' : '';
    return "<li class='nav-item'><a href='$href' class='$active'>$icon <span>$label</span></a></li>";
}
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <img src="/assets/img/logo.svg" alt="Minya 3D">
    <span>ADMIN</span>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-group">
      <div class="nav-group-label">Genel</div>
      <ul>
        <?= navItem('/admin/', '📊', 'Dashboard', $curPage) ?>
      </ul>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Katalog</div>
      <ul>
        <?= navItem('/admin/urunler.php', '📦', 'Ürünler', $curPage) ?>
        <?= navItem('/admin/urun-ekle.php', '➕', 'Ürün Ekle', $curPage) ?>
        <?= navItem('/admin/urunler-seed.php', '🚀', 'Katalog Yükle', $curPage) ?>
        <?= navItem('/admin/kategoriler.php', '🗂️', 'Kategoriler', $curPage) ?>
        <?= navItem('/admin/materyaller.php', '🧪', 'Materyaller', $curPage) ?>
      </ul>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Satış</div>
      <ul>
        <?= navItem('/admin/siparisler.php', '🛒', 'Siparişler', $curPage) ?>
        <?= navItem('/admin/musteriler.php', '👥', 'Müşteriler', $curPage) ?>
      </ul>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">İçerik</div>
      <ul>
        <?= navItem('/admin/blog.php', '✍️', 'Blog', $curPage) ?>
      </ul>
    </div>

    <div class="nav-group">
      <div class="nav-group-label">Sistem</div>
      <ul>
        <?= navItem('/admin/ayarlar.php', '⚙️', 'Ayarlar', $curPage) ?>
        <?= navItem('/admin/guncelle.php', '🔄', 'Güncelleme', $curPage) ?>
        <?= navItem('/admin/yedek.php', '💾', 'Yedekleme', $curPage) ?>
      </ul>
    </div>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="user-avatar"><?= strtoupper(mb_substr($_SESSION['admin_ad'] ?? 'A', 0, 2)) ?></div>
      <div>
        <div style="font-size:.85rem;font-weight:600"><?= e($_SESSION['admin_ad'] ?? '') ?></div>
        <a href="/admin/logout.php" style="font-size:.75rem;color:var(--muted)">Çıkış Yap</a>
      </div>
    </div>
    <div style="margin-top:.75rem;text-align:center">
      <a href="/" target="_blank" style="font-size:.75rem;color:var(--faint)">← Siteyi Gör</a>
    </div>
  </div>
</aside>
