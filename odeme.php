<?php
$pageTitle = 'Ödeme';
$pageDesc  = 'Guvenli odeme ile 3D baski siparisini tamamla. SSL sifreli, 3D Secure odeme.';
require_once __DIR__ . '/includes/header.php';

$sepet   = $_SESSION['sepet'] ?? [];
if (empty($sepet)) redirect('/sepet.php');

$urunler = [];
$toplam  = 0;

$ids = array_keys($sepet);
$in  = implode(',', array_fill(0, count($ids), '?'));
$rows = DB::all("SELECT id,baslik,slug,gorsel,fiyat,indirim_fiyat,stok FROM mn_urunler WHERE id IN ($in)", $ids);
foreach ($rows as $r) {
    $adet    = $sepet[$r['id']];
    $birim   = $r['indirim_fiyat'] > 0 ? $r['indirim_fiyat'] : $r['fiyat'];
    $r['adet']   = $adet;
    $r['birim']  = $birim;
    $r['toplam'] = $birim * $adet;
    $toplam     += $r['toplam'];
    $urunler[]   = $r;
}

$kargo = (float)(ayar('kargo_ucreti', '0'));
$minKargo = (float)(ayar('min_ucretsiz_kargo', '0'));
if ($minKargo > 0 && $toplam >= $minKargo) $kargo = 0;
$genelToplam = $toplam + $kargo;

$hata = '';
$ok   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfCheck()) {
    $adSoyad  = trim($_POST['ad_soyad']  ?? '');
    $email    = trim($_POST['email']     ?? '');
    $telefon  = trim($_POST['telefon']   ?? '');
    $adres    = trim($_POST['adres']     ?? '');
    $sehir    = trim($_POST['sehir']     ?? '');
    $ilce     = trim($_POST['ilce']      ?? '');
    $posta    = trim($_POST['posta_kodu']?? '');

    if (!$adSoyad || !$email || !$adres || !$sehir) {
        $hata = 'Lütfen tüm zorunlu alanları doldurun.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $hata = 'Geçerli bir e-posta adresi girin.';
    } else {
        // Müşteri oluştur / güncelle
        $musteri = DB::row("SELECT id FROM mn_musteriler WHERE email=?", [$email]);
        if ($musteri) {
            $mId = $musteri['id'];
            DB::update('mn_musteriler', compact('adSoyad','telefon','adres','sehir','ilce','posta'), 'id=?', [$mId]);
        } else {
            $mId = DB::insert('mn_musteriler', [
                'ad_soyad' => $adSoyad, 'email' => $email, 'telefon' => $telefon,
                'adres' => $adres, 'sehir' => $sehir, 'ilce' => $ilce, 'posta_kodu' => $posta,
            ]);
        }

        $adresSnap = json_encode(compact('adSoyad','telefon','adres','sehir','ilce','posta'));

        // Sipariş oluştur
        $sId = DB::insert('mn_siparisler', [
            'musteri_id'       => $mId,
            'toplam'           => $genelToplam,
            'kargo'            => $kargo,
            'durum'            => 'bekliyor',
            'odeme_durum'      => 'bekliyor',
            'odeme_yontemi'    => 'kart',
            'adres_snapshot'   => $adresSnap,
            'created_at'       => date('Y-m-d H:i:s'),
        ]);

        // Kalemler
        foreach ($urunler as $u) {
            DB::insert('mn_siparis_kalemleri', [
                'siparis_id' => $sId,
                'urun_id'    => $u['id'],
                'baslik'     => $u['baslik'],
                'fiyat'      => $u['birim'],
                'adet'       => $u['adet'],
                'toplam'     => $u['toplam'],
            ]);
            // Stok düş
            DB::q("UPDATE mn_urunler SET stok = GREATEST(stok - ?, 0) WHERE id=?", [$u['adet'], $u['id']]);
        }

        // ── Müşteriye onay emaili ──────────────────────────────────────
        $urunListesi = implode("\n", array_map(
            fn($u) => "  - {$u['baslik']} x{$u['adet']} = " . para($u['toplam']),
            $urunler
        ));
        $musteriMail = "Merhaba $adSoyad,\n\n"
            . "#{$sId} numaralı siparişiniz başarıyla alınmıştır.\n\n"
            . "SİPARİŞ DETAYI\n" . str_repeat('-', 30) . "\n"
            . $urunListesi . "\n"
            . str_repeat('-', 30) . "\n"
            . "Kargo : " . ($kargo > 0 ? para($kargo) : 'Ücretsiz') . "\n"
            . "TOPLAM: " . para($genelToplam) . "\n\n"
            . "Teslimat Adresi:\n$adres, $ilce / $sehir\n\n"
            . "Siparişiniz en kısa sürede hazırlanacaktır.\n"
            . "Sorularınız için: " . ayar('email', SITE_EMAIL) . "\n\n"
            . "İyi günler,\n" . ayar('site_adi', SITE_NAME);
        @mail($email,
            "Siparişiniz Alındı #$sId – " . ayar('site_adi', SITE_NAME),
            $musteriMail,
            "From: " . ayar('email', SITE_EMAIL) . "\r\nContent-Type: text/plain; charset=UTF-8\r\n"
        );

        // ── Admine bildirim ───────────────────────────────────────────
        $adminEmail = ayar('email', SITE_EMAIL);
        $adminMail  = "Yeni Sipariş #$sId\n\n"
            . "Müşteri: $adSoyad ($email)\n"
            . "Toplam : " . para($genelToplam) . "\n"
            . "Adres  : $adres, $ilce / $sehir\n\n"
            . $urunListesi . "\n\n"
            . "Admin: " . SITE_URL . "/admin/siparis-detay.php?id=$sId";
        @mail($adminEmail,
            "Yeni Sipariş #$sId – " . para($genelToplam),
            $adminMail,
            "From: $adminEmail\r\nContent-Type: text/plain; charset=UTF-8\r\n"
        );

        // Sepeti temizle
        unset($_SESSION['sepet']);

        redirect('/tesekkurler.php?siparis=' . $sId);
    }
}
?>

