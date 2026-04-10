"""
Minya 3D - Kategori bazlı SVG ürün görseli üretici
Her kategori için özgün, 3D baskı estetiğine uygun SVG thumbnail.
"""
import os

# Renk paleti (lacivert-neon teması)
BG     = "#0A1628"
BG2    = "#0D1F35"
BLUE   = "#0EA5E9"
ORANGE = "#F97316"
PURPLE = "#8B5CF6"
GREEN  = "#22C55E"
MUTED  = "#475569"
LIGHT  = "#94A3B8"
WHITE  = "#E2E8F0"

def svg_wrap(content, label, color=BLUE, w=400, h=400):
    return f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w} {h}" width="{w}" height="{h}">
  <defs>
    <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
      <path d="M 20 0 L 0 0 0 20" fill="none" stroke="{color}" stroke-width="0.3" opacity="0.2"/>
    </pattern>
  </defs>
  <!-- Arkaplan -->
  <rect width="{w}" height="{h}" fill="{BG}"/>
  <rect width="{w}" height="{h}" fill="url(#grid)"/>
  <!-- İçerik -->
  {content}
  <!-- Etiket -->
  <rect x="0" y="{h-44}" width="{w}" height="44" fill="{BG2}" opacity="0.95"/>
  <line x1="0" y1="{h-44}" x2="{w}" y2="{h-44}" stroke="{color}" stroke-width="1" opacity="0.5"/>
  <text x="{w//2}" y="{h-18}" text-anchor="middle" font-family="monospace" font-size="12" fill="{color}" opacity="0.9">{label}</text>
</svg>'''

# ─── Kategori SVG'leri ────────────────────────────────────────────────────────

svgs = {}

# 1. Ev & Yaşam — sabunluk
svgs["ev-yasam"] = svg_wrap(f'''
  <rect x="100" y="120" width="200" height="140" rx="12" fill="{BG2}" stroke="{BLUE}" stroke-width="1.5"/>
  <rect x="115" y="135" width="170" height="8" rx="3" fill="{BLUE}" opacity="0.4"/>
  <rect x="115" y="149" width="170" height="8" rx="3" fill="{BLUE}" opacity="0.3"/>
  <rect x="115" y="163" width="170" height="8" rx="3" fill="{BLUE}" opacity="0.2"/>
  <ellipse cx="200" cy="170" rx="55" ry="25" fill="{BLUE}" opacity="0.12"/>
  <text x="200" y="230" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">Sabun Kaseti</text>
  <!-- Drenaj delikleri -->
  {''.join(f'<circle cx="{140+i*20}" cy="{200}" r="4" fill="{BG}" stroke="{BLUE}" stroke-width="1" opacity="0.6"/>' for i in range(7))}
  {''.join(f'<circle cx="{140+i*20}" cy="{215}" r="4" fill="{BG}" stroke="{BLUE}" stroke-width="1" opacity="0.6"/>' for i in range(7))}
''', "EV & YAŞAM", BLUE)

# 2. Ofis & Masa — kalem kutusu
svgs["ofis-masa"] = svg_wrap(f'''
  <!-- Kalem kutusu gövde -->
  <rect x="130" y="140" width="140" height="160" rx="8" fill="{BG2}" stroke="{PURPLE}" stroke-width="1.5"/>
  <!-- Bölmeler -->
  <line x1="175" y1="155" x2="175" y2="290" stroke="{PURPLE}" stroke-width="1" opacity="0.4"/>
  <line x1="220" y1="155" x2="220" y2="290" stroke="{PURPLE}" stroke-width="1" opacity="0.4"/>
  <!-- Kalemler -->
  <rect x="138" y="100" width="10" height="80" rx="3" fill="{ORANGE}" opacity="0.8"/>
  <rect x="152" y="115" width="10" height="65" rx="3" fill="{BLUE}" opacity="0.8"/>
  <rect x="183" y="95" width="10" height="85" rx="3" fill="{GREEN}" opacity="0.8"/>
  <rect x="197" y="110" width="10" height="70" rx="3" fill="{ORANGE}" opacity="0.6"/>
  <rect x="228" y="105" width="10" height="75" rx="3" fill="{PURPLE}" opacity="0.8"/>
  <rect x="242" y="120" width="10" height="60" rx="3" fill="{BLUE}" opacity="0.6"/>
  <text x="200" y="330" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">Kalem Düzenleyici</text>
