<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'first' => 'required|string|max:100',
            'last' => 'required|string|max:100',
            'street' => 'required|string|max:100',
            'details' => 'string|nullable|max:100',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'zip' => 'required|string|min:5|max:10',
            'mobile' => 'required|string',
        ];
    }
}
