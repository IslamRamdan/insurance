<?php

namespace App\Http\Controllers;

use App\Models\VisaApplication;
use App\Models\VisaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

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

    public function engaz($id, Request $request)
    {
        # code...
        $visaRequest = VisaRequest::findOrFail($id);
        $visaRequest->e_number = $request->input('e_number');
        $visaRequest->save();
        return response()->json(['message' => 'تم تحديث حالة إنجاز بنجاح']);
    }

    public function submit($id)
    {

        set_time_limit(300); // 5 دقائق
        // 1. جلب بيانات العميل والطلب (تأكد من وجود العلاقات في الموديل)
        $customer = VisaRequest::with('visaApplication')->findOrFail($id);
        $application = $customer->visaApplication;

        if ($customer->user->visa_balance <= 0) {
            # code...
            return redirect()->route('dashboard')->with('error', 'رصيد التأشيرات الخاص بك غير كافٍ لحجز هذا الطلب. يرجى إعادة شحن رصيدك.');
        }
        $customer->user->visa_balance -= 1; // خصم تأشيرة واحدة من رصيد المستخدم
        $customer->user->save();

        // 2. حساب NumberEntryDay و ResidencyInKSA
        $numberEntryDay = "90";
        $residencyInKSA = "120";

        if ($customer->visa_peroid === "تأشيرة العمل المؤقت لخدمات الحج والعمرة") {
            $numberEntryDay = "90";
            $residencyInKSA = "120";
        } elseif ($customer->visa_peroid === "عمل") {
            $numberEntryDay = "90";
            $residencyInKSA = "90";
        } elseif ($customer->visa_peroid === "عمل مؤقت") {
            $numberEntryDay = "365";
            $residencyInKSA = "90";
        }

        // 3. تحديد السفارة
        $consulates = [
            1 => "السويس",
            2 => "القاهرة",
            3 => "الاسكندرية",
        ];
        $embassyCode = $consulates[$application->consulate_name] ?? "";
        // في وحدة التحكم (Controller) بـ Laravel
        $imagePath = storage_path('app/public/' . ($customer->image ?? ''));
        $base64Image = null;

        if (file_exists($imagePath)) {
            $type = pathinfo($imagePath, PATHINFO_EXTENSION);
            $data = file_get_contents($imagePath);
            $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // أرسل هذا المتغير في المصفوفة


        // 4. تجهيز البيانات للإرسال
        $data = [
            'email' => $customer->email ?? "erfa20045@gmail.com",
            'customer_id' => $customer->id,
            'UserName' => $customer->user->engaz_email ?? "", // تأكد من المسميات في قاعدة بياناتك
            'Password' => $customer->user->engaz_password ?? "",
            'VisaKind' => $application->visa_type ?? "",
            'DocumentNumber' => $application->visa_number ?? "",
            'NATIONALITY' => "EGY",
            'ResidenceCountry' => "272",
            'EmbassyCode' => $embassyCode,
            'NumberOfEntries' => "0",
            'NumberEntryDay' => $numberEntryDay,
            'ResidencyInKSA' => $residencyInKSA,
            // في Laravel
            // 'image_path' => storage_path('app/public/' . ($customer->image ?? '')),
            'image_base64' => $base64Image,

            'AFIRSTNAME' => trim(implode(' ', array_filter([
                $customer->a_first_name,
                $customer->a_father,
                $customer->a_grand,
                $customer->a_family
            ]))),
            'AFATHER' => $customer->a_father ?? "",
            'AGRAND' => $customer->a_grand ?? "",
            'AFAMILY' => $customer->a_family ?? "",
            'EFIRSTNAME' => $customer->e_first_name ?? "",
            'EFATHER' => $customer->e_father ?? "",
            'EGRAND' => $customer->e_grand ?? "",
            'EFAMILY' => $customer->e_family ?? "",
            'PASSPORTnumber' => $customer->passport_number ?? "",
            'PASSPORType' => "1",
            'PASSPORT_ISSUE_PLACE' => "مصر",
            'PASSPORT_ISSUE_DATE' => $customer->passport_issue_date ? Carbon::parse($customer->passport_issue_date)->format('Y-m-d') : "",
            'PASSPORT_EXPIRY_DATE' => $customer->passport_expiry_date ? Carbon::parse($customer->passport_expiry_date)->format('Y-m-d') : "",
            'BIRTH_PLACE' => $customer->birth_place ?? "",
            'BIRTH_DATE' => $customer->birth_date ? Carbon::parse($customer->birth_date)->format('Y-m-d') : "",
            'PersonId' => $customer->card_id ?? "",
            'DEGREE' => "-",
            'DEGREE_SOURCE' => "-",
            'ADDRESS_HOME' => "بحره",
            'Personal_Email' => $customer->email ?? "erfa20045@gmail.com",
            'SPONSER_NAME' => $application->sponsor_full_name ?? "",
            'SPONSER_NUMBER' => $application->sponsor_identity_number ?? "",
            'SPONSER_ADDRESS' => 'جده',
            'SPONSER_PHONE' => '01228815901',
            'COMING_THROUGH' => "2",
            'ENTRY_POINT' => "1",
            'ExpectedEntryDate' => Carbon::now()->addMonths(2)->format('d/m/Y'),
            'purpose' => "عمل لدى " . ($application->sponsor_full_name ?? ""),
            'car_number' => "SV123",
            'RELIGION' => $customer->religion ?? "1",
            'SOCIAL_STATUS' => "2",
            'Sex' => $customer->sex ?? "1",
            'JOB_OR_RELATION_Id' => $customer->job_or_relation_id ?? ""
        ];
        // dd($data);

        // 5. إرسال الطلب عبر Http Client
        try {
            $response = Http::timeout(0)->post('https://jury-channel-laboring.ngrok-free.dev/submit-all', $data);

            if ($response->successful() && $response->json('success')) {
                // return response()->json([
                //     'status' => 'success',
                //     'message' => 'تم إصدار طلب إنجاز بنجاح',
                //     'appNo' => $response->json()['appNo'] ?? 'N/A'
                // ]);
                return redirect()->route('dashboard')->with('success', 'تم إصدار طلب إنجاز بنجاح. ' . ($response->json()['appNo'] ?? 'N/A'));
            }

            // return response()->json(['status' => 'error', 'message' => 'فشل في الاتصال بالسيرفر الخارجي'], 500);
            return redirect()->route('dashboard')->with('error', "تاكد اذا كان تم الحجز ام لا , واذا حدث مشكلة تواصل مع الدعم");
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error',  "تاكد اذا كان تم الحجز ام لا , واذا حدث مشكلة تواصل مع الدعم");
            // return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
