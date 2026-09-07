<?php
// index.php - الواجهة الرئيسية المعدلة
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام كشف الاحتيال المالي المتقدم - AI Fraud Detection System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <!-- ============================================ -->
        <!-- HEADER -->
        <!-- ============================================ -->
        <header class="header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-shield-halved"></i>
                    <span class="logo-pulse"></span>
                </div>
                <div>
                    <h1>كشف الاحتيال المالي</h1>
                    <span class="logo-sub">AI Fraud Detection System v5.0</span>
                </div>
            </div>
            <div class="status">
                <div class="status-dot">
                    <span class="dot"></span>
                    <span>النظام نشط</span>
                </div>
                <span class="badge badge-gold">
                    <i class="fas fa-crown"></i> AI Pro
                </span>
                <span class="badge badge-ai">
                    <i class="fas fa-brain"></i> الذكاء الاصطناعي
                </span>
                <div class="ai-status">
                    <div class="ai-indicator"></div>
                    <span>AI Online</span>
                </div>
            </div>
        </header>

        <!-- ============================================ -->
        <!-- التبويبات الرئيسية -->
        <!-- ============================================ -->
        <nav class="main-tabs">
            <button class="tab-btn active" data-tab="home">
                <i class="fas fa-home"></i>
                <span>الرئيسية</span>
                <span class="tab-badge">AI</span>
            </button>
            <button class="tab-btn" data-tab="text">
                <i class="fas fa-file-lines"></i>
                <span>تحليل النصوص</span>
                <span class="tab-badge">AI</span>
            </button>
            <button class="tab-btn" data-tab="pdf">
                <i class="fas fa-file-pdf"></i>
                <span>تحليل PDF</span>
                <span class="tab-badge">AI</span>
            </button>
            <button class="tab-btn" data-tab="images">
                <i class="fas fa-image"></i>
                <span>تحليل الصور</span>
                <span class="tab-badge">AI</span>
            </button>
            <button class="tab-btn" data-tab="currency">
                <i class="fas fa-money-bill-wave"></i>
                <span>كشف العملات</span>
                <span class="tab-badge">AI Pro</span>
            </button>
        </nav>

        <!-- ============================================ -->
        <!-- TAB 1: HOME -->
        <!-- ============================================ -->
        <div class="tab-content active" id="home">
            <div class="dashboard-grid">
                <!-- إحصائيات -->
                <div class="stats-grid">
                    <div class="stat-card stat-card-1">
                        <div class="stat-icon green">
                            <i class="fas fa-check-circle"></i>
                            <div class="stat-ring"></div>
                        </div>
                        <div class="stat-info">
                            <h3 class="counter" data-target="0">0</h3>
                            <p>معاملات سليمة</p>
                            <span class="stat-trend"><i class="fas fa-arrow-up"></i> +12%</span>
                        </div>
                    </div>
                    <div class="stat-card stat-card-2">
                        <div class="stat-icon orange">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div class="stat-ring"></div>
                        </div>
                        <div class="stat-info">
                            <h3 class="counter" data-target="0">0</h3>
                            <p>معاملات مشبوهة</p>
                            <span class="stat-trend"><i class="fas fa-arrow-down"></i> -3%</span>
                        </div>
                    </div>
                    <div class="stat-card stat-card-3">
                        <div class="stat-icon red">
                            <i class="fas fa-times-circle"></i>
                            <div class="stat-ring"></div>
                        </div>
                        <div class="stat-info">
                            <h3 class="counter" data-target="0">0</h3>
                            <p>معاملات احتيالية</p>
                            <span class="stat-trend"><i class="fas fa-arrow-up"></i> +5%</span>
                        </div>
                    </div>
                    <div class="stat-card stat-card-4">
                        <div class="stat-icon blue">
                            <i class="fas fa-clock"></i>
                            <div class="stat-ring"></div>
                        </div>
                        <div class="stat-info">
                            <h3 class="counter" data-target="0">0</h3>
                            <p>إجمالي المعاملات</p>
                            <span class="stat-trend"><i class="fas fa-arrow-up"></i> +8%</span>
                        </div>
                    </div>
                </div>

                <!-- حالة الذكاء الاصطناعي -->
                <div class="ai-dashboard">
                    <div class="panel ai-panel">
                        <div class="panel-header">
                            <i class="fas fa-brain"></i>
                            <h2>حالة الذكاء الاصطناعي</h2>
                            <span class="badge badge-ai">نشط</span>
                        </div>
                        <div class="ai-status-grid">
                            <div class="ai-status-item">
                                <span class="ai-label">النموذج</span>
                                <span class="ai-value">Random Forest v3.0</span>
                                <div class="ai-bar"><div class="ai-bar-fill" style="width: 100%;"></div></div>
                            </div>
                            <div class="ai-status-item">
                                <span class="ai-label">الدقة</span>
                                <span class="ai-value" id="aiAccuracy">94.7%</span>
                                <div class="ai-bar"><div class="ai-bar-fill" style="width: 94.7%;"></div></div>
                            </div>
                            <div class="ai-status-item">
                                <span class="ai-label">الذاكرة المستخدمة</span>
                                <span class="ai-value">2.4 GB</span>
                                <div class="ai-bar"><div class="ai-bar-fill" style="width: 48%;"></div></div>
                            </div>
                            <div class="ai-status-item">
                                <span class="ai-label">وقت الاستجابة</span>
                                <span class="ai-value" id="aiResponseTime">0.8 ms</span>
                                <div class="ai-bar"><div class="ai-bar-fill" style="width: 20%;"></div></div>
                            </div>
                        </div>
                    </div>

                    <div class="panel activity-panel">
                        <div class="panel-header">
                            <i class="fas fa-chart-line"></i>
                            <h2>نشاط النظام الحي</h2>
                            <span class="badge" style="background: rgba(52, 211, 153, 0.15); color: #34d399;">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i> مباشر
                            </span>
                        </div>
                        <div class="activity-log" id="activityLog">
                            <div class="activity-item">
                                <span class="activity-time">الآن</span>
                                <span class="activity-text">🟢 النظام جاهز للاستخدام</span>
                            </div>
                            <div class="activity-item">
                                <span class="activity-time">-</span>
                                <span class="activity-text">🤖 نموذج الذكاء الاصطناعي نشط</span>
                            </div>
                            <div class="activity-item">
                                <span class="activity-time">-</span>
                                <span class="activity-text">📊 4 أنواع من التحليل متاحة</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- شريحة تصنيف المعاملات -->
                <!-- ============================================ -->
                <div class="panel full-width classification-section">
                    <div class="panel-header">
                        <i class="fas fa-layer-group"></i>
                        <h2>تصنيف المعاملات</h2>
                        <span class="badge badge-gold">دليل المستخدم</span>
                    </div>
                    
                    <div class="classification-grid">
                        <!-- بطاقة سليمة -->
                        <div class="class-card class-safe">
                            <div class="class-header">
                                <div class="class-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3>سليمة</h3>
                                <span class="class-badge badge-safe">آمنة</span>
                            </div>
                            <div class="class-content">
                                <div class="class-condition">
                                    <i class="fas fa-check"></i>
                                    <span>المبلغ معقول (أقل من 20,000 ريال)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-check"></i>
                                    <span>الوقت مناسب (أكثر من 30 دقيقة)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-check"></i>
                                    <span>التكرار طبيعي (3-10 مرات أسبوعياً)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-check"></i>
                                    <span>دولة منخفضة المخاطر (🇸🇦 السعودية)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-check"></i>
                                    <span>ملاحظات طبيعية (بدون كلمات مشبوهة)</span>
                                </div>
                                <div class="class-result">
                                    <i class="fas fa-shield-halved"></i>
                                    <span>نسبة المخاطرة: 0% - 25%</span>
                                    <div class="class-bar"><div class="class-bar-fill safe-fill" style="width: 20%;"></div></div>
                                </div>
                            </div>
                            <div class="class-footer">
                                <i class="fas fa-star"></i>
                                <span>توصية: ✅ الموافقة على المعاملة</span>
                            </div>
                        </div>

                        <!-- بطاقة مشبوهة -->
                        <div class="class-card class-suspicious">
                            <div class="class-header">
                                <div class="class-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <h3>مشبوهة</h3>
                                <span class="class-badge badge-suspicious">مراجعة</span>
                            </div>
                            <div class="class-content">
                                <div class="class-condition">
                                    <i class="fas fa-exclamation"></i>
                                    <span>المبلغ مرتفع (20,000 - 50,000 ريال)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-exclamation"></i>
                                    <span>الوقت قصير (5 - 15 دقيقة)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-exclamation"></i>
                                    <span>تكرار غير طبيعي (10-20 مرة أسبوعياً)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-exclamation"></i>
                                    <span>دولة متوسطة المخاطر (🇪🇬 مصر)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-exclamation"></i>
                                    <span>ملاحظات تحتوي على كلمات مشبوهة</span>
                                </div>
                                <div class="class-result">
                                    <i class="fas fa-shield-halved"></i>
                                    <span>نسبة المخاطرة: 25% - 55%</span>
                                    <div class="class-bar"><div class="class-bar-fill suspicious-fill" style="width: 45%;"></div></div>
                                </div>
                            </div>
                            <div class="class-footer">
                                <i class="fas fa-clock"></i>
                                <span>توصية: ⏳ تأجيل المعاملة لحين التحقق</span>
                            </div>
                        </div>

                        <!-- بطاقة احتيال -->
                        <div class="class-card class-fraud">
                            <div class="class-header">
                                <div class="class-icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <h3>احتيال</h3>
                                <span class="class-badge badge-fraud">خطر</span>
                            </div>
                            <div class="class-content">
                                <div class="class-condition">
                                    <i class="fas fa-times"></i>
                                    <span>المبلغ مرتفع جداً (أكثر من 50,000 ريال)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-times"></i>
                                    <span>الوقت فوري (أقل من 5 دقائق)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-times"></i>
                                    <span>تكرار شاذ (أكثر من 20 مرة أسبوعياً)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-times"></i>
                                    <span>دولة مرتفعة المخاطر (🇺🇸 الولايات المتحدة)</span>
                                </div>
                                <div class="class-condition">
                                    <i class="fas fa-times"></i>
                                    <span>كلمات مشبوهة متعددة (عاجل، طوارئ، سري)</span>
                                </div>
                                <div class="class-result">
                                    <i class="fas fa-shield-halved"></i>
                                    <span>نسبة المخاطرة: 55% - 100%</span>
                                    <div class="class-bar"><div class="class-bar-fill fraud-fill" style="width: 85%;"></div></div>
                                </div>
                            </div>
                            <div class="class-footer">
                                <i class="fas fa-ban"></i>
                                <span>توصية: 🚨 إيقاف المعاملة فوراً</span>
                            </div>
                        </div>
                    </div>

                    <!-- جدول مقارنة سريع -->
                    <div class="comparison-table">
                        <div class="comparison-row header-row">
                            <span>المعيار</span>
                            <span>✅ سليمة</span>
                            <span>⚠️ مشبوهة</span>
                            <span>🚨 احتيال</span>
                        </div>
                        <div class="comparison-row">
                            <span><i class="fas fa-coins"></i> المبلغ</span>
                            <span style="color: #34d399;">أقل من 20,000</span>
                            <span style="color: #fb923c;">20,000 - 50,000</span>
                            <span style="color: #f87171;">أكثر من 50,000</span>
                        </div>
                        <div class="comparison-row">
                            <span><i class="fas fa-clock"></i> الزمن</span>
                            <span style="color: #34d399;">أكثر من 30 دقيقة</span>
                            <span style="color: #fb923c;">5 - 15 دقيقة</span>
                            <span style="color: #f87171;">أقل من 5 دقائق</span>
                        </div>
                        <div class="comparison-row">
                            <span><i class="fas fa-arrow-trend-up"></i> التكرار</span>
                            <span style="color: #34d399;">3 - 10 مرات</span>
                            <span style="color: #fb923c;">10 - 20 مرة</span>
                            <span style="color: #f87171;">أكثر من 20 مرة</span>
                        </div>
                        <div class="comparison-row">
                            <span><i class="fas fa-globe"></i> الدولة</span>
                            <span style="color: #34d399;">🇸🇦 منخفضة</span>
                            <span style="color: #fb923c;">🇪🇬 متوسطة</span>
                            <span style="color: #f87171;">🇺🇸 مرتفعة</span>
                        </div>
                        <div class="comparison-row">
                            <span><i class="fas fa-comment"></i> الملاحظات</span>
                            <span style="color: #34d399;">طبيعية</span>
                            <span style="color: #fb923c;">كلمات مشبوهة</span>
                            <span style="color: #f87171;">كثيرة مشبوهة</span>
                        </div>
                        <div class="comparison-row">
                            <span><i class="fas fa-shield-halved"></i> المخاطرة</span>
                            <span style="color: #34d399;">0% - 25%</span>
                            <span style="color: #fb923c;">25% - 55%</span>
                            <span style="color: #f87171;">55% - 100%</span>
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- شرح آلية العمل -->
                <!-- ============================================ -->
                <div class="panel full-width how-it-works">
                    <div class="panel-header">
                        <i class="fas fa-cogs"></i>
                        <h2>كيف يعمل النظام؟</h2>
                        <span class="badge badge-gold">آلية العمل</span>
                    </div>
                    
                    <div class="workflow-steps">
                        <div class="step-card">
                            <div class="step-number">1</div>
                            <div class="step-icon"><i class="fas fa-upload"></i></div>
                            <div class="step-content">
                                <h3>إدخال البيانات</h3>
                                <p>اختر نوع التحليل المناسب:</p>
                                <ul>
                                    <li><i class="fas fa-file-lines"></i> <strong>تحليل النصوص:</strong> إدخال بيانات المعاملة يدوياً</li>
                                    <li><i class="fas fa-file-pdf"></i> <strong>تحليل PDF:</strong> رفع ملفات المعاملات</li>
                                    <li><i class="fas fa-image"></i> <strong>تحليل الصور:</strong> رفع صور المعاملات</li>
                                    <li><i class="fas fa-money-bill-wave"></i> <strong>كشف العملات:</strong> رفع صور العملات للفحص</li>
                                </ul>
                            </div>
                        </div>
                        <div class="step-card">
                            <div class="step-number">2</div>
                            <div class="step-icon"><i class="fas fa-brain"></i></div>
                            <div class="step-content">
                                <h3>تحليل الذكاء الاصطناعي</h3>
                                <p>يقوم النظام بتحليل البيانات باستخدام:</p>
                                <ul>
                                    <li><i class="fas fa-tree"></i> <strong>Random Forest:</strong> نموذج تعلم آلي للتنبؤ</li>
                                    <li><i class="fas fa-file-lines"></i> <strong>TF-IDF:</strong> تحليل النصوص</li>
                                    <li><i class="fas fa-image"></i> <strong>OpenCV:</strong> معالجة وتحليل الصور</li>
                                    <li><i class="fas fa-chart-bar"></i> <strong>SIFT/ORB:</strong> استخراج ميزات الصور</li>
                                </ul>
                            </div>
                        </div>
                        <div class="step-card">
                            <div class="step-number">3</div>
                            <div class="step-icon"><i class="fas fa-shield-halved"></i></div>
                            <div class="step-content">
                                <h3>تقييم المخاطر</h3>
                                <p>يقوم النظام بتقييم المخاطر بناءً على:</p>
                                <ul>
                                    <li><i class="fas fa-coins"></i> <strong>المبلغ:</strong> تحليل حجم المبلغ</li>
                                    <li><i class="fas fa-clock"></i> <strong>الزمن:</strong> تحليل توقيت المعاملة</li>
                                    <li><i class="fas fa-globe"></i> <strong>الدولة:</strong> تحليل مخاطر الدولة</li>
                                    <li><i class="fas fa-comment"></i> <strong>النص:</strong> تحليل الملاحظات</li>
                                </ul>
                            </div>
                        </div>
                        <div class="step-card">
                            <div class="step-number">4</div>
                            <div class="step-icon"><i class="fas fa-chart-simple"></i></div>
                            <div class="step-content">
                                <h3>عرض النتائج</h3>
                                <p>يتم عرض النتائج بشكل مفصل:</p>
                                <ul>
                                    <li><i class="fas fa-check-circle"></i> <strong>القرار:</strong> سليمة / مشبوهة / احتيال</li>
                                    <li><i class="fas fa-star"></i> <strong>تقييم المخاطر:</strong> نظام 5 نجوم</li>
                                    <li><i class="fas fa-list"></i> <strong>عوامل الخطر:</strong> عرض تفصيلي</li>
                                    <li><i class="fas fa-lightbulb"></i> <strong>توصيات:</strong> إرشادات عملية</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="workflow-diagram">
                        <div class="flow-item">
                            <div class="flow-icon"><i class="fas fa-upload"></i></div>
                            <span>إدخال البيانات</span>
                        </div>
                        <div class="flow-arrow"><i class="fas fa-arrow-left"></i></div>
                        <div class="flow-item">
                            <div class="flow-icon"><i class="fas fa-brain"></i></div>
                            <span>تحليل AI</span>
                        </div>
                        <div class="flow-arrow"><i class="fas fa-arrow-left"></i></div>
                        <div class="flow-item">
                            <div class="flow-icon"><i class="fas fa-shield-halved"></i></div>
                            <span>تقييم المخاطر</span>
                        </div>
                        <div class="flow-arrow"><i class="fas fa-arrow-left"></i></div>
                        <div class="flow-item">
                            <div class="flow-icon"><i class="fas fa-chart-simple"></i></div>
                            <span>عرض النتائج</span>
                        </div>
                    </div>

                    <div class="tech-stack">
                        <span class="tech-tag"><i class="fas fa-code"></i> PHP</span>
                        <span class="tech-tag"><i class="fab fa-python"></i> Python</span>
                        <span class="tech-tag"><i class="fas fa-tree"></i> Random Forest</span>
                        <span class="tech-tag"><i class="fas fa-file-lines"></i> TF-IDF</span>
                        <span class="tech-tag"><i class="fas fa-image"></i> OpenCV</span>
                        <span class="tech-tag"><i class="fas fa-robot"></i> SIFT/ORB</span>
                        <span class="tech-tag"><i class="fas fa-database"></i> scikit-learn</span>
                        <span class="tech-tag"><i class="fas fa-server"></i> Flask API</span>
                    </div>
                </div>

                <!-- اختيار سريع -->
                <div class="panel full-width quick-panel">
                    <div class="panel-header">
                        <i class="fas fa-bolt"></i>
                        <h2>ابدأ التحليل الذكي</h2>
                        <span class="badge">اختر نوع التحليل</span>
                    </div>
                    <div class="quick-buttons">
                        <button class="quick-btn" onclick="switchTab('text')">
                            <div class="quick-icon"><i class="fas fa-file-lines"></i></div>
                            <span>تحليل نصوص</span>
                            <small>إدخال بيانات المعاملة</small>
                            <span class="quick-badge">AI</span>
                        </button>
                        <button class="quick-btn" onclick="switchTab('pdf')">
                            <div class="quick-icon"><i class="fas fa-file-pdf"></i></div>
                            <span>تحليل PDF</span>
                            <small>رفع ملفات PDF</small>
                            <span class="quick-badge">AI</span>
                        </button>
                        <button class="quick-btn" onclick="switchTab('images')">
                            <div class="quick-icon"><i class="fas fa-image"></i></div>
                            <span>تحليل صور</span>
                            <small>رفع صور المعاملات</small>
                            <span class="quick-badge">AI</span>
                        </button>
                        <button class="quick-btn" onclick="switchTab('currency')">
                            <div class="quick-icon"><i class="fas fa-money-bill-wave"></i></div>
                            <span>كشف العملات</span>
                            <small>مقارنة بالصور المرجعية</small>
                            <span class="quick-badge">AI Pro</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- TAB 2: تحليل النصوص -->
        <!-- ============================================ -->
        <div class="tab-content" id="text">
            <div class="analysis-grid">
                <div class="panel input-panel">
                    <div class="panel-header">
                        <i class="fas fa-file-lines"></i>
                        <h2>تحليل المعاملة النصية</h2>
                        <span class="badge badge-gold">AI Powered</span>
                    </div>
                    <form id="textForm" method="POST" action="api/process.php">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> رقم الحساب (مرسل)</label>
                            <input type="text" name="sender" value="SA-1024-8872" required>
                            <span class="input-hint">يجب أن يبدأ بـ SA متبوعاً بـ 8 أرقام</span>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> رقم الحساب (مستقبل)</label>
                            <input type="text" name="receiver" value="SA-3390-1123" required>
                            <span class="input-hint">يجب أن يبدأ بـ SA متبوعاً بـ 8 أرقام</span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-coins"></i> المبلغ</label>
                                <input type="number" name="amount" value="2750" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-money-bill-wave"></i> العملة</label>
                                <select name="currency">
                                    <option value="SAR" selected>🇸🇦 ريال سعودي</option>
                                    <option value="USD">🇺🇸 دولار أمريكي</option>
                                    <option value="EUR">🇪🇺 يورو</option>
                                    <option value="GBP">🇬🇧 جنيه إسترليني</option>
                                    <option value="AED">🇦🇪 درهم إماراتي</option>
                                    <option value="EGP">🇪🇬 جنيه مصري</option>
                                    <option value="TRY">🇹🇷 ليرة تركية</option>
                                    <option value="KWD">🇰🇼 دينار كويتي</option>
                                    <option value="BHD">🇧🇭 دينار بحريني</option>
                                    <option value="OMR">🇴🇲 ريال عماني</option>
                                    <option value="QAR">🇶🇦 ريال قطري</option>
                                    <option value="JOD">🇯🇴 دينار أردني</option>
                                    <option value="YER">🇾🇪 ريال يمني</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-globe"></i> الدولة</label>
                                <select name="country">
                                    <option value="SA" selected>🇸🇦 السعودية</option>
                                    <option value="AE">🇦🇪 الإمارات</option>
                                    <option value="EG">🇪🇬 مصر</option>
                                    <option value="US">🇺🇸 الولايات المتحدة</option>
                                    <option value="UK">🇬🇧 بريطانيا</option>
                                    <option value="TR">🇹🇷 تركيا</option>
                                    <option value="YE">🇾🇪 اليمن</option>
                                    <option value="KW">🇰🇼 الكويت</option>
                                    <option value="BH">🇧🇭 البحرين</option>
                                    <option value="OM">🇴🇲 عمان</option>
                                    <option value="QA">🇶🇦 قطر</option>
                                    <option value="JO">🇯🇴 الأردن</option>
                                    <option value="EU">🇪🇺 أوروبا</option>
                                    <option value="other">🌍 أخرى</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-tag"></i> نوع المعاملة</label>
                                <select name="transaction_type">
                                    <option value="تحويل بنكي" selected>تحويل بنكي</option>
                                    <option value="دفع إلكتروني">دفع إلكتروني</option>
                                    <option value="سحب نقدي">سحب نقدي</option>
                                    <option value="إيداع">إيداع</option>
                                    <option value="تحويل دولي">تحويل دولي</option>
                                    <option value="شراء">شراء</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-clock"></i> الوقت (دقائق)</label>
                                <input type="number" name="time_since" value="120" required>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-arrow-trend-up"></i> تكرار العمليات</label>
                                <input type="number" name="frequency" value="5" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-comment"></i> ملاحظات المعاملة</label>
                            <textarea name="notes" rows="3" placeholder="أدخل تفاصيل المعاملة...">تحويل روتيني بين حساباتي الشخصية</textarea>
                        </div>
                        <button type="submit" class="btn-analyze">
                            <i class="fas fa-microchip"></i>
                            <span>تحليل النص</span>
                            <div class="spinner" style="display:none;"></div>
                            <span class="btn-ai-badge">AI</span>
                        </button>
                    </form>
                </div>
                <div class="panel result-panel">
                    <div class="panel-header">
                        <i class="fas fa-chart-simple"></i>
                        <h2>نتيجة التحليل</h2>
                        <span class="badge" style="background: rgba(52, 211, 153, 0.15); color: #34d399;">
                            <i class="fas fa-circle" style="font-size: 0.4rem;"></i> AI
                        </span>
                    </div>
                    <div class="result-container" id="textResult">
                        <div class="result-placeholder">
                            <div class="placeholder-icon">
                                <i class="fas fa-file-lines"></i>
                                <div class="placeholder-pulse"></div>
                            </div>
                            <p>أدخل بيانات المعاملة واضغط "تحليل النص"</p>
                            <span class="placeholder-hint">سيتم التحقق من صحة المعاملة وتحليل المخاطر</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- TAB 3: تحليل PDF -->
        <!-- ============================================ -->
        <div class="tab-content" id="pdf">
            <div class="analysis-grid">
                <div class="panel input-panel">
                    <div class="panel-header">
                        <i class="fas fa-file-pdf"></i>
                        <h2>تحليل ملفات PDF</h2>
                        <span class="badge badge-gold">رفع ملفات</span>
                    </div>
                    <form id="pdfForm" method="POST" action="api/process_files.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label><i class="fas fa-cloud-upload-alt"></i> رفع ملف PDF</label>
                            <div class="file-upload-area" id="pdfUploadArea">
                                <div class="upload-icon">
                                    <i class="fas fa-file-pdf"></i>
                                    <div class="upload-pulse"></div>
                                </div>
                                <p>اسحب ملف PDF هنا أو اضغط للاختيار</p>
                                <p class="upload-hint">يدعم: ملفات PDF (حجم أقصى 10MB)</p>
                                <input type="file" name="attachments[]" id="pdfInput" accept=".pdf" style="display: none;">
                                <div class="file-list" id="pdfList"></div>
                            </div>
                            <div class="ai-progress" id="pdfProgress" style="display:none;">
                                <div class="ai-progress-bar">
                                    <div class="ai-progress-fill" style="width: 0%;"></div>
                                </div>
                                <span class="ai-progress-text">جاري تحليل PDF...</span>
                            </div>
                        </div>
                        <button type="submit" class="btn-analyze">
                            <i class="fas fa-file-pdf"></i>
                            <span>تحليل PDF</span>
                            <div class="spinner" style="display:none;"></div>
                            <span class="btn-ai-badge">AI</span>
                        </button>
                    </form>
                </div>
                <div class="panel result-panel">
                    <div class="panel-header">
                        <i class="fas fa-chart-simple"></i>
                        <h2>نتيجة تحليل PDF</h2>
                        <span class="badge" style="background: rgba(52, 211, 153, 0.15); color: #34d399;">
                            <i class="fas fa-circle" style="font-size: 0.4rem;"></i> AI
                        </span>
                    </div>
                    <div class="result-container" id="pdfResult">
                        <div class="result-placeholder">
                            <div class="placeholder-icon">
                                <i class="fas fa-file-pdf"></i>
                                <div class="placeholder-pulse"></div>
                            </div>
                            <p>ارفع ملف PDF واضغط "تحليل PDF"</p>
                            <span class="placeholder-hint">سيتم استخراج النصوص وتحليلها</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- TAB 4: تحليل الصور -->
        <!-- ============================================ -->
        <div class="tab-content" id="images">
            <div class="analysis-grid">
                <div class="panel input-panel">
                    <div class="panel-header">
                        <i class="fas fa-image"></i>
                        <h2>تحليل صور المعاملات</h2>
                        <span class="badge badge-gold">رفع صور</span>
                    </div>
                    <form id="imagesForm" method="POST" action="api/process_images.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label><i class="fas fa-cloud-upload-alt"></i> رفع صور المعاملة</label>
                            <div class="file-upload-area" id="imageUploadArea">
                                <div class="upload-icon">
                                    <i class="fas fa-image"></i>
                                    <div class="upload-pulse"></div>
                                </div>
                                <p>اسحب صور المعاملات هنا أو اضغط للاختيار</p>
                                <p class="upload-hint">يدعم: JPG, PNG, GIF, BMP, TIFF (حجم أقصى 10MB)</p>
                                <input type="file" name="images[]" id="imageInput" multiple accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff" style="display: none;">
                                <div class="file-list" id="imageList"></div>
                            </div>
                            <div class="ai-progress" id="imageProgress" style="display:none;">
                                <div class="ai-progress-bar">
                                    <div class="ai-progress-fill" style="width: 0%;"></div>
                                </div>
                                <span class="ai-progress-text">جاري تحليل الصور...</span>
                            </div>
                        </div>
                        <button type="submit" class="btn-analyze">
                            <i class="fas fa-image"></i>
                            <span>تحليل الصور</span>
                            <div class="spinner" style="display:none;"></div>
                            <span class="btn-ai-badge">AI</span>
                        </button>
                    </form>
                </div>
                <div class="panel result-panel">
                    <div class="panel-header">
                        <i class="fas fa-chart-simple"></i>
                        <h2>نتيجة تحليل الصور</h2>
                        <span class="badge" style="background: rgba(52, 211, 153, 0.15); color: #34d399;">
                            <i class="fas fa-circle" style="font-size: 0.4rem;"></i> AI
                        </span>
                    </div>
                    <div class="result-container" id="imagesResult">
                        <div class="result-placeholder">
                            <div class="placeholder-icon">
                                <i class="fas fa-image"></i>
                                <div class="placeholder-pulse"></div>
                            </div>
                            <p>ارفع صور المعاملات واضغط "تحليل الصور"</p>
                            <span class="placeholder-hint">سيتم تحليل الصور بالذكاء الاصطناعي</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- TAB 5: كشف العملات -->
        <!-- ============================================ -->
        <div class="tab-content" id="currency">
            <div class="analysis-grid">
                <div class="panel input-panel">
                    <div class="panel-header">
                        <i class="fas fa-money-bill-wave"></i>
                        <h2>كشف العملات اليمنية المزورة</h2>
                        <span class="badge" style="background: rgba(52, 211, 153, 0.2); color: #34d399;">AI Pro</span>
                    </div>
                    <form id="currencyForm" method="POST" action="api/process_currency.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label><i class="fas fa-coins"></i> نوع العملة اليمنية</label>
                            <select name="currency_type">
                                <option value="YER_500">🇾🇪 500 ريال يمني</option>
                                <option value="YER_1000" selected>🇾🇪 1000 ريال يمني</option>
                                <option value="YER_5000">🇾🇪 5000 ريال يمني</option>
                                <option value="YER_10000">🇾🇪 10000 ريال يمني</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-cloud-upload-alt"></i> رفع صور العملات للفحص</label>
                            <div class="file-upload-area" id="currencyUploadArea">
                                <div class="upload-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <div class="upload-pulse"></div>
                                </div>
                                <p>اسحب صور العملات اليمنية هنا أو اضغط للاختيار</p>
                                <p class="upload-hint">يدعم: JPG, PNG, GIF, BMP, TIFF (حجم أقصى 10MB)</p>
                                <input type="file" name="currency_images[]" id="currencyInput" multiple accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff" style="display: none;">
                                <div class="file-list" id="currencyList"></div>
                            </div>
                            <div class="ai-progress" id="currencyProgress" style="display:none;">
                                <div class="ai-progress-bar">
                                    <div class="ai-progress-fill" style="width: 0%;"></div>
                                </div>
                                <span class="ai-progress-text">جاري تحليل العملات...</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-camera"></i> التقاط صورة من الكاميرا</label>
                            <div class="camera-section">
                                <video id="video" autoplay playsinline style="width: 100%; max-height: 250px; background: #000; border-radius: 8px; display: none;"></video>
                                <canvas id="canvas" style="display: none;"></canvas>
                                <div class="camera-controls">
                                    <button type="button" class="btn-camera" id="startCamera">
                                        <i class="fas fa-video"></i> تشغيل الكاميرا
                                    </button>
                                    <button type="button" class="btn-camera" id="capturePhoto" style="display:none;">
                                        <i class="fas fa-camera"></i> التقاط صورة
                                    </button>
                                    <button type="button" class="btn-camera" id="stopCamera" style="display:none;">
                                        <i class="fas fa-stop"></i> إيقاف
                                    </button>
                                </div>
                                <div id="capturedImagePreview" style="margin-top: 8px; display: none;">
                                    <img id="capturedImg" style="max-width: 200px; border-radius: 8px; border: 2px solid #34d399;">
                                    <p style="color: #34d399; font-size: 0.8rem; margin-top: 4px;">✅ تم التقاط الصورة</p>
                                </div>
                            </div>
                            <small style="color: var(--text-muted); font-size: 0.7rem;">التقط صورة للعملة اليمنية المشتبه بها للمقارنة</small>
                        </div>

                        <button type="submit" class="btn-analyze green">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>كشف العملات</span>
                            <div class="spinner" style="display:none;"></div>
                            <span class="btn-ai-badge">AI Pro</span>
                        </button>
                    </form>
                </div>
                <div class="panel result-panel">
                    <div class="panel-header">
                        <i class="fas fa-chart-simple"></i>
                        <h2>نتيجة كشف العملات</h2>
                        <span class="badge" style="background: rgba(52, 211, 153, 0.15); color: #34d399;">
                            <i class="fas fa-circle" style="font-size: 0.4rem;"></i> AI
                        </span>
                    </div>
                    <div class="result-container" id="currencyResult">
                        <div class="result-placeholder">
                            <div class="placeholder-icon">
                                <i class="fas fa-money-bill-wave"></i>
                                <div class="placeholder-pulse"></div>
                            </div>
                            <p>ارفع صور العملات أو التقط من الكاميرا واضغط "كشف العملات"</p>
                            <span class="placeholder-hint">سيتم مقارنة الصور بقاعدة البيانات المرجعية</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- سجل المعاملات -->
        <!-- ============================================ -->
        <div class="history-section">
            <div class="panel-header">
                <i class="fas fa-clock-rotate-left"></i>
                <h3>سجل المعاملات</h3>
                <span class="badge" style="background: rgba(96, 165, 250, 0.15); color: #60a5fa;">
                    <i class="fas fa-database"></i> 50 معاملة
                </span>
                <button class="btn-clear" id="clearHistory" title="مسح السجل">
                    <i class="fas fa-trash-can"></i>
                </button>
            </div>
            <div class="history-log" id="historyLog">
                <div class="empty-log">
                    <i class="fas fa-inbox"></i>
                    <p>لا توجد معاملات محللة بعد</p>
                    <span>قم بتحليل أول معاملة باستخدام الذكاء الاصطناعي</span>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="footer-content">
                <div>
                    <p><i class="fas fa-brain"></i> نظام مدعوم بالذكاء الاصطناعي المتقدم</p>
                    <p class="footer-tech">تقنيات: Random Forest · OpenCV · SIFT · NLP</p>
                </div>
                <div class="footer-researchers">
                    <p>الباحثون: أحمد الصايدي وإبراهيم المجهصي</p>
                    <p class="footer-version">الإصدار 5.0 · AI Pro</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>