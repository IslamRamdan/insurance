@extends('adminlte::page')

@section('title', 'إنشاء طلب تأشيرة')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="m-0 text-bold" style="font-size: 1.8rem; color: #1a1a1a;">
                    <i class="fas fa-file-signature text-success mr-2"></i> إنشاء طلب تأشيرة جديد
                </h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm custom-alert" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle mr-3 fa-lg"></i>
                    <div>
                        <h5 class="mb-0 font-weight-bold">تمت العملية بنجاح!</h5>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card card-outline card-success shadow-lg border-0 mb-5">
            <div class="card-header bg-white py-3">
                <h3 class="card-title text-bold text-dark">
                    <i class="fas fa-info-circle text-muted mr-1"></i> بيانات التأشيرة الأساسية
                </h3>
            </div>

            <form method="POST" action="/visas">
                @csrf
                <div class="card-body p-4">
                    <div class="row">
                        <!-- اسم الكفيل -->
                        <div class="form-group col-md-6 mb-4">
                            <label class="input-label">اسم الكفيل الكامل</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-left-0"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="sponsor_full_name" class="form-control custom-input"
                                    placeholder="مثال: شركة إنجاز العالمية" required>
                            </div>
                        </div>

                        <!-- رقم الهوية -->
                        <div class="form-group col-md-6 mb-4">
                            <label class="input-label">رقم الهوية / السجل الضريبي</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-left-0"><i
                                            class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" name="sponsor_identity_number"
                                    class="form-control custom-input text-left" placeholder="70XXXXXXXX" required>
                            </div>
                        </div>

                        <!-- نوع التأشيرة -->
                        <div class="form-group col-md-4 mb-4">
                            <label class="input-label">نوع التأشيرة</label>
                            <select name="visa_type" class="form-control custom-select-input" required>
                                <option value="" disabled selected>اختر نوع التأشيرة...</option>
                                <option value="تأشيرة العمل المؤقت لخدمات الحج والعمرة">تأشيرة عمل مؤقت (حج وعمرة)</option>
                                <option value="عمل">تأشيرة عمل</option>
                                <option value="عمل مؤقت">عمل مؤقت</option>
                            </select>
                        </div>

                        <!-- رقم التأشيرة -->
                        <div class="form-group col-md-4 mb-4">
                            <label class="input-label">رقم التأشيرة</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-left-0"><i
                                            class="fas fa-hashtag"></i></span>
                                </div>
                                <input type="text" name="visa_number" class="form-control custom-input text-left"
                                    placeholder="130XXXXXXX" required>
                            </div>
                        </div>

                        <!-- السفارة -->
                        <div class="form-group col-md-4 mb-4">
                            <label class="input-label">السفارة (جهة القدوم)</label>
                            <select name="consulate_name" class="form-control custom-select-input" required>
                                <option value="1">📍 السويس</option>
                                <option value="2" selected>📍 القاهرة</option>
                                <option value="3">📍 الاسكندرية</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light py-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-muted small">
                            <i class="fas fa-shield-alt mr-1"></i> يتم تشفير البيانات وحفظها بشكل آمن وفقاً لسياسة الخصوصية.
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-success px-5 shadow-sm btn-lg font-weight-bold">
                                <i class="fas fa-save mr-2"></i> حفظ الطلب والبدء
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

        body {
            background-color: #f4f7f6;
            font-family: 'Cairo', sans-serif;
        }

        /* تحسين شكل الكارد */
        .card-outline.card-success {
            border-top: 5px solid #006C35 !important;
            border-radius: 15px;
        }

        /* تحسين المدخلات */
        .input-label {
            font-weight: 700;
            font-size: 0.9rem;
            color: #444;
            margin-bottom: 10px;
            display: block;
        }

        .custom-input,
        .custom-select-input {
            height: 48px !important;
            border-radius: 10px !important;
            border: 1px solid #e1e1e1 !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .custom-input:focus,
        .custom-select-input:focus {
            border-color: #006C35 !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 108, 53, 0.1) !important;
            background-color: #fff;
        }

        .input-group-text {
            border-radius: 0 10px 10px 0 !important;
            /* للـ RTL */
            border: 1px solid #e1e1e1;
            color: #006C35;
        }

        /* تعديل الحواف في وضع RTL */
        [dir="rtl"] .custom-input {
            border-radius: 10px 0 0 10px !important;
        }

        /* زرار الحفظ */
        .btn-success {
            background-color: #006C35 !important;
            border: none !important;
            border-radius: 10px;
            padding: 12px 30px;
            transition: transform 0.2s;
        }

        .btn-success:hover {
            background-color: #004d26 !important;
            transform: translateY(-2px);
        }

        .custom-alert {
            border-radius: 12px;
            background-color: #d4edda;
            color: #155724;
        }

        /* تنسيقات إضافية للغة العربية */
        .mr-2,
        .mr-3 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
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
