<?php

namespace App\Http\Controllers\Filters;

use App\Models\Product;

class OrderByFilter
{
    public function newest()
    {
        return Product::orderBy( 'created_at' , 'desc')->get();
    }

    public function defult()
    {
        return Product::all();
    }

    public function lowToHigh()
    {
        return Product::orderBy( 'price' , 'desc')->get();
    }

    public function highToLow()
    {
        return Product::orderBy( 'price' , 'asc')->get();
    }

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
