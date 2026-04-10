<?php
class DB {
    private static ?PDO $instance = null;

    public static function get(): PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                ]);
            } catch (PDOException $e) {
                error_log('DB Bağlantı Hatası: ' . $e->getMessage());
                die('<div style="font-family:sans-serif;padding:2rem;color:#e11d48">Veritabanı bağlantısı kurulamadı. Lütfen config.php dosyasını kontrol edin.</div>');
            }
        }
        return self::$instance;
    }

    public static function q(string $sql, array $params = []): PDOStatement {
        $stmt = self::get()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function row(string $sql, array $params = []): ?array {
        $r = self::q($sql, $params)->fetch();
        return $r ?: null;
    }

    public static function all(string $sql, array $params = []): array {
        return self::q($sql, $params)->fetchAll();
    }

    public static function insert(string $table, array $data): int {
        $cols = implode(',', array_map(fn($k) => "`$k`", array_keys($data)));
        $vals = implode(',', array_fill(0, count($data), '?'));
        self::q("INSERT INTO `$table` ($cols) VALUES ($vals)", array_values($data));
        return (int)self::get()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int {
        $set = implode(',', array_map(fn($k) => "`$k`=?", array_keys($data)));
        $stmt = self::q("UPDATE `$table` SET $set WHERE $where", [...array_values($data), ...$whereParams]);
        return $stmt->rowCount();
    }

    public static function lastId(): int {
        return (int)self::get()->lastInsertId();
    }
}
