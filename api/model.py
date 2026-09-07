# api/model.py - نموذج الذكاء الاصطناعي المحسن

import numpy as np
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.feature_extraction.text import TfidfVectorizer
import joblib
import os
import re
import random
from datetime import datetime

class AIDetector:
    """نموذج ذكاء اصطناعي متقدم لكشف الاحتيال المالي"""
    
    def __init__(self):
        self.model = None
        self.scaler = None
        self.label_encoder = None
        self.tfidf = None
        self.feature_names = []
        
        self.model_path = 'ai_model.pkl'
        self.scaler_path = 'ai_scaler.pkl'
        self.encoder_path = 'ai_encoder.pkl'
        self.tfidf_path = 'ai_tfidf.pkl'
        
        if self.load_model():
            print("✅ تم تحميل نموذج الذكاء الاصطناعي بنجاح")
        else:
            print("🔄 جاري تدريب نموذج الذكاء الاصطناعي...")
            self.train_model()
    
    def generate_training_data(self, n_samples=15000):
        """توليد بيانات تدريب محاكاة للواقع"""
        np.random.seed(42)
        
        data = []
        suspicious_patterns = ['عاجل', 'طوارئ', 'مستعجل', 'سري', 'تحويل', 'حساب', 'مبلغ']
        
        for i in range(n_samples):
            # الميزات الأساسية
            amount = np.random.exponential(5000) + 100
            time_since = np.random.exponential(30) + 1
            frequency = np.random.poisson(5) + 1
            
            # اختيار عشوائي
            countries = ['SA', 'AE', 'EG', 'US', 'UK', 'TR', 'EU', 'other', 'YE']
            country_weights = [0.28, 0.15, 0.12, 0.10, 0.08, 0.07, 0.06, 0.06, 0.08]
            country = np.random.choice(countries, p=country_weights)
            
            currencies = ['SAR', 'USD', 'EUR', 'GBP', 'AED', 'EGP', 'TRY', 'YER']
            currency_weights = [0.30, 0.18, 0.10, 0.08, 0.10, 0.08, 0.06, 0.10]
            currency = np.random.choice(currencies, p=currency_weights)
            
            transaction_types = ['تحويل بنكي', 'دفع إلكتروني', 'سحب نقدي', 'إيداع', 'تحويل دولي', 'شراء']
            type_weights = [0.22, 0.18, 0.15, 0.15, 0.15, 0.15]
            transaction_type = np.random.choice(transaction_types, p=type_weights)
            
            # توليد نص الملاحظات
            if i % 10 == 0:
                notes = "تحويل عاجل مبلغ كبير إلى حساب خارجي"
            elif i % 7 == 0:
                notes = f"طلب تحويل مبلغ {amount:.0f} ريال إلى حساب في {country}"
            elif i % 5 == 0:
                notes = "معاملة طوارئ مستعجلة"
            elif i % 3 == 0 and amount > 30000:
                notes = "تحويل مبلغ كبير جداً، يرجى المراجعة"
            elif i % 4 == 0 and country == 'YE':
                notes = "تحويل إلى حساب في اليمن، مبلغ كبير"
            else:
                notes = "تحويل روتيني"
            
            # توليد محتوى الملف
            file_content = ""
            if amount > 50000:
                file_content += "مبلغ كبير جداً 50000 ريال "
            if country in ['US', 'UK', 'TR']:
                file_content += "تحويل دولي إلى خارج المملكة "
            if country == 'YE':
                file_content += "تحويل إلى اليمن "
            if transaction_type == 'تحويل دولي':
                file_content += "طلب تحويل دولي عاجل "
            file_content += "رقم الحساب: SA123456789"
            
            # حساب درجة المخاطرة الحقيقية
            risk_score = 0
            
            # مبلغ
            if amount > 50000:
                risk_score += 0.35
            elif amount > 20000:
                risk_score += 0.25
            elif amount > 5000:
                risk_score += 0.10
            
            # وقت
            if time_since < 5:
                risk_score += 0.25
            elif time_since < 15:
                risk_score += 0.15
            
            # تكرار
            if frequency > 20:
                risk_score += 0.20
            elif frequency < 3 and amount > 10000:
                risk_score += 0.15
            
            # دولة
            risky_countries = {'US': 0.30, 'UK': 0.25, 'TR': 0.30, 'other': 0.35, 'YE': 0.20}
            risk_score += risky_countries.get(country, 0.10)
            
            # عملة
            if currency != 'SAR' and country != 'SA':
                risk_score += 0.15
            
            # نوع المعاملة
            if transaction_type in ['تحويل دولي', 'سحب نقدي']:
                risk_score += 0.15
            
            # تحليل النص
            suspicious_words = ['عاجل', 'طوارئ', 'مستعجل', 'سري']
            for word in suspicious_words:
                if word in notes or word in file_content:
                    risk_score += 0.08
            
            risk_score = min(1, risk_score)
            
            # تصنيف
            if risk_score > 0.75:
                label = 2  # احتيال
            elif risk_score > 0.45:
                label = 1  # مشبوه
            else:
                label = 0  # سليم
            
            data.append({
                'amount': amount,
                'time_since': time_since,
                'frequency': frequency,
                'country': country,
                'currency': currency,
                'transaction_type': transaction_type,
                'notes': notes,
                'file_content': file_content,
                'label': label,
                'risk_score': risk_score
            })
        
        return data
    
    def train_model(self):
        """تدريب النموذج"""
        try:
            # توليد البيانات
            data = self.generate_training_data(15000)
            df = pd.DataFrame(data)
            
            # ترميز المتغيرات الفئوية
            self.label_encoder = LabelEncoder()
            df['country_encoded'] = self.label_encoder.fit_transform(df['country'])
            df['currency_encoded'] = self.label_encoder.fit_transform(df['currency'])
            df['type_encoded'] = self.label_encoder.fit_transform(df['transaction_type'])
            
            # معالجة النص (TF-IDF)
            texts = df['notes'] + ' ' + df['file_content']
            self.tfidf = TfidfVectorizer(max_features=30, analyzer='char', ngram_range=(2, 4))
            text_features = self.tfidf.fit_transform(texts).toarray()
            
            # تجهيز الميزات العددية
            numeric_features = df[['amount', 'time_since', 'frequency']].values
            
            # ميزات النص المستخرجة
            text_extracted = np.array([[
                len(t),
                len(t.split()),
                len(re.findall(r'\d+', t)),
                sum(1 for w in ['عاجل', 'طوارئ', 'مستعجل', 'سري'] if w in t)
            ] for t in texts])
            
            # دمج جميع الميزات
            X = np.hstack([
                numeric_features,
                text_extracted,
                df[['country_encoded', 'currency_encoded', 'type_encoded']].values,
                text_features
            ])
            
            y = df['label'].values
            
            # تطبيع
            self.scaler = StandardScaler()
            X_scaled = self.scaler.fit_transform(X)
            
            # حفظ أسماء الميزات
            self.feature_names = [
                'المبلغ', 'الزمن', 'التكرار',
                'طول_النص', 'كلمات_النص', 'أرقام_النص', 'كلمات_مشبوهة',
                'الدولة', 'العملة', 'نوع_المعاملة'
            ] + [f'tfidf_{i}' for i in range(text_features.shape[1])]
            
            # تدريب النموذج
            self.model = RandomForestClassifier(
                n_estimators=250,
                max_depth=15,
                min_samples_split=5,
                random_state=42,
                class_weight='balanced',
                n_jobs=-1
            )
            self.model.fit(X_scaled, y)
            
            # حفظ النموذج
            self.save_model()
            
            # طباعة أهمية الميزات
            importance = self.model.feature_importances_
            top_indices = np.argsort(importance)[-10:][::-1]
            print("\n📊 أهم 10 ميزات في النموذج:")
            for idx in top_indices:
                if idx < len(self.feature_names):
                    print(f"   - {self.feature_names[idx]}: {importance[idx]:.4f}")
            
            print("✅ تم تدريب وحفظ نموذج الذكاء الاصطناعي بنجاح")
            
        except Exception as e:
            print(f"❌ خطأ في تدريب النموذج: {str(e)}")
            raise
    
    def save_model(self):
        """حفظ النموذج"""
        try:
            joblib.dump(self.model, self.model_path)
            joblib.dump(self.scaler, self.scaler_path)
            joblib.dump(self.label_encoder, self.encoder_path)
            joblib.dump(self.tfidf, self.tfidf_path)
        except Exception as e:
            print(f"❌ خطأ في حفظ النموذج: {str(e)}")
    
    def load_model(self):
        """تحميل النموذج"""
        try:
            if all(os.path.exists(p) for p in [self.model_path, self.scaler_path, self.encoder_path, self.tfidf_path]):
                self.model = joblib.load(self.model_path)
                self.scaler = joblib.load(self.scaler_path)
                self.label_encoder = joblib.load(self.encoder_path)
                self.tfidf = joblib.load(self.tfidf_path)
                return True
            return False
        except Exception as e:
            print(f"❌ خطأ في تحميل النموذج: {str(e)}")
            return False
    
    def predict(self, features, notes='', file_content=''):
        """التنبؤ باستخدام النموذج"""
        try:
            # ترميز المتغيرات
            try:
                country_encoded = self.label_encoder.transform([features['country']])[0]
            except:
                country_encoded = 0
            
            try:
                currency_encoded = self.label_encoder.transform([features['currency']])[0]
            except:
                currency_encoded = 0
            
            try:
                type_encoded = self.label_encoder.transform([features['transaction_type']])[0]
            except:
                type_encoded = 0
            
            # تجهيز النص للتحليل
            full_text = notes + ' ' + file_content
            
            # ميزات النص
            text_length = len(full_text)
            text_words = len(full_text.split()) if full_text.strip() else 0
            text_numbers = len(re.findall(r'\d+', full_text))
            text_suspicious = sum(1 for w in ['عاجل', 'طوارئ', 'مستعجل', 'سري'] if w in full_text)
            
            # ميزات TF-IDF
            if self.tfidf:
                try:
                    tfidf_features = self.tfidf.transform([full_text]).toarray()
                except:
                    tfidf_features = np.zeros((1, 30))
            else:
                tfidf_features = np.zeros((1, 30))
            
            # تجميع الميزات
            X = np.hstack([
                [[
                    features['amount'],
                    features['time_since'],
                    features['frequency'],
                    text_length,
                    text_words,
                    text_numbers,
                    text_suspicious,
                    country_encoded,
                    currency_encoded,
                    type_encoded
                ]],
                tfidf_features
            ])
            
            # تطبيع
            X_scaled = self.scaler.transform(X)
            
            # تنبؤ
            prediction = self.model.predict(X_scaled)[0]
            probabilities = self.model.predict_proba(X_scaled)[0]
            
            # تفسير
            decision_map = {0: 'سليمة', 1: 'مشبوهة', 2: 'احتيال'}
            decision = decision_map[prediction]
            
            # حساب درجة المخاطرة
            risk_score = (probabilities[2] * 0.7 + probabilities[1] * 0.3) * 100
            
            # أسباب القرار
            text_reasons = []
            if text_suspicious > 0:
                text_reasons.append(f'تم العثور على {text_suspicious} كلمة مشبوهة في النص')
            if text_numbers > 5:
                text_reasons.append(f'تم العثور على {text_numbers} رقم في النص')
            if features['amount'] > 100000:
                text_reasons.append('المبلغ مرتفع جداً')
            if features['time_since'] < 5:
                text_reasons.append('فترة زمنية قصيرة جداً')
            
            return {
                'decision': decision,
                'decision_icon': '✅' if decision == 'سليمة' else ('⚠️' if decision == 'مشبوهة' else '🚨'),
                'confidence': float(max(probabilities)),
                'risk_level': 'منخفض' if decision == 'سليمة' else ('متوسط' if decision == 'مشبوهة' else 'مرتفع جداً'),
                'risk_score': round(risk_score, 1),
                'risk_stars': 5 - round(risk_score / 20),
                'reasons': text_reasons,
                'probabilities': {
                    'سليمة': float(probabilities[0]),
                    'مشبوهة': float(probabilities[1]) if len(probabilities) > 1 else 0,
                    'احتيال': float(probabilities[2]) if len(probabilities) > 2 else 0
                },
                'text_analysis': {
                    'length': text_length,
                    'words': text_words,
                    'numbers': text_numbers,
                    'suspicious_words': text_suspicious
                },
                'using_ai': True
            }
            
        except Exception as e:
            print(f"❌ خطأ في التنبؤ: {str(e)}")
            return {
                'decision': 'غير معروف',
                'decision_icon': '❓',
                'confidence': 0.5,
                'risk_level': 'غير معروف',
                'risk_score': 0,
                'error': str(e),
                'using_ai': False
            }