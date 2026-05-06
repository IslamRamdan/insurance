<?php

namespace App\Http\Controllers;

use App\Models\VisaApplication;
use App\Models\VisaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class VisaRequestController extends Controller
{
    public function create()
    {
        // الوصول الصحيح لبيانات المستخدم عبر دالة user()
        if (auth()->user()->visa_balance <= 0) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'رصيد التأشيرات الخاص بك غير كافٍ لإنشاء طلب جديد. يرجى إعادة شحن رصيدك.');
        }
        $visaApplications = VisaApplication::where("user_id", auth()->id())->get();

        return view('visa_requests.create', compact('visaApplications'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_passport' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'a_first_name' => 'nullable|string',
            'a_father' => 'nullable|string',
            'a_grand' => 'nullable|string',
            'a_family' => 'nullable|string',

            'e_first_name' => 'nullable|string',
            'e_father' => 'nullable|string',
            'e_grand' => 'nullable|string',
            'e_family' => 'nullable|string',

            'passport_number' => 'nullable|string',
            'passport_issue_date' => 'nullable|date',
            'passport_expiry_date' => 'nullable|date',

            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'passport_issue_place' => 'nullable|string',

            'religion' => 'nullable|string',
            'sex' => 'nullable|string',
            'job_or_relation_id' => 'nullable|string',
            'visa_application_id' => 'nullable|exists:visa_applications,id',

        ]);
        $data['user_id'] = auth()->id();
        $data['visa_kind'] = $request->input('visa_peroid'); // تحويل visa_peroid إلى visa_kind
        $data['card_id'] = $request->input('card_id'); // تحويل visa_peroid إلى visa_kind
        $data['email'] = 'erfa20045@gmail.com';
        $data['visa_application_id'] = $request->input('visa_application_id');

        /* رفع الصورة الشخصية */
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('visa/images', 'public');
        }

        /* رفع صورة الجواز */
        if ($request->hasFile('image_passport')) {
            $data['image_passport'] = $request->file('image_passport')->store('visa/passports', 'public');
        }

        VisaRequest::create($data);
        auth()->user()->visa_balance -= 1;
        auth()->user()->save();

        return redirect()->route('dashboard')->with('success', 'تم تسجيل طلب التأشيرة بنجاح');
    }

    public function edit($id)
    {
        $customer = VisaRequest::findOrFail($id);
        if ($customer->user_id !== auth()->id()) {
            abort(403);
        }

        return view('visa_requests.edit', compact('customer'));
    }
    public function update(Request $request, VisaRequest $visaRequest)
    {
        $data = $request->validate([
            'email' => 'nullable|email',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|nullable|min:6',

            'visa_kind' => 'nullable|string',
            'embassy' => 'nullable|string',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_passport' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'a_first_name' => 'nullable|string',
            'a_father' => 'nullable|string',
            'a_grand' => 'nullable|string',
            'a_family' => 'nullable|string',

            'e_first_name' => 'nullable|string',
            'e_father' => 'nullable|string',
            'e_grand' => 'nullable|string',
            'e_family' => 'nullable|string',

            'passport_number' => 'nullable|string',
            'passport_issue_date' => 'nullable|date',
            'passport_expiry_date' => 'nullable|date',

            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'passport_issue_place' => 'nullable|string',

            'religion' => 'nullable|string',
            'sex' => 'nullable|string',
            'job_or_relation_id' => 'nullable|string',

            'sponser_name' => 'nullable|string',
            'sponser_number' => 'nullable|string',
        ]);
        $data['card_id'] = $request->input('card_id'); // تحويل visa_peroid إلى visa_kind

        if ($request->hasFile('image')) {
            if ($visaRequest->image) {
                Storage::disk('public')->delete($visaRequest->image);
            }
            $data['image'] = $request->file('image')->store('visa/images', 'public');
        }

        if ($request->hasFile('image_passport')) {
            if ($visaRequest->image_passport) {
                Storage::disk('public')->delete($visaRequest->image_passport);
            }
            $data['image_passport'] = $request->file('image_passport')->store('visa/passports', 'public');
        }

        unset($data['passport_number']); // 🔥 يمنع التعديل

        $visaRequest->update($data);
        return redirect()->route('dashboard')
            ->with('success', 'تم تحديث طلب التأشيرة بنجاح');
    }
}