''', "OFİS & MASA", PURPLE)

# 3. Dekorasyon & Sanat — geometrik vazo
svgs["dekorasyon-sanat"] = svg_wrap(f'''
  <!-- Facet vazo -->
  <polygon points="200,80 240,140 260,220 240,290 200,310 160,290 140,220 160,140" 
           fill="{BG2}" stroke="{ORANGE}" stroke-width="1.5"/>
  <!-- İç facet -->
  <polygon points="200,110 228,155 242,215 228,268 200,280 172,268 158,215 172,155" 
           fill="none" stroke="{ORANGE}" stroke-width="0.5" opacity="0.4"/>
  <!-- Çiçek -->
  <circle cx="200" cy="190" r="30" fill="{ORANGE}" opacity="0.15"/>
  <circle cx="200" cy="165" r="8" fill="{ORANGE}" opacity="0.7"/>
  <circle cx="220" cy="178" r="7" fill="{ORANGE}" opacity="0.5"/>
  <circle cx="215" cy="200" r="7" fill="{ORANGE}" opacity="0.5"/>
  <circle cx="200" cy="208" r="6" fill="{ORANGE}" opacity="0.4"/>
  <circle cx="185" cy="200" r="7" fill="{ORANGE}" opacity="0.5"/>
  <circle cx="180" cy="178" r="7" fill="{ORANGE}" opacity="0.5"/>
  <text x="200" y="355" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">Geometrik Vazo</text>
''', "DEKORASYON", ORANGE)

# 4. Oyun & Hobi — zar kulesi
svgs["oyun-hobi"] = svg_wrap(f'''
  <!-- Kule gövde -->
  <rect x="155" y="100" width="90" height="200" rx="6" fill="{BG2}" stroke="{GREEN}" stroke-width="1.5"/>
  <!-- Katlar -->
  <rect x="162" y="115" width="76" height="3" rx="1" fill="{GREEN}" opacity="0.5"/>
  <rect x="162" y="155" width="76" height="3" rx="1" fill="{GREEN}" opacity="0.5"/>
  <rect x="162" y="195" width="76" height="3" rx="1" fill="{GREEN}" opacity="0.5"/>
  <!-- Açıklık -->
  <rect x="170" y="245" width="60" height="30" rx="4" fill="{BG}" stroke="{GREEN}" stroke-width="1"/>
  <!-- Zar -->
  <rect x="178" y="130" width="44" height="44" rx="4" fill="{BG}" stroke="{GREEN}" stroke-width="1"/>
  <circle cx="192" cy="144" r="4" fill="{GREEN}" opacity="0.8"/>
  <circle cx="208" cy="144" r="4" fill="{GREEN}" opacity="0.8"/>
  <circle cx="192" cy="158" r="4" fill="{GREEN}" opacity="0.8"/>
  <circle cx="208" cy="158" r="4" fill="{GREEN}" opacity="0.8"/>
  <!-- Alt tepsi -->
  <rect x="140" y="295" width="120" height="15" rx="3" fill="{BG2}" stroke="{GREEN}" stroke-width="1"/>
  <text x="200" y="345" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">RPG Zar Kulesi</text>
