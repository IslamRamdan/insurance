@extends('adminlte::page')

@section('title', 'إعدادات الحساب')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark" style="font-family: 'Cairo', sans-serif;">إعدادات الحساب</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid" dir="rtl">
        <div class="row">
            {{-- العمود الأيمن: المعلومات الأساسية --}}
            <div class="col-md-6">
                <div class="card card-outline card-primary shadow-sm h-100">
                    <div class="card-header">
                        <h3 class="card-title float-right">
                            <i class="fas fa-user-circle ml-2 text-primary"></i> البيانات الشخصية
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- العمود الأيسر: الحماية وإنجاز --}}
            <div class="col-md-6">
                {{-- كارد كلمة المرور --}}
                <div class="card card-outline card-info shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title float-right">
                            <i class="fas fa-key ml-2 text-info"></i> تأمين الحساب (كلمة المرور)
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- كارد بيانات منصة إنجاز --}}
                <div class="card card-outline card-success shadow-sm mt-4">
                    <div class="card-header">
                        <h3 class="card-title float-right">
                            <i class="fas fa-shield-alt ml-2 text-success"></i> بيانات منصة إنجاز
                        </h3>
                    </div>
                    <div class="card-body">
                        {{-- الفورم الخاص بحفظ بيانات إنجاز --}}
                        <form method="post" action="{{ route('profile.engaz.update') }}">
                            @csrf
                            @method('patch')

                            <!-- بريد إنجاز -->
                            <div class="form-group">
                                <x-input-label for="engaz_email" :value="__('بريد منصة إنجاز')" />
                                <x-text-input id="engaz_email" name="engaz_email" type="text" class="form-control"
                                    :value="old('engaz_email', $user->engaz_email)" autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('engaz_email')" />
                            </div>

                            <!-- كلمة مرور إنجاز -->
                            <div class="form-group mt-3">
                                <x-input-label for="engaz_password" :value="__('كلمة مرور منصة إنجاز')" />
                                <x-text-input id="engaz_password" name="engaz_password" type="password" class="form-control"
                                    :value="old('engaz_password', $user->engaz_password)" />
                                <x-input-error class="mt-2" :messages="$errors->get('engaz_password')" />
                            </div>

                            <hr>

                            <!-- زر الحفظ -->
                            <div class="d-flex align-items-center justify-content-start mt-4">
                                <button type="submit" class="btn btn-github-green px-4 font-weight-bold shadow-sm">
                                    <i class="fas fa-save ml-1"></i> {{ __('حفظ بيانات إنجاز') }}
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                                        class="text-sm text-success mr-3 mb-0 font-weight-bold">
                                        <i class="fas fa-check-circle ml-1"></i> {{ __('تم الحفظ بنجاح.') }}
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* 1. الخط العام والتعريب */
        body,
        .main-sidebar,
        .card-title,
        label,
        input,
        button,
        .content-header h1 {
            font-family: 'Cairo', sans-serif !important;
        }

        /* 2. توحيد شكل جميع أزرار الحفظ (اللون الأخضر) */
        .btn-primary,
        .btn-success,
        .btn-github-green,
        button[type="submit"],
        input[type="submit"] {
            background-color: #2ea44f !important;
            /* أخضر GitHub */
            border: 1px solid rgba(27, 31, 35, 0.15) !important;
            color: #ffffff !important;
            padding: 8px 20px !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            box-shadow: 0 1px 0 rgba(27, 31, 35, 0.1) !important;
            transition: all 0.2s ease-in-out !important;
            margin-top: 10px;
        }

        .btn-primary:hover,
        button[type="submit"]:hover,
        .btn-github-green:hover {
            background-color: #2c974b !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        /* 3. تحسين شكل حقول الإدخال */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        .form-control {
            display: block;
            width: 100%;
            height: 42px !important;
            padding: 8px 12px !important;
            border: 1px solid #d1d5da !important;
            border-radius: 6px !important;
            background-color: #ffffff !important;
            color: #24292e !important;
            box-shadow: inset 0 1px 2px rgba(27, 31, 35, 0.075) !important;
        }

        input:focus {
            border-color: #0366d6 !important;
            outline: 0 !important;
            box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.25) !important;
        }

        /* 4. تنسيق النصوص والعناوين */
        label {
            font-weight: 700 !important;
            color: #24292e !important;
            margin-bottom: 5px !important;
            font-size: 0.9rem !important;
        }

        .card-header {
            background-color: #f6f8fa !important;
            border-bottom: 1px solid #e1e4e8 !important;
        }

        .card-title {
            float: right !important;
            font-weight: 700 !important;
        }

        /* 5. ضبط المسافات */
        .mr-3 {
            margin-right: 1rem !important;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }

        /* إخفاء الهوامش الزائدة من Breeze */
        .max-w-xl {
            max-width: 100% !important;
        }
    </style>
@endsection
