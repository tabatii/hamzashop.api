<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'longTitle' => $this->long_title,
            'shortTitle' => $this->short_title,
            'price' => $this->price,
            'stock' => $this->stock,
            'images' => $this->images,
            'description' => $this->description,
            'status' => $this->when(auth()->guard('admin')->check(), $this->status)
        ];
    }
}
