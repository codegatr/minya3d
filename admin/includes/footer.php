
</div><!-- /admin-content -->
</div><!-- /admin-main -->
</div><!-- /admin-layout -->
<script>
// Sidebar mobile toggle
const st = document.getElementById('sidebarToggle');
if(st) st.style.display = 'flex';
// Aktif nav item
const p = window.location.pathname;
document.querySelectorAll('.nav-item a').forEach(a=>{
  if(a.getAttribute('href')===p) a.classList.add('active');
});
// Alert kapat
document.querySelectorAll('[data-dismiss]').forEach(b=>{
  b.addEventListener('click',()=>b.closest('.alert')?.remove());
});
// Görsel önizleme
document.querySelectorAll('input[type=file][data-preview]').forEach(inp=>{
  inp.addEventListener('change',function(){
    const prev = document.getElementById(this.dataset.preview);
    if(prev && this.files[0]){
      const r = new FileReader();
      r.onload = e => prev.src = e.target.result;
      r.readAsDataURL(this.files[0]);
    }
  });
});
</script>
</body>
</html>
