<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';

$pageTitle = 'Blog – 3D Baskı Rehberleri ve Haberler';
$pageDesc  = '3D baskı materyal rehberleri, PLA+ ipuçları, Bambu Lab kullanım kılavuzları, sektör haberleri ve proje ilham kaynakları. Minya 3D blogu.';

SEO::canonical(SITE_URL . '/blog.php');
SEO::addSchema([
    '@context' => 'https://schema.org',
    '@type'    => 'Blog',
    'name'     => 'Minya 3D Blog',
    'url'      => SITE_URL . '/blog.php',
    'description' => $pageDesc,
    'publisher' => ['@type' => 'Organization', 'name' => 'Minya 3D'],
]);

require_once __DIR__ . '/includes/header.php';

// Blog tablosu henüz oluşturulmamış olabilir, güvenli kontrol
try {
    $yazilar = DB::all("SELECT * FROM mn_blog WHERE aktif=1 ORDER BY id DESC LIMIT 12");
} catch (Throwable $e) {
    $yazilar = [];
}
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container">
    <span class="section-tag">▸ BLOG</span>
    <h1 class="section-title">3D Baskı <span>Dünyası</span></h1>
    <p class="section-sub" style="margin-bottom:3rem">Materyal rehberleri, teknik ipuçları, proje hikayeleri ve sektör haberleri.</p>

    <?php if (empty($yazilar)): ?>
    <div style="text-align:center;padding:4rem;background:var(--card);border:1px solid var(--border);border-radius:var(--radius2)">
      <div style="font-size:3rem;margin-bottom:1rem">✍️</div>
      <h3 style="margin-bottom:.75rem">Yakında Blog Yazıları</h3>
      <p style="color:var(--muted)">İçeriklerimiz hazırlanıyor. Takipte kalın!</p>
    </div>
    <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem">
      <?php foreach ($yazilar as $y): ?>
      <a href="/blog/<?= e($y['slug']) ?>" class="product-card" style="text-decoration:none">
        <?php if ($y['kapak']): ?>
        <div class="product-thumb"><img src="<?= UPLOAD_URL ?>blog/<?= e($y['kapak']) ?>" alt="<?= e($y['baslik']) ?>"></div>
        <?php endif; ?>
        <div class="product-body">
          <div style="font-size:.75rem;color:var(--blue);margin-bottom:.5rem;letter-spacing:.08em"><?= date('d.m.Y', strtotime($y['created_at'])) ?></div>
          <div class="product-title" style="font-size:1rem;margin-bottom:.5rem"><?= e($y['baslik']) ?></div>
          <p style="font-size:.85rem;color:var(--muted);line-height:1.7"><?= e(mb_substr(strip_tags($y['ozet']??''),0,110)) ?>…</p>
          <div style="margin-top:1rem;color:var(--blue);font-size:.85rem;font-weight:600">Devamını Oku →</div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
