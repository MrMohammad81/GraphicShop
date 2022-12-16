<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Payments\PaymentService;
use App\Services\Payments\Requests\IDPayRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay()
    {
        $user = User::first();

        $idPayRequest = new IDPayRequest([
            'user' => $user,
            'amount' => 1000
        ]);

        $paymentService = new PaymentService(PaymentService::IDPAY , $idPayRequest);
        dd($paymentService->pay());
    }
}
