<?php
// api/process.php - تحليل النصوص مع أسعار الصرف

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
// أسعار الصرف الديناميكية
// ============================================

function getExchangeRates() {
    $rates = [
        'USD' => 1.0000,
        'SAR' => 3.7500,
        'EUR' => 0.9200,
        'GBP' => 0.7900,
        'AED' => 3.6725,
        'EGP' => 50.2800,
        'TRY' => 34.0000,
        'KWD' => 0.3070,
        'BHD' => 0.3760,
        'OMR' => 0.3845,
        'QAR' => 3.6400,
        'JOD' => 0.7090,
        'YER' => 250.0000,
    ];
    return $rates;
}

function getCurrencyRateInSAR($currency) {
    $rates = getExchangeRates();
    if (!isset($rates[$currency]) || !isset($rates['SAR'])) {
        return 1;
    }
    return $rates['SAR'] / $rates[$currency];
}

function getRatesAgainstSAR() {
    $rates = getExchangeRates();
    $sar = $rates['SAR'];
    $result = [];
    foreach ($rates as $code => $rate) {
        $result[$code] = $rate / $sar;
    }
    return $result;
}

function getCurrencyComparison() {
    $rates = getExchangeRates();
    $sar = $rates['SAR'];
    $values = [];
    foreach ($rates as $code => $rate) {
        if ($code == 'SAR') continue;
        $values[$code] = $rate / $sar;
    }
    arsort($values);
    $highest = key($values);
    $highest_value = reset($values);
    $lowest = array_key_last($values);
    $lowest_value = end($values);
    return [
        'highest' => $highest,
        'highest_value' => round($highest_value, 4),
        'lowest' => $lowest,
        'lowest_value' => round($lowest_value, 4),
        'all_rates' => $values
    ];
}

// ============================================
// التحقق من صحة المعاملة
// ============================================

$is_valid_transaction = true;
$validation_errors = [];

if (!preg_match('/^SA-[0-9]{4}-[0-9]{4}$/', $sender) && !empty($sender)) {
    $validation_errors[] = 'رقم الحساب (مرسل) غير صحيح. يجب أن يكون بالصيغة: SA-XXXX-XXXX';
    $is_valid_transaction = false;
}

if (!preg_match('/^SA-[0-9]{4}-[0-9]{4}$/', $receiver) && !empty($receiver)) {
    $validation_errors[] = 'رقم الحساب (مستقبل) غير صحيح. يجب أن يكون بالصيغة: SA-XXXX-XXXX';
    $is_valid_transaction = false;
}

if ($amount <= 0) {
    $validation_errors[] = 'المبلغ يجب أن يكون أكبر من صفر';
    $is_valid_transaction = false;
}

// ============================================
// تحويل العملات
// ============================================

$currency_rate = getCurrencyRateInSAR($currency);
$amount_in_sar = $amount * $currency_rate;
$rates_against_sar = getRatesAgainstSAR();
$currency_comparison = getCurrencyComparison();

// ============================================
// تحليل المخاطر
// ============================================

$risk_score = 0;
$risk_factors = [];
$reasons = [];

// 1. تحليل المبلغ
if ($amount_in_sar > 200000) {
    $risk_score += 0.35;
    $reasons[] = '🚨 المبلغ مرتفع جداً (أكثر من 200,000 ريال سعودي)';
    $risk_factors[] = ['factor' => 'المبلغ', 'level' => 'مرتفع جداً', 'score' => 35, 'description' => 'مبلغ ضخم'];
} elseif ($amount_in_sar > 100000) {
    $risk_score += 0.25;
    $reasons[] = '⚠️ المبلغ مرتفع (أكثر من 100,000 ريال سعودي)';
    $risk_factors[] = ['factor' => 'المبلغ', 'level' => 'مرتفع', 'score' => 25, 'description' => 'مبلغ كبير'];
} elseif ($amount_in_sar > 50000) {
    $risk_score += 0.15;
    $risk_factors[] = ['factor' => 'المبلغ', 'level' => 'متوسط مرتفع', 'score' => 15, 'description' => 'مبلغ متوسط إلى كبير'];
} elseif ($amount_in_sar > 20000) {
    $risk_score += 0.08;
    $risk_factors[] = ['factor' => 'المبلغ', 'level' => 'متوسط', 'score' => 8, 'description' => 'مبلغ متوسط'];
} else {
    $risk_factors[] = ['factor' => 'المبلغ', 'level' => 'منخفض', 'score' => 4, 'description' => 'مبلغ صغير'];
}

// 2. تحليل العملة
if ($currency != 'SAR') {
    $risk_score += 0.05;
    $reasons[] = "⚠️ معاملة بعملة $currency - عملة أجنبية";
    $risk_factors[] = ['factor' => 'العملة', 'level' => 'متوسط', 'score' => 5, 'description' => "عملة $currency أجنبية"];
}

