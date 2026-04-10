<?php $adminTitle = 'Dashboard'; require_once __DIR__ . '/includes/header.php'; ?>

<?php
$stats = [
  'urun'     => DB::row("SELECT COUNT(*) AS c FROM mn_urunler WHERE aktif=1")['c'] ?? 0,
  'siparis'  => DB::row("SELECT COUNT(*) AS c FROM mn_siparisler")['c'] ?? 0,
  'musteri'  => DB::row("SELECT COUNT(*) AS c FROM mn_musteriler")['c'] ?? 0,
  'bekleyen' => DB::row("SELECT COUNT(*) AS c FROM mn_siparisler WHERE durum='bekliyor'")['c'] ?? 0,
  'gelir'    => DB::row("SELECT COALESCE(SUM(toplam),0) AS c FROM mn_siparisler WHERE durum IN ('tamamlandi','kargoda')")['c'] ?? 0,
];
$sonSiparisler = DB::all("
    SELECT s.*, m.ad_soyad FROM mn_siparisler s
    LEFT JOIN mn_musteriler m ON m.id = s.musteri_id
    ORDER BY s.id DESC LIMIT 8
");
$sonUrunler = DB::all("SELECT id,baslik,gorsel,fiyat,stok,aktif FROM mn_urunler ORDER BY id DESC LIMIT 6");
?>

<!-- STAT CARDS -->
<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-card-icon">📦</div>
    <div class="stat-card-label">Aktif Ürün</div>
    <div class="stat-card-val blue"><?= number_format($stats['urun']) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon">🛒</div>
    <div class="stat-card-label">Bekleyen Sipariş</div>
    <div class="stat-card-val orange"><?= $stats['bekleyen'] ?></div>
    <div class="stat-card-sub">Toplam: <?= number_format($stats['siparis']) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon">👥</div>
    <div class="stat-card-label">Müşteri</div>
    <div class="stat-card-val purple"><?= number_format($stats['musteri']) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon">💰</div>
    <div class="stat-card-label">Toplam Gelir</div>
    <div class="stat-card-val green">₺<?= number_format($stats['gelir'], 0, ',', '.') ?></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem">

  <!-- SON SİPARİŞLER -->
  <div class="card">
    <div class="card-header">
      <span class="card-title">Son Siparişler</span>
      <a href="/admin/siparisler.php" class="btn btn-outline btn-sm">Tümü →</a>
    </div>
    <div class="table-wrap">
      <table class="admin-table">
        <thead>
          <tr><th>#</th><th>Müşteri</th><th>Tutar</th><th>Durum</th><th>Tarih</th></tr>
        </thead>
        <tbody>
          <?php foreach ($sonSiparisler as $s): ?>
          <tr>
            <td><a href="/admin/siparis-detay.php?id=<?= $s['id'] ?>" style="color:var(--blue)">#<?= $s['id'] ?></a></td>
            <td><?= e($s['ad_soyad'] ?? 'Misafir') ?></td>
            <td style="font-family:'Orbitron',sans-serif;color:var(--orange)">₺<?= number_format($s['toplam'],2,',','.') ?></td>
            <td><?php
              $durum = ['bekliyor'=>['badge-orange','Bekliyor'],'hazirlaniyor'=>['badge-blue','Hazırlanıyor'],'kargoda'=>['badge-purple','Kargoda'],'tamamlandi'=>['badge-green','Tamamlandı'],'iptal'=>['badge-red','İptal']];
              [$cls,$lbl] = $durum[$s['durum']] ?? ['badge-gray',$s['durum']];
            ?><span class="badge <?= $cls ?>"><?= $lbl ?></span></td>
            <td style="color:var(--muted);font-size:.82rem"><?= date('d.m.Y H:i', strtotime($s['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- HIZLI İŞLEMLER + SON ÜRÜNLER -->
  <div>
    <div class="card">
      <div class="card-header"><span class="card-title">Hızlı İşlemler</span></div>
      <div style="display:flex;flex-direction:column;gap:.6rem">
        <a href="/admin/urun-ekle.php" class="btn btn-primary">➕ Yeni Ürün Ekle</a>
        <a href="/admin/siparisler.php?durum=bekliyor" class="btn btn-outline">🛒 Bekleyen Siparişler</a>
        <a href="/admin/guncelle.php" class="btn btn-outline">🔄 Güncelleme Kontrol</a>
        <a href="/admin/ayarlar.php" class="btn btn-outline">⚙️ Site Ayarları</a>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <span class="card-title">Son Eklenen Ürünler</span>
        <a href="/admin/urunler.php" class="btn btn-outline btn-sm">Tümü</a>
      </div>
      <?php foreach ($sonUrunler as $u): ?>
      <div style="display:flex;align-items:center;gap:.75rem;padding:.6rem 0;border-bottom:1px solid var(--border)">
        <?php if ($u['gorsel']): ?>
        <img src="<?= UPLOAD_URL ?>urunler/<?= e($u['gorsel']) ?>" style="width:40px;height:40px;object-fit:cover;border-radius:8px;border:1px solid var(--border)">
        <?php else: ?>
        <div style="width:40px;height:40px;border-radius:8px;background:var(--navy3);display:flex;align-items:center;justify-content:center;font-size:1.2rem">📦</div>
        <?php endif; ?>
        <div style="flex:1;min-width:0">
          <div style="font-size:.85rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e($u['baslik']) ?></div>
          <div style="font-size:.78rem;color:var(--muted)">₺<?= number_format($u['fiyat'],2,',','.') ?> · Stok: <?= $u['stok'] ?></div>
        </div>
        <span class="badge <?= $u['aktif'] ? 'badge-green' : 'badge-gray' ?>"><?= $u['aktif'] ? '✓' : '–' ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
