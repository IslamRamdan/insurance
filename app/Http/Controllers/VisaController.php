<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FawaterkService;
use App\Models\VisaTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class VisaController extends Controller
{
    protected $fawaterk;

    public function __construct(FawaterkService $fawaterk)
    {
        $this->fawaterk = $fawaterk;
    }

    // public function processRecharge(Request $request)
    // {
    //     $request->validate([
    //         'visa_qty' => 'required|integer|min:1',
    //     ]);

    //     $user = auth()->user();
    //     $totalPrice = $request->input('visa_qty') * 100;

    //     $apiToken = env('FAWATERK_API_KEY');
    //     $apiUrl = 'https://app.fawaterk.com/api/v2/createInvoiceLink';

    //     $response = Http::withToken($apiToken)
    //         ->post($apiUrl, [
    //             "cartTotal" => $totalPrice,
    //             "currency"  => "EGP",
    //             "customer"  => [
    //                 "first_name" => $user->name ?? "User",
    //                 "last_name"  => "Name",
    //                 "email"      => $user->email ?? "erfa20045@gmail.com",
    //                 "phone"      => $user->phone ?? "01228815901",
    //                 "address"    => "Egypt"
    //             ],
    //             "redirectionUrls" => [
    //                 "successUrl" => route('payment.success'),
    //                 "failUrl"    => route('payment.fail'),
    //                 "pendingUrl" => route('payment.fail')
    //             ],
    //             "cartItems" => [
    //                 [
    //                     "name"     => "شحن رصيد تأشيرات - VisaFlow Pro",
    //                     "price"    => 100,
    //                     "quantity" => (int)$request->input('visa_qty')
    //                 ]
    //             ]
    //         ]);

    //     $data = $response->json();

    //     // if ($response->successful() && isset($data['data']['url'])) {

    //     //     // --- إضافة الفاتورة في قاعدة البيانات هنا ---
    //     //     VisaTransaction::create([
    //     //         'user_id'             => $user->id,
    //     //         'amount'              => $totalPrice,
    //     //         'visa_count'          => $request->input('visa_qty'),
    //     //         'status'              => 'pending', // الحالة مبدئياً معلقة
    //     //         'fawaterk_invoice_id' => $data['data']['invoice_id'],
    //     //     ]);

    //     //     return redirect()->away($data['data']['url']);
    //     // }
    //     if ($response->successful() && isset($data['data']['url'])) {
    //         VisaTransaction::create([
    //             'user_id'             => $user->id,
    //             'amount'              => $totalPrice,
    //             'visa_count'          => $request->input('visa_qty'),
    //             'status'              => 'pending',
    //             // التعديل هنا: استخدام invoice_id وليس invoiceId
    //             'fawaterk_invoice_id' => $data['data']['invoice_id'],
    //         ]);

    //         return redirect()->away($data['data']['url']);
    //     }

    //     return back()->with('error', 'خطأ في الاتصال بالبوابة الحقيقية: ' . ($data['message'] ?? 'تحقق من الـ Token'));
    // }
    public function processRecharge(Request $request)
    {
        $request->validate([
            'visa_qty' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $totalPrice = $request->input('visa_qty') * 100;

        $apiToken = env('FAWATERK_API_KEY');
        $apiUrl = env('FAWATERK_URL');

        // إرسال الطلب لفواتيرك
        $response = Http::withOptions([
            'verify' => false, // لتجنب مشاكل شهادة الأمان في بعض السيرفرات
        ])->withToken($apiToken)
            ->post($apiUrl, [
                "cartTotal" => $totalPrice,
                "currency"  => "EGP",
                "customer"  => [
                    "first_name" => $user->name ?? "User",
                    "last_name"  => "Name",
                    "email"      => $user->email ?? "user@example.com",
                    "phone"      => $user->phone ?? "01000000000",
                    "address"    => "Egypt"
                ],
                "redirectionUrls" => [
                    "successUrl" => route('payment.success'),
                    "failUrl"    => route('payment.fail'),
                    "pendingUrl" => route('payment.fail')
                ],
                "cartItems" => [
                    [
                        "name"     => "شحن رصيد تأشيرات",
                        "price"    => 100,
                        "quantity" => (int)$request->input('visa_qty')
                    ]
                ]
            ]);

        $data = $response->json();

        if ($response->successful() && isset($data['data']['url'])) {
            // حفظ المعاملة في الداتابيز - التأكد من استخدام مفتاح invoice_id الصحيح
            VisaTransaction::create([
                'user_id'             => $user->id,
                'amount'              => $totalPrice,
                'visa_count'          => $request->input('visa_qty'),
                'status'              => 'pending',
                'fawaterk_invoice_id' => $data['data']['invoice_id'] ?? $data['data']['invoiceId'],
            ]);

            return redirect()->away($data['data']['url']);
        }

        return back()->with('error', 'خطأ في الاتصال ببوابة الدفع: ' . ($data['message'] ?? 'تحقق من الإعدادات'));
    }

    public function paymentFail(Request $request)
    {
        $invoiceId = $request->query('invoice_id');

        // تحديث الحالة لفاشلة في الداتابيز
        if ($invoiceId) {
            VisaTransaction::where('fawaterk_invoice_id', $invoiceId)
                ->where('status', 'pending')
                ->update(['status' => 'failed']);
        }

        return view('visa.status', [
            'status'    => 'error',
            'message'   => 'للأسف، فشلت عملية الدفع أو تم إلغاؤها.',
            'invoiceId' => $invoiceId
        ]);
    }

    // public function handleWebhook(Request $request)
    // {
    //     $data = $request->all();

    //     if (isset($data['invoice_id']) && $data['invoice_status'] == 'paid') {

    //         $transaction = VisaTransaction::where('fawaterk_invoice_id', $data['invoice_id'])->first();

    //         // لو العملية موجودة ولم يتم شحن الرصيد من قبل (مثلاً بنعرف ده من حقل آخر أو لو لسه الحالة مش completed)
    //         if ($transaction && $transaction->status !== 'completed_and_charged') {
    //             DB::transaction(function () use ($transaction) {

    //                 // تحديث الحالة لحالة نهائية تؤكد شحن الرصيد
    //                 $transaction->update(['status' => 'completed_and_charged']);

    //                 // شحن الرصيد الفعلي
    //                 $user = $transaction->user;
    //                 $user->increment('visa_balance', $transaction->visa_count);
    //             });
    //             return response()->json(['status' => 'success'], 200);
    //         }
    //     }
    //     return response()->json(['status' => 'ignored'], 200);
    // }

    public function handleWebhook(Request $request)
    {
        // 1. تسجيل البيانات الواردة لمراجعتها عند الحاجة
        Log::info('Fawaterk Webhook Payload:', $request->all());

        $invoiceId = $request->input('invoice_id');
        $status    = $request->input('invoice_status');
        $receivedHash = $request->header('hash_key');

        // التحقق من وجود البيانات الأساسية
        if (!$invoiceId || !$status) {
            Log::error('Fawaterk Webhook: Missing essential data.');
            return response()->json(['status' => 'error', 'message' => 'Missing data'], 400);
        }

        // 2. التحقق من الـ Hash لضمان الأمان (باستخدام الـ providerKey)
        $providerKey = env('FAWATERK_PROVIDER_KEY');
        $calculatedHash = hash('sha256', $invoiceId . $status . $providerKey);

        if ($receivedHash !== $calculatedHash) {
            Log::error('Fawaterk Webhook: Hash Mismatch!', [
                'received' => $receivedHash,
                'calculated' => $calculatedHash
            ]);
            return response()->json(['status' => 'error', 'message' => 'Invalid Hash'], 403);
        }

        // 3. تحديث الرصيد إذا كانت الحالة مدفوعة (paid)
        if ($status === 'paid') {
            $transaction = VisaTransaction::where('fawaterk_invoice_id', $invoiceId)->first();

            if ($transaction && $transaction->status !== 'completed_and_charged') {
                DB::transaction(function () use ($transaction) {
                    // تحديث حالة المعاملة
                    $transaction->update(['status' => 'completed_and_charged']);

                    // شحن رصيد العميل الفعلي
                    $user = $transaction->user;
                    if ($user) {
                        $user->increment('visa_balance', $transaction->visa_count);
                        Log::info("Success: Balance added to User ID: {$user->id}");
                    }
                });

                return response()->json(['status' => 'success'], 200);
            }
        }

        return response()->json(['status' => 'ignored'], 200);
    }

    public function paymentSuccess(Request $request)
    {
        $invoiceId = $request->query('invoice_id');

        // تحديث الحالة في الداتابيز فوراً عند رجوع العميل
        if ($invoiceId) {
            VisaTransaction::where('fawaterk_invoice_id', $invoiceId)
                ->where('status', 'pending')
                ->update(['status' => 'completed']);
        }

        return view('visa.status', [
            'status'    => 'success',
            'message'   => 'تمت عملية الدفع بنجاح! تم شحن محفظتك.',
            'invoiceId' => $invoiceId
        ]);
    }
    public function showRechargePage()
    {
        return view('visa.wallet'); // اسم ملف الـ Blade اللي فيه الفورم بتاعك
    }
}
