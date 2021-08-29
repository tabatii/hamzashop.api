<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;
use App\Services\AmanaService;
use App\Models\Product;
use App\Models\Order;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->with(['product','address'])->latest()->get();
        return response()->json(OrderResource::collection($orders));
    }

    public function store(OrderRequest $request)
    {
        $product = Product::findOrFail($request->product);
        $amana = (new AmanaService)->price($product->weight);

        $order = new Order;
        $order->user_id = auth()->id();
        $order->product_id = $product->id;
        $order->address_id = $request->address;
        $order->quantity = $request->quantity;
        $order->unit_price = $product->price;
        $order->shipping_price = $amana;
        $order->total_amount = ($product->price * $request->quantity) + $amana;
        $order->payment_method = Order::CASH;
        $order->status = Order::PENDING;
        $order->save();
        return response()->json();
    }

    public function show($id)
    {
        //
    }

    public function update(OrderRequest $request, $id)
    {
        //
    }

    public function destroy($id)
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
