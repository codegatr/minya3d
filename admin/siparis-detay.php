<?php $adminTitle = 'Sipariş Detay'; require_once __DIR__ . '/includes/header.php'; ?>
<?php
$id      = (int)($_GET['id'] ?? 0);
$siparis = DB::row("
    SELECT s.*, m.ad_soyad, m.email, m.telefon
    FROM mn_siparisler s
    LEFT JOIN mn_musteriler m ON m.id = s.musteri_id
    WHERE s.id = ?
", [$id]);

if (!$siparis) {
    echo '<div class="alert alert-error">Sipariş bulunamadı.</div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$kalemler = DB::all("SELECT * FROM mn_siparis_kalemleri WHERE siparis_id = ?", [$id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $fields = ['durum','odeme_durum','kargo_no','kargo_firma'];
    $data   = [];
    foreach ($fields as $f) {
        if (isset($_POST[$f])) $data[$f] = trim($_POST[$f]);
    }
    if ($data) DB::update('mn_siparisler', $data, 'id=?', [$id]);

    // Kargo numarası girildiyse müşteriye email gönder
    if (!empty($data['kargo_no']) && !empty($siparis['email'])) {
        $kargoNo   = $data['kargo_no'];
        $kargoFirma= $data['kargo_firma'] ?? '';
        $subject   = "Siparişiniz Kargoya Verildi – #$id";
        $body      = "Merhaba {$siparis['ad_soyad']},\n\n"
            . "#{$id} numaralı siparişiniz kargoya verilmiştir.\n\n"
            . "Kargo Firması : $kargoFirma\n"
            . "Takip Numarası: $kargoNo\n\n"
            . "İyi günler dileriz,\nMinya 3D";
        @mail($siparis['email'], $subject, $body,
            "From: " . ayar('email', SITE_EMAIL) . "\r\nReply-To: " . ayar('email', SITE_EMAIL));
    }

    flash('ok', 'Sipariş güncellendi.');
    redirect('/admin/siparis-detay.php?id=' . $id);
}

$ok = getFlash('ok');
$durumlar    = ['bekliyor'=>'Bekliyor','hazirlaniyor'=>'Hazırlanıyor','kargoda'=>'Kargoda','tamamlandi'=>'Tamamlandı','iptal'=>'İptal'];
$durumBadge  = ['bekliyor'=>'badge-orange','hazirlaniyor'=>'badge-blue','kargoda'=>'badge-purple','tamamlandi'=>'badge-green','iptal'=>'badge-red'];
$adres       = $siparis['adres_snapshot'] ? json_decode($siparis['adres_snapshot'], true) : [];
$kargoFirmalar = ['','Yurtiçi Kargo','MNG Kargo','Aras Kargo','PTT Kargo','Sürat Kargo','UPS','DHL'];
?>

<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start">

  <!-- Sol: Kalemler + Adres -->
  <div>
    <div class="card">
      <div class="card-header">
        <span class="card-title">📦 Sipariş #<?= $id ?></span>
        <div style="display:flex;align-items:center;gap:.75rem">
          <span class="badge <?= $durumBadge[$siparis['durum']] ?? 'badge-gray' ?>"><?= $durumlar[$siparis['durum']] ?? $siparis['durum'] ?></span>
          <span style="color:var(--muted);font-size:.8rem"><?= date('d.m.Y H:i', strtotime($siparis['created_at'])) ?></span>
        </div>
      </div>
      <div class="table-wrap">
        <table class="admin-table">
          <thead><tr><th>Ürün</th><th>Birim</th><th>Adet</th><th>Toplam</th></tr></thead>
          <tbody>
            <?php foreach ($kalemler as $k): ?>
            <tr>
              <td style="font-weight:500"><?= e($k['baslik']) ?></td>
              <td>₺<?= number_format($k['fiyat'], 2, ',', '.') ?></td>
              <td><?= $k['adet'] ?></td>
              <td style="font-family:'Orbitron',sans-serif;color:var(--orange)">₺<?= number_format($k['toplam'], 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr style="border-top:1px solid var(--border)">
              <td colspan="3" style="text-align:right;color:var(--muted)">Kargo:</td>
              <td>₺<?= number_format($siparis['kargo'], 2, ',', '.') ?></td>
            </tr>
            <tr>
              <td colspan="3" style="text-align:right;font-weight:700">TOPLAM:</td>
              <td style="font-family:'Orbitron',sans-serif;color:var(--orange);font-size:1.1rem">
                ₺<?= number_format($siparis['toplam'], 2, ',', '.') ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <?php if ($adres): ?>
    <div class="card">
      <div class="card-header"><span class="card-title">📍 Teslimat Adresi</span></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
        <div>
          <div style="font-weight:600;margin-bottom:.3rem"><?= e($adres['ad_soyad'] ?? '') ?></div>
          <div style="color:var(--muted);font-size:.88rem;line-height:1.8">
            <?= e($adres['adres'] ?? '') ?><br>
            <?= e(($adres['ilce'] ?? '') . ' / ' . ($adres['sehir'] ?? '')) ?>
            <?php if (!empty($adres['posta_kodu'])): ?> <?= e($adres['posta_kodu']) ?><?php endif; ?><br>
            <?= e($adres['telefon'] ?? '') ?>
          </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:.5rem;justify-content:center">
          <?php if (!empty($adres['telefon'])): ?>
          <a href="https://wa.me/<?= e(preg_replace('/\D/', '', $adres['telefon'])) ?>?text=<?= urlencode("Merhaba {$adres['ad_soyad']}, Minya 3D #$id numaralı siparişiniz hakkında...") ?>"
             target="_blank" class="btn btn-outline btn-sm">💬 WhatsApp</a>
          <?php endif; ?>
          <?php if (!empty($siparis['email'])): ?>
          <a href="mailto:<?= e($siparis['email']) ?>?subject=<?= urlencode("Sipariş #$id – Minya 3D") ?>"
             class="btn btn-outline btn-sm">📧 E-posta</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($siparis['not'])): ?>
    <div class="card">
      <div class="card-header"><span class="card-title">📝 Müşteri Notu</span></div>
      <p style="color:var(--muted);font-size:.9rem;line-height:1.8"><?= e($siparis['not']) ?></p>
    </div>
    <?php endif; ?>
  </div>

  <!-- Sağ: Güncelleme -->
  <div>
    <div class="card">
      <div class="card-header"><span class="card-title">🔧 Sipariş Güncelle</span></div>
      <form method="POST">
        <input type="hidden" name="csrf" value="<?= csrf() ?>">

        <div class="form-group">
          <label class="form-label">Sipariş Durumu</label>
          <select name="durum" class="form-control">
            <?php foreach ($durumlar as $v => $l): ?>
            <option value="<?= $v ?>" <?= $siparis['durum'] === $v ? 'selected' : '' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Ödeme Durumu</label>
          <select name="odeme_durum" class="form-control">
            <option value="bekliyor" <?= $siparis['odeme_durum'] === 'bekliyor' ? 'selected' : '' ?>>Bekliyor</option>
            <option value="odendi"   <?= $siparis['odeme_durum'] === 'odendi'   ? 'selected' : '' ?>>Ödendi</option>
            <option value="iade"     <?= $siparis['odeme_durum'] === 'iade'     ? 'selected' : '' ?>>İade</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Kargo Firması</label>
          <select name="kargo_firma" class="form-control">
            <?php foreach ($kargoFirmalar as $kf): ?>
            <option value="<?= e($kf) ?>" <?= ($siparis['kargo_firma'] ?? '') === $kf ? 'selected' : '' ?>><?= $kf ?: '– Seçiniz –' ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Kargo Takip No</label>
          <input type="text" name="kargo_no" class="form-control"
                 value="<?= e($siparis['kargo_no'] ?? '') ?>"
                 placeholder="Boş bırakırsanız email gönderilmez">
          <small style="color:var(--muted);font-size:.75rem;display:block;margin-top:.3rem">
            ℹ Kargo no girilirse müşteriye otomatik email gider.
          </small>
        </div>

        <button type="submit" class="btn btn-primary btn-full">💾 Güncelle</button>
      </form>
    </div>

    <!-- Özet bilgi -->
    <div class="card">
      <div class="card-header"><span class="card-title">👤 Müşteri</span></div>
      <div style="font-weight:600;margin-bottom:.3rem"><?= e($siparis['ad_soyad'] ?? 'Misafir') ?></div>
      <?php if ($siparis['email']): ?><div style="color:var(--muted);font-size:.85rem"><?= e($siparis['email']) ?></div><?php endif; ?>
      <?php if ($siparis['telefon']): ?><div style="color:var(--muted);font-size:.85rem"><?= e($siparis['telefon']) ?></div><?php endif; ?>
      <?php if ($siparis['odeme_durum'] === 'odendi'): ?>
      <div class="badge badge-green" style="margin-top:.75rem;display:inline-block">✓ Ödeme Alındı</div>
      <?php else: ?>
      <div class="badge badge-orange" style="margin-top:.75rem;display:inline-block">⏳ Ödeme Bekliyor</div>
      <?php endif; ?>
    </div>

    <a href="/admin/siparisler.php" class="btn btn-outline btn-full">← Siparişlere Dön</a>
  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


$ok = getFlash('ok');
$durumlar = ['bekliyor'=>'Bekliyor','hazirlaniyor'=>'Hazırlanıyor','kargoda'=>'Kargoda','tamamlandi'=>'Tamamlandı','iptal'=>'İptal'];
$adres    = $siparis['adres_snapshot'] ? json_decode($siparis['adres_snapshot'],true) : [];
?>
<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem">
  <div>
    <div class="card">
      <div class="card-header"><span class="card-title">📦 Sipariş #<?= $id ?></span><span style="color:var(--muted);font-size:.82rem"><?= date('d.m.Y H:i',strtotime($siparis['created_at'])) ?></span></div>
      <table class="admin-table">
        <thead><tr><th>Ürün</th><th>Fiyat</th><th>Adet</th><th>Toplam</th></tr></thead>
        <tbody>
          <?php foreach ($kalemler as $k): ?>
          <tr>
            <td><?= e($k['baslik']) ?></td>
            <td>₺<?= number_format($k['fiyat'],2,',','.') ?></td>
            <td><?= $k['adet'] ?></td>
            <td style="font-family:'Orbitron',sans-serif;color:var(--orange)">₺<?= number_format($k['toplam'],2,',','.') ?></td>
          </tr>
          <?php endforeach; ?>
          <tr style="border-top:1px solid var(--border)">
            <td colspan="3" style="text-align:right;font-weight:600">Kargo:</td>
            <td>₺<?= number_format($siparis['kargo'],2,',','.') ?></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align:right;font-family:'Orbitron',sans-serif;font-weight:700">TOPLAM:</td>
            <td style="font-family:'Orbitron',sans-serif;color:var(--orange);font-size:1.1rem">₺<?= number_format($siparis['toplam'],2,',','.') ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php if ($adres): ?>
    <div class="card">
      <div class="card-header"><span class="card-title">📍 Teslimat Adresi</span></div>
      <p style="color:var(--muted);line-height:1.8">
        <?= e($adres['ad_soyad']??'') ?><br>
        <?= e($adres['adres']??'') ?><br>
        <?= e(($adres['ilce']??'').' / '.($adres['sehir']??'')) ?> <?= e($adres['posta_kodu']??'') ?><br>
        <?= e($adres['telefon']??'') ?>
      </p>
    </div>
    <?php endif; ?>
  </div>

  <div>
    <div class="card">
      <div class="card-header"><span class="card-title">👤 Müşteri</span></div>
      <div style="margin-bottom:.5rem;font-weight:500"><?= e($siparis['ad_soyad']??'Misafir') ?></div>
      <div style="color:var(--muted);font-size:.88rem"><?= e($siparis['email']??'') ?></div>
      <div style="color:var(--muted);font-size:.88rem"><?= e($siparis['telefon']??'') ?></div>
    </div>

    <div class="card">
      <div class="card-header"><span class="card-title">🔧 Güncelle</span></div>
      <form method="POST">
        <input type="hidden" name="csrf" value="<?= csrf() ?>">
        <div class="form-group">
          <label class="form-label">Sipariş Durumu</label>
          <select name="durum" class="form-control">
            <?php foreach ($durumlar as $v=>$l): ?><option value="<?= $v ?>" <?= $siparis['durum']===$v?'selected':'' ?>><?= $l ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Ödeme Durumu</label>
          <select name="odeme_durum" class="form-control">
            <option value="bekliyor" <?= $siparis['odeme_durum']==='bekliyor'?'selected':'' ?>>Bekliyor</option>
            <option value="odendi"   <?= $siparis['odeme_durum']==='odendi'?'selected':'' ?>>Ödendi</option>
            <option value="iade"     <?= $siparis['odeme_durum']==='iade'?'selected':'' ?>>İade</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Kargo Takip No</label>
          <input type="text" name="kargo_no" class="form-control" value="<?= e($siparis['kargo_no']??'') ?>">
        </div>
        <button type="submit" class="btn btn-primary btn-full">💾 Güncelle</button>
      </form>
    </div>
    <a href="/admin/siparisler.php" class="btn btn-outline btn-full">← Siparişlere Dön</a>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
