<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حالة الدفع | VisaFlow Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f6f8fa;
            font-family: -apple-system, system-ui, sans-serif;
            color: #24292f;
        }

        .card {
            border: 1px solid #d0d7de;
            border-radius: 6px;
            box-shadow: none;
            margin-top: 60px;
        }

        .status-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .text-success {
            color: #1a7f37 !important;
        }

        .text-danger {
            color: #cf222e !important;
        }

        .btn-github {
            background-color: #2da44e;
            color: white;
            border: 1px solid rgba(27, 31, 36, 0.15);
            font-weight: 600;
            padding: 10px 30px;
            border-radius: 6px;
        }

        .btn-github:hover {
            background-color: #2c974b;
            color: white;
        }

        .invoice-box {
            background: #fff;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card">
                    <div class="card-body p-5">
                        @if ($status == 'success')
                            <div class="status-icon text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h2 class="font-weight-bold mb-3">تمت العملية بنجاح!</h2>
                            <p class="text-muted">عاش يا بطل، تم تحديث رصيد محفظتك بنجاح وجاهز للاستخدام.</p>
                        @else
                            <div class="status-icon text-danger">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <h2 class="font-weight-bold mb-3">عذراً، لم يكتمل الدفع</h2>
                            <p class="text-muted">يبدو أن هناك مشكلة حدثت أثناء الدفع، لم يتم خصم أي مبالغ من حسابك.</p>
                        @endif

                        <div class="invoice-box shadow-sm">
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">رقم الفاتورة:</span>
                                <span class="font-weight-bold">#{{ $invoiceId ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">تاريخ العملية:</span>
                                <span>{{ now()->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            @if ($status == 'success')
                                <a href="{{ url('/dashboard') }}" class="btn btn-github">الذهاب للوحة التحكم</a>
                            @else
                                <a href="{{ url('/wallet') }}" class="btn btn-outline-dark">المحاولة مرة أخرى</a>
                            @endif
                        </div>
                    </div>
                </div>

                <p class="mt-4 text-muted small">
                    إذا واجهت أي مشكلة، يرجى مراسلة الدعم الفني برقم الفاتورة الموضح أعلاه.
                </p>
            </div>
        </div>
    </div>

</body>

</html>
