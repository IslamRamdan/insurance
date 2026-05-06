<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FawaterkService
{
  protected $baseUrl;
  protected $token;

  public function __construct()
  {
    // يفضل وضعهم في الـ .env لاحقاً
    $this->baseUrl = "https://staging.fawaterk.com/api/v2";
    $this->token = "d83a5d07aaeb8442dcbe259e6dae80a3f2e21a3a581e1a5acd";
  }

  public function createPaymentLink($user, $amount, $visaQty)
  {
    $response = Http::withHeaders([
      'Authorization' => 'Bearer ' . $this->token,
      'Content-Type'  => 'application/json',
      'Accept'        => 'application/json',
    ])->post($this->baseUrl . '/createInvoiceLink', [
      "cartTotal" => (string) $amount,
      "currency"  => "EGP",
      "customer"  => [
        "first_name" => $user->name,
        "last_name"  => "Customer",
        "email"      => $user->email,
        "phone"      => "01228815901", // أو رقم المستخدم لو متاح
      ],
      "cartItems" => [
        [
          "name"     => "شحن رصيد تأشيرات - عدد " . $visaQty,
          "price"    => (string) ($amount / $visaQty),
          "quantity" => (string) $visaQty
        ]
      ],
      "redirectionUrls" => [
        "returnUrl" => route('visa.wallet.callback'),
      ]
    ]);

    return $response->json();
  }
  public function initiatePayment($user, $amount, $visaQty, $methodId)
  {
    $response = Http::withHeaders([
      'Authorization' => 'Bearer ' . env('FAWATERK_API_KEY'),
      'Content-Type'  => 'application/json',
    ])->post(env('FAWATERK_BASE_URL') . '/invoiceInitPay', [
      "payment_method_id" => (int) $methodId,
      "cartTotal" => (string) $amount,
      "currency"  => "EGP",
      "customer"  => [
        "first_name" => $user->name,
        "last_name"  => "User",
        "email"      => $user->email,
        "phone"      => "01228815901", // رقم تجريبي
      ],
      "redirectionUrls" => [
        "successUrl" => route('visa.wallet.callback'),
        "failUrl"    => route('visa.wallet'),
        "pendingUrl" => route('visa.wallet'),
      ],
      "cartItems" => [
        [
          "name"     => "شحن تأشيرات عدد " . $visaQty,
          "price"    => (string) ($amount / $visaQty),
          "quantity" => (string) $visaQty
        ]
      ],
    ]);

    return $response->json();
  }
}
