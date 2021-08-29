<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    const PENDING = 'pending';
    const PAID = 'paid';
    const PACKED = 'packed';
    const SHIPPED = 'shipped';
    const DELIVERED = 'delivered';
    const CANCELLED = 'cancelled';

    const CASH = 'pay on delivery';
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
}
