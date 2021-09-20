<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    const PENDING = 'pending';
    const PACKING = 'packing';
    const SHIPPED = 'shipped';
    const ARRIVED = 'arrived';
    const RECEIVED = 'received';
    const CANCELLED = 'cancelled';

    const CASH = 'pay on delivery';
    const CARD = 'credit card';
    const PAYPAL = 'paypal';

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('HH:mm, DD MMM, YYYY');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
