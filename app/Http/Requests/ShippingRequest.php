<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'country' => 'required|string|max:100',
            'price' => 'required|numeric|min:1|max:100000',
            'min' => 'required|integer|min:1|max:30',
            'max' => 'required|integer|min:1|max:60',
        ];
    }
}
