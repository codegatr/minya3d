<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';

$slug = trim($_GET['slug'] ?? '');
if (!$slug) {
    $slug = basename(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
}

$yazi = null;
try {
    $yazi = DB::row("SELECT * FROM mn_blog WHERE slug=? AND aktif=1", [$slug]);
} catch (Throwable) {}

if (!$yazi) {
    $pageTitle = 'Yazı Bulunamadı';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container" style="padding:8rem 2rem;text-align:center"><h2>Yazı Bulunamadı</h2><a href="/blog.php" class="btn btn-primary" style="margin-top:2rem">Blog\'a Dön</a></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// SEO
$pageTitle = $yazi['baslik'];
$pageDesc  = $yazi['ozet']
    ? mb_substr(strip_tags($yazi['ozet']), 0, 155)
    : mb_substr(strip_tags($yazi['icerik'] ?? ''), 0, 155);

SEO::canonical(SITE_URL . '/blog/' . $yazi['slug']);
SEO::addSchema(SEO::schemaBreadcrumb([
    ['Ana Sayfa', SITE_URL . '/'],
    ['Blog',      SITE_URL . '/blog.php'],
    [$yazi['baslik'], SITE_URL . '/blog/' . $yazi['slug']],
]));
SEO::addSchema([
    '@context'         => 'https://schema.org',
    '@type'            => 'BlogPosting',
    'headline'         => $yazi['baslik'],
    'description'      => $pageDesc,
    'datePublished'    => substr($yazi['created_at'], 0, 10),
    'dateModified'     => substr($yazi['updated_at'] ?? $yazi['created_at'], 0, 10),
    'author'           => ['@type' => 'Organization', 'name' => 'Minya 3D'],
    'publisher'        => ['@type' => 'Organization', 'name' => 'Minya 3D', 'logo' => ['@type' => 'ImageObject', 'url' => SITE_URL . '/assets/img/logo.svg']],
    'image'            => $yazi['kapak'] ? UPLOAD_URL . 'urunler/' . $yazi['kapak'] : SITE_URL . '/assets/img/og-default.jpg',
    'url'              => SITE_URL . '/blog/' . $yazi['slug'],
]);
if ($yazi['kapak']) SEO::set(['og_image' => UPLOAD_URL . 'urunler/' . $yazi['kapak']]);

require_once __DIR__ . '/includes/header.php';

// Sonraki / Önceki yazılar
try {
    $onceki = DB::row("SELECT baslik,slug FROM mn_blog WHERE aktif=1 AND id < ? ORDER BY id DESC LIMIT 1", [$yazi['id']]);
    $sonraki = DB::row("SELECT baslik,slug FROM mn_blog WHERE aktif=1 AND id > ? ORDER BY id ASC LIMIT 1", [$yazi['id']]);
} catch (Throwable) { $onceki = $sonraki = null; }
?>

<div style="margin-top:70px"></div>

<div class="container" style="padding:2.5rem 2rem;max-width:820px">
  <div class="breadcrumb">
    <a href="/">Ana Sayfa</a><span>/</span>
    <a href="/blog.php">Blog</a><span>/</span>
    <span class="current"><?= e($yazi['baslik']) ?></span>
  </div>

  <article style="margin-top:1.5rem">

    <!-- BAŞLIK -->
    <header style="margin-bottom:2rem">
      <div style="font-size:.82rem;color:var(--muted);margin-bottom:.75rem;display:flex;align-items:center;gap:1rem">
        <time datetime="<?= substr($yazi['created_at'],0,10) ?>">
          📅 <?= date('d F Y', strtotime($yazi['created_at'])) ?>
        </time>
        <span>·</span>
        <span>✍️ Minya 3D</span>
      </div>
      <h1 style="font-family:'Orbitron',sans-serif;font-size:clamp(1.6rem,3vw,2.4rem);font-weight:700;line-height:1.2;margin-bottom:1rem">
        <?= e($yazi['baslik']) ?>
      </h1>
      <?php if ($yazi['ozet']): ?>
      <p style="font-size:1.1rem;color:var(--muted);line-height:1.8;border-left:3px solid var(--blue);padding-left:1.25rem;margin-bottom:0">
        <?= e($yazi['ozet']) ?>
      </p>
      <?php endif; ?>
    </header>

    <!-- KAPAK GÖRSELİ -->
    <?php if ($yazi['kapak']): ?>
    <figure style="margin-bottom:2rem;border-radius:var(--radius2);overflow:hidden;border:1px solid var(--border)">
      <img src="<?= UPLOAD_URL ?>urunler/<?= e($yazi['kapak']) ?>" alt="<?= e($yazi['baslik']) ?>" style="width:100%;display:block;max-height:440px;object-fit:cover">
    </figure>
    <?php endif; ?>

    <!-- İÇERİK -->
    <div class="blog-content" style="line-height:1.95;color:var(--muted);font-size:1rem">
      <?= $yazi['icerik'] ?>
    </div>

  </article>

  <!-- NAVİGASYON -->
  <nav style="display:flex;justify-content:space-between;gap:1rem;margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border);flex-wrap:wrap">
    <?php if ($onceki): ?>
    <a href="/blog/<?= e($onceki['slug']) ?>" style="flex:1;background:var(--card);border:1px solid var(--border);border-radius:var(--radius2);padding:1rem 1.25rem;transition:border-color .2s;min-width:200px"
       onmouseover="this.style.borderColor='var(--blue)'" onmouseout="this.style.borderColor='var(--border)'">
      <div style="font-size:.75rem;color:var(--muted);margin-bottom:.3rem">← Önceki Yazı</div>
      <div style="font-weight:600;font-size:.92rem"><?= e(mb_substr($onceki['baslik'],0,60)) ?>…</div>
    </a>
    <?php else: ?><div></div><?php endif; ?>
    <?php if ($sonraki): ?>
    <a href="/blog/<?= e($sonraki['slug']) ?>" style="flex:1;background:var(--card);border:1px solid var(--border);border-radius:var(--radius2);padding:1rem 1.25rem;text-align:right;transition:border-color .2s;min-width:200px"
       onmouseover="this.style.borderColor='var(--blue)'" onmouseout="this.style.borderColor='var(--border)'">
      <div style="font-size:.75rem;color:var(--muted);margin-bottom:.3rem">Sonraki Yazı →</div>
      <div style="font-weight:600;font-size:.92rem"><?= e(mb_substr($sonraki['baslik'],0,60)) ?>…</div>
    </a>
    <?php endif; ?>
  </nav>

  <!-- YORUMLA / PAYLAŞ CTA -->
  <div style="margin-top:2.5rem;background:linear-gradient(135deg,rgba(14,165,233,.07),rgba(139,92,246,.06));border:1px solid var(--border);border-radius:var(--radius2);padding:2rem;text-align:center">
    <h3 style="font-family:'Orbitron',sans-serif;font-size:1.1rem;margin-bottom:.6rem">Sipariş vermek ister misiniz?</h3>
    <p style="color:var(--muted);font-size:.92rem;margin-bottom:1.5rem">PLA+ ile 3D baskı siparişinizi hemen oluşturun.</p>
    <div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap">
      <a href="/urunler.php" class="btn btn-primary">📦 Ürünlere Göz At</a>
      <a href="/ozel-siparis.php" class="btn btn-outline">📁 Özel Sipariş</a>
    </div>
  </div>
</div>

<style>
.blog-content h2,.blog-content h3{font-family:'Orbitron',sans-serif;font-weight:700;color:var(--text);margin:2rem 0 .75rem}
.blog-content h2{font-size:1.4rem}.blog-content h3{font-size:1.1rem}
.blog-content p{margin-bottom:1.25rem}
.blog-content ul,.blog-content ol{padding-left:1.5rem;margin-bottom:1.25rem}
.blog-content li{margin-bottom:.4rem}
.blog-content a{color:var(--blue);text-decoration:underline}
.blog-content img{max-width:100%;border-radius:var(--radius2);border:1px solid var(--border);margin:1rem 0}
.blog-content code{background:rgba(14,165,233,.1);border:1px solid rgba(14,165,233,.2);border-radius:4px;padding:.1rem .4rem;font-size:.88em;color:var(--blue)}
.blog-content pre{background:rgba(2,12,27,.8);border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;overflow-x:auto;margin-bottom:1.25rem}
.blog-content blockquote{border-left:3px solid var(--blue);padding-left:1.25rem;color:var(--muted);font-style:italic;margin:1.5rem 0}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
