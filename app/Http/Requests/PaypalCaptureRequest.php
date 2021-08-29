<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaypalCaptureRequest extends FormRequest
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
            'order' => 'required|string',
            'product' => 'required|integer|exists:products,id',
            'address' => 'required|integer|exists:addresses,id',
            'quantity' => 'required|integer',
        ];
    }
}
