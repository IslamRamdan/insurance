@extends('adminlte::page')

@section('title', 'طلبات التأشيرة الخاصة بي')

@section('content_header')
    @if (auth()->check() && (auth()->user()->engaz_password == null || auth()->user()->engaz_email == null))
        <div class="engaz-alert-bar">
            <div class="container-fluid d-flex justify-content-center align-items-center gap-3">
                <span class="alert-text">
                    <i class="fas fa-exclamation-triangle ml-2"></i>
                    <strong>تنبيه:</strong> يجب ربط حساب إنجاز الخاص بك لتتمكن من الحجز .
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
    <h1 class="text-dark font-weight-bold" style="font-family: 'Cairo', sans-serif;">
        <i class="fas fa-passport text-success mr-2"></i> قائمة طلبات التأشيرة الخاصة بك
    </h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- تم تغيير الكلاس إلى card-success ليعطي شريط أخضر علوي -->
                <div class="card card-success card-outline bg-white shadow-sm border-0">
                    <div class="card-header bg-white border-bottom-0 pt-4">
                        <h3 class="card-title text-success font-weight-bold" style="font-size: 1.2rem;">
                            <i class="fas fa-folder-open mr-2"></i> طلباتي الحالية
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        @if ($applications->count() > 0)
                            <table
                                class="table table-hover table-head-fixed text-nowrap text-center text-dark align-middle">
                                <thead>
                                    <tr class="saudi-table-header">
                                        <th>اسم الكفيل</th>
                                        <th>نوع التأشيرة</th>
                                        <th>رقم التأشيرة</th>
                                        <th>القنصلية</th>
                                        <th>عدد الطلبات الفرعية</th>
                                        <th>تاريخ التسجيل</th>
                                        @if (auth()->user()->email == 'eslam@gmail.com')
                                            <th>اسم الشركة</th>
                                        @endif
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $application)
                                        <tr>
                                            <td>{{ $application->sponsor_full_name }}</td>
                                            <td>
                                                <span class="badge saudi-badge-secondary px-3 py-2">
                                                    {{ $application->visa_type }}
                                                </span>
                                            </td>
                                            <td><code class="text-success font-weight-bold"
                                                    style="font-size: 1rem;">{{ $application->visa_number }}</code></td>
                                            @php
                                                $consulates = [
                                                    1 => '📍 السويس',
                                                    2 => '📍 القاهرة',
                                                    3 => '📍 الإسكندرية',
                                                ];
                                            @endphp

                                            <td class="align-middle">
                                                @if (isset($consulates[$application->consulate_name]))
                                                    <span class="badge px-3 py-2"
                                                        style="background-color: #E6F1EB; color: #006C35; font-weight: 600;">
                                                        {{ $consulates[$application->consulate_name] }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-danger border px-3 py-2">
                                                        ⚠️ غير محدد
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-pill saudi-badge-count px-2.5 py-1.5">
                                                    {{ $application->requests->count() }}
                                                </span>
                                            </td>
                                            <td class="text-muted">{{ $application->created_at->format('Y-m-d') }}</td>
                                            @if (auth()->user()->email == 'eslam@gmail.com')
                                                <td>{{ $application->user->name }}</td>
                                            @endif
                                            <td>
                                                <!-- زر تفاصيل مخصص باللون الأخضر والأبيض -->
                                                <a href="{{ route('customers', $application->id) }}"
                                                    class="btn btn-saudi btn-sm px-3 shadow-sm">
                                                    <i class="fas fa-eye ml-1"></i> عرض العملاء
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-5 text-center text-muted bg-white border rounded shadow-sm">
                                <i
                                    class="fas fa-clipboard-list fa-4x mb-3 text-success opacity-75 animate__animated animate__pulse animate__infinite"></i>
                                <h5 class="font-weight-bold text-dark mb-2" style="font-family: 'Cairo', sans-serif;">لا
                                    يوجد لديك أي طلبات مسجلة حالياً!</h5>
                                <p class="text-secondary mb-4" style="font-family: 'Cairo', sans-serif;">عند قيامك بتقديم
                                    طلب تأشيرة جديد، سيظهر في هذه المنصة فوراً.</p>

                                <!-- زر إضافة تأشيرة منسق بالهوية السعودية -->
                                <a href="{{ route('visas.create') }}"
                                    style="                                    color: #ffffff !important;
"
                                    class="btn btn-saudi-action px-4 py-2 font-weight-bold shadow-sm">
                                    <i class="fas fa-plus-circle ml-1"></i> إضافة تأشيرة جديدة
                                </a>
                            </div>
                            <style>
                                .btn-saudi-action {
                                    background-color: #006C35 !important;
                                    /* الأخضر السعودي الرسمي */
                                    color: #ffffff !important;
                                    border: none;
                                    border-radius: 8px;
                                    font-family: 'Cairo', sans-serif;
                                    transition: all 0.3s ease;
                                }

                                .btn-saudi-action:hover {
                                    background-color: #005429 !important;
                                    /* درجة أغمق عند التمرير */
                                    color: #ffffff !important;
                                    transform: translateY(-2px);
                                    /* رفعة خفيفة للزر تعطي إيحاء بالتفاعل */
                                    box-shadow: 0 4px 12px rgba(0, 108, 53, 0.2) !important;
                                }
                            </style>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@stop

@section('js')
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
@stop
@section('css')
    <style>
        /* استيراد خط كابرو ليعطي طابعاً رسمياً سعودياً */
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

        body,
        h1,
        h2,
        h3,
        th,
        td,
        .btn {
            font-family: 'Cairo', sans-serif !important;
        }

        /* درجات اللون الأخضر السعودي الرسمي */
        :root {
            --saudi-green: #006C35;
            --saudi-green-hover: #005429;
            --saudi-light-green: #E6F1EB;
        }

        /* تنسيق هيدر الجدول */
        .saudi-table-header th {
            background-color: var(--saudi-light-green) !important;
            color: var(--saudi-green) !important;
            font-weight: 700 !important;
            border-bottom: 2px solid #cbd5e1 !important;
        }

        /* الزر الأخضر بهوية سعودية */
        .btn-saudi {
            background-color: var(--saudi-green) !important;
            color: #ffffff !important;
            border: none;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-saudi:hover {
            background-color: var(--saudi-green-hover) !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        /* بادج نوع التأشيرة المخصص */
        .saudi-badge-secondary {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            font-weight: 600;
        }

        /* بادج العداد الأخضر الخفيف */
        .saudi-badge-count {
            background-color: var(--saudi-light-green);
            color: var(--saudi-green);
            font-weight: 700;
        }

        /* تأثير مريح عند تمرير الماوس على صفوف الجدول */
        .table-hover tbody tr:hover {
            background-color: #f8fafc !important;
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
