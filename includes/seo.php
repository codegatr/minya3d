<?php
/**
 * Minya 3D – SEO Motoru
 * Meta tag üretimi, Schema.org markup, canonical, OG, sitemap yardımcıları
 */

// ── Türkiye 81 İl + İlçe veritabanı ─────────────────────────────────────────
function seo_iller(): array {
    return [
        'adana'          => ['ad'=>'Adana',          'slug'=>'adana'],
        'adiyaman'       => ['ad'=>'Adıyaman',        'slug'=>'adiyaman'],
        'afyonkarahisar' => ['ad'=>'Afyonkarahisar',  'slug'=>'afyonkarahisar'],
        'agri'           => ['ad'=>'Ağrı',            'slug'=>'agri'],
        'aksaray'        => ['ad'=>'Aksaray',         'slug'=>'aksaray'],
        'amasya'         => ['ad'=>'Amasya',          'slug'=>'amasya'],
        'ankara'         => ['ad'=>'Ankara',          'slug'=>'ankara'],
        'antalya'        => ['ad'=>'Antalya',         'slug'=>'antalya'],
        'ardahan'        => ['ad'=>'Ardahan',         'slug'=>'ardahan'],
        'artvin'         => ['ad'=>'Artvin',          'slug'=>'artvin'],
        'aydin'          => ['ad'=>'Aydın',           'slug'=>'aydin'],
        'balikesir'      => ['ad'=>'Balıkesir',       'slug'=>'balikesir'],
        'bartin'         => ['ad'=>'Bartın',          'slug'=>'bartin'],
        'batman'         => ['ad'=>'Batman',          'slug'=>'batman'],
        'bayburt'        => ['ad'=>'Bayburt',         'slug'=>'bayburt'],
        'bilecik'        => ['ad'=>'Bilecik',         'slug'=>'bilecik'],
        'bingol'         => ['ad'=>'Bingöl',          'slug'=>'bingol'],
        'bitlis'         => ['ad'=>'Bitlis',          'slug'=>'bitlis'],
        'bolu'           => ['ad'=>'Bolu',            'slug'=>'bolu'],
        'burdur'         => ['ad'=>'Burdur',          'slug'=>'burdur'],
        'bursa'          => ['ad'=>'Bursa',           'slug'=>'bursa'],
        'canakkale'      => ['ad'=>'Çanakkale',       'slug'=>'canakkale'],
        'cankiri'        => ['ad'=>'Çankırı',         'slug'=>'cankiri'],
        'corum'          => ['ad'=>'Çorum',           'slug'=>'corum'],
        'denizli'        => ['ad'=>'Denizli',         'slug'=>'denizli'],
        'diyarbakir'     => ['ad'=>'Diyarbakır',      'slug'=>'diyarbakir'],
        'duzce'          => ['ad'=>'Düzce',           'slug'=>'duzce'],
        'edirne'         => ['ad'=>'Edirne',          'slug'=>'edirne'],
        'elazig'         => ['ad'=>'Elazığ',          'slug'=>'elazig'],
        'erzincan'       => ['ad'=>'Erzincan',        'slug'=>'erzincan'],
        'erzurum'        => ['ad'=>'Erzurum',         'slug'=>'erzurum'],
        'eskisehir'      => ['ad'=>'Eskişehir',       'slug'=>'eskisehir'],
        'gaziantep'      => ['ad'=>'Gaziantep',       'slug'=>'gaziantep'],
        'giresun'        => ['ad'=>'Giresun',         'slug'=>'giresun'],
        'gumushane'      => ['ad'=>'Gümüşhane',       'slug'=>'gumushane'],
        'hakkari'        => ['ad'=>'Hakkari',         'slug'=>'hakkari'],
        'hatay'          => ['ad'=>'Hatay',           'slug'=>'hatay'],
        'igdir'          => ['ad'=>'Iğdır',           'slug'=>'igdir'],
        'isparta'        => ['ad'=>'Isparta',         'slug'=>'isparta'],
        'istanbul'       => ['ad'=>'İstanbul',        'slug'=>'istanbul'],
        'izmir'          => ['ad'=>'İzmir',           'slug'=>'izmir'],
        'kahramanmaras'  => ['ad'=>'Kahramanmaraş',   'slug'=>'kahramanmaras'],
        'karabuk'        => ['ad'=>'Karabük',         'slug'=>'karabuk'],
        'karaman'        => ['ad'=>'Karaman',         'slug'=>'karaman'],
        'kars'           => ['ad'=>'Kars',            'slug'=>'kars'],
        'kastamonu'      => ['ad'=>'Kastamonu',       'slug'=>'kastamonu'],
        'kayseri'        => ['ad'=>'Kayseri',         'slug'=>'kayseri'],
        'kilis'          => ['ad'=>'Kilis',           'slug'=>'kilis'],
        'kirikkale'      => ['ad'=>'Kırıkkale',       'slug'=>'kirikkale'],
        'kirklareli'     => ['ad'=>'Kırklareli',      'slug'=>'kirklareli'],
        'kirsehir'       => ['ad'=>'Kırşehir',        'slug'=>'kirsehir'],
        'kocaeli'        => ['ad'=>'Kocaeli',         'slug'=>'kocaeli'],
        'konya'          => ['ad'=>'Konya',           'slug'=>'konya'],
        'kutahya'        => ['ad'=>'Kütahya',         'slug'=>'kutahya'],
        'malatya'        => ['ad'=>'Malatya',         'slug'=>'malatya'],
        'manisa'         => ['ad'=>'Manisa',          'slug'=>'manisa'],
        'mardin'         => ['ad'=>'Mardin',          'slug'=>'mardin'],
        'mersin'         => ['ad'=>'Mersin',          'slug'=>'mersin'],
        'mugla'          => ['ad'=>'Muğla',           'slug'=>'mugla'],
        'mus'            => ['ad'=>'Muş',             'slug'=>'mus'],
        'nevsehir'       => ['ad'=>'Nevşehir',        'slug'=>'nevsehir'],
        'nigde'          => ['ad'=>'Niğde',           'slug'=>'nigde'],
        'ordu'           => ['ad'=>'Ordu',            'slug'=>'ordu'],
        'osmaniye'       => ['ad'=>'Osmaniye',        'slug'=>'osmaniye'],
        'rize'           => ['ad'=>'Rize',            'slug'=>'rize'],
        'sakarya'        => ['ad'=>'Sakarya',         'slug'=>'sakarya'],
        'samsun'         => ['ad'=>'Samsun',          'slug'=>'samsun'],
        'sanliurfa'      => ['ad'=>'Şanlıurfa',       'slug'=>'sanliurfa'],
        'siirt'          => ['ad'=>'Siirt',           'slug'=>'siirt'],
        'sinop'          => ['ad'=>'Sinop',           'slug'=>'sinop'],
        'sirnak'         => ['ad'=>'Şırnak',          'slug'=>'sirnak'],
        'sivas'          => ['ad'=>'Sivas',           'slug'=>'sivas'],
        'tekirdag'       => ['ad'=>'Tekirdağ',        'slug'=>'tekirdag'],
        'tokat'          => ['ad'=>'Tokat',           'slug'=>'tokat'],
        'trabzon'        => ['ad'=>'Trabzon',         'slug'=>'trabzon'],
        'tunceli'        => ['ad'=>'Tunceli',         'slug'=>'tunceli'],
        'usak'           => ['ad'=>'Uşak',            'slug'=>'usak'],
        'van'            => ['ad'=>'Van',             'slug'=>'van'],
        'yalova'         => ['ad'=>'Yalova',          'slug'=>'yalova'],
        'yozgat'         => ['ad'=>'Yozgat',          'slug'=>'yozgat'],
        'zonguldak'      => ['ad'=>'Zonguldak',       'slug'=>'zonguldak'],
    ];
}

