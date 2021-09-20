<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Shipping;

class AddressResource extends JsonResource
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
            'name' => $this->first_name.' '.$this->last_name,
            'street' => $this->street,
            'details' => $this->details,
            'country' => $this->country,
            'city' => $this->city,
            'zip' => $this->zip,
            'mobile' => $this->mobile
        ];
    }
}
