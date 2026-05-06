@extends('adminlte::page')

@section('title', 'التأشيرات')

@section('content_header')
    <h1>كل طلبات التأشيرة</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">الطلبات</h3>

            <a href="/visas/create" class="btn btn-primary btn-sm">
                + إضافة طلب جديد
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الكفيل</th>
                        <th>رقم الهوية</th>
                        <th>نوع التأشيرة</th>
                        <th>رقم التأشيرة</th>
                        <th>القنصلية</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($visas as $visa)
                        <tr>
                            <td>{{ $visa->id }}</td>
                            <td>{{ $visa->sponsor_full_name }}</td>
                            <td>{{ $visa->sponsor_identity_number }}</td>
                            <td>{{ $visa->visa_type }}</td>
                            <td>{{ $visa->visa_number }}</td>
                            @php
                                $consulates = [
                                    1 => 'السويس',
                                    2 => 'القاهرة',
                                    3 => 'الاسكندرية',
                                ];
                            @endphp

                            <td>
                                {{ $consulates[$visa->consulate_name] ?? 'غير محدد' }}
                            </td>
                            <td>
                                <a href="/visas/{{ $visa->id }}/edit" class="btn btn-warning btn-sm">
                                    تعديل
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop
