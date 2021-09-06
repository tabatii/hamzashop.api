<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;


class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['store', 'show', 'cancel']);
    }

    public function index()
    {
        $orders = Order::with(['product','address','user'])->latest()->get();
        return OrderResource::collection($orders);
    }

    public function store(OrderRequest $request)
    {
        $product = Product::findOrFail($request->product);
        $address = Address::findOrFail($request->address);
        $shipping = Shipping::where('region', $address->country)->first();

        $order = new Order;
        $order->user_id = auth()->id();
        $order->product_id = $product->id;
        $order->address_id = $address->id;
        $order->quantity = $request->quantity;
        $order->unit_price = $product->price;
        $order->shipping_price = $shipping->price;
        $order->total_amount = ($product->price * $request->quantity) + $shipping->price;
        $order->payment_method = Order::CASH;
        $order->status = Order::PENDING;
        $order->save();
        return response()->json();
    }

    public function show($id)
    {
        $orders = Order::where('user_id', auth()->id())->with(['product','address','user'])->latest()->get();
        return OrderResource::collection($orders);
    }

    public function update(OrderRequest $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json();
    }

    public function status($id, Request $request)
    {
        $array = [Order::PENDING, Order::PACKING, Order::SHIPPED, Order::ARRIVED, Order::RECEIVED, Order::CANCELLED];
        $request->validate([
            'status' => 'required|in:'.implode(',', $array)
        ]);
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        return response()->json();
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status === Order::PENDING) {
            $order->status = Order::CANCELLED;
            $order->save();
            return response()->json();
        }
        return response()->json(['message' => "You can't cancel your order by now."], 403);
    }
}
