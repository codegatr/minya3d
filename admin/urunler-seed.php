<?php
/**
 * Minya 3D – MakerWorld PLA Ürün Kataloğu Seed
 * Admin panelinden tek tıkla çalıştırılır, tekrar çalıştırılabilir (IGNORE).
 */
$adminTitle = 'Ürün Kataloğu Yükle';
require_once __DIR__ . '/includes/header.php';

// ─── Kategori tanımları ──────────────────────────────────────────────────
$kategoriler = [
    ['Ev & Yaşam',             'ev-yasam',           '🏠', 1],
    ['Ofis & Masa Düzeni',     'ofis-masa',          '🖥️', 2],
    ['Dekorasyon & Sanat',     'dekorasyon-sanat',   '🎨', 3],
    ['Oyun & Hobi',            'oyun-hobi',          '🎮', 4],
    ['Eğitim & Bilim',         'egitim-bilim',       '🔬', 5],
    ['Teknoloji & Elektronik', 'teknoloji-elektronik','⚡', 6],
    ['El Aleti & Atölye',      'el-aleti-atolye',    '🔧', 7],
    ['Takı & Aksesuar',        'taki-aksesuar',      '💎', 8],
    ['Bahçe & Doğa',           'bahce-doga',         '🌿', 9],
    ['Spor & Outdoor',         'spor-outdoor',       '🏃', 10],
    ['Çocuk & Oyuncak',        'cocuk-oyuncak',      '🧸', 11],
    ['Araç İçi',               'arac-ici',           '🚗', 12],
];

