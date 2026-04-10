<?php $adminTitle = 'Site Ayarları'; require_once __DIR__ . '/includes/header.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $fields = [
        'site_adi','site_slogan','email','telefon','adres',
        'whatsapp','instagram','youtube','linkedin',
        'para_birimi','kargo_ucreti','min_ucretsiz_kargo',
        'github_token','meta_desc','footer_metin',
    ];
    foreach ($fields as $f) {
        $val = trim($_POST[$f] ?? '');
        DB::q("INSERT INTO mn_ayarlar(anahtar,deger) VALUES(?,?) ON DUPLICATE KEY UPDATE deger=?", [$f,$val,$val]);
    }
    // Logo yükleme
    if (!empty($_FILES['logo']['name'])) {
        $yeni = uploadFile($_FILES['logo'], UPLOAD_DIR, ['svg','png','webp','jpg']);
        if ($yeni) {
            DB::q("INSERT INTO mn_ayarlar(anahtar,deger) VALUES('logo',?) ON DUPLICATE KEY UPDATE deger=?", [$yeni,$yeni]);
            copy(UPLOAD_DIR . $yeni, __DIR__ . '/../assets/img/logo.svg');
        }
    }
    // Site URL ve adını config.php'ye yansıt (SITE_NAME, SITE_EMAIL)
    $cfgPath = dirname(__DIR__) . '/config.php';
    if (file_exists($cfgPath)) {
        $cfg = file_get_contents($cfgPath);
        $siteAdi   = DB::row("SELECT deger FROM mn_ayarlar WHERE anahtar='site_adi'")['deger'] ?? 'Minya 3D';
        $siteEmail = DB::row("SELECT deger FROM mn_ayarlar WHERE anahtar='email'")['deger'] ?? SITE_EMAIL;
        $cfg = preg_replace("/define\('SITE_NAME',\s*'[^']+'\)/", "define('SITE_NAME', '$siteAdi')", $cfg);
        $cfg = preg_replace("/define\('SITE_EMAIL',\s*'[^']+'\)/", "define('SITE_EMAIL', '$siteEmail')", $cfg);
        file_put_contents($cfgPath, $cfg);
    }
    flash('ok', 'Ayarlar kaydedildi.');
    redirect('/admin/ayarlar.php');
}

$ok  = getFlash('ok');
$err = getFlash('err');

function ayarVal(string $k): string {
    return e(ayar($k, ''));
}
?>

<?php if ($ok): ?><div class="alert alert-success">✓ <?= e($ok) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error">✗ <?= e($err) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="csrf" value="<?= csrf() ?>">

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">

    <div>
      <div class="card">
        <div class="card-header"><span class="card-title">🌐 Site Bilgileri</span></div>
        <div class="form-group"><label class="form-label">Site Adı</label><input type="text" name="site_adi" class="form-control" value="<?= ayarVal('site_adi') ?>"></div>
        <div class="form-group"><label class="form-label">Slogan</label><input type="text" name="site_slogan" class="form-control" value="<?= ayarVal('site_slogan') ?>"></div>
        <div class="form-group"><label class="form-label">Meta Açıklama</label><textarea name="meta_desc" class="form-control" style="min-height:80px"><?= ayarVal('meta_desc') ?></textarea></div>
        <div class="form-group"><label class="form-label">Footer Metin</label><input type="text" name="footer_metin" class="form-control" value="<?= ayarVal('footer_metin') ?>"></div>
      </div>

      <div class="card">
        <div class="card-header"><span class="card-title">📦 Kargo & Fiyat</span></div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">Para Birimi</label><input type="text" name="para_birimi" class="form-control" value="<?= ayarVal('para_birimi') ?: '₺' ?>"></div>
          <div class="form-group"><label class="form-label">Kargo Ücreti (₺)</label><input type="number" name="kargo_ucreti" class="form-control" step="0.01" value="<?= ayarVal('kargo_ucreti') ?: '0' ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">Ücretsiz Kargo Üzeri (₺, 0=her zaman ücretsiz)</label><input type="number" name="min_ucretsiz_kargo" class="form-control" step="0.01" value="<?= ayarVal('min_ucretsiz_kargo') ?: '0' ?>"></div>
      </div>

      <div class="card">
        <div class="card-header"><span class="card-title">🔗 GitHub Güncelleme Tokeni</span></div>
        <div class="form-group">
          <label class="form-label">GitHub Personal Access Token</label>
          <input type="password" name="github_token" class="form-control" value="<?= ayarVal('github_token') ?>" placeholder="ghp_...">
          <small style="color:var(--muted);font-size:.78rem">Güncelleme sistemi için gereklidir. codegatr/minya3d reposuna read yetkisi yeterlidir.</small>
        </div>
      </div>
    </div>

    <div>
      <div class="card">
        <div class="card-header"><span class="card-title">📞 İletişim</span></div>
        <div class="form-group"><label class="form-label">E-posta</label><input type="email" name="email" class="form-control" value="<?= ayarVal('email') ?>"></div>
        <div class="form-group"><label class="form-label">Telefon</label><input type="text" name="telefon" class="form-control" value="<?= ayarVal('telefon') ?>"></div>
        <div class="form-group"><label class="form-label">Adres</label><textarea name="adres" class="form-control" style="min-height:70px"><?= ayarVal('adres') ?></textarea></div>
        <div class="form-group"><label class="form-label">WhatsApp (uluslararası format: 905xxxxxxxxx)</label><input type="text" name="whatsapp" class="form-control" value="<?= ayarVal('whatsapp') ?>"></div>
      </div>

      <div class="card">
        <div class="card-header"><span class="card-title">📱 Sosyal Medya</span></div>
        <div class="form-group"><label class="form-label">Instagram URL</label><input type="url" name="instagram" class="form-control" value="<?= ayarVal('instagram') ?>"></div>
        <div class="form-group"><label class="form-label">YouTube URL</label><input type="url" name="youtube" class="form-control" value="<?= ayarVal('youtube') ?>"></div>
        <div class="form-group"><label class="form-label">LinkedIn URL</label><input type="url" name="linkedin" class="form-control" value="<?= ayarVal('linkedin') ?>"></div>
      </div>

      <div class="card">
        <div class="card-header"><span class="card-title">🖼️ Site Logosu</span></div>
        <img src="/assets/img/logo.svg" style="height:40px;margin-bottom:1rem">
        <label class="img-upload-area">
          <input type="file" name="logo" accept=".svg,.png,.webp,.jpg" style="display:none">
          <div>🖼️ Logo Seç (SVG, PNG, WEBP)</div>
        </label>
      </div>

      <button type="submit" class="btn btn-primary btn-full">💾 Ayarları Kaydet</button>
    </div>

  </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