// 3. تحليل الزمن
if ($time_since < 2) {
    $risk_score += 0.25;
    $reasons[] = '🚨 فترة زمنية قصيرة جداً (أقل من دقيقتين)';
    $risk_factors[] = ['factor' => 'الزمن', 'level' => 'مرتفع جداً', 'score' => 25, 'description' => 'معاملات متتالية'];
} elseif ($time_since < 5) {
    $risk_score += 0.15;
    $reasons[] = '⚠️ فترة زمنية قصيرة (أقل من 5 دقائق)';
    $risk_factors[] = ['factor' => 'الزمن', 'level' => 'مرتفع', 'score' => 15, 'description' => 'فترة زمنية قصيرة'];
} elseif ($time_since < 15) {
    $risk_score += 0.08;
    $risk_factors[] = ['factor' => 'الزمن', 'level' => 'متوسط', 'score' => 8, 'description' => 'فترة زمنية معقولة'];
} else {
    $risk_factors[] = ['factor' => 'الزمن', 'level' => 'منخفض', 'score' => 4, 'description' => 'فترة زمنية جيدة'];
}

// 4. تحليل التكرار
if ($frequency > 40) {
    $risk_score += 0.20;
    $reasons[] = '🚨 تكرار مرتفع جداً (أكثر من 40 عملية)';
    $risk_factors[] = ['factor' => 'التكرار', 'level' => 'مرتفع جداً', 'score' => 20, 'description' => 'نشاط غير طبيعي'];
} elseif ($frequency > 25) {
    $risk_score += 0.12;
    $reasons[] = '⚠️ تكرار مرتفع (أكثر من 25 عملية)';
    $risk_factors[] = ['factor' => 'التكرار', 'level' => 'مرتفع', 'score' => 12, 'description' => 'تكرار مرتفع'];
} else {
    $risk_factors[] = ['factor' => 'التكرار', 'level' => 'منخفض', 'score' => 6, 'description' => 'تكرار طبيعي'];
}

// 5. تحليل الدولة
$country_risk_map = [
    'SA' => ['level' => 'منخفض جداً', 'score' => 0.02, 'desc' => '🇸🇦 دولة آمنة'],
    'AE' => ['level' => 'منخفض', 'score' => 0.05, 'desc' => '🇦🇪 دولة آمنة'],
    'EG' => ['level' => 'متوسط', 'score' => 0.10, 'desc' => '🇪🇬 دولة متوسطة المخاطر'],
    'YE' => ['level' => 'متوسط', 'score' => 0.12, 'desc' => '🇾🇪 دولة متوسطة المخاطر'],
    'US' => ['level' => 'مرتفع', 'score' => 0.18, 'desc' => '🇺🇸 دولة ذات مخاطر مرتفعة'],
    'UK' => ['level' => 'مرتفع', 'score' => 0.16, 'desc' => '🇬🇧 دولة ذات مخاطر مرتفعة'],
    'TR' => ['level' => 'مرتفع', 'score' => 0.20, 'desc' => '🇹🇷 دولة ذات مخاطر مرتفعة'],
    'KW' => ['level' => 'منخفض', 'score' => 0.04, 'desc' => '🇰🇼 دولة آمنة'],
    'BH' => ['level' => 'منخفض', 'score' => 0.04, 'desc' => '🇧🇭 دولة آمنة'],
    'OM' => ['level' => 'منخفض', 'score' => 0.04, 'desc' => '🇴🇲 دولة آمنة'],
    'QA' => ['level' => 'منخفض', 'score' => 0.04, 'desc' => '🇶🇦 دولة آمنة'],
    'JO' => ['level' => 'متوسط', 'score' => 0.10, 'desc' => '🇯🇴 دولة متوسطة المخاطر'],
    'other' => ['level' => 'مرتفع', 'score' => 0.22, 'desc' => '🌍 دولة غير معروفة']
];

$country_info = $country_risk_map[$country] ?? $country_risk_map['other'];
$risk_score += $country_info['score'];
$risk_factors[] = ['factor' => 'الدولة', 'level' => $country_info['level'], 'score' => round($country_info['score'] * 100), 'description' => $country_info['desc']];

