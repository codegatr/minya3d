<?php
$pageTitle = 'İletişim';
$pageDesc  = 'Minya 3D ile iletisime gecin. Siparis, ozel uretim ve destek icin bize ulasin. Konya, Turkiye.';
require_once __DIR__ . '/includes/header.php';
$ok = $hata = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $ad      = trim($_POST['ad'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $konu    = trim($_POST['konu'] ?? '');
    $mesaj   = trim($_POST['mesaj'] ?? '');
    if (!$ad || !$email || !$mesaj) $hata = 'Lütfen tüm alanları doldurun.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $hata = 'Geçerli e-posta giriniz.';
    else {
        $to = ayar('email', SITE_EMAIL);
        $subject = "[$konu] – minya3d.com İletişim Formu";
        $body = "Ad: $ad\nE-posta: $email\nKonu: $konu\n\n$mesaj";
        @mail($to, $subject, $body, "From: $email\r\nReply-To: $email\r\n");
        $ok = 'Mesajınız iletildi. En kısa sürede geri döneceğiz.';
    }
}
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container" style="max-width:900px">
    <span class="section-tag">▸ İLETİŞİM</span>
    <h1 class="section-title">Bizimle <span>İletişime Geçin</span></h1>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;margin-top:2.5rem">
      <div>
        <?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>
        <?php if ($hata): ?><div class="alert alert-error">✗ <?= e($hata) ?></div><?php endif; ?>
        <form method="POST">
          <input type="hidden" name="csrf" value="<?= csrf() ?>">
          <div class="form-row">
            <div class="form-group"><label class="form-label">Ad Soyad *</label><input type="text" name="ad" class="form-control" required></div>
            <div class="form-group"><label class="form-label">E-posta *</label><input type="email" name="email" class="form-control" required></div>
          </div>
          <div class="form-group"><label class="form-label">Konu</label>
            <select name="konu" class="form-control">
              <option>Genel Bilgi</option><option>Özel Sipariş</option><option>Teknik Destek</option><option>Toplu Sipariş</option><option>Şikayet</option>
            </select>
          </div>
          <div class="form-group"><label class="form-label">Mesaj *</label><textarea name="mesaj" class="form-control" style="min-height:140px" required></textarea></div>
          <button type="submit" class="btn btn-primary">📩 Gönder</button>
        </form>
      </div>
      <div>
        <div class="card" style="margin-bottom:1.25rem">
          <div style="display:flex;gap:1rem;align-items:flex-start;margin-bottom:1.25rem">
            <div style="font-size:1.5rem">📍</div>
            <div><div style="font-weight:600;margin-bottom:.3rem">Adres</div><div style="color:var(--muted);font-size:.9rem"><?= nl2br(e(ayar('adres','Konya, Türkiye'))) ?></div></div>
          </div>
          <div style="display:flex;gap:1rem;align-items:flex-start;margin-bottom:1.25rem">
            <div style="font-size:1.5rem">📧</div>
            <div><div style="font-weight:600;margin-bottom:.3rem">E-posta</div><a href="mailto:<?= e(ayar('email',SITE_EMAIL)) ?>" style="color:var(--blue);font-size:.9rem"><?= e(ayar('email',SITE_EMAIL)) ?></a></div>
          </div>
          <?php if ($tel = ayar('telefon','')): ?>
          <div style="display:flex;gap:1rem;align-items:flex-start">
            <div style="font-size:1.5rem">📞</div>
            <div><div style="font-weight:600;margin-bottom:.3rem">Telefon</div><a href="tel:<?= e($tel) ?>" style="color:var(--blue);font-size:.9rem"><?= e($tel) ?></a></div>
          </div>
          <?php endif; ?>
        </div>
        <?php if ($wa = ayar('whatsapp','')): ?>
        <a href="https://wa.me/<?= e(preg_replace('/\D/','',$wa)) ?>" target="_blank" class="btn btn-outline btn-full" style="margin-bottom:1rem">
          💬 WhatsApp ile Yazın
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
