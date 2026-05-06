@extends('adminlte::page')

@section('title', 'شحن سريع | VisaFlow Pro')

@section('content')
    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">

                <!-- Quick Recharge Card -->
                <div class="card shadow-sm border-0 gh-card">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 font-weight-bold">
                            <i class="fas fa-bolt text-warning ml-2"></i> شحن سريع للمحفظة
                        </h6>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('visa.pay.redirect') }}" method="POST" id="quick-pay-form">
                            @csrf

                            <!-- Visa Quantity Input -->
                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-muted mb-2">عدد التأشيرات المطلوبة</label>
                                <div class="input-group">
                                    <input type="number" name="visa_qty" id="visa_qty"
                                        class="form-control form-control-lg border-right-0 gh-input" value="1"
                                        min="1" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white border-left-0 text-muted small">تأشيرة</span>
                                    </div>
                                </div>
                                <div class="mt-2 small text-secondary">
                                    إجمالي المبلغ المستحق:
                                    <span id="total_price" class="font-weight-bold text-dark h6 mb-0">100</span>
                                    <span class="font-weight-bold text-dark">ج.م</span>
                                </div>
                            </div>

                            <!-- Payment Method Selector -->
                            <label class="small font-weight-bold text-muted mb-3">اختر وسيلة الدفع</label>
                            <div class="payment-selector row no-gutters">
                                <div class="col-4 p-1">
                                    <input type="radio" name="payment_id" value="2" id="card"
                                        class="payment-radio d-none" checked>
                                    <label for="card" class="payment-card-btn">
                                        <i class="fas fa-credit-card"></i>
                                        <span>بطاقة بنكية</span>
                                    </label>
                                </div>
                                <div class="col-4 p-1">
                                    <input type="radio" name="payment_id" value="3" id="fawry"
                                        class="payment-radio d-none">
                                    <label for="fawry" class="payment-card-btn">
                                        <i class="fas fa-store"></i>
                                        <span>فوري</span>
                                    </label>
                                </div>
                                <div class="col-4 p-1">
                                    <input type="radio" name="payment_id" value="4" id="wallet"
                                        class="payment-radio d-none">
                                    <label for="wallet" class="payment-card-btn">
                                        <i class="fas fa-mobile-alt"></i>
                                        <span>محفظة</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-github btn-block btn-lg mt-4 shadow-sm py-3"
                                id="submit-btn">
                                <span id="btn-text">الذهاب للدفع الآمن</span>
                                <i class="fas fa-chevron-left mr-2" style="font-size: 0.8rem;"></i>
                            </button>

                            <div class="text-center mt-3">
                                <small class="text-muted" style="font-size: 10px;">
                                    <i class="fas fa-lock ml-1"></i> جميع المعاملات مشفرة وآمنة
                                </small>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* GitHub Aesthetic Integration */
        .gh-card {
            border-radius: 6px;
            border: 1px solid #d0d7de;
        }

        .gh-input:focus {
            border-color: #0969da !important;
            box-shadow: 0 0 0 3px rgba(9, 105, 218, 0.1) !important;
            outline: none;
        }

        /* Payment Selector Styling */
        .payment-card-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px 5px;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            background: #f6f8fa;
            height: 100%;
            margin-bottom: 0;
        }

        .payment-card-btn i {
            font-size: 1.25rem;
            margin-bottom: 8px;
            color: #57606a;
        }

        .payment-card-btn span {
            font-size: 11px;
            font-weight: 600;
            color: #24292f;
        }

        /* Radio Checked State */
        .payment-radio:checked+.payment-card-btn {
            border-color: #0969da;
            background-color: #f0f7ff;
            box-shadow: 0 0 0 1px #0969da;
        }

        .payment-radio:checked+.payment-card-btn i {
            color: #0969da;
        }

        /* GitHub Green Button */
        .btn-github {
            background-color: #2da44e;
            color: #ffffff;
            border: 1px solid rgba(27, 31, 36, 0.15);
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .btn-github:hover {
            background-color: #2c974b;
            color: #ffffff;
        }

        .btn-github:active {
            background-color: #298e46;
            box-shadow: inset 0 0.15em 0.3em rgba(27, 31, 35, 0.15);
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
            const $totalPrice = $('#total_price');
            const pricePerVisa = 100;
            const $form = $('#quick-pay-form');
            const $submitBtn = $('#submit-btn');

            // تحديث حيوي للسعر
            $qtyInput.on('input', function() {
                const qty = parseInt($(this).val()) || 0;
                $totalPrice.text((qty * pricePerVisa).toLocaleString());
            });

            // تأثيرات الإرسال
            $form.on('submit', function() {
                $submitBtn.prop('disabled', true);
                $submitBtn.find('#btn-text').text('جاري المعالجة...');
                $submitBtn.css('opacity', '0.8');
            });
        });
    </script>
@stop
