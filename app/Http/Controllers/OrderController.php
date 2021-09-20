<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\StatusRequest;
use App\Http\Resources\OrderResource;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Models\Notification;
use App\Models\Shipping;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['store', 'show', 'finish', 'cancel']);
    }

    public function index()
    {
        $orders = Order::with(['product','address','user'])->latest()->get();
        return OrderResource::collection($orders);
    }

    public function store(OrderRequest $request)
    {

        return DB::transaction(function () use ($request) {

            $address = Address::findOrFail($request->address);
            $shipping = Shipping::where('code', $address->country)->first(); // check if morocco

            $product = Product::findOrFail($request->product);
            $product->stock -= 1;
            $product->save();

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

            $notification = new Notification;
            $notification->icon = 'mdi-cart-plus';
            $notification->content = (new NotificationService)->newOrder();
            $notification->save();

            return response()->json();
        });
    }

    public function show($id)
    {
        $orders = Order::where('user_id', auth()->id())->with(['product','address','user'])->latest()->get();
        return OrderResource::collection($orders);
    }

    public function update(StatusRequest $request, $id)
    {
        $service = new OrderService;
        if ($service->status($id, $request->status)) {
            return response()->json();
        }
        return response()->json([], 403);
    }

    public function destroy($id)
    {
        //
    }

    public function refuse($id)
    {
        $service = new OrderService;
        if ($service->refuse($id)) {
            return response()->json();
        }
        return response()->json([], 403);
    }

    public function finish($id)
    {
        $service = new OrderService;
        if ($service->finish($id)) {
            return response()->json();
        }
        return response()->json([], 403);
    }

    public function cancel($id)
    {
        $service = new OrderService;
        if ($service->cancel($id)) {
            return response()->json();
        }
        return response()->json([], 403);
    }
}
