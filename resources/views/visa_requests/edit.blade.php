@extends('adminlte::page')

@section('title', 'تعديل طلب تأشيرة')

@section('content')

    <div class="content-wrapper" dir="rtl" style="margin: 0">

        <section class="content-header">
            <div class="container-fluid">
                <h1>تعديل طلب تأشيرة</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                {{-- Alerts --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6>يرجى تصحيح الأخطاء:</h6>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('visa_requests.update', $customer->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- الصور -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">المستندات</h3>
                        </div>
                        <div class="card-body row">

                            <div class="col-md-6 form-group">
                                <label>الصورة الشخصية</label>
                                <input type="file" name="image" class="form-control">

                                @if ($customer->image)
                                    <img src="{{ asset('storage/' . $customer->image) }}" width="120"
                                        class="mt-2 img-thumbnail">
                                @endif
                            </div>

                            <div class="col-md-6 form-group">
                                <label>صورة الجواز</label>
                                <input type="file" name="image_passport" class="form-control">

                                @if ($customer->image_passport)
                                    <img src="{{ asset('storage/' . $customer->image_passport) }}" width="120"
                                        class="mt-2 img-thumbnail">
                                @endif
                            </div>

                        </div>
                    </div>

                    <!-- الاسم بالعربي -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">الاسم بالعربي</h3>
                        </div>
                        <div class="card-body row">

                            @foreach (['a_first_name' => 'الأول', 'a_father' => 'الأب', 'a_grand' => 'الجد', 'a_family' => 'العائلة'] as $field => $label)
                                <div class="col-md-3 form-group">
                                    <label>{{ $label }}</label>
                                    <input name="{{ $field }}" class="form-control" value="{{ $customer->$field }}">
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <!-- الاسم بالانجليزي -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">الاسم بالإنجليزي</h3>
                        </div>
                        <div class="card-body row">

                            @foreach (['e_first_name' => 'First', 'e_father' => 'Father', 'e_grand' => 'Grand', 'e_family' => 'Family'] as $field => $label)
                                <div class="col-md-3 form-group">
                                    <label>{{ $label }}</label>
                                    <input name="{{ $field }}" class="form-control" value="{{ $customer->$field }}">
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <!-- بيانات الجواز -->
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">بيانات الجواز</h3>
                        </div>
                        <div class="card-body row">

                            @php
                                $fields = [
                                    'passport_number' => 'رقم الجواز',
                                    'card_id' => 'رقم البطاقة',
                                    'passport_issue_place' => 'جهة الإصدار',
                                    'birth_place' => 'مكان الميلاد',
                                ];
                            @endphp

                            @foreach ($fields as $field => $label)
                                <div class="col-md-4 form-group">
                                    <label>{{ $label }}</label>
                                    <input name="{{ $field }}" class="form-control"
                                        value="{{ $customer->$field }}">
                                </div>
                            @endforeach

                            <div class="col-md-4 form-group">
                                <label>تاريخ إصدار الجواز</label>
                                <input type="date" name="passport_issue_date" class="form-control"
                                    value="{{ optional($customer->passport_issue_date)->format('Y-m-d') }}">
                            </div>

                            <div class="col-md-4 form-group">
                                <label>تاريخ انتهاء الجواز</label>
                                <input type="date" name="passport_expiry_date" class="form-control"
                                    value="{{ optional($customer->passport_expiry_date)->format('Y-m-d') }}">
                            </div>

                            <div class="col-md-4 form-group">
                                <label>تاريخ الميلاد</label>
                                <input type="date" name="birth_date" class="form-control"
                                    value="{{ optional($customer->birth_date)->format('Y-m-d') }}">
                            </div>

                        </div>
                    </div>

                    <!-- إضافي -->
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">بيانات إضافية</h3>
                        </div>
                        <div class="card-body row">

                            <div class="col-md-6 form-group">
                                <label>النوع</label>
                                <select name="sex" class="form-control">
                                    <option value="1" {{ $customer->sex == 1 ? 'selected' : '' }}>ذكر</option>
                                    <option value="2" {{ $customer->sex == 2 ? 'selected' : '' }}>أنثى</option>
                                </select>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>الوظيفة</label>
                                <select name="job_or_relation_id" id="jobSelect" class="form-control select2-custom"
                                    data-selected="{{ $customer->job_or_relation_id }}">
                                    <option value="">ابحث عن مهنة...</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="text-center mb-5">
                        <button class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> حفظ التعديلات
                        </button>
                    </div>

                </form>

            </div>
        </section>

    </div>






@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* تحسين مظهر Select2 ليناسب قوالب الإدارة */
        .select2-container--default .select2-selection--single {
            border: 1px solid #ced4da !important;
            height: calc(2.25rem + 2px) !important;
            /* نفس ارتفاع input الـ Bootstrap */
            border-radius: 0.25rem !important;
            padding: 3px 6px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px !important;
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
    <!-- JS (بعد jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            const $select = $('#jobSelect');
            // القيمة المسجلة مسبقاً (التي ستكون نص المهنة الآن)
            const selectedValue = $select.data('selected');

            $select.select2({
                placeholder: "🔍 ابحث عن مهنة (مثلاً: سائق، مهندس...)",
                allowClear: true,
                width: '100%',
                minimumInputLength: 1,
                language: {
                    inputTooShort: function() {
                        return "ادخل حرف واحد أو أكثر للبحث...";
                    },
                    noResults: function() {
                        return "لم يتم العثور على هذه المهنة";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });

            // جلب البيانات من jops.json وتعبئتها
            fetch('/jops.json')
                .then(response => response.json())
                .then(data => {
                    data.forEach(job => {
                        if (job.Text && !job.Disabled) {
                            // التعديل هنا: نقارن النص المختار بالنص الحالي في اللوب
                            const isSelected = (job.Text == selectedValue);

                            // التعديل هنا: نجعل المعامل الثاني (Value) هو job.Text
                            const option = new Option(job.Text, job.Text, isSelected, isSelected);

                            $select.append(option);
                        }
                    });

                    // تحديث السليكت ليعكس الاختيار
                    $select.trigger('change');
                })
                .catch(err => console.error("Error loading jobs:", err));
        });
    </script>
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