<div style="margin-top:70px"></div>
<div class="container" style="padding:2rem">
  <div class="breadcrumb">
    <a href="/">Ana Sayfa</a><span>/</span>
    <a href="/sepet.php">Sepet</a><span>/</span>
    <span class="current">Ödeme</span>
  </div>

  <?php if ($hata): ?><div class="alert alert-error">✗ <?= e($hata) ?></div><?php endif; ?>

  <form method="POST">
    <input type="hidden" name="csrf" value="<?= csrf() ?>">
    <div style="display:grid;grid-template-columns:1fr 360px;gap:2rem;align-items:start">

      <div>
        <div class="card">
          <div class="card-header" style="background:none;border:none;padding:0 0 1rem">
            <h2 style="font-family:'Orbitron',sans-serif;font-size:1.1rem">📍 Teslimat Bilgileri</h2>
          </div>
          <div class="form-row">
            <div class="form-group"><label class="form-label">Ad Soyad *</label><input type="text" name="ad_soyad" class="form-control" required value="<?= e($_POST['ad_soyad']??'') ?>"></div>
            <div class="form-group"><label class="form-label">Telefon</label><input type="tel" name="telefon" class="form-control" value="<?= e($_POST['telefon']??'') ?>"></div>
          </div>
          <div class="form-group"><label class="form-label">E-posta *</label><input type="email" name="email" class="form-control" required value="<?= e($_POST['email']??'') ?>"></div>
          <div class="form-group"><label class="form-label">Adres *</label><textarea name="adres" class="form-control" style="min-height:80px" required><?= e($_POST['adres']??'') ?></textarea></div>
          <div class="form-row">
            <div class="form-group"><label class="form-label">Şehir *</label><input type="text" name="sehir" class="form-control" required value="<?= e($_POST['sehir']??'') ?>"></div>
            <div class="form-group"><label class="form-label">İlçe</label><input type="text" name="ilce" class="form-control" value="<?= e($_POST['ilce']??'') ?>"></div>
          </div>
          <div class="form-group"><label class="form-label">Posta Kodu</label><input type="text" name="posta_kodu" class="form-control" style="max-width:160px" value="<?= e($_POST['posta_kodu']??'') ?>"></div>
        </div>

        <div class="card">
          <div class="card-header" style="background:none;border:none;padding:0 0 1rem">
            <h2 style="font-family:'Orbitron',sans-serif;font-size:1.1rem">💳 Ödeme</h2>
          </div>
          <div class="alert alert-info" style="margin-bottom:1.25rem">
            ℹ️ Sanal POS entegrasyonu için admin paneli → Ayarlar bölümünden ödeme sağlayıcısı ayarlayın.
            Şu an siparişler "bekleme" durumunda oluşturulur.
          </div>
          <!-- İleride NestPay / Garanti / Yapı Kredi entegrasyonu buraya -->
          <div style="border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;display:flex;align-items:center;gap:.75rem">
            <span style="font-size:1.5rem">💳</span>
            <span style="color:var(--muted);font-size:.9rem">Kredi / Banka Kartı (3D Secure)</span>
            <span class="badge badge-blue" style="margin-left:auto">Yakında</span>
          </div>
        </div>
      </div>

      <!-- Sipariş Özeti -->
      <div style="position:sticky;top:90px">
        <div class="card">
          <h3 style="font-family:'Orbitron',sans-serif;font-size:1rem;margin-bottom:1.25rem">🛒 Sipariş Özeti</h3>
          <?php foreach ($urunler as $u): ?>
          <div style="display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;border-bottom:1px solid rgba(14,165,233,.08);font-size:.88rem">
            <div>
              <div style="font-weight:500"><?= e($u['baslik']) ?></div>
              <div style="color:var(--muted)"><?= $u['adet'] ?> adet × <?= para($u['birim']) ?></div>
            </div>
            <div style="font-family:'Orbitron',sans-serif;font-size:.9rem;color:var(--orange)"><?= para($u['toplam']) ?></div>
          </div>
          <?php endforeach; ?>
          <div style="margin-top:1rem">
            <div style="display:flex;justify-content:space-between;color:var(--muted);margin-bottom:.4rem;font-size:.9rem">
              <span>Ara Toplam</span><span><?= para($toplam) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;color:var(--muted);margin-bottom:.4rem;font-size:.9rem">
              <span>Kargo</span>
              <span style="color:var(--green)"><?= $kargo > 0 ? para($kargo) : 'Ücretsiz' ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;padding-top:.75rem;border-top:1px solid var(--border);font-family:'Orbitron',sans-serif;font-size:1.05rem">
              <span>Toplam</span><span style="color:var(--orange)"><?= para($genelToplam) ?></span>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-full" style="margin-top:1.5rem;font-size:1rem">
            🔒 Siparişi Tamamla
          </button>
          <p style="text-align:center;font-size:.78rem;color:var(--faint);margin-top:.75rem">Siparişiniz güvenli bağlantı üzerinden iletilir.</p>
        </div>
      </div>

    </div>
  </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
