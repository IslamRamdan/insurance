@extends('adminlte::page')

@section('title', 'إنشاء طلب تأشيرة')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-bold" style="font-size: 1.6rem; color: #333;">
                    إنشاء طلب تأشيرة
                </h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <h5 class="mb-1">تم الحفظ بنجاح!</h5>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card card-outline card-primary shadow-sm border-0">
            <div class="card-header bg-white">
                <h3 class="card-title text-bold">بيانات التأشيرة الأساسية</h3>
            </div>

            <form method="POST" action="/visas">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- اسم الكفيل -->
                        <div class="form-group col-md-6 mb-4">
                            <label class="text-secondary">اسم الكفيل الكامل</label>
                            <input type="text" name="sponsor_full_name" class="form-control shadow-none"
                                placeholder="أدخل الاسم">
                        </div>

                        <!-- رقم الهوية -->
                        <div class="form-group col-md-6 mb-4">
                            <label class="text-secondary">رقم الهوية / السجل</label>
                            <input type="text" name="sponsor_identity_number" class="form-control shadow-none"
                                placeholder="70xxxxxxxx">
                        </div>

                        <!-- نوع التأشيرة -->
                        <div class="form-group col-md-4">
                            <label class="text-secondary">نوع التأشيرة</label>
                            <select name="visa_type" class="form-control shadow-none">
                                <option value="" disabled selected>اختر النوع...</option>
                                <option value="تأشيرة العمل المؤقت لخدمات الحج والعمرة">تأشيرة عمل مؤقت (حج وعمرة)</option>
                                <option value="عمل">تأشيرة عمل</option>
                                <option value="عمل مؤقت">عمل مؤقت</option>
                            </select>
                        </div>

                        <!-- رقم التأشيرة -->
                        <div class="form-group col-md-4">
                            <label class="text-secondary">رقم التأشيرة</label>
                            <input type="text" name="visa_number" class="form-control shadow-none"
                                placeholder="1300000000">
                        </div>

                        <!-- السفارة -->
                        <div class="form-group col-md-4">
                            <label class="text-secondary">السفارة (جهة القدوم)</label>
                            <select name="consulate_name" class="form-control shadow-none">
                                <option value="1">السويس</option>
                                <option value="2" selected>القاهرة</option>
                                <option value="3">الاسكندرية</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white py-4">
                    <div class="row justify-content-end">
                        <div class="col-auto">
                            <button type="reset" class="btn btn-light px-4 mr-2">إعادة تعيين</button>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">حفظ الطلب</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 10px;
        }

        .form-control {
            border-radius: 6px;
            height: 42px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #007bff;
            background-color: #fff;
        }

        label {
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 8px;
        }

        /* تنسيقات إضافية للغة العربية */
        .mr-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }
    </style>
@stop
