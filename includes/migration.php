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
            // Çoklu statement çalıştır (PDO::MYSQL_ATTR_EMULATE_PREPARES gerekli)
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

            // Satır satır SQL ifadelerini ayır
            $statements = self::splitSql($sql);
            foreach ($statements as $stmt) {
                $stmt = trim($stmt);
                if ($stmt) $pdo->exec($stmt);
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
     * SQL dosyasını birden fazla statement'a böl
     * Delimiter değişimlerini ve yorumları handle eder.
     */
    private static function splitSql(string $sql): array {
        // -- ve # yorumlarını temizle
        $sql = preg_replace('/--[^\n]*\n/', "\n", $sql);
        $sql = preg_replace('/#[^\n]*\n/', "\n", $sql);
        // /* */ yorumlarını temizle
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        // Noktalı virgülle böl ama string içindekileri koru
        $stmts  = [];
        $buf    = '';
        $inStr  = false;
        $strChr = '';
        $len    = strlen($sql);
        for ($i = 0; $i < $len; $i++) {
            $c = $sql[$i];
            if (!$inStr && ($c === "'" || $c === '"')) {
                $inStr = true; $strChr = $c;
            } elseif ($inStr && $c === $strChr && ($i === 0 || $sql[$i-1] !== '\\')) {
                $inStr = false;
            }
            if (!$inStr && $c === ';') {
                $buf = trim($buf);
                if ($buf) $stmts[] = $buf;
                $buf = '';
            } else {
                $buf .= $c;
            }
        }
        $buf = trim($buf);
        if ($buf) $stmts[] = $buf;
        return $stmts;
    }

    /** Belirli bir migration'ın durumunu döndürür */
    public static function status(string $file): ?array {
        self::init();
        return DB::row("SELECT * FROM " . self::$table . " WHERE dosya=?", [$file]);
    }
}
