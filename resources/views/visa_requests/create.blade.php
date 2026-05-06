@extends('adminlte::page')

@section('title', 'طلبات التأشيرة')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1><i class="fas fa-file-invoice mr-2 text-secondary"></i>طلبات التأشيرة</h1>

        <div class="d-flex align-items-center gap-2">
            <!-- عرض الرصيد بتصميم AdminLTE -->
            <div class="info-box shadow-sm mb-0 mr-3" style="min-height: 50px; padding: 5px 15px;">
                <span class="info-box-icon bg-info" style="width: 40px; height: 40px; font-size: 1rem;"><i
                        class="fas fa-wallet"></i></span>
                <div class="info-box-content p-1">
                    <span class="info-box-text" style="font-size: 0.8rem;">الرصيد المتاح</span>
                    <span class="info-box-number" style="font-size: 0.9rem;">
                        {{ auth()->user()->visa_balance ?? 0 }} <small>تأشيرة</small>
                        <a href="{{ route('visa.recharge.view') }}" class="text-success ml-2">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                    </span>
                </div>
            </div>

            <a href="{{ route('visa_requests.create') }}" class="btn btn-success elevation-2">
                <i class="fas fa-plus mr-1"></i> تسجيل عميل جديد
            </a>
        </div>
    </div>
@stop

