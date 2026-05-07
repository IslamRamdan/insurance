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
});

Route::post('/fawaterk/webhook', [VisaController::class, 'handleWebhook'])->name('fawaterk.webhook');


require __DIR__ . '/auth.php';
Route::fallback(function () {
    return redirect('/');
});