// Konya ilçeleri (yerel odak — önce buranın her ilçesini kapsıyoruz)
function seo_konya_ilceleri(): array {
    return [
        'ahirli','akoren','aksehir','altinekin','beysehir','bozkir',
        'cihanbeyli','celtik','cumra','derbent','derebucak','doganhisar',
        'emirgazi','eregli','guneysinir','hadim','halkapinar','huyuk',
        'ilgin','kadinhani','karapinar','karatay','kulu','meram',
        'sarayonu','selcuklu','seydisehir','taskent','tuzlukcu',
        'yalihuyuk','yunak'
    ];
}

function seo_konya_ilce_adi(string $slug): string {
    $map = [
        'ahirli'=>'Ahırlı','akoren'=>'Akören','aksehir'=>'Akşehir',
        'altinekin'=>'Altınekin','beysehir'=>'Beyşehir','bozkir'=>'Bozkır',
        'cihanbeyli'=>'Cihanbeyli','celtik'=>'Çeltik','cumra'=>'Çumra',
        'derbent'=>'Derbent','derebucak'=>'Derebucak','doganhisar'=>'Doğanhisar',
        'emirgazi'=>'Emirgazi','eregli'=>'Ereğli','guneysinir'=>'Güneysınır',
        'hadim'=>'Hadim','halkapinar'=>'Halkapınar','huyuk'=>'Hüyük',
        'ilgin'=>'Ilgın','kadinhani'=>'Kadınhanı','karapinar'=>'Karapınar',
        'karatay'=>'Karatay','kulu'=>'Kulu','meram'=>'Meram',
        'sarayonu'=>'Sarayönü','selcuklu'=>'Selçuklu','seydisehir'=>'Seydişehir',
        'taskent'=>'Taşkent','tuzlukcu'=>'Tuzlukçu','yalihuyuk'=>'Yalıhüyük',
        'yunak'=>'Yunak'
    ];
    return $map[$slug] ?? ucfirst($slug);
}

