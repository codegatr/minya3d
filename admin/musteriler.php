<?php $adminTitle = 'Müşteriler'; require_once __DIR__ . '/includes/header.php'; ?>
<?php
$page    = max(1,(int)($_GET['sayfa']??1));
$perPage = 20; $offset = ($page-1)*$perPage;
$q       = trim($_GET['q']??'');
$where   = $q ? ['ad_soyad LIKE ? OR email LIKE ?'] : ['1=1'];
$params  = $q ? ["%$q%","%$q%"] : [];
$whereStr= implode(' AND ',$where);
$total   = (int)DB::row("SELECT COUNT(*) AS c FROM mn_musteriler WHERE $whereStr",$params)['c'];
$pages   = (int)ceil($total/$perPage);
$musteriler = DB::all("SELECT m.*, (SELECT COUNT(*) FROM mn_siparisler WHERE musteri_id=m.id) AS siparis_sayisi FROM mn_musteriler m WHERE $whereStr ORDER BY m.id DESC LIMIT $perPage OFFSET $offset",$params);
?>
<div class="search-bar">
  <form method="GET" style="display:flex;gap:.6rem;flex:1">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Ad veya e-posta..." class="form-control search-input">
    <button type="submit" class="btn btn-blue">Ara</button>
    <a href="/admin/musteriler.php" class="btn btn-outline">Sıfırla</a>
  </form>
</div>
<div class="card" style="padding:0">
  <div class="table-wrap">
    <table class="admin-table">
      <thead><tr><th>#</th><th>Ad Soyad</th><th>E-posta</th><th>Telefon</th><th>Sipariş</th><th>Kayıt</th></tr></thead>
      <tbody>
        <?php foreach ($musteriler as $m): ?>
        <tr>
          <td style="color:var(--faint)"><?= $m['id'] ?></td>
          <td style="font-weight:500"><?= e($m['ad_soyad']) ?></td>
          <td style="color:var(--muted)"><?= e($m['email']) ?></td>
          <td style="color:var(--muted)"><?= e($m['telefon']??'') ?></td>
          <td><span class="badge badge-blue"><?= $m['siparis_sayisi'] ?></span></td>
          <td style="color:var(--faint);font-size:.8rem"><?= date('d.m.Y',strtotime($m['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($musteriler)): ?><tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--muted)">Müşteri bulunamadı.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php if ($pages>1): ?>
<div class="pagination" style="margin-top:1rem">
  <?php for($i=1;$i<=$pages;$i++): ?>
  <a href="?<?= http_build_query(array_merge($_GET,['sayfa'=>$i])) ?>" class="page-btn <?= $i===$page?'active':'' ?>"><?= $i ?></a>
  <?php endfor; ?>
</div>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
