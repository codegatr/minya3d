
<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-top">

      <div class="footer-brand">
        <img src="/assets/img/logo.svg" alt="<?= e(ayar('site_adi', SITE_NAME)) ?>" class="footer-logo">
        <p>Türkiye'nin öncü 3D baskı hizmet ve ürün platformu. Geleceği bugünden üretiyoruz.</p>
        <div class="footer-contact">
          <span>📍 Konya, Türkiye</span>
          <a href="mailto:<?= e(ayar('email', SITE_EMAIL)) ?>"><?= e(ayar('email', SITE_EMAIL)) ?></a>
          <a href="tel:<?= e(ayar('telefon', '')) ?>"><?= e(ayar('telefon', '')) ?></a>
        </div>
      </div>

      <div class="footer-col">
        <h4>Ürünler</h4>
        <ul>
          <?php
          $fKats = DB::all("SELECT baslik, slug FROM mn_kategoriler WHERE aktif=1 LIMIT 6");
          foreach ($fKats as $fk): ?>
          <li><a href="/kategori/<?= e($fk['slug']) ?>"><?= e($fk['baslik']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Hizmetler</h4>
        <ul>
          <li><a href="/ozel-siparis.php">Özel Sipariş</a></li>
          <li><a href="/dosya-yukle.php">Dosya Yükle & Fiyat</a></li>
          <li><a href="/son-islem.php">Son İşlem</a></li>
          <li><a href="/toplu-siparis.php">Toplu Sipariş</a></li>
          <li><a href="/danismanlik.php">Danışmanlık</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Kurumsal</h4>
        <ul>
          <li><a href="/hakkimizda.php">Hakkımızda</a></li>
          <li><a href="/blog.php">Blog</a></li>
          <li><a href="/referanslar.php">Referanslar</a></li>
          <li><a href="/kvkk.php">KVKK & Gizlilik</a></li>
          <li><a href="/iletisim.php">İletişim</a></li>
        </ul>
      </div>

    </div>

    <div class="footer-bottom">
      <p>© <?= date('Y') ?> <?= e(ayar('site_adi', SITE_NAME)) ?> – Tüm hakları saklıdır.</p>
      <div class="footer-socials">
        <?php if ($ig = ayar('instagram','')): ?>
        <a href="<?= e($ig) ?>" target="_blank" rel="noopener" aria-label="Instagram">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
        </a>
        <?php endif; ?>
        <?php if ($yt = ayar('youtube','')): ?>
        <a href="<?= e($yt) ?>" target="_blank" rel="noopener" aria-label="YouTube">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.54C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
        </a>
        <?php endif; ?>
        <?php if ($li = ayar('linkedin','')): ?>
        <a href="<?= e($li) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
        </a>
        <?php endif; ?>
        <?php if ($wa = ayar('whatsapp','')): ?>
        <a href="https://wa.me/<?= e(preg_replace('/\D/','',$wa)) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        </a>
        <?php endif; ?>
      </div>
    </div>

  </div>
</footer>

<script src="/assets/js/main.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>
