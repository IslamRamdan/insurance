@extends('adminlte::page')

@section('title', 'طلبات التأشيرة')

@section('content_header')
    <div class="row mb-3">
        @if (auth()->check() && (auth()->user()->engaz_password == null || auth()->user()->engaz_email == null))
            <div class="engaz-alert-bar">
                <div class="container-fluid d-flex justify-content-center align-items-center gap-3">
                    <span class="alert-text">
                        <i class="fas fa-exclamation-triangle ml-2"></i>
                        <strong>تنبيه:</strong> يجب ربط حساب إنجاز الخاص بك لتفعيل لتتمكن من الحجز .
                    </span>
                    <a href="{{ route('profile.edit') }}" class="btn-link-action">اربط الآن</a>
                </div>
            </div>
            <style>
                .engaz-alert-bar {
                    background-color: #fff3cd;
                    /* أصفر تحذيري هادئ */
                    border-bottom: 1px solid #ffeeba;
                    padding: 8px 0;
                    /* تقليل الارتفاع ليكون أنحف */
                    width: 100%;
                    position: sticky;
                    /* يظل ظاهراً عند التمرير */
                    top: 0;
                    z-index: 1050;
                    /* أعلى من الـ Navbar */
                    direction: rtl;
                    font-family: 'Cairo', sans-serif;
                    animation: slideDown 0.5s ease-out;
                }

                .alert-text {
                    color: #856404;
                    font-size: 0.9rem;
                    font-weight: bold;
                }

                .alert-text i {
                    color: #dc3545;
                    /* لون الأيقونة أحمر للتنبيه */
                }

                .btn-link-action {
                    background-color: #006C35;
                    color: #ffffff !important;
                    padding: 3px 15px;
                    border-radius: 20px;
                    /* شكل كبسولة عصري */
                    font-size: 0.8rem;
                    font-weight: 700;
                    text-decoration: none !important;
                    transition: 0.3s;
                    border: 1px solid transparent;
                }

                .btn-link-action:hover {
                    background-color: #ffffff;
                    color: #006C35 !important;
                    border-color: #006C35;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                }

                /* أنيميشن بسيط لدخول الشريط */
                @keyframes slideDown {
                    from {
                        transform: translateY(-100%);
                    }

                    to {
                        transform: translateY(0);
                    }
                }

                /* ضبط المسافات في الشاشات الصغيرة */
                @media (max-width: 576px) {
                    .engaz-alert-bar .container-fluid {
                        flex-direction: column;
                        gap: 5px;
                        text-align: center;
                    }
                }
            </style>
        @endif

        <!-- العنوان -->
        <div class="col-md-6 d-flex align-items-center">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-file-invoice text-primary mr-2"></i>
                طلبات التأشيرة
            </h1>
        </div>

        <!-- اليمين -->
        <div class="col-md-6 d-flex justify-content-md-end justify-content-start mt-3 mt-md-0">

            <!-- الرصيد -->
            <div class="card shadow-none border ml-2 mb-0 balance-card" style="border-radius: 12px; background: #f8f9fa;">
                <div class="card-body d-flex align-items-center p-1 pr-3 pl-2">

                    <!-- أيقونة المحفظة بتصميم دائري ناعم -->
                    <div class="balance-icon-container">
                        <i class="fas fa-wallet"></i>
                    </div>

                    <div class="mx-2">
                        <div class="text-uppercase tracking-wider mb-0"
                            style="font-size: 10px; color: #888; font-weight: 700; line-height: 1;">
                            الرصيد المتاح
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="font-weight-bold text-dark" style="font-size: 15px; letter-spacing: 0.5px;">
                                {{ number_format(auth()->user()->visa_balance ?? 0) }}
                                <small class="text-muted font-weight-normal" style="font-size: 10px;">نقطة</small>
                            </span>

                            <!-- زر الشحن بتصميم أنيق -->
                            <a href="{{ route('visa.recharge.view') }}" class="recharge-btn ml-2" title="شحن الرصيد">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <style>
                .balance-card {
                    border: 1px solid #e3e6f0 !important;
                    transition: all 0.3s ease;
                }

                .balance-card:hover {
                    border-color: #006C35 !important;
                    background: #fff !important;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
                }

                .balance-icon-container {
                    background-color: rgba(0, 108, 53, 0.1);
                    /* أخضر شفاف */
                    color: #006C35;
                    width: 34px;
                    height: 34px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 14px;
                }

                .recharge-btn {
                    background-color: #006C35;
                    color: white !important;
                    width: 20px;
                    height: 20px;
                    border-radius: 6px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 10px;
                    text-decoration: none !important;
                    transition: transform 0.2s ease;
                }

                .recharge-btn:hover {
                    transform: scale(1.1);
                    background-color: #004d26;
                }
            </style>

            <!-- زر إضافة -->
            <a href="{{ route('visa_requests.create') }}" class="btn btn-success shadow-sm" style="border-radius: 10px;">

                <i class="fas fa-user-plus mr-1"></i>
                تسجيل عميل
            </a>

        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">قائمة المسجلين</h3>
            <div class="card-tools">
                <a href="" class="btn btn-tool text-primary">
                    <i class="fas fa-history mr-1"></i> سجل المعاملات
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover table-striped text-center mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 80px">الصورة</th>
                        <th>الاسم الكامل (عربي)</th>
                        <th>e number</th>
                        @if (auth()->user()->email == 'eslam@gmail.com')
                            <th>اسم الشركة</th>
                        @endif
                        <th style="width: 150px">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visaRequests as $visa)
                        <tr>
                            <td>
                                @if ($visa->image)
                                    <img src="{{ asset('storage/' . $visa->image) }}" class="img-circle elevation-1"
                                        style="width: 45px; height: 45px; object-cover">
                                @else
                                    <div class="img-circle bg-gray d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 45px; height: 45px; font-size: 0.7rem">
                                        لا صورة
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle font-weight-bold">
                                {{ $visa->a_first_name }} {{ $visa->a_father }} {{ $visa->a_family }}
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-success">{{ $visa->e_number ?? 'في انتظار الحجز' }}</span>
                            </td>

                            @if (auth()->user()->email == 'eslam@gmail.com')
                                <td class="align-middle">
                                    {{ $visa->user->name ?? 'غير محدد' }}
                                </td>
                            @endif

                            <td class="align-middle">
                                <div class="btn-group">
                                    @if ($visa->e_number == null)
                                        <a href="{{ route('visa_requests.edit', $visa->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit mr-1"></i> تعديل
                                        </a>
                                        @if (auth()->user()->engaz_password != null && auth()->user()->engaz_email != null)
                                            {{-- <button class="btn btn-sm btn-success prepare-btn shadow-sm"
                                                title="تجهيز البيانات" data-customer='@json($visa)'
                                                data-engaz-email="{{ $visa->user->engaz_email ?? '' }}"
                                                data-engaz-password="{{ $visa->user->engaz_password ?? '' }}"
                                                data-application='@json($visa->visaApplication ?? [])'>
                                                <i class="fas fa-bolt"></i>
                                            </button> --}}

                                            <a href="{{ route('engaz.submit', $visa->id) }}" id="reserveBtn"
                                                class="btn btn-sm btn-success prepare-btn shadow-sm">
                                                <span id="btnText">حجز</span>
                                                <span id="btnLoader" style="display: none;">
                                                    <i class="fas fa-spinner fa-spin"></i> جاري التحويل...
                                                </span>
                                            </a>
                                        @endif
                                    @else
                                        <span class="badge badge-pill shadow-sm px-3 py-2"
                                            style="background-color: #006C35; color: white; font-weight: 600; font-size: 0.85rem;">
                                            <i class="fas fa-check-circle mr-1"></i> تم الحجز
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                لا توجد طلبات حالياً
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* إضافة لمسات جمالية بسيطة */
        .table td,
        .table th {
            vertical-align: middle !important;
        }

        .info-box {
            border-radius: 10px;
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll(".prepare-btn");

            buttons.forEach(btn => {
                btn.addEventListener("click", async () => {
                    const customer = JSON.parse(btn.getAttribute("data-customer"));

                    const name_ar = [customer.a_first_name, customer.a_father, customer.a_grand,
                        customer.a_family
                    ];
                    const name_en = [customer.e_first_name, customer.e_father, customer.e_grand,
                        customer.e_family
                    ];

                    // حساب NumberEntryDay و ResidencyInKSA حسب نوع التأشيرة
                    let NumberEntryDay = "90";
                    let ResidencyInKSA = "120";
                    let porpose = '';

                    if (customer.visa_peroid === "تأشيرة العمل المؤقت لخدمات الحج والعمرة") {
                        NumberEntryDay = "90";
                        ResidencyInKSA = "120";
                        porpose = 'عمل موسمي لدي ' + customer.sponser_name;
                    } else if (customer.visa_peroid === "عمل") {
                        NumberEntryDay = "90";
                        ResidencyInKSA = "90";
                        porpose = 'عمل لدي ' + customer.sponser_name;
                    } else if (customer.visa_peroid === "عمل مؤقت") {
                        NumberEntryDay = "365";
                        ResidencyInKSA = "90";
                        porpose = 'عمل لدي ' + customer.sponser_name;
                    }
                    let embassy = '';

                    // return console.log(customer.embassy);
                    if (customer.embassy == 1) {
                        embassy = 'السويس';
                    } else if (customer.embassy == 2) {
                        embassy = 'القاهرة';
                    } else if (customer.embassy == 3) {
                        embassy = 'الاسكندرية';
                    } else {
                        embassy = 'غير محدد';
                    }

                    const PASSPORT_ISSUE_DATE = customer.passport_issue_date ?
                        new Date(customer.passport_issue_date).toISOString().slice(0, 10) :
                        "";

                    const PASSPORT_EXPIRY_DATE = customer.passport_expiry_date ?
                        new Date(customer.passport_expiry_date).toISOString().slice(0, 10) :
                        "";

                    const data_engaz_email = btn.getAttribute("data-engaz-email");
                    const data_engaz_password = btn.getAttribute("data-engaz-password");
                    const dataApplication = JSON.parse(btn.getAttribute("data-application"));
                    const consulates = {
                        1: "السويس",
                        2: "القاهرة",
                        3: "الاسكندرية",
                    };


                    const data = {
                        email: customer.email || "erfa20045@gmail.com",
                        customer_id: customer.id,
                        UserName: data_engaz_email || "",
                        Password: data_engaz_password || "",
                        VisaKind: dataApplication.visa_type || "",
                        DocumentNumber: dataApplication.visa_number ||
                            "",
                        NATIONALITY: "EGY",
                        ResidenceCountry: "272",
                        EmbassyCode: consulates[dataApplication.consulate_name] || "",
                        NumberOfEntries: "0",
                        NumberEntryDay: NumberEntryDay,
                        ResidencyInKSA: ResidencyInKSA,
                        imageUrl: `{{ asset('storage') }}/${customer.image || ''}`,
                        AFIRSTNAME: customer.a_first_name || "",
                        AFIRSTNAME: [
                            customer.a_first_name,
                            customer.a_father,
                            customer.a_grand,
                            customer.a_family
                        ].filter(Boolean).join(" "),
                        AFATHER: customer.a_father || "",
                        AGRAND: customer.a_grand || "",
                        AFAMILY: customer.a_family || "",
                        EFIRSTNAME: customer.e_first_name || "",
                        EFATHER: customer.e_father || "",
                        EGRAND: customer.e_grand || "",
                        EFAMILY: customer.e_family || "",
                        PASSPORTnumber: customer.passport_number || "",
                        PASSPORType: "1",
                        PASSPORT_ISSUE_PLACE: "مصر",
                        PASSPORT_ISSUE_DATE: PASSPORT_ISSUE_DATE || "",
                        PASSPORT_EXPIRY_DATE: PASSPORT_EXPIRY_DATE || "",
                        BIRTH_PLACE: customer.birth_place || "",
                        BIRTH_DATE: customer.birth_date ? new Date(customer.birth_date)
                            .toISOString().slice(0, 10) : "",
                        PersonId: customer.card_id || "",
                        DEGREE: "-",
                        DEGREE_SOURCE: "-",
                        ADDRESS_HOME: "بحره",
                        Personal_Email: customer.email || "erfa20045@gmail.com",
                        SPONSER_NAME: dataApplication.sponsor_full_name || "",
                        SPONSER_NUMBER: dataApplication.sponsor_identity_number || "",
                        SPONSER_ADDRESS: 'جده' ||
                            "",
                        SPONSER_PHONE: '01228815901' || "",
                        COMING_THROUGH: "2",
                        ENTRY_POINT: "1",
                        ExpectedEntryDate: new Date(new Date().setMonth(new Date()
                                .getMonth() +
                                2))
                            .toLocaleDateString('en-GB'),
                        // استخدام Template Literals لدمج النصوص بشكل أنيق
                        purpose: `عمل لدى ${dataApplication.sponsor_full_name || ""}`
                            .trim(),
                        car_number: "SV123",
                        RELIGION: customer.religion || "1",
                        SOCIAL_STATUS: "2",
                        Sex: customer.sex || "1",
                        JOB_OR_RELATION_Id: customer.job_or_relation_id || ""
                    };

                    console.log("Prepared Data:", data);
                    const res = await fetch(
                        'https://jury-channel-laboring.ngrok-free.dev/submit-all', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        });

                    const response = await res.json();
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }

                    if (res.ok) {
                        await new Promise((resolve) => {
                            Swal.fire({
                                title: "نجحت العملية!",
                                text: `تم إصدار طلب إنجاز للعميل: ${customer.name}\nرقم الطلب: ${response.appNo}`,
                                icon: "success",
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                didClose: resolve // ⬅️ هنا يتم تنفيذ resolve عند غلق الرسالة
                            });
                        });

                    } else {
                        await new Promise((resolve) => {
                            Swal.fire({
                                title: "فشلت العملية!",
                                text: "لم يتم إصدار الطلب للعميل: " + customer
                                    .name,
                                icon: "error",
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                didClose: resolve
                            });
                        });
                    }
                });
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            // التحقق من وجود رسالة خطأ في الجلسة (Session)
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'عذراً...',
                    text: "{{ session('error') }}",
                    confirmButtonText: 'موافق',
                    confirmButtonColor: '#d33', // لون أحمر متناسق
                    customClass: {
                        popup: 'github-style-popup' // يمكنك إضافة تنسيق مخصص هنا
                    }
                });
            @endif

            // يمكنك أيضاً إضافة رسائل النجاح بنفس الطريقة
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'ممتاز',
                    confirmButtonColor: '#2ea44f' // أخضر GitHub
                });
            @endif
        });
    </script>
    <script>
        document.getElementById('reserveBtn').addEventListener('click', function(e) {
            // 1. التحقق إذا كان الزر يحتوي على كلاس disabled أو تم الضغط عليه مسبقاً
            if (this.classList.contains('disabled') || this.getAttribute('data-clicked') === 'true') {
                e.preventDefault(); // منع الانتقال للرابط
                return false;
            }

            // 2. وسم الزر لمنع النقرات المتتالية في أجزاء من الثانية
            this.setAttribute('data-clicked', 'true');

            // 3. تغيير المحتوى البصري (إخفاء النص وإظهار اللودينج)
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');

            if (btnText) btnText.style.display = 'none';
            if (btnLoader) btnLoader.style.display = 'inline-block';

            // 4. تعطيل الزر نهائياً من الناحية البصرية والوظيفية
            this.classList.add('disabled'); // كلاس Bootstrap للتعطيل البصري
            this.style.pointerEvents = 'none'; // منع أي نقرات ماوس إضافية برمجياً
            this.style.opacity = '0.7'; // زيادة الشفافية للتأكيد على التعطيل
            this.style.cursor = 'not-allowed'; // تغيير شكل الماوس لعلامة "ممنوع"
        });
    </script>

    <script>
        function detectDevTools() {
            const start = performance.now();
            debugger; // لو DevTools مفتوح هيتأخر هنا
            const end = performance.now();

            if (end - start > 100) { // فرق زمني كبير يعني DevTools مفتوح
                document.body.innerHTML = `
    <h1 style="color:red; text-align:center; margin-top:20%; font-size:50px;">
        🚨 تم كشف فتح أدوات المطور 🚨
    </h1>
    <h2 style="text-align:center; font-size:30px;">
        لا تحاول العبث في الكود، هذا قد يؤدي إلى حظر حسابك!
    </h2>
    @if (auth()->user())
        <h3 style="text-align:center; font-size:20px;">
        من فضلك اغلق وضع المطور وقم بعمل ريفريش
    </h3>
    @endif
    `;
            }
        }

        setInterval(detectDevTools, 1000);
    </script>

    <!-- Start of Tawk.to Script -->
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
    <!-- End of Tawk.to Script -->
@stop
