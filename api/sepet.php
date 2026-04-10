<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

$action   = $_POST['action'] ?? '';
$urun_id  = (int)($_POST['urun_id'] ?? 0);

if ($urun_id < 1) { echo json_encode(['ok'=>false,'msg'=>'Geçersiz ürün']); exit; }

$urun = DB::row("SELECT id, stok FROM mn_urunler WHERE id=? AND aktif=1", [$urun_id]);
if (!$urun) { echo json_encode(['ok'=>false,'msg'=>'Ürün bulunamadı']); exit; }

switch ($action) {
    case 'ekle':
        sepetEkle($urun_id, 1);
        break;
    case 'azalt':
        if (isset($_SESSION['sepet'][$urun_id]) && $_SESSION['sepet'][$urun_id] > 1) {
            $_SESSION['sepet'][$urun_id]--;
        } else {
            unset($_SESSION['sepet'][$urun_id]);
        }
        break;
    case 'sil':
        unset($_SESSION['sepet'][$urun_id]);
        break;
}

echo json_encode([
    'ok'         => true,
    'sepet_adet' => sepetAdet(),
    'sepet_tutar'=> sepetTopla(),
]);
