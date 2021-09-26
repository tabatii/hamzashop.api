<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentwallRequest extends FormRequest
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
            'product' => 'required|integer|digits:15|exists:products,id',
            'address' => 'required|integer|digits:15|exists:addresses,id',
            'quantity' => 'required|integer',
            'card' => 'required|digits_between:15,16',
            'date' => 'required|digits:4',
            'cvv' => 'required|digits_between:3,4',
        ];
    }
}