@section('content')

    <div class="container main-container">
        <div class="form-card">

            <!-- Header -->
            <div class="form-header">
                <div class="icon">
                    <i class="fas fa-passport"></i>
                </div>
                <h2>تسجيل طلب تأشيرة</h2>
                <p class="mb-0 mt-2" style="opacity: 0.9;">املأ البيانات التالية بدقة لإتمام طلب التأشيرة</p>
            </div>

            <div class="form-body">

                <!-- Alerts -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6 class="mb-2"><i class="fas fa-exclamation-circle me-2"></i>يرجى تصحيح الأخطاء التالية:
                        </h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('visa_requests.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- الصور -->
                    <h5 class="section-title">
                        <i class="fas fa-images"></i>
                        المستندات المطلوبة
                    </h5>
                    <div class="row">
                        <!-- الصورة الشخصية -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label required">الصورة الشخصية</label>
                            <div class="file-input-wrapper">
                                <input type="file" name="image" id="personalImage" class="form-control"
                                    accept="image/*" required>
                            </div>
                            <small class="text-secondary">صورة حديثة بخلفية بيضاء</small>

                            <div class="image-preview-container mt-3" id="personalImagePreview" style="display: none;">
                                <div class="image-preview-wrapper position-relative">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute"
                                        style="top: 5px; right: 5px; z-index: 10;" onclick="removeImage('personalImage')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <img src="" alt="معاينة" class="image-preview img-fluid rounded border w-100"
                                        style="max-height: 250px; object-fit: contain; background: #f8f9fa;">
                                    <div class="text-success mt-2 small">
                                        <i class="fas fa-check-circle"></i> تم اختيار الصورة الشخصية
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- صورة جواز السفر -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label required">صورة جواز السفر</label>
                            <div class="file-input-wrapper">
                                <input type="file" name="image_passport" id="passportImage" class="form-control"
                                    accept="image/*" required>
                            </div>
                            <small class="text-secondary">صورة واضحة لصفحة البيانات</small>

                            <div class="image-preview-container mt-3" id="passportImagePreview" style="display: none;">
                                <div class="image-preview-wrapper position-relative">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute"
                                        style="top: 5px; right: 5px; z-index: 10;" onclick="removeImage('passportImage')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <img src="" alt="معاينة"
                                        class="image-preview img-fluid rounded border w-100 mb-2"
                                        style="max-height: 250px; object-fit: contain; background: #f8f9fa;">

                                    <button type="button" id="analyzeBtn" class="btn btn-primary w-100 shadow-sm mb-2">
                                        <i class="fas fa-magic me-2"></i> استخراج البيانات تلقائياً
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>التأشيرة</label>

                        <select name="visa_application_id" class="form-control" required>
                            <option value="">-- اختر التأشيرة --</option>

                            @foreach ($visaApplications as $visa)
                                <option value="{{ $visa->id }}">
                                    {{ $visa->sponsor_full_name }} - {{ $visa->visa_number }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- الاسم بالعربي -->
                    <h5 class="section-title">
                        <i class="fas fa-id-card"></i>
                        الاسم بالعربي
                    </h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">الاسم الأول</label>
                            <input type="text" name="a_first_name" class="form-control" placeholder="محمد" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">اسم الأب</label>
                            <input type="text" name="a_father" class="form-control" placeholder="أحمد" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">اسم الجد</label>
                            <input type="text" name="a_grand" class="form-control" placeholder="علي" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">اسم العائلة</label>
                            <input type="text" name="a_family" class="form-control" placeholder="السيد" required>
                        </div>
                    </div>

                    <!-- الاسم بالإنجليزي -->
                    <h5 class="section-title">
                        <i class="fas fa-language"></i>
                        الاسم بالإنجليزي
                    </h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">First Name</label>
                            <input type="text" name="e_first_name" class="form-control" placeholder="Mohamed"
                                required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">Father</label>
                            <input type="text" name="e_father" class="form-control" placeholder="Ahmed" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">Grand</label>
                            <input type="text" name="e_grand" class="form-control" placeholder="Ali" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">Family</label>
                            <input type="text" name="e_family" class="form-control" placeholder="Elsayed" required>
                        </div>
                    </div>

                    <!-- بيانات الجواز -->
                    <h5 class="section-title">
                        <i class="fas fa-passport"></i>
                        بيانات جواز السفر
                    </h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">رقم الجواز</label>
                            <div class="input-group-icon">
                                <i class="fas fa-hashtag"></i>
                                <input type="text" name="passport_number" class="form-control"
                                    placeholder="A12345678" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">رقم البطاقة</label>
                            <div class="input-group-icon">
                                <i class="fas fa-hashtag"></i>
                                <input type="text" name="card_id" class="form-control" placeholder="" required>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required">مكان إصدار الجواز</label>
                            <div class="input-group-icon">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" name="passport_issue_place" class="form-control"
                                    placeholder="القاهرة" required>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required">مكان الميلاد</label>
                            <div class="input-group-icon">
                                <i class="fas fa-map-pin"></i>
                                <input type="text" name="birth_place" class="form-control" placeholder="الإسكندرية"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">تاريخ إصدار الجواز</label>
                            <div class="input-group-icon">
                                <i class="fas fa-calendar-plus"></i>
                                <input type="date" name="passport_issue_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required">تاريخ انتهاء الجواز</label>
                            <div class="input-group-icon">
                                <i class="fas fa-calendar-times"></i>
                                <input type="date" name="passport_expiry_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required">تاريخ الميلاد</label>
                            <div class="input-group-icon">
                                <i class="fas fa-birthday-cake"></i>
                                <input type="date" name="birth_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- بيانات إضافية -->
                    <h5 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        بيانات إضافية
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">النوع</label>
                            <div class="input-group-icon">
                                <i class="fas fa-venus-mars"></i>
                                <select name="sex" class="form-select" required>
                                    <option value="">اختر النوع</option>
                                    <option value="1">ذكر</option>
                                    <option value="2">أنثى</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">الوظيفة</label>
                            <div class="input-group-icon">
                                <i class="fas fa-briefcase"
                                    style="position: absolute; z-index: 9; margin-top: 12px; margin-right: 10px;"></i>
                                <select id="jobSelect" name="job_or_relation_id" class="form-control select2-custom"
                                    required>
                                    <option value="">ابحث عن مهنة...</option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-paper-plane me-2"></i>
                                تسجيل الطلب
                            </button>
                        </div>
                </form>

            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* GitHub-inspired Variables & Root */
        :root {
            --github-border: #d0d7de;
            --github-bg-subtle: #f6f8fa;
            --section-icon-color: #57606a;
            --primary-blue: #0969da;
        }

        /* Main Container & Card Styling */
        .main-container {
            padding-top: 2rem;
            padding-bottom: 3rem;
        }

        .form-card {
            background: #fff;
            border: 1px solid var(--github-border);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        /* Header Styling */
        .form-header {
            background-color: var(--github-bg-subtle);
            border-bottom: 1px solid var(--github-border);
            padding: 24px;
            text-align: center;
        }

        .form-header .icon {
            font-size: 2.5rem;
            color: var(--section-icon-color);
            margin-bottom: 10px;
        }

        .form-header h2 {
            font-weight: 600;
            font-size: 1.5rem;
            color: #24292f;
        }

        /* Section Titles */
        .section-title {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-top: 30px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--section-icon-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Form Body & Inputs */
        .form-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 6px;
            color: #24292f;
        }

        .form-label.required::after {
            content: " *";
            color: #cf222e;
        }

        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid var(--github-border);
            padding: 8px 12px;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: inset 0 0 0 1px var(--primary-blue);
        }

        /* Input with Icon Wrapper */
        .input-group-icon {
            position: relative;
        }

        .input-group-icon i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #8c959f;
            z-index: 4;
        }

        .input-group-icon .form-control {
            padding-right: 35px;
            /* Space for icon on the right for Arabic */
        }

        /* Image Preview Styling */
        .image-preview-container {
            display: none;
            /* Controlled by JS */
            margin-top: 15px;
        }

        .image-preview-wrapper {
            position: relative;
            border: 1px dashed var(--github-border);
            border-radius: 8px;
            padding: 10px;
            background: #fafafa;
            text-align: center;
        }

        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .remove-image-btn {
            position: absolute;
            top: -10px;
            left: -10px;
            background: #cf222e;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            z-index: 10;
        }

        /* Submit Button Customization */
        .btn-submit {
            background-color: #2da44e;
            color: white;
            padding: 12px 40px;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid rgba(27, 31, 36, 0.15);
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #2c974b;
            color: white;
        }

        /* Info Box Adjustment */
        .info-box {
            border: 1px solid var(--github-border);
            background: #fff;
        }

        /* Custom scroll for smooth experience */
        html {
            scroll-behavior: smooth;
        }
    </style>

    <style>
        /* ضبط ارتفاع السليكت ليتماشى مع Bootstrap/Custom CSS */
        .select2-container--default .select2-selection--single {
            height: 45px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding-right: 30px;
            /* مكان الأيقونة */
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 43px;
        }

        /* تنسيق حقل البحث الداخلي */
        .select2-search__field {
            border-radius: 4px !important;
        }
    </style>

    <style>
        /* تغيير لون الخلفية والنص للعنصر النشط في القائمة الجانبية */
        .nav-sidebar .nav-item .nav-link.active {
            background-color: #006C35 !important;
            /* اللون الأخضر */
            color: #ffffff !important;
            /* لون النص أبيض */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            /* ظل خفيف لإبراز العنصر */
            border-radius: 8px;
            /* حواف مستديرة لتناسب الديزاين الجديد */
        }

        /* تغيير لون الأيقونة داخل العنصر النشط */
        .nav-sidebar .nav-item .nav-link.active i {
            color: #ffffff !important;
        }

        /* تأثير اختياري عند تمرير الماوس فوق العناصر غير النشطة */
        .nav-sidebar .nav-item .nav-link:hover:not(.active) {
            background-color: rgba(0, 108, 53, 0.1);
            color: #006C35;
        }
    </style>
@stop

@section('js')
    <!-- JS (بعد jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            const $select = $('#jobSelect');

            // 1. تهيئة Select2
            $select.select2({
                placeholder: "ابحث عن مهنة...",
                allowClear: true,
                width: '100%'
            });

            // 2. جلب البيانات
            fetch('/jops.json')
                .then(response => response.json())
                .then(data => {
                    data.forEach(job => {
                        if (job.Text && !job.Disabled) {
                            // التعديل هنا: جعلنا الاسم (job.Text) هو النص وهو القيمة أيضاً
                            // new Option(text, value, defaultSelected, selected)
                            const option = new Option(job.Text, job.Text, false, false);
                            $select.append(option);
                        }
                    });

                    // تحديث السليكت بعد إضافة البيانات
                    $select.trigger('change');
                })
                .catch(err => console.error("Error loading jobs:", err));
        });
    </script>
    <script>
        // دالة المعاينة المحسنة
        function previewImage(event, previewId) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById(previewId);
            // نستخدم querySelector لضمان الوصول للصورة داخل الحاوية المحددة فقط
            const previewImg = previewContainer.querySelector('.image-preview');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.style.setProperty('display', 'block', 'important');
                }

                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        }

        // ربط الأحداث
        document.getElementById('personalImage').addEventListener('change', function(e) {
            previewImage(e, 'personalImagePreview');
        });

        document.getElementById('passportImage').addEventListener('change', function(e) {
            previewImage(e, 'passportImagePreview');
        });

        // دالة حذف الصورة
        function removeImage(inputId) {
            const input = document.getElementById(inputId);
            const previewContainer = document.getElementById(inputId + 'Preview');
            const previewImg = previewContainer.querySelector('.image-preview');

            input.value = ''; // تفريغ المدخل
            previewContainer.style.display = 'none';
            previewImg.src = '';
        }

        // إضافة تأثير "تحميل" عند الضغط على زر الاستخراج (تجربة مستخدم أفضل)
        document.getElementById('analyzeBtn')?.addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> جاري معالجة الصورة...';

            // هنا يتم استدعاء دالة الـ OCR الخاصة بك
            // مثال: callYourOcrFunction().finally(() => { btn.disabled = false; btn.innerHTML = originalText; });

            // تنبيه بسيط للمعاينة فقط (يمكنك حذفه)
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }, 2000);
        });
    </script>

    <script type="module">
        import {
            GoogleGenerativeAI
        } from "https://esm.sh/@google/generative-ai";

        const genAI = new GoogleGenerativeAI("{{ env('GOOGLE_API_KEY') }}");

        async function fileToBase64(file) {
            const buffer = await file.arrayBuffer();
            const bytes = new Uint8Array(buffer);
            let binary = "";
            bytes.forEach((b) => binary += String.fromCharCode(b));
            return btoa(binary);
        }

        document.getElementById("analyzeBtn").addEventListener("click", async () => {
            const fileInput = document.getElementById("passportImage");
            const file = fileInput.files[0];

            if (!file) {
                Swal.fire({
                    title: "اختر صورة جواز السفر أولاً",
                    icon: "error",
                    confirmButtonText: "حسناً"
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'جاري استخراج البيانات...',
                html: 'الرجاء الانتظار',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const base64Image = await fileToBase64(file);
                const model = genAI.getGenerativeModel({
                    model: "gemini-2.5-flash",
                    // model: "Gemma-4-26B",
                });

                const prompt = `Extract all information from the passport image with high accuracy, ensuring no errors, and present the output as a JSON object. The JSON should include the following keys:

passport_no
type
country_code
full_name_english
full_name_arabic (ensure names are properly separated, e.g., 'محمد حمدي عبده عبده العزبي')
date_of_birth (format: DD/MM/YYYY)
place_of_birth (must be one of: 'القاهرة', 'الجيزة', 'الأسكندرية', 'الدقهلية', 'البحر الأحمر', 'البحيرة', 'الفيوم', 'الغربية', 'الإسماعيلية', 'المنوفية', 'المنيا', 'القليوبية', 'الوادي الجديد', 'السويس', 'أسوان', 'أسيوط', 'بني سويف', 'بورسعيد', 'دمياط', 'الشرقية', 'جنوب سيناء', 'كفر الشيخ', 'مطروح', 'الأقصر', 'قنا', 'شمال سيناء', 'سوهاج')
nationality
sex (M or F)
date_of_issue (format: DD/MM/YYYY)
date_of_expiry (format: DD/MM/YYYY)
issuing_office
national_id (should be in Western/English numerals)
profession`;

                const result = await model.generateContent({
                    contents: [{
                        role: "user",
                        parts: [{
                            inlineData: {
                                mimeType: file.type,
                                data: base64Image,
                            },
                        }, {
                            text: prompt
                        }],
                    }],
                });

                let text = await result.response.text();

                // Clean the text from Markdown if present
                text = text.trim();
                if (text.startsWith("```json")) {
                text = text.replace(/^```json/, '').replace(/```$/, '').trim();
            }

            try {
                const data = JSON.parse(text);

                if (data.passport_no) {
                    // Fill Arabic name fields
                    const arabicNameParts = data.full_name_arabic.split(' ');
                    if (arabicNameParts.length >= 4) {
                        document.querySelector('input[name="a_first_name"]').value = arabicNameParts[0] ||
                            '';
                        document.querySelector('input[name="a_father"]').value = arabicNameParts[1] || '';
                        document.querySelector('input[name="a_grand"]').value = arabicNameParts[2] || '';
                        document.querySelector('input[name="a_family"]').value = arabicNameParts.slice(3)
                            .join(' ') || '';
                    }

                    // Fill English name fields
                    const englishNameParts = data.full_name_english.split(' ');
                    if (englishNameParts.length >= 4) {
                        document.querySelector('input[name="e_first_name"]').value = englishNameParts[0] ||
                            '';
                        document.querySelector('input[name="e_father"]').value = englishNameParts[1] || '';
                        document.querySelector('input[name="e_grand"]').value = englishNameParts[2] || '';
                        document.querySelector('input[name="e_family"]').value = englishNameParts.slice(3)
                            .join(' ') || '';
                    }

                    // Fill passport number
                    document.querySelector('input[name="passport_number"]').value = data.passport_no || '';
                    document.querySelector('input[name="card_id"]').value = data.national_id || '';

                    // Fill issuing office
                    document.querySelector('input[name="passport_issue_place"]').value = data
                        .issuing_office || '';

                    // Fill place of birth
                    document.querySelector('input[name="birth_place"]').value = data.place_of_birth || '';

                    // Fill passport issue date
                    if (data.date_of_issue) {
                        let parts = data.date_of_issue.split('/');
                        if (parts.length === 3) {
                            let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                            document.querySelector('input[name="passport_issue_date"]').value =
                                formattedDate;
                        }
                    }

                    // Fill passport expiry date
                    if (data.date_of_expiry) {
                        let parts = data.date_of_expiry.split('/');
                        if (parts.length === 3) {
                            let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                            document.querySelector('input[name="passport_expiry_date"]').value =
                                formattedDate;
                        }
                    }

                    // Fill birth date
                    if (data.date_of_birth) {
                        let parts = data.date_of_birth.split('/');
                        if (parts.length === 3) {
                            let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                                document.querySelector('input[name="birth_date"]').value = formattedDate;
                            }
                        }

                        // Fill gender
                        const sexSelect = document.querySelector('select[name="sex"]');
                        if (data.sex === 'M') {
                            sexSelect.value = '1'; // Male
                        } else if (data.sex === 'F') {
                            sexSelect.value = '2'; // Female
                        }

                        // Show success message
                        Swal.fire({
                            title: "تم استخراج البيانات بنجاح!",
                            text: "تم ملء جميع الحقول تلقائياً",
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });

                        console.log("Extracted data:", data);

                    } else {
                        Swal.fire({
                            title: "الصورة غير واضحة!",
                            text: "يرجى التقاط صورة أوضح للجواز",
                            icon: "error",
                            confirmButtonText: "حسناً"
                        });
                    }

                } catch (error) {
                    console.error("Error parsing JSON:", error);
                    Swal.fire({
                        title: "خطأ في معالجة البيانات",
                        text: "الرجاء المحاولة مرة أخرى",
                        icon: "error",
                        confirmButtonText: "حسناً"
                    });
                }

            } catch (error) {
                console.error("❌ Error:", error);
                Swal.fire({
                    title: "حدث خطأ",
                    text: "حدث خطأ أثناء تحليل الصورة، الرجاء المحاولة مرة أخرى",
                    icon: "error",
                    confirmButtonText: "حسناً"
                });
            }
        });
    </script>
@stop
