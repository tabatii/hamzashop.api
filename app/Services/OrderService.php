<?php

namespace App\Services;

use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Order;

class OrderService
{
    protected $array = [
        Order::CANCELLED,
        Order::RECEIVED,
    ];

    public function status($id, $status)
    {
        $order = Order::findOrFail($id);
        if (!in_array($order->status, $this->array)) {
            $order->status = $status;
            $order->save();
            return true;
        }
        return false;
    }

    public function refuse($id)
    {
        return DB::transaction(function () use ($id) {
            $order = Order::findOrFail($id);
            if (!in_array($order->status, $this->array)) {
                $order->status = Order::CANCELLED;
                $order->save();
                $product = Product::findOrFail($order->product_id);
                $product->stock += 1;
                $product->save();
                return true;
            }
            return false;
        });
    }

    public function finish($id)
    {
        $order = Order::findOrFail($id);
        if (!in_array($order->status, $this->array)) {
            if ($order->payment_method === Order::CASH) {
                $order->paid_amount = $order->total_amount;
                $order->paid_currency = 'MAD';
            }
            $order->status = Order::RECEIVED;
            $order->save();
            $notification = new Notification;
            $notification->icon = 'mdi-cart-check';
            $notification->content = (new NotificationService)->confirmOrder();
            $notification->save();
            return true;
        }
        return false;
    }

    public function cancel($id)
    {
        return DB::transaction(function () use ($id) {
            $order = Order::findOrFail($id);
            if ($order->status === Order::PENDING) {
                $order->status = Order::CANCELLED;
                $order->save();
                $product = Product::findOrFail($order->product_id);
                $product->stock += 1;
                $product->save();
                $notification = new Notification;
                $notification->icon = 'mdi-cart-remove';
                $notification->content = (new NotificationService)->cancelOrder();
                $notification->save();
                return true;
            }
            return false;
        });
    }
}
