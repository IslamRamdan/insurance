@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css')
    <style>
        /* تغيير الخلفية لتكون احترافية */
        body.login-page {
            background: #f0f2f5 url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2000') no-repeat center center fixed !important;
            background-size: cover !important;
            position: relative;
        }

        /* طبقة زجاجية فوق الخلفية */
        body.login-page::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            /* تغبيش أبيض خفيف */
            backdrop-filter: blur(5px);
            z-index: -1;
        }

        .login-box {
            width: 450px;
        }

        .card-outline.card-primary {
            border-top: 5px solid #006C35 !important;
            /* أخضر سعودي */
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }

        .login-logo a {
            color: #006C35 !important;
            font-weight: 900;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: #006C35 !important;
            border: none !important;
            border-radius: 10px;
            height: 50px;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .form-control {
            border-radius: 10px;
            height: 45px;
        }

        .input-group-text {
            border-radius: 0 10px 10px 0 !important;
            color: #006C35;
        }

        .btn-outline-success:hover {
            background-color: #006C35 !important;
            color: white !important;
            border-color: #006C35 !important;
        }
    </style>
@stop

@section('auth_header', 'سجل دخولك لبدء المعاملة')

{{-- @section('auth_body')
    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">دخول للمنصة</button>
            </div>
        </div>
    </form>
@stop --}}
@section('auth_body')
    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">دخول للمنصة</button>
            </div>
        </div>
    </form>

    {{-- الزرار الجديد --}}
    <div class="mt-4 text-center">
        <p class="mb-1 text-muted">ليس لديك حساب؟</p>
        <a href="{{ route('register') }}" class="btn btn-outline-success btn-block"
            style="border-radius: 10px; border-color: #006C35; color: #006C35; font-weight: 600;">
            إنشاء حساب جديد
        </a>
    </div>
@stop

@section('auth_footer')
    <p class="my-2 text-center">
        <a href="{{ route('password.request') }}" style="color: #666; font-size: 0.9rem;">
            نسيت كلمة المرور؟
        </a>
    </p>
@stop
