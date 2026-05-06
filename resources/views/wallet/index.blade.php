<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شحن الرصيد | VisaFlow Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://app.fawaterk.com/fawaterkPlugin/fawaterkPlugin.min.js"></script>

    <style>
        body {
            background-color: #f6f8fa;
            font-family: -apple-system, sans-serif;
            color: #24292f;
        }

        .card {
            border: 1px solid #d0d7de;
            border-radius: 6px;
            box-shadow: none;
            margin-bottom: 24px;
        }

        .card-header {
            background-color: #f6f8fa;
            border-bottom: 1px solid #d0d7de;
            font-weight: 600;
        }

        .btn-github {
            background-color: #2da44e;
            color: #fff;
            font-weight: 600;
            border: 1px solid rgba(27, 31, 36, 0.15);
            padding: 12px;
            border-radius: 6px;
        }

        .btn-github:hover {
            background-color: #2c974b;
            color: #fff;
        }

        /* تحسين مظهر الـ IFrame */
        #fawaterkDivId {
            min-height: 150px;
            border: 1px dashed #d0d7de;
            border-radius: 6px;
            padding: 10px;
            background: #fff;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if (session('payment_info'))
                    <div class="alert alert-success border-success text-center mb-4 shadow-sm">
                        <h5 class="mb-2"><i class="fas fa-receipt ml-2"></i> كود الدفع (فوري)</h5>
                        <div class="display-4 font-weight-bold">{{ session('payment_info')['code'] }}</div>
                        <p class="small mb-0">يرجى التوجه لأقرب منفذ فوري، الكود صالح حتى:
                            {{ session('payment_info')['expire'] }}</p>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header p-3">شحن رصيد المحفظة عبر IFrame</div>
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">كم عدد التأشيرات؟</label>
                            <input type="number" id="visa_qty_input" name="visa_qty"
                                class="form-control form-control-lg bg-light" placeholder="0" min="1"
                                value="1" required>
                        </div>

                        <label class="font-weight-bold mb-3 text-primary"><i class="fas fa-shield-alt ml-1"></i> اختر
                            وسيلة الدفع من القائمة التالية:</label>

                        <div id="fawaterkDivId" class="mb-4 text-center p-4">
                            <p class="text-muted small">جاري تحميل وسائل الدفع الآمنة...</p>
                        </div>

                    </div>
                </div>

                <div class="text-center">
                    <p class="small text-muted text-uppercase tracking-widest font-weight-bold">Secured by Fawaterk</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ملاحظة: الـ HashKey لازم يتم توليده في الـ Controller وتمريره هنا
            var pluginConfig = {
                envType: "test", // غيرها لـ "live" في الإنتاج
                hashKey: "{{ $hashKey ?? '' }}",
                style: {
                    listing: "horizontal"
                },
                requestBody: {
                    "cartTotal": "100", // القيمة الافتراضية
                    "currency": "EGP",
                    "customer": {
                        "first_name": "{{ auth()->user()->name }}",
                        "last_name": "User",
                        "email": "{{ auth()->user()->email }}",
                        "phone": "01228815901",
                        "address": "Egypt"
                    },
                    "redirectionUrls": {
                        "successUrl": "{{ route('visa.wallet.callback') }}",
                        "failUrl": "{{ route('visa.wallet') }}",
                        "pendingUrl": "{{ route('visa.wallet') }}"
                    },
                    "cartItems": [{
                        "name": "شحن رصيد تأشيرات",
                        "price": "100",
                        "quantity": "1"
                    }]
                }
            };

            // تشغيل الـ IFrame
            if (pluginConfig.hashKey) {
                fawaterkCheckout(pluginConfig);
            } else {
                document.getElementById('fawaterkDivId').innerHTML =
                    '<p class="text-danger">خطأ: لم يتم توليد مفتاح الأمان (HashKey)</p>';
            }
        });
    </script>
</body>

</html>
