<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'product' => [
                'id' => $this->product->id,
                'title' => $this->product->title,
                'images' => $this->product->images,
            ],
            'address' => [
                'id' => $this->address->id,
                'name' => $this->address->name,
                'street' => $this->address->street,
                'details' => $this->address->details,
                'country' => $this->address->country,
                'city' => $this->address->city,
                'zip' => $this->address->zip,
                'mobile' => $this->address->mobile,
            ]
        ];
    }
}
