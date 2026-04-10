<?php $adminTitle = 'Siparişler'; require_once __DIR__ . '/includes/header.php'; ?>

<?php
// Hızlı durum güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck() && isset($_POST['durum_guncelle'])) {
    DB::q("UPDATE mn_siparisler SET durum=? WHERE id=?", [$_POST['durum'], (int)$_POST['siparis_id']]);
    flash('ok', 'Sipariş durumu güncellendi.');
    redirect('/admin/siparisler.php');
}

$page    = max(1, (int)($_GET['sayfa'] ?? 1));
$perPage = 20;
$offset  = ($page - 1) * $perPage;
$where   = ['1=1'];
$params  = [];

if (!empty($_GET['durum'])) { $where[] = 's.durum=?'; $params[] = $_GET['durum']; }
if (!empty($_GET['q']))     { $where[] = '(m.ad_soyad LIKE ? OR m.email LIKE ?)'; $params[] = '%'.$_GET['q'].'%'; $params[] = '%'.$_GET['q'].'%'; }

$whereStr  = implode(' AND ', $where);
$total     = (int)DB::row("SELECT COUNT(*) AS c FROM mn_siparisler s LEFT JOIN mn_musteriler m ON m.id=s.musteri_id WHERE $whereStr", $params)['c'];
$pages     = (int)ceil($total / $perPage);
$siparisler = DB::all(
    "SELECT s.*, m.ad_soyad, m.email FROM mn_siparisler s LEFT JOIN mn_musteriler m ON m.id=s.musteri_id WHERE $whereStr ORDER BY s.id DESC LIMIT $perPage OFFSET $offset",
    $params
);

$durumlar = ['bekliyor'=>'Bekliyor','hazirlaniyor'=>'Hazırlanıyor','kargoda'=>'Kargoda','tamamlandi'=>'Tamamlandı','iptal'=>'İptal'];
$durumBadge = ['bekliyor'=>'badge-orange','hazirlaniyor'=>'badge-blue','kargoda'=>'badge-purple','tamamlandi'=>'badge-green','iptal'=>'badge-red'];

$ok = getFlash('ok');
?>

<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>

<!-- FİLTRE -->
<div class="search-bar">
  <form method="GET" style="display:flex;gap:.6rem;flex:1;flex-wrap:wrap">
    <input type="text" name="q" value="<?= e($_GET['q']??'') ?>" placeholder="Müşteri adı veya e-posta..." class="form-control search-input">
    <select name="durum" class="form-control" style="width:auto">
      <option value="">Tüm Durumlar</option>
      <?php foreach ($durumlar as $val=>$lbl): ?>
      <option value="<?= $val ?>" <?= (($_GET['durum']??'') === $val) ? 'selected':'' ?>><?= $lbl ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-blue">Filtrele</button>
    <a href="/admin/siparisler.php" class="btn btn-outline">Sıfırla</a>
  </form>
</div>

<!-- ÖZET SAYAÇLAR -->
<div style="display:flex;gap:.75rem;margin-bottom:1.25rem;flex-wrap:wrap">
  <?php
  foreach ($durumlar as $val => $lbl) {
      $cnt = DB::row("SELECT COUNT(*) AS c FROM mn_siparisler WHERE durum=?", [$val])['c'];
      $badge = $durumBadge[$val];
      echo "<a href='/admin/siparisler.php?durum=$val' class='badge $badge' style='text-decoration:none;padding:.4rem .85rem;font-size:.82rem'>$lbl: $cnt</a>";
  }
  ?>
</div>

<div class="card" style="padding:0">
  <div class="table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>#</th><th>Müşteri</th><th>Tutar</th><th>Ödeme</th><th>Durum</th><th>Tarih</th><th>İşlem</th></tr>
      </thead>
      <tbody>
        <?php foreach ($siparisler as $s): ?>
        <tr>
          <td style="color:var(--blue);font-weight:600">#<?= $s['id'] ?></td>
          <td>
            <div style="font-weight:500"><?= e($s['ad_soyad'] ?? 'Misafir') ?></div>
            <div style="font-size:.78rem;color:var(--muted)"><?= e($s['email'] ?? '') ?></div>
          </td>
          <td style="font-family:'Orbitron',sans-serif;color:var(--orange)">₺<?= number_format($s['toplam'],2,',','.') ?></td>
          <td><span class="badge <?= $s['odeme_durum']==='odendi' ? 'badge-green' : 'badge-red' ?>"><?= $s['odeme_durum']==='odendi' ? 'Ödendi' : 'Ödenmedi' ?></span></td>
          <td>
            <form method="POST" style="display:flex;gap:.4rem;align-items:center">
              <input type="hidden" name="csrf" value="<?= csrf() ?>">
              <input type="hidden" name="siparis_id" value="<?= $s['id'] ?>">
              <input type="hidden" name="durum_guncelle" value="1">
              <select name="durum" class="form-control" style="width:auto;font-size:.8rem;padding:.3rem .5rem" onchange="this.form.submit()">
                <?php foreach ($durumlar as $val=>$lbl): ?>
                <option value="<?= $val ?>" <?= $s['durum']===$val?'selected':'' ?>><?= $lbl ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
          <td style="color:var(--muted);font-size:.8rem;white-space:nowrap"><?= date('d.m.Y H:i', strtotime($s['created_at'])) ?></td>
          <td><a href="/admin/siparis-detay.php?id=<?= $s['id'] ?>" class="btn btn-outline btn-sm">Detay →</a></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($siparisler)): ?>
        <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--muted)">Sipariş bulunamadı.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if ($pages > 1): ?>
<div class="pagination" style="margin-top:1rem">
  <?php for ($i=1;$i<=$pages;$i++): ?>
  <a href="?<?= http_build_query(array_merge($_GET,['sayfa'=>$i])) ?>" class="page-btn <?= $i===$page?'active':'' ?>"><?= $i ?></a>
  <?php endfor; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
