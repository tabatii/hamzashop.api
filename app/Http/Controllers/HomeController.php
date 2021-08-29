<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::pluck('id')->all();
        return response()->json([
            'usa' => $products[0],
            'spain' => $products[1]
        ]);
    }
}
