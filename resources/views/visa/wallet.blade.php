@extends('adminlte::page')

@section('title', 'شحن المحفظة | VisaFlow Pro')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>شحن الرصيد</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                <li class="breadcrumb-item active">شحن المحفظة</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            @if (session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- GitHub Style Card -->
            <div class="card card-outline card-primary shadow-sm" style="border-radius: 6px; border-top-width: 3px;">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold" style="font-size: 1rem; color: #24292f;">
                        <i class="fas fa-wallet mr-2 text-muted"></i> تفاصيل عملية الشحن
                    </h3>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('visa.recharge') }}" method="POST" id="payment-form">
                        @csrf

                        <div class="form-group mb-4">
                            <label class="text-muted small font-weight-bold mb-2 text-uppercase"
                                style="letter-spacing: 0.5px;">
                                كمية التأشيرات المطلوبة
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"
                                        style="border-radius: 0 6px 6px 0;">
                                        <i class="fas fa-ticket-alt text-muted"></i>
                                    </span>
                                </div>
                                <input type="number" name="visa_qty" id="visa_qty"
                                    class="form-control form-control-lg border-left-0 gh-input" placeholder="0"
                                    min="1" value="1" required
                                    style="border-radius: 6px 0 0 6px; font-size: 1.1rem;">
                            </div>
                        </div>

                        <div class="price-summary-box text-center p-4 mb-4"
                            style="background-color: #f6f8fa; border: 1px dashed #d0d7de; border-radius: 6px;">
                            <span class="text-muted small font-weight-bold">إجمالي المبلغ المستحق</span>
                            <div class="mt-2">
                                <span id="total_amount" class="h1 font-weight-bold mb-0"
                                    style="color: #24292f; font-family: 'Segoe UI', sans-serif;">100</span>
                                <span class="text-muted h5 font-weight-bold ml-1">ج.م</span>
                            </div>
                            <div class="mt-2 border-top pt-2">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> سعر التأشيرة الواحدة <span
                                        class="badge badge-secondary">100 ج.م</span>
                                </small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg btn-block shadow-sm py-3" id="submit-btn"
                            style="background-color: #2da44e; border-color: rgba(27, 31, 36, 0.15); font-weight: 600;">
                            <span class="spinner-border spinner-border-sm mr-2" id="loader"
                                style="display: none;"></span>
                            <span id="btn-text">إتمام عملية الدفع</span>
                            <i class="fas fa-chevron-left ml-2" style="font-size: 0.8rem;"></i>
                        </button>

                        <div class="text-center mt-4">
                            <div class="p-2 d-inline-block"
                                style="background: #fff; border: 1px solid #d0d7de; border-radius: 20px;">
                                <img src="https://www.fawaterk.com/wp-content/uploads/2021/03/payment-methods.png"
                                    alt="Payment Methods" style="height: 18px; filter: grayscale(1); opacity: 0.6;">
                            </div>
                            <p class="text-muted mt-3 mb-0" style="font-size: 0.75rem;">
                                <i class="fas fa-shield-alt mr-1"></i> دفع آمن ومُشفر 100% عبر نظام Fawaterk
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* GitHub Input Focus Effect */
        .gh-input:focus {
            border-color: #0969da !important;
            box-shadow: 0 0 0 3px rgba(9, 105, 218, 0.3) !important;
            outline: none;
        }

        /* Layout Adjustments for AdminLTE */
        .content-wrapper {
            background-color: #f6f8fa !important;
        }

        /* Animation for the loader */
        #loader {
            vertical-align: middle;
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
    <script>
        $(document).ready(function() {
            const $qtyInput = $('#visa_qty');
            const $totalDisplay = $('#total_amount');
            const pricePerVisa = 100;
            const $form = $('#payment-form');
            const $loader = $('#loader');
            const $btnText = $('#btn-text');
            const $submitBtn = $('#submit-btn');

            // تحديث حيوي للمبلغ باستخدام jQuery (يتماشى مع AdminLTE)
            $qtyInput.on('input', function() {
                let qty = parseInt($(this).val()) || 0;
                if (qty < 0) qty = 0;
                $totalDisplay.text((qty * pricePerVisa).toLocaleString());
            });

            // تأثيرات زر الإرسال
            $form.on('submit', function() {
                $submitBtn.prop('disabled', true);
                $loader.show();
                $btnText.text('جاري توجيهك لبوابة الدفع...');
                $submitBtn.css('opacity', '0.85');
            });
        });
    </script>
@stop
