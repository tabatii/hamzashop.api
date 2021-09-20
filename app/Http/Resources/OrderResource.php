<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AddressResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'shipping_price' => $this->shipping_price,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'paid_currency' => $this->paid_currency,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'address' => new AddressResource($this->address),
            'product' => [
                'id' => $this->product->id,
                'longTitle' => $this->product->long_title,
                'shortTitle' => $this->product->short_title,
                'image' => $this->product->images[0],
            ],
            'user' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
            ]
        ];
    }
}
