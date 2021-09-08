<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;


class OrderController extends Controller
{
    protected $array = [
        Order::PENDING,
        Order::PACKING,
        Order::SHIPPED,
        Order::ARRIVED,
        Order::RECEIVED,
    ];

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

        return DB::transaction(function () use ($request) {

            $product = Product::findOrFail($request->product);
            $address = Address::findOrFail($request->address);
            $shipping = Shipping::where('country', $address->country)->first();

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

            $product->stock = $product->stock - 1;
            $product->save();

            return response()->json();
        });
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
        //
    }

    public function status($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:'.implode(',', $this->array)
        ]);
        $order = Order::findOrFail($id);
        if ($order->status !== Order::CANCELLED) {
            $order->status = $request->status;
            $order->save();
            return response()->json();
        }
        return response()->json(['message' => 'This order has been cancelled.'], 403);
    }

    public function refuse($id)
    {
        return DB::transaction(function () use ($id) {

            $order = Order::findOrFail($id);
            $order->status = Order::CANCELLED;
            $order->save();

            $product = Product::findOrFail($order->product_id);
            $product->stock = $product->stock + 1;
            $product->save();

            return response()->json();
        });
    }

    public function cancel($id)
    {
        return DB::transaction(function () use ($id) {

            $order = Order::findOrFail($id);
            if ($order->status === Order::PENDING) {
                $order->status = Order::CANCELLED;
                $order->save();

                $product = Product::findOrFail($order->product_id);
                $product->stock = $product->stock + 1;
                $product->save();

                return response()->json();
            }
            return response()->json(['message' => "You can't cancel your order by now."], 403);
        });
    }
}
