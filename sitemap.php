<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/seo.php';

header('Content-Type: application/xml; charset=utf-8');
$base   = rtrim(SITE_URL, '/');
$today  = date('Y-m-d');

$urls = [];

// ── Statik sayfalar ───────────────────────────────────────────────────────────
$statik = [
    ['/',               '1.0',  'daily',   $today],
    ['/urunler.php',    '0.9',  'daily',   $today],
    ['/kategoriler.php','0.8',  'weekly',  $today],
    ['/materyaller.php','0.7',  'monthly', $today],
    ['/ozel-siparis.php','0.8', 'monthly', $today],
    ['/toplu-siparis.php','0.7','monthly', $today],
    ['/hakkimizda.php', '0.6',  'monthly', $today],
    ['/iletisim.php',   '0.6',  'monthly', $today],
    ['/blog.php',       '0.8',  'weekly',  $today],
    ['/referanslar.php','0.5',  'monthly', $today],
    ['/kvkk.php',       '0.4',  'monthly', $today],
];
foreach ($statik as [$loc,$pr,$ch,$lm]) {
    $urls[] = compact('loc','pr','ch','lm');
}

// ── Kategoriler ───────────────────────────────────────────────────────────────
$kats = DB::all("SELECT slug, updated_at FROM mn_kategoriler WHERE aktif=1");
foreach ($kats as $k) {
    $urls[] = ['loc'=>"/kategori/{$k['slug']}",'pr'=>'0.8','ch'=>'weekly','lm'=>substr($k['updated_at']??$today,0,10)];
}

// ── Ürünler ───────────────────────────────────────────────────────────────────
$urunler = DB::all("SELECT slug, updated_at FROM mn_urunler WHERE aktif=1");
foreach ($urunler as $u) {
    $urls[] = ['loc'=>"/urun/{$u['slug']}",'pr'=>'0.9','ch'=>'weekly','lm'=>substr($u['updated_at']??$today,0,10)];
}

// ── Blog yazıları ─────────────────────────────────────────────────────────────
try {
    $blog = DB::all("SELECT slug, updated_at FROM mn_blog WHERE aktif=1");
    foreach ($blog as $b) {
        $urls[] = ['loc'=>"/blog/{$b['slug']}",'pr'=>'0.7','ch'=>'weekly','lm'=>substr($b['updated_at']??$today,0,10)];
    }
} catch (Throwable) {}

// ── 81 İl sayfaları ───────────────────────────────────────────────────────────
$iller = seo_iller();
foreach ($iller as $slug => $il) {
    $urls[] = ['loc'=>"/$slug-3d-baski",'pr'=>'0.85','ch'=>'monthly','lm'=>$today];
}

// ── Konya ilçeleri ────────────────────────────────────────────────────────────
foreach (seo_konya_ilceleri() as $ilce) {
    $urls[] = ['loc'=>"/konya-$ilce-3d-baski",'pr'=>'0.75','ch'=>'monthly','lm'=>$today];
}

// ── XML çıktısı ───────────────────────────────────────────────────────────────
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    $loc = htmlspecialchars($base . $u['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8');
    echo "  <url>\n";
    echo "    <loc>$loc</loc>\n";
    echo "    <lastmod>{$u['lm']}</lastmod>\n";
    echo "    <changefreq>{$u['ch']}</changefreq>\n";
    echo "    <priority>{$u['pr']}</priority>\n";
    echo "  </url>\n";
}
echo '</urlset>';
