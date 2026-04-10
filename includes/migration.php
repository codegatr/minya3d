<?php
/**
 * Minya 3D – Migration Motoru
 * Kullanım: Migration::run() → bekleyen tüm migration'ları sırayla uygular
 */
class Migration {

    private static string $dir   = '';
    private static string $table = 'mn_migrations';

    public static function init(): void {
        if (!self::$dir) self::$dir = dirname(__DIR__) . '/migrations/';
        // Takip tablosunu oluştur (yoksa)
        DB::q("CREATE TABLE IF NOT EXISTS `" . self::$table . "` (
            `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `dosya`        VARCHAR(200) NOT NULL UNIQUE,
            `durum`        ENUM('ok','hata') DEFAULT 'ok',
            `hata_mesaji`  TEXT,
            `uygulandi_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    /** Uygulanmış migration dosya adlarını döndürür */
    public static function applied(): array {
        self::init();
        $rows = DB::all("SELECT dosya FROM " . self::$table . " WHERE durum='ok' ORDER BY dosya");
        return array_column($rows, 'dosya');
    }

    /** Tüm migration dosyalarını döndürür (sıralı) */
    public static function all(): array {
        self::init();
        $files = glob(self::$dir . '*.sql');
        if (!$files) return [];
        sort($files);
        return array_map('basename', $files);
    }

    /** Bekleyen (uygulanmamış) migration'ları döndürür */
    public static function pending(): array {
        $applied = self::applied();
        return array_values(array_filter(self::all(), fn($f) => !in_array($f, $applied)));
    }

    /**
     * Belirli bir SQL dosyasını çalıştır
     * @return array{ok: bool, msg: string}
     */
    public static function apply(string $file): array {
        self::init();
        $path = self::$dir . $file;
        if (!file_exists($path)) {
            return ['ok' => false, 'msg' => "Dosya bulunamadı: $file"];
        }
        $sql = file_get_contents($path);
        if (!$sql) {
            return ['ok' => false, 'msg' => "Boş dosya: $file"];
        }

        // İzolasyon: zaten uygulanmışsa atla
        $mevcut = DB::row("SELECT id, durum FROM " . self::$table . " WHERE dosya=?", [$file]);
        if ($mevcut && $mevcut['durum'] === 'ok') {
            return ['ok' => true, 'msg' => "Zaten uygulanmış: $file"];
        }

        try {
            $pdo = DB::get();
            // emulate_prepares: multi-statement exec için gerekli
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

            $statements = self::splitSql($sql);

            if (empty($statements)) {
                $log[] = ['ok', "Boş/yorum-only dosya: $file"];
                return ['ok' => true, 'msg' => "Atlandı (içerik yok): $file"];
            }

            foreach ($statements as $i => $stmt) {
                $stmt = trim($stmt);
                if (!$stmt) continue;
                try {
                    $pdo->exec($stmt);
                } catch (PDOException $stmtEx) {
                    // "Duplicate key name" = index zaten var → uyarı ver, devam et
                    $code = $stmtEx->getCode();
                    $msg  = $stmtEx->getMessage();
                    if (str_contains($msg, 'Duplicate key name') ||
                        str_contains($msg, 'already exists') ||
                        $code == '42S01' || $code == '42S21') {
                        // Zaten var, atla
                        continue;
                    }
                    throw $stmtEx; // gerçek hata → yukarıya fırlat
                }
            }

            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            // Takip tablosuna ekle / güncelle
            if ($mevcut) {
                DB::q("UPDATE " . self::$table . " SET durum='ok', hata_mesaji=NULL, uygulandi_at=NOW() WHERE dosya=?", [$file]);
            } else {
                DB::insert(self::$table, ['dosya' => $file, 'durum' => 'ok', 'uygulandi_at' => date('Y-m-d H:i:s')]);
            }
            return ['ok' => true, 'msg' => "✓ $file"];

        } catch (PDOException $e) {
            $msg = $e->getMessage();
            // Hata kaydını tabloya yaz
            if ($mevcut) {
                DB::q("UPDATE " . self::$table . " SET durum='hata', hata_mesaji=? WHERE dosya=?", [$msg, $file]);
            } else {
                try {
                    DB::insert(self::$table, ['dosya' => $file, 'durum' => 'hata', 'hata_mesaji' => $msg, 'uygulandi_at' => date('Y-m-d H:i:s')]);
                } catch (Throwable) {}
            }
            return ['ok' => false, 'msg' => "✗ $file → $msg"];
        }
    }

    /**
     * Tüm bekleyen migration'ları sırayla çalıştır
     * @return array{file: string, ok: bool, msg: string}[]
     */
    public static function run(): array {
        $results  = [];
        $pending  = self::pending();
        foreach ($pending as $file) {
            $res = self::apply($file);
            $results[] = ['file' => $file, 'ok' => $res['ok'], 'msg' => $res['msg']];
            // Hata varsa dur (sıralama bozulmasın)
            if (!$res['ok']) break;
        }
        return $results;
    }

    /**
     * SQL dosyasını birden fazla statement'a böl.
     * Türkçe/unicode yorum satırlarını satır bazlı temizler (regex byte-offset sorunu yok).
     * String parse için mb_str_split kullanır (multi-byte güvenli).
     */
    private static function splitSql(string $sql): array {
        // ── 1) Satır bazlı yorum temizleme (regex yerine — Türkçe karakter güvenli) ──
        $lines   = explode("\n", str_replace("\r\n", "\n", $sql));
        $cleaned = [];
        $inBlock = false;   // /* */ blok yorum içinde mi?

        foreach ($lines as $line) {
            // /* ... */ blok başlangıcı
            if (!$inBlock && str_contains($line, '/*')) {
                $before = substr($line, 0, strpos($line, '/*'));
                if (str_contains($line, '*/')) {
                    // Tek satırda açılıp kapanıyor
                    $after = substr($line, strrpos($line, '*/') + 2);
                    $line  = $before . $after;
                } else {
                    $inBlock = true;
                    $line    = $before;
                }
            } elseif ($inBlock) {
                if (str_contains($line, '*/')) {
                    $inBlock = false;
                    $line    = substr($line, strpos($line, '*/') + 2);
                } else {
                    continue; // blok yorum içi satır → atla
                }
            }

            // -- ve # tek satır yorumlarını temizle
            $trimmed = ltrim($line);
            if (str_starts_with($trimmed, '--') || str_starts_with($trimmed, '#')) {
                continue;
            }

            // Satır içi inline -- yorumu (string dışındaysa)
            if (str_contains($line, '--')) {
                $line = self::stripInlineComment($line);
            }

            $cleaned[] = $line;
        }

        $sql = implode("\n", $cleaned);

        // ── 2) Statement bölme (mb_str_split ile multi-byte güvenli) ──
        $stmts  = [];
        $buf    = '';
        $inStr  = false;
        $strChr = '';
        $chars  = mb_str_split($sql, 1, 'UTF-8'); // UTF-8 karakter dizisi

        foreach ($chars as $idx => $c) {
            if (!$inStr) {
                if ($c === "'" || $c === '"' || $c === '`') {
                    $inStr  = true;
                    $strChr = $c;
                    $buf   .= $c;
                } elseif ($c === ';') {
                    $stmt = trim($buf);
                    if ($stmt !== '') $stmts[] = $stmt;
                    $buf = '';
                } else {
                    $buf .= $c;
                }
            } else {
                $buf .= $c;
                if ($c === $strChr) {
                    // Escape kontrol: bir önceki karakter backslash mı?
                    $prev = $idx > 0 ? $chars[$idx - 1] : '';
                    if ($prev !== '\\') {
                        $inStr = false;
                    }
                }
            }
        }

        $last = trim($buf);
        if ($last !== '') $stmts[] = $last;

        return $stmts;
    }

    /** Satır içi -- yorumunu sil (string dışındaysa) */
    private static function stripInlineComment(string $line): string {
        $out    = '';
        $inStr  = false;
        $strChr = '';
        $chars  = mb_str_split($line, 1, 'UTF-8');
        $len    = count($chars);

        for ($i = 0; $i < $len; $i++) {
            $c = $chars[$i];
            if (!$inStr) {
                if ($c === "'" || $c === '"' || $c === '`') {
                    $inStr = true; $strChr = $c; $out .= $c;
                } elseif ($c === '-' && isset($chars[$i+1]) && $chars[$i+1] === '-') {
                    break; // Satırın geri kalanı yorum, dur
                } else {
                    $out .= $c;
                }
            } else {
                $out .= $c;
                if ($c === $strChr && ($i === 0 || $chars[$i-1] !== '\\')) {
                    $inStr = false;
                }
            }
        }
        return $out;
    }

    /** Belirli bir migration'ın durumunu döndürür */
    public static function status(string $file): ?array {
        self::init();
        return DB::row("SELECT * FROM " . self::$table . " WHERE dosya=?", [$file]);
    }
}