''', "OYUN & HOBİ", GREEN)

# 5. Eğitim & Bilim — DNA sarmal
svgs["egitim-bilim"] = svg_wrap(f'''
  <!-- DNA sarmal -->
  {"".join([f'''
  <line x1="{170 + int(40*__import__("math").sin(i*0.4))}" y1="{80+i*14}" 
        x2="{230 - int(40*__import__("math").sin(i*0.4))}" y2="{80+i*14}" 
        stroke="{'#0EA5E9' if i%3==0 else '#8B5CF6' if i%3==1 else '#22C55E'}" stroke-width="3" stroke-linecap="round" opacity="0.7"/>''' for i in range(18)])}
  <!-- Sarmal çizgiler -->
  <path d="M 170 80 Q 150 130 170 180 Q 190 230 170 280 Q 150 310 170 340" 
        fill="none" stroke="{BLUE}" stroke-width="2" opacity="0.5"/>
  <path d="M 230 80 Q 250 130 230 180 Q 210 230 230 280 Q 250 310 230 340" 
        fill="none" stroke="{PURPLE}" stroke-width="2" opacity="0.5"/>
  <text x="200" y="358" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">DNA Sarmal</text>
''', "EĞİTİM & BİLİM", BLUE)

# 6. Teknoloji & Elektronik — Raspberry Pi kutusu
svgs["teknoloji-elektronik"] = svg_wrap(f'''
  <!-- Kutu -->
  <rect x="110" y="120" width="180" height="160" rx="10" fill="{BG2}" stroke="{BLUE}" stroke-width="1.5"/>
  <!-- PCB -->
  <rect x="125" y="135" width="150" height="120" rx="4" fill="#0f3460" stroke="{BLUE}" stroke-width="0.5" opacity="0.8"/>
  <!-- Port delikler -->
  <rect x="105" y="150" width="10" height="20" rx="2" fill="{BG}" stroke="{BLUE}" stroke-width="1"/>
  <rect x="105" y="178" width="10" height="14" rx="2" fill="{BG}" stroke="{BLUE}" stroke-width="1"/>
  <rect x="285" y="148" width="10" height="16" rx="2" fill="{BG}" stroke="{BLUE}" stroke-width="1"/>
  <rect x="285" y="170" width="10" height="16" rx="2" fill="{BG}" stroke="{BLUE}" stroke-width="1"/>
  <!-- Chipset -->
  <rect x="160" y="165" width="50" height="50" rx="3" fill="{BLUE}" opacity="0.3"/>
  <rect x="165" y="170" width="40" height="40" rx="2" fill="{BLUE}" opacity="0.2"/>
  <text x="185" y="196" text-anchor="middle" font-family="monospace" font-size="9" fill="{BLUE}" opacity="0.8">RPi</text>
  <!-- LED -->
  <circle cx="255" cy="150" r="5" fill="{GREEN}" opacity="0.9"/>
  <circle cx="245" cy="150" r="5" fill="{ORANGE}" opacity="0.7"/>
  <text x="200" y="320" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">Pi Kutusu</text>
''', "TEKNOLOJİ", BLUE)

# 7. El Aleti & Atölye — tornavida ray
svgs["el-aleti-atolye"] = svg_wrap(f'''
  <!-- Arka plaka -->
  <rect x="100" y="100" width="200" height="220" rx="8" fill="{BG2}" stroke="{ORANGE}" stroke-width="1.5"/>
  <!-- Ray delikleri -->
  <circle cx="120" cy="120" r="6" fill="{BG}" stroke="{ORANGE}" stroke-width="1"/>
  <circle cx="280" cy="120" r="6" fill="{BG}" stroke="{ORANGE}" stroke-width="1"/>
  <!-- Tornavidalar -->
  {"".join([f'''
  <rect x="{130+i*20}" y="145" width="8" height="{'100' if i%2==0 else '80'}" rx="2" fill="{ORANGE}" opacity="{0.7-i*0.05}"/>
  <polygon points="{130+i*20},{145} {138+i*20},{145} {134+i*20},{130}" fill="{MUTED}" opacity="0.8"/>
  <rect x="{131+i*20}" y="{'235' if i%2==0 else '220'}" width="6" height="8" rx="1" fill="{BG}" stroke="{ORANGE}" stroke-width="0.5"/>
  ''' for i in range(7)])}
  <text x="200" y="340" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">Tornavida Rayı</text>