// ── SEO Meta Üretici ─────────────────────────────────────────────────────────
class SEO {

    private static array $data = [
        'title'       => '',
        'description' => '',
        'canonical'   => '',
        'og_image'    => '',
        'schema'      => [],
        'noindex'     => false,
    ];

    public static function set(array $d): void {
        self::$data = array_merge(self::$data, $d);
    }

    public static function title(string $t): void    { self::$data['title']       = $t; }
    public static function desc(string $d): void     { self::$data['description'] = $d; }
    public static function canonical(string $u): void{ self::$data['canonical']   = $u; }
    public static function noindex(): void           { self::$data['noindex']     = true; }
    public static function addSchema(array $s): void { self::$data['schema'][]    = $s; }

    public static function render(): string {
        $siteName = ayar('site_adi', SITE_NAME);
        $siteUrl  = rtrim(SITE_URL, '/');
        $cur      = $siteUrl . ($_SERVER['REQUEST_URI'] ?? '/');

        $title    = self::$data['title']
            ? self::$data['title'] . ' | ' . $siteName
            : $siteName . ' – PLA 3D Baskı Hizmeti, Konya';

        $desc     = self::$data['description']
            ?: 'Minya 3D: Bambu Lab A1 Combo ile PLA+ 3D baskı hizmeti. Türkiye\'nin her iline hızlı kargo. Sipariş ver, kapına gelsin.';

        $canon    = self::$data['canonical'] ?: $cur;
        $ogImg    = self::$data['og_image']  ?: $siteUrl . '/assets/img/og-default.jpg';

        $out  = "\n";
        // Temel meta
        $out .= '<meta name="description" content="' . e($desc) . '">' . "\n";
        $out .= '<meta name="robots" content="' . (self::$data['noindex'] ? 'noindex,nofollow' : 'index,follow') . '">' . "\n";
        $out .= '<link rel="canonical" href="' . e($canon) . '">' . "\n";
        // OG
        $out .= '<meta property="og:type"        content="website">' . "\n";
        $out .= '<meta property="og:title"       content="' . e($title) . '">' . "\n";
        $out .= '<meta property="og:description" content="' . e($desc)  . '">' . "\n";
        $out .= '<meta property="og:url"         content="' . e($canon) . '">' . "\n";
        $out .= '<meta property="og:image"       content="' . e($ogImg) . '">' . "\n";
        $out .= '<meta property="og:site_name"   content="' . e($siteName) . '">' . "\n";
        $out .= '<meta property="og:locale"      content="tr_TR">' . "\n";
        // Twitter Card
        $out .= '<meta name="twitter:card"        content="summary_large_image">' . "\n";
        $out .= '<meta name="twitter:title"       content="' . e($title) . '">' . "\n";
        $out .= '<meta name="twitter:description" content="' . e($desc)  . '">' . "\n";
        $out .= '<meta name="twitter:image"       content="' . e($ogImg) . '">' . "\n";
        // Schema.org JSON-LD
        foreach (self::$data['schema'] as $schema) {
            $out .= '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }
        return $out;
    }

    public static function renderTitle(): string {
        $siteName = ayar('site_adi', SITE_NAME);
        if (!empty(self::$data['title'])) {
            return e(self::$data['title'] . ' | ' . $siteName);
        }
        return e($siteName . ' – PLA+ 3D Baskı Hizmeti, Konya');
    }

    // ── Schema üreticiler ────────────────────────────────────────────────────

    public static function schemaOrganization(): array {
        $siteUrl = rtrim(SITE_URL, '/');
        return [
            '@context'  => 'https://schema.org',
            '@type'     => 'Organization',
            'name'      => ayar('site_adi', SITE_NAME),
            'url'       => $siteUrl,
            'logo'      => $siteUrl . '/assets/img/logo.svg',
            'email'     => ayar('email', SITE_EMAIL),
            'telephone' => ayar('telefon', ''),
            'address'   => [
                '@type'           => 'PostalAddress',
                'addressLocality' => 'Konya',
                'addressCountry'  => 'TR',
                'streetAddress'   => ayar('adres', ''),
            ],
            'sameAs' => array_filter([
                ayar('instagram', ''),
                ayar('youtube',   ''),
                ayar('linkedin',  ''),
            ]),
        ];
    }

    public static function schemaLocalBusiness(string $city = 'Konya', string $district = ''): array {
        $siteUrl = rtrim(SITE_URL, '/');
        $loc = $district ? "$district, $city" : $city;
        return [
            '@context'         => 'https://schema.org',
            '@type'            => 'LocalBusiness',
            'name'             => ayar('site_adi', SITE_NAME) . ' – ' . $loc . ' 3D Baskı',
            'image'            => $siteUrl . '/assets/img/bambu-a1-combo.webp',
            'url'              => $siteUrl,
            'telephone'        => ayar('telefon', ''),
            'email'            => ayar('email', SITE_EMAIL),
            'priceRange'       => '₺₺',
            'currenciesAccepted' => 'TRY',
            'paymentAccepted'  => 'Cash, Credit Card, Bank Transfer',
            'areaServed'       => ['TR'],
            'address'          => [
                '@type'           => 'PostalAddress',
                'addressLocality' => 'Konya',
                'addressRegion'   => 'Konya',
                'addressCountry'  => 'TR',
            ],
            'geo' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => 37.8716,
                'longitude' => 32.4846,
            ],
            'openingHoursSpecification' => [[
                '@type'    => 'OpeningHoursSpecification',
                'dayOfWeek'=> ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
                'opens'    => '09:00',
                'closes'   => '18:00',
            ]],
        ];
    }

