@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@section('adminlte_css')
    <style>
        /* توحيد الخلفية مع صفحة الدخول */
        body.register-page {
            background: #f0f2f5 url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2000') no-repeat center center fixed !important;
            background-size: cover !important;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body.register-page::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(8px);
            z-index: -1;
        }

        .register-box {
            width: 500px;
        }

        .card-outline.card-primary {
            border-top: 5px solid #006C35 !important;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }

        .register-logo a {
            color: #006C35 !important;
            font-weight: 900;
            font-family: 'Cairo', sans-serif;
        }

        .btn-primary {
            background-color: #006C35 !important;
            border: none !important;
            border-radius: 10px;
            height: 45px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #004d26 !important;
            transform: translateY(-2px);
        }

        .form-control {
            border-radius: 10px;
            height: 45px;
            border: 1px solid #d1e7dd;
        }

        .input-group-text {
            border-radius: 0 10px 10px 0 !important;
            color: #006C35;
            background: transparent;
        }
    </style>
@stop

@section('auth_header', 'إنشاء حساب جديد للبدء في حجز التأشيرة')

@section('auth_body')
    <form action="{{ route('register') }}" method="post">
        @csrf

        {{-- الاسم بالكامل --}}
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="الاسم بالكامل" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-user"></span></div>
            </div>
            @error('name')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- البريد الإلكتروني --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="البريد الإلكتروني" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- كلمة المرور --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="كلمة المرور" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        {{-- تأكيد كلمة المرور --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control" placeholder="تأكيد كلمة المرور"
                required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">إنشاء الحساب</button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <p class="my-0 text-center">
        <a href="{{ route('login') }}" class="text-center" style="color: #666;">
            لديك حساب بالفعل؟ سجل دخولك هنا
        </a>
    </p>
@stop
