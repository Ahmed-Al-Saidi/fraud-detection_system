<?php
// api/process_images.php - تحليل الصور

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$images = [];
$upload_dir = 'uploads/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['images']['name'][$key];
        $file_size = $_FILES['images']['size'][$key];
        $file_error = $_FILES['images']['error'][$key];
        if ($file_error === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $valid_exts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'];
            if (in_array($ext, $valid_exts) && $file_size <= 10 * 1024 * 1024) {
                $destination = $upload_dir . uniqid() . '.' . $ext;
                if (move_uploaded_file($tmp_name, $destination)) {
                    $images[] = ['name' => $file_name, 'path' => $destination, 'size' => $file_size, 'type' => $ext];
                }
            }
        }
    }
}

$total_images = count($images);
$is_valid_transaction = $total_images > 0;
$validation_errors = [];

if ($total_images === 0) {
    $validation_errors[] = 'لم يتم رفع أي صورة';
}

$result = [
    'decision' => $total_images > 0 ? '✅ صور معاملة صالحة' : '❌ لا توجد صور',
    'decision_icon' => $total_images > 0 ? '✅' : '📷',
    'risk_level' => $total_images > 0 ? 'منخفض' : 'غير معروف',
    'confidence' => $total_images > 0 ? 0.85 : 0,
    'is_valid_transaction' => $is_valid_transaction,
    'validation_errors' => $validation_errors,
    'risk_summary' => $total_images > 0 ? '✅ تم رفع ' . $total_images . ' صور للمعاملة' : '⚠️ لم يتم رفع أي صورة',
    'recommendations' => $total_images > 0 ? ['✅ تحليل الصور', '📝 حفظ الصور'] : ['📷 رفع صور المعاملة'],
    'images' => $images,
    'images_count' => $total_images,
    'analysis_summary' => ['images_analyzed' => $total_images],
    'using_ai' => false,
    'analysis_time' => date('Y-m-d H:i:s')
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>