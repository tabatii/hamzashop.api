<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;

class StatusRequest extends FormRequest
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
        $array = [
            Order::PENDING,
            Order::PACKING,
            Order::SHIPPED,
            Order::ARRIVED,
        ];
        return [
            'status' => 'required|in:'.implode(',', $array)
        ];
    }
}
