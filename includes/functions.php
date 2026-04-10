<?php
function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function slug(string $s): string {
    $tr = ['ş'=>'s','ı'=>'i','ğ'=>'g','ü'=>'u','ö'=>'o','ç'=>'c',
           'Ş'=>'s','İ'=>'i','Ğ'=>'g','Ü'=>'u','Ö'=>'o','Ç'=>'c'];
    $s = strtr(mb_strtolower($s, 'UTF-8'), $tr);
    $s = preg_replace('/[^a-z0-9\s-]/', '', $s);
    $s = preg_replace('/[\s-]+/', '-', trim($s));
    return $s;
}

function para(float $f, ?string $symbol = null): string {
    if ($symbol === null) {
        static $currency = null;
        if ($currency === null) {
            try { $currency = ayar('para_birimi', '₺'); }
            catch (Throwable) { $currency = '₺'; }
        }
        $symbol = $currency;
    }
    return $symbol . number_format($f, 2, ',', '.');
}

function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}

function flash(string $key, string $msg): void {
    $_SESSION['flash'][$key] = $msg;
}

function getFlash(string $key): ?string {
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function uploadFile(array $file, string $dir, array $allowed = ['jpg','jpeg','png','webp','gif','stl','obj','3mf']): string|false {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return false;
    if ($file['size'] > MAX_UPLOAD_MB * 1024 * 1024) return false;
    $name = uniqid('m3d_', true) . '.' . $ext;
    $path = rtrim($dir, '/') . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $path)) return false;
    return $name;
}

function ayar(string $key, string $default = ''): string {
    static $cache = [];
    if (!isset($cache[$key])) {
        $row = DB::row("SELECT deger FROM mn_ayarlar WHERE anahtar=?", [$key]);
        $cache[$key] = $row ? $row['deger'] : $default;
    }
    return $cache[$key];
}

function sepetEkle(int $urun_id, int $adet = 1): void {
    if (!isset($_SESSION['sepet'])) $_SESSION['sepet'] = [];
    if (isset($_SESSION['sepet'][$urun_id])) {
        $_SESSION['sepet'][$urun_id] += $adet;
    } else {
        $_SESSION['sepet'][$urun_id] = $adet;
    }
}

function sepetTopla(): float {
    if (empty($_SESSION['sepet'])) return 0;
    $ids = array_keys($_SESSION['sepet']);
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $rows = DB::all("SELECT id, fiyat FROM mn_urunler WHERE id IN ($in)", $ids);
    $total = 0;
    foreach ($rows as $r) {
        $total += $r['fiyat'] * ($_SESSION['sepet'][$r['id']] ?? 0);
    }
    return $total;
}

function sepetAdet(): int {
    return array_sum($_SESSION['sepet'] ?? []);
}

function isAdmin(): bool {
    return !empty($_SESSION['admin_id']);
}

function requireAdmin(): void {
    if (!isAdmin()) redirect('/admin/login.php');
}

function csrf(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfCheck(): bool {
    return isset($_POST['csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']);
}

// Urun gorsel URL: placeholder veya gercek gorsel
function urunGorsel(array $urun, string $size = 'full'): string {
    $g = $urun['gorsel'] ?? '';
    if (!$g) {
        $slug = $urun['kat_slug'] ?? 'ev-yasam';
        $g    = "placeholder-{$slug}.svg";
    }
    return UPLOAD_URL . 'urunler/' . $g;
}

function timeAgo(string $date): string {
    $diff = time() - strtotime($date);
    if ($diff < 60)     return $diff . ' saniye once';
    if ($diff < 3600)   return floor($diff/60) . ' dakika once';
    if ($diff < 86400)  return floor($diff/3600) . ' saat once';
    if ($diff < 604800) return floor($diff/86400) . ' gun once';
    return date('d.m.Y', strtotime($date));
}

function paginate(int $total, int $page, int $perPage = 12, string $url = ''): array {
    $pages  = (int)ceil($total / $perPage);
    $offset = ($page - 1) * $perPage;
    return compact('pages','offset','perPage','page');
}