''', "EL ALETİ", ORANGE)

# 8. Takı & Aksesuar — geometrik küpe
svgs["taki-aksesuar"] = svg_wrap(f'''
  <!-- Sol küpe -->
  <polygon points="155,130 185,130 170,185" fill="{PURPLE}" opacity="0.7" stroke="{WHITE}" stroke-width="1"/>
  <circle cx="170" cy="120" r="10" fill="{BG2}" stroke="{PURPLE}" stroke-width="1.5"/>
  <line x1="170" y1="105" x2="170" y2="95" stroke="{MUTED}" stroke-width="2"/>
  <!-- Sağ küpe -->
  <polygon points="215,130 245,130 230,185" fill="{PURPLE}" opacity="0.7" stroke="{WHITE}" stroke-width="1"/>
  <circle cx="230" cy="120" r="10" fill="{BG2}" stroke="{PURPLE}" stroke-width="1.5"/>
  <line x1="230" y1="105" x2="230" y2="95" stroke="{MUTED}" stroke-width="2"/>
  <!-- Bilezik -->
  <ellipse cx="200" cy="270" rx="65" ry="35" fill="none" stroke="{PURPLE}" stroke-width="4" opacity="0.6"/>
  <polygon points="185,255 200,240 215,255 215,285 200,300 185,285" fill="{BG2}" stroke="{PURPLE}" stroke-width="1.5"/>
  <text x="200" y="340" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">Takı & Küpe</text>
''', "TAKI & AKSESUAR", PURPLE)

# 9. Bahçe & Doğa — saksı seti
svgs["bahce-doga"] = svg_wrap(f'''
  <!-- Küçük saksı -->
  <polygon points="145,230 165,230 160,290 150,290" fill="{GREEN}" opacity="0.6" stroke="{GREEN}" stroke-width="1"/>
  <ellipse cx="155" cy="230" rx="12" ry="5" fill="{GREEN}" opacity="0.8"/>
  <!-- Orta saksı -->
  <polygon points="185,200 215,200 208,290 192,290" fill="{GREEN}" opacity="0.7" stroke="{GREEN}" stroke-width="1.5"/>
  <ellipse cx="200" cy="200" rx="18" ry="7" fill="{GREEN}" opacity="0.9"/>
  <!-- Büyük saksı -->
  <polygon points="225,165 265,165 256,290 234,290" fill="{GREEN}" opacity="0.8" stroke="{GREEN}" stroke-width="1.5"/>
  <ellipse cx="245" cy="165" rx="22" ry="9" fill="{GREEN}" opacity="0.9"/>
  <!-- Bitkiler -->
  <path d="M155 230 C150 210 140 195 145 180" fill="none" stroke="#4ade80" stroke-width="3"/>
  <circle cx="143" cy="178" r="8" fill="#4ade80" opacity="0.7"/>
  <path d="M200 200 C195 175 188 158 192 140" fill="none" stroke="#4ade80" stroke-width="3"/>
  <circle cx="190" cy="137" r="12" fill="#4ade80" opacity="0.7"/>
  <path d="M245 165 C240 135 232 115 237 95" fill="none" stroke="#4ade80" stroke-width="3"/>
  <circle cx="234" cy="91" r="16" fill="#4ade80" opacity="0.7"/>
  <text x="200" y="335" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">Saksı Seti</text>
''', "BAHÇE & DOĞA", GREEN)

# 10. Spor & Outdoor — bisiklet tutucu
svgs["spor-outdoor"] = svg_wrap(f'''
  <!-- Gidon -->
  <rect x="120" y="160" width="160" height="20" rx="8" fill="{BLUE}" opacity="0.7"/>
  <!-- Telefon tutucu -->
  <rect x="165" y="130" width="70" height="110" rx="8" fill="{BG2}" stroke="{BLUE}" stroke-width="2"/>
  <rect x="171" y="136" width="58" height="98" rx="5" fill="{BG}" stroke="{BLUE}" stroke-width="0.5" opacity="0.5"/>
  <!-- Telefon ekran -->
  <rect x="175" y="142" width="50" height="84" rx="3" fill="#1a1a2e"/>
  <line x1="200" y1="215" x2="200" y2="225" stroke="{BLUE}" stroke-width="2"/>
  <!-- Klipsler -->
  <rect x="155" y="148" width="10" height="30" rx="3" fill="{BLUE}" opacity="0.8"/>
  <rect x="235" y="148" width="10" height="30" rx="3" fill="{BLUE}" opacity="0.8"/>
  <text x="200" y="315" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">Gidon Tutucu</text>
