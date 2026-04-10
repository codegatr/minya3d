<?php
$pageTitle = 'Siparişiniz Alındı';
$pageDesc  = 'Siparisiz basariyla olusturuldu. Minya 3D ile 3D baski siparisiz en kisa surede hazirlanacak.';
require_once __DIR__ . '/includes/header.php';

$sid     = (int)($_GET['siparis'] ?? 0);
$siparis = $sid ? DB::row("SELECT s.*, m.ad_soyad, m.email FROM mn_siparisler s LEFT JOIN mn_musteriler m ON m.id=s.musteri_id WHERE s.id=?", [$sid]) : null;
?>

<div style="margin-top:70px;min-height:70vh;display:flex;align-items:center;justify-content:center;padding:4rem 2rem">
  <div style="max-width:560px;width:100%;text-align:center">
    <div style="font-size:5rem;margin-bottom:1.5rem;animation:bounceIn .6s ease">🎉</div>
    <h1 style="font-family:'Orbitron',sans-serif;font-size:2rem;font-weight:900;margin-bottom:.75rem">
      Siparişiniz Alındı!
    </h1>
    <p style="color:var(--muted);font-size:1.05rem;line-height:1.8;margin-bottom:2rem">
      <?php if ($siparis): ?>
      <strong><?= e($siparis['ad_soyad']) ?></strong>, siparişiniz başarıyla oluşturuldu.<br>
      Sipariş numaranız: <strong style="color:var(--blue)">#<?= $siparis['id'] ?></strong><br>
      <?= e($siparis['email']) ?> adresine onay e-postası gönderilecektir.
      <?php else: ?>
      Siparişiniz başarıyla oluşturuldu.
      <?php endif; ?>
    </p>

    <?php if ($siparis): ?>
    <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius2);padding:1.5rem;text-align:left;margin-bottom:2rem">
      <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid var(--border)">
        <span style="color:var(--muted)">Sipariş No</span>
        <strong style="color:var(--blue)">#<?= $siparis['id'] ?></strong>
      </div>
      <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid var(--border)">
        <span style="color:var(--muted)">Toplam</span>
        <strong style="font-family:'Orbitron',sans-serif;color:var(--orange)"><?= para($siparis['toplam']) ?></strong>
      </div>
      <div style="display:flex;justify-content:space-between;padding:.5rem 0">
        <span style="color:var(--muted)">Durum</span>
        <span class="badge badge-orange">Bekliyor</span>
      </div>
    </div>
    <?php endif; ?>

    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
      <a href="/" class="btn btn-primary">Ana Sayfaya Dön</a>
      <a href="/urunler.php" class="btn btn-outline">Alışverişe Devam</a>
    </div>

    <?php if ($wa = ayar('whatsapp','')): ?>
    <div style="margin-top:2rem">
      <a href="https://wa.me/<?= e(preg_replace('/\D/','',$wa)) ?>?text=<?= urlencode('Merhaba, #'.($siparis['id']??'').' numaralı siparişim hakkında bilgi almak istiyorum.') ?>"
         target="_blank" style="color:var(--green);font-size:.9rem">
        💬 Sipariş hakkında WhatsApp'tan ulaşın
      </a>
    </div>
    <?php endif; ?>
  </div>
</div>

<style>
@keyframes bounceIn{
  0%{transform:scale(0);opacity:0}
  60%{transform:scale(1.2)}
  100%{transform:scale(1);opacity:1}
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
