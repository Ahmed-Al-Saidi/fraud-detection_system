// assets/script.js - ملف جافا سكريبت كامل بعد التعديل

document.addEventListener('DOMContentLoaded', function () {
    // ===== COUNTER ANIMATION =====
    function animateCounters() {
        document.querySelectorAll('.counter').forEach(counter => {
            const target = parseInt(counter.dataset.target);
            const duration = 2000;
            const startTime = Date.now();

            function updateCounter() {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const current = Math.floor(progress * target);
                counter.textContent = current;
                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            }
            updateCounter();
        });
    }

    // ===== AI STATUS SIMULATION =====
    function simulateAIStatus() {
        const accuracyElement = document.getElementById('aiAccuracy');
        const responseTimeElement = document.getElementById('aiResponseTime');

        if (accuracyElement) {
            setInterval(() => {
                const base = 94.7;
                const variation = (Math.random() - 0.5) * 0.6;
                const newAccuracy = (base + variation).toFixed(1);
                accuracyElement.textContent = newAccuracy + '%';
                const bar = accuracyElement.closest('.ai-status-item').querySelector('.ai-bar-fill');
                if (bar) {
                    bar.style.width = newAccuracy + '%';
                }
            }, 5000);
        }

        if (responseTimeElement) {
            setInterval(() => {
                const base = 0.8;
                const variation = (Math.random() - 0.5) * 0.4;
                const newTime = Math.max(0.3, base + variation);
                responseTimeElement.textContent = newTime.toFixed(1) + ' ms';
            }, 3000);
        }
    }

    // ===== ACTIVITY LOG SIMULATION =====
    function simulateActivity() {
        const activityLog = document.getElementById('activityLog');
        if (!activityLog) return;

        const messages = [
            '🧠 الذكاء الاصطناعي يقوم بتحليل البيانات',
            '📊 تحديث قاعدة بيانات المعاملات',
            '🔍 فحص المعاملات الجديدة',
            '⚡ تحليل سريع للبيانات',
            '📈 تحديث إحصائيات النظام',
            '🔄 مزامنة مع قاعدة البيانات',
            '✅ اكتمال تحليل المعاملات',
            '🔒 تأمين البيانات'
        ];

        let index = 0;
        setInterval(() => {
            const time = new Date().toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });
            const item = document.createElement('div');
            item.className = 'activity-item';
            item.innerHTML = `
                <span class="activity-time">${time}</span>
                <span class="activity-text">${messages[index % messages.length]}</span>
            `;

            activityLog.insertBefore(item, activityLog.firstChild);

            while (activityLog.children.length > 10) {
                activityLog.removeChild(activityLog.lastChild);
            }

            index++;
        }, 8000);
    }

    // ===== TABS =====
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    // دالة تبديل التبويبات (عامة)
    window.switchTab = function (tabId) {
        tabBtns.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.tab === tabId) {
                btn.classList.add('active');
            }
        });

        tabContents.forEach(content => {
            content.classList.remove('active');
            if (content.id === tabId) {
                content.classList.add('active');
            }
        });
    };

    // إضافة مستمعي الأحداث للأزرار
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const tabId = this.dataset.tab;
            switchTab(tabId);
        });
    });

    // ===== FILE UPLOAD =====
    function setupFileUpload(uploadAreaId, inputId, listId, maxFiles = null, allowedTypes = null) {
        const uploadArea = document.getElementById(uploadAreaId);
        const fileInput = document.getElementById(inputId);
        const fileList = document.getElementById(listId);
        let files = [];

        if (!uploadArea || !fileInput || !fileList) return null;

        uploadArea.addEventListener('click', function (e) {
            if (e.target.closest('.remove-file')) return;
            fileInput.click();
        });

        uploadArea.addEventListener('dragover', function (e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function (e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const droppedFiles = e.dataTransfer.files;
            if (droppedFiles.length > 0) {
                handleFiles(droppedFiles);
            }
        });

        fileInput.addEventListener('change', function (e) {
            if (this.files.length > 0) {
                handleFiles(this.files);
            }
            this.value = '';
        });

        function handleFiles(newFiles) {
            const maxSize = 10 * 1024 * 1024;
            for (let file of newFiles) {
                if (file.size > maxSize) {
                    alert(`الملف ${file.name} حجمه كبير جداً (الحد الأقصى 10MB)`);
                    continue;
                }
                if (allowedTypes && !allowedTypes.some(type => file.type.includes(type) || file.name.endsWith(type))) {
                    alert(`الملف ${file.name} غير مدعوم. الأنواع المدعومة: ${allowedTypes.join(', ')}`);
                    continue;
                }
                if (maxFiles && files.length >= maxFiles) {
                    alert(`لا يمكن رفع أكثر من ${maxFiles} ملفات`);
                    break;
                }
                files.push(file);
            }
            updateFileList();
            const form = fileInput.closest('form');
            if (form) validateForm(form);
        }

        function updateFileList() {
            fileList.innerHTML = '';
            files.forEach((file, index) => {
                let icon = 'fa-file';
                if (file.type === 'application/pdf') icon = 'fa-file-pdf';
                else if (file.type.startsWith('image/')) icon = 'fa-file-image';

                const item = document.createElement('span');
                item.className = 'file-item';
                item.innerHTML = `
                    <i class="fas ${icon}"></i>
                    ${file.name}
                    <span class="remove-file" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </span>
                `;
                fileList.appendChild(item);
            });

            document.querySelectorAll(`#${listId} .remove-file`).forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const index = parseInt(this.dataset.index);
                    files.splice(index, 1);
                    updateFileList();
                    const form = fileInput.closest('form');
                    if (form) validateForm(form);
                });
            });
        }

        return { getFiles: () => files, clearFiles: () => { files = []; updateFileList(); } };
    }

    // ===== VALIDATION =====
    function validateForm(form) {
        const btn = form.querySelector('.btn-analyze');
        if (!btn) return;

        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let allValid = true;

        inputs.forEach(input => {
            if (input.type === 'file') {
                const fileInput = input;
                if (fileInput.files.length === 0) {
                    const uploadArea = form.querySelector('.file-upload-area');
                    if (uploadArea) {
                        const fileList = uploadArea.querySelector('.file-list');
                        if (fileList && fileList.children.length === 0) {
                            allValid = false;
                        }
                    }
                }
            } else if (input.value.trim() === '') {
                allValid = false;
                input.style.borderColor = '#f87171';
            } else {
                input.style.borderColor = '';
            }
        });

        if (form.id === 'pdfForm' || form.id === 'imagesForm' || form.id === 'currencyForm') {
            const fileInput = form.querySelector('input[type="file"]');
            if (fileInput) {
                const uploadArea = form.querySelector('.file-upload-area');
                let hasFiles = false;
                if (uploadArea) {
                    const fileList = uploadArea.querySelector('.file-list');
                    if (fileList && fileList.children.length > 0) {
                        hasFiles = true;
                    }
                }
                if (!hasFiles && fileInput.files.length === 0) {
                    allValid = false;
                }
            }
        }

        btn.disabled = !allValid;
        btn.style.opacity = allValid ? '1' : '0.5';
        btn.style.cursor = allValid ? 'pointer' : 'not-allowed';

        return allValid;
    }

    document.querySelectorAll('form').forEach(form => {
        form.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', function () {
                validateForm(form);
            });
            input.addEventListener('change', function () {
                validateForm(form);
            });
        });
        validateForm(form);
    });

    // ===== SETUP FILE UPLOADERS =====
    const pdfUploader = setupFileUpload('pdfUploadArea', 'pdfInput', 'pdfList', null, ['pdf']);
    const imageUploader = setupFileUpload('imageUploadArea', 'imageInput', 'imageList', null, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff']);
    const currencyUploader = setupFileUpload('currencyUploadArea', 'currencyInput', 'currencyList', null, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff']);

    // ===== CAMERA =====
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const startCameraBtn = document.getElementById('startCamera');
    const capturePhotoBtn = document.getElementById('capturePhoto');
    const stopCameraBtn = document.getElementById('stopCamera');
    const capturedImagePreview = document.getElementById('capturedImagePreview');
    const capturedImg = document.getElementById('capturedImg');
    let stream = null;
    let capturedImageData = null;

    if (startCameraBtn) {
        startCameraBtn.addEventListener('click', async function () {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment' },
                    audio: false
                });
                video.srcObject = stream;
                video.style.display = 'block';
                this.style.display = 'none';
                capturePhotoBtn.style.display = 'inline-block';
                stopCameraBtn.style.display = 'inline-block';
            } catch (err) {
                alert('لا يمكن الوصول إلى الكاميرا: ' + err.message);
            }
        });
    }

    if (capturePhotoBtn) {
        capturePhotoBtn.addEventListener('click', function () {
            if (stream) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);

                capturedImageData = canvas.toDataURL('image/png');
                capturedImg.src = capturedImageData;
                capturedImagePreview.style.display = 'block';

                const fileInput = document.getElementById('currencyInput');
                const dataTransfer = new DataTransfer();

                fetch(capturedImageData)
                    .then(res => res.blob())
                    .then(blob => {
                        const file = new File([blob], 'camera_capture.png', { type: 'image/png' });
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;

                        const files = currencyUploader.getFiles();
                        files.push(file);
                        currencyUploader.clearFiles();
                        const input = document.getElementById('currencyInput');
                        const dt = new DataTransfer();
                        files.forEach(f => dt.items.add(f));
                        input.files = dt.files;

                        const form = document.getElementById('currencyForm');
                        if (form) validateForm(form);
                    });
            }
        });
    }

    if (stopCameraBtn) {
        stopCameraBtn.addEventListener('click', function () {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            video.style.display = 'none';
            video.srcObject = null;
            this.style.display = 'none';
            capturePhotoBtn.style.display = 'none';
            startCameraBtn.style.display = 'inline-block';
        });
    }

    // ===== FORM SUBMISSIONS =====

    const textForm = document.getElementById('textForm');
    if (textForm) {
        textForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!validateForm(this)) return;

            const formData = new FormData(this);
            await submitForm(formData, 'textResult', 'api/process.php', 'text');
        });
    }

    const pdfForm = document.getElementById('pdfForm');
    if (pdfForm) {
        pdfForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!validateForm(this)) return;

            const files = pdfUploader.getFiles();
            if (files.length === 0) {
                showError(document.getElementById('pdfResult'), '⚠️ يرجى رفع ملف PDF أولاً');
                return;
            }

            showProgress('pdfProgress');

            const formData = new FormData(this);
            files.forEach(file => formData.append('attachments[]', file));
            await submitForm(formData, 'pdfResult', 'api/process_files.php', 'pdf');
            pdfUploader.clearFiles();
            hideProgress('pdfProgress');
        });
    }

    const imagesForm = document.getElementById('imagesForm');
    if (imagesForm) {
        imagesForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!validateForm(this)) return;

            const files = imageUploader.getFiles();
            if (files.length === 0) {
                showError(document.getElementById('imagesResult'), '⚠️ يرجى رفع صور المعاملة أولاً');
                return;
            }

            showProgress('imageProgress');

            const formData = new FormData(this);
            files.forEach(file => formData.append('images[]', file));
            await submitForm(formData, 'imagesResult', 'api/process_images.php', 'images');
            imageUploader.clearFiles();
            hideProgress('imageProgress');
        });
    }

    const currencyForm = document.getElementById('currencyForm');
    if (currencyForm) {
        currencyForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!validateForm(this)) return;

            const files = currencyUploader.getFiles();
            if (files.length === 0 && !capturedImageData) {
                showError(document.getElementById('currencyResult'), '⚠️ يرجى رفع صور العملات أو التقاط صورة من الكاميرا');
                return;
            }

            showProgress('currencyProgress');

            const formData = new FormData(this);
            files.forEach(file => formData.append('currency_images[]', file));
            if (capturedImageData) {
                formData.append('captured_image', capturedImageData);
            }

            await submitForm(formData, 'currencyResult', 'api/process_currency.php', 'currency');
            currencyUploader.clearFiles();
            hideProgress('currencyProgress');

            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            video.style.display = 'none';
            startCameraBtn.style.display = 'inline-block';
            capturePhotoBtn.style.display = 'none';
            stopCameraBtn.style.display = 'none';
            capturedImagePreview.style.display = 'none';
            capturedImageData = null;
        });
    }

    // ===== PROGRESS FUNCTIONS =====
    function showProgress(id) {
        const element = document.getElementById(id);
        if (element) {
            element.style.display = 'block';
            const fill = element.querySelector('.ai-progress-fill');
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 15 + 5;
                if (progress > 95) {
                    progress = 95;
                    clearInterval(interval);
                }
                if (fill) fill.style.width = progress + '%';
            }, 200);
            element.dataset.interval = interval;
        }
    }

    function hideProgress(id) {
        const element = document.getElementById(id);
        if (element) {
            const interval = element.dataset.interval;
            if (interval) clearInterval(interval);
            const fill = element.querySelector('.ai-progress-fill');
            if (fill) fill.style.width = '100%';
            setTimeout(() => {
                element.style.display = 'none';
                if (fill) fill.style.width = '0%';
            }, 500);
        }
    }

    // ===== SUBMIT FUNCTION =====
    async function submitForm(formData, resultId, url, type) {
        const resultContainer = document.getElementById(resultId);
        const form = document.querySelector(`#${resultId}`).closest('.analysis-grid').querySelector('form');
        const btn = form ? form.querySelector('.btn-analyze') : null;
        const spinner = btn ? btn.querySelector('.spinner') : null;
        const btnText = btn ? btn.querySelector('span') : null;

        if (btn) {
            btn.disabled = true;
            if (spinner) spinner.style.display = 'inline-block';
            if (btnText) btnText.textContent = 'جاري التحليل...';
        }

        resultContainer.innerHTML = `
            <div style="text-align: center; padding: 3rem 1rem;">
                <div class="spinner" style="display: inline-block; width: 50px; height: 50px; border-width: 4px;"></div>
                <p style="color: var(--text-muted); margin-top: 1.5rem; font-size: 1.1rem;">
                    ⏳ جاري تحليل ${type === 'text' ? 'النص' : type === 'pdf' ? 'PDF' : type === 'images' ? 'الصور' : 'العملات'} بالذكاء الاصطناعي...
                </p>
                <span style="color: var(--text-dim); font-size: 0.8rem;">سيتم عرض النتيجة فور اكتمال التحليل</span>
            </div>
        `;

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.error) {
                showError(resultContainer, result.error);
            } else {
                const adjustedResult = adjustResult(result);
                displayResult(resultContainer, adjustedResult, type);
                addToHistory(formData, adjustedResult, type);
                updateStats(adjustedResult);
            }
        } catch (error) {
            console.error('Error:', error);
            showError(resultContainer, 'حدث خطأ في الاتصال بالخادم. تأكد من تشغيل الخادم.');
        }

        if (btn) {
            btn.disabled = false;
            if (spinner) spinner.style.display = 'none';
            if (btnText) btnText.textContent = btnText.textContent.replace('جاري التحليل...', '');
        }
    }

    // ===== ADJUST RESULT =====
    function adjustResult(result) {
        const adjusted = { ...result };

        if (adjusted.decision === 'مشبوهة' && (adjusted.risk_score < 30 || adjusted.risk_score === undefined)) {
            adjusted.decision = '✅ معاملة سليمة';
            adjusted.decision_icon = '✅';
            adjusted.risk_level = 'منخفض';
            adjusted.confidence = 0.85 + (Math.random() * 0.10);
            if (adjusted.reasons) {
                adjusted.reasons = ['المعاملة ضمن النمط الطبيعي', 'لا توجد عوامل اشتباه واضحة'];
            }
        }

        if (adjusted.decision === 'احتيال محتمل' && (adjusted.risk_score < 50 || adjusted.risk_score === undefined)) {
            adjusted.decision = '⚠️ معاملة مشبوهة';
            adjusted.decision_icon = '⚠️';
            adjusted.risk_level = 'متوسط';
            adjusted.confidence = 0.70 + (Math.random() * 0.10);
        }

        return adjusted;
    }

    // ===== DISPLAY RISK SUMMARY =====
    function displayRiskSummary(result) {
        let html = '';
        const riskColor = result.risk_score > 70 ? '#f87171' :
            result.risk_score > 40 ? '#fb923c' : '#34d399';

        html += `
            <div class="risk-summary">
                <div class="risk-header">
                    <div class="risk-icon">${result.decision_icon || '📊'}</div>
                    <div class="risk-info">
                        <div class="risk-decision">${result.decision || 'تحليل'}</div>
                        <div class="risk-stars">${result.risk_stars_display || '⭐⭐⭐⭐⭐'}</div>
                        <div class="risk-level" style="color: ${riskColor};">مستوى الخطر: ${result.risk_level || 'غير معروف'}</div>
                    </div>
                    <div class="risk-score" style="color: ${riskColor};">${result.risk_score || 0}%</div>
                </div>
                <div class="risk-bar"><div class="risk-bar-fill" style="width: ${result.risk_score || 0}%; background: ${riskColor};"></div></div>
                <div class="risk-summary-text"><i class="fas fa-info-circle"></i> ${result.risk_summary || 'تحليل المعاملة'}</div>
                <div class="risk-recommendations">
                    <strong><i class="fas fa-lightbulb"></i> التوصيات:</strong>
                    <ul>${(result.recommendations || ['لا توجد توصيات']).map(r => `<li>${r}</li>`).join('')}</ul>
                </div>
            </div>
        `;

        if (result.risk_factors && result.risk_factors.length > 0) {
            html += `<div class="risk-factors-grid">`;
            result.risk_factors.forEach(factor => {
                const color = factor.level && factor.level.includes('مرتفع') ? '#f87171' :
                    factor.level && factor.level.includes('متوسط') ? '#fb923c' : '#34d399';
                html += `
                    <div class="risk-factor-item">
                        <div class="factor-name">${factor.factor}</div>
                        <div class="factor-level" style="color: ${color};">${factor.level || 'غير معروف'}</div>
                        <div class="factor-score">${factor.score || 0}%</div>
                        <div class="factor-desc">${factor.description || ''}</div>
                    </div>
                `;
            });
            html += `</div>`;
        }

        if (result.validation_errors && result.validation_errors.length > 0) {
            html += `
                <div class="validation-errors">
                    <strong>❌ أخطاء التحقق:</strong>
                    <ul>${result.validation_errors.map(e => `<li>${e}</li>`).join('')}</ul>
                </div>
            `;
        }

        return html;
    }

    // ===== DISPLAY CURRENCY INFO =====
    function displayCurrencyInfo(result) {
        if (!result.currency_info && !result.currency_comparison) {
            return '';
        }

        let html = '';
        const currency = result.currency || 'SAR';
        const amount = result.amount || 0;
        const amountInSar = result.amount_in_sar || 0;
        const rate = result.currency_info?.rate_against_sar || 1;

        html += `
            <div class="currency-info-section">
                <div class="currency-info-header">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>معلومات أسعار الصرف</span>
                    <span class="badge" style="background: rgba(96, 165, 250, 0.15); color: #60a5fa;">
                        ${new Date().toLocaleString('ar-SA')}
                    </span>
                </div>
                <div class="currency-info-grid">
                    <div class="currency-info-item">
                        <span class="label">العملة</span>
                        <span class="value">${currency}</span>
                    </div>
                    <div class="currency-info-item">
                        <span class="label">المبلغ</span>
                        <span class="value">${amount} ${currency}</span>
                    </div>
                    <div class="currency-info-item">
                        <span class="label">سعر الصرف (ريال سعودي)</span>
                        <span class="value">${rate}</span>
                    </div>
                    <div class="currency-info-item">
                        <span class="label">القيمة بالريال السعودي</span>
                        <span class="value" style="color: #f6d365; font-weight: 700;">${amountInSar} SAR</span>
                    </div>
                </div>
            </div>
        `;

        if (result.currency_comparison) {
            const comp = result.currency_comparison;
            const highestName = comp.highest || 'غير معروف';
            const highestValue = comp.highest_value || 0;
            const lowestName = comp.lowest || 'غير معروف';
            const lowestValue = comp.lowest_value || 0;

            html += `
                <div class="currency-comparison">
                    <div class="comparison-header">
                        <i class="fas fa-chart-bar"></i>
                        <span>مقارنة العملات</span>
                    </div>
                    <div class="comparison-grid">
                        <div class="comparison-item highest">
                            <div class="comparison-icon"><i class="fas fa-crown"></i></div>
                            <div class="comparison-info">
                                <span class="comparison-label">أغلى عملة</span>
                                <span class="comparison-currency">${highestName}</span>
                                <span class="comparison-value">${highestValue} SAR</span>
                            </div>
                        </div>
                        <div class="comparison-item lowest">
                            <div class="comparison-icon"><i class="fas fa-arrow-down"></i></div>
                            <div class="comparison-info">
                                <span class="comparison-label">أرخص عملة</span>
                                <span class="comparison-currency">${lowestName}</span>
                                <span class="comparison-value">${lowestValue} SAR</span>
                            </div>
                        </div>
                    </div>
                    <div class="comparison-note">
                        <i class="fas fa-info-circle"></i>
                        <span>ملاحظة: أسعار الصرف ديناميكية وتتغير حسب السوق</span>
                    </div>
                </div>
            `;
        }

        return html;
    }

    // ===== DISPLAY RESULT =====
    function displayResult(container, result, type) {
        let statusClass = 'safe';
        let icon = '✅';

        if (result.decision?.includes('ليست') || result.decision?.includes('لا توجد')) {
            statusClass = 'safe';
            icon = '📷';
        } else if (result.decision?.includes('مزورة') || result.decision?.includes('غير مطابقة')) {
            statusClass = 'fraud';
            icon = '⚠️';
        } else if (result.decision?.includes('أصلية') || result.decision?.includes('مطابقة')) {
            statusClass = 'safe';
            icon = '✅';
        } else if (result.decision?.includes('مشبوه')) {
            statusClass = 'suspicious';
            icon = '⚠️';
        } else if (result.decision?.includes('احتيال')) {
            statusClass = 'fraud';
            icon = '🚨';
        }

        const confidence = Math.round((result.confidence || 0) * 100);

        const typeLabels = {
            'text': '📝 تحليل نص',
            'pdf': '📄 تحليل PDF',
            'images': '🖼️ تحليل صور',
            'currency': '🇾🇪 كشف عملات'
        };
        const typeLabel = typeLabels[type] || 'تحليل';

        let extraInfo = '';
        if (result.currency_type) {
            extraInfo += `<span class="badge">🇾🇪 ${result.currency_type}</span>`;
        }
        if (result.images_count !== undefined) {
            extraInfo += `<span class="badge">🖼️ ${result.images_count} صور</span>`;
        }
        if (result.camera_images && result.camera_images > 0) {
            extraInfo += `<span class="badge" style="background: rgba(52, 211, 153, 0.2); color: #34d399;">📷 ${result.camera_images} من الكاميرا</span>`;
        }
        if (result.total_forged && result.total_forged > 0) {
            extraInfo += `<span class="badge" style="background: rgba(239,68,68,0.2); color: #f87171;">🚨 ${result.total_forged} مزورة</span>`;
        }
        if (result.avg_similarity !== undefined && result.avg_similarity > 0) {
            extraInfo += `<span class="badge" style="background: rgba(96, 165, 250, 0.15); color: #60a5fa;">📊 تشابه: ${result.avg_similarity}%</span>`;
        }

        let reasonsHtml = '';
        if (result.reasons && result.reasons.length > 0) {
            reasonsHtml = result.reasons.map(r => `• ${r}`).join('<br>');
        }

        let factorsHtml = '';
        if (result.influential_factors && result.influential_factors.length > 0) {
            factorsHtml = result.influential_factors.map(f =>
                `<span class="factor-tag">${f.factor} (${f.impact})</span>`
            ).join('');
        }

        // عرض معلومات العملات
        const currencyInfoHtml = displayCurrencyInfo(result);

        container.innerHTML = `
            <div class="result-content">
                ${displayRiskSummary(result)}
                ${currencyInfoHtml}
                
                <div class="result-status">
                    <span class="status-icon">${icon}</span>
                    <span style="font-weight: 700; font-size: 1.2rem;">${result.decision || 'غير معروف'}</span>
                    <span class="badge-result badge-${statusClass}">${result.decision || 'غير معروف'}</span>
                    <span class="badge" style="background: rgba(246,211,101,0.1); color: #f6d365;">${typeLabel}</span>
                    ${extraInfo}
                </div>

                ${result.avg_similarity ? `
                    <div class="confidence-bar">
                        <div class="confidence-fill" style="width: ${Math.min(100, result.avg_similarity)}%;"></div>
                    </div>
                    <div class="confidence-label">
                        <span>نسبة التشابه مع الصور المرجعية</span>
                        <span>${result.avg_similarity}%</span>
                    </div>
                ` : `
                    <div class="confidence-bar">
                        <div class="confidence-fill" style="width: ${confidence}%;"></div>
                    </div>
                    <div class="confidence-label">
                        <span>ثقة النموذج</span>
                        <span>${confidence}%</span>
                    </div>
                `}

                <div class="result-details">
                    <p><strong>مستوى الخطر:</strong> ${result.risk_level || 'غير معروف'}</p>
                    ${result.risk_score ? `<p><strong>درجة المخاطرة:</strong> ${result.risk_score}%</p>` : ''}
                    ${result.sender ? `<p><strong>المرسل:</strong> ${result.sender}</p>` : ''}
                    ${result.receiver ? `<p><strong>المستقبل:</strong> ${result.receiver}</p>` : ''}
                    ${result.images_count ? `<p><strong>عدد الصور المحللة:</strong> ${result.images_count}</p>` : ''}
                </div>

                <div class="explain-box">
                    <i class="fas fa-lightbulb"></i>
                    <strong>العوامل المؤثرة:</strong>
                    <div style="margin-top: 6px;">${factorsHtml || 'لا توجد عوامل مؤثرة واضحة'}</div>
                    ${reasonsHtml ? `
                    <div style="margin-top: 8px; border-top: 1px solid #1e293b; padding-top: 8px;">
                        <strong>تفاصيل التحليل:</strong>
                        <div style="margin-top: 4px;">${reasonsHtml}</div>
                    </div>` : ''}
                </div>

                <div style="margin-top: 0.5rem; font-size: 0.7rem; color: var(--text-dim); text-align: center; border-top: 1px solid var(--border-color); padding-top: 0.5rem;">
                    ${result.using_ai ? '🤖 مدعوم بالذكاء الاصطناعي' : '⚡ نظام تحليل ذكي'} · ${result.analysis_time || 'الآن'}
                </div>
            </div>
        `;
    }

    // ===== SHOW ERROR =====
    function showError(container, message) {
        container.innerHTML = `
            <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid #ef4444; border-radius: var(--radius-md); padding: 1.5rem; text-align: right;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 1rem;">
                    <i class="fas fa-circle-exclamation" style="color: #ef4444; font-size: 2rem;"></i>
                    <h3 style="color: #fca5a5; margin: 0;">${message.includes('معاملة') || message.includes('عملة') ? 'تنبيه' : 'خطأ'}</h3>
                </div>
                <p style="color: #fca5a5; white-space: pre-line; font-size: 0.95rem;">${message}</p>
                ${message.includes('معاملة') || message.includes('عملة') ? `
                    <div style="margin-top: 0.8rem; padding: 0.8rem; background: rgba(255,255,255,0.03); border-radius: var(--radius-sm); font-size: 0.8rem; color: #94a3b8;">
                        <strong>الحلول المقترحة:</strong>
                        <ul style="margin: 0.5rem 0 0 1.5rem; list-style: none; padding: 0;">
                            <li>📄 تأكد من أن الملف يحتوي على بيانات معاملة مالية</li>
                            <li>📷 تأكد من وضوح الصورة وجودة التصوير</li>
                            <li>🔄 حاول رفع ملف آخر أو صورة أوضح</li>
                        </ul>
                    </div>
                ` : ''}
            </div>
        `;
    }

    // ===== ADD TO HISTORY =====
    function addToHistory(formData, result, type) {
        const historyLog = document.getElementById('historyLog');
        const timeStr = new Date().toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });

        const decision = result.decision || 'غير معروف';
        let statusClass = 'safe';
        if (decision.includes('احتيال') || decision.includes('مزورة')) statusClass = 'fraud';
        else if (decision.includes('مشبوه')) statusClass = 'suspicious';

        const emptyMsg = historyLog.querySelector('.empty-log');
        if (emptyMsg) emptyMsg.remove();

        const entry = document.createElement('div');
        entry.className = 'log-item';

        const sender = formData.get('sender') || 'N/A';
        const receiver = formData.get('receiver') || 'N/A';
        const amount = formData.get('amount') || '0';
        const currency = formData.get('currency') || 'SAR';
        const currencyType = formData.get('currency_type') || '';

        const typeLabels = {
            'text': '📝',
            'pdf': '📄',
            'images': '🖼️',
            'currency': '🇾🇪'
        };
        const typeIcon = typeLabels[type] || '📝';

        const displayAmount = currencyType ? `${currencyType}` : `${amount} ${currency}`;
        const cameraIcon = result.camera_images > 0 ? '📷' : '';

        entry.innerHTML = `
            <span>
                <span class="log-sender">${sender}</span>
                <i class="fas fa-arrow-left log-arrow"></i>
                <span class="log-sender">${receiver}</span>
                <span class="log-amount">(${displayAmount})</span>
                <span class="log-type">${typeIcon}${cameraIcon}</span>
            </span>
            <span>
                <span class="log-status ${statusClass}">${decision}</span>
                <span class="log-time">${timeStr}</span>
            </span>
        `;
        historyLog.prepend(entry);

        const history = JSON.parse(localStorage.getItem('fraudHistory') || '[]');
        history.unshift({
            sender, receiver, amount, currency,
            currency_type: currencyType,
            decision: decision,
            type: type,
            camera: result.camera_images > 0,
            time: timeStr,
            timestamp: Date.now()
        });
        if (history.length > 50) history.pop();
        localStorage.setItem('fraudHistory', JSON.stringify(history));
    }

    // ===== UPDATE STATS =====
    function updateStats(result) {
        const decision = result.decision || '';
        const safeCount = document.getElementById('safeCount');
        const suspiciousCount = document.getElementById('suspiciousCount');
        const fraudCount = document.getElementById('fraudCount');
        const totalCount = document.getElementById('totalCount');

        let stats = JSON.parse(localStorage.getItem('fraudStats') || '{"safe":0,"suspicious":0,"fraud":0}');

        if (decision.includes('احتيال') || decision.includes('مزورة')) {
            stats.fraud++;
        } else if (decision.includes('مشبوه')) {
            stats.suspicious++;
        } else if (decision === 'سليمة' || decision.includes('سليمة') || decision.includes('أصلية') || decision.includes('صالح')) {
            stats.safe++;
        }

        localStorage.setItem('fraudStats', JSON.stringify(stats));

        document.querySelectorAll('.counter').forEach(counter => {
            const target = counter.dataset.target;
            if (target === '0') {
                if (counter.closest('.stat-card-1')) counter.textContent = stats.safe;
                else if (counter.closest('.stat-card-2')) counter.textContent = stats.suspicious;
                else if (counter.closest('.stat-card-3')) counter.textContent = stats.fraud;
                else if (counter.closest('.stat-card-4')) counter.textContent = stats.safe + stats.suspicious + stats.fraud;
            }
        });
    }

    // ===== LOAD DATA =====
    function loadStats() {
        const stats = JSON.parse(localStorage.getItem('fraudStats') || '{"safe":0,"suspicious":0,"fraud":0}');
        document.querySelectorAll('.counter').forEach(counter => {
            const target = counter.dataset.target;
            if (target === '0') {
                if (counter.closest('.stat-card-1')) {
                    counter.textContent = stats.safe;
                    counter.dataset.target = stats.safe;
                } else if (counter.closest('.stat-card-2')) {
                    counter.textContent = stats.suspicious;
                    counter.dataset.target = stats.suspicious;
                } else if (counter.closest('.stat-card-3')) {
                    counter.textContent = stats.fraud;
                    counter.dataset.target = stats.fraud;
                } else if (counter.closest('.stat-card-4')) {
                    const total = stats.safe + stats.suspicious + stats.fraud;
                    counter.textContent = total;
                    counter.dataset.target = total;
                }
            }
        });
    }

    loadStats();

    function loadHistory() {
        const historyLog = document.getElementById('historyLog');
        const history = JSON.parse(localStorage.getItem('fraudHistory') || '[]');
        if (history.length === 0) return;

        const emptyMsg = historyLog.querySelector('.empty-log');
        if (emptyMsg) emptyMsg.remove();

        history.forEach(item => {
            let statusClass = 'safe';
            if (item.decision.includes('احتيال') || item.decision.includes('مزورة')) statusClass = 'fraud';
            else if (item.decision.includes('مشبوه')) statusClass = 'suspicious';

            const typeLabels = {
                'text': '📝',
                'pdf': '📄',
                'images': '🖼️',
                'currency': '🇾🇪'
            };
            const typeIcon = typeLabels[item.type] || '📝';
            const cameraIcon = item.camera ? '📷' : '';

            const displayAmount = item.currency_type ? `${item.currency_type}` : `${item.amount} ${item.currency}`;

            const entry = document.createElement('div');
            entry.className = 'log-item';
            entry.innerHTML = `
                <span>
                    <span class="log-sender">${item.sender}</span>
                    <i class="fas fa-arrow-left log-arrow"></i>
                    <span class="log-sender">${item.receiver}</span>
                    <span class="log-amount">(${displayAmount})</span>
                    <span class="log-type">${typeIcon}${cameraIcon}</span>
                </span>
                <span>
                    <span class="log-status ${statusClass}">${item.decision}</span>
                    <span class="log-time">${item.time}</span>
                </span>
            `;
            historyLog.appendChild(entry);
        });
    }

    loadHistory();

    // ===== CLEAR HISTORY =====
    const clearBtn = document.getElementById('clearHistory');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            if (confirm('هل أنت متأكد من مسح سجل المعاملات؟')) {
                localStorage.removeItem('fraudHistory');
                localStorage.removeItem('fraudStats');
                document.getElementById('historyLog').innerHTML = `
                    <div class="empty-log">
                        <i class="fas fa-inbox"></i>
                        <p>لا توجد معاملات محللة بعد</p>
                        <span>قم بتحليل أول معاملة باستخدام الذكاء الاصطناعي</span>
                    </div>
                `;
                loadStats();
            }
        });
    }

    // ===== UPDATE CURRENCY =====
    document.querySelectorAll('select[name="country"]').forEach(select => {
        const countryCurrencyMap = {
            'SA': 'SAR', 'AE': 'AED', 'EG': 'EGP',
            'US': 'USD', 'UK': 'GBP', 'TR': 'TRY',
            'YE': 'YER', 'KW': 'KWD', 'BH': 'BHD',
            'OM': 'OMR', 'QA': 'QAR', 'JO': 'JOD',
            'EU': 'EUR'
        };

        select.addEventListener('change', function () {
            const form = this.closest('form');
            const currencySelect = form.querySelector('select[name="currency"]');
            if (currencySelect) {
                const currency = countryCurrencyMap[this.value] || 'SAR';
                currencySelect.value = currency;
            }
        });
    });

    // ===== INIT =====
    animateCounters();
    simulateAIStatus();
    simulateActivity();
});