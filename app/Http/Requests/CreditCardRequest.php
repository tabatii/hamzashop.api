<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardRequest extends FormRequest
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
            'product' => 'required|integer|exists:products,id',
            'address' => 'required|integer|exists:addresses,id',
            'quantity' => 'required|integer',
            'name' => 'required|string|max:100',
            'card' => 'required|digits_between:15,16',
            'date' => 'required|string|max:4',
            'cvv' => 'required|digits_between:3,4',
            'type' => 'required|string',
        ];
    }
}
