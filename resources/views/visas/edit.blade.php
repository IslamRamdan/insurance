@extends('adminlte::page')

@section('title', 'تعديل طلب تأشيرة')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="m-0 text-bold" style="font-size: 1.8rem; color: #1a1a1a;">
                    <i class="fas fa-edit text-warning mr-2"></i> تعديل طلب التأشيرة
                </h1>
                <p class="text-muted small mt-1">تعديل بيانات الطلب رقم: <span
                        class="badge badge-secondary shadow-sm">#{{ $visa->id }}</span></p>
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
                        <h5 class="mb-0 font-weight-bold">تم التحديث بنجاح!</h5>
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
                    <i class="fas fa-database text-muted mr-1"></i> مراجعة وتحديث البيانات
                </h3>
            </div>

            <form method="POST" action="/visas/{{ $visa->id }}">
                @csrf
                @method('PUT')

                <div class="card-body p-4">
                    <div class="row">
                        <!-- اسم الكفيل -->
                        <div class="form-group col-md-6 mb-4">
                            <label class="input-label">اسم الكفيل الكامل</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-left-0 text-success"><i
                                            class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="sponsor_full_name" value="{{ $visa->sponsor_full_name }}"
                                    class="form-control custom-input" placeholder="اسم الكفيل" required>
                            </div>
                        </div>

                        <!-- رقم الهوية -->
                        <div class="form-group col-md-6 mb-4">
                            <label class="input-label">رقم الهوية / الإقامة</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-left-0 text-success"><i
                                            class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" name="sponsor_identity_number"
                                    value="{{ $visa->sponsor_identity_number }}"
                                    class="form-control custom-input text-left font-weight-bold" required>
                            </div>
                        </div>

                        <!-- نوع التأشيرة -->
                        <div class="form-group col-md-4 mb-4">
                            <label class="input-label">نوع التأشيرة</label>
                            <select name="visa_type" class="form-control custom-select-input" required>
                                <option value="تأشيرة العمل المؤقت لخدمات الحج والعمرة"
                                    {{ $visa->visa_type == 'تأشيرة العمل المؤقت لخدمات الحج والعمرة' ? 'selected' : '' }}>
                                    تأشيرة عمل مؤقت (حج وعمرة)
                                </option>
                                <option value="عمل" {{ $visa->visa_type == 'عمل' ? 'selected' : '' }}>تأشيرة عمل</option>
                                <option value="عمل مؤقت" {{ $visa->visa_type == 'عمل مؤقت' ? 'selected' : '' }}>عمل مؤقت
                                </option>
                                <option value="زيارة عمل" {{ $visa->visa_type == 'زيارة عمل' ? 'selected' : '' }}>زيارة عمل
                                </option>
                            </select>
                        </div>

                        <!-- رقم التأشيرة -->
                        <div class="form-group col-md-4 mb-4">
                            <label class="input-label">رقم التأشيرة</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-left-0 text-success"><i
                                            class="fas fa-hashtag"></i></span>
                                </div>
                                <input type="text" name="visa_number" value="{{ $visa->visa_number }}"
                                    class="form-control custom-input text-left font-weight-bold" required>
                            </div>
                        </div>

                        <!-- القنصلية -->
                        <div class="form-group col-md-4 mb-4">
                            <label class="input-label">القنصلية (جهة القدوم)</label>
                            <select name="embassy" class="form-control custom-select-input" required>
                                <option value="1" {{ $visa->embassy == 1 ? 'selected' : '' }}>📍 السويس</option>
                                <option value="2" {{ $visa->embassy == 2 ? 'selected' : '' }}>📍 القاهرة</option>
                                <option value="3" {{ $visa->embassy == 3 ? 'selected' : '' }}>📍 الاسكندرية</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light py-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <a href="/visas" class="btn btn-link text-muted font-weight-bold">
                                <i class="fas fa-arrow-right mr-1"></i> العودة للقائمة
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-success px-5 shadow-sm btn-lg font-weight-bold">
                                <i class="fas fa-sync-alt mr-2"></i> تحديث البيانات
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

        .card-outline.card-success {
            border-top: 5px solid #006C35 !important;
            border-radius: 15px;
        }

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
        }

        .input-group-text {
            border-radius: 0 10px 10px 0 !important;
            /* للتوافق مع RTL */
            border: 1px solid #e1e1e1;
        }

        .btn-success {
            background-color: #006C35 !important;
            border: none !important;
            border-radius: 10px;
            padding: 12px 30px;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background-color: #004d26 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .custom-alert {
            border-radius: 12px;
        }

        /* إصلاح الهوامش للغة العربية */
        .mr-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }

        .mr-3 {
            margin-left: 1rem !important;
            margin-right: 0 !important;
        }

        .mr-1 {
            margin-left: 0.25rem !important;
            margin-right: 0 !important;
        }

        /* تمييز الأرقام بالخط العريض */
        .font-weight-bold {
            font-weight: 700 !important;
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
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();

        // جزء ربط بيانات المستخدم المسجل
        @auth
        Tawk_API.visitor = {
            name: '{{ auth()->user()->name }}',
            email: '{{ auth()->user()->email }}'
        };
        @endauth

        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/6a0352178acb811c36855663/1joefhbbp';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@stop
