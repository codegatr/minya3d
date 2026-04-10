<?php
http_response_code(404);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';

$pageTitle = 'Sayfa Bulunamadı (404)';
$pageDesc  = 'Aradığınız sayfa bulunamadı. Ana sayfaya dönün veya ürünleri keşfedin.';
SEO::noindex();

require_once __DIR__ . '/includes/header.php';

// Popüler ürünler
$popular = DB::all("SELECT u.id, u.baslik, u.slug, u.gorsel, u.fiyat, k.slug AS kat_slug FROM mn_urunler u LEFT JOIN mn_kategoriler k ON k.id=u.kategori_id WHERE u.aktif=1 AND u.vitrin=1 LIMIT 4");
?>

<div style="margin-top:70px;min-height:70vh;display:flex;align-items:center;justify-content:center;padding:4rem 2rem;position:relative;z-index:1">
  <div style="max-width:600px;width:100%;text-align:center">

    <!-- 404 animasyonlu metin -->
    <div style="font-family:'Orbitron',sans-serif;font-size:clamp(5rem,15vw,10rem);font-weight:900;line-height:1;
                background:linear-gradient(135deg,var(--blue),var(--purple));
                -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                margin-bottom:1.5rem;user-select:none">
      404
    </div>

    <h1 style="font-family:'Orbitron',sans-serif;font-size:1.4rem;font-weight:700;margin-bottom:.75rem">
      Sayfa Bulunamadı
    </h1>
    <p style="color:var(--muted);line-height:1.8;margin-bottom:2.5rem;font-size:1rem">
      Aradığınız sayfa taşınmış, silinmiş veya hiç var olmamış olabilir.<br>
      Ana sayfaya dönün ya da ürünleri keşfetmeye devam edin.
    </p>

    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-bottom:3rem">
      <a href="/" class="btn btn-primary">🏠 Ana Sayfaya Dön</a>
      <a href="/urunler.php" class="btn btn-outline">📦 Ürünleri Gör</a>
      <a href="/arama.php" class="btn btn-outline">🔍 Ürün Ara</a>
    </div>

    <!-- Popüler ürünler -->
    <?php if (!empty($popular)): ?>
    <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius2);padding:1.75rem">
      <h3 style="font-family:'Orbitron',sans-serif;font-size:.95rem;margin-bottom:1.25rem;color:var(--muted)">
        POPÜLER ÜRÜNLER
      </h3>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:.75rem">
        <?php foreach ($popular as $u): ?>
        <a href="/urun/<?= e($u['slug']) ?>"
           style="background:rgba(14,165,233,.05);border:1px solid var(--border);border-radius:10px;
                  padding:.75rem;text-align:center;transition:all .2s;display:block"
           onmouseover="this.style.borderColor='var(--blue)'"
           onmouseout="this.style.borderColor='var(--border)'">
          <img src="<?= urunGorsel($u) ?>"
               alt="<?= e($u['baslik']) ?>"
               style="width:60px;height:60px;object-fit:cover;border-radius:8px;margin:0 auto .5rem;display:block">
          <div style="font-size:.78rem;font-weight:600;line-height:1.3;margin-bottom:.3rem">
            <?= e(mb_substr($u['baslik'], 0, 30)) ?>
          </div>
          <div style="font-family:'Orbitron',sans-serif;font-size:.8rem;color:var(--orange)">
            <?= para($u['fiyat']) ?>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
