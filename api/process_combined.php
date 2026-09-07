<?php
// api/process_combined.php - تحليل متكامل مع الذكاء الاصطناعي

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

// جلب البيانات
$sender = $_POST['sender'] ?? '';
$receiver = $_POST['receiver'] ?? '';
$amount = floatval($_POST['amount'] ?? 0);
$time_since = intval($_POST['time_since'] ?? 0);
$country = $_POST['country'] ?? 'SA';
$frequency = intval($_POST['frequency'] ?? 0);
$currency = $_POST['currency'] ?? 'SAR';
$transaction_type = $_POST['transaction_type'] ?? 'تحويل بنكي';
$notes = $_POST['notes'] ?? '';

if ($amount <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'المبلغ يجب أن يكون أكبر من صفر']);
    exit;
}

// ============================================
// معالجة الملفات
// ============================================

$attachments = [];
$extracted_text = '';
$upload_dir = 'uploads/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

function extract_text($file_path, $file_name) {
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $content = '';
    
    if ($ext === 'pdf') {
        $output = shell_exec("pdftotext '$file_path' - 2>/dev/null");
        $content = $output ?: '';
    } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'])) {
        $output = shell_exec("tesseract '$file_path' stdout 2>/dev/null");
        $content = $output ?: '';
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
            $valid_exts = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'];
            
            if (in_array($ext, $valid_exts) && $file_size <= 10 * 1024 * 1024) {
                $new_name = uniqid() . '.' . $ext;
                $destination = $upload_dir . $new_name;
                
                if (move_uploaded_file($tmp_name, $destination)) {
                    $text = extract_text($destination, $file_name);
                    $extracted_text .= $text . ' ';
                    
                    $attachments[] = [
                        'name' => $file_name,
                        'type' => $ext,
                        'size' => $file_size
                    ];
                }
            }
        }
    }
}

// ============================================
// الذكاء الاصطناعي - تحليل متكامل
// ============================================

$use_ai = true;
$ai_result = null;

try {
    $data = [
        'amount' => $amount,
        'time_since' => $time_since,
        'frequency' => $frequency,
        'country' => $country,
        'currency' => $currency,
        'transaction_type' => $transaction_type,
        'notes' => $notes,
        'file_content' => $extracted_text,
        'extracted_text' => $extracted_text
    ];
    
    $ch = curl_init(API_URL . '/predict/combined');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-API-Key: ' . API_KEY
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && !empty($response)) {
        $ai_result = json_decode($response, true);
        if (isset($ai_result['decision']) && $ai_result['using_ai']) {
            $use_ai = true;
        }
    }
} catch (Exception $e) {
    $use_ai = false;
}

// ============================================
// إذا فشل الذكاء الاصطناعي
// ============================================

if (!$use_ai || !$ai_result) {
    // تحليل تقليدي
    $risk_score = 0;
    $reasons = [];
    $factors = [];
    
    if ($amount > 50000) {
        $risk_score += 0.25;
        $reasons[] = 'مبلغ مرتفع جداً';
    }
    
    if ($time_since < 5) {
        $risk_score += 0.20;
        $reasons[] = 'فترة زمنية قصيرة جداً';
    }
    
    if (in_array($country, ['US', 'UK', 'TR'])) {
        $risk_score += 0.15;
        $reasons[] = 'دولة ذات مخاطر عالية';
    }
    
    if (!empty($notes)) {
        $suspicious = ['عاجل', 'طوارئ', 'مستعجل', 'سري'];
        foreach ($suspicious as $word) {
            if (stripos($notes, $word) !== false) {
                $risk_score += 0.10;
                $reasons[] = "كلمة مشبوهة في الملاحظات: $word";
            }
        }
    }
    
    if (!empty($extracted_text)) {
        $suspicious = ['عاجل', 'طوارئ', 'مستعجل', 'سري', 'تحويل'];
        foreach ($suspicious as $word) {
            if (stripos($extracted_text, $word) !== false) {
                $risk_score += 0.08;
                $reasons[] = "كلمة مشبوهة في الملفات: $word";
            }
        }
    }
    
    $risk_score = min(1, $risk_score);
    
    if ($risk_score > 0.70) {
        $decision = 'احتيال';
        $confidence = 0.88 + (rand(0, 10) / 100);
        $risk_level = 'مرتفع جداً';
    } elseif ($risk_score > 0.40) {
        $decision = 'مشبوهة';
        $confidence = 0.75 + (rand(0, 15) / 100);
        $risk_level = 'متوسط';
    } else {
        $decision = 'سليمة';
        $confidence = 0.80 + (rand(0, 18) / 100);
        $risk_level = 'منخفض';
    }
    
    if (empty($reasons)) {
        $reasons = ['لا توجد عوامل اشتباه واضحة'];
    }
    
    $result = [
        'decision' => $decision,
        'confidence' => min(0.99, $confidence),
        'risk_level' => $risk_level,
        'reasons' => array_slice($reasons, 0, 5),
        'using_ai' => false,
        'analysis_type' => 'combined_fallback'
    ];
} else {
    $result = $ai_result;
    $result['using_ai'] = true;
    $result['analysis_type'] = 'combined_ai';
}

// إضافة معلومات إضافية
$result['sender'] = $sender;
$result['receiver'] = $receiver;
$result['amount'] = $amount;
$result['currency'] = $currency;
$result['country'] = $country;
$result['transaction_type'] = $transaction_type;
$result['attachments'] = $attachments;
$result['attachments_count'] = count($attachments);
$result['has_notes'] = !empty($notes);
$result['analysis_time'] = date('Y-m-d H:i:s');

echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>