// ─── Ürün tanımları ──────────────────────────────────────────────────────
// [baslik, kat_slug, fiyat, aciklama, adet_gram_tahmini]
$urunler = [

    // ── Ev & Yaşam ──────────────────────────────────────
    ['Sabun Kaseti (Drenajlı)',       'ev-yasam', 180, 'Delikli tasarım sayesinde su birikintisi oluşmaz. Banyo ve mutfak lavabosuna uygun. PLA+ ile baskı, su temasına dayanıklı yüzey.', 40],
    ['Diş Fırçası ve Macun Tutucu',  'ev-yasam', 160, '4 fırça yuvası, 1 macun kanalı. Duvara vidasız yapıştırıcı bant ile monte edilebilir. Kompakt banyo tasarımı.', 35],
    ['Banyo Köşe Düzenleyici Raf',   'ev-yasam', 320, 'Duş köşesine yerleştirilebilir, şampuan-sabun-jilet düzenleyici. Duvara montaj delikleri mevcut. 15×15 cm taban.', 110],
    ['Tuvalet Kağıdı Yedek Tutucusu','ev-yasam', 220, '3 yedek rulo kapasiteli dikey tutucu. Yere veya duvara monte edilebilir. Minimalist tasarım.', 80],
    ['Havlu Kancası (6'lı Set)',      'ev-yasam', 280, 'Kendi kendine yapışan arka bant uyumlu. Her biri 3 kg taşıma kapasiteli. Kapı, banyo, mutfak için.', 60],
    ['Mutfak Baharat Kavanozu Rafı',  'ev-yasam', 350, 'Dolap kapısı içi montajlı, 6 kavanozu kaldırır. Standart boyut kavanozlarla uyumlu (40 mm çap).', 120],
    ['Kağıt Havlu Altlık Dispenseri', 'ev-yasam', 290, 'Tezgâh üstü dikey kağıt havlu standı. Standart rulo boyutlarıyla uyumlu. Ağırlıklı taban ile devrilmez.', 100],
    ['Buzdolabı Yumurta Dispenseri',  'ev-yasam', 240, '6 yumurta kapasiteli, eğimli raf sistemi. FIFO (ilk giren ilk çıkar) prensibini uygular.', 90],
    ['Plastik Torba Dispenseri',      'ev-yasam', 200, 'Dolap altına monte edilir, alışveriş torbalarını düzenli saklar. Ön çekim mekanizması ile kolay erişim.', 75],
    ['Peçete Tutucu',                 'ev-yasam', 170, 'Masa üstü dikey peçete tutucu. Standart peçete boyutlarıyla uyumlu. Geometrik modern tasarım.', 50],
    ['Kapı Durdurucu (Vidalı)',       'ev-yasam', 130, 'Zemine vidayla monte edilir. Kapının duvara çarpmasını engeller. Kompakt profil.', 30],
    ['Anahtar Askısı (Duvar)',        'ev-yasam', 190, '5 kanca, üst kısımda küçük raf. Mektup, kart, para bırakılabilir. Girişe ideal düzenleyici.', 65],
    ['Çöp Torbası Dispenseri',        'ev-yasam', 210, 'Dolap altına montaj, standart çöp torbası rulolarını saklar. Alt çekim ile kolay erişim.', 70],
    ['Mutfak Kanca Seti (8'li)',      'ev-yasam', 260, 'Izgara veya ray sistemine geçer, tencere-tava-kepçe asabilirsiniz. Rail uyumlu S kanca tasarımı.', 55],
    ['Şarj Kablosu Maskeleme Kancası','ev-yasam', 140, 'Masa kenarına kıskaçla tutunur, kabloyu düzenli tutar. 3 kablo kapasiteli, arka kısmı görünmez.', 25],

    // ── Ofis & Masa ──────────────────────────────────────
    ['Kalem & Ajan Düzenleyici',        'ofis-masa', 200, '5 bölmeli döner masa kalem tutucu. Kalem, makas, cetvel, post-it için ayrı kompartımanlar. Zarif minimalist form.', 70],
    ['Kablo Yönetimi Maskeleme Klibi',  'ofis-masa', 160, 'Masa kenarına 5 adet klips seti. 6 mm'ye kadar kablo geçirir. Görünür kablo sorununa hızlı çözüm.', 30],
    ['Telefon Standı (Ayarlanabilir)',   'ofis-masa', 240, '0-90° açı ayarı, tüm telefon boyutları uyumlu. İzleme ve video görüşme konumu. Kaymaz alt.', 85],
    ['Tablet & iPad Standı',             'ofis-masa', 290, '7-13" tablet uyumlu, 3 açı ayarlı. Katlanabilir, çantaya sığar. Şarj kablosu yuvası mevcut.', 95],
    ['Kartvizit Tutucu',                 'ofis-masa', 130, '50 kartvizit kapasiteli, eğimli sunum tutucusu. İş masası, resepsiyon ve toplantı odaları için.', 40],
    ['Kulaklık Askısı (Masa Kenarı)',    'ofis-masa', 180, 'Masa kenarına sıkıştırma aparatı, kulaklık kolayca asılır. Kablo sarıcı özellikli. Kaymaz kauçuk uç.', 50],
    ['Dikey Telefon & Tablet Standı',    'ofis-masa', 210, 'Hem telefon hem tableti aynı anda tutar. İkili bölmeli, kablo yönetimi deliği mevcut.', 75],
    ['Klavye Bilekliği (Wrist Rest)',     'ofis-masa', 320, 'TKL ve standart klavye uyumlu. Ergo tasarım, uzun süre yazma konforunu artırır. Keçe kaplamalı alt (dahil).', 110],
    ['Monitör Yükseltici Stand',         'ofis-masa', 450, '10 cm yükseltme, altına klavye-mouse sığar. A4 kağıt rafı entegre. Maksimum 15 kg taşıma.', 180],
    ['USB / Aksesuar Düzenleme Tepsisi', 'ofis-masa', 260, 'USB hub, bellek, kalem pil vb. için bölmeli tepsi. Masanın sağ veya sol köşesine uyar.', 90],
    ['Kitap Dayama Seti (2'li)',          'ofis-masa', 220, 'L-şekilli ağır kitap dayama. Zemine kaymaz lastik tabanlar. Modern geometrik desen.', 80],
    ['Post-it Not Dispenseri',            'ofis-masa', 150, 'Standart ve mini post-it uyumlu çift bölmeli tutucu. Masa köşesinde yer almaz, duvara da monte edilir.', 40],
    ['Masaüstü Organizatör (Modüler)',   'ofis-masa', 380, '4 modülden oluşur, birbirine kilitlenir. Kalem, makas, mobil şarj, kablo bölmeleri. Genişletilebilir.', 140],

    // ── Dekorasyon & Sanat ───────────────────────────────
    ['Geometrik Çiçek Vazosu (S)',       'dekorasyon-sanat', 250, '10 cm yükseklik, keskin facet geometri. İnce çiçekler ve kurutulmuş çiçek düzenlemeleri için. Masa süsü.', 80],
    ['Geometrik Çiçek Vazosu (L)',       'dekorasyon-sanat', 420, '22 cm yükseklik, büyük demet düzenlemeleri için. Facet yüzey, her renk PLA ile etkileyici görünüm.', 150],
    ['Hex Duvar Rafı Seti (3'lü)',       'dekorasyon-sanat', 480, 'Altıgen balayı petek tasarımı, modüler bağlantı. Küçük saksı, mum, obje sergilemek için. 15 cm çaplı.', 170],
    ['Mum Tutucu (Tealight)',             'dekorasyon-sanat', 190, '3'lü tealight mum tutucu, geometrik mandala desen. Sofra ve dekor amaçlı. Ahşap altlık uyumlu.', 65],
    ['Kaktüs Saksı Seti (3 Adet)',        'dekorasyon-sanat', 280, 'Farklı boy üç parçalı set. Drenaj deliği mevcut. Kaktüs ve sukulent bitkiler için idealdir.', 95],
    ['Asma Saksı (Makrome Askılı)',        'dekorasyon-sanat', 220, 'İp askıya uyumlu halkalı tasarım. 10 cm çap, standart drenajlı. Balkon ve pencere için.', 70],
    ['Duvar Sanat Paneli (Geometrik)',    'dekorasyon-sanat', 380, '30×30 cm modüler geometrik desen. Boyanabilir PLA. Birden fazla panel birleştirilerek büyütülür.', 130],
    ['Masa Süsü – İstanbul Silüeti',      'dekorasyon-sanat', 340, 'Köprü, cami ve boğaz silueti, 25 cm boy. Siyah PLA ile etkileyici gölge etkisi. Hediye kutusu dahil.', 110],
    ['Fotoğraf Çerçevesi (10×15 cm)',     'dekorasyon-sanat', 210, 'İnce Scandi çerçeve, masa ve duvar montajlı. Arkadan yay kancası ile kolay fotoğraf değiştirme.', 70],
    ['Hayvan Figürü – Kedi (Oturan)',     'dekorasyon-sanat', 290, 'Detaylı yüzey dokusu, 12 cm boy. Masa, raf veya dolap üstü için. Kendi renginde gönderilebilir.', 95],
    ['Hayvan Figürü – Geyik (Geometrik)','dekorasyon-sanat', 320, 'Low-poly geometrik geyik figürü. 15 cm boy. Duvar plakası olarak da kullanılabilir.', 110],
    ['LED Işık Difüzörü (Ay Şekli)',      'dekorasyon-sanat', 360, '18 cm çaplı, E27 veya GU10 soketi uyumlu. Sıcak ışıkla büyüleyici ay efekti. Taban dahil.', 120],
    ['Noel / Bayram Süsleri (10'lu Set)', 'dekorasyon-sanat', 220, 'Yıldız, çan, kar tanesi, ağaç – 5 farklı model x 2. Ağaç askısı deliği mevcut. 6-8 cm boy.', 60],
    ['Karakter Biblolar (Serisi)',         'dekorasyon-sanat', 270, 'Mini karakter figürleri, 8-10 cm boy. Maskomot, troll, gnome modelleri mevcuttur. Boyama seçeneği.', 90],
    ['Dekoratif Kase (Geometrik)',         'dekorasyon-sanat', 310, 'Facet iç-dış yapı, 16 cm çap. Meyve, kuruyemiş, dekoratif obje için. Farklı renk seçenekleri.', 105],

    // ── Oyun & Hobi ──────────────────────────────────────
    ['Zar Kulesi (RPG / D&D)',           'oyun-hobi', 260, 'Zar düşürme mekanizmalı kule, alt toplama tepsi dahil. D4-D20 zarlar uyumlu. Masa oyunu için şık.', 90],
    ['Kart Tutucu / El Düzenleyici',     'oyun-hobi', 200, '12 kart fan kapasiteli, masa yüzeyine yatay oturur. Sabahları çaydan kalkmayanlar için :) Tüm kart oyunları uyumlu.', 65],
    ['Satranç Takımı (32 Parça)',         'oyun-hobi', 680, 'Tam boy standart satranç takımı. 32 parça, siyah+beyaz PLA. Piyonlar 40mm, şah 75mm. Tahta dahil değil.', 240],
    ['Fidget Spinner (Yataklı)',          'oyun-hobi', 190, '608 rulman uyumlu, 90 sn üzeri dönüş. Üç kanatlı aerodinamik form. Rulman dahildir.', 55],
    ['Mini Bulmaca Kutusu (Sır Kutu)',    'oyun-hobi', 280, 'Açmak için belirli sırayla hareket ettirilen sır kutu. Hediye veya değerli eşya saklamak için.', 90],
    ['Minyatür Boya Paleti Tutucu',      'oyun-hobi', 230, '24 Vallejo / Citadel tipi boya şişe tutucu. Döner kaideli. Modeller ve figür boyamak için ideal.', 80],
    ['Fırça Yıkama Tutucu Seti',         'oyun-hobi', 180, 'Boya fırçaları için yıkama kabı tutma aparatı + fırça askı çubukları. Modelist hobiciler için.', 55],
    ['Diorama Taban Seti (3'lü)',         'oyun-hobi', 260, '50 / 75 / 100 mm yuvarlak taban. D&D, Warhammer ve diorama için. Kenar doku detayı mevcut.', 85],
    ['Tavla Pulu Seti (30 Adet)',         'oyun-hobi', 220, '15 siyah + 15 beyaz pul. Standart tavla boyutu 38 mm. Hafif ve pürüzsüz yüzey.', 75],
    ['Masaüstü Top Golf Sahası',          'oyun-hobi', 340, '3 delikli masaüstü mini golf sahası. Küçük top ve sopa dahil. Ofis eğlencesi için.', 115],
    ['Oyun Kartı Standı (Ayaklı)',        'oyun-hobi', 250, 'Aksiyon oyunları ve koleksiyon kartları için eğimli stand. 15 kart kapasiteli, görüntüleme açısı ayarlı.', 85],

    // ── Eğitim & Bilim ───────────────────────────────────
    ['DNA Çift Sarmal Modeli',           'egitim-bilim', 380, '25 cm yükseklik, renk kodlu baz çiftleri. Biyoloji dersi, laboratuvar ve dekor amaçlı. Nuklotid renklendirmeli.', 130],
    ['Atom Modeli (Ayarlanabilir)',       'egitim-bilim', 290, 'Merkez çekirdek + elektron yörüngesi ayarlı kol sistemi. Fizik ve kimya eğitimi için.', 95],
    ['Geometrik Şekiller Seti (12 Adet)','egitim-bilim', 350, 'Küp, küre, silindir, koni, prizma vb. 5-8 cm boyunda. İlkokuldan üniversiteye matematik eğitimi.', 120],
    ['Güneş Sistemi Model Seti',         'egitim-bilim', 580, '9 gezegen (Plüton dahil) + güneş, farklı büyüklüklerde orantılı. Raf veya asma olarak düzenlenebilir.', 200],
    ['İnsan Kafatası Modeli (1:1)',       'egitim-bilim', 420, 'Gerçek boyut, ayrılabilir üst bölüm. Tıp, eczacılık ve biyoloji eğitimi için.', 145],
    ['Kalp Anatomik Kesit Modeli',        'egitim-bilim', 480, 'İkiye ayrılabilir, atardamar-toplardamar renk kodlu. Hemşirelik ve tıp fakültesi için.', 160],
    ['Dinozor İskelet Kiti (T-Rex)',      'egitim-bilim', 520, '35 parçalı kurma kit. 25 cm boy. Eğitim ve hobi koleksiyonu. Kurma kılavuzu dahil.', 175],
    ['Levha Tektoniği Demo Modeli',       'egitim-bilim', 310, 'Kıta kaymalarını ve fay hatlarını gösteren eğitici katmanlı model. Coğrafya eğitimi.', 105],
    ['Optik Mercek / Prizma Tutucu',      'egitim-bilim', 220, 'Fizik deneyi optik kiti için mercek ve prizma tutucusu. Optik tezgâhla uyumlu.', 70],
    ['Mini Hava İstasyonu Gövdesi',       'egitim-bilim', 380, 'DHT22 veya BME280 sensör yuvalı, OLED ekran pencereli hava istasyonu. Elektronik dahil değil.', 125],

    // ── Teknoloji & Elektronik ───────────────────────────
    ['Raspberry Pi 4 Kutusu (Soğutuculu)','teknoloji-elektronik', 290, 'GPIO erişim açıklıkları, USB-C güç girişi deliği. Pasif soğutucu fin tasarımı. Tüm portlar erişilebilir.', 95],
    ['Arduino Uno Proje Kutusu',          'teknoloji-elektronik', 220, 'USB-B ve güç girişi erişimli. Shield montaj desteği. Tüm pin header ulaşılabilir.', 75],
    ['ESP32 / Wemos D1 Kutusu',          'teknoloji-elektronik', 180, 'Kompakt boyut, micro-USB ve USB-C versiyonları. Duvara montaj deliği. Anten alanı açık.', 55],
    ['Kulaklık Şarj Standı (AirPods)',    'teknoloji-elektronik', 200, 'AirPods 1/2/3 ve Pro için Lightning/USB-C kablo yönlendirmeli şarj standı. Minimize masa yerleşimi.', 60],
    ['Akıllı Saat Şarj Standı',           'teknoloji-elektronik', 230, 'Apple Watch, Galaxy Watch, Xiaomi Band uyumlu manyetik şarj yuvası montajlı stand. Kablo yönlendirme kanallı.', 75],
    ['Telefon Kablosuz Şarj Standı',      'teknoloji-elektronik', 260, 'Qi şarj coil yuvası entegre, 15° eğim. Telefonunuzu eğimli pozisyonda şarj edin. USB-C adaptör uyumlu.', 85],
    ['Game Controller Askısı',            'teknoloji-elektronik', 240, 'PS4/PS5, Xbox, Switch Joy-Con için evrensel ölçü. Masa kenarına klips veya duvar montajı.', 80],
    ['Kablo Düzenleme Masaüstü Kutusu',   'teknoloji-elektronik', 320, 'USB hub ve şarj kafaları için arka kablo deliği, kapak kapasite büyük. Masada düzensizliğe son.', 110],
    ['Telefon Hızlı Yükleme Standı',      'teknoloji-elektronik', 210, 'Yatay ve dikey kullanım, tüm telefon boyutları. Kablo yönetim deliği. Alüminyum ağırlık uyumlu.', 70],
    ['Masaüstü LED Şerit Diffüzörü',      'teknoloji-elektronik', 280, 'Masa altı LED şerit için plastik kanal difüzörü. 50 cm uzunlukta, masa kenarına yapışır.', 90],
    ['VR Gözlük Askısı',                  'teknoloji-elektronik', 310, 'Meta Quest, PSVR, Valve Index uyumlu. Duvar montajı. Kontrolcü askısı entegre.', 105],

    // ── El Aleti & Atölye ────────────────────────────────
    ['Tornavida Seti Rayı (10'lu)',       'el-aleti-atolye', 240, 'Mıknatıslı tutucu raylı sistem, 10 tornavida kapasiteli. Duvara vida ile monte edilir. Şalter uçları için.', 80],
    ['Pense / Kargaburun Askı Rayı',      'el-aleti-atolye', 260, '6 adet pense kapasiteli, sap tutma özellikli kancalı. Çalışma tezgâhı arkasına monte.', 90],
    ['Küçük Vida & Somun Düzenleyici',    'el-aleti-atolye', 300, '24 bölmeli çekmece sistemi. M2-M8 vida ve somun sınıflandırması. Etiket yuvaları mevcut.', 100],
    ['Bant Makarası Tutucu (3'lü)',       'el-aleti-atolye', 220, 'Masaüstü yatay rulo tutucu. Elektrik bantı, maskeleme bantı ve izolasyon bandı için. Kesici dahil değil.', 75],
    ['Matkap Ucu Organizeri (Döner)',      'el-aleti-atolye', 290, 'HSS 1-13 mm matkap uçları için 13 bölmeli döner stand. Uç boylarını hızlı seçin.', 95],
    ['Lehim Teli Tutucu & Dispenseri',    'el-aleti-atolye', 210, '1 kg makara uyumlu, kablolu alt kılavuz deliği. Lehim sırasında düzensizliğe son.', 70],
    ['Keski & Ahşap Tornavida Tutucu',   'el-aleti-atolye', 250, '8 sap yuvası, duvara monte. Ahşap işleme ve marangozluk aleti askısı.', 85],
    ['Akü Şarj Cihazı Rafı',             'el-aleti-atolye', 280, '18V Makita / DeWalt / Bosch batarya tipi uyumlu şarj istasyonu tabanı. 2 şarj cihazı kapasiteli.', 95],
    ['Lehim İstasyonu Yardımcı Kolu',    'el-aleti-atolye', 220, '"Third Hand" destekli klips tutucusu alternatifi. PCB ve küçük parça tutma için.', 75],
    ['Elektronik Komponent Düzenleyici', 'el-aleti-atolye', 320, '40 bölmeli SMD ve DIP komponent düzenleme kutusu. Kapak etiketi pencereli.', 110],

    // ── Takı & Aksesuar ──────────────────────────────────
    ['Geometrik Küpe Seti (5 Çift)',      'taki-aksesuar', 280, 'Üçgen, altıgen, daire, kare ve elmas formları. 925 gümüş kanca dahildir. Farklı renk PLA seçenekleri.', 15],
    ['Yüzük (Boylandırma Seti)',          'taki-aksesuar', 190, 'Beden 6-13 arası her ebat, ince bant tasarım. Nikelaj veya epoksi kaplama için uygun yüzey.', 10],
    ['Geometrik Bilezik',                 'taki-aksesuar', 240, 'Eklemli hexagonal panel, el bileğine oturur. 16-18 cm ayarlanabilir bağlantı. Boyanabilir yüzey.', 20],
    ['Kelebek Broş',                      'taki-aksesuar', 220, 'İnce kanat detayı, arkasında iğne yuvası mevcut. Kıyafet ve çanta için. Boyama ile kişiselleştirilebilir.', 12],
    ['Saç Tokası & Klips Seti (10'lu)',   'taki-aksesuar', 200, 'Çiçek, geometrik ve soyut formlar. Çelik yay ekleme yuvası dahil. Çeşitli renklerle sipariş.', 18],
    ['Kolye Ucu (Özel İsimli)',           'taki-aksesuar', 260, 'Sipariş sırasında isim belirtin, baskıyla entegre. Zincir deliği 2 mm. Dil bağlantısı uyumlu.', 14],
    ['Çanta Dekoratif Çekici Uç',         'taki-aksesuar', 180, 'Çanta fermuarına takılan dekoratif uç. Hayvan, nesne ve harf seçenekleri. 3-4 cm form.', 8],
    ['Kolye Kutusu (Kalpli)',              'taki-aksesuar', 310, '8×8 cm mıknatıslı kapanan kutu. İçi kadife astarlı (dahil). Hediye ambalajı olarak ideal.', 85],
    ['Takı Teşhir Standı (T Çubuğu)',    'taki-aksesuar', 250, 'Kolye ve bilezik teşhir standı. 20 cm yükseklik. Butik ve kuyumcu için şık PLA stand.', 80],
    ['Geometrik Küpe Kalıp Seti',         'taki-aksesuar', 350, 'UV reçine küpe döküm kalıpları, 12 farklı form. Kendi küpelerinizi üretmek için.', 40],

    // ── Bahçe & Doğa ─────────────────────────────────────
    ['Saksı Seti (S-M-L 3'lü)',           'bahce-doga', 350, 'Drenaj deliği mevcut. S:10cm / M:15cm / L:20cm yükseklik. Kaktüs, sukulent ve çiçek bitkileri için.', 130],
    ['Tohum Çimlendirme Seti (4'lü)',     'bahce-doga', 260, '4 gözlü çimlendirme kaseti. Şeffaf kapak desteği var (ayrı satılır). Erken fide dönemi için.', 90],
    ['Damlama Sulama Adaptörü',           'bahce-doga', 180, 'Standart PET şişeye vidalanır, toprak içine saplanan sivri uç ile yavaş sulama sağlar.', 40],
    ['Kuş Yem Kasesi (Balkonlu)',         'bahce-doga', 220, 'Balkon demiri veya saksı kenarına kıskaçla tutturulan kuş yem kabı. Yağmura dayanıklı form.', 75],
    ['Bahçe Bitki Etiketi (20'li Set)',   'bahce-doga', 160, 'Kazıklı zemin etiketi, yüzeye yazılabilir mat PLA. 15 cm boy. Tohumdan fidana takip için.', 35],
    ['Saksı Altlığı (Çeşitli Çaplar)',    'bahce-doga', 180, 'Su taşmasını önler. 10, 15, 20 cm çap seçenekleri. Balkon ve ev içi kullanım.', 50],
    ['Teraryum Taban & Bölücü',           'bahce-doga', 290, 'Kapalı teraryum için drenaj katman taban + dikey bölücü. Akuatik ve kara teraryum için.', 95],
    ['Mini Kum Bahçesi Çerçevesi',        'bahce-doga', 310, 'Japon kum bahçesi kiti çerçeve ve tırmık. 20×20 cm, stres giderici masa süsü.', 105],

    // ── Spor & Outdoor ───────────────────────────────────
    ['Bisiklet Gidon Aksesuar Tutucu',    'spor-outdoor', 220, 'Telefon tutucu, ışık veya kamera için gidon bağlantısı. GoPro yuvası uyumlu. Titreşim absorbe plaka.', 70],
    ['Bisiklet Arka Işık Montaj Aparat.', 'spor-outdoor', 180, 'Koltuk direği veya arka çamurluk için ışık klipsi. Çoğu LED arka ışıkla uyumlu.', 50],
    ['Koşu / Yürüyüş Çubuk Aparat.',     'spor-outdoor', 200, 'Nordic yürüyüş çubuklarını duvara asma rafı. Çift çubuk kapasiteli, 2 çift için.', 65],
    ['Halter Plaka Kilitleme Aparatı',    'spor-outdoor', 240, '25-51 mm olimpik ve standart halter için kilit pimi. Olimpik 50 mm veya standart 25 mm seç.', 75],
    ['Atlama İpi Tutucu',                 'spor-outdoor', 160, 'İki atlama ipini düzenli saran kılavuz sarma aparatı. Çantaya kolay takılır.', 40],
    ['Yoga Blok Köşe Koruyucu (4'lü)',   'spor-outdoor', 190, 'Standart EVA yoga blok köşelerine geçer. Zemin ve blok hasarını önler.', 55],
    ['Su Şişesi Çanta Klipsi',            'spor-outdoor', 160, 'Spor çantası D-ring veya kayış halkasına klipsle tutunur. 500-1000 ml şişe uyumlu.', 35],
    ['Antrenman Bilekliği Saatlık Tutucu','spor-outdoor', 170, 'Duvara monte edilir, fitness tracker ve sporcu saatini şarj ederken düzenli tutar.', 45],

    // ── Çocuk & Oyuncak ──────────────────────────────────
    ['Eğitici Alfabe Seti (A-Z)',         'cocuk-oyuncak', 420, '26 harf + 10 rakam, 36 parça. 5 cm boyunda, çocuk eli için güvenli köşeler. Renklendirilebilir PLA.', 145],
    ['Hayvan Figür Seti (10'lu)',         'cocuk-oyuncak', 380, 'Aslan, fil, zürafa, at, kaplumbağa vb. 8-12 cm boylarında. Düzgün yüzey, boya dostu.', 130],
    ['Araç Oyuncak Seti (4'lü)',          'cocuk-oyuncak', 340, 'Araba, kamyon, ambulans ve yangın aracı. Döner tekerlekler (rulman dahil). 12-15 cm.', 115],
    ['Eğitici Şekil Sıralama Kutusu',    'cocuk-oyuncak', 290, 'Daire, kare, üçgen, yıldız, haç delikleri. Montessori yaklaşımı ile şekil-renk öğretimi.', 95],
    ['Parmak Kukla Seti (6'lı)',          'cocuk-oyuncak', 240, 'Masal karakterleri: prenses, ejderha, şövalye, kral, kraliçe, köylü. Doğaçlama oyun için.', 65],
    ['Mini Mutfak Aksesuarları Seti',    'cocuk-oyuncak', 310, 'Oyun hamuru veya oyuncak mutfak için tava, tencere, tabak, çatal. 10-12 cm ölçek.', 100],
    ['Çocuk Adı Plakası (Kişiye Özel)', 'cocuk-oyuncak', 280, 'Sipariş sırasında çocuğun adını belirtin, harf tipografisi ile kapı/oda plakası. 25 cm boy.', 90],

    // ── Araç İçi ─────────────────────────────────────────
    ['Araç Havalandırma Telefon Tutucu','arac-ici', 200, 'Universal havalandırma parmağına takılır. 4.5-7 inch telefon uyumlu. Güçlü yay mekanizması.', 60],
    ['Araç İçi Kargo Askısı',            'arac-ici', 170, 'Ön koltuk başlık rayına asılır. Alışveriş çantası veya çanta askısı. 5 kg taşıma kapasiteli.', 50],
    ['Araç Güneşlik Organizatör',        'arac-ici', 220, 'Güneşlik arkasına geçer, 4 cep. Kart, bilet, not kağıdı, kalem için.', 70],
    ['Araç İçi Bozuk Para Kutusu',       'arac-ici', 180, 'Orta konsol veya torpido gözü uyumlu. Gömme kapak, 1-5 kuruş ayrı bölümler.', 55],
    ['Şarj Kablosu Yönetim Klibi',       'arac-ici', 150, 'Dashboard kenarına yapışır, 3 kablo kanalı. USB-A, USB-C, Lightning düzeni için.', 30],
    ['Araç Hava Tazeleyici Aparatı',     'arac-ici', 160, 'Havalandırma parmağına takılan koku yuvası. 1.5 cm çaplı koku çubukları için.', 35],
];

// ─── İşlem ──────────────────────────────────────────────────────────────
$log = [];
$logHtml = '';
$done = false;

if (isset($_POST['baslat']) && csrfCheck()) {
    $done = true;

    // 1) Kategorileri ekle / slug ile kontrol et
    $katMap = [];
    foreach ($kategoriler as [$baslik, $slug, $ikon, $sira]) {
        $mevcut = DB::row("SELECT id FROM mn_kategoriler WHERE slug=?", [$slug]);
        if ($mevcut) {
            $katMap[$slug] = $mevcut['id'];
            $log[] = ['info', "Kategori mevcut: $baslik"];
        } else {
            $id = DB::insert('mn_kategoriler', [
                'baslik' => $baslik, 'slug' => $slug,
                'ikon'   => $ikon,   'sira' => $sira, 'aktif' => 1,
            ]);
            $katMap[$slug] = $id;
            $log[] = ['ok', "Kategori eklendi: $baslik (ID:$id)"];
        }
    }

    // 2) Materyali ekle
    $matMevcut = DB::row("SELECT id FROM mn_materyaller WHERE baslik='PLA+'");
    if (!$matMevcut) {
        DB::insert('mn_materyaller', ['baslik'=>'PLA+','renk'=>'#0EA5E9','aktif'=>1]);
        $log[] = ['ok', 'Materyal eklendi: PLA+'];
    }

    // 3) Ürünleri ekle
    $eklendi = 0;
    $atlandi = 0;

    foreach ($urunler as [$baslik, $katSlug, $fiyat, $aciklama, $gram]) {
        $sl = slug($baslik);
        $katId = $katMap[$katSlug] ?? null;
        if (!$katId) { $log[] = ['warn', "Kategori bulunamadı: $katSlug → $baslik"]; continue; }

        $mevcut = DB::row("SELECT id FROM mn_urunler WHERE slug=?", [$sl]);
        if ($mevcut) { $atlandi++; continue; }

        // Ağırlık → stok tahmini (1 rulo 1kg PLA = yaklaşık 1000/gram adet kapasitesi)
        $stok = max(5, (int)floor(1000 / max($gram, 10)));

        DB::insert('mn_urunler', [
            'baslik'        => $baslik,
            'slug'          => $sl,
            'aciklama'      => $aciklama,
            'fiyat'         => $fiyat,
            'indirim_fiyat' => 0,
            'stok'          => $stok,
            'materyal'      => 'PLA+',
            'boyut'         => '256×256×256 mm maks.',
            'gorsel'        => null,
            'kategori_id'   => $katId,
            'vitrin'        => 0,
            'aktif'         => 1,
            'sira'          => 0,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
        $eklendi++;
    }

    // İlk 8 ürünü vitrin yap
    DB::q("UPDATE mn_urunler SET vitrin=1 WHERE aktif=1 ORDER BY id ASC LIMIT 8");

    $log[] = ['ok', "TAMAMLANDI — $eklendi ürün eklendi, $atlandi zaten mevcuttu."];
}
?>

<div style="max-width:700px">

<?php if (!$done): ?>
<div class="card">
  <div class="card-header">
    <span class="card-title">📦 MakerWorld PLA Ürün Kataloğu</span>
  </div>

  <div class="alert alert-info" style="margin-bottom:1.5rem">
    Bu script, MakerWorld üzerinde PLA ile basılabilecek
    <strong><?= count($urunler) ?> ürünü</strong>
    <strong><?= count($kategoriler) ?> kategori</strong> altında otomatik olarak ekler.
    Ürünler <em>sipariş üzerine üretim</em> modelinde stok değerleri ile yüklenir.
    Görseller sonradan admin panelinden eklenebilir.
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.6rem;margin-bottom:1.5rem">
    <?php foreach ($kategoriler as [$baslik, $slug, $ikon, $sira]):
      $cnt = count(array_filter($urunler, fn($u) => $u[1] === $slug));
    ?>
    <div style="background:rgba(14,165,233,.07);border:1px solid var(--border);border-radius:8px;padding:.6rem .9rem;font-size:.82rem;display:flex;align-items:center;gap:.5rem">
      <span style="font-size:1.1rem"><?= $ikon ?></span>
      <div>
        <div style="font-weight:600;color:var(--text)"><?= e($baslik) ?></div>
        <div style="color:var(--muted)"><?= $cnt ?> ürün</div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="alert alert-warning" style="margin-bottom:1.5rem">
    ⚠️ Bu script <strong>yalnızca bir kez</strong> çalıştırılmalıdır.
    Tekrar çalıştırırsanız zaten ekli ürünler atlanır (slug kontrolü yapılır).
  </div>

  <form method="POST">
    <input type="hidden" name="csrf" value="<?= csrf() ?>">
    <button name="baslat" type="submit" class="btn btn-primary"
            onclick="return confirm('<?= count($urunler) ?> ürün ve <?= count($kategoriler) ?> kategori eklensin mi?')">
      🚀 Kataloğu Yükle (<?= count($urunler) ?> Ürün)
    </button>
    <a href="/admin/" class="btn btn-outline" style="margin-left:.75rem">İptal</a>
  </form>
</div>

<?php else: ?>

<div class="card">
  <div class="card-header">
    <span class="card-title">📋 Yükleme Sonucu</span>
  </div>
  <div class="update-log">
<?php foreach ($log as [$type, $msg]): ?>
<span class="log-<?= $type === 'warn' ? 'err' : $type ?>">[<?= strtoupper($type) ?>] <?= e($msg) ?></span>
<?php endforeach; ?>
  </div>
  <div style="display:flex;gap:.75rem;margin-top:1.25rem">
    <a href="/admin/urunler.php" class="btn btn-primary">📦 Ürünleri Gör</a>
    <a href="/admin/kategoriler.php" class="btn btn-outline">🗂️ Kategoriler</a>
    <a href="/admin/" class="btn btn-outline">Dashboard</a>
  </div>
</div>

<?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