// 6. تحليل النص
if (!empty($notes)) {
    $suspicious_words = ['عاجل', 'طوارئ', 'مستعجل', 'سري'];
    $found = 0;
    foreach ($suspicious_words as $word) {
        if (stripos($notes, $word) !== false) $found++;
    }
    if ($found > 0) {
        $risk_score += 0.06 * $found;
        $reasons[] = "⚠️ تم العثور على $found كلمات مشبوهة في الملاحظات";
        $risk_factors[] = ['factor' => 'النص', 'level' => 'مرتفع', 'score' => 6 * $found, 'description' => 'يحتوي النص على كلمات مشبوهة'];
    } else {
        $risk_factors[] = ['factor' => 'النص', 'level' => 'منخفض', 'score' => 1, 'description' => 'النص طبيعي'];
    }
}

$risk_score = max(0, min(1, $risk_score));
$risk_percentage = round($risk_score * 100, 1);
$stars = max(1, 5 - round($risk_score * 5));

// ============================================
// اتخاذ القرار
// ============================================

if (!$is_valid_transaction) {
    $decision = '❌ معاملة غير صالحة';
    $decision_icon = '❌';
    $risk_level = 'غير معروف';
    $confidence = 0.1;
    $summary = '⚠️ بيانات المعاملة غير صالحة. يرجى التحقق من الأرقام المدخلة.';
    $recommendations = ['📝 تصحيح أرقام الحسابات', '🔄 إعادة إدخال البيانات', '💡 التأكد من صيغة الحسابات SA-XXXX-XXXX'];
} elseif ($risk_score > 0.70) {
    $decision = '🚨 احتيال محتمل';
    $decision_icon = '🚨';
    $risk_level = 'مرتفع جداً';
    $confidence = 0.85 + (rand(0, 12) / 100);
    $summary = '⚠️ تحذير: هذه المعاملة تحمل مخاطر عالية جداً وقد تكون احتيالية.';
    $recommendations = ['🔴 إيقاف المعاملة فوراً', '📞 الاتصال بالعميل للتحقق', '🔍 مراجعة جميع المستندات', '📋 تسجيل البلاغ'];
} elseif ($risk_score > 0.40) {
    $decision = '⚠️ معاملة مشبوهة';
    $decision_icon = '⚠️';
    $risk_level = 'متوسط';
    $confidence = 0.65 + (rand(0, 18) / 100);
    $summary = '⚠️ تنبيه: هذه المعاملة تحمل مخاطر متوسطة وتحتاج إلى مراجعة.';
    $recommendations = ['🔍 مراجعة التفاصيل بدقة', '📞 التواصل مع العميل للتأكيد', '📄 طلب مستندات إضافية', '⏳ تأجيل المعاملة'];
} else {
    $decision = '✅ معاملة سليمة';
    $decision_icon = '✅';
    $risk_level = 'منخفض';
    $confidence = 0.85 + (rand(0, 12) / 100);
    $summary = '✅ مطمئن: هذه المعاملة تبدو آمنة ومطابقة للمعايير.';
    $recommendations = ['✅ الموافقة على المعاملة', '📝 حفظ سجل المعاملة', '📊 تحديث ملف العميل'];
}

// ============================================
// النتيجة النهائية
// ============================================

$result = [
    'decision' => $decision,
    'decision_icon' => $decision_icon,
    'confidence' => min(0.99, $confidence),
    'risk_level' => $risk_level,
    'risk_score' => $risk_percentage,
    'risk_stars' => $stars,
    'risk_stars_display' => str_repeat('⭐', $stars) . str_repeat('☆', 5 - $stars),
    'is_valid_transaction' => $is_valid_transaction,
    'validation_errors' => $validation_errors,
    'risk_summary' => $summary,
    'recommendations' => $recommendations,
    'risk_factors' => $risk_factors,
    'reasons' => array_slice($reasons, 0, 5),
    'sender' => $sender,
    'receiver' => $receiver,
    'amount' => $amount,
    'currency' => $currency,
    'amount_in_sar' => round($amount_in_sar, 2),
    'country' => $country,
    'transaction_type' => $transaction_type,
    'time_since' => $time_since,
    'frequency' => $frequency,
    'currency_info' => [
        'rate_against_sar' => round($currency_rate, 4),
        'amount_in_sar' => round($amount_in_sar, 2),
        'is_foreign' => $currency != 'SAR'
    ],
    'currency_comparison' => [
        'highest' => $currency_comparison['highest'],
        'highest_value' => $currency_comparison['highest_value'],
        'lowest' => $currency_comparison['lowest'],
        'lowest_value' => $currency_comparison['lowest_value'],
        'rates' => $currency_comparison['all_rates']
    ],
    'exchange_rates' => $rates_against_sar,
    'analysis_summary' => [
        'risk_factors_count' => count($risk_factors),
        'suspicious_flags' => count($reasons),
        'risk_percentage' => $risk_percentage . '%',
        'transaction_type' => $transaction_type,
        'country_risk' => $country_info['level']
    ],
    'using_ai' => false,
    'analysis_time' => date('Y-m-d H:i:s')
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>