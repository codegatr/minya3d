<?php
$pageTitle = 'Özel Sipariş';
require_once __DIR__ . '/includes/header.php';
$ok = $hata = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $ad     = trim($_POST['ad'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $tel    = trim($_POST['telefon'] ?? '');
    $materyal = trim($_POST['materyal'] ?? '');
    $adet   = (int)($_POST['adet'] ?? 1);
    $notlar = trim($_POST['notlar'] ?? '');
    $dosyaAd = '';
    if (!$ad || !$email) { $hata = 'Ad ve e-posta zorunludur.'; }
    else {
        if (!empty($_FILES['dosya']['name'])) {
            $izinli = ['stl','obj','3mf','zip','step','iges'];
            $yeni = uploadFile($_FILES['dosya'], UPLOAD_DIR . 'urunler/', $izinli);
            $dosyaAd = $yeni ?: '';
        }
        $body = "Ad: $ad\nE-posta: $email\nTel: $tel\nMateryal: $materyal\nAdet: $adet\nDosya: $dosyaAd\n\nNotlar:\n$notlar";
        @mail(ayar('email', SITE_EMAIL), "Özel Sipariş Talebi – minya3d.com", $body, "From: $email\r\nReply-To: $email\r\n");
        $ok = 'Talebiniz alındı! En kısa sürede fiyat teklifi göndereceğiz.';
    }
}
$materyaller = DB::all("SELECT baslik FROM mn_materyaller WHERE aktif=1 ORDER BY baslik");
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container" style="max-width:860px">
    <span class="section-tag">▸ ÖZEL SİPARİŞ</span>
    <h1 class="section-title">Kendi Tasarımınızı <span>Bastırın</span></h1>
    <p class="section-sub" style="margin-bottom:2.5rem">STL, OBJ veya 3MF dosyanızı yükleyin, materyal ve adet belirtin; fiyat teklifimizi birkaç saat içinde iletelim.</p>

    <?php if ($ok): ?><div class="alert alert-success" style="margin-bottom:1.5rem">✓ <?= e($ok) ?></div><?php endif; ?>
    <?php if ($hata): ?><div class="alert alert-error" style="margin-bottom:1.5rem">✗ <?= e($hata) ?></div><?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem">
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= csrf() ?>">
        <div class="form-row">
          <div class="form-group"><label class="form-label">Ad Soyad *</label><input type="text" name="ad" class="form-control" required></div>
          <div class="form-group"><label class="form-label">E-posta *</label><input type="email" name="email" class="form-control" required></div>
        </div>
        <div class="form-group"><label class="form-label">Telefon</label><input type="tel" name="telefon" class="form-control"></div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Materyal Tercihi</label>
            <select name="materyal" class="form-control">
              <option value="">– Seçiniz –</option>
              <?php foreach ($materyaller as $m): ?><option><?= e($m['baslik']) ?></option><?php endforeach; ?>
              <option>Bilmiyorum / Önerin</option>
            </select>
          </div>
          <div class="form-group"><label class="form-label">Adet</label><input type="number" name="adet" class="form-control" min="1" value="1"></div>
        </div>
        <div class="form-group">
          <label class="form-label">Dosya Yükle (STL, OBJ, 3MF, STEP, ZIP)</label>
          <div style="border:2px dashed rgba(14,165,233,.3);border-radius:10px;padding:1.5rem;text-align:center;cursor:pointer;position:relative;transition:border-color .3s" id="dropArea">
            <input type="file" name="dosya" accept=".stl,.obj,.3mf,.zip,.step,.iges" style="position:absolute;inset:0;opacity:0;cursor:pointer">
            <div style="font-size:2rem;margin-bottom:.5rem">📁</div>
            <div style="font-size:.88rem;color:var(--muted)">Dosyayı buraya sürükleyin veya tıklayın</div>
            <div style="font-size:.78rem;color:var(--faint);margin-top:.3rem">Maks. <?= MAX_UPLOAD_MB ?>MB</div>
          </div>
        </div>
        <div class="form-group"><label class="form-label">Notlar / Özel İstekler</label><textarea name="notlar" class="form-control" style="min-height:100px" placeholder="Renk, boyut, yüzey kalitesi, kaplama vb."></textarea></div>
        <button type="submit" class="btn btn-primary">📩 Teklif İste</button>
      </form>
      <div>
        <div class="card" style="margin-bottom:1.25rem">
          <h3 style="font-family:'Orbitron',sans-serif;font-size:.95rem;margin-bottom:1rem">📋 Nasıl Çalışır?</h3>
          <?php
          $adimlar = [['1','📁','Dosya Yükleme','STL, OBJ, 3MF veya STEP dosyanızı gönderin'],['2','💬','Teklif','24 saat içinde fiyat ve süre teklifi'],['3','✅','Onay','Teklifi onaylayın, ödeme yapın'],['4','🖨️','Üretim','Bambu Lab A1 Combo ile baskı'],['5','📦','Teslimat','Kapınıza teslim']];
          foreach ($adimlar as [$n,$ic,$t,$d]): ?>
          <div style="display:flex;gap:.75rem;align-items:flex-start;padding:.65rem 0;border-bottom:1px solid rgba(14,165,233,.07)">
            <div style="width:28px;height:28px;border-radius:50%;background:rgba(14,165,233,.12);border:1px solid rgba(14,165,233,.25);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:var(--blue);flex-shrink:0"><?= $n ?></div>
            <div><div style="font-weight:500;font-size:.9rem"><?= $t ?></div><div style="font-size:.8rem;color:var(--muted)"><?= $d ?></div></div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php if ($wa = ayar('whatsapp','')): ?>
        <a href="https://wa.me/<?= e(preg_replace('/\D/','',$wa)) ?>" target="_blank" class="btn btn-outline btn-full">💬 WhatsApp ile Anlık Yardım</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
