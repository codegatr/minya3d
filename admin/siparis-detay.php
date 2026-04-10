<?php $adminTitle = 'Sipariş Detay'; require_once __DIR__ . '/includes/header.php'; ?>
<?php
$id      = (int)($_GET['id'] ?? 0);
$siparis = DB::row("SELECT s.*, m.ad_soyad, m.email, m.telefon FROM mn_siparisler s LEFT JOIN mn_musteriler m ON m.id=s.musteri_id WHERE s.id=?", [$id]);
if (!$siparis) { echo '<div class="alert alert-error">Sipariş bulunamadı.</div>'; require_once __DIR__.'/includes/footer.php'; exit; }
$kalemler = DB::all("SELECT * FROM mn_siparis_kalemleri WHERE siparis_id=?", [$id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    if (isset($_POST['durum']))       DB::q("UPDATE mn_siparisler SET durum=? WHERE id=?", [$_POST['durum'], $id]);
    if (isset($_POST['kargo_no']))    DB::q("UPDATE mn_siparisler SET kargo_no=? WHERE id=?", [trim($_POST['kargo_no']), $id]);
    if (isset($_POST['odeme_durum'])) DB::q("UPDATE mn_siparisler SET odeme_durum=? WHERE id=?", [$_POST['odeme_durum'], $id]);
    flash('ok','Güncellendi.');
    redirect('/admin/siparis-detay.php?id='.$id);
}

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