    public static function schemaProduct(array $urun): array {
        $siteUrl = rtrim(SITE_URL, '/');
        $fiyat   = $urun['indirim_fiyat'] > 0 ? $urun['indirim_fiyat'] : $urun['fiyat'];
        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $urun['baslik'],
            'description' => strip_tags($urun['aciklama'] ?? ''),
            'image'       => $urun['gorsel'] ? $siteUrl . '/uploads/urunler/' . $urun['gorsel'] : $siteUrl . '/assets/img/no-image.webp',
            'brand'       => ['@type'=>'Brand', 'name'=>'Minya 3D'],
            'material'    => $urun['materyal'] ?? 'PLA+',
            'offers'      => [
                '@type'         => 'Offer',
                'url'           => $siteUrl . '/urun/' . $urun['slug'],
                'priceCurrency' => 'TRY',
                'price'         => number_format((float)$fiyat, 2, '.', ''),
                'availability'  => $urun['stok'] > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller'        => ['@type'=>'Organization', 'name'=>'Minya 3D'],
            ],
        ];
    }

    public static function schemaBreadcrumb(array $items): array {
        $list = [];
        foreach ($items as $i => [$name, $url]) {
            $list[] = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $name,
                'item'     => $url,
            ];
        }
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $list,
        ];
    }

    public static function schemaFAQ(array $pairs): array {
        $items = [];
        foreach ($pairs as [$q, $a]) {
            $items[] = [
                '@type'          => 'Question',
                'name'           => $q,
                'acceptedAnswer' => ['@type'=>'Answer','text'=>$a],
            ];
        }
        return ['@context'=>'https://schema.org','@type'=>'FAQPage','mainEntity'=>$items];
    }
}
