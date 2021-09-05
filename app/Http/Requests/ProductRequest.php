<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'long' => 'required|string|max:190',
            'short' => 'required|string|max:40',
            'price' => 'required|numeric|min:1|max:1000000',
            'stock' => 'required|integer|min:1|max:1000',
            'features' => 'required|array',
            'features.*' => 'required|string|max:190',
            'images' => 'required|array|max:9',
            'images.*' => 'required|array',
            'images.*.*' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
        ];
    }
}
