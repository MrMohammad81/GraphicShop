<?php

namespace App\Http\Controllers\Filters;

use App\Models\Product;

class PriceFilter
{
    public function priceFilter10to100()
    {
        return Product::whereBetween('price' , [10,100]);
    }

    public function priceFilter101to200()
    {
        return Product::whereBetween('price' , [101,200]);
    }

    public function priceFilter201to300()
    {
        return Product::whereBetween('price' , [201,300]);
    }
}
