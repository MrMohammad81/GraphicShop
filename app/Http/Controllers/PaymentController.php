<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PayRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Services\Payments\PaymentService;
use App\Services\Payments\Requests\IDPayRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function pay(PayRequest $request)
    {
        $validatedData = $request->validated();

        /**************************  CREATE USER ***************************************/
        $user = User::firstOrCreate(['email' => $validatedData['email']],
            [
                'name' => $validatedData['name'],
                'mobile' => $validatedData['mobile'],
                'email' => $validatedData['email'],
            ]);

        /*************************** CREATE ORDER and ORDER_ITEM  ********************/
        try {
            $orderItems = json_decode(Cookie::get('basket') , true);

            $products = Product::findMany(array_keys($orderItems));

            $productPrice = $products->sum('price');

            $refCode = Str::random(20);

            $createdOrder = Order::create([
               'amount' => $productPrice,
               'ref_code' => $refCode,
               'status' => 'unpaid',
                'user_id' => $user->id
            ]);

            $orderItemsForCreateOrder = $products->map(function ($product){
                $currentProduct = $product->only(['id' , 'price']);

                $currentProduct['product_id'] = $currentProduct['id'];

                unset($currentProduct['id']);

                return $currentProduct;
            });
           $createdOrder->orderItems()->createMany($orderItemsForCreateOrder->toArray());

           $refId = rand(11111 , 99999);

           $createPayment = Payment::create([
               'gateway' => 'idpay',
               'ref_id' => $refId,
               'res_id' => $refId,
               'status' => 'unpaid',
               'order_id' => $createdOrder->id
           ]);

           /******************  SEND INFO FOR PAY **************/
            $idPayRequest = new IDPayRequest([
                'amount' => $productPrice,
                'user' => $user,
                'orderId' => $refCode
            ]);

            $paymentService = new PaymentService(PaymentService::IDPAY , $idPayRequest);

            return $paymentService->pay();

        }catch (\Exception $exception)
        {
            return back()->with('failed' , $exception->getMessage());
        }



    }
}