''', "SPOR & OUTDOOR", BLUE)

# 11. Çocuk & Oyuncak — alfabe harfleri
svgs["cocuk-oyuncak"] = svg_wrap(f'''
  <!-- Harf bloklar -->
  <rect x="90" y="200" width="55" height="55" rx="8" fill="{ORANGE}" opacity="0.8"/>
  <text x="117" y="235" text-anchor="middle" font-family="sans-serif" font-weight="bold" font-size="28" fill="{WHITE}">A</text>
  <rect x="153" y="185" width="55" height="55" rx="8" fill="{PURPLE}" opacity="0.8"/>
  <text x="180" y="220" text-anchor="middle" font-family="sans-serif" font-weight="bold" font-size="28" fill="{WHITE}">B</text>
  <rect x="216" y="200" width="55" height="55" rx="8" fill="{GREEN}" opacity="0.8"/>
  <text x="243" y="235" text-anchor="middle" font-family="sans-serif" font-weight="bold" font-size="28" fill="{WHITE}">C</text>
  <!-- Rakamlar üst -->
  <rect x="110" y="130" width="44" height="44" rx="8" fill="{BLUE}" opacity="0.7"/>
  <text x="132" y="159" text-anchor="middle" font-family="sans-serif" font-weight="bold" font-size="22" fill="{WHITE}">1</text>
  <rect x="178" y="120" width="44" height="44" rx="8" fill="{ORANGE}" opacity="0.6"/>
  <text x="200" y="149" text-anchor="middle" font-family="sans-serif" font-weight="bold" font-size="22" fill="{WHITE}">2</text>
  <rect x="246" y="130" width="44" height="44" rx="8" fill="{PURPLE}" opacity="0.6"/>
  <text x="268" y="159" text-anchor="middle" font-family="sans-serif" font-weight="bold" font-size="22" fill="{WHITE}">3</text>
  <text x="200" y="330" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">Alfabe Seti</text>
''', "ÇOCUK & OYUNCAK", ORANGE)

# 12. Araç İçi — telefon tutucu
svgs["arac-ici"] = svg_wrap(f'''
  <!-- Havalandırma parmağı -->
  <rect x="100" y="100" width="200" height="120" rx="6" fill="{BG2}" stroke="{MUTED}" stroke-width="1"/>
  {"".join([f'<rect x="110" y="{108+i*18}" width="180" height="10" rx="3" fill="{BG}" stroke="{MUTED}" stroke-width="0.5" opacity="0.7"/>' for i in range(5)])}
  <!-- Klips tutucu -->
  <rect x="160" y="195" width="80" height="110" rx="8" fill="{BG2}" stroke="{BLUE}" stroke-width="2"/>
  <!-- Telefon -->
  <rect x="168" y="203" width="64" height="94" rx="5" fill="{BG}" stroke="{BLUE}" stroke-width="0.5"/>
  <rect x="172" y="208" width="56" height="82" rx="3" fill="#0f1f3d"/>
  <!-- Yandan klips -->
  <rect x="148" y="210" width="12" height="30" rx="4" fill="{BLUE}" opacity="0.8"/>
  <rect x="240" y="210" width="12" height="30" rx="4" fill="{BLUE}" opacity="0.8"/>
  <text x="200" y="340" text-anchor="middle" font-family="sans-serif" font-size="11" fill="{LIGHT}">Araç Tutucu</text>
''', "ARAÇ İÇİ", BLUE)

# Kaydet
for slug, content in svgs.items():
    path = f"assets/img/placeholder/{slug}.svg"
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"  ✓ {path}")

print(f"\nToplam {len(svgs)} SVG oluşturuldu.")
