<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $total = Order::where('status', Order::RECEIVED)->sum('total_amount');
        $shipping = Order::where('status', Order::RECEIVED)->sum('shipping_price');
        return response()->json([
            'sales' => $total - $shipping,
            'orders' => Order::where('status', Order::RECEIVED)->count(),
            'products' => Product::count(),
            'users' => User::count(),
        ]);
    }
}
