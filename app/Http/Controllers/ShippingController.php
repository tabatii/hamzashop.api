<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShippingResource;
use App\Http\Requests\ShippingRequest;
use App\Models\Shipping;

class ShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['index', 'show']);
    }

    public function index()
    {
        $rates = Shipping::orderBy('country')->get();
        return ShippingResource::collection($rates);
    }

    public function store(ShippingRequest $request)
    {
        $shipping = new Shipping;
        $shipping->country = $request->country;
        $shipping->code = $request->code;
        $shipping->price = $request->price;
        $shipping->min_time = $request->min;
        $shipping->max_time = $request->max;
        $shipping->save();
        return response()->json();
    }

    public function show($id)
    {
        $shipping = Shipping::findOrFail($id);
        return new ShippingResource($shipping);
    }

    public function update(ShippingRequest $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $shipping = Shipping::findOrFail($id);
        $shipping->delete();
        return response()->json();
    }
}
