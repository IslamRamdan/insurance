@extends('adminlte::page')

@section('title', 'قائمة طلبات التأشيرة')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">📜 كل طلبات التأشيرة</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="/visas/create" class="btn btn-success shadow-sm">
                    <i class="fas fa-plus-circle"></i> إضافة طلب جديد
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-primary">
                <i class="fas fa-list-ul mr-1"></i> قائمة الطلبات الحالية
            </h3>
            <div class="card-tools">
                <!-- اختياري: إضافة خانة بحث سريعة هنا -->
                <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="بحث...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light text-center">
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>اسم الكفيل</th>
                            <th>رقم الهوية</th>
                            <th>نوع التأشيرة</th>
                            <th>رقم التأشيرة</th>
                            <th>القنصلية</th>
                            <th style="width: 150px">الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @forelse ($visas as $visa)
                            <tr>
                                <td class="align-middle font-weight-bold text-muted">{{ $visa->id }}</td>
                                <td class="align-middle">
                                    <span class="d-block font-weight-600 text-dark">{{ $visa->sponsor_full_name }}</span>
                                </td>
                                <td class="align-middle"><code
                                        class="text-indigo">{{ $visa->sponsor_identity_number }}</code></td>
                                <td class="align-middle">
                                    <span class="badge badge-info px-3 py-2" style="font-size: 0.85rem;">
                                        {{ $visa->visa_type }}
                                    </span>
                                </td>
                                <td class="align-middle font-weight-bold">{{ $visa->visa_number }}</td>
                                @php
                                    $consulates = [
                                        1 => '📍 السويس',
                                        2 => '📍 القاهرة',
                                        3 => '📍 الاسكندرية',
                                    ];
                                @endphp
                                <td class="align-middle text-secondary">
                                    {{ $consulates[$visa->consulate_name] ?? '⚠️ غير محدد' }}
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group">
                                        <a href="/visas/{{ $visa->id }}/edit"
                                            class="btn btn-sm btn-outline-warning mx-1" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center text-muted">
                                    <i class="fas fa-folder-open fa-3x d-block mb-2"></i>
                                    لا توجد طلبات تأشيرة مسجلة حالياً.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table thead th {
            border-top: 0;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            color: #555;
        }

        .font-weight-600 {
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 108, 53, 0.03);
            /* لمسة أخضر خفيفة عند الهوفر */
            transition: 0.3s;
        }

        .btn-group .btn {
            border-radius: 8px !important;
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
