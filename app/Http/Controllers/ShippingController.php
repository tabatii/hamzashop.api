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
        $rates = Shipping::orderBy('region')->get();
        return ShippingResource::collection($rates);
    }

    public function store(ShippingRequest $request)
    {
        $shipping = new Shipping;
        $shipping->region = $request->region;
        $shipping->price = $request->price;
        $shipping->min_time = $request->min;
        $shipping->max_time = $request->max;
        $shipping->save();
        return response()->json();
    }

    public function show($id)
    {
        //
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
