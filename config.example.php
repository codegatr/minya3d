<?php
// Minya 3D – Config Örneği
// Bu dosyayı config.php olarak kopyalayın ve doldurun.
// Ya da installer/ sihirbazını kullanın.

define('DB_HOST',    'localhost');
define('DB_NAME',    'minya3d_db');
define('DB_USER',    'DB_KULLANICI');
define('DB_PASS',    'DB_SIFRE');
define('DB_CHARSET', 'utf8mb4');

define('SITE_URL',   'https://minya3d.com');
define('SITE_NAME',  'Minya 3D');
define('SITE_EMAIL', 'info@minya3d.com');
define('ADMIN_PATH', '/admin');

define('GITHUB_REPO',  'codegatr/minya3d');
define('GITHUB_TOKEN', '');

define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('MAX_UPLOAD_MB', 50);

define('APP_VERSION', '1.0.0');
define('APP_ENV', 'production');

error_reporting(0);
ini_set('display_errors', 0);
date_default_timezone_set('Europe/Istanbul');
mb_internal_encoding('UTF-8');
