<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisaApplicationController;
use App\Http\Controllers\VisaController;
use App\Http\Controllers\VisaRequestController;
use App\Models\VisaRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/', function () {
//     return '
//     <!DOCTYPE html>
//     <html lang="ar" dir="rtl">
//     <head>
//         <meta charset="UTF-8">
//         <meta name="viewport" content="width=device-width, initial-scale=1.0">
//         <title>جاري التحديث | Updating</title>
//         <script src="https://cdn.tailwindcss.com"></script>
//         <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
//         <style>
//             body { font-family: "Tajawal", sans-serif; }
//             .loader {
//                 border-top-color: #3498db;
//                 animation: spinner 1.5s linear infinite;
//             }
//             @keyframes spinner {
//                 0% { transform: rotate(0deg); }
//                 100% { transform: rotate(360deg); }
//             }
//         </style>
//     </head>
//     <body class="bg-gray-50 flex items-center justify-center h-screen">
//         <div class="text-center p-8 bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 border-t-4 border-blue-500">
//             <!-- Icon / Logo -->
//             <div class="mb-6 flex justify-center">
//                 <div class="relative">
//                     <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-20 w-20"></div>
//                     <div class="absolute top-0 left-0 h-20 w-20 flex items-center justify-center">
//                         <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
//                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="id-11 5v-1m0 0l-1 1m1-1l1 1m-5 8V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-3M1 1l1 1m-1-1l-1 1"></path>
//                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
//                         </svg>
//                     </div>
//                 </div>
//             </div>

//             <h1 class="text-2xl font-bold text-gray-800 mb-4">جاري التحديث الآن</h1>
//             <p class="text-gray-600 mb-8 leading-relaxed">
//                 نعمل حالياً على تحسين تجربتك وإضافة لمسات جديدة للموقع. سنعود إليكم قريباً جداً!
//             </p>

//             <div class="inline-block bg-blue-50 text-blue-700 px-4 py-2 rounded-full text-sm font-medium">
//                 شكراً لصبركم وثقتكم بنا
//             </div>

//             <div class="mt-8 text-xs text-gray-400 font-mono">
//                 &copy; ' . date("Y") . ' جميع الحقوق محفوظة
//             </div>
//         </div>
//     </body>
//     </html>';
// });

Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->email == "eslam@gmail.com") {
        // لو Admin (الإيميل)
        $visaRequests = VisaRequest::latest()->get();
    } else {
        // كل مستخدم يشوف طلباته فقط
        $visaRequests = VisaRequest::where('user_id', Auth::id())
            ->latest()
            ->get();
    }


    return view('dashboard', compact('visaRequests'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/engaz', [ProfileController::class, 'updateEngaz'])->name('profile.engaz.update');
});

Route::get('/visa-requests/create', [VisaRequestController::class, 'create'])
    ->name('visa_requests.create');

// تخزين الطلب الجديد
Route::post('/visa-requests', [VisaRequestController::class, 'store'])
    ->name('visa_requests.store');

Route::get('/visa-requests/edit/{id}', [VisaRequestController::class, 'edit'])
    ->name('visa_requests.edit');

Route::put(
    '/visa_requests/{visaRequest}',
    [VisaRequestController::class, 'update']
)->name('visa_requests.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/recharge', [VisaController::class, 'showRechargePage'])->name('visa.recharge.view');
    Route::post('/visa/recharge', [VisaController::class, 'processRecharge'])->name('visa.recharge');
    Route::get('/visa/success', [VisaController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/visa/fail', [VisaController::class, 'paymentFail'])->name('payment.fail');
    Route::get('/visa/transactions', [VisaController::class, 'paymentFail'])->name('visa.transactions');


    Route::get('/visas', [VisaApplicationController::class, 'index']);
    Route::get('/visas/create', [VisaApplicationController::class, 'create']);
    Route::post('/visas', [VisaApplicationController::class, 'store']);

    Route::get('/visas/{visa}/edit', [VisaApplicationController::class, 'edit']);
    Route::put('/visas/{visa}', [VisaApplicationController::class, 'update']);

    Route::get('/submit-engaz/{id}', [VisaRequestController::class, 'submit'])->name('engaz.submit');
});

Route::post('/fawaterk/webhook', [VisaController::class, 'handleWebhook'])->name('fawaterk.webhook');
Route::post('/engaz/{id}', [VisaRequestController::class, 'engaz']);


require __DIR__ . '/auth.php';
// Route::fallback(function () {
//     return redirect('/');
// });
