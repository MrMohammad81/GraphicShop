<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CheckOutController extends Controller
{
    public function show()
    {
        $products = json_decode(Cookie::get('basket') , true);

        $productPrice = number_format(array_sum(array_column(json_decode(Cookie::get('basket') , true) , 'price')));

        return view('frontend.products.checkout',compact('products' , 'productPrice'));
    }
}
