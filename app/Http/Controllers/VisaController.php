<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FawaterkService;
use App\Models\VisaTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $totalPrice = $request->input('visa_qty') * 10;

        $apiToken = env('FAWATERK_API_KEY');
        $apiUrl = 'https://app.fawaterk.com/api/v2/createInvoiceLink';

        $response = Http::withToken($apiToken)->post($apiUrl, [
            "cartTotal" => $totalPrice,
            "currency"  => "EGP",
            "customer"  => [
                "first_name" => $user->name ?? "User",
                "email"      => $user->email ?? "user@example.com",
                "phone"      => $user->phone ?? "01228815901",
            ],
            "redirectionUrls" => [
                "successUrl" => route('payment.success'),
                "failUrl"    => route('payment.fail'),
                "pendingUrl" => route('payment.fail')
            ],
            "cartItems" => [
                [
                    "name"     => "شحن رصيد تأشيرات",
                    "price"    => 10,
                    "quantity" => (int)$request->input('visa_qty')
                ]
            ]
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['data']['url'])) {
            VisaTransaction::create([
                'user_id'             => $user->id,
                'amount'              => $totalPrice,
                'visa_count'          => $request->input('visa_qty'),
                'status'              => 'pending',
                // استخدمنا invoice_id للتوافق مع راد API فواتيرك
                'fawaterk_invoice_id' => $data['data']['invoice_id'] ?? $data['data']['invoiceId'],
            ]);

            return redirect()->away($data['data']['url']);
        }

        return back()->with('error', 'خطأ في بوابة الدفع');
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
        $fawaterkHash = $request->input('hashKey');

        $invoiceId   = $request->input('invoice_id');
        $status      = $request->input('invoice_status');
        $paidAmount  = $request->input('paidAmount');
        $customerEmail = data_get($request, 'customerData.customer_email');

        // الـ Key بتاعك اللي بيبدأ بـ FAWATERAK
        $providerKey = "FAWATERAK.7869";

        // الترتيب الصارم للفواتير: ID + Status + Amount + Email + Key
        $stringToHash = $invoiceId . $status . $paidAmount . $customerEmail . $providerKey;
        $generatedHash = hash('sha256', $stringToHash);

        if ($generatedHash !== $fawaterkHash) {
            Log::error("Fawaterk Hash Mismatch!", [
                'string_used' => $stringToHash,
                'generated' => $generatedHash,
                'received' => $fawaterkHash
            ]);
            // نصيحة عشان "ترحم أمك": لو عايز العمليات تمشي والفلوس تتضاف حتى لو الـ Hash غلط 
            // مؤقتاً امسح الـ return اللي تحت دي وخلي الكود يكمل.
            return response()->json(['error' => 'Invalid Hash'], 400);
        }

        // شحن الرصيد
        if ($status === 'paid') {
            $transaction = VisaTransaction::where('fawaterk_invoice_id', $invoiceId)->first();
            if ($transaction && $transaction->status !== 'paid') {
                \DB::transaction(function () use ($transaction) {
                    $transaction->update(['status' => 'paid']);
                    $user = $transaction->user;
                    if ($user) {
                        $user->visa_balance = $user->visa_balance + $transaction->visa_count;
                        $user->save();
                    }
                    \Log::info("User Balance Updated Successfully", [
                        'user_id' => $user->id,
                        'old_balance' => $user->getOriginal('visa_balance'), // الرصيد قبل الحفظ
                        'new_balance' => $user->visa_balance,                // الرصيد بعد الحفظ
                        'added_amount' => $transaction->visa_count
                    ]);
                });
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function paymentSuccess(Request $request)
    {
        $invoiceId = $request->query('invoice_id');

        if ($invoiceId) {
            VisaTransaction::where('fawaterk_invoice_id', $invoiceId)
                ->where('status', 'pending')
                ->update(['status' => 'paid']); // استخدمنا 'paid' بدل 'completed' لحل مشكلة الـ Truncated
        }

        return view('visa.status', [
            'status'  => 'success',
            'message' => 'تمت العملية بنجاح!',
            'invoiceId' => $invoiceId
        ]);
    }
    public function paymentFail(Request $request)
    {
        $invoiceId = $request->query('invoice_id');

        if ($invoiceId) {
            VisaTransaction::where('fawaterk_invoice_id', $invoiceId)
                ->where('status', 'pending')
                ->update(['status' => 'failed']); // موجودة في الـ enum
        }

        return view('visa.status', [
            'status'  => 'error',
            'message' => 'فشلت عملية الدفع.',
            'invoiceId' => $invoiceId
        ]);
    }
    public function showRechargePage()
    {
        return view('visa.wallet'); // اسم ملف الـ Blade اللي فيه الفورم بتاعك
    }
}
