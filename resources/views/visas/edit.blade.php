@extends('adminlte::page')

@section('title', 'تعديل طلب تأشيرة')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-bold" style="font-size: 1.6rem; color: #333;">
                    تعديل طلب تأشيرة
                </h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <h5 class="mb-1">تم التحديث بنجاح!</h5>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card card-outline card-success shadow-sm border-0">
            <div class="card-header bg-white">
                <h3 class="card-title text-bold">تعديل بيانات النموذج رقم: #{{ $visa->id }}</h3>
            </div>

            <form method="POST" action="/visas/{{ $visa->id }}">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <!-- اسم الكفيل -->
                        <div class="form-group col-md-6 mb-4">
                            <label class="text-secondary">اسم الكفيل الكامل</label>
                            <input type="text" name="sponsor_full_name" value="{{ $visa->sponsor_full_name }}"
                                class="form-control shadow-none">
                        </div>

                        <!-- رقم الهوية -->
                        <div class="form-group col-md-6 mb-4">
                            <label class="text-secondary">رقم الهوية / الإقامة</label>
                            <input type="text" name="sponsor_identity_number"
                                value="{{ $visa->sponsor_identity_number }}" class="form-control shadow-none">
                        </div>

                        <!-- نوع التأشيرة -->
                        <div class="form-group col-md-4">
                            <label class="text-secondary">نوع التأشيرة</label>
                            <select name="visa_type" class="form-control shadow-none">
                                <option value="تأشيرة العمل المؤقت لخدمات الحج والعمرة"
                                    {{ $visa->visa_type == 'تأشيرة العمل المؤقت لخدمات الحج والعمرة' ? 'selected' : '' }}>
                                    تأشيرة عمل مؤقت (حج وعمرة)</option>
                                <option value="عمل" {{ $visa->visa_type == 'عمل' ? 'selected' : '' }}>تأشيرة عمل</option>
                                <option value="عمل مؤقت" {{ $visa->visa_type == 'عمل مؤقت' ? 'selected' : '' }}>عمل مؤقت
                                </option>
                            </select>
                        </div>

                        <!-- رقم التأشيرة -->
                        <div class="form-group col-md-4">
                            <label class="text-secondary">رقم التأشيرة</label>
                            <input type="text" name="visa_number" value="{{ $visa->visa_number }}"
                                class="form-control shadow-none">
                        </div>

                        <!-- القنصلية -->
                        <div class="form-group col-md-4">
                            <label class="text-secondary">القنصلية (جهة القدوم)</label>
                            <select name="embassy" class="form-control shadow-none">
                                <option value="1" {{ $visa->embassy == 1 ? 'selected' : '' }}>السويس</option>
                                <option value="2" {{ $visa->embassy == 2 ? 'selected' : '' }}>القاهرة</option>
                                <option value="3" {{ $visa->embassy == 3 ? 'selected' : '' }}>الاسكندرية</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white py-4">
                    <div class="row justify-content-end">
                        <div class="col-auto">
                            <a href="/visas" class="btn btn-light px-4 mr-2">رجوع</a>
                            <button type="submit" class="btn btn-success px-5 shadow-sm">حفظ التغييرات</button>
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
            border-color: #28a745;
            /* لون أخضر للتركيز بما أنها صفحة تعديل/نجاح */
            background-color: #fff;
        }

        label {
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 8px;
        }

        .mr-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }
    </style>
@stop
