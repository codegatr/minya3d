<?php
require_once __DIR__ . '/includes/header.php';

$slug = trim($_GET['slug'] ?? $_SERVER['REQUEST_URI'] ?? '');
$slug = basename(parse_url($slug, PHP_URL_PATH));

$urun = DB::row("
    SELECT u.*, k.baslik AS kat_baslik, k.slug AS kat_slug
    FROM mn_urunler u
    LEFT JOIN mn_kategoriler k ON k.id = u.kategori_id
    WHERE u.slug = ? AND u.aktif = 1
", [$slug]);

if (!$urun) {
    http_response_code(404);
    echo '<div class="container" style="padding:8rem 2rem;text-align:center"><h2>Ürün Bulunamadı</h2><a href="/urunler.php" class="btn btn-primary" style="margin-top:2rem">Ürünlere Dön</a></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$gorseller = $urun['gorseller'] ? json_decode($urun['gorseller'], true) : [];
$anaGorsel  = $urun['gorsel'] ?: ($gorseller[0] ?? '');

// Benzer ürünler
$benzerler = DB::all("
    SELECT id,baslik,slug,gorsel,fiyat,indirim_fiyat,materyal
    FROM mn_urunler
    WHERE kategori_id=? AND aktif=1 AND id!=?
    ORDER BY RAND() LIMIT 4
", [$urun['kategori_id'], $urun['id']]);

$pageTitle = $urun['baslik'];
$pageDesc  = mb_substr(strip_tags($urun['aciklama']), 0, 155);
?>

<div style="margin-top:70px"></div>
<div class="container" style="padding:2rem">
  <div class="breadcrumb">
    <a href="/">Ana Sayfa</a><span>/</span>
    <a href="/urunler.php">Ürünler</a><span>/</span>
    <?php if ($urun['kat_slug']): ?>
    <a href="/urunler.php?kat=<?= e($urun['kat_slug']) ?>"><?= e($urun['kat_baslik']) ?></a><span>/</span>
    <?php endif; ?>
    <span class="current"><?= e($urun['baslik']) ?></span>
  </div>

  <div class="urun-detail">

    <!-- GÖRSEL PANEL -->
    <div class="urun-gallery">
      <div class="urun-main-img">
        <img id="mainImg"
          src="<?= $anaGorsel ? UPLOAD_URL.'urunler/'.e($anaGorsel) : SITE_URL.'/assets/img/no-image.webp' ?>"
          alt="<?= e($urun['baslik']) ?>">
      </div>
      <?php if (!empty($gorseller)): ?>
      <div class="urun-thumbs">
        <?php
        $allImgs = array_unique(array_filter([$urun['gorsel'], ...$gorseller]));
        foreach ($allImgs as $img): ?>
        <button class="thumb-btn <?= $img===$anaGorsel?'active':'' ?>"
                data-src="<?= UPLOAD_URL ?>urunler/<?= e($img) ?>">
          <img src="<?= UPLOAD_URL ?>urunler/<?= e($img) ?>" alt="">
        </button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- BİLGİ PANEL -->
    <div class="urun-info">
      <?php if ($urun['kat_baslik']): ?>
      <span class="product-material"><?= e($urun['kat_baslik']) ?></span>
      <?php endif; ?>
      <h1 class="urun-title"><?= e($urun['baslik']) ?></h1>

      <div class="urun-price-wrap">
        <?php if ($urun['indirim_fiyat'] > 0): ?>
        <span class="urun-price"><?= para($urun['indirim_fiyat']) ?></span>
        <span class="urun-price-old"><?= para($urun['fiyat']) ?></span>
        <span class="urun-discount">-<?= round((1-$urun['indirim_fiyat']/$urun['fiyat'])*100) ?>%</span>
        <?php else: ?>
        <span class="urun-price"><?= para($urun['fiyat']) ?></span>
        <?php endif; ?>
      </div>

      <?php if ($urun['materyal']): ?>
      <div class="urun-meta-row"><span>Materyal:</span> <strong><?= e($urun['materyal']) ?></strong></div>
      <?php endif; ?>
      <?php if ($urun['boyut']): ?>
      <div class="urun-meta-row"><span>Boyut:</span> <strong><?= e($urun['boyut']) ?></strong></div>
      <?php endif; ?>
      <div class="urun-meta-row">
        <span>Stok:</span>
        <strong style="color:<?= $urun['stok']>0 ? 'var(--green)' : 'var(--red)' ?>">
          <?= $urun['stok'] > 0 ? 'Stokta Var' : 'Stok Yok' ?>
        </strong>
      </div>

      <?php if ($urun['aciklama']): ?>
      <div class="urun-desc"><?= $urun['aciklama'] ?></div>
      <?php endif; ?>

      <div class="urun-actions">
        <div class="qty-selector">
          <button class="qty-btn" id="qtyMinus">−</button>
          <span class="qty-val" id="qtyVal">1</span>
          <button class="qty-btn" id="qtyPlus">+</button>
        </div>
        <button class="btn btn-primary btn-full add-to-cart-main" data-id="<?= $urun['id'] ?>">
          🛒 Sepete Ekle
        </button>
      </div>

      <?php if ($urun['whatsapp'] ?? ayar('whatsapp','')): ?>
      <a href="https://wa.me/<?= e(preg_replace('/\D/', '', ayar('whatsapp',''))) ?>?text=<?= urlencode('Merhaba, '.$urun['baslik'].' ürünü hakkında bilgi almak istiyorum.') ?>"
         target="_blank" class="btn btn-outline btn-full" style="margin-top:.75rem">
        💬 WhatsApp ile Sor
      </a>
      <?php endif; ?>

    </div>

  </div>

  <!-- BENZER ÜRÜNLER -->
  <?php if (!empty($benzerler)): ?>
  <div style="margin-top:4rem">
    <h2 class="section-title" style="margin-bottom:1.5rem">Benzer <span>Ürünler</span></h2>
    <div class="products-grid" style="grid-template-columns:repeat(auto-fill,minmax(230px,1fr))">
      <?php foreach ($benzerler as $b): ?>
      <div class="product-card">
        <a href="/urun/<?= e($b['slug']) ?>" class="product-thumb" style="display:block">
          <?php if ($b['gorsel']): ?>
          <img src="<?= UPLOAD_URL ?>urunler/<?= e($b['gorsel']) ?>" alt="<?= e($b['baslik']) ?>">
          <?php else: ?>
          <span class="product-thumb-placeholder">📦</span>
          <?php endif; ?>
        </a>
        <div class="product-body">
          <span class="product-material"><?= e($b['materyal']) ?></span>
          <a href="/urun/<?= e($b['slug']) ?>" class="product-title"><?= e($b['baslik']) ?></a>
          <div class="product-footer">
            <div class="product-price"><?= para($b['indirim_fiyat']>0?$b['indirim_fiyat']:$b['fiyat']) ?></div>
            <button class="add-to-cart" data-id="<?= $b['id'] ?>">+</button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<style>
.urun-detail{display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:start}
.urun-main-img{background:var(--card);border:1px solid var(--border);border-radius:var(--radius2);overflow:hidden;aspect-ratio:1;display:flex;align-items:center;justify-content:center}
.urun-main-img img{width:100%;height:100%;object-fit:contain;padding:1rem}
.urun-thumbs{display:flex;gap:.6rem;margin-top:.75rem;flex-wrap:wrap}
.thumb-btn{width:72px;height:72px;border-radius:8px;border:1px solid var(--border);overflow:hidden;cursor:pointer;transition:border-color .2s;background:var(--card)}
.thumb-btn img{width:100%;height:100%;object-fit:cover}
.thumb-btn.active,.thumb-btn:hover{border-color:var(--blue)}
.urun-title{font-family:'Orbitron',sans-serif;font-size:1.6rem;font-weight:700;margin:.75rem 0}
.urun-price-wrap{display:flex;align-items:baseline;gap:1rem;margin:1rem 0}
.urun-price{font-family:'Orbitron',sans-serif;font-size:2rem;font-weight:700;color:var(--orange)}
.urun-price-old{font-size:1.1rem;color:var(--faint);text-decoration:line-through}
.urun-discount{background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);padding:.2rem .6rem;border-radius:6px;font-size:.82rem;font-weight:700}
.urun-meta-row{display:flex;gap:.5rem;font-size:.92rem;margin:.4rem 0;color:var(--muted)}
.urun-meta-row span{min-width:80px}
.urun-meta-row strong{color:var(--text)}
.urun-desc{margin:1.5rem 0;color:var(--muted);line-height:1.8;font-size:.95rem}
.urun-actions{display:flex;gap:1rem;align-items:center;margin-top:1.75rem}
.qty-selector{display:flex;align-items:center;gap:.5rem;background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:.25rem .5rem}
.add-to-cart-main{flex:1}
@media(max-width:900px){.urun-detail{grid-template-columns:1fr}}
</style>

<script>
let qty = 1;
const qtyVal = document.getElementById('qtyVal');
document.getElementById('qtyPlus')?.addEventListener('click',()=>{ qty=Math.min(qty+1,99); qtyVal.textContent=qty; });
document.getElementById('qtyMinus')?.addEventListener('click',()=>{ qty=Math.max(qty-1,1); qtyVal.textContent=qty; });

document.querySelector('.add-to-cart-main')?.addEventListener('click',async function(){
  const id = this.dataset.id;
  for(let i=0;i<qty;i++){
    await fetch('/api/sepet.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`action=ekle&urun_id=${id}`});
  }
  const data = await (await fetch('/api/sepet.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`action=ekle&urun_id=${id}`})).json();
  this.textContent = '✓ Sepete Eklendi!';
  this.style.background = 'linear-gradient(135deg,var(--green),#16a34a)';
  const b = document.querySelector('.cart-badge'); if(b) b.textContent = data.sepet_adet;
  setTimeout(()=>{ this.textContent='🛒 Sepete Ekle'; this.style.background=''; },2500);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
