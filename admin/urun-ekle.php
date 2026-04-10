<?php
$id      = (int)($_GET['id'] ?? 0);
$editing = $id > 0;
$urun    = $editing ? DB::row("SELECT * FROM mn_urunler WHERE id=?", [$id]) : null;
if ($editing && !$urun) redirect('/admin/urunler.php');

$adminTitle = $editing ? 'Ürün Düzenle' : 'Yeni Ürün';
require_once __DIR__ . '/includes/header.php';

$kategoriler = DB::all("SELECT id, baslik FROM mn_kategoriler WHERE aktif=1 ORDER BY baslik");
$materyaller = DB::all("SELECT id, baslik FROM mn_materyaller WHERE aktif=1 ORDER BY baslik");
$err = ''; $ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $baslik       = trim($_POST['baslik'] ?? '');
    $sl           = slug($_POST['slug'] ?? $baslik);
    $aciklama     = $_POST['aciklama'] ?? '';
    $fiyat        = (float)str_replace(',', '.', $_POST['fiyat'] ?? 0);
    $indirim      = (float)str_replace(',', '.', $_POST['indirim_fiyat'] ?? 0);
    $stok         = (int)($_POST['stok'] ?? 0);
    $kat_id       = (int)($_POST['kategori_id'] ?? 0);
    $materyal     = trim($_POST['materyal'] ?? '');
    $boyut        = trim($_POST['boyut'] ?? '');
    $vitrin       = isset($_POST['vitrin']) ? 1 : 0;
    $aktif        = isset($_POST['aktif']) ? 1 : 0;
    $sira         = (int)($_POST['sira'] ?? 0);
    $whatsapp_msg = trim($_POST['whatsapp_msg'] ?? '');

    if (!$baslik) { $err = 'Ürün adı zorunludur.'; }
    else {
        // Görsel yükleme
        $gorsel = $urun['gorsel'] ?? '';
        if (!empty($_FILES['gorsel']['name'])) {
            $yeni = uploadFile($_FILES['gorsel'], UPLOAD_DIR . 'urunler/');
            if ($yeni) {
                if ($gorsel) @unlink(UPLOAD_DIR . 'urunler/' . $gorsel);
                $gorsel = $yeni;
            } else {
                $err = 'Görsel yüklenemedi. (JPG/PNG/WEBP, maks. ' . MAX_UPLOAD_MB . 'MB)';
            }
        }

        if (!$err) {
            $data = compact('baslik','aciklama','fiyat','indirim_fiyat','stok','kat_id','materyal','boyut','vitrin','aktif','sira','gorsel','whatsapp_msg');
            $data['indirim_fiyat'] = $indirim;
            $data['indirim_fiyat'] = $indirim;
            $data['slug']          = $sl;
            $data['kategori_id']   = $kat_id ?: null;

            if ($editing) {
                DB::update('mn_urunler', $data, 'id=?', [$id]);
                $ok = 'Ürün güncellendi.';
                $urun = DB::row("SELECT * FROM mn_urunler WHERE id=?", [$id]);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $newId = DB::insert('mn_urunler', $data);
                flash('ok', 'Ürün eklendi.');
                redirect('/admin/urun-ekle.php?id=' . $newId);
            }
        }
    }
}
?>

