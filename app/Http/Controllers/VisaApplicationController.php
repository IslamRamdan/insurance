<?php

namespace App\Http\Controllers;

use App\Models\VisaApplication;
use Illuminate\Http\Request;

class VisaApplicationController extends Controller
{
    // صفحة الإنشاء
    public function create()
    {
        return view('visas.create');
    }

    // حفظ جديد
    public function store(Request $request)
    {
        $data = $request->validate([
            'sponsor_full_name' => 'nullable|string',
            'sponsor_identity_number' => 'nullable|string',
            'visa_type' => 'nullable|string',
            'visa_number' => 'nullable|string',
            'consulate_name' => 'nullable|string',
        ]);
        $data['user_id'] = auth()->id();

        VisaApplication::create($data);

        return redirect('/visas/create')->with('success', 'تم الإنشاء بنجاح');
    }

    // صفحة التعديل
    public function edit(VisaApplication $visa)
    {
        if ($visa->user_id !== auth()->id()) {
            abort(403);
        }
        return view('visas.edit', compact('visa'));
    }

    // تحديث البيانات
    public function update(Request $request, VisaApplication $visa)
    {
        $data = $request->validate([
            'sponsor_full_name' => 'nullable|string',
            'sponsor_identity_number' => 'nullable|string',
            'visa_type' => 'nullable|string',
            'visa_number' => 'nullable|string',
            'consulate_name' => 'nullable|string',
        ]);

        $visa->update($data);

        return redirect()->back()->with('success', 'تم التعديل بنجاح');
    }
    public function index()
    {
        $visas = VisaApplication::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('visas.index', compact('visas'));
    }
    public function customers($id)
    {
        # code...

        $visa = VisaApplication::findOrFail($id);
        if ($visa->user_id !== auth()->id()) {
            abort(403);
        }
        $visaRequests = $visa->requests()->get();
        return view('customers', compact('visaRequests', 'visa'));
    }
}
