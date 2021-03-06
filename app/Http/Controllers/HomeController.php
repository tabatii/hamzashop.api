<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'public')->oldest()->take(3)->get();
        return ProductResource::collection($products);
    }
}
