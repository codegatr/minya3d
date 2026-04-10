<?php
$pageTitle = 'KVKK & Gizlilik Politikası';
require_once __DIR__ . '/includes/header.php';
?>
<div style="margin-top:70px"></div>
<section class="section">
  <div class="container" style="max-width:860px">
    <div class="breadcrumb"><a href="/">Ana Sayfa</a><span>/</span><span class="current">KVKK & Gizlilik</span></div>
    <h1 class="section-title" style="margin-bottom:2rem">KVKK <span>Aydınlatma Metni</span></h1>

    <div class="card" style="line-height:1.9;color:var(--muted);font-size:.95rem">

      <h3 style="color:var(--text);margin-bottom:.75rem">1. Veri Sorumlusu</h3>
      <p style="margin-bottom:1.5rem">
        Bu aydınlatma metni, 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında
        <strong style="color:var(--text)"><?= e(ayar('site_adi','Minya 3D')) ?></strong> tarafından hazırlanmıştır.
        Şirketimiz, veri sorumlusu sıfatıyla kişisel verilerinizi işlemektedir.
      </p>

      <h3 style="color:var(--text);margin-bottom:.75rem">2. Toplanan Kişisel Veriler</h3>
      <p style="margin-bottom:1.5rem">
        Sitemizi kullandığınızda aşağıdaki veriler işlenebilir: ad ve soyadınız, e-posta adresiniz,
        telefon numaranız, teslimat adresiniz, sipariş geçmişiniz ve ödeme bilgileriniz
        (ödeme bilgileri yalnızca ödeme aracı kuruluşları tarafından işlenir, tarafımızca saklanmaz).
      </p>

      <h3 style="color:var(--text);margin-bottom:.75rem">3. Kişisel Verilerin İşlenme Amacı</h3>
      <p style="margin-bottom:1.5rem">
        Kişisel verileriniz; sipariş ve teslimat işlemlerinin yürütülmesi, müşteri hizmetlerinin
        sağlanması, yasal yükümlülüklerin yerine getirilmesi ve rızanız dahilinde ticari iletişim
        amacıyla işlenmektedir.
      </p>

      <h3 style="color:var(--text);margin-bottom:.75rem">4. Kişisel Verilerin Aktarılması</h3>
      <p style="margin-bottom:1.5rem">
        Verileriniz; kargo ve lojistik firmaları, ödeme altyapısı sağlayıcıları ve yasal
        zorunluluk halinde ilgili kamu kurum ve kuruluşlarıyla paylaşılabilir. Yurt dışına
        veri aktarımı yapılmamaktadır.
      </p>

      <h3 style="color:var(--text);margin-bottom:.75rem">5. Veri Sahibinin Hakları</h3>
      <p style="margin-bottom:1.5rem">
        KVKK'nın 11. maddesi kapsamında kişisel verilerinize ilişkin; erişim, düzeltme, silme,
        işlemeye itiraz etme ve aktarımın kısıtlanmasını talep etme haklarınız bulunmaktadır.
        Bu hakları kullanmak için <a href="/iletisim.php" style="color:var(--blue)">iletişim formumuzu</a> kullanabilirsiniz.
      </p>

      <h3 style="color:var(--text);margin-bottom:.75rem">6. Çerezler</h3>
      <p style="margin-bottom:1.5rem">
        Sitemiz; oturum yönetimi (zorunlu), kullanım analizi (isteğe bağlı) amacıyla çerez
        kullanmaktadır. Tarayıcı ayarlarından çerezleri devre dışı bırakabilirsiniz.
      </p>

      <h3 style="color:var(--text);margin-bottom:.75rem">7. İletişim</h3>
      <p>
        KVKK kapsamındaki talepleriniz için:
        <a href="mailto:<?= e(ayar('email',SITE_EMAIL)) ?>" style="color:var(--blue)"><?= e(ayar('email',SITE_EMAIL)) ?></a>
      </p>

      <p style="margin-top:2rem;font-size:.82rem;color:var(--faint)">Son güncelleme: <?= date('d.m.Y') ?></p>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
