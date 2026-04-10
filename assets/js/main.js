/* Minya 3D – Main JS v1.0.10 */
(function(){
  'use strict';

  // ── Navbar scroll efekti ────────────────────────────────────────────────────
  const navbar = document.getElementById('navbar');
  if (navbar) {
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 30);
    });
  }

  // ── Burger menü ─────────────────────────────────────────────────────────────
  const burger  = document.getElementById('navBurger');
  const navMenu = document.getElementById('navLinks');
  if (burger && navMenu) {
    burger.addEventListener('click', () => {
      navMenu.classList.toggle('open');
      burger.classList.toggle('open');
    });
    // Dışarı tıklanınca kapat
    document.addEventListener('click', (e) => {
      if (!burger.contains(e.target) && !navMenu.contains(e.target)) {
        navMenu.classList.remove('open');
        burger.classList.remove('open');
      }
    });
  }

  // ── Mobile filtre overlay — dışına tıklanınca kapat ────────────────────────
  const mobileFilter = document.getElementById('mobileFilter');
  if (mobileFilter) {
    mobileFilter.addEventListener('click', (e) => {
      if (e.target === mobileFilter) mobileFilter.classList.remove('open');
    });
  }

  // ── Aktif nav linki ─────────────────────────────────────────────────────────
  const curPath = window.location.pathname;
  document.querySelectorAll('.nav-links a').forEach(a => {
    if (a.getAttribute('href') === curPath) a.classList.add('active');
  });

  // ── Sepete ekle butonları ───────────────────────────────────────────────────
  document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', async function(e) {
      e.preventDefault();
      const id  = this.dataset.id;
      const res = await fetch('/api/sepet.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    `action=ekle&urun_id=${id}`,
      });
      const data = await res.json();
      if (data.ok) {
        this.classList.add('added');
        this.innerHTML = '✓';
        const badge = document.querySelector('.cart-badge');
        if (badge) {
          badge.textContent = data.sepet_adet;
        } else {
          const cart = document.querySelector('.nav-cart');
          if (cart) {
            const b = document.createElement('span');
            b.className   = 'cart-badge';
            b.textContent = data.sepet_adet;
            cart.appendChild(b);
          }
        }
        setTimeout(() => {
          this.classList.remove('added');
          this.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>';
        }, 2000);
      }
    });
  });

  // ── Sepet miktar butonları ──────────────────────────────────────────────────
  document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
      const id  = this.dataset.id;
      const act = this.dataset.action;
      if (!id || !act) return;
      const res  = await fetch('/api/sepet.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    `action=${act}&urun_id=${id}`,
      });
      const data = await res.json();
      if (data.ok) location.reload();
    });
  });

  // ── Scroll reveal animasyonu ────────────────────────────────────────────────
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('revealed');
        observer.unobserve(e.target);
      }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  // ── Ürün görsel büyütme ─────────────────────────────────────────────────────
  document.querySelectorAll('.thumb-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const main = document.getElementById('mainImg');
      if (main) main.src = this.dataset.src;
      document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // ── Alert kapatma ───────────────────────────────────────────────────────────
  document.querySelectorAll('[data-dismiss="alert"]').forEach(btn => {
    btn.addEventListener('click', function() {
      this.closest('.alert')?.remove();
    });
  });

  // ── FAQ accordion (details/summary) ────────────────────────────────────────
  document.querySelectorAll('details').forEach(d => {
    d.addEventListener('toggle', function() {
      const span = this.querySelector('summary span:last-child');
      if (span) span.textContent = this.open ? '−' : '+';
    });
  });

  // ── Görsel lazy load hata fallback ─────────────────────────────────────────
  document.querySelectorAll('img[src]').forEach(img => {
    img.addEventListener('error', function() {
      if (!this.dataset.errored) {
        this.dataset.errored = '1';
        this.src = '/assets/img/no-image.webp';
      }
    });
  });

})();


  // Navbar scroll efekti
  const navbar = document.getElementById('navbar');
  if(navbar){
    window.addEventListener('scroll',()=>{
      navbar.classList.toggle('scrolled', window.scrollY > 30);
    });
  }

  // Burger menü
  const burger  = document.getElementById('navBurger');
  const navMenu = document.getElementById('navLinks');
  if(burger && navMenu){
    burger.addEventListener('click',()=>{
      navMenu.classList.toggle('open');
      burger.classList.toggle('open');
    });
  }

  // Aktif nav linki
  const curPath = window.location.pathname;
  document.querySelectorAll('.nav-links a').forEach(a=>{
    if(a.getAttribute('href') === curPath) a.classList.add('active');
  });

  // Sepete ekle butonları
  document.querySelectorAll('.add-to-cart').forEach(btn=>{
    btn.addEventListener('click', async function(e){
      e.preventDefault();
      const id  = this.dataset.id;
      const res = await fetch('/api/sepet.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body: `action=ekle&urun_id=${id}`
      });
      const data = await res.json();
      if(data.ok){
        this.classList.add('added');
        this.innerHTML = '✓';
        const badge = document.querySelector('.cart-badge');
        if(badge) badge.textContent = data.sepet_adet;
        else {
          const cart = document.querySelector('.nav-cart');
          if(cart){
            const b = document.createElement('span');
            b.className = 'cart-badge';
            b.textContent = data.sepet_adet;
            cart.appendChild(b);
          }
        }
        setTimeout(()=>{
          this.classList.remove('added');
          this.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>';
        }, 2000);
      }
    });
  });

  // Sepet miktar butonları
  document.querySelectorAll('.qty-btn').forEach(btn=>{
    btn.addEventListener('click', async function(){
      const id  = this.dataset.id;
      const act = this.dataset.action;
      const res = await fetch('/api/sepet.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body: `action=${act}&urun_id=${id}`
      });
      const data = await res.json();
      if(data.ok) location.reload();
    });
  });

  // Scroll reveal animasyonu
  const observer = new IntersectionObserver((entries)=>{
    entries.forEach(e=>{
      if(e.isIntersecting){
        e.target.classList.add('revealed');
        observer.unobserve(e.target);
      }
    });
  },{threshold:0.12});
  document.querySelectorAll('.reveal').forEach(el=>observer.observe(el));

  // Alert kapama
  document.querySelectorAll('[data-dismiss="alert"]').forEach(btn=>{
    btn.addEventListener('click',function(){
      this.closest('.alert')?.remove();
    });
  });

  // Görsel büyütme (ürün detay)
  document.querySelectorAll('.thumb-btn').forEach(btn=>{
    btn.addEventListener('click',function(){
      const main = document.getElementById('mainImg');
      if(main) main.src = this.dataset.src;
      document.querySelectorAll('.thumb-btn').forEach(b=>b.classList.remove('active'));
      this.classList.add('active');
    });
  });

})();
