<?php
// api/process_files.php - تحليل PDF

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

$attachments = [];
$extracted_text = '';
$upload_dir = 'uploads/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

function extract_text_from_file($file_path, $file_name) {
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $content = '';
    if ($ext === 'pdf') {
        $output = shell_exec("pdftotext '$file_path' - 2>/dev/null");
        $content = $output ?: file_get_contents($file_path) ?: "محاكاة استخراج نص من PDF: " . $file_name;
    }
    return $content;
}

if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
    foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['attachments']['name'][$key];
        $file_size = $_FILES['attachments']['size'][$key];
        $file_error = $_FILES['attachments']['error'][$key];
        if ($file_error === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if ($ext === 'pdf' && $file_size <= 10 * 1024 * 1024) {
                $destination = $upload_dir . uniqid() . '.' . $ext;
                if (move_uploaded_file($tmp_name, $destination)) {
                    $text = extract_text_from_file($destination, $file_name);
                    $extracted_text .= $text . ' ';
                    $attachments[] = ['name' => $file_name, 'type' => $ext, 'size' => $file_size, 'extracted_text' => substr($text, 0, 500)];
                }
            }
        }
    }
}

// ===== تحليل النص =====
$is_valid_transaction = false;
$validation_errors = [];
$transaction_keywords = ['تحويل', 'حساب', 'مبلغ', 'ريال', 'عميل', 'بنك', 'إيداع', 'سحب', 'معاملة', 'فاتورة'];
$found_keywords = 0;

if (!empty($extracted_text)) {
    foreach ($transaction_keywords as $keyword) {
        if (stripos($extracted_text, $keyword) !== false) $found_keywords++;
    }
    $is_valid_transaction = $found_keywords >= 2;
    if (!$is_valid_transaction) {
        $validation_errors[] = 'المستند لا يحتوي على بيانات معاملة مالية واضحة';
    }
} else {
    $validation_errors[] = 'لا يمكن استخراج نص من الملف';
}

$risk_score = 0;
$risk_factors = [];
$reasons = [];

if ($is_valid_transaction) {
    preg_match_all('/\d+[\.,]?\d*/', $extracted_text, $numbers);
    $amounts = array_filter($numbers[0] ?? [], function($n) { return floatval($n) > 100; });
    if (!empty($amounts)) {
        $max = max(array_map('floatval', $amounts));
        if ($max > 200000) { $risk_score += 0.35; $reasons[] = '🚨 مبلغ مرتفع جداً'; }
        elseif ($max > 100000) { $risk_score += 0.25; $reasons[] = '⚠️ مبلغ مرتفع'; }
    }
    if (strlen($extracted_text) < 100) { $risk_score += 0.10; $reasons[] = '⚠️ النص قصير جداً'; }
}

$risk_score = max(0, min(1, $risk_score));
$risk_percentage = round($risk_score * 100, 1);

if (!$is_valid_transaction) {
    $result = [
        'decision' => '❌ ليس معاملة مالية',
        'decision_icon' => '📄',
        'risk_level' => 'غير معروف',
        'confidence' => 0.1,
        'is_valid_transaction' => false,
        'validation_errors' => $validation_errors,
        'risk_summary' => '⚠️ المستند لا يحتوي على بيانات معاملة مالية واضحة.',
        'recommendations' => ['📄 تأكد من الملف', '🔄 حاول رفع ملف آخر', '💡 استخدم تحليل النصوص'],
        'risk_factors' => [],
        'reasons' => $validation_errors,
        'attachments' => $attachments,
        'attachments_count' => count($attachments),
        'extracted_text_preview' => substr($extracted_text, 0, 300),
        'analysis_summary' => ['keywords_found' => $found_keywords, 'is_transaction' => 'لا'],
        'using_ai' => false,
        'analysis_time' => date('Y-m-d H:i:s')
    ];
} else {
    $result = [
        'decision' => $risk_score > 0.70 ? '🚨 احتيال محتمل' : ($risk_score > 0.40 ? '⚠️ معاملة مشبوهة' : '✅ معاملة صالحة'),
        'decision_icon' => $risk_score > 0.70 ? '🚨' : ($risk_score > 0.40 ? '⚠️' : '✅'),
        'risk_level' => $risk_score > 0.70 ? 'مرتفع جداً' : ($risk_score > 0.40 ? 'متوسط' : 'منخفض'),
        'confidence' => 0.85,
        'is_valid_transaction' => true,
        'validation_errors' => [],
        'risk_summary' => $risk_score > 0.70 ? '⚠️ تحذير: مخاطر عالية' : ($risk_score > 0.40 ? '⚠️ يحتاج مراجعة' : '✅ معاملة آمنة'),
        'recommendations' => $risk_score > 0.70 ? ['🔴 إيقاف المعاملة', '📞 التحقق من العميل'] : ($risk_score > 0.40 ? ['🔍 مراجعة التفاصيل'] : ['✅ الموافقة']),
        'risk_factors' => [],
        'reasons' => $reasons,
        'attachments' => $attachments,
        'attachments_count' => count($attachments),
        'extracted_text_preview' => substr($extracted_text, 0, 300),
        'analysis_summary' => ['keywords_found' => $found_keywords, 'is_transaction' => 'نعم'],
        'using_ai' => false,
        'analysis_time' => date('Y-m-d H:i:s')
    ];
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>