<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error">✗ <?= e($err) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="csrf" value="<?= csrf() ?>">

  <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">

    <!-- SOL: Ana bilgiler -->
    <div>
      <div class="card">
        <div class="card-header"><span class="card-title">Temel Bilgiler</span></div>
        <div class="form-group">
          <label class="form-label">Ürün Adı *</label>
          <input type="text" name="baslik" class="form-control" value="<?= e($urun['baslik']??'') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">URL Slug</label>
          <input type="text" name="slug" class="form-control" value="<?= e($urun['slug']??'') ?>" placeholder="otomatik-olusturulur">
        </div>
        <div class="form-group">
          <label class="form-label">Açıklama</label>
          <textarea name="aciklama" class="form-control" style="min-height:180px"><?= e($urun['aciklama']??'') ?></textarea>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Fiyat (₺) *</label>
            <input type="number" name="fiyat" class="form-control" step="0.01" min="0" value="<?= $urun['fiyat']??'' ?>">
          </div>
          <div class="form-group">
            <label class="form-label">İndirim Fiyatı (₺)</label>
            <input type="number" name="indirim_fiyat" class="form-control" step="0.01" min="0" value="<?= $urun['indirim_fiyat']??'' ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" min="0" value="<?= $urun['stok']??0 ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Sıra</label>
            <input type="number" name="sira" class="form-control" min="0" value="<?= $urun['sira']??0 ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Materyal</label>
            <input type="text" name="materyal" class="form-control" value="<?= e($urun['materyal']??'') ?>" placeholder="PLA+, ABS, Reçine...">
          </div>
          <div class="form-group">
            <label class="form-label">Boyut / Baskı Boyutu</label>
            <input type="text" name="boyut" class="form-control" value="<?= e($urun['boyut']??'') ?>" placeholder="100x100x50mm">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">WhatsApp Mesajı (opsiyonel)</label>
          <input type="text" name="whatsapp_msg" class="form-control" value="<?= e($urun['whatsapp_msg']??'') ?>" placeholder="Bu ürün hakkında bilgi almak istiyorum.">
        </div>
      </div>
    </div>

    <!-- SAĞ: Görsel & Ayarlar -->
    <div>
      <div class="card">
        <div class="card-header"><span class="card-title">Ana Görsel</span></div>
        <?php if ($urun['gorsel'] ?? false): ?>
        <img src="<?= UPLOAD_URL ?>urunler/<?= e($urun['gorsel']) ?>" id="gorselPreview" class="img-preview" style="width:100%;height:160px;margin-bottom:1rem">
        <?php else: ?>
        <img id="gorselPreview" src="" class="img-preview" style="width:100%;height:160px;margin-bottom:1rem;display:none">
        <?php endif; ?>
        <label class="img-upload-area">
          <input type="file" name="gorsel" accept="image/*" data-preview="gorselPreview" style="display:none">
          <div>📷 Görsel Seç</div>
          <div style="font-size:.8rem;color:var(--muted);margin-top:.3rem">JPG, PNG, WEBP – maks. <?= MAX_UPLOAD_MB ?>MB</div>
        </label>
      </div>

      <div class="card">
        <div class="card-header"><span class="card-title">Kategori & Durum</span></div>
        <div class="form-group">
          <label class="form-label">Kategori</label>
          <select name="kategori_id" class="form-control">
            <option value="">– Seçiniz –</option>
            <?php foreach ($kategoriler as $k): ?>
            <option value="<?= $k['id'] ?>" <?= (($urun['kategori_id']??'') == $k['id']) ? 'selected' : '' ?>><?= e($k['baslik']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-check">
            <input type="checkbox" name="aktif" value="1" <?= ($urun['aktif']??1) ? 'checked' : '' ?>>
            Aktif (Sitede görünsün)
          </label>
        </div>
        <div class="form-group">
          <label class="form-check">
            <input type="checkbox" name="vitrin" value="1" <?= ($urun['vitrin']??0) ? 'checked' : '' ?>>
            Vitrin Ürünü (Ana sayfada göster)
          </label>
        </div>
      </div>

      <div style="display:flex;gap:.75rem">
        <button type="submit" class="btn btn-primary btn-full">
          <?= $editing ? '💾 Güncelle' : '➕ Ekle' ?>
        </button>
        <a href="/admin/urunler.php" class="btn btn-outline">İptal</a>
      </div>
    </div>

  </div>
</form>

<script>
// Slug otomatik oluştur
const baslik = document.querySelector('[name=baslik]');
const slugIn = document.querySelector('[name=slug]');
if(baslik && slugIn) {
  baslik.addEventListener('input', function(){
    if(!slugIn.dataset.manual){
      const tr = {'ş':'s','ı':'i','ğ':'g','ü':'u','ö':'o','ç':'c','Ş':'s','İ':'i','Ğ':'g','Ü':'u','Ö':'o','Ç':'c'};
      slugIn.value = this.value.split('').map(c=>tr[c]||c).join('').toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/[\s-]+/g,'-').trim();
    }
  });
  slugIn.addEventListener('input',()=>slugIn.dataset.manual='1');
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
