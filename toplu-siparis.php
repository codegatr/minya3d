<?php
$pageTitle = 'Toplu Sipariş';
$pageDesc  = '50 adet ve uzeri toplu 3D baski siparisi. Kurumsal fiyat, oncelikli uretim, e-fatura.';
require_once __DIR__ . '/includes/header.php';
$ok = $hata = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $firma   = trim($_POST['firma']   ?? '');
    $ad      = trim($_POST['ad']      ?? '');
    $email   = trim($_POST['email']   ?? '');
    $tel     = trim($_POST['telefon'] ?? '');
    $urun    = trim($_POST['urun']    ?? '');
    $adet    = trim($_POST['adet']    ?? '');
    $notlar  = trim($_POST['notlar']  ?? '');
    if (!$ad || !$email || !$urun) { $hata = 'Lütfen zorunlu alanları doldurun.'; }
    else {
        $body = "Firma: $firma\nAd: $ad\nE-posta: $email\nTel: $tel\nÜrün/Açıklama: $urun\nAdet: $adet\n\nNotlar:\n$notlar";
        @mail(ayar('email',SITE_EMAIL), "Toplu Sipariş Talebi – minya3d.com", $body, "From: $email\r\nReply-To: $email\r\n");
        $ok = 'Talebiniz alındı! 24 saat içinde fiyat teklifi ve üretim süresi için size dönüş yapacağız.';
    }
}
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container" style="max-width:900px">
    <span class="section-tag">▸ TOPLU SİPARİŞ</span>
    <h1 class="section-title">Kurumsal & <span>Toplu Sipariş</span></h1>
    <p class="section-sub" style="margin-bottom:2.5rem">50 adetten başlayan toplu siparişlerde özel fiyatlandırma ve öncelikli üretim.</p>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem">
      <div>
        <?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>
        <?php if ($hata): ?><div class="alert alert-error">✗ <?= e($hata) ?></div><?php endif; ?>
        <form method="POST">
          <input type="hidden" name="csrf" value="<?= csrf() ?>">
          <div class="form-row">
            <div class="form-group"><label class="form-label">Firma / Kurum</label><input type="text" name="firma" class="form-control"></div>
            <div class="form-group"><label class="form-label">Yetkili Ad Soyad *</label><input type="text" name="ad" class="form-control" required></div>
          </div>
          <div class="form-row">
            <div class="form-group"><label class="form-label">E-posta *</label><input type="email" name="email" class="form-control" required></div>
            <div class="form-group"><label class="form-label">Telefon</label><input type="tel" name="telefon" class="form-control"></div>
          </div>
          <div class="form-group"><label class="form-label">Ürün / Açıklama *</label><textarea name="urun" class="form-control" style="min-height:100px" placeholder="Ne üretmek istediğinizi detaylıca anlatın..." required></textarea></div>
          <div class="form-group"><label class="form-label">Tahmini Adet</label><input type="text" name="adet" class="form-control" placeholder="ör: 500 adet"></div>
          <div class="form-group"><label class="form-label">Ek Notlar</label><textarea name="notlar" class="form-control" style="min-height:80px" placeholder="Materyal tercihi, renk, termin tarihi..."></textarea></div>
          <button type="submit" class="btn btn-primary">📩 Teklif İste</button>
        </form>
      </div>
      <div>
        <div class="card" style="margin-bottom:1.25rem">
          <h3 style="font-family:'Orbitron',sans-serif;font-size:.95rem;margin-bottom:1.25rem">💼 Kurumsal Avantajlar</h3>
          <?php
          $avantajlar = ['50+ adette %15 indirim','100+ adette %25 indirim','Öncelikli üretim sırası','Özel ambalaj ve etiket','E-fatura ve kurumsal sözleşme','Tekrar sipariş için sabit fiyat garantisi'];
          foreach ($avantajlar as $a): ?>
          <div style="display:flex;align-items:center;gap:.6rem;padding:.5rem 0;border-bottom:1px solid rgba(14,165,233,.08);font-size:.88rem">
            <span style="color:var(--green);font-size:1rem">✓</span>
            <span><?= e($a) ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="card">
          <h3 style="font-family:'Orbitron',sans-serif;font-size:.95rem;margin-bottom:.75rem">📞 Doğrudan İletişim</h3>
          <p style="color:var(--muted);font-size:.88rem;line-height:1.7;margin-bottom:1rem">Acil veya büyük hacimli projeler için doğrudan ulaşın.</p>
          <?php if ($tel = ayar('telefon','')): ?>
          <a href="tel:<?= e($tel) ?>" class="btn btn-outline btn-full" style="margin-bottom:.75rem">📞 <?= e($tel) ?></a>
          <?php endif; ?>
          <?php if ($wa = ayar('whatsapp','')): ?>
          <a href="https://wa.me/<?= e(preg_replace('/\D/','',$wa)) ?>" target="_blank" class="btn btn-outline btn-full">💬 WhatsApp</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
