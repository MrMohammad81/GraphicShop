<?php
namespace App\Http\Controllers;

use App\Mail\SendOrderImages;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Services\Payments\Requests\IDPayVerifyRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Services\Payments\PaymentService;
use App\Http\Requests\Payment\PayRequest;
use App\Services\Payments\Requests\IDPayRequest;

class PaymentController extends Controller
{
    public function pay(PayRequest $request)
    {

        $validatedData = $request->validated();

        $user = User::firstOrCreate([
            'email' => $validatedData['email'],
        ], [
            'name' => $validatedData['name'],
            'mobile' => $validatedData['mobile'],
        ]);

        try {

            $orderItems = json_decode(Cookie::get('basket'), true);

            if (count($orderItems) <= 0) {
                throw new \InvalidArgumentException('سبد خرید شما خالی است');
            }

            $products = Product::findMany(array_keys($orderItems));

            $productsPrice = $products->sum('price');

            $refCode = Str::random(30);

            $createdOrder = $this->setOrder($productsPrice , $user->id , $refCode);

            $this->setOrderItem($products , $createdOrder);

            $this->setPayment($createdOrder,$refCode);

            $idPayRequest = new IDPayRequest([
                'amount' => $productsPrice,
                'user' => $user,
                'orderId' => $refCode,
                'apiKey' => config('services.payment_getaway.id_pay.api_key'),
            ]);

            $paymentService = new PaymentService(PaymentService::IDPAY, $idPayRequest);

            return $paymentService->pay();

        }catch(\Exception $e){
            return back()->with('failed', $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $paymentInfo = $request->all();

        $idPayVerifyRequest = new IDPayVerifyRequest([
            'id' => $paymentInfo['id'],
            'order_id' => $paymentInfo['order_id'],
            'apiKey' => config('services.payment_getaway.id_pay.api_key')
        ]);

        $paymentService = new PaymentService(PaymentService::IDPAY , $idPayVerifyRequest);

        $result = $paymentService->verify();

        if (!$result['status'])
        {
            return redirect()->route('home.checkout.show')->with('failed','پرداخت انجام نشد');
        }
        $result['statusCode'] = 100;
        if ($result['statusCode'] === 101)
        {
            return redirect()->route('home.checkout.show')->with('success','پرداخت قبلا انجام شده و تصاور به ایمیل شما ارسال گردیده است ');
        }

        $currentPayment =  Payment::where('ref_code' , $result['data']['order_id'])->first();

        $currentPayment->update([
            'status' => 'paid',
            'res_id' => $result['data']['track_id']
        ]);
        $currentPayment->order()->update(['status' => 'paid']);

        $reserveImages = $currentPayment->order->orderItems->map(function ($orderItem){
            return $orderItem->product->source_url;
        });

        $currentUser = $currentPayment->order->user;

        //Mail::to($currentUser)->send(new SendOrderImages($reserveImages->toArray() , $currentUser));

        Cookie::queue('basket',null);

        return redirect()->route('home.products.all')->with('success' , 'خرید شما با موفقیت انجام شد و تصاویر به ایمیل شما ارسال شدند');
    }


    
    private function setOrder($price , $user , $ref_code)
    {
       $createOrder =  Order::create([
            'amount' => $price,
            'ref_code' => $ref_code,
            'status' => 'unpaid',
            'user_id' => $user,
        ]);
       return $createOrder;
    }

    private function setOrderItem($product , $createdOrder)
    {
       $product =  $product->map(function ($product) {
            $currentProduct = $product->only(['price', 'id']);

            $currentProduct['product_id'] = $currentProduct['id'];

            unset($currentProduct['id']);

            return $currentProduct;
        });

       $createdOrder = $createdOrder->orderItems()->createMany($product->toArray());
    }

    private function setPayment($createdOrder,$ref_code)
    {
       Payment::create([
            'gateway' => 'idpay',
            'ref_code' => $ref_code,
            'status' => 'unpaid',
            'order_id' => $createdOrder->id,
        ]);

       return $this;
    }
}
